<?php
/**
 */
final class Msq_Theme_Mindsquare {
	/**
	 * @access public
	 * @var string $version
	 */
	public $version;

	/**
	 * @access public
	 * @var Msq_Loader $loader
	 */
	public $loader;

	/**
	 * @access public
	 */
	public function __construct() {
		$this->version = '2.8.0';
	}

	/**
	 * @access public
	 */
	public function init() {
		$this->load_dependencies();
		$this->define_public_hooks();
	}

	/**
	 * @access private
	 */
	private function load_dependencies() {
		require_once( 'class-loader.php' );
		require_once( 'class-post-type.php' );
		require_once( 'class-taxonomy.php' );
		require_once( 'class-taxonomy-single-term.php' );
		require_once( 'class-navigation-walker-nav-menu.php' );

		$this->loader = new Msq_Loader();
		$this->load_post_types();
		$this->load_advanced_custom_fields();
	}

	/**
	 * @access private
	 */
	private function load_post_types() {
		// Lade alle Dateien vom 'include'-Ordner
		$dirname = dirname( __FILE__ ) . '/post_types';
		if( is_dir( $dirname ) ) {
			$filenames = scandir( $dirname );
			$classes = array();

			foreach( $filenames as $filename ) {
				if( !is_dir( $filename ) && preg_match( '/^class-([^\.]+)\.php$/i', $filename, $match ) ) {
					$classes[ 'msq_' . str_replace( '-', '_', $match[ 1 ] ) ] = $filename;
				}
			}

			$classes = apply_filters( 'msq/include_classes', $classes );
			foreach( $classes as $class => $filename ) {
				if( $dirname . '/' . $filename ) {
					require_once( $dirname . '/' . $filename );
				}
			}

			$classes = apply_filters( 'msq/init_classes', $classes );
			foreach( $classes as $classname => $filename ) {
				if( class_exists( $classname ) ) {
					new $classname();
				}
			}
		}
	}

	/**
	 * @access private
	 */
	private function load_advanced_custom_fields() {
		$dirname = dirname( __FILE__ ) . '/acf';
		if( is_dir( $dirname ) ) {
			$filenames = scandir( $dirname );
			$acfs = array();

			foreach( $filenames as $filename ) {
				if( !is_dir( $filename ) && preg_match( '/^([^\.]+)\.php$/i', $filename, $match ) ) {
					$acfs[ $match[ 1 ] ] = $filename;
				}
			}

			$acfs = apply_filters( 'msq/include_acf', $acfs );
			foreach( $acfs as $filename ) {
				if( $dirname . '/' . $filename ) {
					require_once( $dirname . '/' . $filename );
				}
			}
		}
	}

	/**
	 * @access private
	 */
	private function define_public_hooks() {
		$this->loader->add_action( 'wp_enqueue_scripts' , $this, 'define_enqueue_public_scripts', 9 );
		$this->loader->add_action( 'admin_enqueue_scripts' , $this, 'define_enqueue_admin_scripts', 9 );
		$this->loader->add_filter( 'wp_edit_nav_menu_walker', $this, 'navigation_nav_menu_walker_edit', 20 );
	}

	/**
	 * @access public
	 */
	public function define_enqueue_public_scripts() {
		global $post;

		// JS-Dateien
		wp_register_script( 'chevronprozess', get_stylesheet_directory_uri() . '/assets/js/layout/chevronprozess.js', array(), filemtime( get_stylesheet_directory() . '/assets/js/layout/chevronprozess.js' ), true);

		if( !empty( $post->post_type ) && !is_search() && !is_category() && !is_archive() ) {
			do_action( 'msq/enqueue_scripts/post_type=' . $post->post_type );
		}

	}

	/**
	 * @access public
	 */
	public function define_enqueue_admin_scripts() {
		global $post;

		if( !empty( $post->post_type ) ) {
			do_action( 'msq/admin_enqueue_scripts/post_type=' . $post->post_type );
		}
	}

	/**
	 * Diese Funktion fügt ein eigenen Walker-Edit Klasse hinzu, damit
	 * zusätzliche Felder im Backend richtig angezeigt werden können.
	 *
	 * Die Logik von diesem Code wurde von den Fachbereichen kopiert.
	 * @see wp-content\themes\ms_basis_theme\functions.php:2418 msq_filter_pre_update_option_pardot_settings()
	 *
	 * @see wp-content\themes\ms_basis_theme\inc\class-acf-walker-nav-menu-edit.php
	 *
	 * @param string $class Die aktuelle Walker-Klasse
	 * @return string $class Die neue Walker-Klasse
	 */
	function navigation_nav_menu_walker_edit( $class ) {
		if( class_exists( 'ACF_Walker_Nav_Menu_Edit' ) ) {
			global $msqTheme;
			$msqTheme->locateTemplate('inc/Msq_NavWalkerEdit.php', true, true);
			return 'Msq_NavWalkerEdit';
		}
		return $class;
	}

	/**
	 * @access public
	 */
	public function run() {
		if( !empty( $this->loader ) ) {
			$this->loader->run();
		}
	}
}

/**
 * @access public
 * @return MSQ_Theme_Mindsquare
 */
function theme() {
	static $instance = null;
	if( is_null( $instance ) ) {
		$instance = new MSQ_Theme_Mindsquare();
		$instance->init();
	}
	return $instance;
}
?>
