<?php
if( function_exists('acf_add_options_sub_page') ):

acf_add_options_sub_page(array(
	'page_title' => 'Globale Einstellungen von Knowhow',
	'menu_title' => 'Einstellungen',
	'menu_slug'	=> 'acf-options-knowhow',
	'parent_slug' => 'edit.php?post_type=knowhow',
	'capability' => 'manage_options'
));

endif; ?>
