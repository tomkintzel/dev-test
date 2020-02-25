<?php
if ( function_exists( 'acf_add_options_sub_page' ) ) :
	acf_add_options_sub_page(array(
		'page_title' => 'Globale Einstellungen von Posts',
		'menu_title' => 'Einstellungen',
		'menu_slug'	=> 'acf-options-post',
		'parent_slug' => 'edit.php',
		'capability' => 'manage_options'
	));
endif;
?>