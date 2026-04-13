<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // REGISTER VIEW //
    public function register()
    {
        return view('auth/register');
    }

    // REGISTER PROCESS //
    public function store()
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        $messages = [
            'confirm_password' => [
                'matches' => 'Passwords do not match.'
            ]
        ];

        if (!$this->validate($rules, $messages)) { // validate input
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model = new UserModel();

        $email = $this->request->getPost('email');
        $username = $this->request->getPost('username');

        // check duplicates
        if ($model->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'Email already exists'
            ]);
        }

        if ($model->where('username', $username)->first()) {
            return redirect()->back()->withInput()->with('errors', [
                'username' => 'Username already exists'
            ]);
        }

        // insert user
        $model->insert([
            'name' => $this->request->getPost('name'),
            'username' => $username,
            'email' => $email,
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_BCRYPT
            ), // hashed password
            'role_id' => 3, // default student
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // AUTO CREATE STUDENT RECORD
        $userId = $model->insertID();

        $db = \Config\Database::connect();

        $autoStudentId = 'STD-' . date('Y') . '-' . str_pad($userId, 4, '0', STR_PAD_LEFT);

        $db->table('students')->insert([
            'user_id' => $userId,
            'student_id' => $autoStudentId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/login')
            ->with('success', 'Account successfully created! Please login.');
    }

    // LOGIN VIEW //
    public function login()
    {
        return view('auth/login');
    }

    // AUTHENTICATE //
    public function authenticate()
    {
        $model = new UserModel();

        $login = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->getUserWithRole($login); // get user with role

        if ($user && password_verify($password, $user['password'])) { // verify password

            session()->set([
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => strtolower($user['role_name'])
                ],
                'logged_in' => true
            ]);

            $role = strtolower($user['role_name']);

            // role-based redirect
            if (in_array($role, ['admin', 'teacher', 'coordinator'])) {
                return redirect()->to('/dashboard');
            }

            if ($role === 'student') {
                return redirect()->to('/profile');
            }

            return redirect()->to('/login');
        }

        return redirect()->back()
            ->with('error', 'Invalid email/username or password');
    }

    // UNAUTHORIZED PAGE //
    public function unauthorized()
    {
        return view('errors/unauthorized');
    }

    // LOGOUT //
    public function logout()
    {
        session()->destroy(); // clear session
        return redirect()->to('/login');
    }
}