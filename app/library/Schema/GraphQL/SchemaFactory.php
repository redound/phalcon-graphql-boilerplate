<?php

namespace Schema\GraphQL;

use GraphQL\Type\Definition\Type;
use Phalcon\Di;
use Schema\Definition\EnumType;
use Schema\Definition\ObjectType;

class SchemaFactory
{

    public static function build(Di $di, $defaultNamespace, \Schema\Definition\Schema $schema)
    {
        $typeRegistry = new TypeRegistry();

        $defaultScalarTypes = [
            'String' => Type::string(),
            'Int' => Type::int(),
            'Float' => Type::float(),
            'Boolean' => Type::boolean(),
            'ID' => Type::id()
        ];

        foreach ($defaultScalarTypes as $name => $type) {
            $typeRegistry->register($name, $type);
        }

        /** @var EnumType $enumType */
        foreach ($schema->getEnumTypes() as $enumType) {
            $typeRegistry->register($enumType->getName(), EnumTypeFactory::build($enumType));
        }

        /** @var ObjectType $objectType */
        foreach ($schema->getObjectTypes() as $objectType) {
            $typeRegistry->register($objectType->getName(), ObjectTypeFactory::build($di, $defaultNamespace, $objectType, $typeRegistry));
        }

        $schemaFields = [];

        if ($typeRegistry->hasType('Query')) {
            $schemaFields['query'] = $typeRegistry->resolve('Query');
        }

        if ($typeRegistry->hasType('Mutation')) {
            $schemaFields['mutation'] = $typeRegistry->resolve('Mutation');
        }

        return new \GraphQL\Schema($schemaFields);
    }
}
