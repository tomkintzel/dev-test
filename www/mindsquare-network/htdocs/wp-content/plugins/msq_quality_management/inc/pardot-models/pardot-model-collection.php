<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Models;

/**
 */
class Pardot_Model_Collection {
	/** @var string OPTION_NAME */
	const OPTION_NAME = 'msq_pardot_data_%s';

	/** @var int OPTION_CHUNK_SIZE */
	const OPTION_CHUNK_SIZE = 50;

	/** @var Campaign[] $campaigns */
	private $campaigns = [];

	/** @var Email_Template[] $email_template */
	private $email_templates = [];

	/** @var Folder[] $folders */
	private $folders = [];

	/** @var Form[] $forms */
	private $forms = [];

	/** @var Layout_Template[] $layout_templates */
	private $layout_templates = [];

	/** @var Tag[] $tags */
	private $tags = [];

	/** @var Tracking_Domain[] $tracking_domains */
	private $tracking_domains = [];

	/** @var User[] $users */
	private $users = [];

	/** */
	public function __construct() {
		$this->load();
	}

	/**
	 * Lädt die gefundenen Daten aus der Datenbank.
	 * @see https://developer.wordpress.org/reference/functions/get_option/
	 */
	public function load() {
		$this->campaigns = $this->get_option( sprintf( self::OPTION_NAME, 'campaigns' ) ) ?? [];
		$this->email_templates = $this->get_option( sprintf( self::OPTION_NAME, 'email_templates' ) ) ?? [];
		$this->folders = $this->get_option( sprintf( self::OPTION_NAME, 'folders' ) ) ?? [];
		$this->forms = $this->get_option( sprintf( self::OPTION_NAME, 'forms' ) ) ?? [];
		$this->layout_templates = $this->get_option( sprintf( self::OPTION_NAME, 'layout_templates' ) ) ?? [];
		$this->tags = $this->get_option( sprintf( self::OPTION_NAME, 'tags' ) ) ?? [];
		$this->tracking_domains = $this->get_option( sprintf( self::OPTION_NAME, 'tracking_domains' ) ) ?? [];
		$this->users = $this->get_option( sprintf( self::OPTION_NAME, 'users' ) ) ?? [];
	}

	/**
	 * Speichert die gefundenen Daten in die Datenbank.
	 * @see https://codex.wordpress.org/Function_Reference/update_option
	 */
	public function save() {
		$this->update_option( sprintf( self::OPTION_NAME, 'campaigns' ), $this->campaigns );
		$this->update_option( sprintf( self::OPTION_NAME, 'email_templates' ), $this->email_templates );
		$this->update_option( sprintf( self::OPTION_NAME, 'folders' ), $this->folders );
		$this->update_option( sprintf( self::OPTION_NAME, 'forms' ), $this->forms );
		$this->update_option( sprintf( self::OPTION_NAME, 'layout_templates' ), $this->layout_templates );
		$this->update_option( sprintf( self::OPTION_NAME, 'tags' ), $this->tags );
		$this->update_option( sprintf( self::OPTION_NAME, 'tracking_domains' ), $this->tracking_domains );
		$this->update_option( sprintf( self::OPTION_NAME, 'users' ), $this->users );
	}

	/**
	 * @param string $option_name
	 * @return mixed
	 */
	private function get_option( $option_name ) {
		$chunk_size = get_option( $option_name );
		$value = [];
		if( !empty( $chunk_size ) && is_numeric( $chunk_size ) ) {
			for( $chunk_id = 0; $chunk_id < $chunk_size; $chunk_id++ ) {
				$chunk = get_option(  sprintf( '%s_%s', $option_name, $chunk_id ) );
				if( !empty( $chunk ) ) {
					$value = array_replace( $value, $chunk );
				}
			}
		}
		return $value;
	}

	/**
	 * @param string $option_name
	 * @param mixed $value
	 */
	private function update_option( $option_name, $value ) {
		$chunks = array_chunk( $value, self::OPTION_CHUNK_SIZE, true );
		update_option( $option_name, count( $chunks ) );
		foreach( (array)$chunks as $chunk_id => $chunk ) {
			update_option( sprintf( '%s_%s', $option_name, $chunk_id ), $chunk );
		}
	}

	/**
	 * Setzt alle Daten zurück.
	 * @see https://codex.wordpress.org/Function_Reference/update_option
	 */
	public function reset() {
		$this->campaigns = [];
		$this->email_templates = [];
		$this->folders = [];
		$this->forms = [];
		$this->layout_templates = [];
		$this->tags = [];
		$this->tracking_domains = [];
		$this->users = [];
		$this->save();
	}

	/**
	 * @param int $id
	 * @return Campaign
	 */
	public function create_campaign( int $id ) {
		if( empty( $this->campaigns[ $id ] ) ) {
			$this->campaigns[ $id ] = new Campaign( $id );
		}
		return $this->campaigns[ $id ];
	}

