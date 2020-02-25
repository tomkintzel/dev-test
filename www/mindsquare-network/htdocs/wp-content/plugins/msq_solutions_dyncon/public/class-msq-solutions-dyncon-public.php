<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link https://mindsquare.de
 * @since 1.0.0
 *
 * @package msq_solutions_dyncon
 * @subpackage msq_solutions_dyncon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package msq_solutions_dyncon
 * @subpackage msq_solutions_dyncon/public
 * @author Andrej Genschel <genschel@mindsquare.de>
 */
class Msq_Solutions_Dyncon_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The ID of this plugin.
	 * @param string $version The current version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		if( is_singular( array( 'solutions' ) ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/msq-solutions-dyncon-public.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/msq-solutions-dyncon-public.css' ), 'all' );
		}
	}

	/**
	 * Register the sidebar for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function register_sidebar() {
		register_sidebar( array(
			'name' => __( 'Angebot Sidebar', 'starkers' ),
			'id' => 'msq-solutions-dyncon-widget-area',
			'description' => __( 'Widgets auf der rechten Seite von Angeboten', 'starkers' ),
			'before_widget' => '<div id="%1$s" class="widget dyncon %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		) );
	}
}
?>
