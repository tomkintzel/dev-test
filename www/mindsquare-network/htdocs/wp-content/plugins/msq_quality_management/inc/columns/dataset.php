<?php
namespace MSQ\Plugin\Quality_Management\Columns;

class Dataset {
	/** @var Column_Collection $column_collection */
	private $column_collection;

	/** @var object[] $values */
	private $values;

	/** @return Column_Collection */
	public function get_column_collection() {
		return $this->column_collectionM;
	}

	/** @param Column_Collection $column_collection */
	public function set_column_collection( $column_collection ) {
		$this->column_collection = $column_collection;
	}

	/** @return object[] */
	public function get_values() {
		return $this->values;
	}

	/** @param object[] $values */
	public function set_values( $values ) {
		$this->values = $values;
	}

	/** @param object $value */
	public function add_value( $value ) {
		$this->values[] = $value;
	}

	/**
	 * @param Closure $select_callback
	 * @return Dataset_Iterator
	 **/
	public function select( $select_callback ) {
		$selected_values = [];
		$iterator = new Dataset_Iterator( $this->values, $this->column_collection );
		while( $iterator->valid() ) {
			if( call_user_func( $select_callback, $iterator ) !== false ) {
				$selected_values[] = $iterator->current();
			}
			$iterator->next();
		}
		return new Dataset_Iterator( $selected_values, $this->column_collection );
	}

	/** @return Dataset_Iterator */
	public function select_all() {
		return new Dataset_Iterator( $this->values, $this->column_collection );
	}
}
