<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Prevent access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
$role = $_SESSION['role'];
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniActivity Planner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php 
$nav_bg = ($role === 'admin') ? 'bg-danger' : 'bg-success';
?>
<nav class="navbar navbar-expand-lg navbar-dark <?php echo $nav_bg; ?> mb-4">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">UniActivity Planner</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <?php if ($role === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="activities.php">Manage Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="students.php">Manage Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sent_notifications.php">Sent Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="attendance.php">Attendance</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="activities.php">All Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="notifications.php">Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_attendance.php">My Attendance</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link text-light">Welcome, <?php echo htmlspecialchars($name); ?> (<?php echo ucfirst($role); ?>)</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white ms-2 px-3" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
