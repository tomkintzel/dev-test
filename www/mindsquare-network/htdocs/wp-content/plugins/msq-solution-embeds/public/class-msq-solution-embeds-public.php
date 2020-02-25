<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link https://mindsquare.de
 * @since 1.0.0
 *
 * @package msq_responsive_video
 * @subpackage msq_responsive_video/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package msq_responsive_video
 * @subpackage msq_responsive_video/public
 * @author Andrej Genschel <genschel@mindsquare.de>
 */
class Msq_Solution_Embeds_Public {
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
	 * Diese Funktion wird beim ausführen des 'msq-plugin-responsive-video'-
	 * Shortcodes ausgeführt. In dieser Funktion wird mit der Hilfe eines
	 * Templates, HTML-Code für das Video erzeugt.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes in shortcode tag.
	 * @param string $content Content to search for shortcodes.
	 * @param string $tag Shortcode name.
	 * @return string Content with shortcodes filtered out.
	 */
	public function msq_solution_embeds_shortcode( $atts, $content, $tag ) {
		wp_enqueue_style( 
			$this->plugin_name, plugin_dir_url( __FILE__ ) . 
			'css/msq-solution-embeds-public.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 
			'css/msq-solution-embeds-public.css' ), 'all'
		);
		$solution_id = $atts['id'];
		$displaySetting = $atts['display_setting'];
		$baseClass = 'SolutionEmbed';
		$class     = $baseClass;
		$class .= sprintf( ' %s_%s', $baseClass, $displaySetting );

		$template = plugin_dir_path( __FILE__ ) . 'partial/msq-solution-embeds-content.php';
		if( file_exists( $template ) ) {
			extract( shortcode_atts( array(
				'id'              	=> null,
				'display_setting' 	=> 'small',
				'quote_text'		=> null,
				'button_text'		=> 'Zum Angebot',
				'headline'			=> null,
				'body'				=> null
			), $atts, 'solution' ) );

			// Lade das Template
			ob_start();
			require( $template );
			return ob_get_clean();
		}
		return "";
	}
}
?>
