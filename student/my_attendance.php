<?php
// student/my_attendance.php
require_once '../config/database.php';
require_once '../includes/header.php';

// Ensure it's student
if ($_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all activities and check if the student has a matching attendance record
$sql = "SELECT a.title, a.activity_date, att.status 
        FROM activities a
        LEFT JOIN attendance att ON a.id = att.activity_id AND att.user_id = ?
        ORDER BY a.activity_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$records = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h3 class="fw-bold text-dark">My Activities & Attendance</h3>
        <p class="text-muted small">Your record of participation in various events.</p>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th class="py-3">Activity</th>
                        <th class="py-3">Date</th>
                        <th class="py-3 text-center">Participation Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($records): ?>
                        <?php foreach ($records as $record): ?>
                        <tr>
                            <td class="fw-medium text-dark py-3">
                                <?php echo htmlspecialchars($record['title']); ?>
                            </td>
                            <td class="text-muted py-3">
                                <?php echo date('M d, Y', strtotime($record['activity_date'])); ?>
                            </td>
                            <td class="text-center py-3">
                                <?php if ($record['status'] === 'present'): ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i> Present
                                    </span>
                                <?php elseif ($record['status'] === 'absent'): ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i> Absent
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2">
                                        <i class="bi bi-clock me-1"></i> Not Marked
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">No activity records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
