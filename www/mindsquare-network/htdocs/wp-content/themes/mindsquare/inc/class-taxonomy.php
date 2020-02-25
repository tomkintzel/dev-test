<?php
/**
 */
abstract class MSQ_Taxonomy {
	/**
	 * @access protected
	 * @var string $name The unique name of this post type.
	 */
	protected $name;

	/**
	 * @access protected
	 * @var array $labels An array of labels for this taxonomy.
	 */
	protected $labels;

	/**
	 * @access protected
	 * @var array $object_type Name of the object type for the taxonomy object.
	 */
	protected $object_types;

	/**
	 * @access protected
	 * @var array $args An array of arguments to create the taxonomy.
	 */
	protected $args;

	/**
	 * @access public
	 */
	public function __construct() {
		$this->define_labels();
		$this->define_object_types();
		$this->define_args();
		$this->define_post_type_hooks();
	}

	/**
	 * @access protected
	 */
	protected function define_labels() {
		$this->labels = array(
			'name' => _x( 'Writers', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name' => _x( 'Writer', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name' => _x( 'Writers', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar' => _x( 'Writer', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new' => _x( 'Add New', 'writer', 'your-plugin-textdomain' ),
			'add_new_item' => __( 'Add New writer', 'your-plugin-textdomain' ),
			'new_item' => __( 'New writer', 'your-plugin-textdomain' ),
			'edit_item' => __( 'Edit writer', 'your-plugin-textdomain' ),
			'view_item' => __( 'View writer', 'your-plugin-textdomain' ),
			'all_items' => __( 'All witer', 'your-plugin-textdomain' ),
			'search_items' => __( 'Search writer', 'your-plugin-textdomain' ),
			'parent_item_colon' => __( 'Parent writer:', 'your-plugin-textdomain' ),
			'not_found' => __( 'No writer found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No writer found in Trash.', 'your-plugin-textdomain' )
		);
	}

	/**
	 * @access protected
	 */
	protected function define_object_types() {
		$this->$object_types = 'book';
	}

	/**
	 * @access protected
	 */
	protected function define_args() {
		$this->args = array(
			'labels' => $this->labels,
			'public' => false,
			'show_ui' => true,
			'hierarchical' => true,
			'show_in_menu' => true,
			'show_tagcloud' => false,
			'show_in_nav_menus' => false,
			'show_admin_column' => true,
			'rewrite' => array( 'slug' => 'writer' )
		);
	}

	/**
	 * @access public
	 */
	final public function define_post_type_hooks() {
		theme()->loader->add_action( 'init', $this, 'register_taxonomy' );
	}

	/**
	 * @access public
	 */
	final public function register_taxonomy() {
		register_taxonomy( $this->name, $this->object_types, $this->args );
	}
}
?>
