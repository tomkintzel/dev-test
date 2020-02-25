<?php


namespace Msq\Schulungskatalog\Pages;

/**
 * Der Seitentyp für Schulungskategorien.
 *
 * @package Msq\Schulungskatalog\Pages
 */
class CategoryTitlePage extends TitlePage {

	/**
	 * CategoryTitlePage constructor.
	 *
	 * @param string                $title           Name der Schulungskategorie
	 * @param PageSettingsInterface $pageSettings
	 * @param null                  $backgroundImage Das Hintergrundbild der Seite
	 */
	public function __construct(
		$title,
		PageSettingsInterface $pageSettings,
		$backgroundImage = null
	) {
		if (empty($backgroundImage)) {
			$backgroundImage = $this->getAssetUrl() . 'images/category-background.png';
		}

		parent::__construct(
			$title,
			null,
			$backgroundImage,
			$pageSettings
		);
	}
}
