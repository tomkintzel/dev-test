<?php
if ( function_exists( 'acf_add_options_sub_page' ) ) :
	acf_add_options_sub_page(array(
		'page_title' => 'Globale Einstellungen für Schulungen',
		'menu_title' => 'Einstellungen',
		'menu_slug'	=> 'acf-options-schulungsseiten',
		'parent_slug' => 'edit.php?post_type=seminare',
		'capability' => 'manage_options'
	));
endif;
?>