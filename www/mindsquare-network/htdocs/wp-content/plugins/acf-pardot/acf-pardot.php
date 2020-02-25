<?php

/*
Plugin Name: Advanced Custom Fields: Pardot-Formulare
Description: Es wird ein neuer Feldtyp "Pardot-Formulare" angelegt, um in ACF Felder für Pardot zu realisieren.
Version: 1.0.0
Author: Edwin Eckert
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_plugin_pardot') ) :

class acf_plugin_pardot {
	
	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
		
		// vars
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);
		
		
		// set text domain
		// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
		load_plugin_textdomain( 'acf-pardot', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
		
		
		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field_types')); // v5
		
	}
	
	
	/*
	*  include_field_types
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	n/a
	*/
	
	function include_field_types( $version = false ) {
				
		// include
		include_once('fields/acf-pardot.php');
		
	}
	
}


// initialize
new acf_plugin_pardot();


// class_exists check
endif;
	
?>