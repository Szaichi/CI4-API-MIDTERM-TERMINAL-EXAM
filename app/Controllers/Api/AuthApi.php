<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthApi extends BaseController
{
    // LOGIN //
    public function login()
    {
        $model = new UserModel();
        $data = $this->request->getJSON(true); // get JSON request

        // validation
        if (!$data || !isset($data['email']) || !isset($data['password'])) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Email and password are required'
            ]);
        }

        $user = $model->getUserWithRole($data['email']); // fetch user with role

        if ($user && password_verify($data['password'], $user['password'])) { // verify password
            unset($user['password']);
            return $this->response->setJSON([
                'status' => 200,
                'message' => 'Login successful',
                'data' => $user
            ]);
        }

        return $this->response->setJSON([
            'status' => 401,
            'message' => 'Invalid credentials'
        ]);
    }

    // REGISTER //
    public function register()
    {
        $model = new UserModel();
        $data = $this->request->getJSON(true); // get JSON request

        // validation
        if (
            !$data ||
            !isset($data['name']) ||
            !isset($data['username']) ||
            !isset($data['email']) ||
            !isset($data['password'])
        ) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'All fields are required'
            ]);
        }

        // check email
        if ($model->where('email', $data['email'])->first()) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Email already exists'
            ]);
        }

        // check username
        if ($model->where('username', $data['username'])->first()) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Username already exists'
            ]);
        }

        // insert user
        $model->insert([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT), // hash password
            'role_id' => 3, // default role
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => 201,
            'message' => 'User registered successfully'
        ]);
    }
}