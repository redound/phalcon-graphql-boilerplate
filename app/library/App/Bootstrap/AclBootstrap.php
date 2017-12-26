<?php

namespace App\Bootstrap;

use App\BootstrapInterface;
use App\Constants\Services;
use Phalcon\Acl;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;
use App\Constants\AclRoles;

class AclBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        /** @var \PhalconApi\Acl\MountingEnabledAdapterInterface $acl */
        $acl = $di->get(Services::ACL);

        $unauthorizedRole = new Acl\Role(AclRoles::UNAUTHORIZED);
        $authorizedRole = new Acl\Role(AclRoles::AUTHORIZED);

        $acl->addRole($unauthorizedRole);
        $acl->addRole($authorizedRole);

        $acl->addRole(new Acl\Role(AclRoles::USER), $authorizedRole);
        $acl->addRole(new Acl\Role(AclRoles::ADMIN), $authorizedRole);
    }
}