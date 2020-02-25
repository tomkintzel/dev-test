<?php
/**
 */
abstract class MSQ_Post_Type {
	/**
	 * @access protected
	 * @var string $name The unique name of this post type.
	 */
	protected $name;

	/**
	 * @access protected
	 * @var array $labels An array of labels for this post type.
	 */
	protected $labels;

	/**
	 * @access protected
	 * @var array $supports An array of features to add.
	 */
	protected $supports;

	/**
	 * @access protected
	 * @var array $args An array of arguments to create the post type.
	 */
	protected $args;

	/**
	 * @access public
	 */
	public function __construct() {
		$this->define_labels();
		$this->define_supports();
		$this->define_args();
		$this->define_post_type_hooks();
	}

	/**
	 * @access protected
	 */
	protected function define_labels() {
		$this->labels = array(
			'name' => _x( 'Books', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name' => _x( 'Book', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name' => _x( 'Books', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar' => _x( 'Book', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new' => _x( 'Add New', 'book', 'your-plugin-textdomain' ),
			'add_new_item' => __( 'Add New Book', 'your-plugin-textdomain' ),
			'new_item' => __( 'New Book', 'your-plugin-textdomain' ),
			'edit_item' => __( 'Edit Book', 'your-plugin-textdomain' ),
			'view_item' => __( 'View Book', 'your-plugin-textdomain' ),
			'all_items' => __( 'All Books', 'your-plugin-textdomain' ),
			'search_items' => __( 'Search Books', 'your-plugin-textdomain' ),
			'parent_item_colon' => __( 'Parent Books:', 'your-plugin-textdomain' ),
			'not_found' => __( 'No books found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No books found in Trash.', 'your-plugin-textdomain' )
		);
	}

	/**
	 * @access protected
	 */
	protected function define_supports() {
		$this->supports = array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'comments',
			'revisions'
		);
	}

	/**
	 * @access protected
	 */
	protected function define_args() {
		$this->args = array(
			'labels' => $this->labels,
			'description' => __( 'Description.', 'your-plugin-textdomain' ),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'book' ),
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => $this->supports
		);
	}

	/**
	 * @access public
	 */
	final public function define_post_type_hooks() {
		theme()->loader->add_action( 'init', $this, 'register_post_type' );
	}

	/**
	 * @access public
	 */
	final public function register_post_type() {
		register_post_type( $this->name, $this->args );
	}
}
?>
