<?php

/**
 * The public-facing functionality of the plugin.
 * @since      1.0.0
 *
 * @package    Ask_The_Author
 * @subpackage Ask_The_Author/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ask_The_Author
 * @subpackage Ask_The_Author/public
 * @author     Edwin Eckert <eckert@mindsquare.de>
 */
class Ask_The_Author_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( is_single() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ask-the-author-public.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/ask-the-author-public.css' ), 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( is_single() ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ask-the-author-public.js', array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Adds the HTML container to ask the author after the content of a post.
	 *
	 * @since    1.0.0
	 */
	public function insert_ask_the_author_content( $content ) {
		// Da der the_content Filter auch bei get_the_excerpt ausgeführt wird, muss sichergestellt werden, dass wenn es sich nicht um die Hauptquery handelt, der Autor nicht 
		// bei dem Excerpt hinzugefügt wird. Leider funktioniert die Funktion is_main_query() hier nicht, da die Posts mittels get_post geholt werden und get_post nur 
		// ein Post-Objekt aufsetzt, nicht aber eine neue WP_Query erstellt.
		global $wp_the_query, $post;
		if( !empty( $wp_the_query->queried_object->ID ) && !empty( $post->ID ) && $wp_the_query->queried_object->ID == $post->ID && is_singular( 'post' ) ) {

			$pardot_form_id = get_field( 'pardot-ask-author', 'option' );

			if ( get_the_author_meta( 'description' , $post->post_author ) && !empty( $pardot_form_id ) ) {

				$email = get_the_author_meta('user_email');
				$alt = get_the_author_meta('display_name');

				$author_link = get_the_author_with_authordetails_link( $post->post_author );

				$url_args = array(
					'Post_Title'		=>	get_the_title(),
					'Berater_zum_Beitrag'	=>	get_the_author_meta( 'display_name' ),
					'eventCategory'		=> 'AskAuthor',
					'eventLabel'		=> get_the_title()
				);

				$pardot_form = $this->get_pardot_form( $pardot_form_id, $url_args );

				ob_start();
				require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/ask-the-author-content.php';
				return $content . ob_get_clean();

			}
		}

		return $content ;

	}


	/**
	 * Gibt das Pardot Formular in HTML zurück
	 *
	 * @since 	1.0.0
	 */
	public function get_pardot_form( $pardot_form_id, $url_args = array(), $height = '300px' ) {

		if( class_exists( 'Pardot_Plugin' ) ) {

			$querystring = http_build_query( $url_args );

			$args = array(
				'form_id'		=>	$pardot_form_id,
				'height'		=> 	$height,
				'querystring'	=>	$querystring
			);

			return msq_get_pardot_form( $args );

		}

		return false;
	}



}
