<?php
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_59a3be12c344d',
		'title' => 'Knowhow - Einstellungen',
		'fields' => array(
			array(
				'key' => 'field_5c77df587e595',
				'label' => 'Archiv-Seite',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_5c77e03f7e597',
				'label' => 'Titel',
				'name' => 'archive_title',
				'type' => 'text',
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
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5c77e0b37e598',
				'label' => 'Inhalt',
				'name' => 'archive_content',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_5c792b375d0a6',
				'label' => 'Hintergrundgrafik',
				'name' => 'archive_background_image',
				'type' => 'image',
				'instructions' => 'Hintergrundgrafik, die im Titel-Banner angezeigt werden soll. Das Bild erstreckt sich über die gesamte Breite des Bildschirms.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'url',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_5c7e68c9c7ae5',
				'label' => 'Weitere Inhalte',
				'name' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '<i class="fa fa-info-circle"></i> <b>Die nachfolgenden Inhalte werden am Ende der Archiv-Seite angezeigt</b>',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
			array(
				'key' => 'field_5c7d3c962492f',
				'label' => 'Weitere Inhalte',
				'name' => 'kh',
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
					0 => 'field_5890a33486435',
					1 => 'field_59bb892b1ecd9',
				),
				'display' => 'seamless',
				'layout' => 'block',
				'prefix_label' => 0,
				'prefix_name' => 1,
			),
			array(
				'key' => 'field_5c77df8a7e596',
				'label' => 'Am Ende eines Knowhows',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_59a3be36fa75b',
				'label' => 'Schritte',
				'name' => 'knowhow_op_steps',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => '',
				'min' => 0,
				'max' => 3,
				'layout' => 'table',
				'button_label' => 'Schritt hinzufügen',
				'sub_fields' => array(
					array(
						'key' => 'field_59a3beaefa75c',
						'label' => 'Name',
						'name' => 'knowhow_op_steps_name',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => 30,
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
						'readonly' => 0,
						'disabled' => 0,
					),
					array(
						'key' => 'field_59a3bef3fa75d',
						'label' => 'Icon',
						'name' => 'knowhow_op_steps_icon',
						'type' => 'text',
						'instructions' => 'Ein Icon von http://fontawesome.io/icons/',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => 20,
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
						'readonly' => 0,
						'disabled' => 0,
					),
					array(
						'key' => 'field_59a3bf2dfa75e',
						'label' => 'Beschreibung',
						'name' => 'knowhow_op_steps_description',
						'type' => 'textarea',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => 50,
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'maxlength' => '',
						'rows' => 4,
						'new_lines' => 'br',
						'readonly' => 0,
						'disabled' => 0,
					),
				),
			),
			array(
				'key' => 'field_5c7e96784cd34',
				'label' => 'Organisatorisches',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_59a3bf69fa75f',
				'label' => 'Fachbereiche',
				'name' => 'knowhow_op_fachbereiche',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_59a3c003fa762',
				'min' => 0,
				'max' => 0,
				'layout' => 'block',
				'button_label' => 'Fachbereich hinzufügen',
				'sub_fields' => array(
					array(
						'key' => 'field_59a3bf8dfa760',
						'label' => 'Fachbereichs ID',
						'name' => 'knowhow_op_fachbereiche_id',
						'type' => 'text',
						'instructions' => 'Die WP-Blog-ID. (Falls nicht vorhanden, hohen Wert setzen)',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '33',
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
						'key' => 'field_59a3c003fa762',
						'label' => 'Fachbereichsnamen',
						'name' => 'knowhow_op_fachbereiche_name',
						'type' => 'text',
						'instructions' => '&nbsp;',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => 66,
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
						'readonly' => 0,
						'disabled' => 0,
					),
					array(
						'key' => 'field_59a3bfb2fa761',
						'label' => 'Telefonnummer',
						'name' => 'knowhow_op_fachbereiche_number',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => 33,
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
						'readonly' => 0,
						'disabled' => 0,
					),
					array(
						'key' => 'field_59a3c01ffa763',
						'label' => 'E-Mail-Adresse',
						'name' => 'knowhow_op_fachbereiche_mail',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => 33,
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
						'readonly' => 0,
						'disabled' => 0,
					),
					array(
						'key' => 'field_59a3c04cfa764',
						'label' => 'Button-Link (Kontakt aufnehmen)',
						'name' => 'knowhow_op_fachbereiche_link',
						'type' => 'page_link',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '33',
							'class' => '',
							'id' => '',
						),
						'post_type' => '',
						'taxonomy' => '',
						'allow_null' => 0,
						'allow_archives' => 1,
						'multiple' => 0,
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-knowhow',
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
