<?php
namespace MSQ\Plugin\Quality_Management\Filter;

class Greater_Equals_Criteria implements Criteria {
	/** @var mixed $value */
	private $value;

	/** @var string $fieldname */
	private $fieldname;

	/**
	 * @param mixed $value
	 * @param string $fieldname
	 */
	public function __construct( $value, string $fieldname ) {
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
				if( $value >= $this->value ) {
					return true;
				}
			}
		} else if( $values >= $this->value ) {
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
		return sprintf( '(<abbr title="%s">%s</abbr> ist größer als oder gleich "%s")', htmlspecialchars( implode( ', ', (array)$values ) ), $field->get_headline( $this->fieldname ), $this->value );
	}

	/**
	 * @return string
	 */
	public function to_string() {
		return sprintf( '%s >= %s', $this->fieldname, $this->value );
	}
}
