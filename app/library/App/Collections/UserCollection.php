<?php

namespace App\Collections;

use App\Model\User;
use PhalconGraphQL\Definition\Collections\ModelCollection;

class UserCollection extends ModelCollection
{
    public function initialize()
    {
        $this
            ->model(User::class)
            ->crud();
    }
}