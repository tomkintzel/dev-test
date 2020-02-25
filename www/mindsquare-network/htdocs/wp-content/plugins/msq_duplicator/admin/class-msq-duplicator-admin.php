<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link https://mindsquare.de
 * @since 1.0.1
 *
 * @package msq_duplicator
 * @subpackage msq_duplicator/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package msq_duplicator
 * @subpackage msq_duplicator/admin
 * @author Andrej Genschel <genschel@mindsquare.de>
 */
class Msq_Duplicator_Admin {
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
	* Diese Funktion kopiert ein Post-Eintrag und speichert diese als Entwurf ab.
	* Dazu werden die Informationen zu dem Post-Eintrag, die Zugehörigkeiten zu den Taxonomien, und die Meta-Fields kopiert.
	* Sollte der Eintrag erfolgreich kopiert worden sein, so wird der Benutzer zu dem Eintrag weitergeleitet.
	* Wenn der Eintrag nicht kopiert werden konnte, wird er wieder zur Übersicht weitergeleitet.
	*/
	public function duplicate_post() {
		global $wpdb;

		if( !empty( $_GET[ 'postId' ] ) ) {
			$post_id = $_GET[ 'postId' ];
			if( $duplicate = get_post( $post_id, 'ARRAY_A' ) ) {
				if( $duplicate[ 'post_type' ] == 'page' ? current_user_can( 'edit_pages' ) : current_user_can( 'edit_posts' ) ) {
					$user = wp_get_current_user();
					$userId = $user->ID;
	
					$duplicate[ 'post_status' ] = 'draft';
					$duplicate[ 'post_author' ] = $userId;
					
					unset( $duplicate[ 'ID' ] );
					unset( $duplicate[ 'guid' ] );
					unset( $duplicate[ 'comment_count' ] );
					unset( $duplicate[ 'post_date' ] );
					unset( $duplicate[ 'post_date_gmt' ] );
					unset( $duplicate[ 'post_modified' ] );
					unset( $duplicate[ 'post_modified_gmt' ] );
	
					// Erstelle ein neuen Beitrag
					$new_post_id = wp_insert_post( $duplicate );
					
					// Kopiere die zugehörigkeiten zu den Taxonomien
					if( $taxonomies = get_object_taxonomies( $duplicate[ 'post_type' ] ) ) {
						foreach( $taxonomies as $taxonomy ) {
							if( $terms = wp_get_object_terms( $postId, $taxonomy, array( 'fields' => 'names' ) ) ) {
								wp_set_object_terms( $new_post_id, $terms, $taxonomy, false );
							}
						}
					}
	
					// Kopiere die PostMeta in den neuen Post
					if( $custom_fields = get_post_custom( $post_id ) ) {
						foreach( $custom_fields as $meta_key => $meta_values ) {
							if( is_array( $meta_values ) && count( $meta_values ) > 0 ) {
								foreach( $meta_values as $meta_value ) {
									add_post_meta( $new_post_id, $meta_key, $meta_value );
								}
							}
						}
					}
				}
				if( $duplicate[ 'post_type' ] != 'post' ) {
					if( wp_redirect( admin_url( 'edit.php?post_type=' . $duplicate[ 'post_type' ] ) ) ) {
						exit();
					}
				}
			}
		}
		if( wp_redirect( admin_url( 'edit.php' ) ) ) {
			exit();
		}
	}

	/**
	 * Diese Funktion fügt zu der Liste von Aktionen noch ein Duplizieren-Button hinzu
	 *
	 * @param $actions Die Liste von Aktionen unter einem Post-Eintrag
	 * @param $post Der Post-Eintrag unter dem der Duplizieren-Button eingebaut werden soll
	 * @return mixed[] Die erweiterte Liste von Aktionen
	 */
	public function register_duplicate_button( $actions, $post ) {
		if( $post->post_type == 'page' ? current_user_can( 'edit_pages' ) : current_user_can( 'edit_posts' ) ) {
			$actions[ 'duplicate' ] = '<a href="admin.php?action=msq_plugin_duplicator&postId=' . $post->ID . '" title="Eintrag duplizieren">Duplizieren</a>';
		}
		return $actions;
	}
}
?>
