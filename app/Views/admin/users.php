<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>


<!-- MESSAGES -->
<div class="card p-4">

    <h3 class="mb-3">User Management</h3>

    <?php if(session('success')): ?> <!-- success message -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?> <!-- error message -->
        <div class="alert alert-danger text-center"><?= session('error') ?></div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="table-responsive">

        <table class="table table-bordered align-middle text-center">

            <thead style="background:#ff4da6; color:white;">
                <tr>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th style="width:150px;">Role</th>
                    <th style="width:180px;">Action</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach($users as $user): ?> <!-- loop users -->
                <tr>

                    <td class="fw-semibold">
                        <?= esc($user['name']) ?>
                    </td>

                    <td>
                        <?= esc($user['username']) ?>
                    </td>

                    <td>
                        <?= esc($user['email']) ?>
                    </td>

                    <td>
                        <span class="fw-semibold">
                            <?= ucfirst($user['role_name']) ?>
                        </span>
                    </td>

                    <td>
                        <div class="d-flex justify-content-center gap-2">

                            <button 
                                class="btn btn-sm"
                                style="background:#ff4da6; color:white; border:none; padding:4px 10px;"
                                onclick="openModal(<?= $user['id'] ?>, '<?= $user['role_id'] ?>')"> <!-- open modal -->
                                Edit
                            </button>

                            <form 
                                action="/admin/users/delete/<?= $user['id'] ?>" 
                                method="post"
                                onsubmit="return confirm('Are you sure you want to delete this user?');"
                            >
                                <?= csrf_field() ?> <!-- CSRF protection -->
                                <button 
                                    class="btn btn-sm"
                                    style="background:#ff1a75; color:white; border:none; padding:4px 10px;">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>


<!-- ROLE MODAL -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" id="modalForm">

                <div class="modal-header">
                    <h5 class="modal-title">Update Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">

                    <select name="role_id" id="modalRole" class="form-select"> <!-- role select -->
                        <?php foreach($roles as $id => $role): ?>
                            <option value="<?= $id ?>">
                                <?= ucfirst($role) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
// OPEN MODAL //
function openModal(userId, roleId) {
    document.getElementById('modalForm').action = '/admin/users/assign-role/' + userId;
    document.getElementById('modalRole').value = roleId;

    var modal = new bootstrap.Modal(document.getElementById('roleModal'));
    modal.show();
}
</script>

<?= $this->endSection() ?>