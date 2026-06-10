-- ==========================================
-- HEATHSYNC AI DATABASE SCHEMA FOR MYSQL
-- Use this script to import into MySQL / MySQL Workbench / DBeaver 
-- to automatically generate the Entity-Relationship Diagram (ERD).
-- Last updated: 2026-06-10
-- ==========================================
CREATE DATABASE IF NOT EXISTS healthsync;
USE healthsync;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------
-- Table structure for `users`
-- ------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`               VARCHAR(255) NOT NULL,
  `email`              VARCHAR(255) NOT NULL UNIQUE,
  `email_verified_at`  TIMESTAMP NULL DEFAULT NULL,
  `password`           VARCHAR(255) NOT NULL,
  `role`               ENUM('user','doctor','admin') NOT NULL DEFAULT 'user',
  `plan`               VARCHAR(50) NOT NULL DEFAULT 'Free',
  `status`             ENUM('active','inactive','blocked') NOT NULL DEFAULT 'active',
  -- Profile fields
  `phone`              VARCHAR(20) DEFAULT NULL,
  `dob`                DATE DEFAULT NULL,
  `gender`             VARCHAR(10) DEFAULT NULL,
  `height`             INT DEFAULT NULL,            -- in cm
  `weight`             INT DEFAULT NULL,            -- in kg
  `blood_type`         VARCHAR(5) DEFAULT NULL,
  `address`            TEXT DEFAULT NULL,
  `job`                VARCHAR(100) DEFAULT NULL,
  `health_goals`       TEXT DEFAULT NULL,
  `avatar`             VARCHAR(255) DEFAULT NULL,
  -- Quick-access health metrics snapshot
  `heart_rate`         INT DEFAULT NULL,
  `spo2`               INT DEFAULT NULL,
  `water_intake`       DECIMAL(5,2) DEFAULT NULL,  -- in Liters
  `sleep_hours`        DECIMAL(5,2) DEFAULT NULL,
  `steps`              INT DEFAULT NULL,
  `calories`           INT DEFAULT NULL,
  `remember_token`     VARCHAR(100) DEFAULT NULL,
  `created_at`         TIMESTAMP NULL DEFAULT NULL,
  `updated_at`         TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `password_reset_tokens`
-- ------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email`       VARCHAR(255) NOT NULL PRIMARY KEY,
  `token`       VARCHAR(255) NOT NULL,
  `created_at`  TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `sessions`
-- ------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id`            VARCHAR(255) NOT NULL PRIMARY KEY,
  `user_id`       BIGINT UNSIGNED DEFAULT NULL,
  `ip_address`    VARCHAR(45) DEFAULT NULL,
  `user_agent`    TEXT DEFAULT NULL,
  `payload`       LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  INDEX `sessions_user_id_index` (`user_id`),
  INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `doctors`
-- ------------------------------------------
DROP TABLE IF EXISTS `doctors`;
CREATE TABLE `doctors` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `specialty`   VARCHAR(255) NOT NULL,
  `email`       VARCHAR(255) DEFAULT NULL UNIQUE,
  `phone`       VARCHAR(20) DEFAULT NULL,
  `password`    VARCHAR(255) DEFAULT NULL,
  `avatar`      VARCHAR(255) DEFAULT NULL,
  `place`       VARCHAR(255) DEFAULT NULL,
  `address`     TEXT DEFAULT NULL,
  `status`      VARCHAR(50) NOT NULL DEFAULT 'active',
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL,
  INDEX `doctors_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `health_metrics`
