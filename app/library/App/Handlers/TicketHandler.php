<?php

namespace App\Handlers;

use App\Model\Project;
use App\Model\Ticket;
use Schema\Handlers\Handler;

class TicketHandler extends Handler
{
    public function project(Ticket $source)
    {
        return Project::findFirst($source->projectId);
    }
}
