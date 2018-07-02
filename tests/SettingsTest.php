<?php
declare( strict_types=1 );
include 'mock.php';

use Livy\Zelda\Settings;
use PHPUnit\Framework\TestCase;

final class SettingsTest extends TestCase {
	public function testTheseSettingsShouldBeStrings() {
		$this->assertInternalType( 'string', Settings::get( 'name' ), '`name` is not a string.' );
		$this->assertInternalType( 'string', Settings::get( 'label' ), '`label` is not a string.' );
		$this->assertInternalType( 'string', Settings::get( 'category' ), '`category` is not a string.' );
	}

	public function testTheseSettingsShouldBeArrays() {
		$this->assertInternalType( 'array', Settings::get( 'defaults' ), '`defaults` is not an array.' );
		$this->assertInternalType( 'array', Settings::get( 'l10n' ), '`l10n` is not an array.' );
	}

	public function testReturnFalseForNonexistantSettings() {
		$this->assertFalse( Settings::get( 'polarity' ), 'Returned non-falsy value for non-existant setting.' );
		$this->assertTrue( (boolean) Settings::get( 'name' ), 'Returned non-truthy value for existing setting.' );
	}
}