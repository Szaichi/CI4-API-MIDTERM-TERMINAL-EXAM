<?php

namespace App\Controllers;

use App\Models\StudentModel;

class Students extends BaseController
{
    // LIST STUDENTS //
    public function index()
    {
        $model = new StudentModel();

        $model->select('students.*, students.phone, students.address');

        $data['students'] = $model->paginate(10); // pagination
        $data['pager'] = $model->pager;

        return view('students/index', $data);
    }

    // CREATE VIEW //
    public function create()
    {
        return view('students/create');
    }

    // STORE STUDENT //
    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[3]',
            'course' => 'required',
            'year_level' => 'required',
            'section' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new StudentModel();

        $last = $model->orderBy('id', 'DESC')->first();

        $number = 1;

        if ($last && isset($last['student_id'])) {
            $lastNumber = (int) substr($last['student_id'], -4);
            $number = $lastNumber + 1;
        }

        $student_id = 'STD-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        $course = ($this->request->getPost('course') == 'N/A') ? '' : $this->request->getPost('course');
        $year = ($this->request->getPost('year_level') == 'N/A') ? '' : $this->request->getPost('year_level');
        $section = ($this->request->getPost('section') == 'N/A') ? '' : $this->request->getPost('section');

        $model->insert([
            'student_id' => $student_id,
            'full_name' => $this->request->getPost('full_name'),
            'course' => $course,
            'year_level' => $year,
            'section' => $section,

            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),

            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/students/create')->with('success', 'Student added successfully');
    }

    // UPDATE STUDENT //
    public function update($id)
    {
        $rules = [
            'full_name' => 'required|min_length[3]',
            'course' => 'required',
            'year_level' => 'required',
            'section' => 'required',

            'phone' => 'required',
            'address' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new StudentModel();

        $course = ($this->request->getPost('course') == 'N/A') ? '' : $this->request->getPost('course');
        $year = ($this->request->getPost('year_level') == 'N/A') ? '' : $this->request->getPost('year_level');
        $section = ($this->request->getPost('section') == 'N/A') ? '' : $this->request->getPost('section');

        $model->update($id, [
            'full_name' => $this->request->getPost('full_name'),
            'course' => $course,
            'year_level' => $year,
            'section' => $section,

            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),

            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/students')->with('success', 'Student updated successfully');
    }

    // DELETE STUDENT //
    public function delete($id)
    {
        $model = new StudentModel();
        $model->delete($id);

        return redirect()->to('/students')->with('success', 'Student deleted successfully');
    }
}