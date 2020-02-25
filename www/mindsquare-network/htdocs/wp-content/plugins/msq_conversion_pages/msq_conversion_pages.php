<?php
/*
Plugin Name: Conversion Pages
Plugin URI: https://mindsquare.de
Description: Fügt den Custom Post Type "Conversion Pages" hinzu um Conversion Pages erstellen zu können
Version: 1.0.0
Author: Edwin Eckert
*/


// Einbinden von ACF-Einstellungen.
require( plugin_dir_path(__FILE__) . 'acf-settings.php' );



// Logo im Customizer pflegbar machen für Conversion-Pages
function logo_customize_register( $wp_customize ) {
  $wp_customize->add_setting( 'fb_logo' );
  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'fb_logo', array(
        'label'    => 'Logo Webseite',
        'section'  => 'title_tagline',
        'settings' => 'fb_logo',
      ) 
    ) 
  );
}
add_action( 'customize_register', 'logo_customize_register' );



/* Findet Custom Post Type "Conversion Pages"  - 27.02.2017, Basti */
function cp_parse_request($wbobj) {
  global $wp, $wp_query;
  $vars = $wp->query_vars;

    if( !empty( $vars[ 'year' ] ) ) {
        return $wbobj;
    }
    else if( !empty( $vars[ 'category_name' ] ) ) {
        $slug = $vars[ 'category_name' ];
    }
    else if( !empty( $vars[ 'name' ] ) ) {
        $slug = $vars[ 'name' ];
    }
    else if( !empty( $vars[ 'pagename' ] ) ) {
        $slug = $vars[ 'pagename' ];
    }
    else {
        return $wbobj;
    }

    $posts = get_posts(array(
        'name' => $slug,
        'posts_per_page' => 1,
        'post_type' => 'conversionpages',
        'post_status' => 'publish'
      )
    );
      
    if( !empty( $posts ) ) {
      unset($wp->query_vars['page']);
      unset($wp->query_vars['pagename']);
      unset($wp->query_vars['category_name']);

      $wp->query_vars['post_type'] = "conversionpages"; //CPT name
      $wp->query_vars['name'] = $slug;
      //$wp_query->is_page = false;
      //$wp_query->is_single = true;
      //$wp_query->queried_object = $posts[0];
    }

    return $wbobj;
 }
add_filter('parse_request', "cp_parse_request" , 1, 1);
 


/* Removes permalink slug from Conversion Pages - 20.02.2017, Basti */
function cp_remove_slug( $post_link, $post, $leavename ) {
    if ( 'conversionpages' != $post->post_type ) {
      return $post_link;
    }

    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
    
    return $post_link;
}
add_filter( 'post_type_link', 'cp_remove_slug', 10, 3 ); 



function conversionpage_register() {    
  $labels = array(
    'name'               => _x( 'Conversion Pages', 'post type general name' ),
    'singular_name'      => _x( 'Conversion Page', 'post type singular name' ),
    'menu_name'          => _x( 'Conversion Pages', 'admin menu' ),
    'name_admin_bar'     => _x( 'Conversion Page', 'Neue Conversion Page hinzufügen' ),
    'add_new'            => _x( 'Neu erstellen', 'book' ),
    'add_new_item'       => __( 'Neue Conversion Page erstellen' ),
    'new_item'           => __( 'Neue Conversion Page', 'your-plugin-textdomain' ),
    'edit_item'          => __( 'Conversion Page bearbeiten', 'your-plugin-textdomain' ),
    'view_item'          => __( 'Conversion Page anzeigen', 'your-plugin-textdomain' ),
    'all_items'          => __( 'Alle Conversion Pages anzeigen', 'your-plugin-textdomain' ),
    'search_items'       => __( 'Conversion Pages durchsuchen', 'your-plugin-textdomain' ),
    'not_found'          => __( 'Keine Conversion Page gefunden', 'your-plugin-textdomain' ),
    'not_found_in_trash' => __( 'Keine Conversion Pages im Papierkorb gefunden', 'your-plugin-textdomain' )
  );


  $args = array(    
    'labels' => $labels,    
    'singular_label' => __('Conversion Page'),    
    'public' => true,    
    'show_ui' => true,    
    'capability_type' => 'post',    
    'map_meta_cap' => true,
    'hierarchical' => false,    
    'rewrite' => true,    
    'supports' => array('title', 'revisions'),
    'menu_icon' => 'dashicons-feedback',
    'capabilities' => array(
		'publish_posts' => 'general_conversionpages',
		'read_private_posts' => 'general_conversionpages',
		'create_posts' => 'general_conversionpages',
		'edit_posts' => 'general_conversionpages',
		'delete_posts' => 'general_conversionpages',
		'delete_private_posts' => 'delete_private_posts',
		'delete_published_posts' => 'delete_published_posts',
		'delete_others_posts' => 'delete_others_posts'
    )
  );    
    
  register_post_type( 'conversionpages' , $args );    
}
add_action('init', 'conversionpage_register');



/* Filter the single_template with our custom function*/
function msq_conversionpage_template( $single ) {
  global $wp_query, $post;

  /* Checks for single template by post type */
  if( $wp_query->is_main_query() && $wp_query->is_single ) {
    if( !empty( $post->post_type ) && $post->post_type == "conversionpages" ){
      if(file_exists( plugin_dir_path(__FILE__) . 'single-conversionpage.php')) {
        add_action( 'wp_print_scripts', 'msq_dequeue_preloader_script', 11 );
        return plugin_dir_path(__FILE__) . 'single-conversionpage.php';
      }
    }
  }
  return $single;
}
add_filter('template_include', 'msq_conversionpage_template', 100);

function msq_dequeue_preloader_script() {
   wp_dequeue_script( 'dfd_queryloader2' );
}


function add_custom_image_sizes() {
  add_image_size( 'msq_conversion_pages_logo', 200, 144, false );
}
add_action('after_setup_theme','add_custom_image_sizes');



function regenerate_fb_logo_image_size() {
  if ( $theme_mod_logo = get_theme_mod( 'fb_logo' ) ){
    $image_id = attachment_url_to_postid( $theme_mod_logo );
    preg_match('/files(.*)$/i', $theme_mod_logo, $file_match);
    preg_match('/^(.*\/files)/i', wp_upload_dir()['path'], $upload_dir);
    $file = $upload_dir[1] . $file_match[1];
    $imagesize = getimagesize( $file );
    $metadata['width'] = $imagesize[0];
    $metadata['height'] = $imagesize[1];
    $metadata['file'] = _wp_relative_upload_path( $file ); 
    global $_wp_additional_image_sizes;
    $metadata['sizes']['msq_conversion_pages_logo'] = image_make_intermediate_size( $file, $_wp_additional_image_sizes['msq_conversion_pages_logo']['width'], $_wp_additional_image_sizes['msq_conversion_pages_logo']['height'], $_wp_additional_image_sizes['msq_conversion_pages_logo']['crop'] );
    wp_update_attachment_metadata( $image_id, apply_filters( 'wp_generate_attachment_metadata', $metadata, $image_id ) );
  }
}
add_action('customize_save_after', 'regenerate_fb_logo_image_size');

?>