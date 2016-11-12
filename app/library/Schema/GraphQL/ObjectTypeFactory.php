<?php

namespace Schema\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use Phalcon\Di;
use Schema\Definition\Field;
use Schema\Definition\ObjectType as SchemaObjectType;

class ObjectTypeFactory
{

    public static function build(Di $di, $defaultNamespace, SchemaObjectType $objectType, TypeRegistry $typeRegistry)
    {
        return new ObjectType([
            'name' => $objectType->getName(),
            'description' => $objectType->getDescription(),
            'fields' => function () use ($di, $defaultNamespace, $objectType, $typeRegistry) {

                $fields = [];

                $handler = null;
                $handlerClassName = $objectType->getHandler();

                if ($handlerClassName) {
                    $handler = new $handlerClassName;
                } else {
                    $handlerClassName = $defaultNamespace . '\\' . $objectType->getName() . 'Handler';

                    $handler = new $handlerClassName;
                }

                if ($handler instanceof \Phalcon\Di\Injectable) {
                    $handler->setDI($di);
                }

                /** @var Field $field */
                foreach ($objectType->getFields() as $field) {
                    $fields[$field->getName()] = FieldFactory::build($field, $handler, $typeRegistry);
                }

                return $fields;
            }
        ]);
    }
}
