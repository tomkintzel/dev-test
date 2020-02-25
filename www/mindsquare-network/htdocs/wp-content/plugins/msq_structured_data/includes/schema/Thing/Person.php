<?php
class MSQ_Structured_Data_Person extends MSQ_Structured_Data_Thing {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'Person',
			'email' => '{{null}}',
			'gender' => '{{null}}',
			'jobTitle' => '{{null}}',
			'worksFor' => '{{organization id="37" format="short"}}',
			'name' => '{{null}}',
			'description' => '{{post.excerpt}}',
			'sameAs' => '{{null}}',
			'url' => '{{post.url}}',
			'image' => '{{post.image}}'
		);
	}
}
?>
