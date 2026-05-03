CREATE TABLE IF NOT EXISTS `emp_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_day_hours` decimal(5,2) DEFAULT '8.00',
  `half_day_hours` decimal(5,2) DEFAULT '4.00',
  `slip_bg_image` varchar(255) DEFAULT NULL,
  `slip_text_positions` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `emp_settings` (`full_day_hours`, `half_day_hours`) VALUES (8.00, 4.00)
ON DUPLICATE KEY UPDATE `full_day_hours`=VALUES(`full_day_hours`);

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_no` varchar(50) NOT NULL UNIQUE,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `base_salary` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `emp_attendance_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_no` varchar(50) NOT NULL,
  `log_time` datetime NOT NULL,
  `in_out` tinyint(1) NOT NULL COMMENT '0=In, 1=Out',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_log` (`emp_no`, `log_time`, `in_out`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `emp_daily_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_no` varchar(50) NOT NULL,
  `log_date` date NOT NULL,
  `total_hours` decimal(5,2) DEFAULT '0.00',
  `status` enum('Full Day','Half Day','Absent') DEFAULT 'Absent',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_daily` (`emp_no`, `log_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `emp_salary_slips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_no` varchar(50) NOT NULL,
  `month` tinyint(2) NOT NULL,
  `year` int(4) NOT NULL,
  `total_days` int(11) DEFAULT '0',
  `full_days` int(11) DEFAULT '0',
  `half_days` int(11) DEFAULT '0',
  `absents` int(11) DEFAULT '0',
  `final_salary` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slip` (`emp_no`, `month`, `year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
