-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 01:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demoapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `work_date` date DEFAULT NULL,
  `status` enum('Present','Absent','Late','Leave') DEFAULT 'Present',
  `overtime_hours` int(11) DEFAULT 0,
  `ot` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `emp_id`, `work_date`, `status`, `overtime_hours`, `ot`) VALUES
(452, 66143441, '2025-09-01', 'Present', 0, 0),
(453, 66143441, '2025-09-02', 'Present', 0, 0),
(454, 66143441, '2025-09-03', 'Present', 0, 0),
(455, 66143441, '2025-09-04', 'Present', 0, 15),
(456, 66143441, '2025-09-05', 'Present', 0, 0),
(457, 66143441, '2025-09-06', 'Present', 0, 0),
(458, 66143441, '2025-09-07', 'Present', 0, 0),
(459, 66143441, '2025-09-08', 'Present', 0, 0),
(460, 66143441, '2025-09-09', 'Present', 0, 0),
(461, 66143441, '2025-09-10', 'Present', 0, 0),
(462, 66143441, '2025-09-11', 'Present', 0, 0),
(463, 66143441, '2025-09-12', 'Present', 0, 0),
(464, 66143441, '2025-09-13', 'Present', 0, 0),
(465, 66143441, '2025-09-14', 'Present', 0, 0),
(466, 66143441, '2025-09-15', 'Present', 0, 0),
(467, 66143441, '2025-09-16', 'Present', 0, 0),
(468, 66143441, '2025-09-17', 'Present', 0, 0),
(469, 66143441, '2025-09-18', 'Present', 0, 0),
(470, 66143441, '2025-09-19', 'Present', 0, 0),
(471, 66143441, '2025-09-20', 'Present', 0, 0),
(472, 66143441, '2025-09-21', 'Present', 0, 0),
(473, 66143441, '2025-09-22', 'Present', 0, 0),
(474, 66143441, '2025-09-23', 'Present', 0, 0),
(475, 66143441, '2025-09-24', 'Present', 0, 0),
(476, 66143441, '2025-09-25', 'Present', 0, 0),
(477, 66143441, '2025-09-26', 'Present', 0, 0),
(478, 66143441, '2025-09-27', 'Present', 0, 0),
(479, 66143441, '2025-09-28', 'Present', 0, 0),
(480, 66143441, '2025-09-29', 'Present', 0, 0),
(481, 66143441, '2025-09-30', 'Present', 0, 0),
(482, 66143507, '2025-09-01', 'Present', 0, 0),
(483, 66143507, '2025-09-02', 'Present', 0, 0),
(484, 66143507, '2025-09-03', 'Present', 0, 0),
(485, 66143507, '2025-09-04', 'Present', 0, 0),
(486, 66143507, '2025-09-05', 'Present', 0, 0),
(487, 66143507, '2025-09-06', 'Present', 0, 0),
(488, 66143507, '2025-09-07', 'Present', 0, 0),
(489, 66143507, '2025-09-08', 'Present', 0, 0),
(490, 66143507, '2025-09-09', 'Present', 0, 0),
(491, 66143507, '2025-09-10', 'Present', 0, 0),
(492, 66143507, '2025-09-11', 'Present', 0, 0),
(493, 66143507, '2025-09-12', 'Present', 0, 0),
(494, 66143507, '2025-09-13', 'Present', 0, 0),
(495, 66143507, '2025-09-14', 'Present', 0, 0),
(496, 66143507, '2025-09-15', 'Present', 0, 0),
(497, 66143507, '2025-09-16', 'Present', 0, 0),
(498, 66143507, '2025-09-17', 'Present', 0, 0),
(499, 66143507, '2025-09-18', 'Present', 0, 0),
(500, 66143507, '2025-09-19', 'Present', 0, 0),
(501, 66143507, '2025-09-20', 'Present', 0, 0),
(502, 66143507, '2025-09-21', 'Present', 0, 0),
(503, 66143507, '2025-09-22', 'Present', 0, 0),
(504, 66143507, '2025-09-23', 'Present', 0, 0),
(505, 66143507, '2025-09-24', 'Present', 0, 0),
(506, 66143507, '2025-09-25', 'Present', 0, 20),
(507, 66143507, '2025-09-26', 'Present', 0, 0),
(508, 66143507, '2025-09-27', 'Present', 0, 5),
(509, 66143507, '2025-09-28', 'Present', 0, 0),
(510, 66143507, '2025-09-29', 'Present', 0, 0),
(511, 66143507, '2025-09-30', 'Present', 0, 0),
(512, 66143515, '2025-09-01', 'Present', 0, 0),
(513, 66143515, '2025-09-02', 'Present', 0, 0),
(514, 66143515, '2025-09-03', 'Present', 0, 0),
(515, 66143515, '2025-09-04', 'Present', 0, 0),
(516, 66143515, '2025-09-05', 'Present', 0, 0),
(517, 66143515, '2025-09-06', 'Present', 0, 0),
(518, 66143515, '2025-09-07', 'Present', 0, 0),
(519, 66143515, '2025-09-08', 'Present', 0, 0),
(520, 66143515, '2025-09-09', 'Present', 0, 0),
(521, 66143515, '2025-09-10', 'Present', 0, 0),
(522, 66143515, '2025-09-11', 'Present', 0, 0),
(523, 66143515, '2025-09-12', 'Present', 0, 0),
(524, 66143515, '2025-09-13', 'Present', 0, 0),
(525, 66143515, '2025-09-14', 'Present', 0, 0),
(526, 66143515, '2025-09-15', 'Present', 0, 0),
(527, 66143515, '2025-09-16', 'Present', 0, 0),
(528, 66143515, '2025-09-17', 'Present', 0, 0),
(529, 66143515, '2025-09-18', 'Present', 0, 0),
(530, 66143515, '2025-09-19', 'Present', 0, 0),
(531, 66143515, '2025-09-20', 'Present', 0, 0),
(532, 66143515, '2025-09-21', 'Present', 0, 0),
(533, 66143515, '2025-09-22', 'Present', 0, 0),
(534, 66143515, '2025-09-23', 'Present', 0, 0),
(535, 66143515, '2025-09-24', 'Present', 0, 0),
(536, 66143515, '2025-09-25', 'Present', 0, 0),
(537, 66143515, '2025-09-26', 'Present', 0, 0),
(538, 66143515, '2025-09-27', 'Present', 0, 0),
(539, 66143515, '2025-09-28', 'Present', 0, 0),
(540, 66143515, '2025-09-29', 'Present', 0, 0),
(541, 66143515, '2025-09-30', 'Present', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(100) NOT NULL,
  `position` varchar(50) DEFAULT NULL,
  `bank_account` varchar(50) DEFAULT NULL,
  `bank_name` varchar(50) DEFAULT NULL,
  `salary_per_day` decimal(10,2) NOT NULL DEFAULT 500.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `emp_name`, `position`, `bank_account`, `bank_name`, `salary_per_day`) VALUES
