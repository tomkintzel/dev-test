<?php
define('WP_CACHE', true); // Added by WP Rocket

/** Begin: Mindsquare */
define( 'PB_BACKUPBUDDY_MULTISITE_EXPERIMENT', true );
define( 'PODLOVE_DISABLE_TAG_AND_CATEGORY_SEARCH', true );
define( 'WP_ALLOW_MULTISITE', true );
define( 'WPO_MU_USE_SUBSITE_OPTIONS', true );
/** End: Mindsquare */

/** 
 * The base configurations of the WordPress.
 *
 **************************************************************************
 * Do not try to create this file manually. Read the README.txt and run the 
 * web installer.
 **************************************************************************
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mindsquare-network');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'db');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('SUBDOMAIN_INSTALL', false ); 
$base = '/';
define('DOMAIN_CURRENT_SITE', 'blog.mindsquare.de' );
define('PATH_CURRENT_SITE', '/' );
define('SITE_ID_CURRENT_SITE', 1);
define('BLOGID_CURRENT_SITE', '1' );
define('WP_ALLOW_REPAIR', true);

/* Uncomment to allow blog admins to edit their users. See http://trac.mu.wordpress.org/ticket/1169 */
//define( "EDIT_ANY_USER", true );
/* Uncomment to enable post by email options. See http://trac.mu.wordpress.org/ticket/1084 */
//define( "POST_BY_EMAIL", true );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/wpmu/salt WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'ad5e2199659d25984f6d83c091b4bd6419fc0b77a60cefea2b7183099294b031');
define('SECURE_AUTH_KEY', 'c4dc3d14bccdcdfaa96b2374fc764c2afac989aa45ecb9039c7857b66b47aabf');
define('LOGGED_IN_KEY', '9ea39823d043820e1bdb64d30e3c74aec4770694b160f39be39a1c925941131c');
define('NONCE_KEY', '673933e47837848753e2b2d505940dc4905926b961b4df3758402ddcfafc6b7b');
define('AUTH_SALT', '8f725610c3b63ed58f529ec4ee72ade5c78c42cb4bf5f6a013303ff72b4789cd');
define('LOGGED_IN_SALT', 'a19c32f1dd542ca00db62d97a9c49d024eb9cf22570309f5f3fa419df4048a62');
define('SECURE_AUTH_SALT', '6211fa6ee42fa228447c6e21d3509619c5e48ad069e83c43ec4016157402e4c3');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'de_DE');

// double check $base
if( $base == 'BASE' )
	die( 'Problem in wp-config.php - $base is set to BASE when it should be the path like "/" or "/blogs/"! Please fix it!' );

// uncomment this to enable WP_CONTENT_DIR/sunrise.php support
define( 'SUNRISE', 'on' );

// uncomment to move wp-content/blogs.dir to another relative path
// remember to change WP_CONTENT too.
// define( "UPLOADBLOGSDIR", "fileserver" );

// If VHOST is 'yes' uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, the browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
// Set this value to %siteurl% to redirect to the root of the site
// define( 'NOBLOGREDIRECT', '' );
// On a directory based install you must use the theme 404 handler.

// Location of mu-plugins
// define( 'WPMU_PLUGIN_DIR', '' );
// define( 'WPMU_PLUGIN_URL', '' );
// define( 'MUPLUGINDIR', 'wp-content/mu-plugins' );

define( "WP_USE_MULTIPLE_DB", false );
define( 'NONCE_SALT', 'RY$/pb+!=3E;|Sa!tI(}q=HaRq$B[+9Tz=`LC,#Y:C=x$DAt7O[2/FIU=E5TIpg}' );

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true ); 
define( 'WP_MEMORY_LIMIT', '256M' );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
