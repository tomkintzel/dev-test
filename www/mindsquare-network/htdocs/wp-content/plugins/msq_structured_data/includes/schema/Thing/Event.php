<?php
class MSQ_Structured_Data_Event extends MSQ_Structured_Data_Thing {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'Event',
			'location' => array(
				'@type' => 'Place',
				'name' => '{{null}}',
				'address' => '{{null}}'
			),
			'name' => '{{post.title}}',
			'url' => '{{post.url}}',
			'description' => '{{null}}',
			'startDate' => '{{null}}',
			'endDate' => '{{null}}',
			'image' => '{{null}}'
		);
	}
}
?>
