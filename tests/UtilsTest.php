<?php


use Mogreet\Utils;

class UtilsTest extends PHPUnit_Framework_TestCase
{
    public function testCamelCase()
    {
        $str = "some_snake_case_string";

        $this->assertEquals(
            "someSnakeCaseString",
            Utils::toCamelCase( $str)
        );
    }
}