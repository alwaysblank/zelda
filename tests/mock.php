<?php

/**
 * Mock some WP functions if they are not available for testing.
 */

if (!function_exists('__')) {
	/**
	 * Fake the localization, and just return the string.
	 *
	 * @param $text
	 * @param $domain
	 *
	 * @return mixed
	 */
	function __($text, $domain) {
		return $text;
	}
}

if (!function_exists('apply_filters')) {
	function apply_filters($tag, $value) {
		return $value;
	}
}