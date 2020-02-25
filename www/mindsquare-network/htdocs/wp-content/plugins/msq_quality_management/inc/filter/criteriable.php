<?php
namespace MSQ\Plugin\Quality_Management\Filter;

interface Criteriable {
	/**
	 * @param string $fieldname
	 * @return string
	 */
	public function get_value( string $fieldname );

	/**
	 * @param string $fieldname
	 * @return string
	 */
	public function get_headline( string $fieldname );
}
