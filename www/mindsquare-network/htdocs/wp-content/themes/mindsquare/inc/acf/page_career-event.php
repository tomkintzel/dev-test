<?php
if( function_exists('acf_add_options_sub_page') ):

acf_add_options_sub_page(array(
	'page_title' => 'Globale Einstellungen der Karriere-Veranstaltungen',
	'menu_title' => 'Einstellungen',
	'menu_slug' => 'acf-options-einstellungen',
	'parent_slug' => 'edit.php?post_type=career-event',
	'capability' => 'manage_options'
));

endif;
?>
