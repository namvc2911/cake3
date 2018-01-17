-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2017 at 11:06 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `manage_overtime_tbl`
--

CREATE TABLE `manage_overtime_tbl` (
  `manage_overtime_id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `project_id` char(36) NOT NULL,
  `day_of_overtime` date NOT NULL COMMENT 'Ngay lam them gio',
  `reason` varchar(500) NOT NULL COMMENT 'ly do lam muon',
  `approve_status` int(1) NOT NULL DEFAULT '0' COMMENT '0: khong chap nhan, 1: chap nhan',
  `approver` char(36) DEFAULT NULL COMMENT 'Người duyệt làm muộn',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='quan ly thoi gian lam them';

--
-- Dumping data for table `manage_overtime_tbl`
--

INSERT INTO `manage_overtime_tbl` (`manage_overtime_id`, `user_id`, `project_id`, `day_of_overtime`, `reason`, `approve_status`, `approver`, `create_time`, `update_time`) VALUES
(1, '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '551ccc8b-b0c8-4e7c-8c65-088c7f003535', '2017-07-07', '', 1, '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '2017-07-07 17:43:38', '2017-07-07 17:43:38'),
(2, '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '551ccc8b-b0c8-4e7c-8c65-088c7f003535', '2017-07-10', 'AloAlo', 0, '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '2017-07-10 16:30:32', '2017-07-10 16:30:32'),
(3, '5850fec1-b4b0-49f0-bc31-646b671c27d1', '5567e206-3f30-4db6-9d20-0927ac1f0dc0', '2017-08-21', '', 0, NULL, '2017-07-11 17:45:46', '2017-07-11 17:45:46'),
(4, '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '551ccc8b-b0c8-4e7c-8c65-088c7f003535', '2017-07-12', '', 1, '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '2017-07-12 17:48:42', '2017-07-12 17:48:42'),
(5, '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '551ccc8b-b0c8-4e7c-8c65-088c7f003535', '2017-07-13', '', 1, '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '2017-07-13 15:41:00', '2017-07-13 15:41:00'),
(6, '5850fec1-b4b0-49f0-bc31-646b671c27d1', '9075330f-8233-4651-a162-2233f309b3fc', '2017-08-22', 'TEST', 0, NULL, '2017-08-22 01:14:16', '2017-08-22 01:14:16'),
(7, '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '9075330f-8233-4651-a162-2233f309b3fc', '2017-08-10', 'TEST', 1, '5850fec1-b4b0-49f0-bc31-646b671c27d1', '2017-08-22 01:16:05', '2017-08-22 01:16:05'),
(8, '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '9075330f-8233-4651-a162-2233f309b3fc', '2017-08-31', 'TEST', 1, '5850fec1-b4b0-49f0-bc31-646b671c27d1', '2017-08-22 01:18:53', '2017-08-22 01:18:53'),
(9, '5850fec1-b4b0-49f0-bc31-646b671c27d1', 'd47bf25c-9571-4e42-9ae0-53e3f0a2fd2d', '2016-08-22', 'TEST PART 2', 0, NULL, '2017-08-22 04:03:50', '2017-08-22 04:03:50'),
(10, '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '44f9db92-70b4-43e6-b99a-5fd374783002', '2017-08-30', 'TEST PART 2', 1, '5850fec1-b4b0-49f0-bc31-646b671c27d1', '2017-08-22 04:04:18', '2017-08-22 04:04:18'),
(11, '5850fec1-b4b0-49f0-bc31-646b671c27d1', '9075330f-8233-4651-a162-2233f309b3fc', '2017-09-29', 'Tu Test', 0, NULL, '2017-08-24 07:31:32', '2017-08-24 07:31:32');

-- --------------------------------------------------------

--
-- Table structure for table `member_project_tbl`
--

CREATE TABLE `member_project_tbl` (
  `member_project_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `is_leader` int(1) DEFAULT NULL COMMENT '0: member, 1: leader',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `member_project_tbl`
--

INSERT INTO `member_project_tbl` (`member_project_id`, `user_id`, `project_id`, `is_leader`, `create_time`, `update_time`) VALUES
('019a016a-670b-4c1f-9a5e-e1c1b1118476', '5570f3db-02dc-4410-9541-2bbeac1f0dc0', '9075330f-8233-4651-a162-2233f309b3fc', 0, '2017-08-15 09:17:08', '2017-08-15 09:17:08'),
('03c4b710-03a5-411a-a7dc-83d92cd4d39e', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '972449a9-ced8-43d5-82c7-31376efcc11d', 0, '2017-08-14 08:47:29', '2017-08-14 08:47:29'),
('04ec10a0-a197-42fe-af43-c7b683d653c0', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '9075330f-8233-4651-a162-2233f309b3fc', 1, '2017-08-15 09:17:08', '2017-08-15 09:17:08'),
('071e0247-90d8-4091-b95c-4f0094796e60', '5570f3db-02dc-4410-9541-2bbeac1f0dc0', '4d108f85-1d1e-416e-a718-5bcf27cfe96f', 1, '2017-08-14 10:58:53', '2017-08-14 10:58:53'),
('0a07dbf2-400b-45a9-ae25-965374e616d2', '55234633-5600-4e99-86ee-0bbbac1f0dc0', 'b7e160de-0095-4e13-88a5-c1a2cea5aae7', 0, '2017-08-15 09:33:51', '2017-08-15 09:33:51'),
('0bb5e309-eebc-4fa4-b67e-665600e9a9fb', '5570f3db-02dc-4410-9541-2bbeac1f0dc0', 'd47bf25c-9571-4e42-9ae0-53e3f0a2fd2d', 0, '2017-08-11 03:57:36', '2017-08-11 03:57:36'),
('1bf15911-b070-44ef-8a4c-7cb26bf9d2d7', '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '7b2b4580-6529-4862-904e-01780241ef1f', 0, '2017-08-18 04:31:10', '2017-08-18 04:31:10'),
('1c084915-6b76-4588-9616-4d05ce3be9f9', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '4d108f85-1d1e-416e-a718-5bcf27cfe96f', 0, '2017-08-14 10:58:53', '2017-08-14 10:58:53'),
('25309bcd-1513-4115-807c-066e51b4c5d9', '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '7ee8982a-c2ac-4742-8a78-1302caa0eede', 0, '2017-08-22 04:09:20', '2017-08-22 04:09:20'),
('265f37e5-be50-4c23-9913-122d93fe5dea', '5850fec1-b4b0-49f0-bc31-646b671c27d1', 'd47bf25c-9571-4e42-9ae0-53e3f0a2fd2d', 1, '2017-08-11 03:57:36', '2017-08-11 03:57:36'),
('278ea511-95e9-42ba-a309-091f82ee59f5', '55594716-513c-413b-902f-2825ac1f0dc0', '0e7d2eb5-df8b-4946-952f-8746ac64ee77', 0, '2017-08-22 04:12:54', '2017-08-22 04:12:54'),
('2e34fb2b-7e88-4838-a4d8-778bdfeddcc7', '55594716-513c-413b-902f-2825ac1f0dc0', 'b7e160de-0095-4e13-88a5-c1a2cea5aae7', 0, '2017-08-15 09:33:52', '2017-08-15 09:33:52'),
('2f309197-bb43-4283-b2af-779e325a5d51', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '7b2b4580-6529-4862-904e-01780241ef1f', 1, '2017-08-18 04:31:10', '2017-08-18 04:31:10'),
('322dc885-dc3f-4aed-94b6-6d7a45fb1410', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '1988c99a-bc71-4021-9d92-7462fabee5be', 0, '2017-08-22 09:27:19', '2017-08-22 09:27:19'),
('33668992-e78e-41b7-9ac2-c500260b44b5', '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '553b4d75-20d4-4da8-a4e1-4434ac1f0dc0', 0, '2017-08-15 09:16:18', '2017-08-15 09:16:18'),
('42322571-9890-4b3d-8989-d9f11a1890b6', '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '972449a9-ced8-43d5-82c7-31376efcc11d', 0, '2017-08-14 08:47:29', '2017-08-14 08:47:29'),
('46e422d2-86ba-4f24-a858-0d68f64303b2', '5850fec1-b4b0-49f0-bc31-646b671c27d1', 'ca39c182-7535-485c-80b7-ba631e6f8f47', 1, '2017-08-25 03:09:33', '2017-08-25 03:09:33'),
('491d1d22-b629-4611-bb05-32214374dcd6', '55594716-513c-413b-902f-2825ac1f0dc0', 'd47bf25c-9571-4e42-9ae0-53e3f0a2fd2d', 0, '2017-08-11 03:57:36', '2017-08-11 03:57:36'),
('4eb18ab3-45f1-42bd-ae86-be0a9fcb1a9a', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '65ea086f-ce07-41ac-901a-2b54bdc33076', 0, '2017-08-14 10:57:56', '2017-08-14 10:57:56'),
('4f7a00b0-2924-4384-a8c0-421e59ec4a54', '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '0e7d2eb5-df8b-4946-952f-8746ac64ee77', 0, '2017-08-22 04:12:54', '2017-08-22 04:12:54'),
('56f87ff4-7e69-44bf-8f3e-5c6b0bb97138', '55594716-513c-413b-902f-2825ac1f0dc0', '978f2313-6cc1-4231-b045-b61b92a68de8', 0, '2017-08-15 09:39:52', '2017-08-15 09:39:52'),
('619d0b37-f44d-47a1-996e-a7e24a2a78a2', '55594716-513c-413b-902f-2825ac1f0dc0', 'ca39c182-7535-485c-80b7-ba631e6f8f47', 1, '2017-08-25 03:09:33', '2017-08-25 03:09:33'),
('63763385-4a74-4775-8df4-683f708988cd', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '7ee8982a-c2ac-4742-8a78-1302caa0eede', 1, '2017-08-22 04:09:20', '2017-08-22 04:09:20'),
('63c2be56-2369-4e33-bf18-a6bada2c0683', '5570f3db-02dc-4410-9541-2bbeac1f0dc0', '978f2313-6cc1-4231-b045-b61b92a68de8', 0, '2017-08-15 09:39:52', '2017-08-15 09:39:52'),
('690ec50e-f100-4fd8-9481-529d8286a4d2', '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '972449a9-ced8-43d5-82c7-31376efcc11d', 0, '2017-08-14 08:47:29', '2017-08-14 08:47:29'),
('7345d659-4c56-44c4-bb3a-a0b4b61e4b2d', '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '9075330f-8233-4651-a162-2233f309b3fc', 0, '2017-08-15 09:17:08', '2017-08-15 09:17:08'),
('7723267f-c581-442a-930a-a083cc4efe0a', '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '6a7be9b6-1166-48dd-ad03-a2f8ed240abc', 0, '2017-08-15 09:31:49', '2017-08-15 09:31:49'),
('77a67b3a-ef03-4c61-8b2b-f327aeeefbd1', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '978f2313-6cc1-4231-b045-b61b92a68de8', 0, '2017-08-15 09:39:52', '2017-08-15 09:39:52'),
('7cd3b134-01e5-48f1-8f82-17214197692f', '55594716-513c-413b-902f-2825ac1f0dc0', '7ee8982a-c2ac-4742-8a78-1302caa0eede', 0, '2017-08-22 04:09:20', '2017-08-22 04:09:20'),
('828fe357-66b5-11e7-8f0b-000c298fcec5', '1ba797bc-6b24-11e4-b7fc-40167e34a12f', '551ccc8b-b0c8-4e7c-8c65-088c7f003535', 0, '2017-07-12 00:00:00', '2017-07-12 00:00:00'),
('8a1f4c2a-99a1-4e5f-bacc-7839591c3384', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '7b2b4580-6529-4862-904e-01780241ef1f', 0, '2017-08-18 04:31:10', '2017-08-18 04:31:10'),
('909ddbbb-7be3-45f7-918e-b367a22b26cc', '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '9075330f-8233-4651-a162-2233f309b3fc', 0, '2017-08-15 09:17:08', '2017-08-15 09:17:08'),
('912fed15-7c1f-4da0-9dca-531c2d24337a', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '0e7d2eb5-df8b-4946-952f-8746ac64ee77', 0, '2017-08-22 04:12:54', '2017-08-22 04:12:54'),
('915a5e12-66b5-11e7-8f0b-000c298fcec5', '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', '551ccc8b-b0c8-4e7c-8c65-088c7f003535', 1, '2017-07-12 00:00:00', '2017-07-12 00:00:00'),
('91bdca08-399c-40b2-8eba-8a875202aed2', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '553b4d75-20d4-4da8-a4e1-4434ac1f0dc0', 1, '2017-08-15 09:16:18', '2017-08-15 09:16:18'),
('94e736cb-e16b-467e-9ddd-4921bafe5e07', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '1988c99a-bc71-4021-9d92-7462fabee5be', 0, '2017-08-22 09:27:19', '2017-08-22 09:27:19'),
('a17c5a22-b2fd-4c48-855d-c2dc3a809530', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '972449a9-ced8-43d5-82c7-31376efcc11d', 1, '2017-08-14 08:47:29', '2017-08-14 08:47:29'),
('a203894d-4b9b-45e1-be5d-41fe387609ad', '5570f3db-02dc-4410-9541-2bbeac1f0dc0', '44f9db92-70b4-43e6-b99a-5fd374783002', 0, '2017-08-14 10:56:21', '2017-08-14 10:56:21'),
('a3453bb8-9d89-4dd7-8f61-e0a87a98d89b', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '4d108f85-1d1e-416e-a718-5bcf27cfe96f', 0, '2017-08-14 10:58:53', '2017-08-14 10:58:53'),
('ab8bcba1-dbd1-4b06-b631-c4d06c75ff70', '5570f3db-02dc-4410-9541-2bbeac1f0dc0', '1988c99a-bc71-4021-9d92-7462fabee5be', 0, '2017-08-22 09:27:19', '2017-08-22 09:27:19'),
('ae745e5f-2e27-43a8-84c4-794b57c4e4b4', '5850fec1-b4b0-49f0-bc31-646b671c27d1', 'b7e160de-0095-4e13-88a5-c1a2cea5aae7', 1, '2017-08-15 09:33:52', '2017-08-15 09:33:52'),
('b000a6b1-af25-489e-b128-a0b2238ccdb1', '55594716-513c-413b-902f-2825ac1f0dc0', '65ea086f-ce07-41ac-901a-2b54bdc33076', 0, '2017-08-14 10:57:56', '2017-08-14 10:57:56'),
('b61826b4-b315-46ed-9033-7a8fcf4374e5', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '65ea086f-ce07-41ac-901a-2b54bdc33076', 1, '2017-08-14 10:57:56', '2017-08-14 10:57:56'),
('c6a7be8e-4194-4aea-a688-de7d2a585cdc', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '44f9db92-70b4-43e6-b99a-5fd374783002', 0, '2017-08-14 10:56:21', '2017-08-14 10:56:21'),
('c969f9ed-296e-49bb-a1bc-774a67d593da', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '978f2313-6cc1-4231-b045-b61b92a68de8', 1, '2017-08-15 09:39:52', '2017-08-15 09:39:52'),
('cf6baa30-66b5-11e7-8f0b-000c298fcec5', '552359e3-b298-4fc6-a3b4-326bac1f0dc0', '5567e206-3f30-4db6-9d20-0927ac1f0dc0', 1, '2017-07-12 00:00:00', '2017-07-12 00:00:00'),
('d0150c7d-b0a6-497d-933a-55312ee8dce0', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '6a7be9b6-1166-48dd-ad03-a2f8ed240abc', 0, '2017-08-15 09:31:49', '2017-08-15 09:31:49'),
('d0e9505e-fb84-4ee6-a5d9-27c95324b167', '5570f3db-02dc-4410-9541-2bbeac1f0dc0', '7b2b4580-6529-4862-904e-01780241ef1f', 0, '2017-08-18 04:31:10', '2017-08-18 04:31:10'),
('d451beb2-53aa-4ec8-b029-b1d9f423741f', '552359e3-b298-4fc6-a3b4-326bac1f0dc0', 'ca39c182-7535-485c-80b7-ba631e6f8f47', 1, '2017-08-25 03:09:33', '2017-08-25 03:09:33'),
('d5054976-23fc-4d7b-ad1e-1abc3d568d1d', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '44f9db92-70b4-43e6-b99a-5fd374783002', 1, '2017-08-14 10:56:21', '2017-08-14 10:56:21'),
('d761c81a-27af-4a27-bf06-b9eed3122d6a', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '0e7d2eb5-df8b-4946-952f-8746ac64ee77', 1, '2017-08-22 04:12:54', '2017-08-22 04:12:54'),
('d7e89ca3-9af8-4e8b-bb5d-c20c964df0a8', '55594716-513c-413b-902f-2825ac1f0dc0', '1988c99a-bc71-4021-9d92-7462fabee5be', 0, '2017-08-22 09:27:19', '2017-08-22 09:27:19'),
('db5c50bb-66b5-11e7-8f0b-000c298fcec5', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '5567e206-3f30-4db6-9d20-0927ac1f0dc0', 0, '2017-07-12 00:00:00', '2017-07-12 00:00:00'),
('dd9eace4-1cae-452e-a3fd-13fee883c9d2', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '9075330f-8233-4651-a162-2233f309b3fc', 1, '2017-08-15 09:17:08', '2017-08-15 09:17:08'),
('e4bca0f2-1557-43d2-aaa7-c6a5c138b35c', '55594716-513c-413b-902f-2825ac1f0dc0', '553b4d75-20d4-4da8-a4e1-4434ac1f0dc0', 1, '2017-08-15 09:16:18', '2017-08-15 09:16:18'),
('e9483fcb-a2b3-4e87-b74f-d0edbad2ba07', '5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', 'ca39c182-7535-485c-80b7-ba631e6f8f47', 0, '2017-08-25 03:09:33', '2017-08-25 03:09:33'),
('f6496b0b-ba30-464a-a4c7-4d3b474136d5', '5850fec1-b4b0-49f0-bc31-646b671c27d1', '6a7be9b6-1166-48dd-ad03-a2f8ed240abc', 1, '2017-08-15 09:31:49', '2017-08-15 09:31:49'),
('f955e874-9992-4feb-8ece-46d3c4405cbf', '55234633-5600-4e99-86ee-0bbbac1f0dc0', '553b4d75-20d4-4da8-a4e1-4434ac1f0dc0', 0, '2017-08-15 09:16:18', '2017-08-15 09:16:18'),
('ff34e849-4813-4bd1-9a78-b8b807800fde', '5570f3db-02dc-4410-9541-2bbeac1f0dc0', 'b7e160de-0095-4e13-88a5-c1a2cea5aae7', 0, '2017-08-15 09:33:52', '2017-08-15 09:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `project_tbl`
--

CREATE TABLE `project_tbl` (
  `project_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '0: Xoa du an, 1: Chưa thực hiện, 2: Đang thực hiện, 3: Đã hoàn thành',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_tbl`
--

INSERT INTO `project_tbl` (`project_id`, `name`, `description`, `start_date`, `end_date`, `status`, `create_time`, `update_time`) VALUES
('0e7d2eb5-df8b-4946-952f-8746ac64ee77', 'Test 1', 'Tú Test Sửa', '2017-08-01 00:00:00', '2017-08-31 00:00:00', '1', '2017-08-22 04:12:22', '2017-08-22 04:12:54'),
('1988c99a-bc71-4021-9d92-7462fabee5be', 'P4', 'Đại gia Dubai JVB', '2017-07-05 00:00:00', '2017-08-15 00:00:00', '1', '2017-08-09 02:47:11', '2017-08-22 09:27:19'),
('44f9db92-70b4-43e6-b99a-5fd374783002', 'GJGJGJGJ', 'GYGYGYGY', '2017-08-16 00:00:00', '2017-08-22 00:00:00', '2', '2017-08-14 10:56:21', '2017-08-14 10:56:21'),
('4d108f85-1d1e-416e-a718-5bcf27cfe96f', 'P900', 'HF', '2017-08-01 00:00:00', '2017-08-31 00:00:00', '1', '2017-08-14 10:47:57', '2017-08-14 10:47:57'),
('551ccc8b-b0c8-4e7c-8c65-088c7f003535', 'P1', 'Người theo hương hoa mây mù giăng lối, Làn sương khói phôi phai đưa bước ai xa rồi, Đơn côi mình ta vấn vương hồi ức trong men say chiều mưa buồn', '2015-04-02 06:58:00', '2015-12-31 00:00:00', '1', NULL, NULL),
('553b4d75-20d4-4da8-a4e1-4434ac1f0dc0', 'P29', 'Người theo hương hoa mây mù giăng lối, Làn sương khói phôi phai đưa bước ai xa rồi, Đơn côi mình ta vấn vương hồi ức trong men say chiều mưa buồn', '2015-02-24 00:00:00', '2015-12-31 00:00:00', '1', NULL, '2017-08-09 10:57:11'),
('5567e206-3f30-4db6-9d20-0927ac1f0dc0', 'P3', 'Người theo hương hoa mây mù giăng lối, Làn sương khói phôi phai đưa bước ai xa rồi, Đơn côi mình ta vấn vương hồi ức trong men say chiều mưa buồn', '2015-12-29 00:00:00', '2016-05-04 00:00:00', '3', NULL, NULL),
('65ea086f-ce07-41ac-901a-2b54bdc33076', 'TMT', 'GG', '2017-08-01 00:00:00', '2017-08-31 00:00:00', '1', '2017-08-14 10:49:27', '2017-08-14 10:49:27'),
('6a7be9b6-1166-48dd-ad03-a2f8ed240abc', 'P7', 'GG Well Play', '2017-08-01 00:00:00', '2017-08-29 00:00:00', '2', '2017-08-14 09:48:39', '2017-08-14 09:48:39'),
('7b2b4580-6529-4862-904e-01780241ef1f', 'E10', 'JVB', '2017-08-03 00:00:00', '2017-08-30 00:00:00', '1', '2017-08-18 04:31:11', '2017-08-18 04:31:11'),
('7ee8982a-c2ac-4742-8a78-1302caa0eede', 'P0909', 'JVB', '2017-08-09 00:00:00', NULL, '1', '2017-08-15 09:40:20', '2017-08-22 04:09:20'),
('9075330f-8233-4651-a162-2233f309b3fc', 'JVB VietNam', 'JVB', '2017-08-01 00:00:00', '2017-08-22 00:00:00', '2', '2017-08-15 09:17:08', '2017-08-15 09:17:08'),
('972449a9-ced8-43d5-82c7-31376efcc11d', 'P2000', 'ABCD', '2017-08-01 00:00:00', '2017-08-31 00:00:00', '3', '2017-08-10 01:29:00', '2017-08-14 07:17:05'),
('978f2313-6cc1-4231-b045-b61b92a68de8', 'P90909', 'JJDJDJ', '2017-08-01 00:00:00', '2017-08-15 00:00:00', '1', '2017-08-15 09:39:52', '2017-08-15 09:39:52'),
('b7e160de-0095-4e13-88a5-c1a2cea5aae7', 'JVB 2', 'JVB', '2017-08-18 00:00:00', '2017-08-31 00:00:00', '1', '2017-08-15 09:33:17', '2017-08-15 09:33:52'),
('ca39c182-7535-485c-80b7-ba631e6f8f47', 'Ahihi', '@#@', '2017-06-06 00:00:00', '2017-08-31 00:00:00', '3', '2017-08-15 09:34:22', '2017-08-15 09:34:22'),
('d47bf25c-9571-4e42-9ae0-53e3f0a2fd2d', 'P4000', 'JVB', '2017-07-05 00:00:00', '2017-08-31 00:00:00', '2', '2017-08-10 08:21:17', '2017-08-11 03:57:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_mst`
--

CREATE TABLE `role_mst` (
  `role_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_mst`
--

INSERT INTO `role_mst` (`role_id`, `value`, `create_time`, `update_time`) VALUES
('3070f8cc-6976-11e4-8db0-40167e347eff', 'ADMIN', '2014-11-11 07:00:00', '2014-11-11 07:00:00'),
('bb165d60-6976-11e4-9803-0800200c9a66', 'MEMBERS', '2014-11-11 07:00:00', '2014-11-11 07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `user_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `username` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nickname` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` char(36) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Quyen cua user',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`user_id`, `username`, `password`, `fullname`, `nickname`, `role_id`, `create_time`, `update_time`) VALUES
('1ba797bc-6b24-11e4-b7fc-40167e34a12f', 'user1', '8540df00bc3caf9acb981f82084f5b7f79460735', 'Trần Văn A', 'user1', 'bb165d60-6976-11e4-9803-0800200c9a66', '2014-11-13 00:00:00', '2014-11-13 00:00:00'),
('55234633-5600-4e99-86ee-0bbbac1f0dc0', 'user2', '8540df00bc3caf9acb981f82084f5b7f79460735', 'Nguyễn Thị B', 'user2', 'bb165d60-6976-11e4-9803-0800200c9a66', NULL, NULL),
('552359e3-b298-4fc6-a3b4-326bac1f0dc0', 'user3', '8540df00bc3caf9acb981f82084f5b7f79460735', 'Trương Văn B', 'user3', 'bb165d60-6976-11e4-9803-0800200c9a66', NULL, NULL),
('5546fab4-e46c-4ebe-abbb-7c2dac1f0dc0', 'user4', '8540df00bc3caf9acb981f82084f5b7f79460735', 'Đào Quốc A', 'user4', 'bb165d60-6976-11e4-9803-0800200c9a66', NULL, NULL),
('55594716-513c-413b-902f-2825ac1f0dc0', 'user5', '8540df00bc3caf9acb981f82084f5b7f79460735', 'Hà Thị C', 'user5', 'bb165d60-6976-11e4-9803-0800200c9a66', NULL, NULL),
('5570f3db-02dc-4410-9541-2bbeac1f0dc0', 'user6', '8540df00bc3caf9acb981f82084f5b7f79460735', 'Trần Thị D', 'user6', 'bb165d60-6976-11e4-9803-0800200c9a66', NULL, NULL),
('5850fec1-b4b0-49f0-bc31-646b671c27d1', 'manhtu', '$2y$10$gztGMRRQtTRRTq/nn.w5D.6r6Yg2f8IXpnywcT99hRZJ3iuB3Eh6y', 'Trần Mạnh Tú', 'Manh Tu', 'bb165d60-6976-11e4-9803-0800200c9a66', NULL, NULL),
('b7636b32-6977-11e4-8db0-40167e347eff', 'admin', '$2y$10$hGkcQd1.6wzjk4tiqy7SeOzY4tHtmAGplAHh2k6ZN.2fTMgAxMGaq', 'admin', 'admin', '3070f8cc-6976-11e4-8db0-40167e347eff', '2014-11-11 00:00:00', '2014-11-11 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `manage_overtime_tbl`
--
ALTER TABLE `manage_overtime_tbl`
  ADD PRIMARY KEY (`manage_overtime_id`);

--
-- Indexes for table `member_project_tbl`
--
ALTER TABLE `member_project_tbl`
  ADD PRIMARY KEY (`member_project_id`),
  ADD KEY `fk_member_project_users1_idx` (`user_id`),
  ADD KEY `fk_member_project_projects1_idx` (`project_id`);

--
-- Indexes for table `project_tbl`
--
ALTER TABLE `project_tbl`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `role_mst`
--
ALTER TABLE `role_mst`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_users_tbl_role_mst1_idx` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `manage_overtime_tbl`
--
ALTER TABLE `manage_overtime_tbl`
  MODIFY `manage_overtime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `member_project_tbl`
--
ALTER TABLE `member_project_tbl`
  ADD CONSTRAINT `fk_member_project_users1` FOREIGN KEY (`user_id`) REFERENCES `user_tbl` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
