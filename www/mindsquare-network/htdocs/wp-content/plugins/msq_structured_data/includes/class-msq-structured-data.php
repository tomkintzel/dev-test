<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mindsquare.de
 * @since      1.0.0
 *
 * @package    msq_structured_data
 * @subpackage msq_structured_data/includes
 */
class Msq_Structured_Data {
	/**
	 * @var array $placeholder - Speichert ein Array von Platzhalternamen ab.
	 * Nach diesen Namen wird anschließend im Array recursive durchsucht.
	 */
	public $placeholders;

	/**
	 * @var array $structuredData - Dieses Array speichert alle aktuellen
	 * Informationen von der Struktur ab.
	 */
	public $structuredData;

	/** @var WpmParser Interpretiert die Platzhalter **/
	public static $wpmParser;

	/**
	 */
	public function __construct() {
		$this->definePublicHooks();

		if (!isset(self::$wpmParser)) {
			self::$wpmParser = new WpmParser( __DIR__ . '/../providers' );
		}
	}

	/**
	 */
	private function definePublicHooks() {
		if ( !did_action( 'wp_head' ) ) {
			add_action( 'wp_head', array( $this, 'printStructuredData' ), PHP_INT_MAX - 10 );
		} else {
			add_action( 'wp_footer', array( $this, 'printStructuredData' ), PHP_INT_MAX - 10 );
		}
	}

	public function unhook() {
		remove_action('wp_head', [$this, 'printStructuredData'], PHP_INT_MAX - 10 );
		remove_action('wp_footer', [$this, 'printStructuredData'], PHP_INT_MAX - 10 );
	}

	/**
	 * Diese Funktion wird mit dem wp_head Hook ausgeführt und gibt
	 * die aktuelle Struktur als Json aus.
	 */
	public function printStructuredData() {
		// Mit diesem Hook können weitere Elemente vor dem Ersetzten hinzugefügt werden.';
		do_action( 'msq_structured_data_init', $this );
		$structuredData = $this->replacePlaceholders( $this->structuredData );
		$structuredData = apply_filters( 'msq_structured_data_replace_placeholders', $structuredData );
		$this->structuredData = $structuredData;

		$json = $this->getJson();
		if ( !empty( $json ) ) {
			echo apply_filters( 'msq_structured_data_print', '<script type="application/ld+json">' . $json . '</script>' );
		}
	}

	/**
	 * Diese Funktion gibt die aktuelle Struktur als ein Json-String zurück.
	 * @return string
	 */
	public function getJson() {
		$json = apply_filters( 'msq_structured_get_json', $this->structuredData, $this );

		// Säubere das Ergebniss
		$json = $this->recursive_filter_array( $json );
		if ( empty( array_diff( array_keys( $json ), array( '@context', '@type' ) ) ) ) {
			return null;
		}

		return json_encode( $json );
	}

	/**
	 * Diese Funktion geht rekurive durch das ganze Array durch und entfernt
	 * dabei leere String, leere Arrays und null Werte.
	 *
	 * @param array $array - Das Array welches gesäubert werden soll
	 *
	 * @return array
	 */
	private function recursive_filter_array( $array ) {
		$isNumArray = array_keys( $array ) === range( 0, sizeof( $array ) -1 );
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->recursive_filter_array( $value );
				if ( empty( $value ) || empty( array_diff_key( $value, array( '@type' => null ) ) ) ) {
					unset( $array[$key] );
				}
			} elseif ( is_null( $value ) || ( is_string( $value ) && strlen( $value ) == 0 ) ) {
				unset( $array[$key] );
			}
		}
		if( $isNumArray ) {
			return array_values( $array );
		}
		return $array;
	}

	/**
	 * Diese Funktion versucht alle Placeholders eines Arrays zu ersetzten.
	 * Dabei wird Recursive durch das ganze Array alle Placeholder ersetzt.
	 * Dabei werden die tiefsten Werte zuerst ersetzt und geht nacheinander
	 *
	 */
	public function replacePlaceholders( $data, $debug = false ) {
		if ( $debug &= !empty( $data ) ) {
			echo '<pre style="font-family: monospace">';
		}

		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( $debug ) echo "$key => $value";

				$data[$key] = $this->replacePlaceholders( $value );
			}
		} else {
			$data = self::$wpmParser->parse( $data, true );
		}

		if ( $debug ) {
			print_r( $data );
			echo "\n";
			echo '</pre>';
		}

		return $data;
	}
}

?>
