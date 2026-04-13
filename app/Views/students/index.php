<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>


<?php 
    $role = session('user')['role'] ?? null; // get user role
?>

<style>
    .table-bordered {
        border: 1px solid #ff99cc;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #ff99cc !important;
        vertical-align: middle;
        padding: 12px;
    }

    .table thead th {
        background: #fff0f6;
        font-weight: bold;
    }
</style>

<!-- HEADER -->
<div class="card p-4 shadow">

    <h3 class="mb-3">Manage Students</h3>

    <!-- MESSAGES -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ACTION -->
    <?php if ($role === 'teacher' || $role === 'admin'): ?>
        <a href="/students/create" class="btn btn-primary mb-3">
            Add Student
        </a>
    <?php endif; ?>

    <!-- TABLE -->
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Section</th>
                <th>Phone</th> 
                <th>Address</th> 
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= esc($s['student_id']) ?></td>
                    <td><?= esc($s['full_name']) ?></td>

                    <td><?= !empty($s['course']) && $s['course'] != 'N/A' ? esc($s['course']) : '' ?></td>
                    <td><?= !empty($s['year_level']) && $s['year_level'] != 'N/A' ? esc($s['year_level']) : '' ?></td>
                    <td><?= !empty($s['section']) && $s['section'] != 'N/A' ? esc($s['section']) : '' ?></td>

                    <td><?= !empty($s['phone']) && $s['phone'] != '-' ? esc($s['phone']) : '' ?></td>
                    <td><?= !empty($s['address']) && $s['address'] != '-' ? esc($s['address']) : '' ?></td>

                    <td>

                        <button 
                            class="btn action-btn btn-sm view-btn"
                            data-name="<?= esc($s['full_name']) ?>"
                            data-course="<?= (!empty($s['course']) && $s['course'] != 'N/A') ? esc($s['course']) : '' ?>"
                            data-year="<?= (!empty($s['year_level']) && $s['year_level'] != 'N/A') ? esc($s['year_level']) : '' ?>"
                            data-section="<?= (!empty($s['section']) && $s['section'] != 'N/A') ? esc($s['section']) : '' ?>"
                            data-phone="<?= (!empty($s['phone']) && $s['phone'] != '-') ? esc($s['phone']) : '' ?>" 
                            data-address="<?= (!empty($s['address']) && $s['address'] != '-') ? esc($s['address']) : '' ?>"
                            data-created="<?= !empty($s['created_at']) ? date('M d, Y - h:i A', strtotime($s['created_at'])) : '' ?>"
                            data-updated="<?= !empty($s['updated_at']) ? date('M d, Y - h:i A', strtotime($s['updated_at'])) : '' ?>"
                        >
                            View
                        </button>

                        <?php if ($role === 'teacher' || $role === 'admin' || $role === 'coordinator'): ?>
                            <button 
                                class="btn action-btn btn-sm edit-btn"
                                data-id="<?= $s['id'] ?>"
                                data-name="<?= esc($s['full_name']) ?>"
                                data-course="<?= (!empty($s['course']) && $s['course'] != 'N/A') ? esc($s['course']) : '' ?>"
                                data-year="<?= (!empty($s['year_level']) && $s['year_level'] != 'N/A') ? esc($s['year_level']) : '' ?>"
                                data-section="<?= (!empty($s['section']) && $s['section'] != 'N/A') ? esc($s['section']) : '' ?>"
                                data-phone="<?= (!empty($s['phone']) && $s['phone'] != '-') ? esc($s['phone']) : '' ?>"
                                data-address="<?= (!empty($s['address']) && $s['address'] != '-') ? esc($s['address']) : '' ?>"
                            >
                                Edit
                            </button>
                        <?php endif; ?>

                        <?php if ($role === 'teacher' || $role === 'admin'): ?>
                            <form action="/students/delete/<?= $s['id'] ?>" method="post" onsubmit="return confirm('Are you sure?')" style="display:inline;">
                                <?= csrf_field() ?>
                                <button class="btn action-btn btn-sm">
                                    Delete
                                </button>
                            </form>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-center">
        <?= $pager->links() ?>
    </div>

</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><b>Full Name:</b> <span id="m_name"></span></p>
                <p><b>Course:</b> <span id="m_course"></span></p>
                <p><b>Year Level:</b> <span id="m_year"></span></p>
                <p><b>Section:</b> <span id="m_section"></span></p>
                <p><b>Phone:</b> <span id="m_phone"></span></p> 
                <p><b>Address:</b> <span id="m_address"></span></p> 
                <p><b>Created:</b> <span id="m_created"></span></p>
                <p><b>Updated:</b> <span id="m_updated"></span></p>
            </div>

        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" id="editForm">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <?= csrf_field() ?>

                    <input type="hidden" id="e_id">

                    <input class="form-control mb-2" id="e_name" name="full_name" placeholder="Full Name" required>
                    <input class="form-control mb-2" id="e_course" name="course" placeholder="Course" required>
                    <input class="form-control mb-2" id="e_year" name="year_level" placeholder="Year Level" required>
                    <input class="form-control mb-2" id="e_section" name="section" placeholder="Section" required>

                    <input class="form-control mb-2" id="e_phone" name="phone" placeholder="Phone" required>
                    <input class="form-control mb-2" id="e_address" name="address" placeholder="Address" required>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

// VIEW SCRIPT //
document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', function(){

        document.getElementById('m_name').innerText = this.dataset.name;
        document.getElementById('m_course').innerText = this.dataset.course;
        document.getElementById('m_year').innerText = this.dataset.year;
        document.getElementById('m_section').innerText = this.dataset.section;
        document.getElementById('m_phone').innerText = this.dataset.phone; 
        document.getElementById('m_address').innerText = this.dataset.address; 
        document.getElementById('m_created').innerText = this.dataset.created;
        document.getElementById('m_updated').innerText = this.dataset.updated;

        new bootstrap.Modal(document.getElementById('viewModal')).show();
    });
});

// EDIT SCRIPT //
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function(){

        const id = this.dataset.id;

        document.getElementById('e_id').value = id;
        document.getElementById('e_name').value = this.dataset.name;
        document.getElementById('e_course').value = this.dataset.course;
        document.getElementById('e_year').value = this.dataset.year;
        document.getElementById('e_section').value = this.dataset.section;

        document.getElementById('e_phone').value = this.dataset.phone;
        document.getElementById('e_address').value = this.dataset.address;

        document.getElementById('editForm').action = "/students/update/" + id;

        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});
</script>

<?= $this->endSection() ?>