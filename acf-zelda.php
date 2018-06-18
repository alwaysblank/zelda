<?php

/*
Plugin Name: Advanced Custom Fields: Zelda
Plugin URI: https://github.com/alwaysblank/zelda
Description: More flexible, less fragile: Better link selection.
Version: 0.0.1-alpha
Author: Ben Martinez-Bateman
Author URI: https://www.alwaysblank.org
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('livy_acf_plugin_zelda') ) :

class livy_acf_plugin_zelda {
	
	// vars
	var $settings;
	
	
	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/
	
	function __construct() {
		
		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);
		
		
		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
		add_action('acf/register_fields', 		array($this, 'include_field')); // v4
	}
	
	
	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to 4
	*  @return	void
	*/
	
	function include_field( $version = 5 ) {
		
		// load textdomain
		load_plugin_textdomain( 'acf-zelda', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
		
		
		// include
		/** @noinspection PhpIncludeInspection */
		include_once( 'fields/class-livy-acf-field-zelda-v' . $version . '.php');
	}
	
}


// initialize
new livy_acf_plugin_zelda();


// class_exists check
endif;
	
