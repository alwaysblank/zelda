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

if (!function_exists('get_permalink')) {
	function get_permalink($integer) {
		if (is_integer($integer)) {
			return '/link/to/permalink';
		}

		return false;
	}
}

if (!function_exists('get_term_link')) {
	function get_term_link($integer) {
		if (is_integer($integer)) {
			return '/link/to/term';
		}

		return false;
	}
}

if (!function_exists('get_post_type_archive_link')) {
	function get_post_type_archive_link($string) {
		if (is_string($string)) {
			return '/link/to/archive';
		}

		return false;
	}
}

if (!function_exists('esc_attr')) {
	function esc_attr($string) {
		return $string;
	}
}

if (!function_exists('is_wp_error')) {
	function is_wp_error($return) {
		return false === $return ? true : false;
	}
}