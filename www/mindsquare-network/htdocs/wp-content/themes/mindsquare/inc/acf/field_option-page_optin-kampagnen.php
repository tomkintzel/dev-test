<?php
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5d42ecf1ba912',
        'title' => 'OptinMonster Kampagnen',
        'fields' => array(
            array(
                'key' => 'field_5d42ed069808f',
                'label' => 'OptinMonster Kampagnen',
                'name' => 'optinmonster_kampagnen',
                'type' => 'repeater',
                'instructions' => 'Dieser Filter hat nur Anwendung, wenn in den Output Settings von OptinMonster nichts eingetragen ist!',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5d42edc3c4d48',
                        'label' => 'Kampagne',
                        'name' => 'kampagne',
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
                            'Networking Brunch RZ10' => 'Networking Brunch RZ10',
                            'MSQ_Veranstaltungen 2019 Bild Seite' => 'MSQ_Veranstaltungen 2019 Bild Seite',
                            'Personalmarketing: Popup zu mindcareer (Footer/ITTalentDay)' => 'Personalmarketing: Popup zu mindcareer (Footer/ITTalentDay)',
                            'MSQ_mindsquare-Potentialförderung' => 'MSQ_mindsquare-Potentialförderung',
                            'MSQ_Stellenangebot_Banner_unten' => 'MSQ_Stellenangebot_Banner_unten',
                        ),
                        'default_value' => array(
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_5d42ee06c4d49',
                        'label' => 'Wo soll die Kampagne ausgespielt werden?',
                        'name' => 'output_location',
                        'type' => 'radio',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'optin_career' => 'Karriere - Seiten',
                            'optin_homepage' => 'Kunden - Seiten',
                        ),
                        'allow_null' => 0,
                        'other_choice' => 0,
                        'default_value' => '',
                        'layout' => 'vertical',
                        'return_format' => 'value',
                        'save_other_choice' => 0,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-optinmonster',
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