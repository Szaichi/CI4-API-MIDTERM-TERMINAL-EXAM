<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- ROLE HANDLING -->
<?php 
    $role = session('user')['role'] ?? null; // get role

    $redirect = '/login'; // default redirect

    if ($role === 'admin' || $role === 'teacher') {
        $redirect = '/dashboard'; // admin/teacher redirect
    }

    if ($role === 'student') {
        $redirect = '/profile'; // student redirect
    }
?>

<!-- VIEW -->
<div class="card p-5 text-center">
    <h2>403 Unauthorized</h2>

    <p>Your role: 
        <b><?= esc($role ?? 'Guest') ?></b>
    </p>

    <p>You do not have access to this page.</p>

    <a href="<?= $redirect ?>" class="btn btn-primary">
        Go Back
    </a>
</div>

<?= $this->endSection() ?>