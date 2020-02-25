<?php

class PostProvider extends DataProvider {
	const PROVIDED_OBJECT = 'post';

	/** @var WP_Post */
	private $post;

	/** @var WP_Post */
	private $temporaryPost;

	public function before( WpmPlaceholder &$placeholder ) {
		$this->setPost( $placeholder->getParameter( 'id' ) );
	}

	public function getTitle() {
		return html_entity_decode( $this->post->post_title, ENT_NOQUOTES, 'UTF-8' );
	}

	public function getMeta( $name = null ) {
		if ( !isset( $name ) ) return null;
		return get_post_meta( $this->post->ID, $name, true );
	}

	public function getDate() {
		$date = $this->post->post_date;
		return DateProvider::format( $date );
	}

	public function getModified() {
		$date = $this->post->post_modified;
		return DateProvider::format( $date );
	}

	public function getPublished() {
		$date = $this->post->post_date;
		return DateProvider::format( $date );
	}

	public function getAcf( $name = null ) {
		if ( !isset( $name ) ) return null;
		return get_field( $name, $this->post->ID );
	}

	public function getWordCount() {
		return str_word_count( $this->post->post_content );
	}

	public function getContent( $raw = false ) {
		$postContent = $this->post->post_content;

		if ( !$raw ) {
			$postContent = wp_strip_all_tags( do_shortcode( $postContent ), true );
		}

		return $postContent;
	}

	public function getExcerpt() {
		$excerpt = get_post_meta( $this->post->ID, '_yoast_wpseo_metadesc', true );

		if ( empty( $excerpt ) && !empty( $this->post->post_excerpt ) ) {
			$excerpt = apply_filters( 'get_the_excerpt', $this->post->post_excerpt, $this->post );
		} else {
			$excerpt = strip_shortcodes( $this->post->post_content );
			$excerpt = apply_filters( 'the_content', $excerpt );
			$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
			$excerpt_length = apply_filters( 'excerpt_length', 55 );
			$excerpt_more = apply_filters( 'excerpt_more', ' [&hellip;]' );

			$excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
		}

		return $excerpt;
	}

	public function getCategories() {
		$categories = get_the_category( $this->post );
		$names = [];

		foreach ( $categories as $category ) {
			$names[] = $category->name;
		}

		return $names;
	}

	public function getTerm( $taxonomy = null ) {
		$parameter = [ 'id' => $this->post->ID ];

		if ( !empty( $taxonomy ) ) {
			$parameter['taxonomy'] = $taxonomy;
		}

		return new WpmPlaceholder( 'term', [], $parameter);
	}

	public function getThumbnail() {
		return get_post_thumbnail_id( $this->post->ID );
	}

	public function getAuthor() {
		return $this->post->post_author;
	}

	public function getId() {
		return $this->post->ID;
	}

	public function getName() {
		return $this->post->post_name;
	}

	public function getType() {
		return $this->post->post_type;
	}

	public function getUrl() {
		return get_permalink( $this->post );
	}

	public function getParent() {
		return $this->post->post_parent;
	}

	public function getCommentCount() {
		return get_comment_count( $this->post->ID )['approved'];
	}

	public function getComments() {
		$rawComments = get_comments( [
			'post_id' => $this->post->ID
		] );

		$comments = [];

		foreach ( $rawComments as $rawComment ) {
			$comments[] = self::formatComment( $rawComment );
		}

		return $comments;
	}

	public function getTags() {
		$tags = wp_get_post_terms( $this->post->ID, 'post_tag', array(
			'fields' => 'names'
		) );

		if ( empty( $tags ) ) {
			$tags = null;
		}

		return $tags;
	}

	/**
	 * Diese Funktion gibt ein Bild nach der folgenden Reihenfolge:
	 * 1. Post-Thumbnail
	 * 2. Das erste Video-Thumbnail im Post-Content
	 * 3. Das erste Bild im Post-Content
	 */
	public function getImage() {
		if( !empty( $this->post ) ) {
			$thumbnailId = $this->getThumbnail();
			if( !empty( $thumbnailId ) ) {
				return new WpmPlaceholder( 'image', [], [
					'id' => $thumbnailId,
					'imageSize' => 'full'
				]);
			}

			$rawPostContent = $this->getContent( true );
			$videoJson = VideoProvider::get( $rawPostContent );
			if( !empty( $videoJson[ 'thumbnailUrl' ] ) ) {
				return $videoJson[ 'thumbnailUrl' ];
			}

			if( preg_match( '/< *img[^>]*src *= *["\']?([^"\']+)/i', $rawPostContent, $match ) ) {
				return $match[ 1 ];
			}
		}
	}

	public function getVideo( $id = null ) {
		return new WpmPlaceholder( 'video', [], array(
			'search' => new WpmPlaceholder(
				'post', [ 'content' ], array(
					'raw' => true,
					'id' => !empty( $id ) ? $id : get_the_ID()
				)
			)
		));
	}

	public static function formatComment( $rawComment ) {
		$upvotes = max( 0, $rawComment->comment_karma );
		$downvotes = min( 0, $rawComment->comment_karma );

		$date = DateProvider::format( $rawComment->comment_date );

		return [
			'@type'         => 'Comment',
			'text'          => $rawComment->comment_content,
			'datePublished' => $date,
			'upvoteCount'   => $upvotes,
			'downvoteCount' => $downvotes,
			'author'        => [
				'@type' => 'Person',
				'name'  => $rawComment->comment_author,
				'email' => $rawComment->comment_author_email,
				'url'   => $rawComment->comment_author_url
			]
		];
	}

	protected function initializePlaceholder( WpmPlaceholder &$placeholder ) {
		parent::initializePlaceholder( $placeholder );
	}

	/**
	 * @return WP_Post
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * @param WP_Post | int $post        Der Post
	 * @param bool          $temporarily Ob der Post nur temporär geändert werden soll. Kann mit {@link resetPost()}
	 *                                   rückgängig gemacht werden.
	 */
	public function setPost( $post, $temporarily = false ) {
		if ( $temporarily && !isset( $this->temporaryPost ) ) {
			$this->temporaryPost = $this->post;
		}

		if ( $post instanceof WP_Post ) {
			$this->post = $post;
		} elseif ( is_numeric( $post ) ) {
			$this->post = get_post( $post );
		} else {
			global $post;
			$this->post = $post;
		}
	}

	/**
	 * Falls der Post mit {@link setPost()} temporär geändert wurde, kann dies hier zurückgesetzt werden
	 */
	public function resetPost() {
		$this->post = $this->temporaryPost;
		unset( $this->temporaryPost );
	}
}