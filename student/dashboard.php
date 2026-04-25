<?php
// student/dashboard.php
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get counts
$unreadNotifCount = $pdo->query("SELECT count(*) FROM notifications WHERE user_id = $user_id AND status = 'unread'")->fetchColumn();
$recentActivities = $pdo->query("SELECT * FROM activities ORDER BY activity_date DESC LIMIT 5")->fetchAll();
?>

<div class="card premium-card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-4 p-md-5 bg-success text-white position-relative" style="background: linear-gradient(135deg, #22c55e, #16a34a) !important;">
        <div class="position-relative z-index-1">
            <h2 class="fw-bold mb-2">Student Dashboard</h2>
            <p class="text-white-50 mb-0">Welcome to your student portal</p>
        </div>
        <!-- Decorative background element -->
        <div class="position-absolute border rounded-circle bg-white opacity-10" style="width: 200px; height: 200px; top: -50px; right: -20px;"></div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card dashboard-stat border-0 shadow-sm rounded-4 h-100" style="border-left-color: #f59e0b !important;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="text-muted fw-normal mb-1">Unread Notifications</h5>
                    <h2 class="mb-0 fw-bold" style="color: #1e293b;"><?php echo $unreadNotifCount; ?></h2>
                </div>
                <div class="icon-box bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-bell fs-3"></i>
                </div>
            </div>
            <a href="notifications.php" class="btn btn-sm btn-outline-warning w-100 rounded-pill">View Notifications</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card dashboard-stat border-0 shadow-sm rounded-4 h-100" style="border-left-color: #198754 !important;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="text-muted fw-normal mb-1">Upcoming Activities</h5>
                    <h2 class="mb-0 fw-bold" style="color: #1e293b;"><?php echo count($recentActivities); ?></h2>
                </div>
                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-calendar-event fs-3"></i>
                </div>
            </div>
            <a href="activities.php" class="btn btn-sm btn-outline-success w-100 rounded-pill">View All Activities</a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 mt-2">
        <h5 class="fw-bold" style="color: #0f172a;">Recent Activities</h5>
    </div>
    <div class="card-body px-4 pb-4">
        <?php if ($recentActivities): ?>
        <div class="list-group list-group-flush mt-3">
            <?php foreach ($recentActivities as $act): ?>
            <a href="activities.php" class="list-group-item list-group-item-action border-0 mb-2 rounded-3 bg-light bg-opacity-50 hover-shadow transition-all p-3">
                <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-calendar2-check text-success me-2"></i><?php echo htmlspecialchars($act['title']); ?>
                    </h5>
                    <span class="badge bg-white text-dark border shadow-sm"><i class="bi bi-clock me-1"></i><?php echo date('M d, Y h:i A', strtotime($act['activity_date'])); ?></span>
                </div>
                <p class="mb-0 text-muted ms-4 ps-2 border-start border-2 border-success border-opacity-25 py-1"><?php echo htmlspecialchars(substr($act['description'], 0, 100)) . '...'; ?></p>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="mt-3 text-end">
            <a href="activities.php" class="btn btn-outline-success btn-sm rounded-pill px-4">View Full Schedule</a>
        </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 opacity-50 mb-3 block"></i>
                <p>No activities posted yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
