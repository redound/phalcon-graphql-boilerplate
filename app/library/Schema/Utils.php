<?php

namespace Schema;

class Utils
{
    public static function getShortClass($class){

        $path = explode('\\', $class);
        return array_pop($path);
    }
}