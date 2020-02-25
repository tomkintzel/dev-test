<?php
namespace MSQ\Plugin\Quality_Management\Filter;

class Not_Criteria implements Criteria {
	/** @var Criteria $criteria */
	private $criteria;

	/**
	 * @param Criteria $criteria
	 */
	public function __construct( Criteria $criteria ) {
		$this->criteria = $criteria;
	}

	/**
	 * @param Criteriable $field
	 * @return bool
	 */
	public function match( Criteriable $field ) {
		return !$this->criteria->match( $field );
	}

	/**
	 * @param Criteriable $field
	 * @return string
	 **/
	public function debug( Criteriable $field ) {
		return 'nicht ' . $this->criteria->debug( $field );
	}

	/**
	 * @return string
	 */
	public function to_string() {
		return sprintf( '!(%s)', $this->criteria->to_string() );
	}
}
