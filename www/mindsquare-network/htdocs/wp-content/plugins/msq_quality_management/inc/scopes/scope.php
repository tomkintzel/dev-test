<?php
namespace MSQ\Plugin\Quality_Management\Scopes;
use MSQ\Plugin\Quality_Management\Columns\Collumn_Collection;

/** */
class Scope {
	/** @var string $name */
	private $name;

	/** @var string $page_slug */
	private $page_slug;

	/** @var string $parent_slug */
	private $parent_slug;

	/** @var string $page_title */
	private $page_title;

	/** @var string $menu_title */
	private $menu_title;

	/** @var string $capability */
	private $capability;

	/** @var string $hookname */
	private $hookname;

	/** @var Column_Collection $column_collection */
	private $column_collection;

	/** @var Table $table */
	private $table;

	/** @var string $table_class_name */
	private $table_class_name;

	/**
	 * @param string $name
	 * @param string $page_slug
	 * @param string $parent_slug
	 * @param string $page_title
	 * @param string $menu_title
	 * @param string $capability
	 */
	public function __construct( $name, $page_slug, $parent_slug, $page_title, $menu_title, $capability, $column_collection, $table_class_name ) {
		// vars
		$this->name = $name;
		$this->page_slug = $page_slug;
		$this->parent_slug = $parent_slug;
		$this->page_title = $page_title;
		$this->menu_title = $menu_title;
		$this->capability = $capability;
		$this->column_collection = $column_collection;
		$this->table_class_name = $table_class_name;

		// Hooks
		self::add_action( 'admin_menu', [ $this, 'register_submenu_page' ], 11 );
	}

	/** */
	public function register_submenu_page() {
		// vars
		$this->hookname = add_submenu_page( $this->get_parent_slug(), $this->get_page_title(), $this->get_menu_title(), $this->get_capability(), $this->get_page_slug(), [ $this, 'display_page' ] );

		// Hooks
		self::add_action( 'load-' . $this->hookname, [ $this, 'load_page' ] );
	}

	/** */
	public function load_page() {
		$table = $this->get_table();
		if( is_callable( [ $table, 'load_page' ] ) ) {
			call_user_func( [ $table, 'load_page' ] );
		}
	}

	/** */
	public function display_page() {
		$table = $this->get_table();
		if( is_callable( [ $table, 'display_page' ] ) ) {
			call_user_func( [ $table, 'display_page' ] );
		}
	}

	/** @return string */
	public function get_name() {
		return $this->name;
	}

	/** @return string */
	public function get_page_slug() {
		return $this->page_slug;
	}

	/** @return string */
	public function get_parent_slug() {
		return $this->parent_slug;
	}

	/** @return string */
	public function get_capability() {
		return $this->capability;
	}

	/** @return string */
	public function get_page_title() {
		return $this->page_title;
	}

	/** @return string */
	public function get_menu_title() {
		return $this->menu_title;
	}

	/** @return string */
	public function get_hookname() {
		return $this->hookname;
	}

	/** @return Column_Collection */
	public function get_column_collection() {
		return $this->column_collection;
	}

	/** @return Table */
	public function get_table() {
		if( empty( $this->table ) && did_action( 'load-' . $this->hookname ) ) {
			$this->table = new $this->table_class_name( $this->get_page_slug(), $this->get_page_title(), $this );
		}
		return $this->table;
	}

	/**
	 * @param string $action
	 * @param Closure $callback
	 */
	protected static function add_action( $action, $callback, $priority = 10, $accepted_args = 1 ) {
		if( did_action( $action ) ) {
			call_user_func( $callback );
		} else {
			add_action( $action, $callback, $priority, $accepted_args  );
		}
	}
}
