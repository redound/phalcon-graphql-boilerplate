<?php

namespace App\Model;

class Ticket extends \App\Mvc\DateTrackingModel
{
    public $id;
    public $title;
    public $projectId;

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

    public function initialize() {

        $this->belongsTo('projectId', Project::class, 'id', [
            'alias' => 'Project',
        ]);
    }
}
