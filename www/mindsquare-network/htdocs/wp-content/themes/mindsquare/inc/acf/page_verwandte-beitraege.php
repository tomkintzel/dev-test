<?php 
if( function_exists('acf_add_local_field_group') ):
acf_add_local_field_group(array(
	'key' => 'group_5c1ca591459b9',
	'title' => 'Verwandte Beiträge',
	'fields' => array(
		array(
			'key' => 'field_5c1ca5a2387b3',
			'label' => 'Verwandte Beiträge',
			'name' => 'appropriate_links',
			'type' => 'repeater',
			'instructions' => 'Hier werden Beiträge ausgesucht, die unter dem ausgewählten Beitrag ausgespielt werden.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 4,
			'layout' => 'table',
			'button_label' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_5c2dc9e905432',
					'label' => 'Post',
					'name' => 'post',
					'type' => 'relationship',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'post_type' => array(
					),
					'taxonomy' => array(
					),
					'filters' => array(
						0 => 'search',
						1 => 'post_type',
						2 => 'taxonomy',
					),
					'elements' => array(
						0 => 'featured_image',
					),
					'min' => '',
					'max' => 4,
					'return_format' => 'id',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
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