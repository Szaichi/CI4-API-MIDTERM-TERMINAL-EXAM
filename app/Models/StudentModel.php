<?php

namespace App\Models;

use CodeIgniter\Model;

// STUDENT MODEL //
class StudentModel extends Model
{
    // CONFIG //
    protected $table = 'students'; // table name 

    protected $allowedFields = [ 
        'student_id',
        'full_name',
        'course',
        'year_level',
        'section',
        'phone',
        'address',
        'created_at',
        'updated_at'
    ];
}