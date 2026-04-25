<?php
// clear_hackathon.php
require_once 'config/database.php';

try {
    // Find notifications containing 'Hackathon' or related text
    $stmt = $pdo->prepare("SELECT id, message, reaction_emoji, reply_text FROM notifications WHERE message LIKE '%Hackathon%'");
    $stmt->execute();
    $notifications = $stmt->fetchAll();

    if ($notifications) {
        foreach ($notifications as $n) {
            echo "Found: " . $n['message'] . " (ID: " . $n['id'] . ")\n";
            // Clear feedback
            $update = $pdo->prepare("UPDATE notifications SET reaction_emoji = NULL, reply_text = NULL WHERE id = ?");
            $update->execute([$n['id']]);
            echo "Cleared feedback for ID: " . $n['id'] . "\n";
        }
    } else {
        echo "No notifications found matching 'Hackathon'.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
