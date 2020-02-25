<?php 
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_597adea4d806e',
		'title' => 'Pardot',
		'fields' => array (
			array (
				'key' => 'field_597adeab861c0',
				'label' => 'Pardot-Formular',
				'name' => 'pardot-ask-author',
				'type' => 'pardot',
				'instructions' => 'Welches Pardot-Formular soll bei den Autoren ausgespielt werden?',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'allow_null' => 0,
				'ui' => 1,
				'ajax' => 0,
				'choices' => array (),
				'placeholder' => '',
				'disabled' => 0,
				'readonly' => 0,
				'default_value' => array (
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'frage-den-autoren',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

endif;