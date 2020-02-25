<?php
class MSQ_Structured_Data_Course extends MSQ_Structured_Data_Thing {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'Course',
			'name' => '{{post.title}}',
			'description' => '{{post.excerpt}}',
			'provider' => '{{organization id="37" format="short"}}',
			'video' => '{{post.video}}',
			'url' => '{{post.url}}'
		);
	}
}
?>
