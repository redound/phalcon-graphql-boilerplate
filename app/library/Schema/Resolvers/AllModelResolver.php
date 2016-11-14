<?php

namespace Schema\Resolvers;

use Schema\Definition\Field;

class AllModelResolver extends ModelResolver
{
    public function resolve($source, $args, Field $field)
    {
        $model = $this->getModel($field);

        return $model::find();
    }
}