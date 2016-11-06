<?php

namespace App\Handlers;

use App\Model\Project;
use App\Model\Ticket;

class ItemHandler
{

    public function project(Ticket $source, $args, $context, $info)
    {
        return Project::findFirst($source->projectId);
    }
}
