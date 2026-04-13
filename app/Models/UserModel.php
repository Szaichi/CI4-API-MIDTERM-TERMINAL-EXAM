<?php

namespace App\Models;

use CodeIgniter\Model;

// USER MODEL //
class UserModel extends Model
{

    // CONFIG //
    protected $table = 'users'; // table name

    protected $primaryKey = 'id'; // primary key 

    protected $allowedFields = [
        'name',
        'username',
        'email',
        'password',

        'profile_image',

        'role_id',

        'created_at',
        'updated_at'
    ];

    // UPDATE PROFILE //
    public function updateProfile(int $userId, array $data): bool
    {
        $strictUserFields = [
            'name',
            'username',
            'email',
            'password',
            'role_id',
            'created_at',
            'updated_at'
        ];

        $data = array_intersect_key($data, array_flip($strictUserFields));

        return $this->update($userId, $data); // update user
    }

    // GET USER WITH ROLE //
    public function getUserWithRole($login)
    {
        return $this->select('users.*, roles.name as role_name') // include role
            ->join('roles', 'roles.id = users.role_id')
            ->groupStart()
                ->where('email', $login)
                ->orWhere('username', $login) // login via email or username
            ->groupEnd()
            ->first();
    }

}