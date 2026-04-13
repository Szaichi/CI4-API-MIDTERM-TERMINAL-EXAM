<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\StudentModel;

class StudentApi extends ResourceController
{
    protected $modelName = StudentModel::class;
    protected $format = 'json'; // response format

    // GET ALL //
    public function index()
    {
        $students = $this->model->findAll(); // fetch all

        return $this->respond([
            'status' => 200,
            'message' => 'Students retrieved successfully',
            'data' => $students
        ]);
    }

    // GET SINGLE //
    public function show($id = null)
    {
        $student = $this->model->find($id); // fetch by id

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        return $this->respond([
            'status' => 200,
            'data' => $student
        ]);
    }

    // CREATE //
    public function create()
    {

        $data = $this->request->getJSON(true); // get JSON request

        if (!$data) {
            return $this->fail('Invalid input', 400);
        }

        $this->model->insert($data); // insert student

        return $this->respondCreated([
            'status' => 201,
            'message' => 'Student created successfully'
        ]);
    }

    // UPDATE //
    public function update($id = null)
    {
        $student = $this->model->find($id); // check existence

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $data = $this->request->getJSON(true); // get JSON request

        if (!$data) {
            return $this->fail('Invalid input', 400);
        }

        $this->model->update($id, $data); // update student

        return $this->respond([
            'status' => 200,
            'message' => 'Student updated successfully'
        ]);
    }

    // DELETE //
    public function delete($id = null)
    {
        $student = $this->model->find($id); // check existence

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        $this->model->delete($id); // delete student

        return $this->respondDeleted([
            'status' => 200,
            'message' => 'Student deleted successfully'
        ]);
    }
}