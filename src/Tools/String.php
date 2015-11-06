<?php
/**
 * Created by PhpStorm.
 * User: sharif
 * Date: 10/22/2015
 * Time: 1:53 PM
 */
namespace PHPParser\Tools;

class String{

    /**
     * get string between 2 string
     *
     * @param $string    string
     * @param $first     ,first position of string
     * @param $last      ,last position of string
     * @param $get_first , contains first chars in string
     * @param $get_last  , contains last chars in string
     *
     * @return string
     */
    public static function getBetween($string,$first,$last,$get_first = false,$get_last = false)
    {

        $first_position = strpos ($string,$first);
        if (!$get_first)
        {
            $first_position += strlen ($first);
        }
        $last_position = strpos ($string,$last);
        if ($get_last)
        {
            $last_position += 1;
        }
        $length = abs ($first_position - $last_position);

        return trim (substr ($string,$first_position,$length));
    }

    /**
     * @param $string
     * @param $first       ,last position to remove
     * @param $last        ,last position to remove
     * @param $remove_last , remove last chars or not default true
     *
     * @return string,clean string
     */
    public static function removeSubStr($string,$first,$last,$remove_first = true,$remove_last = true)
    {

        $remove_string = self::getBetween ($string,$first,$last,$remove_first,$remove_last);
        if ($remove_string)
        {
            return str_replace ($remove_string,"",$string);
        }
        else
        {
            return $string;
        }
    }

    /**
     * find all position of needle string in string
     * @param  $string
     * @param $needle
     * @param $lastPos, last position to start finding
     * @param $getAll,bool getAll position of needle string or just first position
     * @return array,position of needle string
     */
    public static function findPositions($string,$needle,$lastPos=0,$getAll=true)
    {
        $positions = array();

        while (($lastPos = strpos($string, $needle, $lastPos))!== false)
        {
            $positions[] = $lastPos;
            $lastPos = $lastPos + strlen($needle);
            if(!$getAll)
                return $positions;
        }
        return $positions;

    }
}