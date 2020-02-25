<?php

class CategoryProvider extends DataProvider {
	const PROVIDED_OBJECT = 'category';

	public function getTitle() {
		return single_cat_title('', false);
	}

	public function getDescription( $id = 0 ) {
		return category_description( $id );
	}

	public function getUrl( $id = null ) {
		if ( empty( $id ) ) {
			$id = $this->getId();
		}

		if ( !empty( $id ) ) {
			return get_category_link( $id );
		}
	}

	public function getId() {
		$categories = get_the_category();
		if( !empty( $categories ) ) {
			return $categories[ 0 ]->cat_ID;
		}
		return null;
	}

}