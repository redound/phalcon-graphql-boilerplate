<?php

namespace App\Model;

use App\Behaviors\DateTracking;
use Phalcon\Mvc\Model;

class User extends Model
{
    public $id;
    public $role;
    public $email;
    public $firstName;
    public $lastName;
    public $location;
    public $username;
    public $password;

    public $createdAt;
    public $updatedAt;

    public function getSource()
    {
        return 'users';
    }

    public function columnMap()
    {
        return [
            'id' => 'id',
            'role' => 'role',
            'email' => 'email',
            'username' => 'username',
            'first_name' => 'firstName',
            'last_name' => 'lastName',
            'location' => 'location',
            'password' => 'password',

            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt'
        ];
    }

    public function excludedInputFields(){

        return ['createdAt', 'updatedAt'];
    }

    public function excludedOutputFields(){

        return ['password'];
    }

    public function initialize()
    {
        $this->addBehavior(new DateTracking());
    }
}
