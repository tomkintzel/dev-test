<?php
/**
 * Created by PhpStorm.
 * User: StefanWiebe
 * Date: 06.08.2018
 * Time: 15:33
 */

class MSQ_SD_Utils {
	public static function mapKeys( $keys, $source, &$target, $formatter = null ) {
		foreach ( $keys as $key => $value ) {
			if ( is_numeric( $key ) ) {
				if ( isset( $source[$value] ) ) {
					$targetKey = call_user_func( $formatter, $value );
					$target[$targetKey] = $source[$value];
				}
			} else {
				$targetKey = call_user_func( $formatter, $key );

				if ( isset( $source[ $key ] ) && is_array( $value ) ) {
					self::mapKeys( $value, $source[$key], $target[$targetKey], $formatter );
				}
			}
		}
	}

	/**
	 * @param string $string
	 * @param bool   $firstLower
	 *
	 * @return mixed|string
	 */
	public static function toCamelCase( $string, $firstLower = true ) {
		$string = str_replace( [ '-', '_' ], '', ucwords( $string, '-_' ) );

		if ( $firstLower ) {
			$string = lcfirst( $string );
		}

		return $string;
	}

	/**
	 * Setzt einen Wert im übergebenen Array, falls dieser noch nicht gesetzt ist.
	 *
	 * @param array    $array              In welchem Array der Wert gesetzt werden soll
	 * @param mixed    $key                Welcher Schlüssel überprüft werden soll
	 * @param mixed    $defaultValue       Der Standardwert, falls keiner gesetzt wurde
	 * @param callable $formattingCallback Ein Callback zum Formatieren bestehender Werte
	 */
	public static function setIfNotSet( array &$array, $key, $defaultValue, $formattingCallback = null ) {
		if ( !isset( $array[$key] ) ) {
			$array[$key] = $defaultValue;
		} elseif ( $formattingCallback !== null ) {
			$array[$key] = call_user_func( $formattingCallback, $array[$key] );
		}
	}

	/**
	 * Interpretiert den übergebenen Wert als Array.
	 *
	 * Falls es ein String ist, wird es an Kommas getrennt.<br>
	 * Falls es ein Array ist, bleibt es unverändert.<br>
	 * Ansonsten wird ein leeres Array zurückgegeben.
	 *
	 * @param $value
	 *
	 * @return array
	 */
	public static function parseArray( $value ) {
		if ( is_string( $value ) ) {
			$value = array_map( 'trim', explode( ',', $value ) );
		} elseif ( !is_array( $value ) ) {
			$value = [];
		}

		return $value;
	}

	public static function implode( array $array = null ) {
		if ( empty($array) ) return null;

		return http_build_query($array);
	}

	public static function explode ( $string ) {
		$result = array();
		parse_str($string, $result);

		return $result;
	}


	/**
	 * Prüft, ob der übergebene Parameter eine Zahl ist.
	 *
	 * @param mixed $number
	 *
	 * @return int|null Gegebenenfalls der Parameter als Integer, sonst null
	 */
	public static function validateNumber( $number ) {
		if ( is_numeric( $number ) ) {
			$number = intval( $number );
		} else {
			$number = null;
		}

		return $number;
	}
}