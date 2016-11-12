<?php

namespace Schema\Handlers;

use GraphQL\Type\Definition\ResolveInfo;

class Handler
{
    public function __call($name, $arguments)
    {
        list($source, $args, $context, $info) = $arguments;

        $fieldName = $info->fieldName;
        $property = null;

        if (is_array($source) || $source instanceof \ArrayAccess) {
            if (isset($source[$fieldName])) {
                $property = $source[$fieldName];
            }
        } else if (is_object($source)) {
            if (isset($source->{$fieldName})) {
                $property = $source->{$fieldName};
            }
        }

        return $property instanceof \Closure ? $property($source, $args, $context) : $property;
    }
}
