<?php
class MSQ_Structured_Data_News_Article extends MSQ_Structured_Data_Creative_Work {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		parent::defineStructure();
		$this->structuredData['@type'] = 'NewsArticle';
	}
}
?>
