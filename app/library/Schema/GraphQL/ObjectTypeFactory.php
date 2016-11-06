<?php

namespace Schema\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use Schema\Definition\Field;
use Schema\Definition\ObjectType as SchemaObjectType;

class ObjectTypeFactory
{

    public function __invoke(SchemaObjectType $objectType, TypeRegistry $typeRegistry)
    {
        return new ObjectType([
            'name' => $objectType->getName(),
            'description' => $objectType->getDescription(),
            'fields' => function () use ($objectType, $typeRegistry) {

                $fields = [];

                $fieldFactory = new FieldFactory;

                /** @var Field $field */
                foreach ($objectType->getFields() as $field) {
                    $fields[$field->getName()] = $fieldFactory($field, $typeRegistry);
                }

                return $fields;
            }
        ]);
    }
}
