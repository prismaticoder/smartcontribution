-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2019 at 05:29 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_contribution`
--

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `transaction_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `month` varchar(11) NOT NULL,
  `year` varchar(11) NOT NULL,
  `savings_rate` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `amount` int(20) NOT NULL DEFAULT '0',
  `balance` int(15) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `transaction_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_date` int(11) NOT NULL,
  `amount` varchar(10) NOT NULL,
  `month` varchar(10) NOT NULL,
  `year` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `log` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `main_customers`
--

CREATE TABLE `main_customers` (
  `customer_id` int(11) NOT NULL,
  `card_no` varchar(15) NOT NULL,
  `customer_name` varchar(40) NOT NULL,
  `customer_phone_num` varchar(20) NOT NULL,
  `reg_date` date NOT NULL,
  `address_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `savings_rate` int(11) NOT NULL,
  `loan_rate` int(11) NOT NULL,
  `author` varchar(25) NOT NULL,
  `savings_balance` int(15) NOT NULL DEFAULT '0',
  `loan_balance` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `main_customers`
--

INSERT INTO `main_customers` (`customer_id`, `card_no`, `customer_name`, `customer_phone_num`, `reg_date`, `address_id`, `zone_id`, `savings_rate`, `loan_rate`, `author`, `savings_balance`, `loan_balance`) VALUES
(1, 'A1', 'Prince A.', '08055667744', '2019-06-25', 0, 2, 1200, 0, 'lukman(STAFF)', 7200, 0),
(2, 'B1', 'Jesutomiwa Salam', '07035048364', '2019-03-21', 0, 1, 1000, 2300, 'lukman(STAFF)', 7820, -4760),
(3, 'C1', 'Ife Nasir', '09033442233', '2019-04-16', 0, 1, 500, 0, 'lukman(STAFF)', 2000, 3000),
(4, 'E3', 'Asekome Peter', '07065421762', '2019-05-09', 0, 2, 350, 0, 'lukman(STAFF)', 350, 0),
(5, 'B27', 'Nosa A.', '08153646621', '2019-04-08', 0, 4, 1500, 0, 'lukman(STAFF)', 3000, -1000),
(6, 'D12', 'Michael A.', '07095642312', '2019-06-10', 0, 1, 500, 0, 'lukman(STAFF)', 5000, 0),
(7, 'A5', 'Adeola Goodness', '09088771111', '2019-01-09', 0, 4, 2500, 0, 'lukman(STAFF)', 0, 0),
(8, 'B19', 'Maupe Y.', '09066255252', '2019-03-23', 0, 2, 1000, 0, 'lukman(STAFF)', 2000, 0),
(9, 'B24', 'Adewumi Ololade', '09066775522', '2019-03-17', 0, 3, 1000, 0, 'lukman(STAFF)', 0, 0),
(10, 'A16', 'Jude O.', '09066227722', '2019-04-26', 0, 2, 1200, 0, 'lukman(STAFF)', 0, 0),
(11, 'B15', 'Ajanaku James', '09077661155', '2019-06-10', 0, 3, 500, 0, 'oyee(SUPERADMIN)', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

CREATE TABLE `months` (
  `month_id` int(11) NOT NULL,
  `month` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `months`
--

INSERT INTO `months` (`month_id`, `month`) VALUES
(1, 'January'),
(2, 'February'),
(3, 'March'),
(4, 'April'),
(5, 'May'),
(6, 'June'),
(7, 'July'),
(8, 'August'),
(9, 'September'),
(10, 'October'),
(11, 'November'),
(12, 'December');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role`) VALUES
(1, 'SUPERADMIN'),
(2, 'ADMIN'),
(3, 'STAFF');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `savings_rate` int(11) DEFAULT NULL,
  `loan_rate` int(11) DEFAULT NULL,
  `savingsDayNo` int(11) NOT NULL DEFAULT '0',
  `loanDayNo` int(11) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL,
  `month` varchar(10) NOT NULL,
  `year` int(11) NOT NULL,
  `description` varchar(35) NOT NULL,
  `type` varchar(3) NOT NULL,
  `balance` int(11) NOT NULL,
  `author` varchar(32) DEFAULT NULL,
  `isReversed` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `customer_id`, `transaction_date`, `savings_rate`, `loan_rate`, `savingsDayNo`, `loanDayNo`, `amount`, `month`, `year`, `description`, `type`, `balance`, `author`, `isReversed`) VALUES
