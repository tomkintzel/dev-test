<?php
if ( function_exists( 'acf_add_options_sub_page' ) ) :
	acf_add_options_sub_page(array(
		'page_title' => 'Globale Einstellungen für Chat auf den Karriere-Seiten',
		'menu_title' => 'Chat-Einstellungen',
		'menu_slug'	=> 'acf-options-chat',
		'parent_slug' => 'edit.php?post_type=employment_notice',
		'capability' => 'manage_options'
	));
endif;
?>