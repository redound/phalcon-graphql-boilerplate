<?php

namespace Schema\Handlers;

class EmptyHandler
{
    public function __call($name, $arguments)
    {
        return [];
    }
}