<?php
/**
 * Options-Seite für Bottom of the Funnel - Sidebar
 */

function msq_bofu_add_options_page() {
	acf_add_options_sub_page(
		array(
			'page_title'  => 'Globale Einstellungen für Bottom of the Funnel',
			'menu_title'  => 'Bottom of the Funnel',
			'parent_slug' => 'options-general.php',
			'capability'  => 'manage_options',
		)
	);
}
add_action( 'acf/init', 'msq_bofu_add_options_page' );

function msq_bofu_page_type_field_choices( $field ) {
	$field['choices'] = [];

	$post_types = get_post_types( [ 'public' => true ], 'objects' );

	foreach ( $post_types as $post_type ) {
		$field['choices'][ $post_type->name ] = $post_type->labels->name;
	}

	return $field;
}
add_action( 'acf/prepare_field/name=post_types', 'msq_bofu_page_type_field_choices' );

function msq_bofu_template_field_choices( $field ) {
	$field['choices'] = [];

	$templates = get_page_templates();

	foreach ( $templates as $template_name => $template_filename ) {
		$field['choices'][ $template_filename ] = $template_name;
	}

	return $field;
}
add_action( 'acf/prepare_field/name=templates', 'msq_bofu_template_field_choices' );


function msq_bofu_category_field_choices( $field ) {
	$field['choices'] = [];

	/** @var WP_Term[] $categories */
	$categories = get_categories();

	foreach ( $categories as $category ) {
		$field['choices'][ $category->term_id ] = $category->name;
	}

	return $field;
}
add_action( 'acf/prepare_field/name=categories', 'msq_bofu_category_field_choices' );

function msq_bofu_fb_remove_contact( $field ) {
	if ( ! is_admin() || get_current_blog_id() === 37 ) {
		return $field;
	}

	$layouts          = $field['layouts'];
	$field['layouts'] = [];

	$layout_names = array_map(
		function ( $layout ) {
				return $layout['name'];
		},
		$layouts
	);

	$index = array_search( 'contact_element', $layout_names, true );

	if ( $index !== false ) {
		unset( $layouts[ $index ] );
	}

	$field['layouts'] = $layouts;

	return $field;
}
add_action( 'acf/load_field/name=elements', 'msq_bofu_fb_remove_contact' );

