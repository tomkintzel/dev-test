<?php 
function register_advisor_posttype() {
    $labels = array(
        'name'                 => _x( 'Referenten', 'post type general name' ),
        'singular_name'        => _x( 'Referent', 'post type singular name' ),
        'add_new'             => __( 'Referent anlegen' ),
        'add_new_item'         => __( 'Neuen Referenten hinzufügen' ),
        'edit_item'         => __( 'Referenten bearbeiten' ),
        'new_item'             => __( 'Neuer Referent' ),
        'view_item'         => __( 'Referent anschauen' ),
        'search_items'         => __( 'Referenten durchsuchen' ),
        'not_found'         => __( 'Keinen Referenten gefunden' ),
        'not_found_in_trash'=> __( 'Keinen Referenten im Papierkorb gefunden' ),
        'parent_item_colon' => __( 'Referent' ),
        'menu_name'            => __( 'Referenten' )
    );
    
    $taxonomies = array();
    
    $supports = array('title','revisions');
    
    $post_type_args = array(
        'labels'             => $labels,
        'singular_label'     => __('Referent'),
        'public'             => true,
        'show_ui'             => true,
        'show_in_menu' => false,
        'show_in_admin_bar' => true,
        'publicly_queryable'=> true,
        'query_var'            => true,
        'capability_type'     => 'post',
	  'map_meta_cap' => true,
        'has_archive'         => false,
        'hierarchical'         => false,
        'rewrite'             => array('slug' => 'referenten', 'with_front' => false ),
        'supports'             => $supports,
        'menu_position'     => 31,
        'menu_icon'         => 'dashicons-admin-users',
        'taxonomies'        => $taxonomies,
	  'capabilities' => array(
		  'publish_posts' => 'general_advisor',
		  'read_private_posts' => 'general_advisor',
		  'create_posts' => 'general_advisor',
		  'edit_posts' => 'general_advisor',
		  'delete_posts' => 'general_advisor',
		  'delete_private_posts' => 'delete_private_posts',
		  'delete_published_posts' => 'delete_published_posts',
		  'delete_others_posts' => 'delete_others_posts'
	  )
     );
     register_post_type('advisor',$post_type_args);
}
add_action('init', 'register_advisor_posttype');


function msq_advisor_under_seminare() {
	global $submenu;
	if( !empty( $submenu[ 'edit.php?post_type=seminare' ] ) ) {
		$submenu[ 'edit.php?post_type=seminare' ][] = array( 'Referenten', 'manage_options', site_url( 'wp-admin/edit.php?post_type=advisor' ) );
	}
}
add_action( 'admin_menu', 'msq_advisor_under_seminare' );
?>