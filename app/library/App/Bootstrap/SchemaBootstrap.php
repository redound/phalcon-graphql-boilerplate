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
use PhalconGraphQL\Definition\FieldGroups\ModelMutationFieldGroup;
use PhalconGraphQL\Definition\FieldGroups\ModelQueryFieldGroup;
use PhalconGraphQL\Definition\ModelInputObjectType;
use PhalconGraphQL\Definition\ModelObjectType;
use PhalconGraphQL\Definition\ObjectType;
use PhalconGraphQL\Definition\ObjectTypeGroups\EmbeddedObjectTypeGroup;
use PhalconGraphQL\Definition\Schema;

class SchemaBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $schema = Schema::factory()

            ->embed()

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
             * Types
             */
            ->objectGroup(EmbeddedObjectTypeGroup::factory(ModelObjectType::factory(User::class)))
            ->objectGroup(EmbeddedObjectTypeGroup::factory(ModelObjectType::factory(Project::class)))
            ->objectGroup(EmbeddedObjectTypeGroup::factory(ModelObjectType::factory(Ticket::class)))

            ->inputObject(ModelInputObjectType::create(Project::class))
            ->inputObject(ModelInputObjectType::update(Project::class))

            ->inputObject(ModelInputObjectType::create(Ticket::class))
            ->inputObject(ModelInputObjectType::update(Ticket::class))

            /**
             * Query
             */
            ->object(ObjectType::query()
                ->field(Field::viewer())
            )

            ->object(ObjectType::viewer()

                ->fieldGroup(ModelQueryFieldGroup::factory(Project::class))
                ->fieldGroup(ModelQueryFieldGroup::factory(Ticket::class))
            )

            /**
             * Mutation
             */
            ->object(ObjectType::mutation()

                ->fieldGroup(ModelMutationFieldGroup::factory(Project::class))
                ->fieldGroup(ModelMutationFieldGroup::factory(Ticket::class))
            );

        $di->setShared(Services::SCHEMA, $schema);
    }
}
