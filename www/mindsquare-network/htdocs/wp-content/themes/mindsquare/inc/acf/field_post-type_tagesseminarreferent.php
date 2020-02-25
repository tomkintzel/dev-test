<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5a0e952b9d9f5',
	'title' => 'Eventreferenten',
	'fields' => array(
		array(
			'key' => 'field_5a0e9551a2669',
			'label' => 'Titel',
			'name' => 'title',
			'type' => 'text',
			'instructions' => 'z.B. Dipl. Ing.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5a0e955ba266a',
			'label' => 'Position',
			'name' => 'position',
			'type' => 'text',
			'instructions' => 'z.B. Gründer mindsquare',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5a0e95a0a266b',
			'label' => 'Webseite',
			'name' => 'url',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array(
			'key' => 'field_5a0e976ea5c2e',
			'label' => 'Bild',
			'name' => 'image',
			'type' => 'image',
			'instructions' => 'Das Bild muss eine Größe von 67x104px haben',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'thumbnail',
			'library' => 'all',
			'min_width' => 67,
			'min_height' => 104,
			'min_size' => '',
			'max_width' => 67,
			'max_height' => 104,
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'tagesseminarreferent',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'left',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
?>