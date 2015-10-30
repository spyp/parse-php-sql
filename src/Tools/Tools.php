<?php
/**
 * Created by PhpStorm.
 * User: sharif
 * Date: 10/29/2015
 * Time: 4:48 PM
 */
namespace PHPParser\Tools;

class Tools {

    /**
     * sort Multi dimensional  array
     * @return array
     */
    public static function sort ()
    {
        $args = func_get_args ();
        $data = array_shift ($args);
        foreach ($args as $n => $field)
        {
            if (is_string ($field))
            {
                $tmp = array();
                foreach ($data as $key => $row)
                {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array ('array_multisort', $args);

        return array_pop ($args);
    }
}