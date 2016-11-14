<?php

namespace Schema\Resolvers;

use Schema\Definition\Field;

interface ResolverInterface
{
    public function resolve($source, $args, Field $field);
}