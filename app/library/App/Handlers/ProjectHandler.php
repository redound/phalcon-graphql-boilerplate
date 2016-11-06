<?php

namespace App\Handlers;

use App\Model\Project;
use App\Model\Ticket;
use Schema\Utils;

class ProjectHandler
{

    public function tickets(Project $source, $args, $context, $info)
    {
        return Utils::connectionFromArray(Ticket::find([
            'projectId = :projectId:',
            'bind' => [
                'projectId' => $source->id
            ]
        ]));
    }
}
