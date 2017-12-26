<?php

namespace App\Constants;

class AclRoles
{
    const UNAUTHORIZED = 'Unauthorized';
    const AUTHORIZED = 'Authorized';
    const USER = 'User';
    const ADMIN = 'Admin';

    const ALL_ROLES = [self::UNAUTHORIZED, self::AUTHORIZED, self::USER, self::ADMIN];
}