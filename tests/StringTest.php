<?php
/**
 * Created by PhpStorm.
 * User: sharif
 * Date: 10/22/2015
 * Time: 3:33 PM
 */

namespace PHPParser\Test;
use PHPUnit_Framework_TestCase;
use PHPParser\Tools\String;
class StringTest extends PHPUnit_Framework_TestCase
{
    public function tesGetBetween()
    {
        $query = 'SELECT * FROM test';
        $expected = "*";
        $actual = String::getBetween($query,'SELECT','FROM');
        $this->assertEquals($expected,$actual);
    }

}