-- ------------------------------------------
DROP TABLE IF EXISTS `health_metrics`;
CREATE TABLE `health_metrics` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `heart_rate`   INT DEFAULT NULL,
  `spo2`         INT DEFAULT NULL,
  `weight`       DECIMAL(5,2) DEFAULT NULL,
  `water_intake` DECIMAL(4,2) DEFAULT NULL,
  `sleep_hours`  DECIMAL(4,2) DEFAULT NULL,
  `steps`        INT DEFAULT NULL,
  `calories`     INT DEFAULT NULL,            -- Calories consumed
  `burned`       INT DEFAULT NULL,            -- Calories burned
  `recorded_at`  DATE NOT NULL,
  `created_at`   TIMESTAMP NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP NULL DEFAULT NULL,
  UNIQUE KEY `user_recorded_date` (`user_id`, `recorded_at`),
  CONSTRAINT `fk_health_metrics_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `workout_plans`
-- ------------------------------------------
DROP TABLE IF EXISTS `workout_plans`;
CREATE TABLE `workout_plans` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `workouts`
-- ------------------------------------------
DROP TABLE IF EXISTS `workouts`;
CREATE TABLE `workouts` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`          BIGINT UNSIGNED NOT NULL,
  `type`             VARCHAR(100) NOT NULL,
  `duration_minutes` INT NOT NULL,
  `calories_burned`  INT NOT NULL,
  `started_at`       DATETIME NOT NULL,
  `created_at`       TIMESTAMP NULL DEFAULT NULL,
  `updated_at`       TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_workouts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `appointments`
