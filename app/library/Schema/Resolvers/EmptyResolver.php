<?php

namespace Schema\Resolvers;

use Schema\Definition\Field;

class EmptyResolver implements ResolverInterface
{
    public function resolve($source, $args, Field $field)
    {
        return [];
    }
}