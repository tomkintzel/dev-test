<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_583ff09fcec92',
	'title' => 'Stellenanzeigen',
	'fields' => array(
		array(
			'key' => 'field_5840042c00bcd',
			'label' => 'Allgemein',
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
			'key' => 'field_583ff1265e56f',
			'label' => 'Untertitel',
			'name' => 'untertitel',
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
			'key' => 'field_595ccf848e61b',
			'label' => 'Position',
			'name' => 'position',
			'type' => 'true_false',
			'instructions' => 'Soll diese Stellenanzeige eine bessere Position bei den Suchergebnissen bekommen ?',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Bessere Position',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_584aa4ce0323c',
			'label' => '"Jetzt herunterladen"-Button entfernen',
			'name' => 'jetzt_herunterladen-button_entfernen',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_583ff41ef3a8b',
			'label' => 'Icons',
			'name' => 'icons',
			'type' => 'checkbox',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Schulung' => 'Weiterbildung als Unternehmenswert',
				'Zertifizierung' => 'Offizielle IT-Zertifizierungen',
				'Money' => 'Feste Gehaltssteigerungen',
				'Bonus' => 'Diverse Prämien',
				'karriere' => 'Karriere in alle Richtungen',
				'Firmenwagen' => 'Firmenwagen nach freier Auswahl',
				'Bahncard' => 'Bahncard',
				'Laptop' => 'Neuester Laptop',
				'Iphone' => 'iPhone der neuesten Generation',
				'Mentor-Coach' => 'Mentor / Coach',
				'Events' => 'Events ohne Ende',
				'Gaming' => 'Gamingclan',
				'Gesundheit' => 'Gesundheitsmaßnahmen',
				'Enterprise2.0' => 'Enterprise 2.0',
				'4TageWoche' => '4-Tage-Woche',
			),
			'allow_custom' => 0,
			'save_custom' => 0,
			'default_value' => array(
			),
			'layout' => 'horizontal',
			'toggle' => 1,
			'return_format' => 'value',
		),
		array(
			'key' => 'field_594cced60a5b0',
			'label' => 'Standorte',
			'name' => 'standorte',
			'type' => 'checkbox',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Bielefeld' => 'Bielefeld',
				'Düsseldorf' => 'Düsseldorf',
				'Hamburg' => 'Hamburg',
				'Berlin' => 'Berlin',
				'deutschlandweit' => 'deutschlandweit',
				'Braunschweig' => 'Braunschweig',
			),
			'allow_custom' => 0,
			'save_custom' => 0,
			'default_value' => array(
			),
			'layout' => 'horizontal',
			'toggle' => 0,
			'return_format' => 'value',
		),
		array(
			'key' => 'field_5954858583276',
			'label' => 'Bereiche',
			'name' => 'bereiche',
			'type' => 'checkbox',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'IT' => 'IT',
				'Marketing/Vertrieb' => 'Marketing/Vertrieb',
				'Personal' => 'Personal',
				'Verwaltung' => 'Verwaltung',
			),
			'allow_custom' => 0,
			'save_custom' => 0,
			'default_value' => array(
			),
			'layout' => 'horizontal',
			'toggle' => 1,
			'return_format' => 'value',
		),
		array(
			'key' => 'field_595487605e80d',
			'label' => 'Ähnliche Stellenanzeigen',
			'name' => 'employment_similar_ads',
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
				0 => 'employment_notice',
			),
			'taxonomy' => array(
			),
			'allow_null' => 0,
			'multiple' => 1,
			'return_format' => 'object',
			'ui' => 1,
		),
		array(
			'key' => 'field_5840044d00bce',
			'label' => 'Beschreibung',
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
			'key' => 'field_58401bbeec0c9',
			'label' => 'Wir bieten dir',
			'name' => 'wir_bieten_dir_ausfuhrlich',
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
			'key' => 'field_584025458716a',
			'label' => 'Video "Wir bieten dir"',
			'name' => 'video',
			'type' => 'oembed',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'width' => '',
			'height' => '',
		),
		array(
			'key' => 'field_5840046400bcf',
			'label' => 'Das bringst du mit',
			'name' => 'das_bringst_du_mit_ausfuhrlich',
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
			'key' => 'field_5950a7f96bebc',
			'label' => 'Bild "Das bringst du mit"',
			'name' => 'bild',
			'type' => 'image',
			'instructions' => 'Das Bild sollte eine minimale Größe von 300x178px oder 300x210px haben',
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
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_5ac4cb399eaba',
			'label' => 'Stellenangebot als PDF',
			'name' => 'pdf',
			'type' => 'file',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_size' => '',
			'max_size' => '',
			'mime_types' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'employment_notice',
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
?>
