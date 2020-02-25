<?php
function fb_add_acf_option_pages(){
	fb_add_options_page_fb();
}
add_action('acf/init', 'fb_add_acf_option_pages');


function fb_add_acf_fields(){
	fb_add_fields_for_conversion_page();
	fb_add_filters_for_conversion_page();
	fb_add_fields_for_fb_global_settings();
}
add_action('acf/init', 'fb_add_acf_fields');

function fb_add_filters_for_conversion_page() {
	add_filter('acf/load_value/name=cp_background_image',function ($value, $post_id, $field) {
		$cp_standard_hintergrundbild=get_field('cp_standard_hintergrundbild', 'option');
			 if (!isset($value)) $value=$cp_standard_hintergrundbild['id'];
		 return $value;
	},10,3);
}

function fb_add_options_page_fb() {
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Globale Einstellungen für Conversionpage',
		'menu_title'	=> 'Einstellungen',
		'menu_slug'		=> 'acf-options-conversionpage-optionen',
		'parent_slug'	=> 'edit.php?post_type=conversionpages',
		'capability'	=> 'manage_options'
	));
}

/* 
	Laedt Template Auswahl für Conversion Pages (ACF)
	Bastian Plohr
*/
function acf_load_cv_template_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();
    
    
	$path    = plugin_dir_path(__FILE__) . 'templates';
	$templatefiles = array_diff(scandir($path), array('.', '..'));

    

    
    // loop through array and add to field 'choices'
    if( is_array($templatefiles) ) {
        
        foreach( $templatefiles as $templatefile ) {
            $template_data = get_file_data( "$path/$templatefile" , array( 'Template Name' => 'Template Name' ) );
			$filename=basename($templatefile,".php");
			$template_name = $filename;
			if (isset($template_data["Template Name"])&&strlen($template_data["Template Name"])>0) {
				$template_name=$template_data["Template Name"];
				$field['choices'][ $filename ] = $template_name;
			}
            
            
        }
        
    }
    

    // return the field
    return $field;
    
}

if( is_admin() ) {
	add_filter('acf/load_field/name=cv_template', 'acf_load_cv_template_choices');
}

