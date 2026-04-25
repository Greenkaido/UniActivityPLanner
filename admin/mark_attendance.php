<?php
// admin/mark_attendance.php
require_once '../config/database.php';
require_once '../includes/header.php';

// Ensure it's admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$activity_id = $_GET['id'] ?? null;
if (!$activity_id) {
    header("Location: attendance.php");
    exit;
}

// Fetch activity details
$stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ?");
$stmt->execute([$activity_id]);
$activity = $stmt->fetch();

if (!$activity) {
    header("Location: attendance.php");
    exit;
}

// Handle Attendance Submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance_data = $_POST['attendance'] ?? [];
    
    foreach ($attendance_data as $student_id => $status) {
        // Upsert logic (Insert or Update if exists)
        $stmt = $pdo->prepare("INSERT INTO attendance (activity_id, user_id, status) 
                               VALUES (?, ?, ?) 
                               ON DUPLICATE KEY UPDATE status = VALUES(status)");
        $stmt->execute([$activity_id, $student_id, $status]);
    }
    $message = '<div class="alert alert-success mt-3">Attendance updated successfully!</div>';
}

// Fetch all students
$students = $pdo->query("SELECT * FROM users WHERE role = 'student' ORDER BY name ASC")->fetchAll();

// Fetch existing attendance for this activity
$attendance_stmt = $pdo->prepare("SELECT user_id, status FROM attendance WHERE activity_id = ?");
$attendance_stmt->execute([$activity_id]);
$marked_attendance = $attendance_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h3 class="fw-bold text-dark">Mark Attendance</h3>
        <h5 class="text-danger fw-medium"><?php echo htmlspecialchars($activity['title']); ?></h5>
        <p class="text-muted small">Date: <?php echo date('F d, Y', strtotime($activity['activity_date'])); ?></p>
        <?php echo $message; ?>
    </div>
    <div class="card-body px-4 pb-4">
        <form method="POST">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-3">Student Name</th>
                            <th class="py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <?php 
                            $status = $marked_attendance[$student['id']] ?? 'absent';
                        ?>
                        <tr>
                            <td class="py-3"><?php echo htmlspecialchars($student['name']); ?></td>
                            <td class="text-center py-3">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <input type="radio" class="btn-check" name="attendance[<?php echo $student['id']; ?>]" id="present_<?php echo $student['id']; ?>" value="present" <?php echo ($status === 'present') ? 'checked' : ''; ?> autocomplete="off">
                                    <label class="btn btn-outline-success btn-sm rounded-start-pill px-3" for="present_<?php echo $student['id']; ?>">Present</label>

                                    <input type="radio" class="btn-check" name="attendance[<?php echo $student['id']; ?>]" id="absent_<?php echo $student['id']; ?>" value="absent" <?php echo ($status === 'absent') ? 'checked' : ''; ?> autocomplete="off">
                                    <label class="btn btn-outline-danger btn-sm rounded-end-pill px-3" for="absent_<?php echo $student['id']; ?>">Absent</label>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-4">
                <a href="attendance.php" class="btn btn-light rounded-pill px-4 me-2 border">Back</a>
                <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">Save Attendance</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
