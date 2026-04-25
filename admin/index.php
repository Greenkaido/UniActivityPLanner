<?php
// admin/index.php
session_start();
require_once '../config/database.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['login_id']);
    // Since we use raw md5 for dummy data and md5 in general for this project
    $password = md5(trim($_POST['password']));

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid admin credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - UniActivity Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="login-card">
        <h3 class="text-center mb-4 text-danger">Admin Login</h3>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Enter ID</label>
                <input type="text" name="login_id" class="form-control" required placeholder="Email ID" autocomplete="new-string">
            </div>
            <div class="mb-3">
                <label class="form-label">Enter Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Password" autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-danger w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="../index.php">Back to Home</a>
        </div>
    </div>
</body>

</html>