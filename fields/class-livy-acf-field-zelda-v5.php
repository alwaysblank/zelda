<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// check if class already exists
if ( ! class_exists( 'livy_acf_field_zelda' ) ) :


	class livy_acf_field_zelda extends acf_field {

		/**
		 * array_map but with keys.
		 *
		 * Pulled from Zenodorus\Arrays, because I don't want to include the entire library.
		 *
		 * @link https://github.com/zenodorus-tools/arrays
		 *
		 * @param callable $function
		 * @param array    $array
		 *
		 * @return mixed
		 */
		function array_map_assoc( callable $function, array $array ) {
			$new_array = array();
			foreach ( $array as $key => $value ) {
				$result = call_user_func( $function, $value, $key );
				if ( is_array( $result ) ) {
					if ( 1 === count( $result ) ) {
						// Only returned a value, no key, so keep existing key.
						$new_array[ $key ] = array_shift( $result );
					} elseif ( 2 === count( $result ) ) {
						// Returned a key and a value, so set a new key.
						$new_array[ array_shift( $result ) ] = array_shift( $result );
					}
				}
				unset( $result );
			}

			return $new_array;
		}


		/*
		*  __construct
		*
		*  This function will setup the field type data
		*
		*  @type	function
		*  @date	5/03/2014
		*  @since	5.0.0
		*
		*  @param	n/a
		*  @return	n/a
		*/

		function __construct( $settings ) {

			/*
			*  name (string) Single word, no spaces. Underscores allowed
			*/

			$this->name = 'zelda';


			/*
			*  label (string) Multiple words, can include spaces, visible when selecting a field type
			*/

			$this->label = __( 'Zelda', 'acf-zelda' );


			/*
			*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			*/

			$this->category = 'relational';


			/*
			*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			*/

			$this->defaults = array(
				'post_type'         => false,
				'post_type_archive' => false,
				'taxonomy'          => false,
				'link_class'        => null,
				'user_class'        => false,
				'default_text'      => "Read More",
				'user_text'         => false,
				'email'             => false,
				'external'          => false,
				'external_new_tab'  => true,
			);


			/*
			*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
			*  var message = acf._e('zelda', 'error');
			*/

			$this->l10n = array(
				'error' => __( 'Error! Please enter a higher value', 'acf-zelda' ),
			);


			/*
			*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
			*/

			$this->settings = $settings;


			// do not delete!
			parent::__construct();

		}


		/*
		*  render_field_settings()
		*
		*  Create extra settings for your field. These are visible when editing a field
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field (array) the $field being edited
		*  @return	n/a
		*/

		function render_field_settings( $field ) {

			/*
			*  acf_render_field_setting
			*
			*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
			*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
			*
			*  More than one setting can be added by copy/paste the above code.
			*  Please note that you must also have a matching $defaults value for the field name (font_size)
			*/

			acf_render_field_setting( $field, array(
				'label'        => __( 'Allowed Post Types', 'acf' ),
				'instructions' => '',
				'type'         => 'select',
				'name'         => 'post_type',
				'choices'      => acf_get_pretty_post_types(),
				'multiple'     => 1,
				'ui'           => 1,
				'allow_null'   => 1,
				'placeholder'  => __( "", 'acf' ),
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'Post Type Archives', 'acf-zelda' ),
				'instructions' => __( 'Allow users to link to post type archives?', 'acf-zelda' ),
				'name'         => 'post_type_archive',
				'type'         => 'true_false',
				'ui'           => 1,
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'Allowed Taxonomies', 'acf' ),
				'instructions' => 'This allows users to link to taxonomy archives.',
				'type'         => 'select',
				'name'         => 'taxonomy',
				'choices'      => acf_get_pretty_taxonomies(),
				'multiple'     => 1,
				'ui'           => 1,
				'allow_null'   => 1,
				'placeholder'  => __( "", 'acf' ),
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'Class', 'acf-zelda' ),
				'instructions' => __( 'This class will be applied to all returned elements.', 'acf-zelda' ),
				'type'         => 'text',
				'name'         => 'link_class',
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'User Class', 'acf-zelda' ),
				'instructions' => __( 'Allow users to add an arbitrary class to the returned element?', 'acf-zelda' ),
				'name'         => 'user_class',
				'type'         => 'true_false',
				'ui'           => 1,
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'User Text', 'acf-zelda' ),
				'instructions' => __( 'Allow users set the content of the linked text?', 'acf-zelda' ),
				'name'         => 'user_text',
				'type'         => 'true_false',
				'ui'           => 1,
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'Default Text', 'acf-zelda' ),
				'instructions' => __( "This will be the linked text unless <b>User Text</b> is True and the user has entered text.", 'acf-zelda' ),
				'type'         => 'text',
				'name'         => 'default_text',
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'Email', 'acf-zelda' ),
				'instructions' => __( 'Allow email links?', 'acf-zelda' ),
				'name'         => 'email',
				'type'         => 'true_false',
				'ui'           => 1,
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'External', 'acf-zelda' ),
				'instructions' => __( 'Allow external links?', 'acf-zelda' ),
				'name'         => 'external',
				'type'         => 'true_false',
				'ui'           => 1,
			) );

			acf_render_field_setting( $field, array(
				'label'        => __( 'Open in New Tab', 'acf-zelda' ),
				'instructions' => __( 'Open external links in new tab?', 'acf-zelda' ),
				'name'         => 'external_new_tab',
				'type'         => 'true_false',
				'ui'           => 1,
			) );

		}


		/*
		*  render_field()
		*
		*  Create the HTML interface for your field
		*
		*  @param	$field (array) the $field being rendered
		*
		*  @type	action
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$field (array) the $field being edited
		*  @return	n/a
		*/

		function render_field( $field ) {


			/*
			*  Review the data of $field.
			*  This will show what data is available
			 */

			/**
			 * Generate a list of possible link types.
			 */
			$type_options = array();

			if ( $field['post_type'] && is_array( $field['post_type'] ) ) {
				$type_options['content'] = array(
					'label'   => "Content",
					'options' => $this->array_map_assoc( function ( $key, $value ) {
						$post_type = get_post_type_object( $key );

						return array( $key, $post_type->labels->name );
					}, $field['post_type'] )
				);
			}

			if ( $field['taxonomy'] && is_array( $field['taxonomy'] ) ) {
				$type_options['taxonomies'] = array(
					'label'   => "Taxonomies",
					'options' => $this->array_map_assoc( function ( $key, $value ) {
						$taxonomy = get_taxonomy( $key );

						return array( $key, $taxonomy->labels->name );
					}, $field['taxonomy'] )
				);
			}

			if ( $field['email'] ) {
				$type_options['email'] = "Email";
			}

			if ( $field['external'] ) {
				$type_options['external'] = "External";
			}

			echo '<pre>';
			var_dump( $field );


			var_dump( $type_options );
			echo '</pre>';

			/**
			 * Generate some input fields.
			 */

			/**
			 * Select type of link
			 */
			if ( is_array( $type_options ) && count( $type_options ) > 0 ) {
				?>
                <select name="<?php echo esc_attr( $field['name'] ) ?>[type]">
					<?php foreach ( $type_options as $option => $label ) {
						if ( is_array( $label ) ) {
							printf(
								'<optgroup label="%s">%s</optgroup>',
								$label['label'],
								join( '', $this->array_map_assoc( function ( $value, $key ) use ( $field ) {
									return [
										sprintf(
											'<option value="%s" %s>%s</option>',
											$key,
											$key == $field['value']['type'] ? 'selected' : null,
											$value
										)
									];
								}, $label['options'] )
								) );
						} elseif ( is_string( $label ) ) {
							printf(
								'<option value="%s" %s>%s</option>',
								$option,
								$option == $field['value']['type'] ? 'selected' : null,
								$label
							);
						}
					} ?>
                </select>
				<?php
			}

			/**
			 * Select the content, if there is content
			 */
			if ( isset( $type_options['content'] )
			     && is_array( $type_options['content'] )
			     && is_array( $type_options['content']['options'] ) ) {
				foreach ( $type_options['content']['options'] as $key => $label ) {
					?>
                    <label for="<?php echo esc_attr( $field['name'] ) ?>[content][<?php echo esc_attr( $key ) ?>]">
						<?php echo $label ?>
                    </label>
                    <select name="<?php echo esc_attr( $field['name'] ) ?>[content][<?php echo esc_attr( $key ) ?>]">
						<?php if ( $field['post_type_archive'] ) {
							printf(
								'<option value="%s" %s>Archive</option>',
								$key . '_archive',
								$field['value']['content'][ $key ] == $key . '_archive' ? 'selected' : null
							);
						} ?>

						<?php $this_type = get_posts( array( 'post_type' => $key ) );
						if ( $this_type && count( $this_type ) > 0
						) {
							if ( $field['post_type_archive'] ) {
								echo '<option disabled>──────────</option>';
							}
							foreach ( $this_type as $post ) {
								printf( '<option value="%s" %s>%s</option>',
									$post->ID,
									(int) $field['value']['content'][ $key ] == $post->ID ? 'selected' : null,
									$post->post_title
								);
							}
							// Can't be too careful
							unset( $this_type );
						} ?>
                    </select>
					<?php
				}
			}

			/**
			 * Select taxonomies, if there are taxonomies
			 */
			if ( isset( $type_options['taxonomies'] )
			     && is_array( $type_options['taxonomies'] )
			     && is_array( $type_options['taxonomies']['options'] ) ) {
				foreach ( $type_options['taxonomies']['options'] as $key => $label ) {
					?>
                    <label for="<?php echo esc_attr( $field['name'] ) ?>[taxonomy][<?php echo esc_attr( $key ) ?>]">
						<?php echo $label ?>
                    </label>
                    <select name="<?php echo esc_attr( $field['name'] ) ?>[taxonomy][<?php echo esc_attr( $key ) ?>]">
						<?php $this_taxonomy = get_terms( array( 'taxonomy' => $key ) );
						if ( $this_taxonomy && count( $this_taxonomy ) > 0
						) {
							foreach ( $this_taxonomy as $taxonomy ) {
								/** @var $taxonomy \WP_Term */
								printf( '<option value="%s" %s>%s</option>',
									$taxonomy->term_taxonomy_id,
									(int) $field['value']['taxonomy'][ $key ] == $taxonomy->term_taxonomy_id ? 'selected' : null,
									$taxonomy->name
								);
							}
							// Can't be too careful
							unset( $this_taxonomy );
						} ?>
                    </select>
					<?php
				}
			}

			/*
			*  Email, if email is set
			*/
			if ( $type_options['email'] && is_string( $type_options['email'] ) ) {
				?>
                <label for="<?php echo esc_attr( $field['name'] ) ?>[email]"><?php echo $type_options['email']
					?></label>
                <input type="email" name="<?php echo esc_attr( $field['name'] ) ?>[email]"
                       value="<?php echo esc_attr( $field['value']['email'] ) ?>"/>
				<?php
			}

			/*
			*  External, if external is set
			*/
			if ( $type_options['external'] && is_string( $type_options['external'] ) ) {
				?>
                <label for="<?php echo esc_attr( $field['name'] ) ?>[external]"><?php echo $type_options['external']
					?></label>
                <input type="url" name="<?php echo esc_attr( $field['name'] ) ?>[external]"
                       value="<?php echo esc_attr( $field['value']['external'] ) ?>"/>
				<?php
			}

			/**
			 * Set user class
			 */
			if ( $field['user_class'] ) {
				?>
                <label for="<?php echo esc_attr( $field['name'] ) ?>[user_class]">Class</label>
                <input type="text" name="<?php echo esc_attr( $field['name'] ) ?>[user_class]"
                       value="<?php echo esc_attr( $field['value']['user_class'] ) ?>">
				<?php
			} else {
			    // Still submit this value, but make it null
				?>
                <input type="hidden" name="<?php echo esc_attr( $field['name'] ) ?>[user_class]"
                       value="">
				<?php
			}

			/**
			 * Set user text
			 */
			if ( $field['user_text'] ) {
				?>
                <label for="<?php echo esc_attr( $field['name'] ) ?>[user_text]">Text</label>
                <input type="text" name="<?php echo esc_attr( $field['name'] ) ?>[user_text]"
                       value="<?php echo esc_attr( $field['value']['user_text'] ) ?>">
				<?php
			} else {
				// Still submit this value, but make it the default text
				?>
                <input type="hidden" name="<?php echo esc_attr( $field['name'] ) ?>[user_text]"
                       value="<?php echo esc_attr( $field['default_text'] ) ?>">
				<?php
			}
		}


		/*
		*  input_admin_enqueue_scripts()
		*
		*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		*  Use this action to add CSS + JavaScript to assist your render_field() action.
		*
		*  @type	action (admin_enqueue_scripts)
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	n/a
		*  @return	n/a
		*/

		/*

		function input_admin_enqueue_scripts() {

			// vars
			$url = $this->settings['url'];
			$version = $this->settings['version'];


			// register & include JS
			wp_register_script('acf-zelda', "{$url}assets/js/input.js", array('acf-input'), $version);
			wp_enqueue_script('acf-zelda');


			// register & include CSS
			wp_register_style('acf-zelda', "{$url}assets/css/input.css", array('acf-input'), $version);
			wp_enqueue_style('acf-zelda');

		}

		*/


		/*
		*  input_admin_head()
		*
		*  This action is called in the admin_head action on the edit screen where your field is created.
		*  Use this action to add CSS and JavaScript to assist your render_field() action.
		*
		*  @type	action (admin_head)
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	n/a
		*  @return	n/a
		*/

		/*

		function input_admin_head() {



		}

		*/


		/*
		   *  input_form_data()
		   *
		   *  This function is called once on the 'input' page between the head and footer
		   *  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
		   *  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
		   *  seen on comments / user edit forms on the front end. This function will always be called, and includes
		   *  $args that related to the current screen such as $args['post_id']
		   *
		   *  @type	function
		   *  @date	6/03/2014
		   *  @since	5.0.0
		   *
		   *  @param	$args (array)
		   *  @return	n/a
		   */

		/*

		function input_form_data( $args ) {



		}

		*/


		/*
		*  input_admin_footer()
		*
		*  This action is called in the admin_footer action on the edit screen where your field is created.
		*  Use this action to add CSS and JavaScript to assist your render_field() action.
		*
		*  @type	action (admin_footer)
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	n/a
		*  @return	n/a
		*/

		/*

		function input_admin_footer() {



		}

		*/


		/*
		*  field_group_admin_enqueue_scripts()
		*
		*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
		*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
		*
		*  @type	action (admin_enqueue_scripts)
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	n/a
		*  @return	n/a
		*/

		/*

		function field_group_admin_enqueue_scripts() {

		}

		*/


		/*
		*  field_group_admin_head()
		*
		*  This action is called in the admin_head action on the edit screen where your field is edited.
		*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
		*
		*  @type	action (admin_head)
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	n/a
		*  @return	n/a
		*/

		/*

		function field_group_admin_head() {

		}

		*/


		/*
		*  load_value()
		*
		*  This filter is applied to the $value after it is loaded from the db
		*
		*  @type	filter
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$value (mixed) the value found in the database
		*  @param	$post_id (mixed) the $post_id from which the value was loaded
		*  @param	$field (array) the field array holding all the field options
		*  @return	$value
		*/

		/*

		function load_value( $value, $post_id, $field ) {

			return $value;

		}

		*/


		/*
		*  update_value()
		*
		*  This filter is applied to the $value before it is saved in the db
		*
		*  @type	filter
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$value (mixed) the value found in the database
		*  @param	$post_id (mixed) the $post_id from which the value was loaded
		*  @param	$field (array) the field array holding all the field options
		*  @return	$value
		*/

		function update_value( $value, $post_id, $field ) {

			return $value;

		}


		/*
		*  format_value()
		*
		*  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
		*
		*  @type	filter
		*  @since	3.6
		*  @date	23/01/13
		*
		*  @param	$value (mixed) the value which was loaded from the database
		*  @param	$post_id (mixed) the $post_id from which the value was loaded
		*  @param	$field (array) the field array holding all the field options
		*
		*  @return	$value (mixed) the modified value
		*/

		/*

		function format_value( $value, $post_id, $field ) {

			// bail early if no value
			if( empty($value) ) {

				return $value;

			}


			// apply setting
			if( $field['font_size'] > 12 ) {

				// format the value
				// $value = 'something';

			}


			// return
			return $value;
		}

		*/


		/*
		*  validate_value()
		*
		*  This filter is used to perform validation on the value prior to saving.
		*  All values are validated regardless of the field's required setting. This allows you to validate and return
		*  messages to the user if the value is not correct
		*
		*  @type	filter
		*  @date	11/02/2014
		*  @since	5.0.0
		*
		*  @param	$valid (boolean) validation status based on the value and the field's required setting
		*  @param	$value (mixed) the $_POST value
		*  @param	$field (array) the field array holding all the field options
		*  @param	$input (string) the corresponding input name for $_POST value
		*  @return	$valid
		*/

		/*

		function validate_value( $valid, $value, $field, $input ){

			// Basic usage
			if( $value < $field['custom_minimum_setting'] )
			{
				$valid = false;
			}


			// Advanced usage
			if( $value < $field['custom_minimum_setting'] )
			{
				$valid = __('The value is too little!','acf-zelda'),
			}


			// return
			return $valid;

		}

		*/


		/*
		*  delete_value()
		*
		*  This action is fired after a value has been deleted from the db.
		*  Please note that saving a blank value is treated as an update, not a delete
		*
		*  @type	action
		*  @date	6/03/2014
		*  @since	5.0.0
		*
		*  @param	$post_id (mixed) the $post_id from which the value was deleted
		*  @param	$key (string) the $meta_key which the value was deleted
		*  @return	n/a
		*/

		/*

		function delete_value( $post_id, $key ) {



		}

		*/


		/*
		*  load_field()
		*
		*  This filter is applied to the $field after it is loaded from the database
		*
		*  @type	filter
		*  @date	23/01/2013
		*  @since	3.6.0
		*
		*  @param	$field (array) the field array holding all the field options
		*  @return	$field
		*/

		/*

		function load_field( $field ) {

			return $field;

		}

		*/


		/*
		*  update_field()
		*
		*  This filter is applied to the $field before it is saved to the database
		*
		*  @type	filter
		*  @date	23/01/2013
		*  @since	3.6.0
		*
		*  @param	$field (array) the field array holding all the field options
		*  @return	$field
		*/

		/*

		function update_field( $field ) {

			return $field;

		}

		*/


		/*
		*  delete_field()
		*
		*  This action is fired after a field is deleted from the database
		*
		*  @type	action
		*  @date	11/02/2014
		*  @since	5.0.0
		*
		*  @param	$field (array) the field array holding all the field options
		*  @return	n/a
		*/

		/*

		function delete_field( $field ) {



		}

		*/


	}


// initialize
	new livy_acf_field_zelda( $this->settings );


// class_exists check
endif;

?>