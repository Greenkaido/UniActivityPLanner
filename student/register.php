<?php
// student/register.php
session_start();
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Email already registered!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
        if ($stmt->execute([$name, $email, $password])) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Failed to register. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register - UniActivity Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { max-width: 450px; width: 100%; padding: 40px; border-radius: 10px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="login-card mt-5 mb-5">
    <h3 class="text-center mb-4" style="color: #2ece89 !important;">Student Registration</h3>
    <?php if($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="name" class="form-control" required placeholder="Full Name">
        </div>
        <div class="mb-3">
            <label class="form-label">Enter ID</label>
            <input type="email" name="email" class="form-control" required placeholder="Email ID">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Create Password">
        </div>
        <button type="submit" class="btn w-100 mb-3 text-white fw-bold" style="background-color: #2ece89; border-color: #2ece89;">Register</button>
    </form>
    <div class="text-center">
        <p>Already have an account? <a href="index.php" class="text-success fw-bold text-decoration-none">Login here</a></p>
        <a href="../index.php">Back to Home</a>
    </div>
</div>
</body>
</html>
