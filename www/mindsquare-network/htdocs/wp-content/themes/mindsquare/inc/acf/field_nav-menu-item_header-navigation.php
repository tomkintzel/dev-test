<?php
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
								  'key' => 'group_5c133d1d3b18b',
								  'title' => 'Header - Navigation',
								  'fields' => array(
									  array(
										  'key' => 'field_5c133d45742b8',
										  'label' => 'Download',
										  'name' => 'menu_download',
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
											  0 => 'downloads',
										  ),
										  'taxonomy' => array(
										  ),
										  'allow_null' => 1,
										  'multiple' => 0,
										  'return_format' => 'id',
										  'ui' => 1,
									  ),
									  array(
										  'key' => 'field_5c75660603a1d',
										  'label' => 'Breite',
										  'name' => 'menu_multi_column',
										  'type' => 'true_false',
										  'instructions' => 'Welche Breite sollte das Menü haben?',
										  'required' => 0,
										  'conditional_logic' => 0,
										  'wrapper' => array(
											  'width' => '',
											  'class' => '',
											  'id' => '',
										  ),
										  'message' => '',
										  'default_value' => 1,
										  'ui' => 1,
										  'ui_on_text' => 'Volle Breite',
										  'ui_off_text' => 'Standard',
									  ),
									  array(
										  'key' => 'field_5c133da7742b9',
										  'label' => 'Position',
										  'name' => 'menu_position',
										  'type' => 'select',
										  'instructions' => 'Nur bei voller Breite relevant',
										  'required' => 0,
										  'conditional_logic' => 0,
										  'wrapper' => array(
											  'width' => '',
											  'class' => '',
											  'id' => '',
										  ),
										  'choices' => array(
											  'left' => 'Links',
											  'middle' => 'Mitte',
											  'right' => 'Rechts',
										  ),
										  'default_value' => array(
										  ),
										  'allow_null' => 0,
										  'multiple' => 0,
										  'ui' => 1,
										  'ajax' => 0,
										  'return_format' => 'value',
										  'placeholder' => '',
									  ),
								  ),
								  'location' => array(
									  array(
										  array(
											  'param' => 'nav_menu_item',
											  'operator' => '==',
											  'value' => 'location/primary_navigation',
										  ),
									  ),
									  array(
										  array(
											  'param' => 'nav_menu_item',
											  'operator' => '==',
											  'value' => 'location/karriere_primary_navigation',
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
