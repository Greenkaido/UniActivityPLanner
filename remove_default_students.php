<?php
// remove_default_students.php
require_once 'config/database.php';
try {
    // Delete default students by their seeded emails
    $stmt = $pdo->prepare("DELETE FROM users WHERE email IN ('student@uni.com', 'jane.smith@uni.com') AND role = 'student'");
    $stmt->execute();
    $count = $stmt->rowCount();
    echo "<p style='font-family:sans-serif;color:green;'>✅ Done! Removed <strong>$count</strong> default student(s) from the database.</p>";
    echo "<p style='font-family:sans-serif;color:#555;'>All related notifications and attendance records were also removed automatically (CASCADE).</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
