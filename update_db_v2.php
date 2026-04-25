<?php
// update_db_v2.php
require_once 'config/database.php';

try {
    // Create attendance table
    $sql = "CREATE TABLE IF NOT EXISTS `attendance` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `activity_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `status` enum('present','absent') NOT NULL DEFAULT 'absent',
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `activity_student` (`activity_id`, `user_id`),
        CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
        CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "Attendance table created successfully!\n";

} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
?>
