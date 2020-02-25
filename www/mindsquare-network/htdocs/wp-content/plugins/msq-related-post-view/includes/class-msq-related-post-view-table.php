<?php 
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Related_Post_View extends WP_List_Table {

	
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
		$blog_url = get_site_url() . '?p=';

		$sql = "SELECT {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_title, concat('<a href=\"" . $blog_url . "',{$wpdb->prefix}posts.id,'\">" . $blog_url . "',{$wpdb->prefix}posts.id,'</a>') as guid , {$wpdb->prefix}postmeta.meta_value, postid2.post_title as rp_posttitle, concat('<a href=\"" . $blog_url . "',postid2.id,'\">" . $blog_url . "',postid2.id,'</a>') as rp_postguid, COUNT(pm1.meta_value) as anzahl
		FROM {$wpdb->prefix}posts 
		LEFT JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID
		LEFT JOIN {$wpdb->prefix}posts AS postid2 ON {$wpdb->prefix}postmeta.meta_value = postid2.ID 
		LEFT Join {$wpdb->prefix}postmeta pm1 on {$wpdb->prefix}posts.ID = pm1.post_id AND pm1.meta_key = 'rp_post' WHERE {$wpdb->prefix}postmeta.meta_key = 'rp_post' ";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= "GROUP BY {$wpdb->prefix}posts.ID, {$wpdb->prefix}postmeta.meta_value ORDER BY " . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC ';
		}else{
			$sql .= "GROUP BY {$wpdb->prefix}posts.ID , {$wpdb->prefix}postmeta.meta_value  ORDER BY anzahl DESC, pm1.post_id ASC";
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		/*  var_dump( $sql );exit;  */

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

		$sql = "SELECT COUNT({$wpdb->prefix}posts.ID)
		FROM {$wpdb->prefix}posts 
		LEFT JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID 
		LEFT JOIN {$wpdb->prefix}posts AS postid2 ON {$wpdb->prefix}postmeta.meta_value = postid2.ID 
		WHERE {$wpdb->prefix}postmeta.meta_key = 'rp_post'";

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
			case 'guid':
			case 'post_title':
            case 'meta_value': 
			case 'rp_posttitle':
			case 'rp_postguid':
			case 'anzahl':
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
			'guid'			=> __( 'URL' , 'sp' ),
			'post_title'	=> __( 'Title' , 'sp' ),
            'meta_value'    => __( 'RP_ID', 'sp'),
            'rp_posttitle'  => __( 'RP_PostTitle', 'sp' ),
			'rp_postguid'   => __( 'RP_URL', 'sp' ),
			'anzahl'		=> __( 'Anzahl', 'sp' )
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
			'guid'			=> array( 'guid', false ),
			'post_title'	=> array( 'post_title', false ),
			'meta_value'    => array( 'meta_value', false ),
			'rp_posttitle'	=> array( 'rp_posttitle', false ),
			'rp_postguid'	=> array( 'rp_postguid', false ),
			'anzahl'		=> array( 'anzahl', false )
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