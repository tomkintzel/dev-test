<?php
/*
 * 
 * @since             1.0.0
 * @package           Ask_The_Author
 * 
 * Plugin Name: Frage den Autoren
 * Plugin URI: http://mindsquare.de
 * Description: Ein Plugin, welches es ermöglicht einen Autoren unterhalb eines Beitrages anzuzeigen und diesem mittels Pardot eine Frage zu stellen.
 * Version: 1.0.0
 * Author: mindsquare
 * Text Domain:       ask-the-author
 * Domain Path:       /languages
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ask-the-author.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ask_the_author() {

	$plugin = new Ask_The_Author();
	$plugin->run();

}

run_ask_the_author();


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_msq_ask_the_author( $network_wide ) {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ask-the-author-activator.php';

	Ask_The_Author_Activator::activate( $network_wide );

}

register_activation_hook( __FILE__, 'activate_msq_ask_the_author' );
