<?php
// admin/activities.php
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$alert = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'create') {
        $title       = trim($_POST['title']);
        $description = trim($_POST['description']);
        $activity_date = $_POST['activity_date'];
        $notify_all  = isset($_POST['notify_all']); // checkbox

        // Insert Activity
        $stmt = $pdo->prepare("INSERT INTO activities (title, description, activity_date, created_by) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $activity_date, $_SESSION['user_id']])) {

            $notified = 0;
            if ($notify_all) {
                // Fetch all students
                $students = $pdo->query("SELECT id FROM users WHERE role = 'student'")->fetchAll();
                $notif_msg = "📢 New Activity: {$title}\n\n{$description}\n\n📅 Date: " . date('F d, Y h:i A', strtotime($activity_date));
                $notifStmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
                foreach ($students as $s) {
                    $notifStmt->execute([$s['id'], $notif_msg]);
                    $notified++;
                }
            }

            $notif_text = $notify_all ? " and <strong>notified {$notified} student(s)</strong>" : "";
            $alert = "<div class='alert alert-success alert-dismissible fade show rounded-3' role='alert'>
                        <i class='bi bi-check-circle-fill me-2'></i> Activity created successfully{$notif_text}!
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
        }

    } elseif ($action == 'edit') {
        $id = $_POST['id'];
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $activity_date = $_POST['activity_date'];
        $stmt = $pdo->prepare("UPDATE activities SET title = ?, description = ?, activity_date = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $activity_date, $id])) {
            $alert = "<div class='alert alert-success alert-dismissible fade show rounded-3' role='alert'>
                        <i class='bi bi-check-circle-fill me-2'></i> Activity updated successfully!
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
        }

    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ?");
        if ($stmt->execute([$id])) {
            $alert = "<div class='alert alert-danger alert-dismissible fade show rounded-3' role='alert'>
                        <i class='bi bi-trash-fill me-2'></i> Activity deleted.
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
        }
    }
}

// Fetch all activities
$activities = $pdo->query("
    SELECT a.*, u.name as admin_name
    FROM activities a
    LEFT JOIN users u ON a.created_by = u.id
    ORDER BY a.activity_date DESC
")->fetchAll();

// Student count for the notify badge
$studentCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
?>

<style>
.activity-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    transition: all 0.25s ease;
    overflow: hidden;
}
.activity-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.09);
}
.activity-card .card-date-badge {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border-radius: 10px;
    padding: 10px 14px;
    text-align: center;
    min-width: 60px;
}
.activity-card .card-date-badge .day {
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1;
}
.activity-card .card-date-badge .month {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    opacity: 0.9;
}
.notify-toggle {
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s;
}
.notify-toggle:hover {
    background: #dcfce7;
    border-color: #86efac;
}
.notify-toggle input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #22c55e;
    cursor: pointer;
}
.modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}
.modal-header {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border-radius: 16px 16px 0 0;
    border-bottom: none;
    padding: 20px 24px;
}
.modal-header .btn-close {
    filter: invert(1);
    opacity: 0.8;
}
.modal-body {
    padding: 24px;
}
.modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 16px 24px;
}
.form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: #475569;
    margin-bottom: 6px;
}
.form-control {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.9rem;
    transition: border-color 0.2s;
}
.form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220,53,69,0.1);
}
.empty-activities {
    text-align: center;
    padding: 80px 20px;
    color: #94a3b8;
}
.empty-activities i {
    font-size: 4rem;
    display: block;
    margin-bottom: 16px;
    opacity: 0.3;
}
</style>

<?php echo $alert; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color:#0f172a;">Manage Activities</h2>
        <p class="text-muted mb-0 small">Create and manage university events. Notify all students instantly.</p>
    </div>
    <button class="btn btn-danger rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-circle me-2"></i> New Activity
    </button>
</div>

<!-- Activities List -->
<?php if ($activities): ?>
<div class="row g-4">
    <?php foreach ($activities as $act): ?>
    <div class="col-12">
        <div class="activity-card p-4">
            <div class="d-flex align-items-start gap-4">
                <!-- Date Badge -->
                <div class="card-date-badge flex-shrink-0">
                    <div class="day"><?php echo date('d', strtotime($act['activity_date'])); ?></div>
                    <div class="month"><?php echo date('M Y', strtotime($act['activity_date'])); ?></div>
                </div>
                <!-- Content -->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($act['title']); ?></h5>
                            <p class="text-muted mb-2 small" style="max-width:600px;"><?php echo htmlspecialchars($act['description']); ?></p>
                            <div class="d-flex gap-3">
                                <span class="small text-muted"><i class="bi bi-clock me-1"></i><?php echo date('h:i A', strtotime($act['activity_date'])); ?></span>
                                <span class="small text-muted"><i class="bi bi-person-fill me-1"></i>By <?php echo htmlspecialchars($act['admin_name']); ?></span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 ms-3 flex-shrink-0">
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal<?php echo $act['id']; ?>">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Delete this activity permanently?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $act['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal<?php echo $act['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Activity</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?php echo $act['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Activity Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($act['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($act['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date &amp; Time</label>
                            <input type="datetime-local" name="activity_date" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($act['activity_date'])); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php else: ?>
<div class="activity-card empty-activities">
    <i class="bi bi-calendar-x"></i>
    <h5 class="fw-semibold text-dark">No activities yet</h5>
    <p class="small">Click "New Activity" to create your first event.</p>
</div>
<?php endif; ?>

<!-- Create Activity Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Create New Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label class="form-label">Activity Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Annual Tech Symposium" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe what will happen at this event..." required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Date &amp; Time</label>
                        <input type="datetime-local" name="activity_date" class="form-control" required>
                    </div>

                    <!-- Notify All Students Toggle -->
                    <label class="notify-toggle d-flex align-items-center gap-3 w-100 user-select-none">
                        <input type="checkbox" name="notify_all" id="notifyAll" checked>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size:0.95rem;">
                                <i class="bi bi-megaphone-fill text-success me-1"></i>
                                Notify All Students
                            </div>
                            <div class="small text-muted mt-1">
                                A notification will be sent to all
                                <strong class="text-success"><?php echo $studentCount; ?> registered student(s)</strong>
                                about this activity.
                            </div>
                        </div>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Create &amp; Notify
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
