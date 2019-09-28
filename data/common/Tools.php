<?php

namespace common;

class Tools
{


    /**
     * @Description
     *      Translates a string with underscores into camel case (e.g. first_name -> firstName)
     *
     * @param string $str
     * @param bool $catapitalise_first_char
     * @return string
     */
    public static function toCamelCase(string $str, bool $catapitalise_first_char = false) : string
    {
        $str = mb_strtolower($str);
        if ($catapitalise_first_char) {
            $str = Tools::ucfirst($str);
        }
        return preg_replace_callback('/(-+|_+)([a-z])/', function($c) {
            return strtoupper($c[2]);
        }, $str);
    }



    /**
     * @param string $str
     * @return string
     */
    public static function ucfirst(string $str) : string
    {
        return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
    }





}