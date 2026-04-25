<?php
// student/activities.php
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit;
}

$activities = $pdo->query("SELECT a.*, u.name as admin_name FROM activities a LEFT JOIN users u ON a.created_by = u.id ORDER BY a.activity_date DESC")->fetchAll();
?>

<div class="mb-4">
    <h2>All Activities</h2>
    <p class="text-muted">Browse all activities posted by the university administration.</p>
</div>

<div class="row">
    <?php foreach($activities as $act): ?>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title text-success"><?php echo htmlspecialchars($act['title']); ?></h5>
                <h6 class="card-subtitle mb-3 text-muted">
                    <span class="badge bg-secondary"><?php echo date('F d, Y h:i A', strtotime($act['activity_date'])); ?></span>
                </h6>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($act['description'])); ?></p>
            </div>
            <div class="card-footer bg-white text-muted small">
                Posted by <?php echo htmlspecialchars($act['admin_name']); ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php if(empty($activities)): ?>
    <div class="col-12">
        <div class="alert alert-success">No activities have been posted yet.</div>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
