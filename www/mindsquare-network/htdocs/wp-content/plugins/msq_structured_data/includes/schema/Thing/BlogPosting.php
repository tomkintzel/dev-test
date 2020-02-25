<?php
require_once __DIR__ . '/CreativeWork.php';
require_once __DIR__ . '/../../../providers/ImageProvider.php';

class MSQ_Structured_Data_Blog_Posting extends MSQ_Structured_Data_Creative_Work {
	public static function fromPost( WP_Post $post ) {
		$title             = get_the_title( $post );
		$yoast_title       = msq_get_yoast_title( $post );
		$yoast_description = msq_get_yoast_description( $post );
		$tags              = get_the_tags( $post );
		$keywords          = null;
		if ( ! empty( $tags ) ) {
			$keywords = array_map( function ( WP_Term $tag ) {
				return $tag->name;
			}, $tags );
			$keywords = implode( ', ', $keywords );
		}

		if ( $post->post_type === 'downloads'
			|| $post->post_type === 'webinare' ) {
			switch_to_blog( 37 );

			switch ( $post->post_type ) {
				case 'downloads':
					$selector = 'download_image';
					break;
				case 'webinare':
					$selector = 'webinar_img';
					break;
			}

			$author = 'Tobias Harmes';
			$image = msq_get_acf_image( $selector, $post, 'full' );
			$image = ImageProvider::formatImage($image['url'], $image['height'], $image['width']);
			restore_current_blog();
		} else {
			$author = get_the_author_meta( 'display_name', $post->post_author );
			$image = ImageProvider::get( get_post_thumbnail_id( $post ), 'full' );
		}


		$blogPosting                 = new MSQ_Structured_Data_Blog_Posting();
		$blogPosting->structuredData = [
			'@context'             => 'http://schema.org',
			'@type'                => 'BlogPosting',
			'headline'             => $yoast_title,
			'name'                 => $yoast_title,
			'alternativeHeadline'  => $title,
			'description'          => $yoast_description,
			'datePublished'        => DateProvider::format( $post->post_date ),
			'dateModified'         => DateProvider::format( $post->post_modified ),
			'image'                => $image,
			'interactionStatistic' => [
				'@type'                => 'InteractionCounter',
				'interactionType'      => 'http://schema.org/LikeAction',
				'userInteractionCount' => get_post_meta( $post->ID, 'msq-like-post-value', true ) ?: 0
			],
			'mainEntityOfPage'     => array(
				'@type' => 'WebPage',
				'@id'   => get_permalink( $post ),
			),
			'author'               => [
				'@type' => 'Person',
				'name'  => $author,
			],
			'keywords'             => $keywords,
			'publisher'            => '{{organization id="{{blog.id}}" format="short" publisher-logo="true"}}',
		];

		return $blogPosting;
	}

	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		parent::defineStructure();
		$this->structuredData['@type'] = 'BlogPosting';
	}
}

?>
