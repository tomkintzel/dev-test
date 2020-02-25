<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://mindsquare.de
 * @since   1.0.0
 * @package msq_structured_data
 *
 * @wordpress-plugin
 * Plugin Name: MSQ - Strukturierte Daten
 * Plugin URI: https://mindsquare.de
 * Description: Diese Plugin aktiviert für alle Seiten die Strukturierte Daten
 * Version: 1.0.0
 * Author: Andrej Genschel <genschel@mindsquare.de>
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/**
 * Wenn ein harter Fehler aufgetretten ist, wird dieses Plugin deaktiviert.
 */
function msq_structured_data_action_deactivate_plugin() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Wenn ein harter Fehler aufgetretten ist, wird der Benutzer über das
 * Deaktivieren benachrichtigt.
 */
function msq_structured_data_action_admin_notice_deactivate_plugin() {
	echo '<div class="notice notice-error is-dismissible"><p>Das Plugin <strong>\'MSQ - Strukturierte Daten\'</strong> wurde aufgrund eines schweren Fehlers deaktiviert.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Nachricht verbergen.</span></button></div>';
}

/**
 * Diese Funktion lädt alle PHP-Dateien aus einem Ordner und geht dabei
 * auch in tiefere Verzeichnise.
 *
 * @param string $dirname - Aus welchen Verzeichnis die Dateien geladen werden sollen
 */
function msq_structured_data_include_recursive( $dirname ) {
	if ( is_dir( $dirname ) ) {
		$filenames = scandir( $dirname );
		foreach ( $filenames as $filename ) {
			if ( !preg_match( '/^\.+$/', $filename ) ) {
				if ( is_dir( $dirname . '/' . $filename ) ) {
					$dirs[] = $dirname . '/' . $filename;
				} else if ( preg_match( '/\.php$/i', $filename ) ) {
					$files[] = $dirname . '/' . $filename;
				}
			}
		}
		if ( !empty( $files ) ) {
			foreach ( $files as $file ) {
				require_once( $file );
			}
		}
		if ( !empty( $dirs ) ) {
			foreach ( $dirs as $dir ) {
				msq_structured_data_include_recursive( $dir );
			}
		}
	}
}

function msq_structured_data_action_embed() {
	global $post;
	$currentBlogId = get_current_blog_id();

	if (is_single()) {
		if (get_post_type() === 'post') {
			$categories = get_the_category();
			$categoryNames = array();

			foreach ($categories as $category) {
				$categoryNames[] = $category->name;
			}

			if ( in_array( 'News', $categoryNames ) ) {
				new MSQ_Structured_Data_News_Article();
			} else if( !MSQ_Structured_Data_Blog_Posting::isCreated() ) {
				if( $currentBlogId != 37 ) {
					new MSQ_Structured_Data_Blog_Posting();
				} else {
					new MSQ_Structured_Data_Blog_Posting(array(
						'author' => new WpmPlaceholder( 'organization', [], [
							'format' => 'short'
						])
					));
				}
			}

			new MSQ_Structured_Data_Breadcrumb();
		}
	} else if( is_page() && get_post_type() === 'page' ) {
		if( is_front_page() ) {
			new MSQ_Structured_Data_Organization();
			new MSQ_Structured_Data_Web_Site();

			if( !MSQ_Structured_Data_Web_Page::isCreated() && !MSQ_Structured_Data_Blog::isCreated() ) {
				new MSQ_Structured_Data_Web_Page();
			}
		} else {
			$contact = get_field( 'sd_contact_page', 'options' );

			if ( !empty( $post ) && !empty( $contact ) && $post->ID === $contact->ID ) {
				new MSQ_Structured_Data_Contact_Page();
			}
			elseif( !MSQ_Structured_Data_Web_Page::isCreated() && !MSQ_Structured_Data_Blog::isCreated() ) {
				global $post;
				$fields = array();

				if( $currentBlogId === 37 ) {
					if( $post->post_name === 'downloads-fuer-unternehmen' || $post->post_name === 'downloads-fuer-karriere' ) {
						$downloadsPage = get_field( 'sd_downloads_page', 'option' );
						if( !empty( $downloadsPage ) ) {
							$itemList[ 1 ] = array(
								'@id' => get_permalink( $downloadsPage ),
								'name' => get_the_title( $downloadsPage )
							);
						}
					}
				}

				if( !empty( $itemList ) ) {
					$fields[ 'breadcrumb' ] = array(
						'@type' => 'BreadcrumbList',
						'itemListElement' => new WpmPlaceholder( 'breadcrumb', [], array(
							'items' => !empty( $itemList ) ? $itemList : null
						))
					);
				}

				new MSQ_Structured_Data_Web_Page( $fields );
			}
		}
	} elseif( !is_404() ) {
		global $wp_query;

		$posts = $wp_query->get_posts();
		$count = $wp_query->found_posts;

		if ( ! MSQ_Structured_Data_Item_List::isCreated() && ! MSQ_Structured_Data_Blog::isCreated() ) {
			new MSQ_Structured_Data_Item_List( $posts, $count );
		}
		if ( ! MSQ_Structured_Data_Web_Page::isCreated() && ! MSQ_Structured_Data_Blog::isCreated() ) {
			new MSQ_Structured_Data_Web_Page();
		}
	} else {
		if ( ! MSQ_Structured_Data_Web_Page::isCreated() ) {
			new MSQ_Structured_Data_Web_Page();
		}
	}
}


// Lade die PHP-Dateien vom includes-Ordner
msq_structured_data_include_recursive( dirname( __FILE__ ) . '/includes' );
msq_structured_data_include_recursive( dirname( __FILE__ ) . '/assets' );

add_action( 'wp_footer', 'msq_structured_data_action_embed' );

// Prüfe ob ACF Funktionen vorhanden sind
if ( !function_exists( 'acf_add_local_field_group' ) || !class_exists( 'Msq_Structured_Data' ) ) {
	add_action( 'admin_init', 'msq_structured_data_action_deactivate_plugin' );
	add_action( 'admin_notices', 'msq_structured_data_action_admin_notice_deactivate_plugin' );
}

?>
