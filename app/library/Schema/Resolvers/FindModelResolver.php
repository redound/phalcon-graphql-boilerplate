<?php

namespace Schema\Resolvers;

use Schema\Definition\Field;

class FindModelResolver extends ModelResolver
{
    public function resolve($source, $args, Field $field)
    {
        $model = $this->getModel($field);

        return $model::findFirst($args['id']);
    }
}