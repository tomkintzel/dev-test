<?php 
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Related_Post_View_Switch extends WP_List_Table {

	
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

		$sql = "SELECT {$wpdb->prefix}postmeta.meta_value as rp_meta, postid2.post_title as rp_posttitle, concat('<a href=\"" . $blog_url . "',postid2.id,'\">" . $blog_url . "',postid2.id,'</a>') as rp_postguid, {$wpdb->prefix}posts.ID, {$wpdb->prefix}posts.post_title,
		concat('<a href=\"" . $blog_url . "',{$wpdb->prefix}posts.id,'\">" . $blog_url . "',{$wpdb->prefix}posts.id,'</a>') as guid, pm2.anzahl
		FROM {$wpdb->prefix}posts 
		LEFT JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID 
		LEFT JOIN {$wpdb->prefix}posts AS postid2 ON {$wpdb->prefix}postmeta.meta_value = postid2.ID 
		LEFT JOIN (SELECT pm1.meta_value, count(pm1.post_id) as anzahl 
		FROM {$wpdb->prefix}postmeta pm1 WHERE pm1.meta_key = 'rp_post' 
		GROUP BY pm1.meta_value) as pm2 ON {$wpdb->prefix}postmeta.meta_value = pm2.meta_value 
		WHERE {$wpdb->prefix}postmeta.meta_key = 'rp_post' ";
		if ( !empty( $_REQUEST['orderby'] ) ) {
			if ( $_REQUEST['orderby'] == 'RP_Meta' ) {
				$sql .= "ORDER BY cast(" . esc_sql( $_REQUEST['orderby'] ) . ' as unsigned)';
			}else {
				$sql .= "ORDER BY " . esc_sql( $_REQUEST['orderby'] );
			}
			$sql .= !empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC ';
		}else{
			$sql .= "ORDER BY pm2.anzahl ASC";
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
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
		$sql = "SELECT COUNT({$wpdb->prefix}postmeta.meta_value)
		FROM {$wpdb->prefix}posts 
		LEFT JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID 
		LEFT JOIN {$wpdb->prefix}posts AS postid2 ON {$wpdb->prefix}postmeta.meta_value = postid2.ID 
		LEFT JOIN (SELECT pm1.meta_value, count(pm1.post_id) as anzahl 
		FROM {$wpdb->prefix}postmeta pm1 WHERE pm1.meta_key = 'rp_post' 
		GROUP BY pm1.meta_value) as pm2 ON {$wpdb->prefix}postmeta.meta_value = pm2.meta_value 
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
			case 'rp_meta':
			case 'rp_posttitle':
			case 'rp_postguid':
            case 'ID': 
			case 'post_title':
			case 'guid':
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
			'rp_meta'    	=> __( 'RP_Meta', 'sp'),
            'rp_posttitle'  => __( 'RP_PostTitle', 'sp' ),
			'rp_postguid'   => __( 'RP_Postguid', 'sp' ),
			'ID'       		=> __( 'Post_ID', 'sp' ),
			'post_title'	=> __( 'post_title' , 'sp' ),
			'guid'			=> __( 'GUID' , 'sp' ),
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
			'rp_meta'    	=> array( 'RP_Meta' , true),
			'rp_posttitle'	=> array( 'RP_PostTitle', false ),
			'rp_postguid'	=> array( 'RP_Postguid', false ),
			'ID'    		=> array( 'Post_ID', true ),
			'post_title'	=> array( 'post_title', false ),
			'guid'			=> array( 'GUID', false ),
			'anzahl'		=> array( 'Anzahl', false )
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