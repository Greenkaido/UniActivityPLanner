<?php
// admin/students.php
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'notify') {
        $user_id = $_POST['user_id'];
        $notify_message = trim($_POST['message']);
        
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        if($stmt->execute([$user_id, $notify_message])) {
            $message = "<div class='alert alert-success'>Notification sent successfully.</div>";
        }
    } elseif ($_POST['action'] == 'delete_student') {
        $user_id = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
        if($stmt->execute([$user_id])) {
            $message = "<div class='alert alert-success'>Student deleted successfully.</div>";
            // Refresh student list
            $students = $pdo->query("SELECT * FROM users WHERE role = 'student' ORDER BY id DESC")->fetchAll();
        }
    }
}

// Fetch all students
$students = $pdo->query("SELECT * FROM users WHERE role = 'student' ORDER BY id DESC")->fetchAll();
?>

<div class="mb-4">
    <h2>Manage Students</h2>
    <p class="text-muted">View registered students and send notifications</p>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($students as $student): ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-danger text-white me-2" data-bs-toggle="modal" data-bs-target="#notifyModal<?php echo $student['id']; ?>">Send Notification</button>
                            
                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this student and all their data?');">
                                <input type="hidden" name="action" value="delete_student">
                                <input type="hidden" name="user_id" value="<?php echo $student['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                    <?php if(empty($students)): ?>
                        <tr><td colspan="4" class="text-center">No students registered yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Render all Notify Modals at the bottom of the page to avoid stacking issues -->
<?php foreach($students as $student): ?>
<div class="modal fade" id="notifyModal<?php echo $student['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Notify <?php echo htmlspecialchars($student['name']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="notify">
                    <input type="hidden" name="user_id" value="<?php echo $student['id']; ?>">
                    <div class="mb-3">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger text-white">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php require_once '../includes/footer.php'; ?>
