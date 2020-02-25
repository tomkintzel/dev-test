<?php
namespace MSQ\Plugin\Quality_Management\Filter;

use MatthiasMullie\Minify\Exception;

class Regex_Criteria implements Criteria {
	/** @var string $regex */
	private $regex;

	/** @var string $field */
	private $fieldname;

	/**
	 * @param string $regex
	 * @param string $fieldname
	 */
	public function __construct( string $regex, string $fieldname ) {
		$this->regex = $regex;
		$this->fieldname = $fieldname;
	}

	/**
	 * @param Criteriable $field
	 * @return bool
	 */
	public function match( Criteriable $field ) {
		$values = $field->get_value( $this->fieldname );
		try {
			if( is_array( $values ) ) {
				foreach( (array) $values as $value ) {
					if( preg_match( $this->regex, $value ) ) {
						return true;
					}
				}
			} else if( preg_match( $this->regex, $values ) ) {
				return true;
			}
		} catch( Exception $err ) {
			trigger_error( $err->getMessage() );
		}
		return false;
	}

	/**
	 * @param Criteriable $field
	 * @return string
	 **/
	public function debug( Criteriable $field ) {
		$values = $field->get_value( $this->fieldname );
		return sprintf( '(<abbr title="%s">%s</abbr> stimmt mit regulärem Ausdruck "%s" überein)', htmlspecialchars( implode( ', ', (array)$values ) ), $field->get_headline( $this->fieldname ), $this->value );
	}

	/**
	 * @return string
	 */
	public function to_string() {
		return sprintf( '%s regex %s', $this->fieldname, $this->value );
	}
}
