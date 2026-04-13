<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileApi extends BaseController
{
    // GET PROFILE //
    public function show($id)
    {
        $model = new UserModel();
        $user = $model->find($id); // get user by id

        if (!$user) {
            return $this->response->setJSON([
                'status' => 404,
                'message' => 'User not found'
            ]);
        }

        // remove password before returning data
        unset($user['password']);

        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Profile retrieved successfully',
            'data' => $user
        ]);
    }

    // UPDATE PROFILE //
    public function update($id)
    {
        $model = new UserModel();
        $data = $this->request->getJSON(true); // get JSON request

        // validation
        if (!$data) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Invalid input'
            ]);
        }

        // check user existence
        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status' => 404,
                'message' => 'User not found'
            ]);
        }

        // update profile
        $model->update($id, $data);

        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Profile updated successfully'
        ]);
    }
}