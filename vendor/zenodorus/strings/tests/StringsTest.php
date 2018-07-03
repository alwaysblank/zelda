<?php namespace Zenodorus;

use \PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
    /**
     * @Strings::safe()
     */

    public function testSafeGood()
    {
        $good_string = 'agoodstring';
        $this->assertEquals($good_string, Strings::safe($good_string));
    }

    public function testSafeBad()
    {
        $bad_string = 'a bad string @';
        $bad_string2 = '$$%@lkamns';
        $this->assertFalse(Strings::safe($bad_string));
        $this->assertFalse(Strings::safe($bad_string2));
    }

    /**
     * @Strings::clean()
     */

    public function testCleanCharacters()
    {
        $before_string = 'the is_the string!';
        $after_string = 'theis_thestring';
        $this->assertEquals($after_string, Strings::clean($before_string));
    }

    public function testCleanReplacement()
    {
        $before_string = 'the is_the string!';
        $after_string = 'the-is_the-string-';
        $this->assertEquals($after_string, Strings::clean($before_string, '-'));
    }

    public function testCleanNoReplacement()
    {
        $before_string = 'this_string_is_already_okay';
        $after_string = 'this_string_is_already_okay';
        $this->assertEquals($after_string, Strings::clean($before_string, '-'));
    }

    /**
     * @Strings::replaceFirst()
     */

    public function testReplaceFirstSingle()
    {
        $search_for = 'this';
        $search_in = 'this is a line full of this';
        $replace_with = 'that';
        $expected = 'that is a line full of this';
        $this->assertEquals($expected, Strings::replaceFirst($search_for, $replace_with, $search_in));
    }

    public function testReplaceFirstPosition()
    {
        $search_for = 'line';
        $search_in = 'this is a line full of line';
        $replace_with = 'box';
        $expected = 'this is a box full of line';
        $this->assertEquals($expected, Strings::replaceFirst($search_for, $replace_with, $search_in));
    }

    /**
     * @Strings::addNew()
     */

    public function testAddNewReplace()
    {
        $add = 'world';
        $existing = 'hello';
        $result = 'hello world';
        $this->assertEquals($result, Strings::addNew($add, $existing));
    }

    public function testAddNewConcatenate()
    {
        $add = 'world';
        $existing = 'hello';
        $concatenate = ' cruel ';
        $result = 'hello cruel world';
        $this->assertEquals($result, Strings::addNew($add, $existing, $concatenate));
    }

    public function testAddNewExists()
    {
        $add = 'world';
        $existing = 'hello world';
        $result = 'hello world';
        $this->assertEquals($result, Strings::addNew($add, $existing));
    }

    public function testAddNewExistsWithConcatenate()
    {
        $add = 'world';
        $existing = 'hello cruel world';
        $concatenate = ' cruel ';
        $result = 'hello cruel world';
        $this->assertEquals($result, Strings::addNew($add, $existing, $concatenate));
    }
}
