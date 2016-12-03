<?php

namespace App\Bootstrap;

use App\BootstrapInterface;
use App\Collections\ProjectCollection;
use App\Collections\TicketCollection;
use App\Collections\UserCollection;
use App\Constants\Services;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;
use PhalconGraphQL\Definition\Fields\Field;
use PhalconGraphQL\Definition\ObjectType;
use PhalconGraphQL\Definition\Schema;
use PhalconGraphQL\Plugins\Paging\OffsetLimitPagingPlugin;

class SchemaBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $schema = Schema::factory()

            ->embedList()

            ->plugin(new OffsetLimitPagingPlugin())

            ->object(ObjectType::query()
                ->field(Field::viewer())
            )

            ->object(ObjectType::viewer())

            ->object(ObjectType::mutation())

            ->mount(new ProjectCollection)
            ->mount(new TicketCollection)
            ->mount(new UserCollection);

        $di->setShared(Services::SCHEMA, $schema);
    }
}
