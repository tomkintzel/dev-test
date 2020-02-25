<?php
namespace MSQ\Plugin\Quality_Management\Columns;

class Column_Collection {
	/** @var Column[][] $column_groups */
	private $column_groups = [];

	/** @return Column[][] */
	public function get_column_groups() {
		return $this->column_groups;
	}

	/** @return Column[] */
	public function get_columns() {
		$result = [];
		foreach( $this->column_groups as $columns ) {
			$result = array_merge( $result, $columns );
		}
		return $result;
	}

	/**
	 * @param string $column_name
	 * @param string &$group_name
	 * @return Column
	 **/
	public function get_column( $column_name, &$ref_group_name = null ) {
		foreach( $this->column_groups as $group_name => $columns ) {
			if( !empty( $columns[ $column_name ] ) ) {
				$ref_group_name = $group_name;
				return $columns[ $column_name ];
			}
		}
		return null;
	}

	/** @return Column[] */
	public function get_hidden_columns() {
		$result = [];
		foreach( $this->column_groups as $columns ) {
			foreach( $columns as $column_name => $column ) {
				if( $column->is_hidden() ) {
					$result[ $column_name ] = $column;
				}
			}
		}
		return $result;
	}

	/**
	 * @var Column $column
	 * @var string $group_name
	 **/
	public function add_column( $column, $group_name ) {
		if( !empty( $column ) ) {
			$this->column_groups[ $group_name ][ $column->get_name() ] = $column;
		}
	}
}
