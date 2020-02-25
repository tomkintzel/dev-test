<?php
namespace MSQ\Plugin\Quality_Management\Filter;

class Ends_With_Criteria implements Criteria {
	/** @var string $value */
	private $value;

	/** @var int $offset */
	private $offset;

	/** @var string $fieldname */
	private $fieldname;

	/**
	 * @param string $value
	 * @param string $fieldname
	 */
	public function __construct( string $value, string $fieldname ) {
		$this->value = $value;
		$this->offset = -strlen( $value );
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
				if( substr_compare( $value, $this->value, $this->offset, null, true ) === 0 ) {
					return true;
				}
			}
		} else if( substr_compare( $values, $this->value, $this->offset, null, true ) === 0 ) {
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
		return sprintf( '(<abbr title="%s">%s</abbr> endet auf "%s")', htmlspecialchars( implode( ', ', (array)$values ) ), $field->get_headline( $this->fieldname ), $this->value );
	}

	/**
	 * @return string
	 */
	public function to_string() {
		return sprintf( '%s ends with %s', $this->fieldname, $this->value );
	}
}
