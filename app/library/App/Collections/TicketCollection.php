<?php

namespace App\Collections;

use App\Constants\Types;
use App\Model\Ticket;
use PhalconGraphQL\Definition\Collections\ModelCollection;
use PhalconGraphQL\Definition\EnumType;

class TicketCollection extends ModelCollection
{
    public function initialize()
    {
        $this
            ->model(Ticket::class)

            ->enum(EnumType::factory(Types::TICKET_STATE_ENUM, 'Represents the state of the ticket')
                ->value('NEW', 0, 'New')
                ->value('IN_PROGRESS', 1, 'In Progress')
                ->value('COMPLETED', 2, 'Completed')
            )

            ->crud(Types::VIEWER, Types::MUTATION);
    }
}