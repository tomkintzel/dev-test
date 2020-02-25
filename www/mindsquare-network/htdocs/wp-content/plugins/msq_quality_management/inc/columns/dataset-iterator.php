<?php
namespace MSQ\Plugin\Quality_Management\Columns;
use MSQ\Plugin\Quality_Management\Filter\Criteriable;
use Iterator;
use Countable;

class Dataset_Iterator implements Criteriable, Iterator, Countable {
	/** @var object[] $values */
	private $values = [];

	/** @var Column_Collection $column_collection */
	private $column_collection;

	/** @var int position */
	private $position;

	/**
	 * @param object[] $values
	 * @param Column_Collection $column_collection
	 **/
	public function __construct( $values, $column_collection ) {
		$this->values = array_values( $values );
		$this->column_collection = $column_collection;
		$this->position = 0;
	}

	/** @return object */
	public function current() {
		return $this->values[ $this->position ];
	}

	/** @return int */
	public function key() {
		return $this->position;
	}

	/** */
	public function next() {
		$this->position++;
	}

	/** */
	public function rewind() {
		$this->position = 0;
	}

	/** @return bool */
	public function valid() {
		return isset( $this->values[ $this->position ] );
	}

	/** @return int */
	public function count() {
		return count( $this->values );
	}

	/**
	 * @param string $column_name
	 * @return mixed
	 **/
	public function get_value( $column_name ) {
		$value = '';
		if( $this->valid() ) {
			$current = $this->current();
			$column = $this->column_collection->get_column( $column_name );
			if( !empty( $column ) ) {
				$value_callback = $column->get_value_callback();
				if( !empty( $value_callback ) ) {
					$value =  call_user_func( $value_callback, $current );
				}
			}
		}
		return $value;
	}

	/**
	 * @param string $column_name
	 * @return string
	 */
	public function get_headline( $column_name ) {
		$column = $this->column_collection->get_column( $column_name, $group_name );
		$headline = '';
		if( !empty( $column ) ) {
			$headline = sprintf( '%s > %s', $group_name, $column->get_headline() );
		}
		return $headline;
	}

	/** @return mixed[] */
	public function get_values() {
		$values = [];
		if( $this->valid() ) {
			$current = $this->current();
			$columns = $this->column_collection->get_columns();
			foreach( $columns as $column ) {
				$value_callback = $column->get_value_callback();
				if( !empty( $value_callback ) ) {
					$values[ $column->get_name() ] =  call_user_func( $value_callback, $current );
				}
			}
		}
		return $values;
	}

	/**
	 * @param string $column_name
	 * @param string $value
	 **/
	public function set_value( $column_name, $value ) {
		if( $this->valid() ) {
			$current = $this->current();
			$current->set_meta_value( $column_name, $value );
		}
	}

	/** @return string */
	public function get_output( $column_name ) {
		$output = '';
		if( $this->valid() ) {
			$current = $this->current();
			$column = $this->column_collection->get_column( $column_name );
			if( !empty( $column ) ) {
				$output_callback = $column->get_output_callback() ?: $column->get_value_callback();
				if( !empty( $output_callback ) ) {
					$output =  call_user_func( $output_callback, $current );
				}
			}
		}
		return $output;
	}

	/** @return string[] */
	public function get_outputs() {
		$outputs = [];
		if( $this->valid() ) {
			$current = $this->current();
			$columns = $this->column_collection->get_columns();
			foreach( $columns as $column ) {
				$output_callback = $column->get_output_callback() ?: $column->get_value_callback();
				if( !empty( $output_callback ) ) {
					$outputs[ $column->get_name() ] =  call_user_func( $output_callback, $current );
				}
			}
		}
		return $outputs;
	}

	/**
	 * Sortiert die Felder anhand eines Arrays, dass wie folgt aufgebaut sein sollte:
	 * $sort = [
	 *    'column_name'   => SORT_DESC,
	 *    'column_name_2' => [ SORT_ASC, SORT_STRING ]
	 * ];
	 *
	 * @param string[] $sort_array
	 */
	public function sort( $sort ) {
		// vars
		$sort_values = [];

		// Drehe die Sortierung um
		$sort = array_reverse( $sort );

		// Lade die Daten
		foreach( $this->values as $value ) {
			foreach( (array) $sort as $column_name => $sort_directions ) {
				$column = $this->column_collection->get_column( $column_name );
				$sort_values[ $column_name ][] = call_user_func( $column->get_value_callback(), $value );
			}
		}

		// Erstelle die Argumente für die 'array_multisort'-Funktion
		foreach( (array) $sort as $column_name => $sort_directions ) {
			if( !empty( $sort_values[ $column_name ] ) ) {
				// vars
				$args = [];
				$column = $this->column_collection->get_column( $column_name );
				$sort_callback = $column->get_sort_callback();

				// Vorbereitungen
				$args[] = $sort_values[ $column_name ];
				foreach( (array) $sort_directions as $sort_direction ) {
					$args[] = $sort_direction;
				}
				$args[] = &$this->values;

				// Prüfe ob eine eigene Sortierung angegeben wurde
				call_user_func_array( $sort_callback, $args );
			}
		}
	}

	/**
	 * @param int $current_page
	 * @param int $per_page
	 */
	public function slice( $current_page, $per_page ) {
		$this->values = array_slice( $this->values, $per_page * ( $current_page - 1 ), $per_page );
	}

	/**
	 * @param Closure $select_callback
	 */
	public function filter( $select_callback ) {
		$selected = [];
		foreach( $this->values as $value ) {
			if( call_user_func( $select_callback, $value ) !== false ) {
				$selected[] = $value;
			}
		}
		$this->values = $selected;
	}
}
