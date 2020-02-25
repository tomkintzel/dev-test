<?php
/**
 * Plugin Name: MSQ - Bottom of the Funnel Sidebar
 * Plugin URI: https://mindsquare.de
 * Description: Dieses Plugin fügt ein Bottom of the Funnel Sidebar zu allen Seiten hinzu Version: 1.2.0
 * Author: Andrej Genschel & Stefan Wiebe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Einbinden von ACF-Einstellungen.
require_once plugin_dir_path( __FILE__ ) . 'acf-settings.php';

/**
 * Gibt die relevanten Pfade für den jeweiligen Fachbereich zurück.
 *
 * @param int $blog_id Die ID des Fachbereichs.
 * @return array Ein Array mit folgenden Schlüsseln für die jeweiligen Pfade:<br>
 *               'template'<br>
 *               'style-path'<br>
 *               'style-url'
 */
function msq_bofu_get_locations( $blog_id ) {
	switch ( $blog_id ) {
		case 37:
			return [
				'template'   => plugin_dir_path( __FILE__ ) . 'msq_bofu_template_karriere.php',
				'style-path' => plugin_dir_path( __FILE__ ) . 'msq_bofu_sidebar_karriere.css',
				'style-url'  => plugins_url(
					'msq_bofu_sidebar_karriere.css',
					__FILE__
				),
			];
		default:
			return [
				'template'   => plugin_dir_path( __FILE__ ) . 'msq_bofu_template.php',
				'style-path' => plugin_dir_path( __FILE__ ) . 'msq_bofu_sidebar.css',
				'style-url'  => plugins_url( 'msq_bofu_sidebar.css', __FILE__ ),
			];
	}
}

$locations          = msq_bofu_get_locations( get_current_blog_id() );
$whitelist_entities = [
	'post_types' => [
		'whitelistFn' => function( $post, $whitelisted ) {
			return in_array(
				$post->post_type,
				$whitelisted,
				true
			);
		},
	],
	'templates'  => [
		'whitelistFn' => function( $post, $whitelisted ) {
			return in_array(
				$post->page_template,
				$whitelisted,
				true
			);
		},
	],
	'categories' => [
		'whitelistFn' => function( $post, $whitelisted ) {
			$post_category_ids = array_map(
				function( $category ) {
					return $category->term_id;
				},
				get_the_category( $post )
			);

			$category_ids = array_map(
				function( $category_id ) {
					return (int) $category_id;
				},
				$whitelisted
			);

			return ! empty( array_intersect( $post_category_ids, $category_ids ) );
		},
	],
	'pages'      => [
		'whitelistFn' => function( $post, $whitelisted ) {
			if ( is_archive() ) {
				$query_object = get_queried_object();
				return in_array(
					get_post_type_archive_link( $query_object->name ),
					$whitelisted,
					true
				);
			}
			return in_array(
				get_the_permalink( $post ),
				$whitelisted,
				true
			);
		},
	],
];

/**
 * Fügt die Bofu-Bar ein, falls sie whitelisted bzw. nicht blacklisted ist.
 */
function msq_bofu_add_content() {
	global $post, $locations;

	if ( empty( $post ) || ! msq_bofu_is_blacklisted( $post ) ) {
		while ( have_rows( 'bofu_bars', 'options' ) ) {
			the_row();

			if ( msq_bofu_is_whitelisted( $post ) ) {
				require $locations['template'];
				break;
			}
		}
	}
}

/**
 * Registriert FontAwesome für die Bofu-Bars
 */
function msq_bofu_register_fontawesome() {
	// Falls kein Font-Awesome bereits im Theme inkludiert wurde, wird es hiermit eingebunden.
	if ( ! wp_style_is( 'fontawesome', 'registered' ) ) {
		wp_enqueue_style(
			'font-awesome',
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
			[],
			'4.7.0'
		);
	} else {
		wp_enqueue_style( 'fontawesome' );
	}
}

add_action( 'wp_enqueue_scripts', 'msq_bofu_register_fontawesome', 11 );

/**
 * Bindet das Stylesheet für die Bofu-Bar ein.
 */
function msq_bofu_enqueue() {
	global $locations;
	wp_enqueue_style(
		'msq_bofu_sidebar',
		$locations['style-url'],
		[],
		filemtime( $locations['style-path'] )
	);
}

add_action( 'wp_enqueue_scripts', 'msq_bofu_enqueue' );

/**
 * Fügt den Filter hinzu, welcher die BOFU-Bar einbindet.
 */
