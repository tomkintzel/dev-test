<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link https://mindsquare.de
 * @since 1.0.1
 * @package msq_responsive_video
 *
 * @wordpress-plugin
 * Plugin Name: MSQ - Responsive Video
 * Plugin URI: https://mindsquare.de
 * Description: Dieses Plug-In bettet ein Responsive-Video in eine Seite mittels eines Shortcodes hinzu.
 * Version: 1.0.1
 * Author: Andrej Genschel <genschel@mindsquare.de>
 */

// If this file is called directly, abort.
if( !defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-msq-responsive-video.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.1
 */
function run_msq_responsive_video() {
	$plugin = new Msq_Responsive_Video();
	$plugin->run();
}
run_msq_responsive_video();
?>
