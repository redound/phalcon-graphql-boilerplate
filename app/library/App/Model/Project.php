<?php

namespace App\Model;

use App\Constants\Types;

class Project extends \App\Mvc\DateTrackingModel
{
    public $id;
    public $ownerUserId;
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
            'owner_user_id' => 'ownerUserId',
            'title' => 'title',
            'state' => 'state'
        ];
    }

    public function typeMap()
    {
        return [
            'state' => Types::PROJECT_STATE_ENUM
        ];
    }

    public function excludedFields(){

        return ['createdAt', 'updatedAt'];
    }

    public function initialize() {

        $this->hasMany('id', Ticket::class, 'projectId', [
            'alias' => 'Tickets',
        ]);

        $this->hasOne('ownerUserId', User::class, 'id', [
            'alias' => 'OwnerUser'
        ]);
    }
}
