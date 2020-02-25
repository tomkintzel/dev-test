<?php
/*
Plugin Name: Uppercase To Lowercase
Description: Fixes 404s in pages caused by uppercase letters in url.
Version: 1.0
Author: Edwin Eckert
License: GPL2
*/
function isPartUppercase($string) {
	return (bool) preg_match('/[A-Z]/', $string);
}

add_action('parse_request', 'parseUppercase');

function parseUppercase($wp) {
	$params = strstr($_SERVER["REQUEST_URI"], '?');
	$query = strtok($_SERVER["REQUEST_URI"],'?');
	if(isPartUppercase($query))
	{
		$link = site_url() . strtolower($query) . $params;
		wp_redirect( $link , 301 );
		exit;
	}
}
?>