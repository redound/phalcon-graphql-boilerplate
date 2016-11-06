<?php

namespace Schema\GraphQL;

use Schema\Definition\InputField;

class InputFieldFactory
{

    public function __invoke(InputField $inputField, TypeRegistry $typeRegistry)
    {

        $type = $inputField->getType();
        $nonNull = $inputField->getNonNull();
        $isList = $inputField->getIsList();
        $isNonNullList = $inputField->getIsNonNullList();

        return [
            'description' => $inputField->getDescription(),
            'type' => $typeRegistry->resolve($type, $nonNull, $isList, $isNonNullList),
            'defaultValue' => $inputField->getDescription()
        ];
    }
}
