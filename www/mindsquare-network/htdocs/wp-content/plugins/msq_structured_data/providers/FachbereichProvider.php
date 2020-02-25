<?php

class FachbereichProvider extends DataProvider {
	const PROVIDED_OBJECT = 'fachbereich';
	private $fachbereiche;

	public function __construct() {
		switch_to_blog( 37 );
		$fachbereiche = get_field( 'sd_fachbereiche', 'options' );
		restore_current_blog();

		$this->fachbereiche = [];

		if( !empty( $fachbereiche ) ) {
			foreach( $fachbereiche as $fachbereich ) {
				$fields = [];

				MSQ_SD_Utils::mapKeys( [
					'name',
					'blog_id',
					'logo' => [
						'url',
						'width',
						'height'
					],
					'publisher_logo' => [
						'url',
						'width',
						'height'
					]
				], $fachbereich, $fields, 'MSQ_SD_Utils::toCamelCase' );

				$this->fachbereiche[$fachbereich['blog_id']] = $fields;
			}
		}
	}

	public function before( WpmPlaceholder &$placeholder ) {
		parent::before( $placeholder );
		$placeholder->initParameter( 'id', get_current_blog_id(), 'MSQ_SD_Utils::validateNumber' );
	}

	public function getIds( $excludeIds = '' ) {
		$excludeIds = MSQ_SD_Utils::parseArray( $excludeIds );

		$ids = array_keys( $this->fachbereiche );
		$ids = array_diff( $ids, $excludeIds );

		return $ids;
	}

	public function getBrand( $id = null ) {
		$id = is_numeric( $id ) ? $id : get_current_blog_id();

		if( !empty( $this->fachbereiche[ $id ][ 'name' ] ) ) {
			return [
				'@type' => 'Brand',
				'name'  => $this->fachbereiche[ $id ][ 'name' ],
				'logo'  => $this->getLogo( $id )
			];
		}
		return null;
	}

	public function getName( $id = null ) {
		$id = is_numeric( $id ) ? $id : get_current_blog_id();

		if( !empty( $this->fachbereiche[ $id ][ 'name' ] ) ) {
			return $this->fachbereiche[ $id ][ 'name' ];
		}
		return null;
	}

	public function getLogo( $id = null, $publisherLogo = false ) {
		$id = is_numeric( $id ) ? $id : get_current_blog_id();

		if( !empty( $this->fachbereiche[ $id ] ) ) {
			if ( !$publisherLogo ) {
				$logo = $this->fachbereiche[$id]['logo'];
			} else {
				$logo = $this->fachbereiche[$id]['publisherLogo'];
			}

			if( !empty( $logo ) ) {
				$logo['url'] = home_url( preg_replace( '/.*?\.de\//i', '', $logo['url'] ) );
				$logo = ImageProvider::formatImage( $logo['url'], $logo['height'], $logo['width'] );

				return $logo;
			}
		}
		return null;
	}
}