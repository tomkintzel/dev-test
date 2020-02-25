<?php
require_once __DIR__ . '/WebPage.php';

class MSQ_Structured_Data_Blog extends MSQ_Structured_Data_Web_Page {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		parent::defineStructure();
		$this->structuredData['@type'] = 'Blog';

		if ( get_current_blog_id() === 28 ) {
			$this->structuredData['author'] = [
				'@type' => 'Person',
				'name' => 'Tobias Harmes',
				'url' => 'https://rz10.de/authordetails/tobias-harmes'
			];
		}

		unset($this->structuredData['lastReviewed']);
		unset($this->structuredData['breadcrumb']);
	}
}
?>
