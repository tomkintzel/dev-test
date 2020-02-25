<?php
/**
 * The admin-specific functionality of the plugin.
 * 
 * @since      1.0.0
 *
 * @package    Ask_The_Author
 * @subpackage Ask_The_Author/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Ask_The_Author
 * @subpackage Ask_The_Author/admin
 * @author     Edwin Eckert <eckert@mindsquare.de>
 */
class Ask_The_Author_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add new Menu Element "Frage den Autoren - Einstellungen"
	 *
	 * @since    1.0.0
	 */
	public function add_menu_page() {
		
		acf_add_options_sub_page( array(
			'page_title'			=>	'Frage den Autoren - Einstellungen', 
			'menu_title'			=>	'Frage den Autoren', 
			'parent_slug'			=>	'options-general.php',
			'menu_slug'				=>	'frage-den-autoren',
			'capability'			=>	'manage_options'
			) 
		);

	}

	/**
	 * Add new Field Pardot for Settings Page
	 *
	 * @since    1.0.0
	 */
	public function register_field_pardot_on_options_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/assets/acf-pardot-field.php';

	}

}
