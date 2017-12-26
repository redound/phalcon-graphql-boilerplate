<?php

namespace App\Model;

use Phalcon\Mvc\Model;

class User extends Model
{
    public $id;
    public $role;
    public $firstName;
    public $lastName;
    public $email;
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
            'first_name' => 'firstName',
            'last_name' => 'lastName',
            'email' => 'email',
            'password' => 'password',
            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt'
        ];
    }

    public function excludedFields(){

        return ['password'];
    }

    public function excludedInputFields(){

        return ['createdAt', 'updatedAt'];
    }
}
