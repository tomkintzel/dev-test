<?php
add_action( 'init', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
   wp_enqueue_script( 'pardot-resizing', get_stylesheet_directory_uri() . '/assets/js/initFrameResizing.js', array(), filemtime( get_stylesheet_directory() . '/assets/js/initFrameResizing.js' ) );
}

?>