<?php

namespace App\Model;

use App\Behaviors\DateTracking;
use App\Constants\Types;
use Phalcon\Mvc\Model;

class Project extends Model
{
    public $id;
    public $ownerUserId;
    public $title;
    public $state;

    public $createdAt;
    public $updatedAt;

    public function getSource()
    {
        return 'projects';
    }

    public function columnMap()
    {
        return [
            'id' => 'id',
            'owner_user_id' => 'ownerUserId',
            'title' => 'title',
            'state' => 'state',

            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt'
        ];
    }

    public function typeMap()
    {
        return [
            'state' => Types::PROJECT_STATE_ENUM
        ];
    }

    public function excludedInputFields(){

        return ['createdAt', 'updatedAt'];
    }

    public function initialize() {

        $this->addBehavior(new DateTracking());

        $this->hasMany('id', Ticket::class, 'projectId', [
            'alias' => 'Tickets',
        ]);

        $this->hasOne('ownerUserId', User::class, 'id', [
            'alias' => 'OwnerUser'
        ]);
    }
}
