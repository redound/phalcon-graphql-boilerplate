<?php

namespace App\Behaviors;

class SoftDelete extends \Phalcon\Mvc\Model\Behavior\SoftDelete
{
    public function __construct($options=[])
    {
        parent::__construct(array_merge($options, [
            'field' => 'deleted',
            'value' => 1
        ]));
    }
}