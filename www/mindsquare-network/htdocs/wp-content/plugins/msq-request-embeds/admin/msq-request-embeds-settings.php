<?php 




if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Request Embeds Einstellungen',
		'menu_title'	=> 'Request Embeds Einstellungen',
        'menu_slug' 	=> 'request-embeds-settings',
        'parent_slug'   => 'options-general.php',
		'capability'	=> 'manage-options',
    ));
    
}



if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5df8b646cf994',
        'title' => 'Request Embeds - Einstellungen',
        'fields' => array(
            array(
                'key' => 'field_5df8b6657bbc6',
                'label' => 'Request Embeds - Pardot Formular',
                'name' => 'request_embeds_pardot',
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
                    'value' => 'request-embeds-settings',
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