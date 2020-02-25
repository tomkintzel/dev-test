<?php

/**
 * Class DataProvider
 *
 * Eine abstrakte Klasse um Basis-Funktionalitäten für konkrete Provider bereitzustellen.
 * Provider werden vom {@see WpmParser} benutzt, um in Platzhaltern angefragte Informationen bereitzustellen.
 */
abstract class DataProvider {
	/** @var string Für welches Objekt  der Provider zuständig ist */
	const PROVIDED_OBJECT = '';
	protected $format;
	protected $whitelist;
	/** @var WpmPlaceholder */
	protected $placeholder;

	/**
	 * Gibt für die übergebenen Attribute und Parameter Daten zurück, indem sie diese an die zuständige Methode
	 * übergibt.
	 *
	 * @param array $objectAttributes Welche Attribute abgerufen werden sollen
	 * @param array $parameters       Mit welchen Parametern
	 *
	 * @return array|mixed|null Der Rückgabewert der ermittelten Methode
	 * @see initializePlaceholder(), mapMethod(), before(), getResult(), after()
	 */
	public function getData( WpmPlaceholder $wpmPlaceholder ) {
		$this->placeholder = $wpmPlaceholder;

		$this->initializePlaceholder( $wpmPlaceholder );
		$methodName = $this->mapMethod( $wpmPlaceholder );
		$result = null;

		try {
			if ( isset( $wpmPlaceholder->getParameters()['id'] ) && is_array( $wpmPlaceholder->getParameter( 'id' ) ) ) {
				foreach ( $wpmPlaceholder->getParameter( 'id' ) as $id ) {
					$idPlaceholder = WpmPlaceholder::copy( $wpmPlaceholder );
					$idPlaceholder->setParameter( 'id', $id );

					$this->before( $idPlaceholder );
					$idResult = $this->getResult( $methodName, $idPlaceholder );
					$result[] = $this->after( $idPlaceholder, $idResult );

				}
			} else {
				$this->before( $wpmPlaceholder );
				$result = $this->getResult( $methodName, $wpmPlaceholder );
				$result = $this->after( $wpmPlaceholder, $result );
			}

		} catch ( ReflectionException $e ) {
			error_log( "Es wurde keine Methode gefunden, welche das Attribut \"{$wpmPlaceholder->getAttributes()[0]}\" für das Objekt \"" .
				static::PROVIDED_OBJECT . "\" bereitstellt." );
		}

		return $result;
	}

	/**
	 * Gibt Daten zurück, indem es die Parameter korrekt formatiert und als Argumente der Methode übergibt
	 *
	 * @param string $methodName Der Name der aufzurufenden Methode
	 * @param array  $parameters Die Parameter, welche als Argumente dienen sollen
	 *
	 * @return mixed Die Rückgabe der aufgerufenen Methode
	 * @throws ReflectionException
	 */
	public function getResult( $methodName, WpmPlaceholder $placeholder ) {
		$providingMethod = new ReflectedCallback( array( $this, $methodName ) );

		foreach ( $placeholder->getParameters() as $key => $value ) {
			$arguments[MSQ_SD_Utils::toCamelCase( $key )] = $value;
		}

		return $providingMethod->invokeArgs( $arguments );
	}

	/**
	 * Ruft eine JSON-Datei für das {@see PROVIDED_OBJECT} ab.
	 *
	 * @param string $name Der Name der JSON-Datei (ohne Dateiendung)
	 *
	 * @return array|mixed Der Inhalt der JSON-Datei
	 */
	public static function getJson( $name = '' ) {
		$json = null;
		$fileContent = file_get_contents( __DIR__ . '/../json/' . static::PROVIDED_OBJECT . '/' . $name . '.json' );

		if ( !empty( $fileContent ) ) {
			$json = json_decode( $fileContent, true );
		}

		return $json;
	}

	/**
	 * @param      $string
	 * @param bool $firstLower
	 *
	 * @deprecated
	 */
	public static function toCamelCase( $string, $firstLower = true ) {
		return MSQ_SD_Utils::toCamelCase( $string, $firstLower );
	}

	/**
	 * Erzeugt anhand der Objekt-Attribute einen Methodennamen.<br>
	 * Beispiel: image.size wird zu getSize
	 *
	 * @param WpmPlaceholder|null $placeholder
	 *
	 * @return mixed|string Den Methodennamen
	 */
	public function mapMethod( WpmPlaceholder $placeholder ) {
		$objectAttributes = $placeholder->getAttributes();
		$methodName = join( '_', $objectAttributes );
		$methodName = MSQ_SD_Utils::toCamelCase( $methodName, false );
		$methodName = 'get' . $methodName;

		return $methodName;
	}

	/**
	 * Initialisiert falls nötig den übergebenen Platzhalter.
	 *
	 * Wird vor jeglichen weiteren Aktionen aufgerufen.
	 *
	 * @param WpmPlaceholder $placeholder
	 */
	protected function initializePlaceholder( WpmPlaceholder &$placeholder ) {
	}

	/**
	 * Kann überschrieben werden, falls vor dem Datenaufruf weitere Aktionen durchgeführt werden sollen.
	 *
	 * @param WpmPlaceholder $placeholder
	 */
	public function before( WpmPlaceholder &$placeholder ) {
	}

	/**
	 * Kann überschrieben werden, falls nach dem Datenabruf die Parameter oder das Resultat abgeändert werden müssen.
	 *
	 * @param WpmPlaceholder $placeholder
	 * @param mixed          $result Das Ergebnis des Methodenaufrufs
	 *
	 * @return mixed
	 * @see setWhitelist()
	 */
	public function after( WpmPlaceholder &$placeholder, $result ) {
		if ( !empty( $placeholder->getWhitelist() ) && is_array( $result ) ) {
			$result = array_intersect_key( $result, array_flip( $placeholder->getWhitelist() ) );
		}

		return $result;
	}
}