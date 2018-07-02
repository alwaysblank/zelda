<?php

use Livy\Zelda\{
	Settings
};
use Zenodorus\Arrays;

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// check if class already exists
if ( ! class_exists( 'livy_acf_field_zelda' ) ) :


	class livy_acf_field_zelda extends acf_field {

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

			/**
			 * Apply field settings and defaults.
			 */
			Settings::apply( $this );

			/**
			 * Set settings passed to this object.
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
				'name'         => 'new_tab',
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
			$stored       = $field['value'] ?? null;
			$stored_type  = $stored['type'] ?? false;
			$stored_value = $stored['value'] ?? false;
			$stored_class = $stored['class'] ?? false;
			$stored_text  = $stored['text'] ?? false;

			/**
			 * Generate a list of possible link types.
			 */
			$type_options = array();

			if ( $field['post_type'] && is_array( $field['post_type'] ) ) {
				$type_options['content'] = array(
					'label'   => "Content",
					'slug'    => 'content',
					'options' => Arrays::mapKeys( function ( $key, $value ) {
						$post_type = get_post_type_object( $key );

						return array( $key, $post_type->labels->name );
					}, $field['post_type'] )
				);
			}

			if ( $field['taxonomy'] && is_array( $field['taxonomy'] ) ) {
				$type_options['taxonomies'] = array(
					'label'   => "Taxonomies",
					'slug'    => 'taxonomy',
					'options' => Arrays::mapKeys( function ( $key, $value ) {
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

			/**
			 * Generate some input fields.
			 */

			/**
			 * Select type of link
			 */
			if ( is_array( $type_options ) && count( $type_options ) > 0 ) {
				?>
                <div class="acf-field-zelda__typeSelect">
                    <label for="<?php echo esc_attr( $field['name'] ) ?>[type]">Type</label>
                    <select name="<?php echo esc_attr( $field['name'] ) ?>[type]">
						<?php foreach ( $type_options as $option => $label ) {
							if ( is_array( $label ) ) {
								printf(
									'<optgroup label="%s">%s</optgroup>',
									$label['label'],
									join( '', Arrays::mapKeys( function ( $value, $key ) use ( $stored_type, $label ) {
										return [
											sprintf(
												'<option value="%s/%s" %s>%s</option>',
												$label['slug'],
												$key,
												$label['slug'] . '/' . $key == $stored_type ? 'selected' : null,
												$value
											)
										];
									}, $label['options'] )
									) );
							} elseif ( is_string( $label ) ) {
								printf(
									'<option value="%s" %s>%s</option>',
									$option,
									$option == $stored_type ? 'selected' : null,
									$label
								);
							}
						} ?>
                    </select>
                </div>
				<?php
			}

			/**
			 * Select the content, if there is content
			 */
			?>
            <fieldset class="acf-field-zelda__contentSelect">
				<?php if ( isset( $type_options['content'] )
				           && is_array( $type_options['content'] )
				           && is_array( $type_options['content']['options'] ) ) { ?>
                    <div class="acf-field-zelda__postTypes .acf-field-zelda__contentWrap" data-zelda-type="content">
						<?php foreach ( $type_options['content']['options'] as $key => $label ) { ?>
                            <div class="acf-field-zelda__postType acf-field-zelda__fieldWrap"
                                 data-zelda-type="<?php echo esc_attr( $key ) ?>" hidden>
                                <label for="<?php echo esc_attr( $field['name'] ) ?>[content][<?php echo esc_attr( $key ) ?>]">
									<?php echo $label ?>
                                </label>
                                <select name="<?php echo esc_attr( $field['name'] ) ?>[content][<?php echo esc_attr( $key ) ?>]"
                                        data-zelda-field>
                                    <option value="placeholder"></option>
									<?php if ( $field['post_type_archive'] && get_post_type_archive_link( $key ) ) {
										printf(
											'<option value="%s" %s>Archive</option>',
											$key . '_archive',
											$stored_value == $key . '_archive' ? 'selected' : null
										);
									} ?>

									<?php $this_type = get_posts( array( 'post_type' => $key ) );
									if ( $this_type && count( $this_type ) > 0
									) {
										if ( $field['post_type_archive'] && get_post_type_archive_link( $key ) ) {
											echo '<option disabled>──────────</option>';
										}
										foreach ( $this_type as $post ) {
											printf( '<option value="%s" %s>%s</option>',
												$post->ID,
												intval( $stored_value ) == $post->ID ? 'selected' : null,
												$post->post_title
											);
										}
										// Can't be too careful
										unset( $this_type );
									} ?>
                                </select>
                            </div>
						<?php } ?>
                    </div>
				<?php }

				/**
				 * Select taxonomies, if there are taxonomies
				 */
				if ( isset( $type_options['taxonomies'] )
				     && is_array( $type_options['taxonomies'] )
				     && is_array( $type_options['taxonomies']['options'] ) ) { ?>
                    <div class="acf-field-zelda__taxonomies .acf-field-zelda__contentWrap" data-zelda-type="taxonomy">
						<?php foreach ( $type_options['taxonomies']['options'] as $key => $label ) {
							?>
                            <div class="acf-field-zelda__taxonomy acf-field-zelda__fieldWrap"
                                 data-zelda-type="<?php echo esc_attr( $key ) ?>" hidden>
                                <label for="<?php echo esc_attr( $field['name'] ) ?>[taxonomy][<?php echo esc_attr( $key ) ?>]">
									<?php echo $label ?>
                                </label>
                                <select name="<?php echo esc_attr( $field['name'] ) ?>[taxonomy][<?php echo esc_attr( $key ) ?>]"
                                        data-zelda-field>
                                    <option value="placeholder"></option>
									<?php $this_taxonomy = get_terms( array( 'taxonomy' => $key ) );
									if ( $this_taxonomy && count( $this_taxonomy ) > 0
									) {
										foreach ( $this_taxonomy as $taxonomy ) {
											/** @var $taxonomy \WP_Term */
											printf( '<option value="%s" %s>%s</option>',
												$taxonomy->term_taxonomy_id,
												intval( $stored_value ) == $taxonomy->term_taxonomy_id ? 'selected' : null,
												$taxonomy->name
											);
										}
										// Can't be too careful
										unset( $this_taxonomy );
									} ?>
                                </select>
                            </div>
							<?php
						} ?>
                    </div>
					<?php
				}

				/*
				*  Email, if email is set
				*/
				if ( $type_options['email'] && is_string( $type_options['email'] ) ) {
					?>
                    <div class="acf-field-zelda__email acf-field-zelda__fieldWrap" data-zelda-type="email" hidden>
                        <label for="<?php echo esc_attr( $field['name'] ) ?>[email]"><?php echo $type_options['email']
							?></label>
                        <input data-zelda-field type="email" name="<?php echo esc_attr( $field['name'] ) ?>[email]"
                               value="<?php echo 'email' === $stored_type ? $stored_value : null; ?>"/>
                    </div>
					<?php
				}

				/*
				*  External, if external is set
				*/
				if ( $type_options['external'] && is_string( $type_options['external'] ) ) {
					?>
                    <div class="acf-field-zelda__external acf-field-zelda__fieldWrap" data-zelda-type="external" hidden>
                        <label for="<?php echo esc_attr( $field['name'] ) ?>[external]"><?php echo $type_options['external']
							?></label>
                        <input data-zelda-field type="url" name="<?php echo esc_attr( $field['name'] ) ?>[external]"
                               value="<?php echo 'external' === $stored_type ? $stored_value : null; ?>"/>
                    </div>
					<?php
				} ?>
            </fieldset>
			<?php
			/**
			 * Set user class
			 */
			if ( $field['user_class'] ) {
				?>
                <div class="acf-field-zelda__userClass">
                    <label for="<?php echo esc_attr( $field['name'] ) ?>[user_class]">Class</label>
                    <input data-zelda-field type="text" name="<?php echo esc_attr( $field['name'] ) ?>[user_class]"
                           value="<?php echo $stored_class; ?>">
                </div>
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
                <div class="acf-field-zelda__userText">
                    <label for="<?php echo esc_attr( $field['name'] ) ?>[user_text]">Text</label>
                    <input data-zelda-field type="text" name="<?php echo esc_attr( $field['name'] ) ?>[user_text]"
                           value="<?php echo $stored_text ?>">
                </div>
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

		function input_admin_enqueue_scripts() {

			// vars
			$url     = $this->settings['url'];
			$version = $this->settings['version'];


			// register & include JS
			wp_register_script( 'acf-zelda', "{$url}assets/js/input.js", array( 'acf-input' ), $version );
			wp_enqueue_script( 'acf-zelda' );


			// register & include CSS
			wp_register_style( 'acf-zelda', "{$url}assets/css/input.css", array( 'acf-input' ), $version );
			wp_enqueue_style( 'acf-zelda' );

		}


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

		/**
		 * Get the value for the data type.
		 *
		 * @param $type (string)
		 * @param $data (array)
		 *
		 * @return bool|mixed
		 */
		function get_type_value_from_form( $type, $data ) {
			if ( ! is_string( $type ) || ! is_array( $data ) ) {
				return false;
			}

			$directions = explode( '/', $type );

			$return = Arrays::pluck( $data, $directions, true );

			return null !== $return ? $return : false;
		}


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
			$destination = $this->get_type_value_from_form( $value['type'], $value );

			return array(
				'type'  => $value['type'],
				'value' => $destination,
				'class' => $value['user_class'] ?? false,
				'text'  => $value['user_text'] ?? false,
			);
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
		function format_value( $value, $post_id, $field ) {

			// bail early if no value
			if ( empty( $value ) ) {

				return $value;

			}

			$type = explode( '/', $value['type'] );

			$destination_raw = $value['value'];
			$destination     = false;
			switch ( $type[0] ) {
				case 'content' :
					if ( strpos( $destination_raw, '_archive' ) > 0 ) {
						$archive_name = explode( '_', $destination_raw );
						$destination  = get_post_type_archive_link( $archive_name[0] );
					} elseif ( is_numeric( $destination_raw ) ) {
						$destination = get_permalink( intval( $destination_raw ) );
					}
					break;
				case 'taxonomy':
					if ( is_numeric( $destination_raw ) ) {
						$destination = get_term_link( intval( $destination_raw ) );
					}
					break;
				case 'email':
					$destination = "mailto:{$destination_raw}";
					break;
				case 'external' :
					$destination = $destination_raw;
					break;
			}

			if ( $destination ) {

				$class = trim( $value['class']
					? esc_attr( $field['link_class'] . ' ' . $value['class'] )
					: esc_attr( $field['link_class'] ) );

				$target = $field['new_tab']
					? 'target="_blank" rel="noopener noreferrer"'
					: null;

				return sprintf(
					'<a href="%s" class="%s" %s>%s</a>',
					esc_attr( $destination ),
					esc_attr( $class ),
					$target,
					$value['text']
				);
			}


			// Couldn't get a valid link
			return null;
		}


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

		function validate_value( $valid, $value, $field, $input ) {

			$valid_number = function ( $possible_number ) {
				return is_numeric( $possible_number );
			};

			$valid_archive = function ( $possible_archive ) {
				$archive_raw = explode( '_', $possible_archive );
				if ( count( $archive_raw ) >= 2 ) {
					if ( get_post_type_archive_link( $archive_raw[0] ) ) {
						return true;
					}
				}

				return false;
			};

			/**
			 * Assumes we've already checked that $possible_post is a number.
			 *
			 * @param $possible_post
			 *
			 * @return bool
			 */
			$valid_post = function ( $possible_post ) {
				if ( get_permalink( intval( $possible_post ) ) ) {
					return true;
				}

				return false;
			};

			/**
			 * Assumes we've already checked that $possible_taxonomy is a number.
			 *
			 * @param $possible_taxonomy
			 *
			 * @return bool
			 */
			$valid_taxonomy = function ( $possible_taxonomy ) {
				if ( get_term( intval( $possible_taxonomy ) ) ) {
					return true;
				}

				return false;
			};

			if ( ! empty( $value['user_class'] ) ) {
				if ( 1 !== preg_match( '/^[a-zA-Z_\-0-9 ]*$/m', $value['user_class'] ) ) {
					return __( 'Enter valid class names.', 'acf-zelda' );
				}
			}

			if ( ! empty( $value['user_text'] ) ) {
				if ( wp_kses_post( $value['user_text'] ) !== $value['user_text'] ) {
					return __( 'Enter valid link text.', 'acf-zelda' );
				}
			}

			$type        = explode( '/', $value['type'] );
			$destination = Arrays::pluck( $value, $type );

			switch ( $type[0] ) {
				case 'content':
					if ( $valid_archive( $destination ) ) {
						return true;
					} elseif ( $valid_number( $destination ) && $valid_post( $destination ) ) {
						return true;
					}

					return __( 'Enter a valid content item.', 'acf-zelda' );
					break;

				case 'taxonomy':
					if ( $valid_number( $destination ) && $valid_taxonomy( $destination ) ) {
						return true;
					}

					return __( 'Enter a valid taxonomy.', 'acf-zelda' );
					break;

				case 'email':
					$emails = explode( ',', $destination );
					foreach ( $emails as $email ) {
						if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
							return sprintf( __( 'This email address is invalid: %s', 'my-text-domain' ), esc_html( $email ) );
						}
					}

					return true;
					break;

				case 'external':
					if ( filter_var( $destination, FILTER_VALIDATE_URL ) ) {
						return true;
					}

					return __( 'Enter a valid URL.', 'acf-zelda' );
					break;

				default:
					return false;
					break;
			}
		}


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