<?php


namespace Msq\Schulungskatalog\AcfLoader;

/**
 * Lädt ACF-Felder für die Einleitung des Katalogs
 * @package Msq\Schulungskatalog\AcfLoader
 */
class IntroPageAcfLoader extends AcfLoader {

	public function __construct($prefix) {
		$prefix .= 'intro_';
		parent::__construct($prefix, 'options');
	}

	/**
	 * @return mixed|void|null Titel der Einleitung
	 */
	public function getTitle() {
		return $this->getField('title');
	}

	/**
	 * @return mixed|string|void|null Inhalt der Einleitung
	 */
	public function getText() {
		$text = $this->getContentField('text');

		return $text;
	}
}
