<?php
namespace MSQ\Plugin\Quality_Management;
use MSQ\Plugin\Quality_Management\ACF_Fields\ACF_Custom_Conditional_Logic;
use MSQ\Plugin\Quality_Management\Pardot_Updaters\Pardot_Model_Updater;
use MSQ\Plugin\Quality_Management\Scopes\Scope_Builder;
use MSQ\Plugin\Quality_Management\Scopes\Scope;

/**
 * Diese Klasse dient als Einsteigspunkt für dieses Plugin.
 */
class Quality_Management {
	/** @var string $plugin_name */
	private $plugin_name;

	/** @var string $url */
	private $url;

	/** @var string $path */
	private $path;

	/** @var Scope[] $scopes */
	private $scopes;

	/**
	 * Definiert die Core-Funktionen.
	 * @param string[]
	 */
	private function __construct( $settings ) {
		// vars
		$this->plugin_name = 'msq-quality-management';
		$this->url = $settings[ 'url' ] ?: '';
		$this->path = $settings[ 'path' ] ?: '';

		$this->define_admin_hooks();
	}

	/** */
	private function define_admin_hooks() {
		if( is_admin() ) {
			// Actions
			add_action( 'init', [ $this, 'init' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 12 );
			add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
			add_action( 'acf/include_field_types', 	[ $this, 'register_field_types' ] );

			// Filter
			add_filter( 'acf/load_field/name=trigger', [ $this, 'load_field_trigger' ] );
			add_filter( 'acf/load_field/name=trigger-group', [ $this, 'load_field_trigger' ] );
			add_filter( 'acf/load_field/name=qs-ruleset-values_field', [ $this, 'load_field_qs_rule_set_values_field' ] );
			add_filter( 'msq/plugins/quality_management/register_custom_values', [ $this, 'register_custom_values' ] );
			add_filter( 'msq/plugins/quality_management/update_custom_values', [ $this, 'update_custom_values' ], 10, 2 );
		}
	}

	/** */
	public function init() {
		Pardot_Model_Updater::get_instance();
		$this->init_scopes();
	}

	/** */
	public function enqueue_scripts() {
		$url = $this->url;
		$path = $this->path;
		$filename = "{$url}assets/css/admin-style.css";
		$version = filemtime( "{$path}assets/css/admin-style.css" );

		wp_enqueue_style( 'qs-pardot-styles', $filename, [], $version );
	}

	/**
	 * Registiere alle Seiten.
	 */
	public function register_admin_menu() {
		// vars
		$plugin_name = $this->get_plugin_name();

		// Erstelle das Hauptmenü
		add_menu_page( 'Qualitätssicherung', 'Qualitätssicherung', 'manage_options', $plugin_name, false, 'dashicons-clipboard' );

		// Erstelle die Options-Seite für die Pardot-Formulare
		if( function_exists( 'acf_add_options_sub_page' ) ) {
			$this->pages[ 'acf-options-' . $plugin_name ] = acf_add_options_sub_page( array(
				'page_title' => 'Regeln für die Pardot-Einstellungen',
				'menu_title' => 'Regeln',
				'menu_slug' => 'acf-options-' . $plugin_name,
				'parent_slug' => $plugin_name,
				'capability' => 'manage_options'
			));
		}
	}

	/** */
	public function register_field_types() {
		new ACF_Custom_Conditional_Logic([
			'url' => $this->get_url(),
			'path' => $this->get_path()
		]);
	}

	/**
	 * @todo In Scopes verschieben
	 * @param mixed[] $acf_field
	 * @return mixed[]
	 */
	public function load_field_trigger( $field ) {
		if( $field[ 'type' ] == 'custom_conditional_logic' ) {
			// vars
			$field[ 'params' ] = [];

			// Registierte Felder
			$scope = $this->get_scope( 'form' );
			$column_collection = $scope->get_column_collection( 'form' );
			$column_groups = $column_collection->get_column_groups();
			foreach( $column_groups as $group => $columns ) {
				foreach( $columns as $column_name => $column ) {
					$field[ 'params' ][ $group ][ $column_name ] = $group . ' > ' . $column->get_headline();
				}
			}
		}
		return $field;
	}

	/**
	 * @todo In Scopes verschieben
	 * @param mixed[] $acf_field
	 * @return mixed[]
	 */
	public function load_field_qs_rule_set_values_field( $field ) {
		// vars
		$field[ 'choices' ] = [];
		$custom_columns = get_field( 'qs-ruleset-columns', 'option' );
		if( !empty( $custom_columns ) ) {
			foreach( $custom_columns as $custom_column ) {
				$name = sanitize_title( 'custom_' . $custom_column[ 'fieldname' ] );
				$headline = $custom_column[ 'fieldname' ];
				$field[ 'choices' ][ 'Eigene Felder' ][ $name ] = $headline;
			}
		}
		return $field;
	}

	/**
	 * @todo In Scopes verschieben
	 * @param array $custom_columns
	 * @return array
	 */
	public function register_custom_values( $custom_columns ) {
		$custom_columns[] = [
			'headline' => 'Hier gefunden',
			'fieldname' => 'post_links',
			'settings' => []
		];
		return $custom_columns;
	}

	/**
	 * @todo In Scopes verschieben
	 * @param array $changes
	 * @param MSQ\Plugin\Quality_Management\Pardot_Models\Form $form
	 * @return array
	 */
	public function update_custom_values( $changes, $form ) {
		global $wpdb;

		// results[ form_id ][ blog_id ][ post_name ] = link_args
		static $results;

		// vars
		$form_id = $form->get_id();

		// Erstelle eine Liste von Posts
		if( empty( $results ) ) {
			$sites = get_sites([
				'domain__not_in' => [
					'blog.mindsquare.de',
					'marketing.mindsquare.de'
				]
			]);

			// Gehe durch alle Fachbereiche
			foreach( $sites as $site ) {
				switch_to_blog( $site->blog_id );

				// Suche bei den Posts nach bekannten Pardot-Formular-Felder
				$postmeta_results = $wpdb->get_results( "SELECT pm.post_id, p.post_name, pm.meta_value FROM {$wpdb->prefix}postmeta pm LEFT JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id WHERE pm.meta_key IN ('pardot-ask-author', 'pardot') AND p.post_type NOT IN ( 'revision' )" );
				foreach( $postmeta_results as $postmeta_result ) {
					$results[ $postmeta_result->meta_value ][ $site->blog_id ][ $postmeta_result->post_name ][ 'p' ] = $postmeta_result->post_id;
				}
				if( $site->blog_id == 37 ) {
					// Mindsquare
					$postmeta_results = $wpdb->get_results( "SELECT pm.post_id, p.post_name, pm.meta_value FROM {$wpdb->prefix}postmeta pm LEFT JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id WHERE (pm.meta_key IN ('download_form_pardot', 'webinar_pardot') OR pm.meta_key LIKE '%%form') AND p.post_type NOT IN ( 'revision' )" );
				} else {
					// Fachbereich
					$postmeta_results = $wpdb->get_results( "SELECT pm.post_id, p.post_name, pm.meta_value FROM {$wpdb->prefix}postmeta pm LEFT JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id WHERE pm.meta_key IN ('team_contact_pardot', 'contactform_pardot', 'form_highlight_form') AND p.post_type NOT IN ( 'revision' )" );
				}
				foreach( $postmeta_results as $postmeta_result ) {
					$results[ $postmeta_result->meta_value ][ $site->blog_id ][ $postmeta_result->post_name ][ 'p' ] = $postmeta_result->post_id;
				}

				// Suche bei den Posts nach Shortcodes mit Pardot-Formularen
				$posts_results = $wpdb->get_results( "SELECT p.ID, p.post_name, p.post_content FROM {$wpdb->prefix}posts p WHERE p.post_content LIKE '%[pardot-form%' AND p.post_type NOT IN ( 'revision' )" );
				foreach( $posts_results as $posts_result ) {
					if( preg_match_all( '/\[pardot-form[^\]]+id="(?<form_id>\d+)"/i', $posts_result->post_content, $matches, PREG_SET_ORDER ) ) {
						foreach( $matches as $match ) {
							$results[ $match[ 'form_id' ] ][ $site->blog_id ][ $posts_result->post_name ][ 'p' ] = $posts_result->ID;
						}
					}
				}
				$postmeta_results = $wpdb->get_results( "SELECT pm.post_id, p.post_name, pm.meta_value FROM {$wpdb->prefix}postmeta pm LEFT JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id WHERE p.meta_value LIKE '%[pardot-form%' AND p.post_type NOT IN ( 'revision' )" );
				foreach( $postmeta_results as $postmeta_result ) {
					if( preg_match_all( '/\[pardot-form[^\]]+id="(?<form_id>\d+)"/i', $postmeta_result->meta_value, $matches, PREG_SET_ORDER ) ) {
						foreach( $matches as $match ) {
							$results[ $match[ 'form_id' ] ][ $site->blog_id ][ $postmeta_result->post_name ][ 'p' ] = $postmeta_result->post_id;
						}
					}
				}

				// Suche bei den Taxonomien nach Shortcodes mit Pardot-Formularen
				$term_taxonomy_results = $wpdb->get_results( "SELECT tt.taxonomy, t.slug, t.name, tt.description FROM {$wpdb->prefix}term_taxonomy tt LEFT JOIN {$wpdb->prefix}terms t ON t.term_id = tt.term_id WHERE tt.description LIKE '%[pardot-form%'" );
				foreach( $term_taxonomy_results as $term_taxonomy_result ) {
					if( preg_match_all( '/\[pardot-form[^\]]+id="(?<form_id>\d+)"/i', $term_taxonomy_result->description, $matches, PREG_SET_ORDER ) ) {
						foreach( $matches as $match ) {
							$results[ $match[ 'form_id' ] ][ $site->blog_id ][ $term_taxonomy_result->name ] = [
								'taxonomy' => $term_taxonomy_result->taxonomy,
								'term' => $term_taxonomy_result->slug
							];
						}
					}
				}

				restore_current_blog();
			}
		}

		// post_links
		if( !empty( $results[ $form_id ] ) ) {
			$links = [];
			foreach( $results[ $form_id ] as $blog_id => $post_args ) {
				switch_to_blog( $blog_id );
				$blog_url = get_home_url();
				foreach( $post_args as $post_name => $args ) {
					$permalink = add_query_arg( $args, $blog_url );
					$links[] = sprintf( '<li><a href="%s">%s</a></li>', $permalink, $post_name );
				}
				restore_current_blog();
			}
			$changes[] = [
				'fieldname' => 'post_links',
				'value' => sprintf( '<ul class="QMTable-List">%s</ul>', implode( '', $links ) )
			];
		}
		return $changes;
	}

	/** */
	private function init_scopes() {
		$this->scopes = Scope_Builder::build( $this->plugin_name );
	}

	/** @return string */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/** @return string */
	public function get_url() {
		return $this->url;
	}

	/** @return string */
	public function get_path() {
		return $this->path;
	}

	/**
	 * @param string $name
	 * @return Scope
	 */
	public function get_scope( $name ) {
		if( !empty( $this->scopes[ $name ] ) ) {
			return $this->scopes[ $name ];
		}
		return null;
	}

	/**
	 * @return Quality_Management
	 */
	public static function get_instance() {
		static $instance;
		if( empty( $instance ) ) {
			$instance = new self([
				'url'  => plugin_dir_url( dirname( __FILE__ ) ),
				'path' => plugin_dir_path( dirname( __FILE__ ) )
			]);
		}
		return $instance;
	}
}
