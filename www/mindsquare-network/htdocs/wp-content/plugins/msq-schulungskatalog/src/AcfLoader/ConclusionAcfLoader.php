<?php


namespace Msq\Schulungskatalog\AcfLoader;

/**
 * Lädt ACF-Felder für die letzte Seite des Katalogs.
 *
 * @package Msq\Schulungskatalog\AcfLoader
 */
class ConclusionAcfLoader extends AcfLoader {
	public function __construct($prefix) {
		$prefix = $prefix .= 'conclusion_';
		parent::__construct($prefix, 'options');
	}

	/**
	 * @return mixed|void|null Der Inhalt der letzten Seite
	 */
	public function getContent() {
		$content = $this->getContentField('text');

		return $content;
	}

	/**
	 * @return array URLs von Auszeichnungs-Bildern
	 */
	public function getAwards() {
		$awards    = $this->getField('awards');
		$awardUrls = array_column($awards, 'image');

		return $awardUrls;
	}

}
