<?php
namespace MSQ\Plugin\Quality_Management\Filter;

class Or_Criteria implements Criteria {
	/** @var Criteria[] $criterias */
	private $criterias;

	/** @var Criteria[] $matched */
	private $matched = null;

	/**
	 * @param Criteria[] $criterias
	 */
	public function __construct( array $criterias ) {
		$this->criterias = $criterias;
	}

	/**
	 * @param Criteriable $field
	 * @return bool
	 */
	public function match( Criteriable $field ) {
		$this->matched = null;
		foreach( $this->criterias as $criteria ) {
			if( $criteria->match( $field ) ) {
				$this->matched = $criteria;
				return true;
			}
		}
		return false;
	}

	/**
	 * @param Criteriable $field
	 * @return string
	 **/
	public function debug( Criteriable $field ) {
		if( !empty( $this->matched ) ) {
			return $this->matched->debug( $field );
		}
		return '';
	}

	/**
	 * @return string
	 */
	public function to_string() {
		$string = [];
		foreach( $this->criterias as $criteria ) {
			$string[] = $criteria->to_string();
		}
		return sprintf( '(%s)', implode( ' || ', $string ) );
	}
}
