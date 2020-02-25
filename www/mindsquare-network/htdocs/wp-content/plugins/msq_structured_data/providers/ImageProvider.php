<?php

class ImageProvider extends DataProvider {
	const PROVIDED_OBJECT = 'image';

	public static function formatImage( $url, $height, $width ) {
		if ( !isset( $url, $height, $width ) ) return null;

		return [
			'@type'  => 'ImageObject',
			'url'    => $url,
			'height' => $height,
			'width'  => $width
		];
	}

	public static function get( $id = null, $imageSize = null, $fbId = null ) {
		if ( !isset( $id ) ) return null;

		if ( is_numeric( $fbId ) ) {
			switch_to_blog( $fbId );
		}

		$size = empty ( $imageSize ) ? 'thumbnail' : $imageSize;
		$image = wp_get_attachment_image_src( $id, $size );

		if ( is_numeric( $fbId ) ) {
			restore_current_blog();
		}

		return self::formatImage( $image[0], $image[2], $image[1] );
	}

	public static function getAvatarUrl( $id = null, $imageSize = null ) {
		if ( !isset( $id ) ) return null;

		$options = [];

		if ( isset( $imageSize ) ) {
			$options['size'] = $imageSize;
		}

		return get_avatar_url( AuthorProvider::getEmail( $id ), $options );
	}
}