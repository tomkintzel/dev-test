<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;

abstract class Update_Details extends Async_Task {
	/**
	 * @param string $key
	 * @param string $html
	 * @param string &$result
	 * @return bool|string
	 */
	public static function match_table_value( $key, $html, &$result = null ) {
		if( preg_match( '/>' . $key . '<\/td.+?"value"[^>]*>(?<value>.*?)<\/td>/is', $html, $match ) ) {
			$result = $match[ 'value' ];
			return $match[ 'value' ];
		}
		return false;
	}
}
