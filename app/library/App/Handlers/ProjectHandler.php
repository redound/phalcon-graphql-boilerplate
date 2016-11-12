<?php

namespace App\Handlers;

use App\Model\Project;
use App\Model\Ticket;
use Schema\Handlers\Handler;
use Schema\Utils;

class ProjectHandler extends Handler
{
    public function tickets(Project $source, $args, $context, $info)
    {
        return Utils::connectionFromArray($source->getTickets());
    }
}
