<?php
// admin/sent_notifications.php
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Handle notification deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $notif_id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ?");
    if($stmt->execute([$notif_id])) {
        // Success message handled below
    }
}

// Fetch all notifications with user details
$query = "SELECT n.*, u.name as student_name, u.email as student_email 
          FROM notifications n 
          JOIN users u ON n.user_id = u.id 
          ORDER BY n.created_at DESC";
$notifications = $pdo->query($query)->fetchAll();
?>

<div class="mb-4">
    <h2>Sent Notifications & Feedback</h2>
    <p class="text-muted">Track student status and text replies to your messages.</p>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Reaction</th>
                        <th>Reply</th>
                        <th>Sent At</th>
                        <th class="pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($notifications): ?>
                        <?php foreach ($notifications as $n): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold"><?php echo htmlspecialchars($n['student_name']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($n['student_email']); ?></div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;" title="<?php echo htmlspecialchars($n['message']); ?>">
                                    <?php echo htmlspecialchars($n['message']); ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?php echo $n['status'] === 'read' ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo ucfirst($n['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if(isset($n['reaction_emoji']) && $n['reaction_emoji']): ?>
                                    <span class="fs-4 px-2 py-1 rounded bg-light border d-inline-block"><?php echo $n['reaction_emoji']; ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">None</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($n['reply_text']) && $n['reply_text']): ?>
                                    <div class="small bg-light p-2 rounded border" style="max-width: 200px;">
                                        <?php echo nl2br(htmlspecialchars($n['reply_text'])); ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">No reply yet</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted">
                                <?php echo date('M d, Y h:i A', strtotime($n['created_at'])); ?>
                            </td>
                            <td class="pe-4">
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $n['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No notifications sent yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
