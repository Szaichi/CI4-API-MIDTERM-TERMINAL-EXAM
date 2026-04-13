<?php

namespace App\Controllers;

use App\Models\StudentModel;

class Dashboard extends BaseController
{
    // DASHBOARD //
    public function index()
    {

        $role = session('user')['role'];

        $model = new StudentModel();

        $data['students'] = $model
            ->orderBy('id', 'DESC') // latest first
            ->findAll();

        $data['role'] = $role;

        return view('dashboard/index', $data);
    }

}