// Felder für Custom Post Type Conversion Page
function fb_add_fields_for_conversion_page(){
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_58492a3842aa0',
			'title' => 'Conversion Page',
			'fields' => array(
				array(
					'key' => 'field_58494f8b4e9cb',
					'label' => 'Template',
					'name' => 'cv_template',
					'type' => 'select',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'template-conversionpage' => 'Conversion Page',
						'template-erlsoft-test' => 'Erlebe Software - Test',
						'template-sales-summit' => 'Sales Summit 2018',
						'template-schnellbewerbung' => 'MSQ-Schnellbewerbung',
						'template-seminare' => 'Schulungen',
					),
					'default_value' => array(
					),
					'allow_null' => 0,
					'multiple' => 0,
					'ui' => 0,
					'ajax' => 0,
					'placeholder' => '',
					'disabled' => 0,
					'readonly' => 0,
					'return_format' => 'value',
				),
				array(
					'key' => 'field_588e513d7ba99',
					'label' => 'Allgemein',
					'name' => '',
					'type' => 'tab',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '!=',
								'value' => 'template-schnellbewerbung',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'placement' => 'top',
					'endpoint' => 0,
				),
				array(
					'key' => 'field_5a27cb61b5957',
					'label' => 'Header Farbe',
					'name' => 'header_farbe',
					'type' => 'color_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
				),
				array(
					'key' => 'field_5a27e3dcb9eac',
					'label' => 'Innere Header Farbe',
					'name' => 'innere_header_farbe',
					'type' => 'color_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
				),
				array(
					'key' => 'field_5885de3f65bb6',
					'label' => 'Untertitel',
					'name' => 'untertitel',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
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
					'key' => 'field_584962eb44b45',
					'label' => 'Hintergrundbild',
					'name' => 'cp_background_image',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
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
					'key' => 'field_58492a6e05ec9',
					'label' => 'Vorschau Bild',
					'name' => 'image',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-erlsoft-test',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
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
					'key' => 'field_58983712e91c3',
					'label' => 'Vorschau Video',
					'name' => 'vorschau_video',
					'type' => 'oembed',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'width' => '',
					'height' => '',
				),
				array(
					'key' => 'field_598aa7a8e237a',
					'label' => 'Autoren Beschreibung',
					'name' => 'autoren_beschreibung',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
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
					'key' => 'field_59369176cb1b0',
					'label' => 'Fallback-Image für den Autoren',
					'name' => 'schulungen_image',
					'type' => 'image',
					'instructions' => 'Dieses Bild wird verwendet, wenn kein Autor gefunden wurde.',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-seminare',
							),
						),
					),
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
					'key' => 'field_59369110cb1af',
					'label' => 'Fallback-Beschreibung für Autoren',
					'name' => 'schulungen_autoren_subline',
					'type' => 'wysiwyg',
					'instructions' => 'Dieses Beschreibung wird verwendet, wenn kein Autor gefunden wurde.',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-seminare',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 0,
				),
				array(
					'key' => 'field_5984785073b73',
					'label' => 'Email Adresse',
					'name' => 'cp_email_adresse',
					'type' => 'text',
					'instructions' => 'Info Mail Adresse (wird im Footer der Conversionpages verwendet). Als Fallback werden die Globalen Einstellungen der Conversion-Page verwendet.',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-seminare',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
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
					'key' => 'field_598478e173b74',
					'label' => 'Telefonnummer',
					'name' => 'cp_telefonnummer',
					'type' => 'text',
					'instructions' => 'Telefonnummer des Fachbereichs (wird im Footer der Conversionpages verwendet). Als Fallback werden die Globalen Einstellungen der Conversion-Page verwendet.',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-seminare',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
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
					'key' => 'field_598bfcd1c3901',
					'label' => 'Formular Position',
					'name' => 'formular_position',
					'type' => 'checkbox',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-erlsoft-test',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'Pardot-Formular unter der Beschreibung anzeigen' => 'Pardot-Formular unter der Beschreibung anzeigen',
					),
					'default_value' => array(
					),
					'layout' => 'vertical',
					'toggle' => 0,
					'allow_custom' => 0,
					'save_custom' => 0,
					'return_format' => 'value',
				),
				array(
					'key' => 'field_588e51577ba9a',
					'label' => 'Formular',
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
					'key' => 'field_584982a287d29',
					'label' => 'Formular Titel',
					'name' => 'pardot_titel',
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
					'readonly' => 0,
					'disabled' => 0,
				),
				array(
					'key' => 'field_584981ae54625',
					'label' => 'Pardot',
					'name' => 'pardot',
					'type' => 'pardot',
					'instructions' => '',
					'required' => 1,
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
					'ui' => 1,
					'ajax' => 0,
					'placeholder' => '',
					'disabled' => 0,
					'readonly' => 0,
					'multiple' => 0,
				),
				array(
					'key' => 'field_588e518e7ba9b',
					'label' => 'Stichpunkte',
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
					'key' => 'field_58861802ba0b4',
					'label' => 'Stichpunkte Überschrift',
					'name' => 'bullet_title',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-seminare',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
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
					'key' => 'field_59886ef7df08e',
					'label' => 'Bulletpoints Beschreibung',
					'name' => 'bulletpoints_beschreibung',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-erlsoft-test',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 0,
				),
				array(
					'key' => 'field_58497392a2db4',
					'label' => 'Beschreibung',
					'name' => 'beschreibung',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '!=',
								'value' => 'template-schnellbewerbung',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 0,
				),
				array(
					'key' => 'field_58497e499153c',
					'label' => 'Bulletpoints',
					'name' => 'bulletlist',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-conversionpage',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-sales-summit',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'table',
					'button_label' => 'Eintrag hinzufügen',
					'sub_fields' => array(
						array(
							'key' => 'field_58497e5d9153d',
							'label' => 'Feature',
							'name' => 'bullet_title',
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
							'readonly' => 0,
							'disabled' => 0,
						),
						array(
							'key' => 'field_58497e699153e',
							'label' => 'Beschreibung',
							'name' => 'bullet_description',
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
							'readonly' => 0,
							'disabled' => 0,
						),
					),
				),
				array(
					'key' => 'field_5936bab466577',
					'label' => 'Icon-Klasse der Bulletpoints',
					'name' => 'schulungen_list_icon',
					'type' => 'text',
					'instructions' => 'Die Klasse von Bootstrap oder Font-Awesome hier einfügen
		(Bootstrap) glyphicon glyphicon-example
		(Fontawesome) fa fa-check',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-seminare',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'fa fa-check',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array(
					'key' => 'field_59369243cb1b1',
					'label' => 'Fallback-Liste für die Bulletpoints',
					'name' => 'schulungen_bulletlist',
					'type' => 'repeater',
					'instructions' => 'Diese Liste von Bulletpoints wird verwendet, wenn keine Informationen gefunden wurden.',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-seminare',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'table',
					'button_label' => 'Eintrag hinzufügen',
					'sub_fields' => array(
						array(
							'key' => 'field_59369260cb1b2',
							'label' => 'Feature',
							'name' => 'schulungen_bullet_title',
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
							'readonly' => 0,
							'disabled' => 0,
						),
						array(
							'key' => 'field_59369276cb1b3',
							'label' => 'Beschreibung',
							'name' => 'schulungen_bullet_description',
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
							'readonly' => 0,
							'disabled' => 0,
						),
					),
				),
				array(
					'key' => 'field_5968861e2860d',
					'label' => 'Trusted Icons',
					'name' => 'trusted_icons',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-erlsoft-test',
							),
						),
						array(
							array(
								'field' => 'field_58494f8b4e9cb',
								'operator' => '==',
								'value' => 'template-schnellbewerbung',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => 'field_5968868e2860e',
					'min' => 0,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Icon hinzufügen',
					'sub_fields' => array(
						array(
							'key' => 'field_5968868e2860e',
							'label' => 'Icon',
							'name' => 'icon',
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
							'preview_size' => 'author-thumb',
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
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'conversionpages',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'acf_after_title',
			'style' => 'seamless',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => 1,
			'description' => '',
		));

	endif;
}

function fb_add_fields_for_fb_global_settings(){
	acf_add_local_field_group(array(
		'key' => 'group_588b83e1af0dd',
		'title' => 'Conversionpage Optionen',
		'fields' => array(
			array(
				'key' => 'field_588b840cd7115',
				'label' => 'Email Adresse',
				'name' => 'cp_email_adresse',
				'type' => 'email',
				'instructions' => 'Info Mail Adresse (wird im Footer der Conversionpages verwendet)',
				'required' => 1,
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
			),
			array(
				'key' => 'field_588b8493d7116',
				'label' => 'Telefonnummer',
				'name' => 'cp_telefonnummer',
				'type' => 'text',
				'instructions' => 'Telefonnummer des Fachbereichs (wird im Footer der Conversionpages verwendet)',
				'required' => 1,
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
				'key' => 'field_58986898ba0af',
				'label' => 'Standard Hintergrundbild',
				'name' => 'cp_standard_hintergrundbild',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'preview_size' => 'post-thumb',
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
				'key' => 'field_58aab860b359b',
				'label' => 'Trusted Icons',
				'name' => 'trusted_icons',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_58aab889b359c',
				'min' => 0,
				'max' => 0,
				'layout' => 'row',
				'button_label' => 'Icon hinzufügen',
				'sub_fields' => array(
					array(
						'key' => 'field_58aab889b359c',
						'label' => 'Icon',
						'name' => 'icon',
						'type' => 'image',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'preview_size' => 'author-thumb',
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
			array(
				'key' => 'field_5b977fde8b50e',
				'label' => 'Danke-Seite für Schulungen',
				'name' => 'seminar_completion_page',
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
					'value' => 'acf-options-conversionpage-optionen',
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
}

?>
