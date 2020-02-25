<?php

class WpmPlaceholder {
	/** @var string */
	private $objectName;

	/** @var array */
	private $attributes;

	/** @var array */
	private $parameters;

	/** @var array */
	private $whitelist;

	/** @var string */
	private $format;

	/**
	 * WpmPlaceholder constructor.
	 *
	 * @param string $objectName
	 * @param array  $attributes
	 * @param array  $parameters
	 */
	public function __construct( $objectName = '', array $attributes = [], array $parameters = [], array $whitelist = null, $format = null ) {
		$this->objectName = $objectName;
		$this->attributes = $attributes;

		MSQ_SD_Utils::setIfNotSet( $parameters, 'id', null );
		MSQ_SD_Utils::setIfNotSet( $parameters, 'whitelist', [] );
		MSQ_SD_Utils::setIfNotSet( $parameters, 'format', 'full' );

		$this->parameters = $parameters;
		$this->whitelist = empty( $whitelist ) ? $this->parameters['whitelist'] : $whitelist;
		$this->format = empty( $format ) ? $this->parameters['format'] : $format;
	}

	public static function copy( WpmPlaceholder $placeholder ) {
		$copy = new WpmPlaceholder();
		$copy->objectName = $placeholder->objectName;
		$copy->attributes = $placeholder->attributes;
		$copy->parameters = $placeholder->parameters;
		$copy->format = $placeholder->format;

		return $copy;
	}

	/**
	 * @return string
	 */
	public function getObjectName() {
		return $this->objectName;
	}

	/**
	 * @param string $objectName
	 */
	public function setObjectName( $objectName ) {
		$this->objectName = $objectName;
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @param array $attributes
	 */
	public function setAttributes( $attributes ) {
		$this->attributes = $attributes;
	}

	public function setAttribute( $key, $value ) {
		$this->attributes[$key] = $value;
	}

	public function getAttribute( $key ) {
		return $this->attributes[$key];
	}

	/**
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @param array $parameters
	 */
	public function setParameters( $parameters ) {
		$this->parameters = $parameters;
	}

	public function getParameter( $key ) {
		return $this->parameters[$key];
	}

	public function setParameter( $key, $value ) {
		$this->parameters[$key] = $value;
	}

	/**
	 * Setzt einen Wert der Parameter, falls dieser noch nicht gesetzt ist.
	 *
	 * @param mixed    $key                Welcher Schlüssel überprüft werden soll
	 * @param mixed    $defaultValue       Der Standardwert, falls keiner gesetzt wurde
	 * @param callable $formattingCallback Ein Callback zum Formatieren bestehender Werte
	 */
	public function initParameter( $key, $value, $formattingCallback = null ) {
		MSQ_SD_Utils::setIfNotSet( $this->parameters, $key, $value, $formattingCallback );
	}

	/**
	 * @return array
	 */
	public function getWhitelist() {
		return $this->whitelist;
	}

	/**
	 * Definiert, welche Schlüssel im Resultat enthalten sein dürfen.
	 *
	 * @param array|string $whitelist
	 */
	public function setWhitelist( $whitelist ) {
		$whitelist = MSQ_SD_Utils::parseArray( $whitelist );

		if ( !empty( $whitelist ) ) {
			$this->whitelist = $whitelist;
		} else {
			$this->whitelist = null;
		}
	}

	/**
	 * Fügt Werte zur bestehenden Whitelist hinzu
	 *
	 * @param $whitelist
	 */
	public function addToWhitelist( $whitelist ) {
		$whitelist = MSQ_SD_Utils::parseArray( $whitelist );

		if ( !isset( $this->whitelist ) ) {
			$this->whitelist = $whitelist;
		} else {
			$this->whitelist = array_merge( $this->whitelist, $whitelist );
		}
	}

	/**
	 * @return string
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * @param string $format
	 */
	public function setFormat( $format ) {
		$this->format = $format;
	}

	public function __toString() {
		return print_r([
			'objectName' => $this->objectName,
			'attributes' => $this->attributes,
			'parameter' => $this->parameters,
			'whitelist' => $this->whitelist,
			'format' => $this->format
		], true);
	}


}