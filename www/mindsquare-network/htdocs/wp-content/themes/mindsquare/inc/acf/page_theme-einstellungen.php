<?php
if( function_exists('acf_add_options_sub_page') ):

acf_add_options_sub_page(array(
	'page_title' => 'Theme Einstellungen',
	'menu_title' => 'Einstellungen',
	'menu_slug' => 'acf-options-theme',
	'parent_slug' => 'themes.php',
	'capability' => 'manage_options'
));

endif;
?>
