<?php
class MSQ_Structured_Data_Job_Posting extends MSQ_Structured_Data_Thing {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'JobPosting',
			'datePosted' => '{{post.date}}',
			'description' => '{{post.excerpt}}',
			'employmentType' => '{{null}}',
			'hiringOrganization' => '{{organization id="37" format="short"}}',
			'jobLocation' => array(
				'@type' => 'Place',
				'address' => new WpmPlaceholder( 'location', [ 'all' ] )
			),
			'title' => '{{post.title}}'
		);
	}
}
?>
