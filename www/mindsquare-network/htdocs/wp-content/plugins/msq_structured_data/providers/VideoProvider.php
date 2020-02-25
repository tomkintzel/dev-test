<?php

class VideoProvider extends DataProvider {
	const PROVIDED_OBJECT = 'video';

	public static function get( $search = null ) {
		$response = null;

		if ( isset( $search ) && preg_match( '~https?://(?:[0-9A-Z-]+\.)?(?:youtu\.be/|youtube(?:-nocookie)?\.com\S*?[^\w\s-])([\w-]{11})(?=[^\w-]|$)[?=&+%\w.-]*~im', $search, $matches ) ) {
			$response = json_decode( wp_remote_retrieve_body( wp_remote_get( sprintf( 'https://api.hunchmanifest.com/schemaorg/video.json?ids=%s', $matches[1] ) ) ), true );
		}

		return $response;
	}
}