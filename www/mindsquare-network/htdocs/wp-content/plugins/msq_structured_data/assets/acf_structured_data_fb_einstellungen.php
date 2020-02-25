<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5b72eb0766fd4',
	'title' => 'Structured Data - Einstellungen (Fachbereiche)',
	'fields' => array(
		array(
			'key' => 'field_5b72eb119b5ea',
			'label' => 'Kontaktseite',
			'name' => 'sd_fb_contact_page',
			'type' => 'clone',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'clone' => array(
				0 => 'field_5b695e762f036',
			),
			'display' => 'seamless',
			'layout' => 'block',
			'prefix_label' => 0,
			'prefix_name' => 0,
		),
		array(
			'key' => 'field_5b72eb709b5eb',
			'label' => 'Referenzseite',
			'name' => 'sd_fb_reference_page',
			'type' => 'page_link',
			'instructions' => 'Auf welcher Seite Referenzen ausgespielt werden',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'page',
			),
			'taxonomy' => array(
			),
			'allow_null' => 0,
			'allow_archives' => 0,
			'multiple' => 0,
		),
		array(
			'key' => 'field_5bc998f46a997',
			'label' => 'Teamseite',
			'name' => 'sd_fb_team_page',
			'type' => 'post_object',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'page',
			),
			'taxonomy' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'return_format' => 'object',
			'ui' => 1,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-structured-data-fb',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
?>