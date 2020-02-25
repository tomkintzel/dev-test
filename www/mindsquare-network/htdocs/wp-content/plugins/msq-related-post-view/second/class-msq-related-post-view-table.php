<?php 
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Related_Post extends WP_List_Table {

	
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Verwandter Beitrag', 'sp' ), 
			'plural'   => __( 'Verwandte Beiträge', 'sp' ), 
			'ajax'     => false 
		] );

	}



	


	/**
	 * Daten aus der Datenbank erhalten.
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */ 
	public static function get_posts( $per_page = 200, $page_number = 1 ) {

		global $wpdb;
		
		$sql = "SELECT {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_title, concat('<a href =\"',{$wpdb->prefix}posts.guid,'\">',{$wpdb->prefix}posts.guid,'</a>') as guid  
		FROM {$wpdb->prefix}posts 
		WHERE {$wpdb->prefix}posts.post_status = 'publish' AND {$wpdb->prefix}posts.post_type = 'post' AND {$wpdb->prefix}posts.ID NOT IN 
		(SELECT {$wpdb->prefix}postmeta.post_id FROM {$wpdb->prefix}postmeta WHERE {$wpdb->prefix}postmeta.meta_key = 'rp_post')";
		/* if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= "GROUP BY {$wpdb->prefix}posts.ID, {$wpdb->prefix}postmeta.meta_value ORDER BY " . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC ';
		}else{
			$sql .= "GROUP BY {$wpdb->prefix}posts.ID , {$wpdb->prefix}postmeta.meta_value  ORDER BY anzahl DESC, pm1.post_id ASC";
		} */
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		/* var_dump( $sql ); exit; */
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}





	/**
	 * Gibt die Anzahl der gefunden Einträge ab
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT({$wpdb->prefix}posts.ID), {$wpdb->prefix}posts.post_title, concat('<a href =\"',{$wpdb->prefix}posts.guid,'\">',{$wpdb->prefix}posts.guid,'</a>') as guid  
		FROM {$wpdb->prefix}posts 
		WHERE {$wpdb->prefix}posts.post_status = 'publish' AND {$wpdb->prefix}posts.post_type = 'post' AND {$wpdb->prefix}posts.ID NOT IN 
		(SELECT {$wpdb->prefix}postmeta.post_id FROM {$wpdb->prefix}postmeta WHERE {$wpdb->prefix}postmeta.meta_key = 'rp_post')";

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'Keine Einträge vorhanden.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'ID':
			case 'post_title':
			case 'guid':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}



	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'ID'       		=> __( 'Post ID', 'sp' ),
			'post_title'	=> __( 'Titel' , 'sp' ),
			'guid'			=> __( 'URL' , 'sp' )
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'ID'       		=> array( 'ID' , true),
			'post_title'	=> array( 'post_title', false ),
			'guid'			=> array( 'guid', false ),
		);
		return $sortable_columns;
	}

	


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();
		$per_page     = $this->get_items_per_page( 'posts_per_page', 200 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );
		$this->items = self::get_posts( $per_page, $current_page );
	}

	

}