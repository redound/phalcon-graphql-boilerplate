<?php

namespace App\Model;

class Project extends \App\Mvc\DateTrackingModel
{
    public $id;
    public $title;
    public $state;

    public function getSource()
    {
        return 'projects';
    }

    public function columnMap()
    {
        return parent::columnMap() + [
            'id' => 'id',
            'title' => 'title',
            'state' => 'state'
        ];
    }

    public function initialize() {

        $this->hasMany('id', Ticket::class, 'projectId', [
            'alias' => 'Tickets',
        ]);
    }
}
