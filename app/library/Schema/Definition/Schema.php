<?php

namespace Schema\Definition;

class Schema
{
    protected $_enumTypes = [];
    protected $_objectTypes = [];

    public function enumType(EnumType $enumType)
    {
        $this->_enumTypes[] = $enumType;
        return $this;
    }

    public function getEnumTypes()
    {
        return $this->_enumTypes;
    }

    public function objectType(ObjectType $objectType)
    {
        $this->_objectTypes[] = $objectType;
        return $this;
    }

    public function getObjectTypes()
    {
        return $this->_objectTypes;
    }

    public static function factory()
    {
        return new Schema;
    }
}
