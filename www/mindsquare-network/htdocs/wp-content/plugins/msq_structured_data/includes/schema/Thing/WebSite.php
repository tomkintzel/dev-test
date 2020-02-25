<?php
class MSQ_Structured_Data_Web_Site extends MSQ_Structured_Data_Thing {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'WebSite',
			'name' => '{{blog.name}}',
			'description' => '{{blog.description}}',
			'image' => '{{fachbereich.logo}}',
			'url' => '{{blog.url}}',
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id' => '{{post.url}}'
			),
			'inLanguage' => 'de-DE',
			'headline' => '{{blog.name}}',
			'keywords' => '{{post.tags}}',
			'dateCreated' => '{{blog.date}}',
			'dateModified' => '{{blog.modified}}',
			'datePublished' => '{{blog.date}}',
			'copyrightYear' => '{{date format="Y"}}',
			'author' => '{{organization format="short"}}',
			'publisher' => '{{organization format="short"}}',
			'copyrightHolder' => '{{organization id="37" format="short"}}',
			'text' => '{{post.content}}',
			'video' => '{{video search="{{post.content raw=\'true\'}}"}}',
			'potentialAction' => array(
				'@type' => 'SearchAction',
				'target' => get_search_link() . '?s={query}',
				'query-input' => 'required name=query'
			)
		);
	}
}
?>
