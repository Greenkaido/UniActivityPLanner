<?php
// admin/dashboard.php
require_once '../config/database.php';
require_once '../includes/header.php';

// Ensure it's admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Fetch stats
$studentsCount = $pdo->query("SELECT count(*) FROM users WHERE role = 'student'")->fetchColumn();
$activitiesCount = $pdo->query("SELECT count(*) FROM activities")->fetchColumn();
$recentActivities = $pdo->query("SELECT * FROM activities ORDER BY activity_date DESC LIMIT 5")->fetchAll();
?>

<div class="card premium-card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-4 p-md-5 bg-danger text-white position-relative" style="background: linear-gradient(135deg, #ef4444, #dc3545) !important;">
        <div class="position-relative z-index-1">
            <h2 class="fw-bold mb-2">Admin Dashboard</h2>
            <p class="text-white-50 mb-0">Overview of your activity planner system</p>
        </div>
        <!-- Decorative background element -->
        <div class="position-absolute border rounded-circle bg-white opacity-10" style="width: 200px; height: 200px; top: -50px; right: -20px;"></div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card dashboard-stat h-100 border-0 shadow-sm rounded-4" style="border-left-color: #dc3545 !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted fw-normal mb-1">Total Students</h5>
                    <h2 class="mb-0 fw-bold" style="color: #1e293b;"><?php echo $studentsCount; ?></h2>
                </div>
                <div class="icon-box bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-people fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card dashboard-stat h-100 border-0 shadow-sm rounded-4" style="border-left-color: #10b981 !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted fw-normal mb-1">Total Activities</h5>
                    <h2 class="mb-0 fw-bold" style="color: #1e293b;"><?php echo $activitiesCount; ?></h2>
                </div>
                <div class="icon-box bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-calendar-event fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-box bg-danger rounded-circle p-3 text-white me-3">
                    <i class="bi bi-person-check fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Attendance Tracking</h5>
                    <p class="text-muted small mb-0">Mark and manage student participation</p>
                </div>
            </div>
            <a href="attendance.php" class="btn btn-outline-danger w-100 rounded-pill mt-auto">Go to Attendance</a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 mt-2">
        <h5 class="fw-bold" style="color: #0f172a;">Recent Activities</h5>
    </div>
    <div class="card-body px-4 pb-4">
        <?php if ($recentActivities): ?>
        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="rounded-start">Title</th>
                        <th class="rounded-end">Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentActivities as $activity): ?>
                    <tr>
                        <td class="fw-medium text-dark py-3">
                            <i class="bi bi-calendar2-check text-danger me-2"></i>
                            <?php echo htmlspecialchars($activity['title']); ?>
                        </td>
                        <td class="text-muted py-3">
                            <span class="badge bg-light text-dark border"><i class="bi bi-clock me-1"></i><?php echo date('F d, Y h:i A', strtotime($activity['activity_date'])); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3 text-end">
            <a href="activities.php" class="btn btn-outline-danger btn-sm rounded-pill px-4">View All</a>
        </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clipboard-x fs-1 opacity-50 mb-3 block"></i>
                <p>No activities found. <a href="activities.php" class="text-decoration-none border-bottom border-danger">Create one</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
