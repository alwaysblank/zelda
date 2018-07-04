<?php
declare( strict_types=1 );
include 'mock.php';

use Livy\Zelda\Output;
use PHPUnit\Framework\TestCase;

final class OutputTest extends TestCase {
	protected $value;
	protected $field;
	protected $post_id;
	protected $output;

	public function setUp() {
		$this->value   = [
			'type'  => 'content/post',
			'value' => '33',
			'class' => 'user-class',
			'text'  => 'Click Me',
		];
		$this->field   = [
			'link_class' => 'field-class',
			'new_tab'    => 1,
		];
		$this->post_id = 1;

		$this->output = Output::make( $this->value, $this->field, $this->post_id );
	}

	public function testGetCorrectLinkClass() {
		$this->assertEquals( "{$this->field['link_class']} {$this->value['class']}", $this->output->getClass() );
	}

	public function testGetCorrectLinkContent() {
		$this->assertEquals( $this->value['text'], $this->output->getContent() );
	}

	public function testGetCorrectLinkAttributes() {
		$this->assertEquals( 'target="_blank" rel="noopener noreferrer"', $this->output->getAttributes() );
	}

	public function testGetContentTypeDestination() {
		$this->assertEquals( '/link/to/permalink', $this->output->getDestination() );
	}

	public function testGetContentTypeArchiveDestination() {
		$archive_type          = $this->value;
		$archive_type['value'] = 'post_archive';
		$this->assertEquals( '/link/to/archive', Output::make( $archive_type, $this->field, $this->post_id )->getDestination() );
	}

	public function testGetTaxonomyTypeDestination() {
		$taxonomy_type          = $this->value;
		$taxonomy_type['type']  = 'taxonomy/category';
		$taxonomy_type['value'] = '22';
		$this->assertEquals( '/link/to/term', Output::make( $taxonomy_type, $this->field, $this->post_id )->getDestination() );
	}

	public function testGetEmailTypeDestination() {
		$email_type          = $this->value;
		$email_type['type']  = 'email';
		$email_type['value'] = 'user@email.com';
		$this->assertEquals( "mailto:{$email_type['value']}", Output::make( $email_type, $this->field, $this->post_id )->getDestination(), "Does not return the correct email destination." );

		$email_type['value'] = 'notanemail';
		$this->assertEquals( "mailto:", Output::make( $email_type, $this->field, $this->post_id )->getDestination(), "Allows bad email addresses through." );
	}

	public function testGetExternalTypeDestination() {
		$external_type          = $this->value;
		$external_type['type']  = 'external';
		$external_type['value'] = 'http://web.site';
		$this->assertEquals( $external_type['value'], Output::make( $external_type, $this->field, $this->post_id )->getDestination(), "Does not return normal url." );
		$external_type['value'] = 'web.site';
		$this->assertEquals( null, Output::make( $external_type, $this->field, $this->post_id )->getDestination(), "Returns invalid urls." );
	}
}