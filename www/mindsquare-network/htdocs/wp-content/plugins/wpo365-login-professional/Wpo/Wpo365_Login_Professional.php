<?php

    namespace Wpo;

    // Prevent public access to this script
    defined( 'ABSPATH' ) or die();

    use \Wpo\Aad\Auth;
    use \Wpo\Pages\Wizard_Page;
    use \Wpo\Util\Logger;
    use \Wpo\Util\Helpers;
    use \Wpo\Util\Options;
    
    if( !class_exists( '\Wpo\Wpo365_Login_Professional' ) ) {

        class Wpo365_Login_Professional {

            private static $instance;

            private $updateChecker;

            public static function getInstance() {
                
                if( empty( self::$instance ) )
                    self::$instance = new Wpo365_Login_Professional();
            }

            private function __construct() {
                $this->on_register();
                $this->add_actions();
                $this->add_filters();
            }

            /**
             * Plugin activation stuff
             * 
             * @since 4.0
             * 
             * @return void
             */
            public function on_register() {
                // Test und disable if found the freemium version
                register_activation_hook( $GLOBALS[ 'WPO365_PLUGIN_FILE' ], function() {
                    
                    if ( false === function_exists( 'get_plugins' ) )
                        require_once ABSPATH . 'wp-admin/includes/plugin.php';
                
                    $plugin_id = 'wpo365-login/wpo365-login.php';

                    if( is_plugin_active( $plugin_id ) )
                        deactivate_plugins( dirname( $GLOBALS[ 'WPO365_PLUGIN_DIR' ] ) . '/' . $plugin_id );
                } );
            }

            /**
             * Called from the plugins_loaded action.
             * 
             * @since 4.0
             * 
             * @return void
             */
            public function init() {
                // Start validating the session as soon as all plugins are loaded
                Auth::validate_current_session();

                // Do super admin stuff
                if( is_admin() && is_super_admin() ) {

                    // Check plugin version
                    Helpers::check_version();
                }
            }

            /**
             * Add all WP actions
             * 
             * @since 4.0
             * 
             * @return void
             */
            private function add_actions() {
                // Add and hide wizard (page)
                add_action( 'admin_menu', '\Wpo\Pages\Wizard_Page::add_management_page' );
                add_action( 'network_admin_menu', '\Wpo\Pages\Wizard_Page::add_management_page' );

                // Ensure session is valid and remains valid
                add_action( 'destroy_wpo365_session', '\Wpo\Aad\Auth::destroy_session' );

                // Prevent email address update
                add_action( 'personal_options_update', '\Wpo\User\User_Manager::prevent_email_change', 10, 1 );

                add_action( 'personal_options_update', '\Wpo\User\User_Details::save_user_details', 10, 1 );
                add_action( 'edit_user_profile_update', '\Wpo\User\User_Details::save_user_details', 10, 1 );

                // Prevent WP default login for O365 accounts
                add_action( 'wp_authenticate', '\Wpo\Aad\Auth::prevent_default_login_for_o365_users', 11, 1 );

                // Show admin notification when WPO365 not properly configured
                add_action( 'admin_notices', '\Wpo\Util\Helpers::show_admin_notices', 10, 0 );
                add_action( 'network_admin_notices', '\Wpo\Util\Helpers::show_admin_notices', 10, 0 );
                add_action( 'admin_init', '\Wpo\Util\Helpers::dismiss_admin_notices', 10, 0 );

                // Add short code(s)
                add_action( 'init', 'Wpo\Util\Helpers::ensure_pintra_short_code' );
                add_action( 'init', 'Wpo\Util\Helpers::ensure_display_error_message_short_code' );

                // Logout from O365
                add_action( 'wp_logout', '\Wpo\Aad\Auth::logout_O365', 1, 0 );

                // update the WP_User's roles with mapped AAD groups and the configured default tole
                add_action( 'wpo_update_user_roles', '\Wpo\User\User_Manager::update_user_roles', 10, 2 );

                // Wire up AJAX backend services
                add_action( 'wp_ajax_get_tokencache', '\Wpo\API\Services::get_tokencache' );
                add_action( 'wp_ajax_delete_tokens', '\Wpo\API\Services::delete_tokens' );
                add_action( 'wp_ajax_get_settings', '\Wpo\API\Services::get_settings' );
                add_action( 'wp_ajax_update_settings', '\Wpo\API\Services::update_settings' );
                add_action( 'wp_ajax_activate_license', '\Wpo\API\Services::activate_license' );
                add_action( 'wp_ajax_get_log', '\Wpo\API\Services::get_log' );

                // Configure the options
                add_action( 'plugins_loaded', array( $this, 'init' ), 1, 0 );                

                // Flush log on shutdown
                add_action( 'shutdown', '\Wpo\Util\Logger::flush_log' );

                // Check for updates
                add_action( 'admin_init', '\Wpo\Util\Helpers::check_for_updates' );

                // Add pintraredirectjs
                add_action( 'wp_enqueue_scripts', '\Wpo\Util\Helpers::enqueue_pintra_redirect', 10, 0 );
                add_action( 'login_enqueue_scripts', '\Wpo\Util\Helpers::enqueue_pintra_redirect', 10, 0 );
                add_action( 'admin_enqueue_scripts', '\Wpo\Util\Helpers::enqueue_pintra_redirect', 10, 0 );

                // Logout without confirmation
                add_action( 'check_admin_referer', 'Wpo\Util\Helpers_Premium::logout_without_confirmation', 10, 2 );
            }

            /**
             * Add all WP filters
             * 
             * @since 4.0
             * 
             * @return void
             */
            private function add_filters() {
                // Only allow password changes for non-O365 users and only when already logged on to the system
                add_filter( 'show_password_fields',  '\Wpo\User\User_Manager::show_password_fields', 10, 2 );
                add_filter( 'allow_password_reset', '\Wpo\User\User_Manager::allow_password_reset', 10, 2 );
                    
                // Enable login message output
                add_filter( 'login_message', '\Wpo\Util\Error_Handler::check_for_login_messages' );

                // Add custom wp query vars
                add_filter( 'query_vars', '\Wpo\Util\Helpers::add_query_vars_filter' );

                // Show settings link
                add_filter( ( is_network_admin() ? 'network_admin_' : '' ) . 'plugin_action_links_' . $GLOBALS[ 'WPO365_PLUGIN' ], 
                    '\Wpo\Util\Helpers::get_configuration_action_link', 10, 1 );

                // Action to create new WP user
                add_filter( 'wpo_add_user', '\Wpo\User\User_Manager_Premium::add_user', 10, 1 );

                // Filter to update the redirect url
                add_filter( 'wpo_redirect_url', 'Wpo\Util\Helpers_Premium::get_redirect_url', 10, 2 );

                // Filter to change the new user email notification
                add_filter( 'wp_new_user_notification_email', 'Wpo\Util\Notifications::new_user_notification_email', 99, 3 );
            }
        }
    }