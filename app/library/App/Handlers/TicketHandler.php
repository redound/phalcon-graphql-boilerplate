<?php

namespace App\Handlers;

use App\Model\Project;
use App\Model\Ticket;

class TicketHandler
{

    public function project(Ticket $source, $args, $context, $info)
    {
        return Project::findFirst($source->projectId);
    }
}
