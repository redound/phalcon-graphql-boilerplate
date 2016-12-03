<?php

namespace App\Collections;

use App\Constants\Types;
use App\Model\Project;
use PhalconGraphQL\Definition\Collections\ModelCollection;
use PhalconGraphQL\Definition\EnumType;

class ProjectCollection extends ModelCollection
{
    public function initialize()
    {
        $this
            ->model(Project::class)

            ->enum(EnumType::factory(Types::PROJECT_STATE_ENUM, 'Represents the state of the project')
                ->value('OPEN', 0, 'Open')
                ->value('CLOSED', 1, 'Closed')
            )

            ->crud(Types::VIEWER, Types::MUTATION);
    }
}