	/**
	 * @param int $id
	 * @return Campaign
	 */
	public function get_campaign( int $id ) {
		if( !empty( $this->campaigns[ $id ] ) ) {
			return $this->campaigns[ $id ];
		}
		return null;
	}

	/** @return Campaign[] */
	public function get_campaigns() {
		return $this->campaigns;
	}

	/**
	 * @param int $id
	 * @return Email_Template
	 */
	public function create_email_template( int $id ) {
		if( empty( $this->email_templates[ $id ] ) ) {
			$this->email_templates[ $id ] = new Email_Template( $id );
		}
		return $this->email_templates[ $id ];
	}

	/**
	 * @param int $id
	 * @return Email_Template
	 */
	public function get_email_template( int $id ) {
		if( !empty( $this->email_templates[ $id ] ) ) {
			return $this->email_templates[ $id ];
		}
		return null;
	}

	/** @return Email_Template[] */
	public function get_email_templates() {
		return $this->email_templates;
	}

	/**
	 * @param int $id
	 * @return Folder
	 */
	public function create_folder( int $id ) {
		if( empty( $this->folders[ $id ] ) ) {
			$this->folders[ $id ] = new Folder( $id );
		}
		return $this->folders[ $id ];
	}

	/**
	 * @param int $id
	 * @return Folder
	 */
	public function get_folder( int $id ) {
		if( !empty( $this->folders[ $id ] ) ) {
			return $this->folders[ $id ];
		}
		return null;
	}

	/** @return Folder[] */
	public function get_folders() {
		return $this->folders;
	}

	/**
	 * @param int $id
	 * @return Form
	 */
	public function create_form( int $id ) {
		if( empty( $this->forms[ $id ] ) ) {
			$this->forms[ $id ] = new Form( $id );
		}
		return $this->forms[ $id ];
	}

	/**
	 * @param int $id
	 * @return Form
	 */
	public function get_form( int $id ) {
		if( !empty( $this->forms[ $id ] ) ) {
			return $this->forms[ $id ];
		}
		return null;
	}

	/** @return Form[] */
	public function get_forms() {
		return $this->forms;
	}

	/**
	 * @param int $id
	 * @return Layout_Template
	 */
	public function create_layout_template( int $id ) {
		if( empty( $this->layout_templates[ $id ] ) ) {
			$this->layout_templates[ $id ] = new Layout_Template( $id );
		}
		return $this->layout_templates[ $id ];
	}

	/**
	 * @param int $id
	 * @return Layout_Template
	 */
	public function get_layout_template( int $id ) {
		if( !empty( $this->layout_templates[ $id ] ) ) {
			return $this->layout_templates[ $id ];
		}
		return null;
	}

	/** @return Layout_Template[] */
	public function get_layout_templates() {
		return $this->layout_templates;
	}

	/**
	 * @param int $id
	 * @return Tag
	 */
	public function create_tag( int $id ) {
		if( empty( $this->tags[ $id ] ) ) {
			$this->tags[ $id ] = new Tag( $id );
		}
		return $this->tags[ $id ];
	}

	/**
	 * @param int $id
	 * @return Tag
	 */
	public function get_tag( int $id ) {
		if( !empty( $this->tags[ $id ] ) ) {
			return $this->tags[ $id ];
		}
		return null;
	}

	/** @return Tag[] */
	public function get_tags() {
		return $this->tags;
	}

	/**
	 * @param int $id
	 * @return Tracking_Domain
	 */
	public function create_tracking_domain( int $id ) {
		if( empty( $this->tracking_domains[ $id ] ) ) {
			$this->tracking_domains[ $id ] = new Tracking_Domain( $id );
		}
		return $this->tracking_domains[ $id ];
	}

	/**
	 * @param int $id
	 * @return Tracking_Domain
	 */
	public function get_tracking_domain( int $id ) {
		if( !empty( $this->tracking_domains[ $id ] ) ) {
			return $this->tracking_domains[ $id ];
		}
		return null;
	}

	/** @return Tracking_Domain[] */
	public function get_tracking_domains() {
		return $this->tracking_domains;
	}

	/**
	 * @param int $id
	 * @return User
	 */
	public function create_user( int $id ) {
		if( empty( $this->users[ $id ] ) ) {
			$this->users[ $id ] = new User( $id );
		}
		return $this->users[ $id ];
	}

	/**
	 * @param int $id
	 * @return User
	 */
	public function get_user( int $id ) {
		if( !empty( $this->users[ $id ] ) ) {
			return $this->users[ $id ];
		}
		return null;
	}

	/** @return User[] */
	public function get_users() {
		return $this->users;
	}

	/** @return Pardot_Model_Collection */
	public static function get_instance() {
		static $instance;
		if( empty( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}
