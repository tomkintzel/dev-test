<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_employment_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_employment_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'crum_page_custom_';
	
    $meta_boxes[] = array(
        'id'         => 'masonry_blog_params',
        'title'      => __('Select Blog parameters', 'dfd'),
        'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_on' => array(
            'key' => 'page-template',
            'value' => array(
                'tmp_employment_type.php',
            ),
        ),
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name' => __('Display posts of certain category?', 'dfd'),
                'desc' => __('Check, if you want to display posts from a certain category', 'dfd'),
                'id'   => 'blog_sort_category',
                'type' => 'checkbox'
            ),
            array(
                'name' => __('Blog Category', 'dfd'),
                'desc'  => __('Select blog category', 'dfd'),
                'id'    => 'blog_category',
                'taxonomy' => 'employment_type',
                'type' => 'taxonomy_multicheck',
            ),
            array (
                'name' => __('Number of posts ot display', 'dfd'),
                'desc'  => '',
                'id'    => 'blog_number_to_display',
                'type'  => 'text'
            ),
            array(
                'name' => __('Save image ratio for thumbnails', 'dfd'),
                'desc' => '',
                'id'   => 'save_image_ratio',
                'type' => 'checkbox'
            ),
        ),
    );
    
    $meta_boxes[] = array(
        'id' => $prefix . '_pagination_type',
        'title' => __('Portfolio pagination type', 'dfd'),
        'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_on' => array(
            'key' => 'page-template',
            'value' => array(
                'tmp_employment_type.php',
            ),
        ),
        'show_names' => true,
        'fields' => array(
            array(
                'name' => __('Pagination type', 'dfd'),
                'desc' => '',
                'id' => 'dfd_pagination_type',
                'type' => 'select',
                'std' => '0',
                'options' => array(
                    array(
                        'name' => __('Default', 'dfd'),
                        'value' => '0',
                    ),
                    array(
                        'name' => __('Ajax', 'dfd'),
                        'value' => '1'
                    ),
                    array(
                        'name' => __('Lazy load', 'dfd'),
                        'value' => '2'
                    ),
                ),
            ),
        ),
    );

	return $meta_boxes;
}
