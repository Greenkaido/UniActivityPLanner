<?php
// update_db.php
require_once 'config/database.php';

try {
    // Check for reply_text
    $query = $pdo->query("SHOW COLUMNS FROM notifications LIKE 'reply_text'");
    if (!$query->fetch()) {
        $pdo->exec("ALTER TABLE notifications ADD COLUMN reply_text TEXT DEFAULT NULL AFTER status");
    }

    // Check for reaction_emoji
    $query = $pdo->query("SHOW COLUMNS FROM notifications LIKE 'reaction_emoji'");
    if (!$query->fetch()) {
        $pdo->exec("ALTER TABLE notifications ADD COLUMN reaction_emoji VARCHAR(50) DEFAULT NULL AFTER status");
    }

    echo "<h2 style='color: green;'>Success! Database schema has been fully updated.</h2>";
    
    echo "<p>You can now go back to <a href='student/notifications.php'>Notifications</a> and try replying again.</p>";
    echo "<p style='color: red;'><strong>Important:</strong> Please delete this file (update_db.php) after use for security.</p>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Error updating database:</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>
