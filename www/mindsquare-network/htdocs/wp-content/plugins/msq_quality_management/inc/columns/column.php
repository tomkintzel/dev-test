<?php
namespace MSQ\Plugin\Quality_Management\Columns;

class Column {
	/** @var string $name */
	private $name;

	/** @var string $headline */
	private $headline;

	/** @var bool $hidden */
	private $hidden;

	/** @var Closure $valueCallback */
	private $value_callback;

	/** @var Closure $output_callback */
	private $output_callback;

	/** @var Closure $sort_callback */
	private $sort_callback = 'array_multisort';

	/**
	 * @param string $name
	 * @param string $headline
	 */
	public function __construct( $name, $headline, $hidden = false ) {
		$this->name = $name;
		$this->headline = $headline;
		$this->hidden = $hidden;
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
	public function get_headline() {
		return $this->headline;
	}

	/** @param string $headline */
	public function set_headline( $headline ) {
		$this->headline = $headline;
	}

	/** @return bool */
	public function is_hidden() {
		return $this->hidden;
	}

	/** @param bool $hidden */
	public function set_hidden( $hidden ) {
		$this->hidden = $hidden;
	}

	/** @return Closure */
	public function get_value_callback() {
		return $this->value_callback;
	}

	/** @param Closure $valueCallback */
	public function set_value_callback( $value_callback ) {
		$this->value_callback = $value_callback;
	}

	/** @return Closure */
	public function get_output_callback() {
		return $this->output_callback;
	}

	/** @param Closure $output_callback */
	public function set_output_callback( $output_callback ) {
		$this->output_callback = $output_callback;
	}

	/** @return Closure */
	public function get_sort_callback() {
		return $this->sort_callback;
	}

	/** @param Closure $sort_callback */
	public function set_sort_callback( $sort_callback ) {
		$this->sort_callback = $sort_callback;
	}
}
