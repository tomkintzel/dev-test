<?php
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_5993f51244942',
		'title' => 'Chat Einstellungen für Karriere-Seiten',
		'fields' => array (
			array (
				'key' => 'field_5993f51ec8e61',
				'label' => 'Online?',
				'name' => 'chat-online',
				'type' => 'radio',
				'instructions' => 'Soll der Chat Online oder Offline sein? Wenn der Chat Online ist, wird er nur in den Zeiten zwischen 9:30 - 16:00 Uhr angezeigt.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					'online' => 'Online',
					'offline' => 'Offline',
				),
				'allow_null' => 0,
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'online',
				'layout' => 'horizontal',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acf-options-chat',
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