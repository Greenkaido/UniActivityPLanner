<?php
// student/notifications.php
require_once '../config/database.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mark all unread as read upon visiting
$stmt = $pdo->prepare("UPDATE notifications SET status = 'read' WHERE user_id = ? AND status = 'unread'");
$stmt->execute([$user_id]);

// Handle Reaction or Reply submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $notif_id = intval($_POST['notif_id']);

        if ($_POST['action'] == 'submit_reply') {
            $reply = trim($_POST['reply_text']);
            $stmt = $pdo->prepare("UPDATE notifications SET reply_text = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$reply, $notif_id, $user_id]);
        } elseif ($_POST['action'] == 'submit_reaction') {
            $emoji = $_POST['emoji'];
            $stmt = $pdo->prepare("UPDATE notifications SET reaction_emoji = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$emoji, $notif_id, $user_id]);
        }

        header("Location: notifications.php");
        exit;
    }
}

// Fetch notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();
?>

<style>
.notif-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: box-shadow 0.2s ease;
}
.notif-card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}
.notif-header {
    padding: 16px 20px;
    background: linear-gradient(135deg, #f8fffe, #f0fdf4);
    border-bottom: 1px solid #d1fae5;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.notif-body {
    padding: 18px 20px;
}
.notif-message {
    font-size: 1rem;
    color: #1e293b;
    line-height: 1.6;
    margin-bottom: 0;
}
.my-reply-box {
    background: #f0fdf4;
    border-left: 4px solid #22c55e;
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 10px;
}
.my-reply-box .label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    color: #16a34a;
    text-transform: uppercase;
    margin-bottom: 4px;
}
.my-reply-box p {
    font-size: 0.9rem;
    color: #166534;
    margin: 0;
}
.emoji-btn {
    border: 2px solid #e2e8f0;
    background: #fff;
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.2s;
    line-height: 1.6;
}
.emoji-btn:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
    transform: scale(1.05);
}
.emoji-btn.active {
    background: #d1fae5;
    border-color: #22c55e;
    box-shadow: 0 0 0 2px rgba(34,197,94,0.2);
}
.reply-area {
    padding: 14px 20px 18px;
    border-top: 1px solid #f1f5f9;
}
.reply-input-group {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}
.reply-input-group input {
    flex: 1;
    border: 1.5px solid #e2e8f0;
    border-radius: 50px;
    padding: 8px 18px;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.2s;
}
.reply-input-group input:focus {
    border-color: #22c55e;
}
.reply-input-group button {
    border-radius: 50px;
    padding: 8px 22px;
    font-weight: 600;
    font-size: 0.85rem;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}
.empty-state i {
    font-size: 3rem;
    display: block;
    margin-bottom: 12px;
    opacity: 0.4;
}
</style>

<div class="mb-4">
    <h2 class="fw-bold" style="color:#0f172a;">📬 Your Notifications</h2>
    <p class="text-muted">Messages from the administration and their responses.</p>
</div>

<?php if ($notifications): ?>
    <?php foreach ($notifications as $notif): ?>
    <div class="notif-card">
        <!-- Header -->
        <div class="notif-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-megaphone-fill text-success"></i>
                <span class="fw-semibold text-success small">From Admin</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if ($notif['reaction_emoji']): ?>
                    <span class="fs-5" title="Your reaction"><?php echo $notif['reaction_emoji']; ?></span>
                <?php endif; ?>
                <span class="badge <?php echo $notif['status'] === 'read' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'; ?> rounded-pill px-2">
                    <?php echo ucfirst($notif['status']); ?>
                </span>
                <span class="small text-muted"><?php echo date('M d, Y · h:i A', strtotime($notif['created_at'])); ?></span>
            </div>
        </div>

        <!-- Message Body -->
        <div class="notif-body">
            <p class="notif-message"><?php echo nl2br(htmlspecialchars($notif['message'])); ?></p>

            <!-- Student's existing reply preview -->
            <?php if (!empty($notif['reply_text'])): ?>
            <div class="my-reply-box">
                <div class="label"><i class="bi bi-chat-fill me-1"></i> Your Reply</div>
                <p><?php echo nl2br(htmlspecialchars($notif['reply_text'])); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Reply Area -->
        <div class="reply-area">
            <!-- Quick Reactions -->
            <div class="mb-3">
                <label class="small text-muted fw-semibold">Quick Reaction:</label>
                <div class="d-flex gap-2 mt-1 flex-wrap">
                    <?php
                    $emojis = ['👍', '❤️', '😂', '🚀', '✅'];
                    foreach ($emojis as $e):
                        $isActive = (isset($notif['reaction_emoji']) && $notif['reaction_emoji'] === $e);
                    ?>
                    <form method="POST" class="m-0">
                        <input type="hidden" name="action" value="submit_reaction">
                        <input type="hidden" name="notif_id" value="<?php echo $notif['id']; ?>">
                        <input type="hidden" name="emoji" value="<?php echo $e; ?>">
                        <button type="submit" class="emoji-btn <?php echo $isActive ? 'active' : ''; ?>"><?php echo $e; ?></button>
                    </form>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Text Reply Form -->
            <label class="small text-muted fw-semibold">
                <?php echo !empty($notif['reply_text']) ? 'Update Your Reply:' : 'Write a Reply:'; ?>
            </label>
            <form method="POST">
                <input type="hidden" name="action" value="submit_reply">
                <input type="hidden" name="notif_id" value="<?php echo $notif['id']; ?>">
                <div class="reply-input-group">
                    <input
                        type="text"
                        name="reply_text"
                        placeholder="Type your reply here..."
                        value="<?php echo htmlspecialchars($notif['reply_text'] ?? ''); ?>"
                        required
                    >
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send me-1"></i> Send
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="empty-state">
        <i class="bi bi-bell-slash"></i>
        <h5>No notifications yet</h5>
        <p class="small">The admin hasn't sent you any messages yet.</p>
    </div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