-- ------------------------------------------
DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`          BIGINT UNSIGNED NOT NULL,
  `doctor_id`        BIGINT UNSIGNED DEFAULT NULL,
  `doctor_name`      VARCHAR(255) NOT NULL,
  `specialty`        VARCHAR(255) NOT NULL,
  `appointment_date` DATETIME NOT NULL,
  `proposed_date`    DATETIME DEFAULT NULL,       -- Doctor's proposed reschedule date
  `status`           VARCHAR(50) NOT NULL DEFAULT 'scheduled',
  --   Values: scheduled | completed | canceled | rescheduled_pending
  `created_at`       TIMESTAMP NULL DEFAULT NULL,
  `updated_at`       TIMESTAMP NULL DEFAULT NULL,
  INDEX `appointments_user_id_index` (`user_id`),
  INDEX `appointments_appointment_date_index` (`appointment_date`),
  INDEX `appointments_status_index` (`status`),
  CONSTRAINT `fk_appointments_user`   FOREIGN KEY (`user_id`)   REFERENCES `users`   (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_appointments_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `medical_records`
-- ------------------------------------------
DROP TABLE IF EXISTS `medical_records`;
CREATE TABLE `medical_records` (
  `id`                      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_id`               BIGINT UNSIGNED NOT NULL,
  `user_id`                 BIGINT UNSIGNED NOT NULL,
  `diagnosis`               TEXT NOT NULL,
  `symptoms`                TEXT NOT NULL,
  `exam_result`             TEXT DEFAULT NULL,
  `prescribed_medicine`     TEXT DEFAULT NULL,
  `treatment_instructions`  TEXT DEFAULT NULL,
  `notes`                   TEXT DEFAULT NULL,
  `recorded_at`             DATETIME NOT NULL,
  `created_at`              TIMESTAMP NULL DEFAULT NULL,
  `updated_at`              TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_medical_records_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_medical_records_user`   FOREIGN KEY (`user_id`)   REFERENCES `users`   (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `consultations`
-- ------------------------------------------
DROP TABLE IF EXISTS `consultations`;
CREATE TABLE `consultations` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `sender`      ENUM('doctor','patient') NOT NULL,
  `message`     TEXT NOT NULL,
  `file_path`   VARCHAR(255) DEFAULT NULL,
  `file_type`   VARCHAR(50) DEFAULT NULL,
  `is_read`     TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_consultations_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_consultations_user`   FOREIGN KEY (`user_id`)   REFERENCES `users`   (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `meal_plans`
-- ------------------------------------------
DROP TABLE IF EXISTS `meal_plans`;
CREATE TABLE `meal_plans` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`        VARCHAR(255) NOT NULL,
  `description`  TEXT DEFAULT NULL,
  `calories`     INT DEFAULT NULL,
  `tags`         JSON DEFAULT NULL,
  `doctor_id`    BIGINT UNSIGNED DEFAULT NULL,
  `patient_id`   BIGINT UNSIGNED DEFAULT NULL,
  `is_template`  TINYINT(1) NOT NULL DEFAULT 0,
  `days`         JSON DEFAULT NULL,              -- Structured daily meals
  `created_at`   TIMESTAMP NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP NULL DEFAULT NULL,
  INDEX `meal_plans_doctor_id_index`  (`doctor_id`),
  INDEX `meal_plans_patient_id_index` (`patient_id`),
  CONSTRAINT `fk_meal_plans_doctor`  FOREIGN KEY (`doctor_id`)  REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_meal_plans_patient` FOREIGN KEY (`patient_id`) REFERENCES `users`   (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `doctor_recommendations`
-- ------------------------------------------
DROP TABLE IF EXISTS `doctor_recommendations`;
CREATE TABLE `doctor_recommendations` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `advice`      TEXT NOT NULL,
  `meals`       JSON DEFAULT NULL,
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_recommendations_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_recommendations_user`   FOREIGN KEY (`user_id`)   REFERENCES `users`   (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `chat_messages`
-- ------------------------------------------
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `message`     TEXT NOT NULL,
  `response`    TEXT NOT NULL,
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_chat_messages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `feedbacks`
-- ------------------------------------------
DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE `feedbacks` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`        BIGINT UNSIGNED DEFAULT NULL,
  `guest_name`     VARCHAR(255) DEFAULT NULL,
  `guest_avatar`   VARCHAR(255) DEFAULT NULL,
  `rating`         INT DEFAULT NULL,
  `content`        TEXT NOT NULL,
  `parent_id`      BIGINT UNSIGNED DEFAULT NULL,
  `is_admin_reply` TINYINT(1) NOT NULL DEFAULT 0,
  `likes_count`    INT NOT NULL DEFAULT 0,
  `dislikes_count` INT NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT `fk_feedbacks_user`   FOREIGN KEY (`user_id`)   REFERENCES `users`      (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_feedbacks_parent` FOREIGN KEY (`parent_id`) REFERENCES `feedbacks`  (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `feedback_reactions`
-- ------------------------------------------
DROP TABLE IF EXISTS `feedback_reactions`;
CREATE TABLE `feedback_reactions` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `feedback_id` BIGINT UNSIGNED NOT NULL,
  `user_id`     BIGINT UNSIGNED DEFAULT NULL,
  `session_id`  VARCHAR(255) DEFAULT NULL,
  `type`        VARCHAR(10) NOT NULL,           -- 'like' or 'dislike'
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL,
  UNIQUE KEY `feedback_user_session` (`feedback_id`, `user_id`, `session_id`),
  CONSTRAINT `fk_reactions_feedback` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reactions_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`     (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `notifications`
-- ------------------------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `type`        VARCHAR(100) NOT NULL,
  -- notification types: admin_reply | admin_like | admin_dislike | user_reply | user_like
  -- appointment types: appointment_confirmed | appointment_canceled | reschedule_proposed | reschedule_accepted | reschedule_declined
  `title`       VARCHAR(255) NOT NULL,
  `message`     TEXT NOT NULL,
  `link`        VARCHAR(255) DEFAULT NULL,      -- URL to navigate when clicked
  `feedback_id` BIGINT UNSIGNED DEFAULT NULL,
  `is_read`     TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL DEFAULT NULL,
  INDEX `notifications_user_read_index` (`user_id`, `is_read`),
  INDEX `notifications_created_at_index` (`created_at`),
  CONSTRAINT `fk_notifications_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`     (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notifications_feedback` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `personal_access_tokens`
-- ------------------------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `tokenable_type` VARCHAR(255) NOT NULL,
  `tokenable_id`   BIGINT UNSIGNED NOT NULL,
  `name`           VARCHAR(255) NOT NULL,
  `token`          VARCHAR(64) NOT NULL UNIQUE,
  `abilities`      TEXT DEFAULT NULL,
  `last_used_at`   TIMESTAMP NULL DEFAULT NULL,
  `expires_at`     TIMESTAMP NULL DEFAULT NULL,
  `created_at`     TIMESTAMP NULL DEFAULT NULL,
  `updated_at`     TIMESTAMP NULL DEFAULT NULL,
  INDEX `personal_access_tokens_tokenable_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `cache`
-- ------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key`        VARCHAR(255) NOT NULL PRIMARY KEY,
  `value`      MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `cache_locks`
-- ------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key`        VARCHAR(255) NOT NULL PRIMARY KEY,
  `owner`      VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for `jobs`
-- ------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `queue`        VARCHAR(255) NOT NULL,
  `payload`      LONGTEXT NOT NULL,
  `attempts`     TINYINT UNSIGNED NOT NULL,
  `reserved_at`  INT UNSIGNED DEFAULT NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at`   INT UNSIGNED NOT NULL,
  INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
