<?php

namespace Schema\Definition;

class ObjectType
{

    protected $_name;

    protected $_description;

    protected $_handler;

    protected $_fields = [];

    public function name($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function description($description)
    {
        $this->_description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function handler($handler)
    {
        $this->_handler = $handler;
        return $this;
    }

    public function getHandler()
    {
        return $this->_handler;
    }

    public function field(Field $field)
    {
        $this->_fields[] = $field;
        return $this;
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public static function factory() {
        return new ObjectType;
    }
}
