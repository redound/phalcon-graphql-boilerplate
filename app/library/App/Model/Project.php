<?php

namespace App\Model;

use App\Constants\Types;
use Schema\Definition\FieldAware;

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

    public function typeMap()
    {
        return [
            'state' => Types::TICKET_STATE_ENUM
        ];
    }

    public function excludedFields(){

        return ['createdAt', 'updatedAt'];
    }

    public function initialize() {

        $this->hasMany('id', Ticket::class, 'projectId', [
            'alias' => 'Tickets',
        ]);
    }
}
