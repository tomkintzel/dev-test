<?php
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_5bf6ca4cea4ee',
		'title' => 'Post - Einstellungen',
		'fields' => array(
			array(
				'key' => 'field_5bf6ca719c49e',
				'label' => 'Blog-Übersichtsseite',
				'name' => 'blog_archive_link',
				'type' => 'page_link',
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
				'allow_null' => 1,
				'allow_archives' => 1,
				'multiple' => 0,
			),
			array(
				'key' => 'field_5df792fd8fb39',
				'label' => 'Angebot einbinden - Pardot Formular',
				'name' => 'solution_embed_pardot',
				'type' => 'pardot',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'allow_null' => 1,
				'ui' => 1,
				'ajax' => 0,
				'multiple' => 0,
				'choices' => array(
				),
				'placeholder' => '',
				'disabled' => 0,
				'readonly' => 0,
				'default_value' => array(
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-post',
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