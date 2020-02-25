<?php

/*
* Plugin Name: MSQ - Related Posts View
* Plugin URI: mindsquare.de
* Description: Zeigt die Related Posts
* Version: 1.0
* Author: Tom Kinztel
* Author URI:  gitlab.com/tom.kin
* 
* @package msq-related-post-view
* 
*
*/

if ( !defined( 'WPINC' ) ) {
	die;
}

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-msq-related-post-view-table.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-msq-related-post-view-inst.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/none/class-msq-related-post-view-table-none.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/none/class-msq-related-post-view-none.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/switch/class-msq-related-post-view-table.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/switch/class-msq-related-post-view-inst.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/none_switch/class-msq-related-post-view-none-switch.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/none_switch/class-msq-related-post-view-table-none-switch.php';
}
