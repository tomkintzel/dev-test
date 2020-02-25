<?php
class MSQ_Structured_Data_Creative_Work extends MSQ_Structured_Data_Thing {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		parent::defineStructure();

		$this->structuredData = array_merge($this->structuredData, array(
			'@type' => 'CreativeWork',
			'articleBody' => '{{post.content}}',
			'articleSection' => '{{post.categories}}',
			'wordCount' => '{{post.word_count}}',
			'headline' => '{{post.title}}',
			'image' => '{{post.image}}',
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id' => '{{post.url}}'
			),
			'datePublished' => '{{post.published}}',
			'dateModified' => '{{post.modified}}',
			'author' => '{{author id="{{post.author}}"}}',
			'publisher' => '{{organization id="{{blog.id}}" format="short" publisher-logo="true"}}',
			'description' => '{{post.excerpt}}',
			'comment' => '{{post.comments}}',
			'commentCount' => '{{post.comment_count}}',
			'copyrightHolder' => '{{organization id="37" format="short"}}',
			'copyrightYear' => '{{date format="Y"}}',
			'inLanguage' => 'de-DE',
			'isAccessibleForFree' => true,
			'keywords' => '{{post.tags}}',
			'video' => '{{post.video}}',
			'name' => '{{post.title}}',
			'url' => '{{post.url}}'
		));
	}
}