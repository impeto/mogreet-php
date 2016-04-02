<?php

namespace Mogreet;

class Utils
{

    public static function toCamelCase($str) 
    {
        return preg_replace_callback( '/_([a-z])/', function( $matches){
            return strtoupper( $matches[1]);
        }, $str);

        //return preg_replace('/_([a-z])/e', "strtoupper('\\1')", $str);
    }

    public static function fromCamelCase($str)
    {
        $str = preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", $str);
        return strtolower($str);
    }


    public static function is_assoc($var)
    {
        return is_array($var) && array_diff_key($var,array_keys(array_keys($var)));
    }

    // json_decode is AWESOME, but we need to respect PHP style conventions!
    public static function json_to_object($in)
    {
        $is_arr = is_array($in);
        if ($is_arr && static::is_assoc($in)) {
            $obj = new \stdClass();
            foreach ($in as $k => $v) {
                $k = static::toCamelCase($k);
                $obj->$k = static::json_to_object($v);
            }
        } else if ($is_arr) {
            $obj = array();
            foreach ($in as $k => $v) {
                $k = static::toCamelCase($k);
                $obj[$k] = static::json_to_object($v);
            }
        } else {
            $obj = $in;
        }
        return $obj;
    }
}
