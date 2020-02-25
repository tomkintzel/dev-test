<?php


namespace Msq\Schulungskatalog\AcfLoader;


use DateTimeImmutable;

/**
 * Lädt ACF-Felder für das Deckblatt des Katalogs.
 *
 * @package Msq\Schulungskatalog\AcfLoader
 */
class FrontPageAcfLoader extends AcfLoader {
	public function __construct($prefix) {
		parent::__construct($prefix, 'options');
	}

	/**
	 * @return mixed|void|null Der Titel auf dem Deckblatt
	 */
	public function getTitle() {
		return $this->getField('title');
	}

	/**
	 * @return mixed|string|void|null Der Untertitel bzw. die Jahreszahl, falls kein Untertitel angegeben wurde
	 * @throws \Exception
	 */
	public function getSubtitle() {
		$subtitle = $this->getField('subtitle');

		if (empty($subtitle)) {
			$date     = new DateTimeImmutable();
			$subtitle = $date->format('Y');
		}

		return $subtitle;
	}

	/**
	 * @return mixed|void|null Der Herausgeber des Schulungskatalogs
	 */
	public function getPublisher() {
		return $this->getField('publisher');
	}

	/**
	 * @return string Die URL des Hintergrundbilds
	 */
	public function getCoverImage() {
		return $this->getField('cover_image')['url'];
	}
}
