<?php

namespace Schema\Definition\ModelFields;

use Schema\Definition\Field;
use Schema\Definition\InputField;
use Schema\Definition\Types;
use Schema\Resolvers\AllModelResolver;
use Schema\Resolvers\FindModelResolver;

class ModelField extends Field
{
    protected $_model;

    public function __construct($model=null, $name=null, $type=null, $description=null)
    {
        parent::__construct($name, $type, $description);

        $this->_model = $model;
    }

    /**
     * @param string $modelClass
     * @return static
     */
    public function model($modelClass)
    {
        $this->_model = $modelClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @param string $model
     * @param string $name
     * @param string $type
     * @param string $description
     *
     * @return static
     */
    public static function factory($model=null, $name=null, $type=null, $description=null)
    {
        return new ModelField($model, $name, $type, $description);
    }

    public static function listFactory($model=null, $name=null, $type=null, $description=null)
    {
        return self::factory($model, $name, $type, $description)->isList();
    }


    public static function all($model=null, $name=null, $type=null, $description=null)
    {
        return self::factory($model, $name, $type, $description)
            ->resolver(AllModelResolver::class)
            ->nonNull();
    }

    public static function find($model=null, $name=null, $type=null, $description=null)
    {
        return self::factory($model, $name, $type, $description)
            ->resolver(FindModelResolver::class)
            ->arg(InputField::factory('id', Types::ID));
    }
}