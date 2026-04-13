<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class UserApi extends ResourceController
{
    protected $modelName = UserModel::class;
    protected $format = 'json'; // response format

    // GET ALL USERS //
    public function index()
    {
        $users = $this->model
            ->select('users.*, roles.name as role_name') // include role
            ->join('roles', 'roles.id = users.role_id', 'left') // join roles
            ->findAll();

        return $this->respond([
            'status' => 200,
            'message' => 'Users retrieved successfully',
            'data' => $users
        ]);
    }

    // GET SINGLE USER //
    public function show($id = null)
    {
        $user = $this->model->find($id); // fetch by id

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        return $this->respond([
            'status' => 200,
            'message' => 'User retrieved successfully',
            'data' => $user
        ]);
    }

    // DELETE USER //
    public function delete($id = null)
    {
        if (!$this->model->find($id)) { // check existence
            return $this->failNotFound('User not found');
        }

        $this->model->delete($id); // delete user

        return $this->respondDeleted([
            'status' => 200,
            'message' => 'User deleted successfully'
        ]);
    }
}