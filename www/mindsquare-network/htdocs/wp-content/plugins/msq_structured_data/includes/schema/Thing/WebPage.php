<?php
class MSQ_Structured_Data_Web_Page extends MSQ_Structured_Data_Thing {
	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'WebPage',
			'breadcrumb' => array(
				'@context' => 'http://schema.org',
				'@type' => 'BreadcrumbList',
				'itemListElement' => '{{breadcrumb}}'
			),
			'significantLink' => '{{null}}',
			'inLanguage' => 'de-DE',
			'copyrightYear' => '{{date format="Y"}}',
			'author' => '{{organization format="short"}}',
			'publisher' => '{{organization format="short"}}',
			'copyrightHolder' => '{{organization id="37" format="short"}}',
		);

		if( is_single() || is_page() ) {
			$this->structuredData += array(
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id' => '{{post.url}}'
				),
				'lastReviewed' => '{{post.modified}}',
				'primaryImageOfPage' => '{{post.image}}',
				'name' => '{{post.title}}',
				'headline' => '{{post.title}}',
				'description' => '{{post.excerpt}}',
				'image' => '{{post.image}}',
				'url' => '{{post.url}}',
				'keywords' => '{{post.tags}}',
				'dateCreated' => '{{post.date}}',
				'dateModified' => '{{post.modified}}',
				'datePublished' => '{{post.published}}',
				'text' => '{{post.content}}',
				'video' => '{{video search="{{post.content raw=\'true\'}}"}}'
			);
		} elseif( is_category() ) {
			$this->structuredData += array(
				'name' => '{{category.title}}',
				'headline' => '{{category.title}}',
				'description' => '{{category.description}}',
				'url' => '{{category.url}}',
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id' => '{{category.url}}'
				)
			);
		} elseif( is_post_type_archive() ) {
			$this->structuredData += array(
				'name' => '{{archive.title}}',
				'headline' => '{{archive.title}}',
				'description' => '{{archive.description}}',
				'url' => '{{archive.url}}',
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id' => '{{archive.url}}'
				)
			);
		} elseif( is_tax() ) {
			$this->structuredData += array(
				'name' => '{{term.title}}',
				'headline' => '{{term.title}}',
				'description' => '{{term.description}}',
				'url' => '{{term.url}}',
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id' => '{{term.url}}'
				)
			);
		} elseif( is_author() ) {
			$authorName = get_the_author();
			$url = get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) );
			$this->structuredData += array(
				'name' => 'Beiträge von ' . $authorName,
				'headline' => 'Beiträge von ' . $authorName,
				'description' => null,
				'url' => $url,
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id' => $url
				)
			);
		} elseif( is_date() ) {
			global $wp;
			$url = home_url( $wp->request );
			$this->structuredData += array(
				'name' => '{{archive.title}}',
				'headline' => '{{archive.title}}',
				'description' => null,
				'url' => $url,
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id' => $url
				)
			);
		}
	}
}
?>
