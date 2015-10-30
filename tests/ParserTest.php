<?php
/**
 * Created by PhpStorm.
 * User: sharif
 * Date: 10/26/2015
 * Time: 6:54 PM
 */

namespace PHPParser\Test;
use PHPUnit_Framework_TestCase;
use PHPParser\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        // test select
        $sql = 'SELECT * FROM test';
        $parser = new Parser($sql);
        $expected = 'select';
        $actual = $parser->ParseObject->type;
        $this->assertEquals($expected,$actual);
    }
}