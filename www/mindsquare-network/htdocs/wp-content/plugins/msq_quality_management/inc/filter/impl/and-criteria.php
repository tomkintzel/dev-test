<?php
namespace MSQ\Plugin\Quality_Management\Filter;

class And_Criteria implements Criteria {
	/** @var Criteria[] $criterias */
	private $criterias;

	/** @var bool $matched */
	private $matched = false;

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
		$this->matched = false;
		foreach( $this->criterias as $criteria ) {
			if( !$criteria->match( $field ) ) {
				return false;
			}
		}
		$this->matched = true;
		return true;
	}

	/**
	 * @param Criteriable $field
	 * @return string
	 **/
	public function debug( Criteriable $field ) {
		if( $this->matched === true ) {
			$string = [];
			foreach( $this->criterias as $criteria ) {
				$string[] = $criteria->debug( $field );	
			}
			return implode( ' und ', $string );
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
		return sprintf( '(%s)', implode( ' && ', $string ) );
	}
}
