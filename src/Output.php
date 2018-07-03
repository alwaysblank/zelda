<?php

namespace Livy\Zelda;


use Zenodorus\Strings;

class Output {

	private $class;
	private $content;
	private $destination;
	private $attributes;

	public function __construct( $value, $field, $post_id ) {
		$startup_data = [ 'value' => $value, 'field' => $field, 'post_id' => $post_id ];

		$this->class = $this->makeClass( $startup_data );

		$this->content = $this->makeContent( $startup_data );

		$this->destination = $this->makeDestination( $startup_data );

		$this->attributes = $this->makeAttributes( $startup_data );
	}

	/**
	 * Simple factory for Output.
	 *
	 * @param $value
	 * @param $field
	 * @param $post_id
	 *
	 * @return Output
	 */
	public static function make( $value, $field, $post_id ) {
		return new self( $value, $field, $post_id );
	}

	/**
	 * Get, compile, and clean all classes (field & user) for the field.
	 *
	 * @param $data array
	 *
	 * @return string
	 */
	private function makeClass( $data ) {
		$class          = [];
		$class['field'] = Process::getFieldClass( $data['field'] );
		$class['user']  = Process::getUserClass( $data['value'] );

		$compiled = $this->aggressiveTrim( join( ' ', apply_filters( 'zelda/output/class', $class, $data ) ) );

		return $this->cleanUp( $compiled );
	}

	/**
	 * Get content for this link.
	 *
	 * @param $data array
	 *
	 * @return string
	 */
	private function makeContent( $data ) {
		return apply_filters( 'zelda/output/content', Process::getUserContent( $data['value'] ), $data );
	}

	/**
	 * Get the destination for this link.
	 *
	 * @param $data array
	 *
	 * @return string
	 */
	private function makeDestination( $data ) {
		$destination = Process::getUserDestination(
			apply_filters( 'zelda/output/destination/value', $data['value']['value'], $data ),
			apply_filters( 'zelda/output/destination/type', Process::getDestinationType( $data['value']['type'] ), $data )
		);

		return apply_filters( 'zelda/output/destination/', $destination );
	}

	/**
	 * Collect, compile, and process the attributes for this link.
	 *
	 * @param $data array
	 *
	 * @return string
	 */
	private function makeAttributes( $data ) {
		$attributes = [];

		$new_tab = apply_filters( 'zelda/output/new-tab', Process::getForceNewTab( $data['field'] ), $data );
		if ( $new_tab ) {
			$attributes[] = [ 'target', '_blank' ];
			$attributes[] = [ 'rel', 'noopener noreferrer' ];
		}

		$compiled = array_reduce(
			apply_filters( 'zelda/output/attributes', $attributes, $data ),
			function ( $carry, $current ) {
				if ( isset( $current[1] ) ) {
					$carry .= sprintf(
						'%s="%s" ',
						Strings::clean( $current[0], '', '/[^\w_-]/u' ),
						esc_attr( $current[1] )
					);
				} elseif ( isset( $current[0] ) ) {
					$carry .= sprintf(
						'%s ',
						Strings::clean( $current[0], '', '/[^\w_-]/u' )
					);
				}

				return $carry;
			},
			null
		);

		return trim( $compiled );
	}

	/**
	 * Aggressively trim out multiple spaces.
	 *
	 * @param $string
	 *
	 * @return string
	 */
	private function aggressiveTrim( $string ) {
		/**
		 * Remove any instance of duplicate spaces.
		 */
		$spaces_removed = preg_replace(
			'/(\s{2,})/m',
			' ',
			$string
		);
		$trimmed        = trim( $spaces_removed );

		return $trimmed;
	}

	/**
	 * Clean things for HTML attributes (i.e. for `class`).
	 *
	 * @param $string string
	 *
	 * @return string
	 */
	private function cleanUp( $string ) {
		return trim( Strings::clean( $string, "-", '/[^\w\s_-]/u' ) );
	}

	private function getProp( $prop ) {
		if ( property_exists( $this, $prop ) ) {
			return $this->{$prop};
		}

		return null;
	}


	private function canDisplayLink() {
		return ( null !== $this->getDestination() )
		       && ( null !== $this->getContent() );
	}

	public function getClass() {
		return $this->getProp( 'class' );
	}

	public function getContent() {
		return $this->getProp( 'content' );
	}

	public function getAttributes() {
		return $this->getProp( 'attributes' );
	}

	public function getDestination() {
		return $this->getProp( 'destination' );
	}

	/**
	 * Get the template used for link generation.
	 *
	 * Implements the `zelda/output/template` filter, allowing you to filter/modify the template used on the fly.
	 *
	 * @return string
	 */
	public function template() {
		return apply_filters(
			'zelda/output/template',
			'<a href="%s" class="%s" %s>%s</a>',
			$this
		);
	}

	/**
	 * Get the HTML link element.
	 *
	 * @return string
	 */
	public function element() {
		if ( $this->canDisplayLink() ) {
			return apply_filters(
				'zelda/output/element',
				sprintf(
					$this->template(),
					$this->getDestination(),
					$this->getClass(),
					$this->getAttributes(),
					$this->getContent()
				),
				$this );
		}

		return null;
	}
}