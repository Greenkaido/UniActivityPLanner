<?php
// admin/attendance.php
require_once '../config/database.php';
require_once '../includes/header.php';

// Ensure it's admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Fetch all activities
$activities = $pdo->query("SELECT * FROM activities ORDER BY activity_date DESC")->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h3 class="fw-bold text-dark">Attendance Management</h3>
        <p class="text-muted">Select an activity to mark student attendance.</p>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">Activity Title</th>
                        <th class="py-3">Date</th>
                        <th class="py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td class="fw-medium text-dark py-3">
                            <i class="bi bi-calendar-check text-danger me-2"></i>
                            <?php echo htmlspecialchars($activity['title']); ?>
                        </td>
                        <td class="text-muted py-3">
                            <?php echo date('M d, Y', strtotime($activity['activity_date'])); ?>
                        </td>
                        <td class="text-center py-3">
                            <a href="mark_attendance.php?id=<?php echo $activity['id']; ?>" class="btn btn-danger btn-sm rounded-pill px-4">
                                <i class="bi bi-person-check me-1"></i> Mark Attendance
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
