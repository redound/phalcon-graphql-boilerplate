<?php

namespace App\Handlers;

use App\Model\Project;
use Schema\Handlers\Handler;

class ProjectHandler extends Handler
{
    public function tickets(Project $source)
    {
        return $source->getTickets();
    }
}
