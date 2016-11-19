<?php

namespace App\Bootstrap;

use App\BootstrapInterface;
use App\Constants\Services;
use App\Constants\Types;
use App\Model\Project;
use App\Model\Ticket;
use App\Model\User;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;
use PhalconGraphQL\Definition\EnumType;
use PhalconGraphQL\Definition\Field;
use PhalconGraphQL\Definition\ModelField;
use PhalconGraphQL\Definition\ModelObjectType;
use PhalconGraphQL\Definition\ObjectType;
use PhalconGraphQL\Definition\ObjectTypeGroups\EmbeddedObjectTypeGroup;
use PhalconGraphQL\Definition\Schema;

class SchemaBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        Schema::setDefaultEmbedMode(Schema::EMBED_MODE_ALL);

        $schema = Schema::factory()

            /**
             * Define Enum Types
             */
            ->enum(EnumType::factory(Types::PROJECT_STATE_ENUM, 'Represents the state of the project')
                ->value('OPEN', 0, 'Open')
                ->value('CLOSED', 1, 'Closed')
            )
            ->enum(EnumType::factory(Types::TICKET_STATE_ENUM, 'Represents the state of the ticket')
                ->value('NEW', 0, 'New')
                ->value('IN_PROGRESS', 1, 'In Progress')
                ->value('COMPLETED', 2, 'Completed')
            )

            /**
             * Define Object Types
             */
            ->object(ObjectType::query()
                ->field(Field::viewer())
            )

            ->object(ObjectType::viewer()
                ->field(ModelField::all(Project::class))
                ->field(ModelField::find(Project::class))
                ->field(ModelField::all(Ticket::class))
                ->field(ModelField::find(Ticket::class))
            )

            ->objects(EmbeddedObjectTypeGroup::factory(ModelObjectType::factory(User::class)))
            ->objects(EmbeddedObjectTypeGroup::factory(ModelObjectType::factory(Project::class)))
            ->objects(EmbeddedObjectTypeGroup::factory(ModelObjectType::factory(Ticket::class)));

        $di->setShared(Services::SCHEMA, $schema);
    }
}