(66143441, 'สุพัฒนกร เป็งคำตา', 'พนักงานขาย', '123-456-7890', 'กสิกรไทย', 600.00),
(66143507, 'ปุณณวิช จันทร์โอ', 'Dev', '987-654-3210', 'ทหารไทย', 1000.00),
(66143515, 'จิตติศักดิ์ จันทนะ', 'พนักงานทำความสะอาด', '321-456-9870', 'ไทยพาณิชย์', 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `salary_id` int(11) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `month_year` varchar(7) DEFAULT NULL,
  `base_salary` decimal(10,2) DEFAULT NULL,
  `ot_pay` decimal(10,2) DEFAULT NULL,
  `deductions` decimal(10,2) DEFAULT NULL,
  `net_salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`salary_id`, `emp_id`, `month_year`, `base_salary`, `ot_pay`, `deductions`, `net_salary`) VALUES
(47, 66143441, '2025-09', 18000.00, 0.00, 0.00, 18000.00),
(48, 66143441, '2025-09', 18000.00, 1500.00, 0.00, 19500.00),
(49, 66143507, '2025-09', 30000.00, 0.00, 0.00, 30000.00),
(50, 66143507, '2025-09', 30000.00, 2500.00, 0.00, 32500.00),
(51, 66143515, '2025-09', 9000.00, 0.00, 0.00, 9000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') DEFAULT 'employee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'admin', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'admin'),
(2, 'emp1', '88d4266fd4e6338d13b845fcf289579d209c897823b9217da3e161936f031589', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`salary_id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=572;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66143590;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`);

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
