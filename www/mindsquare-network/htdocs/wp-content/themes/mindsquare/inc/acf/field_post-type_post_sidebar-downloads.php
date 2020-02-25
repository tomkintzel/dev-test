<?php
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5c0fb4203eb08',
        'title' => 'Downloads in der Sidebar',
        'fields' => array(
            array(
                'key' => 'field_5c0fb4456fa55',
                'label' => 'Downloads',
                'name' => 'sidebar-downloads',
                'type' => 'post_object',
                'instructions' => 'Wenn du hier Downloads auswählst, wird der Inhalt der kompletten Sidebar auf der rechten Seite überschrieben.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'downloads',
                ),
                'taxonomy' => array(
                ),
                'allow_null' => 0,
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
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