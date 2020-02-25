<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Models;

/** */
class Form_Field_Conditional implements Pardot_Model {
	/** @var int $conditional_id */
	private $conditional_id;

	/** @var int $prospect_field_id */
	private $prospect_field_id;

	/** @var string $sort_order */
	private $sort_order;

	/**
	 */
	public function __construct() {
	}

	/** @return int */
	public function get_conditional_id() {
		return $this->conditional_id;
	}

	/** @param int $conditional_id */
	public function set_conditional_id( $conditional_id ) {
		$this->conditional_id = $conditional_id;
	}

	/** @return int */
	public function get_prospect_field_id() {
		return $this->prospect_field_id;
	}

	/** @param int $prospect_field_id */
	public function set_prospect_field_id( $prospect_field_id ) {
		$this->prospect_field_id = $prospect_field_id;
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
