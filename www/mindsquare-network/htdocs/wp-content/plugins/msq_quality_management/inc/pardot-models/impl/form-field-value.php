<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Models;

/** */
class Form_Field_Value implements Pardot_Model {
	/** @var int $listx_id */
	private $listx_id;

	/** @var int $profile_id */
	private $profile_id;

	/** @var string $value */
	private $value;

	/** @var string $label */
	private $label;

	/** @var int $sort_order */
	private $sort_order;

	/**
	 */
	public function __construct() {
	}

	/** @return int */
	public function get_listx_id() {
		return $this->listx_id;
	}

	/** @param int $listx_id */
	public function set_listx_id( $listx_id ) {
		$this->listx_id = $listx_id;
	}

	/** @return int */
	public function get_profile_id() {
		return $this->profile_id;
	}

	/** @param int $profile_id */
	public function set_profile_id( $profile_id ) {
		$this->profile_id = $profile_id;
	}

	/** @return string */
	public function get_value() {
		return $this->value;
	}

	/** @param string $value */
	public function set_value( $value ) {
		$this->value = $value;
	}

	/** @return string */
	public function get_label() {
		return $this->label;
	}

	/** @param string $label */
	public function set_label( $label ) {
		$this->label = $label;
	}

	/** @return int */
	public function get_sort_order() {
		return $this->sort_order;
	}

	/** @param int $sort_order */
	public function set_sort_order( $sort_order ) {
		$this->sort_order = $sort_order;
	}
}
