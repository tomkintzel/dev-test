<?php
if( function_exists('acf_add_options_sub_page') ):

acf_add_options_sub_page(array(
	'page_title' => 'Globale Einstellungen von der Freelancer Jobbörse',
	'menu_title' => 'Einstellungen',
	'menu_slug'	=> 'acf-options-freelancer-projekte',
	'parent_slug' => 'edit.php?post_type=freelancer-projekte',
	'capability' => 'manage_options'
));

endif; ?>
