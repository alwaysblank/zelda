<?php

namespace Livy\Zelda;


class Process {
	/**
	 * Get the field class (if any) from the field definition.
	 *
	 * @param $field
	 *
	 * @return string
	 */
	public static function getFieldClass( $field ) {
		return isset( $field['link_class'] )
			? trim( $field['link_class'] )
			: null;
	}

	/**
	 * Get the user class (if any) from the field value.
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public static function getUserClass( $value ) {
		return isset( $value['class'] )
			? trim( $value['class'] )
			: null;
	}

	/**
	 * Get user text, if defined.
	 *
	 * Falls back to default text if no text is set.
	 *
	 * @param $value array
	 *
	 * @return string
	 */
	public static function getUserContent( $value ) {
		return isset( $value['text'] )
			? trim( $value['text'] )
			: Settings::get( 'defaults' )['default_text'];
	}

	/**
	 * Get the computed destination. Will return `null` without a valid type.
	 *
	 * @param string $destination_raw
	 * @param $type string
	 *
	 * @return string|null
	 */
	public static function getUserDestination( $destination_raw, $type = 'no-type' ) {
		switch ( $type ) {
			case 'content' :
				return Process::getDestinationTypeContent( $destination_raw );
				break;
			case 'taxonomy':
				return Process::getDestinationTypeTaxonomy( $destination_raw );
				break;
			case 'email':
				return Process::getDestinationTypeEmail( $destination_raw );
				break;
			case 'external' :
				return Process::getDestinationTypeExternal( $destination_raw );
				break;
		}

		return null;
	}

	/**
	 * Get the single type for the destination.
	 *
	 * @param $type_full
	 *
	 * @return string
	 */
	public static function getDestinationType( $type_full ) {
		$extract = explode( '/', $type_full );

		return isset( $extract[0] )
			? $extract[0]
			: 'no-type';
	}

	/**
	 * Get (& validate) external destination.
	 *
	 * @param $retrieved_destination string
	 *
	 * @return null|string
	 */
	public static function getDestinationTypeExternal( $retrieved_destination ) {
		if ( filter_var( $retrieved_destination, FILTER_VALIDATE_URL ) ) {
			return $retrieved_destination;
		}

		return null;
	}

	/**
	 * Get (& validate) email destination.
	 *
	 * @param $retrieved_destination string
	 *
	 * @return null|string
	 */
	public static function getDestinationTypeEmail( $retrieved_destination ) {
		if ( is_string( $retrieved_destination ) ) {
			$addresses         = explode( ',', $retrieved_destination );
			$checked_addresses = array_reduce( $addresses, function ( $carry, $email ) {
				$valid_email = filter_var( $email, FILTER_VALIDATE_EMAIL );
				if ( $valid_email ) {
					return null === $carry
						? $valid_email
						: "{$carry},{$valid_email}";
				}

				/** Bad email, just keep going. */
				return $carry;
			}, null );

			return "mailto:{$checked_addresses}";
		}

		return null;
	}

	/**
	 * Get (& validate) taxonomy destination.
	 *
	 * @param $retrieved_destination string
	 *
	 * @return null|string
	 */
	public static function getDestinationTypeTaxonomy( $retrieved_destination ) {
		if ( is_numeric( $retrieved_destination ) ) {
			$destination = get_term_link( intval( $retrieved_destination ) );

			return is_wp_error( $destination )
				? null
				: $destination;
		}

		return null;
	}

	/**
	 * Get (& validate) content destination.
	 *
	 * @param $retrieved_destination string
	 *
	 * @return null|string
	 */
	public static function getDestinationTypeContent( $retrieved_destination ) {
		if ( strpos( $retrieved_destination, '_archive' ) > 0 ) {
			$archive_name = explode( '_', $retrieved_destination );

			return get_post_type_archive_link( $archive_name[0] ) ?? null;
		} elseif ( is_numeric( $retrieved_destination ) ) {
			return get_permalink( intval( $retrieved_destination ) ) ?? null;
		}

		return null;
	}

	/**
	 * Does this field want you to open it in a new tab?
	 *
	 * @param $field
	 *
	 * @return bool
	 */
	public static function getForceNewTab( $field ) {
		return isset( $field['new_tab'] ) && $field['new_tab'];
	}
}