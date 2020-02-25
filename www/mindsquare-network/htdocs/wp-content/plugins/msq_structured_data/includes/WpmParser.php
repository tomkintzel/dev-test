<?php
require_once __DIR__ . '/DataProvider.php';

/**
 * Nimmt Strings mit Platzhaltern entgegen, und sucht basierend auf der Struktur des Platzhalters einen passenden
 * {@see DataProvider} heraus, um die angefragten Daten zurückzugeben.
 */
class WpmParser {
	/** @var DataProvider[] */
	private $providers;

	/** @var string Gleicht Platzhalter ab. Das erste Ergebnis sind alle Attribute, das zweite alle Parameter. */
	const WPM_REGEX = <<<REGEXP
/^\s*{{\s*((?:[\w\-]+\.?)+)((?:\s+[\w\-]+\s*=\s*(?|'.*'|".*"))*)\s*}}\s*$/
REGEXP;

	/** @var string Gleicht einzelne Objekt-Attribute ab und teilt diese an Punkten auf. */
	const OBJECT_REGEX = <<<REGEXP
/(?<=^|\.)([^.\s{}="']+)/
REGEXP;

	/** @var string Gleicht einzelne Parameter ab */
	const PARAMETER_REGEX = <<<REGEXP
/.*?([^.\s{}="']+)\s*=\s*(?|'((?:[^'\\\]|\\\.)*)'|"((?:[^"\\\]|\\\.)*)").*?/
REGEXP;

	/** @var string Gleicht "escapte" Anführungszeichen ab */
	const UNESCAPE_REGEX = <<<REGEXP
/(?|^[^'"]*\K\\\(['"])|\\\(['"][^'"]*$))/
REGEXP;


	/**
	 * Erzeugt einen WpmParser
	 *
	 * @param string $providerPath In welchem Pfad nach Providern gesucht werden soll
	 */
	public function __construct( $providerPath = null ) {
		if ( isset( $providerPath ) ) {
			$this->readProviders( $providerPath );
		}
	}

	public function parse( $input, $failSilently = false ) {
		$matches = [];
		$result = null;

		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$result[$key] = $this->parse( $value, $failSilently );
			}
		} else {
			$placeholder = null;

			if ( is_string( $input ) ) {
				// Ist der String ein Platzhalter?
				preg_match_all( self::WPM_REGEX, $input, $matches );

				if ( !empty( $matches[0] ) ) {
					$objectAttributes = $matches[1][0];
					$parameters = $matches[2][0];

					// Attribute aufteilen
					preg_match_all( self::OBJECT_REGEX, $objectAttributes, $matches );
					$objectAttributes = $matches[0];

					/** @var string $objectName Der Name des Objekts */
					$objectName = array_slice( $objectAttributes, 0, 1 )[0];

					// Alle weiteren Attribute
					$objectAttributes = array_slice( $objectAttributes, 1 );

					// Parameter aufteilen
					preg_match_all( self::PARAMETER_REGEX, $parameters, $matches );
					$parameters = array_combine( $matches[1], $matches[2] );

					$placeholder = new WpmPlaceholder( $objectName, $objectAttributes, $parameters );
				} else if ( $failSilently ) {
					$result = $input;
				} else {
					throw new InvalidArgumentException( 'Syntax-Error im WPM-Text' );
				}
			} else if ( $input instanceof WpmPlaceholder ) {
				$placeholder = $input;
			} else if ( $failSilently ) {
				$result = $input;
			} else {
				throw new InvalidArgumentException( 'Der übergebene Wert besitzt einen ungültigen Datentyp' );
			}

			if ( !empty( $placeholder ) ) {
				foreach ( $placeholder->getParameters() as $parameter => $value ) {
					// Falls nötig den Inhalt der Parameter "einklammern"
					if( is_string( $value ) ) {
						$value = preg_replace( self::UNESCAPE_REGEX, '$1', $value );
					}

					// Inhalt der Parameter analysieren
					$placeholder->setParameter($parameter, $this->parse( $value, $failSilently ));
				}

				$result = $this->callProviderForPlaceholder( $placeholder );

				if ( !empty( $result ) ) {
					$result = $this->parse( $result, $failSilently );
				}
			}
		}

		return $result;
	}

	protected function callProviderForPlaceholder( WpmPlaceholder $placeholder ) {
		$provider = $this->getProviderForPlaceholder( $placeholder );
		$data = null;

		if ( !empty( $provider ) ) {
			$data = $provider->getData( $placeholder );
		}

		return $data;
	}

	protected function getProviderForPlaceholder( WpmPlaceholder $placeholder ) {
		$provider = null;

		if ( isset( $this->providers[$placeholder->getObjectName()] ) ) {
			$provider = $this->providers[$placeholder->getObjectName()];
		}

		return $provider;
	}

	/**
	 * Registriert einen Provider
	 *
	 * @param DataProvider $provider
	 */
	public function registerProvider( DataProvider $provider ) {
		if ( is_string( $provider::PROVIDED_OBJECT ) ) {
			$this->providers[$provider::PROVIDED_OBJECT] = $provider;
		} elseif ( is_array( $provider::PROVIDED_OBJECT ) ) {
			foreach ( $provider::PROVIDED_OBJECT as $providedObject ) {
				$this->providers[$providedObject] = $provider;
			}
		}
	}

	/**
	 * Durchsucht einen Pfad nach PHP-Dateien und versucht diese (nach Dateinamen) als Klasse zu instanziieren.
	 * Die Instanzen werden dann per {@see registerProvider()} registriert.
	 *
	 * @param $path
	 */
	public function readProviders( $path ) {
		$dir = array_diff( scandir( $path ), array( '..', '.' ) );

		foreach ( $dir as $entry ) {
			$fullPath = $path . DIRECTORY_SEPARATOR . $entry;

			if ( is_dir( $fullPath ) ) {
				$this->readProviders( $fullPath );
			} else if ( substr( $entry, -4 ) === '.php' ) {
				$className = substr( $entry, 0, -4 );

				require_once( $fullPath );

				$this->registerProvider( new $className() );
			}
		}

	}

	/**
	 * Gibt den Provider zurück, der für das Objekt mit dem übergebenen Namen zuständig ist.
	 *
	 * @param String $name Name des Objekts
	 *
	 * @return DataProvider Zuständiger Provider
	 */
	public function getProvider( $name ) {
		return $this->providers[$name];
	}
}