<?php

namespace App\Handlers;

use App\Model\Project;
use App\Model\Ticket;
use Schema\Handlers\Handler;
use Schema\Utils;

class ViewerHandler extends Handler
{
    public function allProjects()
    {
        return Utils::connectionFromArray(Project::find());
    }

    public function findProject($source, $args)
    {
        return Project::findFirst($args['id']);
    }

    public function allTickets()
    {
        return Utils::connectionFromArray(Ticket::find());
    }

    public function findTicket($source, $args)
    {
        return Ticket::findFirst($args['id']);
    }
}
