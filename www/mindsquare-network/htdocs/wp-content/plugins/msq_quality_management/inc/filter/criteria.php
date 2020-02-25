<?php
namespace MSQ\Plugin\Quality_Management\Filter;

interface Criteria {
	/**
	 * @param Criteriable $field
	 * @return bool
	 */
	public function match( Criteriable $field );

	/**
	 * @param Criteriable $field
	 * @return string
	 */
	public function debug( Criteriable $field );

	/**
	 * @return string
	 */
	public function to_string();
}
