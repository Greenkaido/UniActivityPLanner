<?php
// clear_notifications.php
require_once 'config/database.php';

try {
    // This will clear all notifications from the table
    $pdo->exec("TRUNCATE TABLE notifications");
    echo "<h2 style='color: green;'>Success! All test notifications have been deleted.</h2>";
    echo "<p>The notification list is now empty and clean.</p>";
    echo "<p>Go back to <a href='admin/sent_notifications.php'>Sent Notifications</a> to verify.</p>";
    echo "<p style='color: red;'><strong>Important:</strong> Please delete this file (clear_notifications.php) after use.</p>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>Error:</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>