function msq_bofu_add_fields_global_settings() {
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_596484509b72e',
			'title' => 'Bottom of the Funnel - Einstellungen',
			'fields' => array(
				array(
					'key' => 'field_59670617eb57b',
					'label' => 'Sidebar',
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
					'key' => 'field_5c49f455a2aa5',
					'label' => 'BOFU-Bars',
					'name' => 'bofu_bars',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => 'field_5c49f581a2aab',
					'min' => 0,
					'max' => 0,
					'layout' => 'block',
					'button_label' => 'BOFU-Bar hinzufügen',
					'sub_fields' => array(
						array(
							'key' => 'field_5c49f581a2aab',
							'label' => 'Name',
							'name' => 'name',
							'type' => 'text',
							'instructions' => 'Zur Identifikation, hat keine Auswirkungen auf Funktionalität',
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
							'key' => 'field_5c4ade19746f9',
							'label' => 'Elemente',
							'name' => '',
							'type' => 'accordion',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'open' => 1,
							'multi_expand' => 1,
							'endpoint' => 0,
						),
						array(
							'key' => 'field_5c4b04e0c469c',
							'label' => '',
							'name' => 'elements',
							'type' => 'flexible_content',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layouts' => array(
								'5c4b0512260c9' => array(
									'key' => '5c4b0512260c9',
									'name' => 'link_element',
									'label' => 'Link-Element',
									'display' => 'block',
									'sub_fields' => array(
										array(
											'key' => 'field_5c4b0a1984c25',
											'label' => 'Font-Awesome-Icon',
											'name' => 'icon',
											'type' => 'text',
											'instructions' => 'Direkt in der BOFU-Bar sichtbar',
											'required' => 1,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '50',
												'class' => '',
												'id' => '',
											),
											'default_value' => '',
											'placeholder' => 'fa fa-file-text',
											'prepend' => '',
											'append' => '',
											'maxlength' => '',
										),
										array(
											'key' => 'field_5c4b0a7184c26',
											'label' => 'Beschriftung',
											'name' => 'label',
											'type' => 'text',
											'instructions' => 'Beim Hovern des Icons',
											'required' => 1,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '50',
												'class' => '',
												'id' => '',
											),
											'default_value' => '',
											'placeholder' => 'Angebot anfordern',
											'prepend' => '',
											'append' => '',
											'maxlength' => '',
										),
										array(
											'key' => 'field_5c49f554a2aaa',
											'label' => 'Link',
											'name' => 'link',
											'type' => 'page_link',
											'instructions' => 'Auf welche Seite soll der Button verlinken?',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
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
									'min' => '',
									'max' => '50%',
								),
								'layout_5c4b05d2c469e' => array(
									'key' => 'layout_5c4b05d2c469e',
									'name' => 'contact_element',
									'label' => 'Ansprechpartner-Element',
									'display' => 'block',
									'sub_fields' => array(
										array(
											'key' => 'field_5c4b0652c469f',
											'label' => '',
											'name' => '',
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
												0 => 'field_5c49f554a2aa8',
												1 => 'field_5c49f554a2aa9',
											),
											'display' => 'seamless',
											'layout' => 'block',
											'prefix_label' => 0,
											'prefix_name' => 0,
										),
										array(
											'key' => 'field_5c4b09d719f25',
											'label' => '',
											'name' => '',
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
												0 => 'field_5c4b0652c469f_field_5c49f554a2aa8',
												1 => 'field_5c4b0652c469f_field_5c49f554a2aa9',
											),
											'display' => 'seamless',
											'layout' => 'block',
											'prefix_label' => 0,
											'prefix_name' => 0,
										),
										array(
											'key' => 'field_5c4b0bbf6b7b0',
											'label' => '',
											'name' => '',
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
												0 => 'field_5c4b0a1984c25',
												1 => 'field_5c4b0a7184c26',
											),
											'display' => 'seamless',
											'layout' => 'block',
											'prefix_label' => 0,
											'prefix_name' => 0,
										),
										array(
											'key' => 'field_5c4b089328d50',
											'label' => 'Ansprechpartner',
											'name' => 'contact',
											'type' => 'group',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'layout' => 'block',
											'sub_fields' => array(
												array(
													'key' => 'field_5c4b0686c46a0',
													'label' => 'Vorname',
													'name' => 'first_name',
													'type' => 'text',
													'instructions' => '',
													'required' => 1,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '50',
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
													'key' => 'field_5c4b087828d4f',
													'label' => 'Nachname',
													'name' => 'last_name',
													'type' => 'text',
													'instructions' => '',
													'required' => 1,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '50',
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
													'key' => 'field_5c4b0927b2944',
													'label' => 'Position',
													'name' => 'position',
													'type' => 'text',
													'instructions' => '',
													'required' => 1,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '34',
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
													'key' => 'field_5c4b093eb2945',
													'label' => 'E-Mail',
													'name' => 'email',
													'type' => 'text',
													'instructions' => '',
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
													'key' => 'field_5c4b0c2f353ed',
													'label' => 'Telefonnummer',
													'name' => 'phone',
													'type' => 'text',
													'instructions' => '',
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
													'key' => 'field_5c4b0c6b353ee',
													'label' => 'Bild',
													'name' => 'image',
													'type' => 'image',
													'instructions' => '',
													'required' => 1,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '',
														'class' => '',
														'id' => '',
													),
													'return_format' => 'array',
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
											),
										),
									),
									'min' => '',
									'max' => '',
								),
								'layout_5dbad8e1278ea' => array(
									'key' => 'layout_5dbad8e1278ea',
									'name' => 'contact_element_whatsapp',
									'label' => 'Ansprechpartner-Element-Whatspp',
									'display' => 'block',
									'sub_fields' => array(
										array(
											'key' => 'field_5dbad8e1278eb',
											'label' => '',
											'name' => '',
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
												0 => 'field_5c49f554a2aa8',
												1 => 'field_5c49f554a2aa9',
											),
											'display' => 'seamless',
											'layout' => 'block',
											'prefix_label' => 0,
											'prefix_name' => 0,
										),
										array(
											'key' => 'field_5dbad8e1278ec',
											'label' => '',
											'name' => '',
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
												0 => 'field_5c4b0652c469f_field_5c49f554a2aa8',
												1 => 'field_5c4b0652c469f_field_5c49f554a2aa9',
											),
											'display' => 'seamless',
											'layout' => 'block',
											'prefix_label' => 0,
											'prefix_name' => 0,
										),
										array(
											'key' => 'field_5dbad8e1278ed',
											'label' => '',
											'name' => '',
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
												0 => 'field_5c4b0a1984c25',
												1 => 'field_5c4b0a7184c26',
											),
											'display' => 'seamless',
											'layout' => 'block',
											'prefix_label' => 0,
											'prefix_name' => 0,
										),
										array(
											'key' => 'field_5dbad8e1278ee',
											'label' => 'Ansprechpartner',
											'name' => 'contact',
											'type' => 'group',
											'instructions' => '',
											'required' => 0,
											'conditional_logic' => 0,
											'wrapper' => array(
												'width' => '',
												'class' => '',
												'id' => '',
											),
											'layout' => 'block',
											'sub_fields' => array(
												array(
													'key' => 'field_5dbad8e1278ef',
													'label' => 'Vorname',
													'name' => 'first_name',
													'type' => 'text',
													'instructions' => '',
													'required' => 1,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '50',
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
													'key' => 'field_5dbad8e1278f0',
													'label' => 'Nachname',
													'name' => 'last_name',
													'type' => 'text',
													'instructions' => '',
													'required' => 1,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '50',
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
													'key' => 'field_5dbad8e1278f1',
													'label' => 'Position',
													'name' => 'position',
													'type' => 'text',
													'instructions' => '',
													'required' => 1,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '34',
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
													'key' => 'field_5dbad8e1278f4',
													'label' => 'Bild',
													'name' => 'image',
													'type' => 'image',
													'instructions' => '',
													'required' => 1,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '',
														'class' => '',
														'id' => '',
													),
													'return_format' => 'array',
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
													'key' => 'field_5dbad927278f5',
													'label' => 'Whatsapp Adresse',
													'name' => 'whatsapp_adress',
													'type' => 'link',
													'instructions' => '',
													'required' => 0,
													'conditional_logic' => 0,
													'wrapper' => array(
														'width' => '',
														'class' => '',
														'id' => '',
													),
													'return_format' => 'array',
												),
											),
										),
									),
									'min' => '',
									'max' => '',
								),
							),
							'button_label' => 'Eintrag hinzufügen',
							'min' => '',
							'max' => '',
						),
						array(
							'key' => 'field_5c4ade58746fa',
							'label' => 'Whitelist',
							'name' => '',
							'type' => 'accordion',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'open' => 0,
							'multi_expand' => 1,
							'endpoint' => 0,
						),
						array(
							'key' => 'field_5c4ad9961cc05',
							'label' => '',
							'name' => 'whitelist',
							'type' => 'group',
							'instructions' => 'In welchen Inhalten diese BOFU-Bar dargestellt werden soll.
		Falls keine definiert sind, ist sie für alle Inhalte freigegeben.
		
		Es handelt sich hierbei um Oder-Verknüpfungen.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layout' => 'block',
							'sub_fields' => array(
								array(
									'key' => 'field_5c4add10ca31b',
									'label' => 'Post-Types',
									'name' => 'post_types',
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'choices' => array(
									),
									'default_value' => array(
									),
									'allow_null' => 1,
									'multiple' => 1,
									'ui' => 1,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
								array(
									'key' => 'field_5c4add60ca31c',
									'label' => 'Seiten-Templates',
									'name' => 'templates',
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'choices' => array(
									),
									'default_value' => array(
									),
									'allow_null' => 1,
									'multiple' => 1,
									'ui' => 1,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
								array(
									'key' => 'field_5c519f9069e82',
									'label' => 'Kategorien',
									'name' => 'categories',
									'type' => 'select',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'choices' => array(
									),
									'default_value' => array(
									),
									'allow_null' => 1,
									'multiple' => 1,
									'ui' => 1,
									'ajax' => 0,
									'return_format' => 'value',
									'placeholder' => '',
								),
								array(
									'key' => 'field_5c4add7cca31d',
									'label' => 'Einzelseiten',
									'name' => 'pages',
									'type' => 'page_link',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'post_type' => '',
									'taxonomy' => '',
									'allow_null' => 1,
									'allow_archives' => 1,
									'multiple' => 1,
								),
							),
						),
					),
				),
				array(
					'key' => 'field_596631ea5a37f',
					'label' => 'Blacklist für einzelne Seiten',
					'name' => 'bofu_blacklist_pages',
					'type' => 'post_object',
					'instructions' => 'Hier können einzelne Seiten in die Blacklist hinzugefügt werden.',
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
					'allow_null' => 0,
					'multiple' => 1,
					'return_format' => 'id',
					'ui' => 1,
				),
				array(
					'key' => 'field_59663a83ba48d',
					'label' => 'Blacklist für Post Types',
					'name' => 'bofu_blacklist_posttypes',
					'type' => 'select',
					'instructions' => 'Hier können Post Types in die Blacklist hinzugefügt werden.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
					),
					'default_value' => array(
					),
					'allow_null' => 0,
					'multiple' => 1,
					'ui' => 1,
					'ajax' => 0,
					'placeholder' => '',
					'disabled' => 0,
					'readonly' => 0,
					'return_format' => 'value',
				),
				array(
					'key' => 'field_596768675612a',
					'label' => 'Autorenseite',
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
					'key' => 'field_5967688a5612b',
					'label' => 'Button Text',
					'name' => 'bofu_authordetails_btn_text',
					'type' => 'text',
					'instructions' => 'Welcher Text soll der Button auf den Autorenseiten bekommen? (Beispiel: Beraterprofil anfordern)',
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
					'readonly' => 0,
					'disabled' => 0,
				),
				array(
					'key' => 'field_596768e15612c',
					'label' => 'Button Link',
					'name' => 'bofu_authordetails_btn_link',
					'type' => 'page_link',
					'instructions' => 'Auf welche Seite soll der Button verlinken?',
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
					'allow_null' => 1,
					'multiple' => 0,
					'allow_archives' => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'acf-options-bottom-of-the-funnel',
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
}
add_action( 'acf/init', 'msq_bofu_add_fields_global_settings' );
