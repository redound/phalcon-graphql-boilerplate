<?php

namespace Schema\GraphQL;

use GraphQL\Type\Definition\EnumType;
use Schema\Definition\EnumType as SchemaEnumType;
use Schema\Definition\EnumTypeValue;

class EnumTypeFactory
{

    public static function build(SchemaEnumType $enumType)
    {
        $values = [];

        /** @var EnumTypeValue $value */
        foreach ($enumType->getValues() as $value) {
            $values[$value->getName()] = [
                'value' => $value->getValue(),
                'description' => $value->getDescription()
            ];
        }

        return new EnumType([
            'name' => $enumType->getName(),
            'description' => $enumType->getDescription(),
            'values' => $values
        ]);
    }
}
