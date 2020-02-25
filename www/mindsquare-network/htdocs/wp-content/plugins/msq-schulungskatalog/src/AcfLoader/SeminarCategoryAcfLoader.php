<?php


namespace Msq\Schulungskatalog\AcfLoader;

/**
 * Lädt ACF-Felder für Schulungskategorien.
 * @package Msq\Schulungskatalog\AcfLoader
 */
class SeminarCategoryAcfLoader extends AcfLoader {
	/**
	 * @return mixed|void|null Die URL des Hintergrundbilds
	 */
	public function getBackground() {
		return $this->getField('background');
	}

	/**
	 * @return mixed|void|null Die URL des Headerbilds
	 */
	public function getHeader() {
		return $this->getField('header');
	}

}
