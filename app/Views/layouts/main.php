<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI4 CRUD</title>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #ffe6f2;
            font-family: Arial, Helvetica, sans-serif;
        }

        .center {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar {
            background: #ff66b2;
        }

        .btn-primary {
            background: #ff4da6;
            border: none;
        }

        .btn-primary:hover {
            background: #ff1a8c;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: #ff4da6;
            color: white;
            padding: 30px 20px;
        }

        .sidebar h4 {
            font-weight: bold;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 0;
            font-weight: 500;
        }

        .sidebar a:hover {
            opacity: 0.8;
        }

        .content {
            flex: 1;
            background: #fff5f9;
            padding: 40px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #ff4da6;
        }

        .table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .table-bordered {
            border: 1px solid #f1b6d3;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #f1b6d3 !important;
            vertical-align: middle;
            padding: 12px;
        }

        .table thead th {
            background: #fce4ec;
            border-bottom: 2px solid #f48fb1;
            font-weight: bold;
        }

        .action-btn {
            background: #ffffff;
            color: #ff4da6;
            border: 2px solid #ff4da6;
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 6px;
            transition: all .25s ease;
        }

        .action-btn:hover {
            background: #ff4da6;
            color: #ffffff;
            transform: scale(1.05);
        }

        .action-btn:active {
            transform: scale(0.95);
        }

        .btn-back {
            border: 2px solid #ff4da6;
            color: #ff4da6;
            background: transparent;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .btn-back:hover {
            background: #ff4da6;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 77, 166, 0.3);
        }

        .btn-back:active {
            transform: scale(0.95);
        }

        .table tbody tr:hover {
            background: #fff0f6;
        }
    </style>
</head>

<body>

<!-- LAYOUT -->
<?php 
    $segment = service('uri')->getSegment(1); // current route
    $user = session('user') ?? null; // session user
    $role = $user['role'] ?? null; // user role
?>

<?php if ($segment != 'login' && $segment != 'register'): ?> <!-- main layout condition -->

<div class="layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h4>Dashboard</h4>

        <?php if ($role === 'admin' || $role === 'teacher' || $role === 'coordinator' ): ?> <!-- dashboard access -->
            <a href="/dashboard">Home</a>
        <?php endif; ?>

        <?php if ($role === 'student'): ?> <!-- student menu -->
            <a href="/profile">Student Profile</a>
        <?php endif; ?>

        <?php if ($role === 'teacher' || $role === 'admin'): ?> <!-- manage students -->
            <a href="/students/create">Add Student</a>
            <a href="/students">Manage Students</a>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?> <!-- admin only -->
            <a href="/admin/users">Manage Users</a>
        <?php endif; ?>

        <a href="/logout">Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <?php if ($user): ?> <!-- show welcome -->
            <div class="card p-3 mb-4" style="border-left:5px solid #ff4da6;">
                <h5 class="mb-1">
                    Welcome, <?= esc($user['name']) ?> 
                    <span style="color:#ff4da6;">
                        (<?= esc(ucfirst($role)) ?>)
                    </span>
                </h5>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?> <!-- render page content -->

    </div>

</div>

<?php else: ?> <!-- auth pages layout -->

    <!-- AUTH CONTENT -->
    <div class="center">
        <?= $this->renderSection('content') ?>
    </div>

<?php endif; ?>

</body>
</html>