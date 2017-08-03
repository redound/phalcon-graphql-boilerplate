<?php

namespace App\Model;

use App\Behaviors\DateTracking;
use App\Constants\Types;
use Phalcon\Mvc\Model;

class Ticket extends Model
{
    public $id;
    public $title;
    public $projectId;
    public $amountHours;
    public $state;
    public $private;

    public $createdAt;
    public $updatedAt;

    public function getSource()
    {
        return 'tickets';
    }

    public function columnMap()
    {
        return [
            'id' => 'id',
            'title' => 'title',
            'project_id' => 'projectId',
            'amount_hours' => 'amountHours',
            'state' => 'state',
            'private' => 'private',

            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt'
        ];
    }

    public function excludedInputFields(){

        return ['createdAt', 'updatedAt'];
    }

    public function typeMap()
    {
        return [
            'private' => Types::BOOLEAN,
            'state' => Types::TICKET_STATE_ENUM
        ];
    }

    public function initialize() {

        $this->addBehavior(new DateTracking());

        $this->belongsTo('projectId', Project::class, 'id', [
            'alias' => 'Project',
        ]);
    }
}
