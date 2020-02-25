<?php

    namespace Wpo\Util;

    // Prevent public access to this script
    defined( 'ABSPATH' ) or die();

    use \Wpo\Util\Options;
    use \Wpo\Aad\Auth;

    if( !class_exists( '\Wpo\Util\Error_Handler' ) ) {

        class Error_Handler {

            const NOT_CONFIGURED    = 'NOT_CONFIGURED';
            const CHECK_LOG         = 'CHECK_LOG';
            const TAMPERED_WITH     = 'TAMPERED_WITH';
            const USER_NOT_FOUND    = 'USER_NOT_FOUND';
            const NOT_IN_GROUP      = 'NOT_IN_GROUP';
            const ID_TOKEN_ERROR    = 'ID_TOKEN_ERROR';
            const BASIC_VERSION     = 'BASIC_VERSION';
            const DUAL_LOGIN        = 'DUAL_LOGIN';
            const DUAL_LOGIN_V2     = 'DUAL_LOGIN_V2';
            const AADAPPREG_ERROR   = 'AADAPPREG_ERROR';

            /**
             * Checks for errors in the login messages container and display and unset immediatly after if any
             *
             * @since   1.0
             * @return  void
             */
            public static function check_for_login_messages() {

                if( !isset( $_GET[ 'login_errors' ] ) ) {
                    return;
                }

                // Using $_GET here since wp_query is not loaded on login page
                $login_error_codes = $_GET[ 'login_errors' ];

                $result = '';

                foreach( explode( ',', $login_error_codes ) as $login_error_code ) {

                    $error_message = self::get_error_message( $login_error_code );

                    if( empty( $error_message ) ) {
                        continue;
                    }

                    $result .= '<p class="message">' . $error_message . '</p><br />';
                }
                
                // Return messages to display to hook
                return $result;
            }

            /**
             * Tries to get an error message for the error code provided either from
             * the options or else from the hard coded backup dictionary provided.
             * 
             * @since 0.1
             * 
             * @param string $error_code Error code
             * @return string Error message
             */
            public static function get_error_message( $error_code ) {

                $error_messages = Array(
                    self::NOT_CONFIGURED    => __( 'Wordpress + Office 365 login not configured yet. Please contact your System Administrator.' ),
                    self::CHECK_LOG         => __( 'Please contact your System Administrator and check log file.' ),
                    self::TAMPERED_WITH     => __( 'Your login might be tampered with. Please contact your System Administrator.' ),
                    self::USER_NOT_FOUND    => __( 'Could not create or retrieve your login. Please contact your System Administrator.' ),
                    self::NOT_IN_GROUP      => __( 'Access Denied. Please contact your System Administrator.' ),
                    self::ID_TOKEN_ERROR    => __( 'Your ID token could not be processed. Please contact your System Administrator.' ),
                    self::BASIC_VERSION     => __( 'The BASIC edition of the WordPress + Office 365 plugin does not automatically create new users. See the following <a href="https://www.wpo365.com/basic-edition/">online documentation</a> for more info.' ),
                    self::DUAL_LOGIN        => __( 'Alternatively, you can click the following link to sign into this website with your corporate <a href="__##OAUTH_URL##__">network login (Office 365)</a>' ),
                    self::DUAL_LOGIN_V2     => __( 'Alternatively, you can click the following link to sign into this website with your corporate <span class="wpo365-dual-login-notice" style="cursor: pointer; text-decoration: underline; color: #000CD" onclick="window.wpo365.pintraRedirect.toMsOnline()">network login (Office 365)</span>' ),
                    self::AADAPPREG_ERROR   => __( 'Could not create or retrieve your login. Most likely the authentication response received from Microsoft does not contain an email address. Consult the <a target="_blank" href="https://www.wpo365.com/troubleshooting-the-wpo365-login-plugin/#PARSING_ERROR">online documentation</a> for details.' ),
                );

                $error_message = Options::get_global_string_var( 'wpo_error_' . strtolower( $error_code ) );
                
                if( empty( $error_message ) )
                    $error_message = !empty( $error_messages[ $error_code ] )
                        ? $error_messages[ $error_code ]
                        : '';
                
                // Optionally replace template tokens when error is DUAL_LOGIN or DUAL_LOGINV2
                if( stripos( $error_code, 'DUAL_LOGIN' ) === 0 ) {

                    if( Options::get_global_boolean_var( 'hide_sso_link' ) ) {
                        return '';
                    }

                    $site_url = $GLOBALS[ 'WPO365_URL_INFO_CACHE' ][ 'wp_site_url' ];

                    ob_start();
                    include( $GLOBALS[ 'WPO365_PLUGIN_DIR' ] . '/templates/openid-duallogin.php' );
                    $content = ob_get_clean();
                    echo $content;

                    if( false !== stripos( $error_message, '__##OAUTH_URL##__' ) ) {
                        $redirect_to = !empty( $_GET[ 'redirect_to' ] )
                            ? strtolower( trim( $_GET[ 'redirect_to' ] ) )
                            : null;
                        $oauth_url = Auth::get_oauth_url( null, $redirect_to );
                        $error_message = str_replace( "__##OAUTH_URL##__", $oauth_url, $error_message );
                    }
                }

                return $error_message;
            }
        }
    }

?>