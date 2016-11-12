<?php

namespace App\Bootstrap;

use App\BootstrapInterface;
use App\Constants\Types;
use App\Handlers\ProjectHandler;
use App\Handlers\ViewerHandler;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconRest\Api;
use Schema\Definition\EnumType;
use Schema\Definition\EnumTypeValue;
use Schema\Definition\Field;
use Schema\Definition\InputField;
use Schema\Definition\ObjectType;
use Schema\Definition\Schema;

class SchemaBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $schema = Schema::factory()

            /**
             * Define Enum Types
             */
            ->enum(EnumType::factory(Types::PROJECT_STATE_ENUM, 'Represents the state of the project')
                ->value(EnumTypeValue::factory('OPEN', 0, 'Open'))
                ->value(EnumTypeValue::factory('CLOSED', 1, 'Closed'))
            )
            ->enum(EnumType::factory(Types::TICKET_STATE_ENUM, 'Represents the state of the ticket')
                ->value(EnumTypeValue::factory('NEW', 0, 'New'))
                ->value(EnumTypeValue::factory('IN_PROGRESS', 1, 'In Progress'))
                ->value(EnumTypeValue::factory('COMPLETED', 2, 'Completed'))
            )

            /**
             * Define Object Types
             */
            ->object(ObjectType::factory(Types::QUERY)
                ->field(Field::factory('viewer', Types::VIEWER)
                    ->nonNull()
                    ->resolver(function () {
                        return [];
                    })
                )
            )

            ->object(ObjectType::factory(Types::VIEWER)
                ->field(Field::factory('allProjects', Types::connection(Types::PROJECT))
                    ->nonNull()
                )
                ->field(Field::factory('findProject', Types::PROJECT)
                    ->arg(InputField::id('id'))
                    ->nonNull()
                )
                ->field(Field::factory('allTickets', Types::connection(Types::TICKET))
                    ->nonNull()
                )
                ->field(Field::factory('findTicket', Types::TICKET)
                    ->arg(InputField::id('id'))
                    ->nonNull()
                )
            )

            ->embeddedObject(ObjectType::factory(Types::PROJECT, 'Represents a Project')
                ->field(Field::id('id')
                    ->nonNull()
                )
                ->field(Field::string('title', 'Title of the Project')
                    ->nonNull()
                )
                ->field(Field::factory('state', Types::PROJECT_STATE_ENUM, 'State of the Project')
                    ->nonNull()
                )
                ->field(Field::factory('tickets', Types::connection(Types::TICKET), 'Tickets of the Project')
                    ->nonNull()
                )
            )

            ->embeddedObject(ObjectType::factory(Types::TICKET, 'Represents a Ticket')
                ->field(Field::id('id')
                    ->nonNull()
                )
                ->field(Field::string('title')
                    ->nonNull()
                )
                ->field(Field::factory('state', Types::TICKET_STATE_ENUM)
                    ->nonNull()
                )
                ->field(Field::boolean('private', 'Whether the Ticket is private or not')
                    ->nonNull()
                )
                ->field(Field::int('amountHours', 'How many hours the Ticket will cost to resolve')
                    ->nonNull()
                )
                ->field(Field::factory('project', Types::PROJECT))
            );

        $di->setShared('schema', $schema);
    }
}