function msq_bofu_init() {
	if ( ! is_admin() ) {
		add_filter( 'wp_footer', 'msq_bofu_add_content' );
	}
}

add_filter( 'init', 'msq_bofu_init' );

/**
 * Gibt zurück, ob eine vorher mit `the_row()` abgefragte BOFU-Bar für den
 * übergebenen Post freigegeben ist.
 *
 * @param WP_Post $post Der Post, auf welchen geprüft werden soll.
 *
 * @return bool Ob die BOFU-Bar für $post freigegeben ist.
 */
function msq_bofu_is_whitelisted( $post ) {
	global $whitelist_entities;

	$whitelist      = get_sub_field( 'whitelist' );
	$is_whitelisted = true;

	foreach ( $whitelist_entities as $key => &$whitelist_entity ) {
		$whitelist_entity['empty'] = empty( $whitelist[ $key ] );

		if ( ! $whitelist_entity['empty'] ) {
			$is_whitelisted = $whitelist_entity['whitelistFn']( $post, $whitelist[ $key ]);

			if ( $is_whitelisted ) {
				break;
			}
		}
	}

	return $is_whitelisted;
}

/**
 * Gibt zurück, ob BOFU-Bars für den übergebenen Post angezeigt werden sollten.
 *
 * @param WP_Post $post Der Post, welcher überprüft werden soll.
 *
 * @return bool Ob BOFU-Bars bei dem übergebenen Post angezeigt werden sollten<br>
 *              true => BOFU-Bars verboten<br>
 *              false => BOFU-Bars erlaubt
 */
function msq_bofu_is_blacklisted( $post ) {
	$blacklist_posttypes = get_field( 'bofu_blacklist_posttypes', 'options' );
	$blacklist_pages     = get_field( 'bofu_blacklist_pages', 'options' );

	return ! empty( $post ) && (
			( ! empty( $blacklist_posttypes )
			&& in_array(
				$post->post_type,
				$blacklist_posttypes,
				true
			) )
			||
			( ! empty( $blacklist_pages )
				&& in_array(
					$post->ID,
					$blacklist_pages,
					true
				) )
	);
}

/**
 * Bereitet die Auswahlmöglichkeiten für das Blacklist-Feld vor.
 *
 * @param object $field Das ACF-Feld.
 *
 * @return object Das ACF-Feld
 */
function msq_bofu_prepare_acf_blacklist_posttypes( $field ) {
	$posttypes = get_post_types( array(), 'objects' );

	if ( is_array( $posttypes ) ) {
		foreach ( $posttypes as $posttype ) {
			$field['choices'][ $posttype->name ] = $posttype->labels->name;
		}
	}

	return $field;
}

add_filter(
	'acf/prepare_field/name=bofu_blacklist_posttypes',
	'msq_bofu_prepare_acf_blacklist_posttypes'
);

/**
 * Sucht nach Advanced Custom Fields der alten Implementation und versucht,
 * diese in die neuen ACF zu übertragen. Löscht dabei auch alte Datenbank-
 * Einträge.
 */
function msq_bofu_adapt_old_fields() {
	$db_version = get_option( 'msq_bofu_version' );

	if ( version_compare( $db_version, '1.2.0', '<' ) ) {
		global $wpdb;

		$elements = [];

		$results    = $wpdb->get_results( "SELECT * FROM $wpdb->options WHERE option_name LIKE '%options_bofu_elements_%'" );
		$old_fields = [];

		foreach ( $results as $result ) {
			$matches = [];
			$success = preg_match( '/^options_bofu_elements_(\d+)_(\S+)$/', $result->option_name, $matches );

			if ( $success ) {
				$old_fields[ $matches[1] ][ $matches[2] ] = $result->option_value;
			}
		}

		wp_reset_postdata();

		foreach ( $old_fields as $old_field ) {
			$acf_fc_layout = 'link_element';
			$icon          = $old_field['bofu_icon'];
			$label         = $old_field['bofu_text'];
			$link          = $old_field['bofu_link'];

			$post_id = intval( $link );
			if ( $post_id !== 0 ) {
				$link = $post_id;
			}

			$elements[] = compact( 'acf_fc_layout', 'icon', 'label', 'link' );
		}

		if ( ! empty( $elements ) ) {
			$new_row = [
				'name'     => 'Standard',
				'elements' => $elements,
			];

			add_row( 'bofu_bars', $new_row, 'options' );
		}

		foreach ( $results as $result ) {
			delete_option( $result->option_name );
		}

		update_option( 'msq_bofu_version', '1.2.0' );
	}
}

add_action( 'acf/init', 'msq_bofu_adapt_old_fields' );
