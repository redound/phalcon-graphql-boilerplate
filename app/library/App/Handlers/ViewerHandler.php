<?php

namespace App\Handlers;

use App\Model\Project;
use App\Model\Ticket;
use Schema\Utils;

class ViewerHandler
{

    public function allProjects($source, $args, $context, $info)
    {
        return Utils::connectionFromArray(Project::find());
    }

    public function findProject($source, $args, $context, $info)
    {
        return Project::findFirst($args['id']);
    }

    public function allTickets($source, $args, $context, $info)
    {
        return Utils::connectionFromArray(Ticket::find());
    }

    public function findTicket($source, $args, $context, $info)
    {
        return Ticket::findFirst($args['id']);
    }
}
