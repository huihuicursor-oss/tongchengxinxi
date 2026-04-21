CREATE DATABASE IF NOT EXISTS `elderly_care` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `elderly_care`;

CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role_name` VARCHAR(50) NOT NULL,
  `real_name` VARCHAR(50) NOT NULL,
  `status` TINYINT NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `elder_profile` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `gender` ENUM('男','女') NOT NULL,
  `age` TINYINT UNSIGNED NOT NULL,
  `room_no` VARCHAR(20) NOT NULL,
  `care_level` VARCHAR(50) NOT NULL,
  `contact_person` VARCHAR(50) NOT NULL,
  `contact_phone` VARCHAR(20) NOT NULL,
  `medical_tags` VARCHAR(255) DEFAULT NULL,
  `admission_date` DATE DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `caregiver` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `role_name` VARCHAR(50) NOT NULL,
  `team_name` VARCHAR(50) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `status` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `health_record` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `elder_id` INT UNSIGNED NOT NULL,
  `blood_pressure` VARCHAR(20) DEFAULT NULL,
  `blood_oxygen` VARCHAR(10) DEFAULT NULL,
  `heart_rate` SMALLINT UNSIGNED DEFAULT NULL,
  `temperature` DECIMAL(4,1) DEFAULT NULL,
  `sleep_hours` DECIMAL(4,1) DEFAULT NULL,
  `risk_level` VARCHAR(20) NOT NULL DEFAULT '正常',
  `recorded_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_elder_recorded_at` (`elder_id`, `recorded_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `alert_event` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `elder_id` INT UNSIGNED NOT NULL,
  `alert_type` VARCHAR(50) NOT NULL,
  `alert_level` VARCHAR(20) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT '待处理',
  `handler_name` VARCHAR(50) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_alert_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `service_order` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_name` VARCHAR(100) NOT NULL,
  `service_time` DATETIME NOT NULL,
  `target_area` VARCHAR(100) NOT NULL,
  `owner_name` VARCHAR(50) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT '待执行',
  `remark` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `visit_record` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `elder_id` INT UNSIGNED NOT NULL,
  `visitor_name` VARCHAR(50) NOT NULL,
  `relation_name` VARCHAR(50) NOT NULL,
  `visit_time` DATETIME NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT '待审核',
  `checkin_time` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `operation_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_name` VARCHAR(50) NOT NULL,
  `operator_name` VARCHAR(50) NOT NULL,
  `action_desc` VARCHAR(255) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin_user` (`username`, `password_hash`, `role_name`, `real_name`) VALUES
('admin', '$2y$10$demo.hash.replace.in.production', '超级管理员', '系统管理员');

INSERT INTO `elder_profile` (`name`, `gender`, `age`, `room_no`, `care_level`, `contact_person`, `contact_phone`, `medical_tags`, `admission_date`) VALUES
('王淑珍', '女', 82, 'A-203', '二级护理', '王磊', '13800000001', '高血压,睡眠监测', '2025-09-15'),
('李建国', '男', 76, 'B-112', '一级护理', '李婷', '13800000002', '糖尿病,营养餐', '2025-10-03'),
('周桂芳', '女', 79, 'C-305', '失能照护', '周洋', '13800000003', '认知训练,离床监测', '2026-01-08');

INSERT INTO `caregiver` (`name`, `role_name`, `team_name`, `phone`) VALUES
('张敏', '护工', '护理组A', '13800001001'),
('赵琳', '康复师', '康复组', '13800001002'),
('王涛', '调度员', '运营组', '13800001003');

INSERT INTO `health_record` (`elder_id`, `blood_pressure`, `blood_oxygen`, `heart_rate`, `temperature`, `sleep_hours`, `risk_level`, `recorded_at`) VALUES
(1, '150/92', '96%', 105, 36.8, 5.5, '预警', '2026-04-21 08:40:00'),
(2, '132/78', '98%', 82, 36.6, 7.0, '正常', '2026-04-21 08:35:00'),
(3, '142/90', '95%', 96, 36.7, 6.0, '关注', '2026-04-21 09:12:00');

INSERT INTO `alert_event` (`elder_id`, `alert_type`, `alert_level`, `description`, `status`, `handler_name`, `created_at`) VALUES
(1, '血压异常', '高', '收缩压连续两次超过 150', '处理中', '刘颖', '2026-04-21 08:40:00'),
(3, '离床超时', '中', '离床 20 分钟未返回', '待处理', NULL, '2026-04-21 09:12:00');

INSERT INTO `service_order` (`service_name`, `service_time`, `target_area`, `owner_name`, `status`, `remark`) VALUES
('晨检测温', '2026-04-21 08:00:00', '全院', '护理组 A', '已完成', '系统自动生成晨检任务'),
('康复训练', '2026-04-21 09:30:00', '康复专区', '赵琳', '执行中', '重点关注膝关节恢复老人'),
('文娱活动', '2026-04-21 15:00:00', '活动室', '社工组', '待执行', '书法与音乐疗愈活动');

INSERT INTO `visit_record` (`elder_id`, `visitor_name`, `relation_name`, `visit_time`, `status`, `checkin_time`) VALUES
(1, '王磊', '儿子', '2026-04-21 14:30:00', '已预约', NULL),
(2, '李婷', '女儿', '2026-04-21 15:00:00', '待审核', NULL),
(3, '周洋', '孙子', '2026-04-21 16:20:00', '已到访', '2026-04-21 16:18:00');

INSERT INTO `operation_log` (`module_name`, `operator_name`, `action_desc`, `ip_address`, `created_at`) VALUES
('健康监测', '护士长-刘颖', '处理高血压预警', '127.0.0.1', '2026-04-21 08:45:00'),
('老人档案', '客服-陈静', '更新紧急联系人', '127.0.0.1', '2026-04-21 09:05:00'),
('服务调度', '调度员-王涛', '新增康复训练排班', '127.0.0.1', '2026-04-21 09:18:00');
