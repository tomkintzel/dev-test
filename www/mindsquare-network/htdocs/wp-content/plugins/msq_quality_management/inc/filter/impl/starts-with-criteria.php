<?php
namespace MSQ\Plugin\Quality_Management\Filter;

class Starts_With_Criteria implements Criteria {
	/** @var string $value */
	private $value;

	/** @var string $fieldname */
	private $fieldname;

	/**
	 * @param string $value
	 * @param string $fieldname
	 */
	public function __construct( string $value, string $fieldname ) {
		$this->value = $value;
		$this->fieldname = $fieldname;
	}

	/**
	 * @param Criteriable $field
	 * @return bool
	 */
	public function match( Criteriable $field ) {
		$values = $field->get_value( $this->fieldname );
		if( is_array( $values ) ) {
			foreach( (array) $values as $value ) {
				if( stripos( $value, $this->value ) === 0 ) {
					return true;
				}
			}
		} else if( stripos( $values, $this->value ) === 0 ) {
			return true;
		}
		return false;
	}

	/**
	 * @param Criteriable $field
	 * @return string
	 **/
	public function debug( Criteriable $field ) {
		$values = $field->get_value( $this->fieldname );
		return sprintf( '(<abbr title="%s">%s</abbr> beginnt mit "%s")', htmlspecialchars( implode( ', ', (array)$values ) ), $field->get_headline( $this->fieldname ), $this->value );
	}

	/**
	 * @return string
	 */
	public function to_string() {
		return sprintf( '%s starts with %s', $this->fieldname, $this->value );
	}
}
