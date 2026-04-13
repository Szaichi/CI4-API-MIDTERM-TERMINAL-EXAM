<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminController extends BaseController
{
    // VIEW USERS //
    public function index()
    {
        $model = new UserModel();

        $data['users'] = $model
            ->select('users.*, roles.name as role_name') // include role
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->findAll();

        $data['roles'] = [
            1 => 'admin',
            2 => 'teacher',
            3 => 'student',
            4 => 'coordinator'
        ];

        return view('admin/users', $data);
    }

    // ASSIGN ROLE //
    public function assignRole($id)
    {
        $model = new UserModel();
        $role_id = $this->request->getPost('role_id'); // get selected role

        // prevent self-role change
        if (session('user')['id'] == $id) {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        $db = \Config\Database::connect();
        $user = $model->find($id); // get user

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $db->table('students')->where('user_id', $id)->delete();
        $db->table('teachers')->where('user_id', $id)->delete();
        $db->table('coordinators')->where('user_id', $id)->delete();

        // update role_id
        $model->update($id, [
            'role_id' => $role_id
        ]);

        // re-fetch updated user
        $user = $model->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found after update.');
        }

        // insert new role data

        if ($role_id == 2) { // teacher
            $db->table('teachers')->insert([
                'user_id' => $user['id'],
                'full_name' => $user['name']
            ]);
        }

        elseif ($role_id == 3) { // student
            $db->table('students')->insert([
                'student_id' => 'STD-' . date('Y') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT),
                'full_name' => $user['name'],
                'course' => 'N/A',
                'year_level' => 'N/A',
                'section' => 'N/A',
                'user_id' => $user['id'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        elseif ($role_id == 4) { // coordinator
            $db->table('coordinators')->insert([
                'user_id' => $user['id'],
                'full_name' => $user['name']
            ]);
        }

        return redirect()->back()->with('success', 'Role updated successfully.');
    }


    // DELETE USER //
    public function delete($id)
    {
        $model = new UserModel();
        $db = \Config\Database::connect();

        // prevent self-delete
        if (session('user')['id'] == $id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // DELETE PROFILE IMAGE FILE 
        $student = $db->table('students')->where('user_id', $id)->get()->getRowArray();

        if ($student && !empty($student['profile_image'])) {
            $path = FCPATH . 'uploads/profiles/' . $student['profile_image'];

            if (file_exists($path) && is_file($path)) {
                unlink($path); // delete image file
            }
        }

        // delete related data
        $db->table('students')->where('user_id', $id)->delete();
        $db->table('teachers')->where('user_id', $id)->delete();
        $db->table('coordinators')->where('user_id', $id)->delete();

        // delete user
        $model->delete($id);

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}