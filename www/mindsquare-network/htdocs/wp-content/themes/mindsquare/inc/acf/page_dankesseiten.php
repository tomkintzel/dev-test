<?php
if ( function_exists( 'acf_add_options_sub_page' ) ):

	acf_add_options_sub_page( array(
		'page_title'  => 'Einstellungen Danke-Seiten',
		'menu_title'  => 'Danke-Seiten',
		'menu_slug'   => 'acf-options-einstellungen-danke-seiten',
		'parent_slug' => 'options-general.php',
		'capability'  => 'manage_options'
	));

endif;
?>
