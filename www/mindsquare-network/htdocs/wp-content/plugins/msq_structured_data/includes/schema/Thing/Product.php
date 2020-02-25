<?php
class MSQ_Structured_Data_Product extends MSQ_Structured_Data_Thing {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		parent::defineStructure();
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'Product',
			'brand' => '{{fachbereich.brand}}',
			'category' => '{{post.term}}',
			'description' => '{{post.excerpt}}',
			'image' => '{{null}}',
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id' => '{{post.url}}'
			),
			'name' => '{{post.title}}',
			'url' => '{{post.url}}'
		);
	}
}
?>
