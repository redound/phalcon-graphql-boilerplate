<?php

namespace App\Behaviors;

class DateTracking extends \Phalcon\Mvc\Model\Behavior\Timestampable
{
    public function __construct($options=[])
    {
        parent::__construct(array_merge($options, [
            'beforeCreate' => ['field'  => 'created_at', 'format' => 'Y-m-d H:i:s'],
            'beforeUpdate' => ['field'  => 'updated_at', 'format' => 'Y-m-d H:i:s']
        ]));
    }
}