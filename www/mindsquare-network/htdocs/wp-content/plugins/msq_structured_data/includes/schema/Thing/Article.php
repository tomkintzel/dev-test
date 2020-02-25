<?php
require_once __DIR__ . '/CreativeWork.php';

class MSQ_Structured_Data_Article extends MSQ_Structured_Data_Creative_Work {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		parent::defineStructure();

		$this->structuredData = array_merge($this->structuredData, array(
			'@type' => 'Article',
			'articleSection' => '{{post.term taxonomy="knowhow-category"}}',
			'author' => new WpmPlaceholder( 'organization', [], [
				'format' => 'short'
			])
		));
	}
}
?>
