<?php
// index.php
session_start();

// If user is already logged in, redirect them based on their role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
        exit;
    } else {
        header("Location: student/dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - UniActivity Planner</title>
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-card {
            max-width: 500px;
            width: 100%;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: #fff;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="welcome-card">
    <h1 class="mb-4 text-primary">UniActivity Planner</h1>
    <p class="text-muted mb-4">Welcome to the university activities and events management system.</p>
    
    <div class="d-grid gap-3">
        <a href="admin/index.php" class="btn btn-danger btn-lg">Admin Portal</a>
        <a href="student/index.php" class="btn btn-success btn-lg">Student Portal</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
