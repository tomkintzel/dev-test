<?php
if( function_exists('acf_add_options_sub_page') ) {
	if( get_current_blog_id() == 37 ) {
		acf_add_options_sub_page(array(
			'page_title'  => 'Einstellungen für Structured Data',
			'menu_title'  => 'Structured Data',
			'menu_slug'   => 'acf-options-structured-data',
			'parent_slug' => 'options-general.php',
			'capability'  => 'manage_options'
		));
	} else {
		acf_add_options_sub_page(array(
			'page_title'  => 'Fachbereichs-Einstellungen für Structured Data',
			'menu_title'  => 'Structured Data (FB)',
			'menu_slug'   => 'acf-options-structured-data-fb',
			'parent_slug' => 'options-general.php',
			'capability'  => 'manage_options'
		));
	}
}
?>