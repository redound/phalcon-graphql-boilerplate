<?php

namespace App\Collections;

use App\Constants\AclRoles;
use App\Constants\Types;
use App\Model\User;
use PhalconGraphQL\Definition\Collections\ModelCollection;

class UserCollection extends ModelCollection
{
    public function initialize()
    {
        $this
            ->model(User::class)

            ->allowQuery(AclRoles::AUTHORIZED)
            ->allowMutation(AclRoles::AUTHORIZED)

            ->crud(Types::VIEWER, Types::MUTATION);
    }
}