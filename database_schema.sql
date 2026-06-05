-- ==========================================
-- HEATHSYNC AI DATABASE SCHEMA FOR MYSQL
-- Use this script to import into MySQL / MySQL Workbench / DBeaver 
-- to automatically generate the Entity-Relationship Diagram (ERD).
-- ==========================================

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------
-- Table structure for `users`
-- ------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `dob` DATE DEFAULT NULL,
  `gender` VARCHAR(10) DEFAULT NULL,
  `height` DECIMAL(5,2) DEFAULT NULL,
  `weight` DECIMAL(5,2) DEFAULT NULL,
  `blood_type` VARCHAR(5) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `job` VARCHAR(100) DEFAULT NULL,
  `health_goals` TEXT DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `heart_rate` INT DEFAULT NULL,
  `spo2` INT DEFAULT NULL,
  `water_intake` DECIMAL(4,2) DEFAULT NULL,
  `sleep_hours` DECIMAL(4,2) DEFAULT NULL,
  `steps` INT DEFAULT NULL,
  `calories` INT DEFAULT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `health_metrics`
-- ------------------------------------------
DROP TABLE IF EXISTS `health_metrics`;
CREATE TABLE `health_metrics` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `heart_rate` INT DEFAULT NULL,
  `spo2` INT DEFAULT NULL,
  `weight` DECIMAL(5,2) DEFAULT NULL,
  `water_intake` DECIMAL(4,2) DEFAULT NULL,
  `sleep_hours` DECIMAL(4,2) DEFAULT NULL,
  `steps` INT DEFAULT NULL,
  `calories` INT DEFAULT NULL,
  `burned` INT DEFAULT NULL,
  `recorded_at` DATE NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  UNIQUE KEY `user_recorded_date` (`user_id`, `recorded_at`),
  CONSTRAINT `fk_health_metrics_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `workouts`
-- ------------------------------------------
DROP TABLE IF EXISTS `workouts`;
CREATE TABLE `workouts` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `type` VARCHAR(100) NOT NULL,
  `duration_minutes` INT NOT NULL,
  `calories_burned` INT NOT NULL,
  `started_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_workouts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `chat_messages`
-- ------------------------------------------
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `message` TEXT NOT NULL,
  `response` TEXT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_chat_messages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `feedbacks`
-- ------------------------------------------
DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE `feedbacks` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `guest_name` VARCHAR(255) DEFAULT NULL,
  `guest_avatar` VARCHAR(255) DEFAULT NULL,
  `rating` INT DEFAULT NULL,
  `content` TEXT NOT NULL,
  `parent_id` BIGINT UNSIGNED DEFAULT NULL,
  `is_admin_reply` TINYINT(1) DEFAULT 0,
  `likes_count` INT DEFAULT 0,
  `dislikes_count` INT DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_feedbacks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_feedbacks_parent` FOREIGN KEY (`parent_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `feedback_reactions`
-- ------------------------------------------
DROP TABLE IF EXISTS `feedback_reactions`;
CREATE TABLE `feedback_reactions` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `feedback_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `session_id` VARCHAR(255) DEFAULT NULL,
  `type` VARCHAR(10) NOT NULL, -- 'like' or 'dislike'
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  UNIQUE KEY `feedback_user_session` (`feedback_id`, `user_id`, `session_id`),
  CONSTRAINT `fk_reactions_feedback` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `notifications`
-- ------------------------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `type` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `link` VARCHAR(255) DEFAULT NULL,
  `feedback_id` BIGINT UNSIGNED DEFAULT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notifications_feedback` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
