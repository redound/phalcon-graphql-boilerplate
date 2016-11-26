<?php

namespace App\Handlers;

use App\Model\Project;

class MutationHandler extends \PhalconGraphQL\Handlers\Handler
{
    public function createProject($source, $args)
    {
        return Project::findFirst();
    }
}