(1, 5, '2019-07-03', 1500, NULL, 3, 0, 4500, 'Jul', 2019, 'Daily Savings', 'CR', 4500, NULL, 1),
(2, 5, '2019-06-30', 1500, NULL, 3, 0, 4500, 'Jun', 2019, 'Daily Savings', 'CR', 9000, NULL, 1),
(3, 5, '2019-07-03', NULL, NULL, 0, 0, 4000, 'Jul', 2019, 'Loan Collection', 'DR', 5000, 'Prince A.', 1),
(4, 5, '2019-07-03', NULL, NULL, 0, 0, 4000, 'Jul', 2019, 'Reversal of Transaction ID: 3', 'CR', 9000, NULL, 1),
(5, 5, '2019-07-03', 1500, NULL, -3, 0, -4500, 'Jul', 2019, 'Reversal of Transaction ID: 1', 'DR', 4500, NULL, 1),
(6, 5, '2019-07-03', 1500, NULL, -3, 0, -4500, 'Jun', 2019, 'Reversal of Transaction ID: 2', 'DR', 0, NULL, 1),
(7, 5, '2019-07-03', 1500, NULL, 2, 0, 3000, 'Jul', 2019, 'Daily Savings', 'CR', 3000, NULL, 0),
(8, 1, '2019-07-03', 1200, NULL, 9, 0, 10800, 'Jul', 2019, 'Daily Savings', 'CR', 14400, NULL, 1),
(9, 1, '2019-07-03', 1200, NULL, 3, 0, 3600, 'Jul', 2019, 'Daily Savings', 'CR', 18000, NULL, 0),
(10, 1, '2019-07-03', 1200, NULL, -9, 0, -10800, 'Jul', 2019, 'Reversal of Transaction ID: 8', 'DR', 7200, NULL, 1),
(11, 6, '2019-07-10', 500, NULL, 5, 0, 2500, 'Jul', 2019, 'Daily Savings', 'CR', 2500, NULL, 0),
(12, 6, '2019-07-10', 500, NULL, 5, 0, 2500, 'Jul', 2019, 'Daily Savings', 'CR', 5000, NULL, 0),
(13, 5, '2019-07-16', NULL, NULL, 0, 0, 1000, 'Jul', 2019, 'Loan Collection', 'DR', 2000, 'Prince A.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `username`, `password`) VALUES
(1, 1, 'oyee', 'admin'),
(2, 3, 'ade', 'ade'),
(3, 1, 'ayinde', 'sulaimon'),
(4, 3, 'lukman', 'oyee'),
(6, 1, 'jesutomiwa', 'jesutomiwa99'),
(7, 2, 'onyinye', 'schooler');

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `year_id` int(11) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`year_id`, `year`) VALUES
(1, 2015),
(2, 2016),
(3, 2017),
(4, 2018),
(5, 2019);

-- --------------------------------------------------------

--
-- Table structure for table `zone`
--

CREATE TABLE `zone` (
  `zone_id` int(11) NOT NULL,
  `zone_code` varchar(4) NOT NULL,
  `zone` varchar(25) NOT NULL,
  `zone_officer` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zone`
--

INSERT INTO `zone` (`zone_id`, `zone_code`, `zone`, `zone_officer`) VALUES
(1, 'AL', 'Aleshinloye', 'Mike D.'),
(2, 'DU1', 'Dugbe1', 'Oladosu M'),
(3, 'DU2', 'Dugbe2', 'Ife Olabode'),
(4, 'OG', 'Ogunpa', 'Mark Z.'),
(5, 'OSG', 'Osogbo', 'Adewale M.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `month_id` (`month`),
  ADD KEY `year_id` (`year`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `main_customers`
--
ALTER TABLE `main_customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `card_no` (`card_no`),
  ADD KEY `zone_id` (`zone_id`);

--
-- Indexes for table `months`
--
ALTER TABLE `months`
  ADD PRIMARY KEY (`month_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`year_id`);

--
-- Indexes for table `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`zone_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `main_customers`
--
ALTER TABLE `main_customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `month_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `years`
--
ALTER TABLE `years`
  MODIFY `year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `zone`
--
ALTER TABLE `zone`
  MODIFY `zone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `contributions_ibfk_4` FOREIGN KEY (`customer_id`) REFERENCES `main_customers` (`customer_id`);

--
-- Constraints for table `main_customers`
--
ALTER TABLE `main_customers`
  ADD CONSTRAINT `main_customers_ibfk_1` FOREIGN KEY (`zone_id`) REFERENCES `zone` (`zone_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `main_customers` (`customer_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
