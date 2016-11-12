<?php

namespace Schema\Definition;

class Types
{
    const STRING = "String";
    const INT = "Int";
    const FLOAT = "Float";
    const BOOLEAN = "Boolean";
    const ID = "ID";

    public static function connection($name){

        return $name . 'Connection';
    }

    public static function edge($name){

        return $name . 'Edge';
    }
}