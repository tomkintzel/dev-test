<?php
if( function_exists( 'acf_add_options_sub_page' ) ):

acf_add_options_sub_page( array(
	'page_title' => 'Einstellungen zum Posten von Beiträgen auf Facebook',
	'menu_title' => 'Beiträge auf Facebook',
	'menu_slug' => 'acf-options-facebook',
	'parent_slug' => 'options-general.php',
	'capability' => 'manage_options'
));

endif;
?>
