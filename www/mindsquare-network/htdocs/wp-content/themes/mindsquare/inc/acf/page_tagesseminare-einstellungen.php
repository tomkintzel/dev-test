<?php
if( function_exists('acf_add_options_sub_page') ):

acf_add_options_sub_page(array(
	'page_title' => 'Einstellungen Events',
	'menu_title' => 'Einstellungen',
	'menu_slug'	=> 'acf-options-einstellungen-tagesseminare',
	'parent_slug' => 'edit.php?post_type=tagesseminare',
	'capability' => 'manage_options'
));

endif; ?>
