<?php

namespace Livy\Zelda;

use livy_acf_field_zelda;

class Settings {
	/**
	 * This are the settings we want to apply to the ACF field object on instantiation.
	 * @var array
	 */
	private $acf_settings = [
		'name',
		'label',
		'category',
		'defaults',
		'l10n',
	];
	/*
	 * Machine-readable name for this plugin. Single word, no spaces. Underscores allowed.
	 * @return string
	 */
	public function name() {
		return 'zelda';
	}

	/**
	 * Translatable name for this plugin, used in the UI. Multiple words, can include spaces.
	 * @return string
	 */
	public function label() {
		return __( 'Zelda', 'acf-zelda' );
	}

	/**
	 * Field category; used by ACF to sort field types when creating fields.
	 * @return string
	 */
	public function category() {
		return 'relational';
	}

	/**
	 * Set of default values for the field.
	 * @return array
	 */
	public function defaults() {
		return array(
			'post_type'         => false,
			'post_type_archive' => false,
			'taxonomy'          => false,
			'link_class'        => null,
			'user_class'        => false,
			'default_text'      => "Read More",
			'user_text'         => false,
			'email'             => false,
			'external'          => false,
			'new_tab'           => true,
		);
	}

	/**
	 * Set of strings passed to JavaScript. This allows JS strings to be translated in PHP and loaded via:
	 * var message = acf._e('zelda', 'error');
	 * @return array
	 */
	public function l10n() {
		return array(
			'error' => __( 'Error! Please enter a higher value', 'acf-zelda' ),
		);
	}

	/**
	 * Quickly get setting.
	 * @param $setting
	 *
	 * @return bool
	 */
	public static function get($setting) {
		$Settings = new self;
		if (method_exists($Settings, $setting)) {
			return $Settings->{$setting}();
		}

		return false;
	}

	/**
	 * Applies and individual setting to an ACF field object
	 * @param livy_acf_field_zelda $object
	 * @param $setting
	 *
	 * @return mixed
	 */
	private function apply_to_acf_object( livy_acf_field_zelda $object, $setting) {
		if (method_exists($this, $setting)) {
			$object->{$setting} = $this->{$setting}();
		}
		return $object;
	}

	/**
	 * @param livy_acf_field_zelda $field_object
	 *
	 * @return void
	 */
	public static function apply( livy_acf_field_zelda $field_object ) {
		$Settings = new self;
		array_reduce($Settings->acf_settings, [$Settings, 'apply_to_acf_object'], $field_object);
	}
}