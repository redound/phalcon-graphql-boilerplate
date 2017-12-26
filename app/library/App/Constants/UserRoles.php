<?php

namespace App\Constants;

class UserRoles
{
    const USER = 1;
    const ADMIN = 2;

    const ALL_ROLES = [self::USER, self::ADMIN];

    public static function toAclRole($role){

        if($role == self::USER){
            return AclRoles::USER;
        }
        else if($role == self::ADMIN){
            return AclRoles::ADMIN;
        }

        return null;
    }
}