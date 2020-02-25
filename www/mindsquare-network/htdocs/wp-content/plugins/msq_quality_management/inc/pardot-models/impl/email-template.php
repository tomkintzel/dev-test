<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Models;

use MSQ\Plugin\Quality_Management\Pardot_Updaters\Pardot_Model_Updater;

/** */
class Email_Template implements Pardot_Model {
	/** @var int $id */
	public $id;

	/** @var string $name */
	public $name;

	/** @var string[] $senders */
	private $senders;

	/** @var string[] $replies */
	private $replies;

	/** @var string $subject */
	private $subject;

	/** @var string $message */
	private $message;

	/** @var string[] $availables */
	private $availables;

	/** @var int $create_date */
	private $create_date;

	/** @var int $edit_date */
	private $edit_date;

	/** @var int $update_index_date */
	private $update_index_date;

	/** @var int $update_details_date */
	private $update_details_date;

	/** @var int $folder_id */
	private $folder_id;

	/** @var int[] $tags */
	private $tags = [];

	/** @var int $creator_id */
	private $creator_id;

	/** @var int $editor_id */
	private $editor_id;

	/** @var string READ_URL */
	const READ_URL = 'https://pi.pardot.com/emailTemplate/read/id/%id%';

	/** @param int $id */
	public function __construct( $id ) {
		$this->id = $id;
	}

	/** @return int */
	public function get_id() {
		return $this->id;
	}

	/** @return string */
	public function get_name() {
		return $this->name;
	}

	/** @param string $name */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/** @return string[] */
	public function get_senders() {
		return $this->senders;
	}

	/** @param string $sender */
	public function add_sender( $sender ) {
		$this->senders[] = $sender;
	}

	/** @return string[] */
	public function get_replies() {
		return $this->replies;
	}

	/** @param string $reply */
	public function add_reply( $reply ) {
		$this->replies[] = $reply;
	}

	/** @return string */
	public function get_subject() {
		return $this->subject;
	}

	/** @param string $subject */
	public function set_subject( $subject ) {
		$this->subject = $subject;
	}

	/** @return string */
	public function get_message() {
		return $this->message;
	}

	/** @param string $message */
	public function set_message( $message ) {
		$this->message = $message;
	}

	/** @return string[] */
	public function get_availables() {
		return $this->replies;
	}

	/** @param string $available */
	public function add_available( $available ) {
		$this->availables[] = $available;
	}

	/** @return int */
	public function get_create_date() {
		return $this->create_date;
	}

	/** @param int $create_date */
	public function set_create_date( $create_date ) {
		$this->create_date = $create_date;
	}

	/** @return int */
	public function get_edit_date() {
		return $this->edit_date;
	}

	/** @param int $edit_date */
	public function set_edit_date( $edit_date ) {
		$this->edit_date = $edit_date;
	}

	/** @return int */
	public function get_update_index_date() {
		return $this->update_index_date;
	}

	/** @param int $update_details_date */
	public function set_update_index_date( $update_index_date ) {
		$this->update_index_date = $update_index_date;
	}

	/** @return int */
	public function get_update_details_date() {
		return $this->update_details_date;
	}

	/** @param int $update_details_date */
	public function set_update_details_date( $update_details_date ) {
		$this->update_details_date = $update_details_date;
	}

	/** @return int[] */
	public function get_tag_ids() {
		return $this->tags;
	}

	/**
	 * @return Tag[]
	 **/
	public function get_tags() {
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		return array_map( function( $tag_id ) use( $pardot_model_collection ) {
			return $pardot_model_collection->get_tag( $tag_id );
		}, $this->tags );
	}

	/** @param int $tag_id */
	public function add_tag_id( $tag_id ) {
		if( !empty( $this->tags[ $tag_id ] ) ) {
			$this->tags[ $tag_id ] = $tag_id;
		}
	}

	/** @return int */
	public function get_folder_id() {
		return $this->folder_id;
	}

	/**
	 * @return Folder
	 **/
	public function get_folder() {
		$folder_id = $this->get_folder_id();
		if( !empty( $folder_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_folder( $folder_id );
		}
		return null;
	}

	/** @param int $folder_id */
	public function set_folder_id( $folder_id ) {
		$this->folder_id = $folder_id;
	}

	/** @return int */
	public function get_creator_id() {
		return $this->creator_id;
	}

	/**
	 * @return User
	 **/
	public function get_creator() {
		$user_id = $this->get_creator_id();
		if( !empty( $user_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_user( $user_id );
		}
		return null;
	}

	/** @param int $campaign_id */
	public function set_creator_id( $creator_id ) {
		$this->creator_id = $creator_id;
	}

	/** @return int */
	public function get_editor_id() {
		return $this->editor_id;
	}

	/**
	 * @return User
	 **/
	public function get_editor() {
		$editor_id = $this->get_editor_id();
		if( !empty( $editor_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_user( $editor_id );
		}
		return null;
	}

	/** @param int $campaign_id */
	public function set_editor_id( $editor_id ) {
		$this->editor_id = $editor_id;
	}

	/** @return string */
	public function get_read_url() {
		return str_replace( '%id%', $this->id, self::READ_URL );
	}
}
