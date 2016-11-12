<?php

namespace Schema\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use PhalconRest\Exception;
use Schema\Definition\Field;
use Schema\Definition\InputField;

class FieldFactory
{
    /**
     * If a no resolve functions are given, then a default resolve behavior is used
     * which takes the property of the source object of the same name as the field
     * and returns it as the result, or if it's a function, returns the result
     * of calling that function while passing along args and context.
     *
     * @param array $resolvers
     * @return \Closure
     */
    private static function getResolverFn($resolvers = [])
    {
        if (empty($resolvers)) {

            return function ($source, $args, $context, ResolveInfo $info) {
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
            };
        }

        return function ($source, $args, $context, $info) use ($resolvers) {

            foreach ($resolvers as $resolverFn) {

                if (is_callable($resolverFn)) {
                    $source = call_user_func($resolverFn, $source, $args, $context, $info);
                } else if (is_string($resolverFn)) {

                    $parts = explode('::', $resolverFn);

                    if (count($parts) === 2) {

                        $className = $parts[0];
                        $methodName = $parts[1];

                        if (class_exists($className)) {

                            $obj = new $className;
                            call_user_func([$obj, $methodName], $source, $args, $context, $info);
                        }
                    }
                }
            }

            return $source;
        };
    }

    public static function build(Field $field, $handler, TypeRegistry $typeRegistry)
    {
        $type = $field->getType();
        $nonNull = $field->getNonNull();
        $isList = $field->getIsList();
        $isNonNullList = $field->getIsNonNullList();

        $resolvers = $field->getResolvers();

        $resolverFn = static::getResolverFn($resolvers);

        $args = [];

        /** @var InputField $inputField */
        foreach ($field->getArgs() as $inputField) {

            $args[$inputField->getName()] = InputFieldFactory::build($inputField, $typeRegistry);
        }

        return [
            'description' => $field->getDescription(),
            'type' => $typeRegistry->resolve($type, $nonNull, $isList, $isNonNullList),
            'args' => $args,
            'resolve' => $resolverFn
        ];
    }
}
