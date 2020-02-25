<?php
function register_employment_notice_posttype() {
    $labels = array(
        'name'                  => _x( 'Stellenangebote', 'post type general name' ),
        'singular_name'         => _x( 'Stellenangebot', 'post type singular name' ),
        'add_new'               => __( 'Stellenangebot anlegen' ),
        'add_new_item'          => __( 'Neues Stellenangebot hinzufügen' ),
        'edit_item'             => __( 'Stellenangebot bearbeiten' ),
        'new_item'              => __( 'Neues Stellenangebot' ),
        'view_item'             => __( 'Stellenangebot anschauen' ),
        'search_items'          => __( 'Stellenangebote durchsuchen' ),
        'not_found'             => __( 'Keine Stellenangebote gefunden' ),
        'not_found_in_trash'    => __( 'Keine Stellenangebote im Papierkorb gefunden' ),
        'parent_item_colon'     => __( 'Stellenangebote' ),
        'menu_name'             => __( 'Stellenangebote' )
    );

    /* Hier noch bearbeiten
    ********************************************************/

    $taxonomies = array('employment_type');

    $supports = array('title','thumbnail','revisions');

    $post_type_args = array(
        'labels'                => $labels,
        'singular_label'        => __('Stellenangebot'),
        'public'                => true,
        'show_ui'               => true,
        'publicly_queryable'    => true,
        'query_var'             => true,
        'capability_type'       => 'employment_notice',
	  'map_meta_cap'            => true,
        'has_archive'           => false,
        'hierarchical'          => false,
        'rewrite'               => array(
                                    'slug' => 'stellenangebote',
                                    'with_front' => false
        ),
        'supports'          => $supports,
        'menu_position'     => 32,
        'menu_icon'         => 'dashicons-groups',
        'taxonomies'        => $taxonomies,
	    'capabilities'      => array(
		  'publish_posts'       	=> 'general_employment_notice',
		  'read_private_posts' 		=> 'general_employment_notice',
		  'create_posts' 			=> 'general_employment_notice',
		  'edit_posts' 				=> 'general_employment_notice',
		  'edit_private_posts' 		=> 'general_employment_notice',
		  'edit_published_posts'	=> 'general_employment_notice',
		  'edit_others_posts' 		=> 'general_employment_notice',
		  'delete_posts' 			=> 'general_employment_notice',
		  'delete_private_posts' 	=> 'general_employment_notice',
		  'delete_published_posts' 	=> 'general_employment_notice',
		  'delete_others_posts' 	=> 'general_employment_notice'
      ),
      'show_in_rest' => true,
     );
     register_post_type('employment_notice',$post_type_args);
}
add_action('init', 'register_employment_notice_posttype');


// registration code for seminarkategorie taxonomy
function register_employment_type_tax() {
    $labels = array(
        'name'                  => _x( 'Stellenkategorie', 'taxonomy general name' ),
        'singular_name'         => _x( 'Stellenkategorie', 'taxonomy singular name' ),
        'add_new'               => _x( 'Neue Stellenkategorie hinzufügen', 'Seminarkategorie'),
        'add_new_item'          => __( 'Neue Stellenkategorie hinzufügen' ),
        'edit_item'             => __( 'Stellenkategorie bearbeiten' ),
        'new_item'              => __( 'Neue Stellenkategorie' ),
        'view_item'             => __( 'Stellenkategorie anzeigen' ),
        'search_items'          => __( 'Stellenkategorien durchsuchen' ),
        'not_found'             => __( 'Keine Stellenkategorie gefunden' ),
        'not_found_in_trash'	=> __( 'Keine Stellenkategorie im Papierkorb gefunden' ),
    );

    $pages = array('employment_notice');

    $args = array(
        'labels'            => $labels,
        'singular_label'    => __('Stellenkategorie'),
        'public'            => false,
        'show_ui'           => true,
        'hierarchical'		=> true,
        'show_tagcloud'     => false,
        'show_in_nav_menus' => false,
        'rewrite'           => array('slug' => 'stellenkategorie', 'with_front' => false ),
        'capabilities' 		=> array(
            'manage_terms'	=> 'manage_categories',
            'edit_terms' 	=> 'manage_categories',
            'delete_terms' 	=> 'manage_categories',
            'assign_terms' 	=> 'assign_employment_type'
        ),
     );
    register_taxonomy('employment_type', $pages, $args);
}
add_action('init', 'register_employment_type_tax', 0);
register_taxonomy_for_object_type( 'employment_type', 'employment_notice' );

/**
 * Wenn eine Stellenanzeige aufgerunfen wird, und dabei eine 404-Seite
 * angezeigt wird, wird der Nutzer auf die Übersichtsseite weitergeleitet.
 * @param WP $wp
 */
function msq_action_wp_handle_404_employment_notice( $wp ) {
	if( !empty( $wp->query_vars[ 'employment_notice' ] ) ) {
		global $wp_the_query;
		if( $wp_the_query->is_404() ) {
			// Eine Seite wurde nicht gefunden
			/* $adminEMail = get_option( 'admin_email' );
			if( !empty( $adminEMail ) ) {
				$errorMsg = 'Ein Benutzer hat eine nicht existierende Stellenanzeige mit der URL aufgerufen (' . home_url( $wp->request ) . ').';
				trigger_error( $errorMsg );
				wp_mail( $adminEMail, 'Eine Stellenanzeige wurde nicht gefunden', $errorMsg, array( 'Content-Type: text/html; charset=UTF-8' ) );
			} */

			// Sende den Benutzer zur Übersichstseite
			$archivePage = get_page_by_path( 'karriere/stellenangebote' );
			$archiveUrl = get_permalink( $archivePage );
			if( wp_redirect( $archiveUrl ) ) {
				exit();
			}
		}
	}
}
add_action( 'wp', 'msq_action_wp_handle_404_employment_notice' );

?>
