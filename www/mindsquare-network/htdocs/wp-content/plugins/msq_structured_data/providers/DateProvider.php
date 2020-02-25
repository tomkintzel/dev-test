<?php

class DateProvider extends DataProvider {
	const PROVIDED_OBJECT = 'date';

	public static function format($date, $format = DateTime::RFC3339) {
		return mysql2date($format, $date);
	}

	public function get($time = null, $format = DateTime::RFC3339) {
		return date($format, $time);
	}

	protected function initializePlaceholder( WpmPlaceholder &$placeholder ) {
		$placeholder->initParameter('format', DateTime::RFC3339 );
		parent::initializePlaceholder( $placeholder );

		$placeholder->initParameter('time', time() );
	}
}