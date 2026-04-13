<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{

    // VIEW PROFILE //
    public function show()
    {

        $userSession = session('user');

        if (!$userSession || !isset($userSession['id'])) {
            return redirect()->to('/login');
        }

        $userId = $userSession['id'];

        $db = \Config\Database::connect();

        $builder = $db->table('users');
        $builder->select('
            users.*,
            students.student_id as student_student_id,
            students.course,
            students.year_level,
            students.section,
            students.phone as student_phone,
            students.address as student_address,
            students.profile_image as student_profile_image
        ');
        $builder->join('students', 'students.user_id = users.id', 'left');
        $builder->where('users.id', $userId);

        $user = $builder->get()->getRowArray();

        return view('profile/show', [
            'user' => $user
        ]);
    }

    // EDIT PROFILE //
    public function edit()
    {
        $userModel = new UserModel();

        $userSession = session('user');

        if (!$userSession || !isset($userSession['id'])) {
            return redirect()->to('/login');
        }

        $userId = $userSession['id'];

        $db = \Config\Database::connect();

        $builder = $db->table('users');
        $builder->select('
            users.*,
            students.student_id as student_student_id,
            students.course,
            students.year_level,
            students.section,
            students.phone as student_phone,
            students.address as student_address,
            students.profile_image as student_profile_image
        ');
        $builder->join('students', 'students.user_id = users.id', 'left');
        $builder->where('users.id', $userId);

        $user = $builder->get()->getRowArray();

        return view('profile/edit', [
            'user' => $user
        ]);
    }

    // UPDATE PROFILE //
    public function update()
    {
        $userModel = new UserModel();

        $userSession = session('user');

        if (!$userSession || !isset($userSession['id'])) {
            return redirect()->to('/login');
        }

        $userId = $userSession['id'];

        $user = $userModel->find($userId);

        $db = \Config\Database::connect();

        $file = $this->request->getFile('profile_image');
        $removeImage = $this->request->getPost('remove_image');

        $student = $db->table('students')->where('user_id', $userId)->get()->getRowArray();
        $filename = $student['profile_image'] ?? null;

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $allowedMime = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

            $ext = strtolower($file->getExtension());
            $mime = $file->getMimeType();

            if (!in_array($ext, $allowedExtensions) || !in_array($mime, $allowedMime)) {
                return redirect()->back()->withInput()->with('errors', [
                    'profile_image' => 'Only JPG, JPEG, PNG, and WEBP images are allowed.'
                ]);
            }

            if ($file->getSize() > 2048000) {
                return redirect()->back()->withInput()->with('errors', [
                    'profile_image' => 'Image must not exceed 2MB.'
                ]);
            }
        }

        // REMOVE IMAGE //
        if ($removeImage == 1) {

            if (!empty($filename)) {
                $oldPath = FCPATH . 'uploads/profiles/' . $filename;

                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
            }

            $filename = null;
        }

        // UPLOAD NEW IMAGE //
        elseif ($file && $file->isValid() && !$file->hasMoved()) {

            if (!empty($filename)) {
                $oldPath = FCPATH . 'uploads/profiles/' . $filename;

                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
            }

            $ext = $file->getExtension();
            $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;

            $file->move(FCPATH . 'uploads/profiles/', $filename);
        }

        // USERS UPDATE //
        $userModel->updateProfile($userId, [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $studentBuilder = $db->table('students');

        $existing = $studentBuilder
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        $existingStudentId = $existing['student_id'] ?? null;

        $autoStudentId = $existingStudentId 
            ? $existingStudentId 
            : 'STD-' . date('Y') . '-' . str_pad($userId, 4, '0', STR_PAD_LEFT);

        $dataStudent = [
            'user_id' => $userId,
            'student_id' => $autoStudentId,
            'course' => $this->request->getPost('course'),
            'year_level' => $this->request->getPost('year_level'),
            'section' => $this->request->getPost('section'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'profile_image' => $filename,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($existing) {
            $studentBuilder->where('user_id', $userId)->update($dataStudent);
        } else {
            $dataStudent['created_at'] = date('Y-m-d H:i:s');
            $studentBuilder->insert($dataStudent);
        }

        return redirect()->to('/profile')
            ->with('success', 'Profile Updated Successfully');
    }
}