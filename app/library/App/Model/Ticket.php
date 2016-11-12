<?php

namespace App\Model;

use App\Constants\Types;

class Ticket extends \App\Mvc\DateTrackingModel
{
    public $id;
    public $title;
    public $projectId;
    public $amountHours;
    public $state;
    public $private;

    public function getSource()
    {
        return 'tickets';
    }

    public function columnMap()
    {
        return parent::columnMap() + [
            'id' => 'id',
            'title' => 'title',
            'project_id' => 'projectId',
            'amount_hours' => 'amountHours',
            'state' => 'state',
            'private' => 'private'
        ];
    }

    public function typeMap()
    {
        return [
            'private' => Types::BOOLEAN,
            'state' => Types::TICKET_STATE_ENUM
        ];
    }

    public function initialize() {

        $this->belongsTo('projectId', Project::class, 'id', [
            'alias' => 'Project',
        ]);
    }
}
