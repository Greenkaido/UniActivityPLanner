<?php
// update_db_v3.php
require_once 'config/database.php';
try {
    $pdo->exec("ALTER TABLE notifications ADD COLUMN IF NOT EXISTS admin_reply TEXT DEFAULT NULL AFTER reply_text");
    echo "<p style='color:green; font-family:sans-serif;'>✅ Database updated: <strong>admin_reply</strong> column added successfully!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
