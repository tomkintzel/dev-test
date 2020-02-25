<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link https://mindsquare.de
 * @since 1.0.0
 *
 * @package msq-request-embeds
 * @subpackage msq-request-embeds/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package msq-request-embeds
 * @subpackage msq-request-embeds/admin
 * @author Tom Kintzel
 */
class Msq_Request_Embeds_Admin {
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
	 * @param string $version The current version of this plugin
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'css-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array(), '4.7.0' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/msq-request-embeds-admin.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/msq-request-embeds-admin.css' ), 'all' );
	}

	/**
	 * Eine zusätzliche JavaScript-Datei wird zum TinyMCE-Editor hinzugefügt.
	 *
	 * @since 1.0.0
	 * @param string[] $plugins Eine Liste von registrierten JavaScript-Dateien.
	 * @return $string[] Eine Liste von registrierten JavaScript-Dateien.
	 */
	function register_external_plugin( $plugins ) {
		$plugins[ 'msq-request-embeds' ] = plugin_dir_url( __FILE__ ) . 'js/msq-request-embeds-admin.js';
		return $plugins;
	}

	/**
	* Register new button in TinyMCE-Editor.
	*
	* @since 1.0.0
	* @param string[] $buttons Eine Liste von registrierten Buttons
	* im TinyMCE-Editor.
	* @return $string[] Eine Liste von registrierten Buttons
	* im TinyMCE-Editor.
	*/
	public function register_mce_button( $buttons ) {
		array_push( $buttons, 'msq_plugin_request_embeds_button' );
		return $buttons;
    }
    
    /**
     * Holt sich alle Anfragen und übergibt sie per ajax, somit sie in der Lightbox ausgeben
     * werden kann.
     *
     * @since 1.0.0
     */
/*     public function get_requests() {

		$requests = get_posts( [
            'posts_per_page' => -1,
            'post_type'      => 'requests',
            'post_status'    => 'publish'
        ] );

		if ( !empty( $requests ) ) :
			$result = [];
			foreach ( $requests as $request) :
				$result[ $request->ID ] = $request->post_title;
            endforeach;
			wp_send_json( $result );
		endif;
		wp_die();
	} */
}
?>
