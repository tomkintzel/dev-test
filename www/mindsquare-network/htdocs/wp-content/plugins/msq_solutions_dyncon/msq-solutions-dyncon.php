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
 * @since 1.0.0
 * @package msq_solutions_dyncon
 *
 * @wordpress-plugin
 * Plugin Name: MSQ - Solutions DynCon
 * Plugin URI: https://mindsquare.de
 * Description: Dieses Plug-In fügt bei den Angebots-Seiten ein DynCon hinzu.
 * Version: 1.0.0
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
require plugin_dir_path( __FILE__ ) . 'includes/class-msq-solutions-dyncon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_msq_solutions_dyncon() {
	$plugin = new Msq_Solutions_Dyncon();
	$plugin->run();
}
run_msq_solutions_dyncon();
?>
