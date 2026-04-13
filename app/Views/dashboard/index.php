<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>


<!-- HEADERROLE HANDLING -->
<?php $role = session('user')['role']; ?> <!-- get user role -->

<!-- ACTION CARDS -->
<?php if ($role === 'teacher' || $role === 'admin'): ?> <!-- role-based access -->

<div class="row mb-4">

    <div class="col-md-6">
        <div class="card p-4 shadow">
            <h5>Add Student</h5>
            <p>Add a new student to the system.</p>
            <a href="/students/create" class="btn btn-primary w-100">
                Add Student
            </a>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-4 shadow">
            <h5>Manage Students</h5>
            <p>View, edit, or delete student records.</p>
            <a href="/students" class="btn btn-primary w-100">
                Open Students
            </a>
        </div>
    </div>

</div>

<?php endif; ?>

<!-- STUDENTS TABLE -->
<?php if ($role !== 'student'): ?> 

<div class="card p-4 shadow">

    <h5 class="mb-3">
        <?= $role === 'coordinator' ? 'Students Overview' : 'Recent Students' ?>
    </h5>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Section</th>
                <th>Created</th>
                <th>Updated</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($students as $s): ?> <!-- loop students -->
                <tr>
                    <td><?= esc($s['student_id']) ?></td>
                    <td><?= esc($s['full_name']) ?></td>
                    <td><?= esc($s['course']) ?></td>
                    <td><?= esc($s['year_level']) ?></td>
                    <td><?= esc($s['section']) ?></td>
                    <td>
                        <?= !empty($s['created_at']) 
                            ? date('M d, Y - h:i A', strtotime($s['created_at'])) 
                            : '' ?>
                    </td>
                    <td>
                        <?= !empty($s['updated_at']) 
                            ? date('M d, Y - h:i A', strtotime($s['updated_at'])) 
                            : '' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>

<?php endif; ?>


<?= $this->endSection() ?>