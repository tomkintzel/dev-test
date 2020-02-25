<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Models;

/** */
class Tracking_Domain implements Pardot_Model {
	/** @var int $id */
	public $id;

	/** @var string $name */
	public $name;

	/** @var string READ_URL */
	const READ_URL = 'https://pi.pardot.com/trackerDomain/read/id/%id%';

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

	/** @return string */
	public function get_read_url() {
		return str_replace( '%id%', $this->id, self::READ_URL );
	}
}
