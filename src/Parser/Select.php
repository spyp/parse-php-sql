<?php
/**
 * Created by PhpStorm.
 * User: sharif
 * Date: 10/18/2015
 * Time: 11:16 PM
 */
namespace PHPParser\Parser;

use PHPParser\Tools\String;
use PHPParser\Parser\Object;
class Select extends Object {

    /**
     * get column to select
     */
    public function getColumns ()
    {
        // before this we have to disable functions
        $sql =$this->functions(false);
        $columns = trim(String::getBetween ($sql, 'SELECT', 'FROM'));
        if ($columns && $columns != '' && strlen ($columns) > 0)
        {


            $fields = explode (',', $columns);
            $data = array();
            // explore multiple columns
            foreach ($fields as $column)
            {
                $column = trim ($column);
                if (strpos ($column, 'AS'))
                {
                    $result = explode ('AS', $column);
                    $data[] = array('column' => trim (trim ($result[0]), '`'), 'alias' => trim (trim ($result[1]), '`'));
                }
                elseif (count ($column_saperate = explode (' ', $column)) > 1)
                {
                    $reverse_column = array_reverse ($column_saperate);
                    $data[] = array('column' => trim (trim ($column_saperate[0]), '`'), 'alias' => $reverse_column[0]);

                }
                else
                {
                    $data[] = array('column' => trim ($column, '`'), 'alias' => '');
                }

            }

            return $data;

        }
        else
        return array();
    }
}