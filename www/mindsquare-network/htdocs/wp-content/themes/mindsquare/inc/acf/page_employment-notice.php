<?php
if ( function_exists( 'acf_add_options_sub_page' ) ) :
	acf_add_options_sub_page(array(
		'page_title'		=> 	'Globale Einstellungen für Stellenangebote',
		'menu_title'		=>	'Einstellungen',
		'menu_slug'			=>	'acf-options-stellenanzeigen',
		'parent_slug'		=>	'edit.php?post_type=employment_notice',
		'capability'		=>	'manage_options'
	));
endif;
?>