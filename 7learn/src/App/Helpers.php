<?php

namespace App;

class Helpers
{
    public static function camelToUnderscore($string, $us = "_")
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]+|(?<!^|\d)[\d]+/', $us . '$0', $string));
    }
}
