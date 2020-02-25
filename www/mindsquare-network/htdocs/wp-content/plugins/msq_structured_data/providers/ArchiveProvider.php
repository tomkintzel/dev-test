<?php

class ArchiveProvider extends DataProvider {
	const PROVIDED_OBJECT = 'archive';

	public function getTitle() {
		$title = null;
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} else if ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} else {
			$title = get_the_archive_title();
		}

		return $title;
	}

	public function getDescription() {
		return get_the_archive_description();
	}

	public function getUrl( $postType = null ) {
		if ( empty( $postType ) ) {
			$postType = get_post_type();
		}

		return get_post_type_archive_link($postType);
	}
}