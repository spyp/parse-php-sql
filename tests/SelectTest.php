<?php
/**
 * Created by PhpStorm.
 * User: sharif
 * Date: 10/26/2015
 * Time: 7:09 PM
 */
namespace PHPParser\Test;

use PHPUnit_Framework_TestCase;
use PHPParser\Parser;

class SelectTest extends PHPUnit_Framework_TestCase{

    protected $parser;

    public function setUp()
    {

        $this->parser = new Parser();
    }

    public function tearDown()
    {

        unset($this->parser);
    }

    /**
     * @dataProvider sqlQueries
     *
     * @param $sql
     * @param $expected
     */
    public function testColumns($sql,$expected)
    {

        $this->parser->parse ($sql);
        $actual = $this->parser->ParseObject->getColumns ();
        $this->assertEquals ($expected,$actual);
    }

    public function testGetFunctions()
    {

        $sql = 'SELECT IFNULL(ABS(username),1,0) from test';
        $this->parser->parse ($sql);
        $expected = 'SELECT  FROM test ';
        $actual = $this->parser->ParseObject->functions (false,$sql);
        $this->assertEquals ($expected,$actual);
    }

    /**
     * @dataProvider sqlJoinsQuery
     */
    public function testJoins($sql,$expected)
    {

        $this->parser->parse ($sql);
        $actual = $this->parser->ParseObject->joins ();
        $this->assertEquals ($expected,$actual);
    }


    // providers
    public function sqlQueries()
    {

        return array(
            array('sql1' => 'SELECT * FROM test','expected1' => array(array('column' => '*','alias' => ''))),
            array('sql1' => 'select * FROM test','expected1' => array(array('column' => '*','alias' => ''))),
            array('sql2' => 'SELECT `name` FROM test','expected2' => array(array('column' => 'name','alias' => ''))),
            array('sql3' => 'SELECT `name` AS me FROM test','expected3' => array(array('column' => 'name','alias' => 'me'))),
            array('sql4' => 'SELECT `name` AS me,`email`,`firstname` AS first_name FROM test','expected4' => array(array('column' => 'name','alias' => 'me'),array('column' => 'email','alias' => ''),array('column' => 'firstname','alias' => 'first_name'))),
            array('sql5' => 'select `name` AS me,`email`,`firstname` AS first_name from test','expected4' => array(array('column' => 'name','alias' => 'me'),array('column' => 'email','alias' => ''),array('column' => 'firstname','alias' => 'first_name'))),
            array('sql6' => 'select `name`  me,`email`,`firstname` AS first_name from test','expected4' => array(array('column' => 'name','alias' => 'me'),array('column' => 'email','alias' => ''),array('column' => 'firstname','alias' => 'first_name'))),
            array('sql7' => 'select first_name.`name` as  me,`email`,`firstname` AS first_name from test','expected4' => array(array('column' => 'firstname.`name`','alias' => 'me'),array('column' => 'email','alias' => ''),array('column' => 'firstname','alias' => 'first_name'))),
            array('sql8' => 'select `name` as  me,`email`,`firstname` AS first_name,IFNULL(lastname,1,0) sharif from test','expected4' => array(
                array('column' => 'name','alias' => 'me'),
                array('column' => 'email','alias' => ''),
                array('column' => 'firstname','alias' => 'first_name'),
                array('column' => 'IFNULL(lastname,1,0)','alias' => 'sharif'))),
        );
    }

    public function sqlJoinsQuery()
    {

        return array(
            array('joins1' => 'SELECT * from ps_table left join ps_table2 as sharif ON ps_table.id=sharif.id','expected' => array(array('table' => 'ps_table2','alias' => 'sharif'))),
            array('joins2' => 'SELECT * from ps_table left join ps_table2  sharif ON ps_table.id=sharif.id','expected' => array(array('table' => 'ps_table2','alias' => 'sharif'))),
            array('joins3' => 'SELECT * from ps_table left join ps_table2  ON ps_table.id=sharif.id','expected' => array(array('table' => 'ps_table2','alias' => ''))),
        );
    }
}