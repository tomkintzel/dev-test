<?php
    /**
     *  Plugin Name: WPO365 Login (professional)
     *  Plugin URI: https://www.wpo365.com/downloads/wordpress-office-365-login-professional/
     *  Description: Wordpress + Office 365 login allows Microsoft O365 users to seamlessly and securely log on to your corporate Wordpress intranet. The plugin will create a Wordpress user for each corporate user when logged on to Office 365 and thus avoiding the default Wordpress login screen: No username or password required.
     *  Version: 9.4
     *  Author: marco@wpo365.com
     *  Author URI: https://www.wpo365.com
     *  License: See license.txt
     */
    
    namespace Wpo;

    // Prevent public access to this script
    defined( 'ABSPATH' ) or die( );

    $GLOBALS[ 'WPO365_VERSION_KEY' ]             = 'PLUGIN_VERSION_wpo365_login_professional';
    $GLOBALS[ $GLOBALS[ 'WPO365_VERSION_KEY' ] ] = '9.4';
    $GLOBALS[ 'WPO365_PLUGIN' ]                  = plugin_basename( __FILE__ );
    $GLOBALS[ 'WPO365_PLUGIN_DIR' ]              = __DIR__;
    $GLOBALS[ 'WPO365_PLUGIN_URL' ]              = plugin_dir_url( __FILE__ );
    $GLOBALS[ 'WPO365_PLUGIN_FILE' ]             = __FILE__;
    $GLOBALS[ 'WPO365_SLUG' ]                    = 'wpo365-login-professional';
    $GLOBALS[ 'WPO365_STORE' ]                   = 'https://www.wpo365.com/';
    $GLOBALS[ 'WPO365_STORE_ITEM' ]              = 'WordPress + Office 365 login professional';
    $GLOBALS[ 'WPO365_STORE_ITEM_ID' ]           = 2757;

    require __DIR__ . '/vendor/autoload.php';

    \Wpo\Util\Helpers::ensure_url_info_cache();
    \Wpo\Wpo365_Login_Professional::getInstance();
    