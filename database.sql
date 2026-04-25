CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin User', 'admin@uni.com', '0192023a7bbd73250516f069df18b500', 'admin'),
(2, 'name', 'student@uni.com', '24d6d60a16b9b3e1a0b3b429bd44dd55', 'student'),
(3, 'Jane Smith', 'jane.smith@uni.com', '24d6d60a16b9b3e1a0b3b429bd44dd55', 'student');

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `activity_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `activities` (`id`, `title`, `description`, `activity_date`, `created_by`) VALUES
(1, 'Tech Symposium 2024', 'Annual technology symposium featuring guest talks and project demos.', '2024-10-15 10:00:00', 1),
(2, 'Sports Meet', 'Inter-department sports competitions.', '2024-11-05 09:00:00', 1);

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `reaction_emoji` varchar(50) DEFAULT NULL,
  `reply_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `notifications` (`id`, `user_id`, `message`, `status`, `created_at`) VALUES
(1, 2, 'Welcome to UniActivity Planner, name!', 'unread', '2024-05-01 10:00:00'),
(2, 3, 'Welcome to UniActivity Planner, Jane!', 'unread', '2024-05-01 10:05:00');
