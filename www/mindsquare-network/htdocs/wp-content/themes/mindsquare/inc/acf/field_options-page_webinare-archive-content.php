<?php
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5c4ae2416098d',
        'title' => 'Webinare - Einstiegsseite',
        'fields' => array(
            array(
                'key' => 'field_5c80cacca087c',
                'label' => 'Überschrift der Webinare-Übersichtsseite',
                'name' => 'webinare_header_section_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => 'Webinare',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5c4ae24183733',
                'label' => 'Webinare im Header',
                'name' => 'webinare_header_section',
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
                    0 => 'webinare',
                ),
                'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
            ),
            array(
                'key' => 'field_5c4ae2418491c',
                'label' => 'Überschrift vom oberen Text Bereich',
                'name' => 'webinare_first_text_title',
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
                'key' => 'field_5c4ae24184cfe',
                'label' => 'Oberer Text Bereich',
                'name' => 'webinare_first_text_body',
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
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-msq-webinare',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            0 => 'the_content',
        ),
        'active' => 1,
        'description' => '',
    ));
    
    endif;