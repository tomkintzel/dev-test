<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mindsquare.de
 * @since             1.0.0
 * @package           msq-solution-embeds
 *
 * @wordpress-plugin
 * Plugin Name:       MSQ - Angebot Embeds
 * Plugin URI:        https://mindsquare.de
 * Description:       Durch dieses Plugin können Angebote über den Editor in ein Beitrag hinzugefügt werden.
 * Version:           1.0.0
 * Author:            Tom Kintzel
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
//define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-msq-solution-embeds.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_msq_solution_embeds() {

	$plugin = new Msq_Solution_Embeds();
	$plugin->run();

}
run_msq_solution_embeds();
