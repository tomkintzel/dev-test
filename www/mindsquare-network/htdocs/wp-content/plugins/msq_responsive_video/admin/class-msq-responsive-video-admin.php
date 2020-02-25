<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link https://mindsquare.de
 * @since 1.0.1
 *
 * @package msq_responsive_video
 * @subpackage msq_responsive_video/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package msq_responsive_video
 * @subpackage msq_responsive_video/admin
 * @author Andrej Genschel <genschel@mindsquare.de>
 */
class Msq_Responsive_Video_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.1
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.1
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.1
	 * @param string $plugin_name The ID of this plugin.
	 * @param string $version The current version of this plugin
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.1
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'css-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array(), '4.7.0' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/msq-responsive-video-admin.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/msq-responsive-video-admin.css' ), 'all' );
	}

	/**
	 * Eine zusätzliche JavaScript-Datei wird zum TinyMCE-Editor hinzugefügt.
	 *
	 * @since 1.0.1
	 * @param string[] $plugins Eine Liste von registrierten JavaScript-Dateien.
	 * @return $string[] Eine Liste von registrierten JavaScript-Dateien.
	 */
	function register_external_plugin( $plugins ) {
		$plugins[ 'msq_responsive_video' ] = plugin_dir_url( __FILE__ ) . 'js/msq-responsive-video-admin.js';
		return $plugins;
	}

	/**
	* Register new button in TinyMCE-Editor.
	*
	* @since 1.0.1
	* @param string[] $buttons Eine Liste von registrierten Buttons
	* im TinyMCE-Editor.
	* @return $string[] Eine Liste von registrierten Buttons
	* im TinyMCE-Editor.
	*/
	public function register_mce_button( $buttons ) {
		array_push( $buttons, 'msq_plugin_responsive_video_button' );
		return $buttons;
	}
}
?>
