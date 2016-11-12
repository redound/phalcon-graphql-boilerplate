<?php

namespace Schema\Definition;

use Phalcon\Db\Column;
use Phalcon\Di;
use Phalcon\Mvc\Model\MetaData;
use Schema\Constants\Services;

class ModelObjectType extends ObjectType
{
    protected $modelClass;
    protected $built = false;
    protected $skippedFields = [];

    protected $di;

    public function __construct($modelClass, $name=null, $description=null)
    {
        // Use class name if name not provided
        if($name === null) {
            $path = explode('\\', $modelClass);
            $name = array_pop($path);
        }

        parent::__construct($name, $description);

        $this->modelClass = $modelClass;

        $this->di = Di::getDefault();
    }

    public function skip($field)
    {
        $this->removeField($field);
        $this->skippedFields[] = $field;

        return $this;
    }

    public function getFields()
    {
        // Delay building, build when the fields are queried
        if(!$this->built){

            $this->build();
            $this->built = true;
        }

        return parent::getFields();
    }

    protected function build()
    {
        /** @var MetaData $modelsMetadata */
        $modelsMetadata = $this->di->get(Services::MODELS_METADATA);

        $modelClass = $this->modelClass;
        $model = new $modelClass();

        $columnMap = $modelsMetadata->getColumnMap($model);
        $dataTypes = $modelsMetadata->getDataTypes($model);
        $nonNullAttributes = $modelsMetadata->getNotNullAttributes($model);
        $identityField = $modelsMetadata->getIdentityField($model);

        $skip = $this->skippedFields;
        $typeMap = [];

        if(method_exists($model, 'excludedFields')){
            $skip = array_merge($skip, $model->excludedFields());
        }

        if(method_exists($model, 'typeMap')){
            $typeMap = $model->typeMap();
        }

        $mappedDataTypes = [];
        $mappedNonNullAttributes = [];

        foreach ($dataTypes as $attributeName => $dataType) {

            $mappedAttributeName = is_array($columnMap) && array_key_exists($attributeName, $columnMap) ? $columnMap[$attributeName] : $attributeName;

            $type = null;
            if($attributeName == $identityField){
                $type = Types::ID;
            }
            else if(array_key_exists($mappedAttributeName, $typeMap)){
                $type = $typeMap[$mappedAttributeName];
            }
            else {
                $type = $this->getMappedDatabaseType($dataType);
            }

            $mappedDataTypes[$mappedAttributeName] = $type;

            if(in_array($attributeName, $nonNullAttributes)){
                $mappedNonNullAttributes[] = $mappedAttributeName;
            }
        }

        $originalFields = $this->_fields;
        $newFields = [];

        foreach($mappedDataTypes as $attribute => $type){

            if(in_array($attribute, $skip) || $this->fieldExists($attribute)){
                continue;
            }

            $field = Field::factory($attribute, $type);
            if(in_array($attribute, $mappedNonNullAttributes)){
                $field->nonNull();
            }

            $newFields[] = $field;
        }

        $this->_fields = array_merge($newFields, $originalFields);
    }

    protected function getMappedDatabaseType($type)
    {
        $responseType = null;

        switch ($type) {

            case Column::TYPE_INTEGER:
            case Column::TYPE_BIGINTEGER: {

                $responseType = Types::INT;
                break;
            }

            case Column::TYPE_DECIMAL:
            case Column::TYPE_DOUBLE:
            case Column::TYPE_FLOAT: {

                $responseType = Types::FLOAT;
                break;
            }

            case Column::TYPE_BOOLEAN: {

                $responseType = Types::BOOLEAN;
                break;
            }

            case Column::TYPE_VARCHAR:
            case Column::TYPE_CHAR:
            case Column::TYPE_TEXT:
            case Column::TYPE_BLOB:
            case Column::TYPE_MEDIUMBLOB:
            case Column::TYPE_LONGBLOB: {

                $responseType = Types::STRING;
                break;
            }

            // TODO: Implement?
//            case Column::TYPE_DATE:
//            case Column::TYPE_DATETIME: {
//
//                $responseType = self::TYPE_DATE;
//                break;
//            }

            default:
                $responseType = Types::STRING;
        }

        return $responseType;
    }

    /**
     * @return static
     */
    public static function factory($modelClass, $name=null, $description=null)
    {
        return new ModelObjectType($modelClass, $name, $description);
    }
}