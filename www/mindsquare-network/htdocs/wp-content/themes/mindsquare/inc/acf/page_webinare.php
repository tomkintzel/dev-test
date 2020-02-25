<?php
if( function_exists( 'acf_add_options_sub_page' ) ):

acf_add_options_sub_page( array(
	'page_title' => 'Globale Einstellungen der Webinare',
	'menu_title' => 'Einstellungen',
	'menu_slug' => 'acf-options-msq-webinare',
	'parent_slug' => 'edit.php?post_type=webinare',
	'capability' => 'manage_options'
));

endif;
?>