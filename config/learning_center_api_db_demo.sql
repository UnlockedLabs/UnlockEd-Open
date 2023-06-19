-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 14, 2021 at 02:11 PM
-- Server version: 10.2.22-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `learning_center_api_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_levels`
--

CREATE TABLE `access_levels` (
  `id` int(11) NOT NULL,
  `access_num` int(11) DEFAULT NULL,
  `access_name` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `access_levels`
--

INSERT INTO `access_levels` (`id`, `access_num`, `access_name`, `timestamp`) VALUES
(1, 1, 'Open Enrollment', '2021-12-07 03:35:09'),
(2, 2, 'Category Enrollment Required', '2021-12-07 03:35:09'),
(3, 3, 'Course Enrollment Required', '2021-12-07 03:35:09'),
(4, 4, 'Cohort Enrollment Required', '2021-12-07 03:35:09'),
(5, 5, 'Group Enrollment Required', '2021-12-07 03:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `admin_levels`
--

CREATE TABLE `admin_levels` (
  `id` int(11) NOT NULL,
  `admin_num` int(11) DEFAULT NULL,
  `admin_name` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_levels`
--

INSERT INTO `admin_levels` (`id`, `admin_num`, `admin_name`, `timestamp`) VALUES
(1, 1, 'Student (Non-Admin)', '2021-12-07 03:35:09'),
(2, 2, 'Facilitator (Cohort Admin)', '2021-12-07 03:35:09'),
(3, 3, 'Instructor (Course Admin)', '2021-12-07 03:35:09'),
(4, 4, 'School Admin (Category Admin)', '2021-12-07 03:35:09'),
(5, 5, 'Site Admin', '2021-12-07 03:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `admin_privileges`
--

CREATE TABLE `admin_privileges` (
  `id` varchar(64) NOT NULL,
  `name` varchar(256) NOT NULL,
  `friendly_name` varchar(256) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_privileges`
--

INSERT INTO `admin_privileges` (`id`, `name`, `friendly_name`, `timestamp`) VALUES
('02d92974-6d2d-47cd-afa5-dcc166b8cc09', 'delete_user', 'Delete User', '2021-12-07 03:35:09'),
('17d2a983-463b-4c23-ab52-a423660a9b43', 'create_user', 'Create User', '2021-12-07 03:35:09'),
('2fedc1fe-25de-45d6-aaf5-64b9b7d66b39', 'modify_topic', 'Modify Topic', '2021-12-07 03:35:09'),
('42cd2d29-e5f8-4dc0-ac6d-db4a246ff9d5', 'modify_category', 'Modify Category', '2021-12-07 03:35:09'),
('459842a9-c35d-4482-8c9a-0dd1933c9390', 'create_lesson', 'Create Lesson', '2021-12-07 03:35:09'),
('5a32083b-54c5-4624-9fef-8959737326b1', 'modify_lesson', 'Modify Lesson', '2021-12-07 03:35:09'),
('5a95afdf-4f44-46e2-9483-134b40470196', 'create_category', 'Create Category', '2021-12-07 03:35:09'),
('6627a2d4-9c00-4418-b289-3ee96e2a9edf', 'create_topic', 'Create Topic', '2021-12-07 03:35:09'),
('74bd030e-b9bc-4738-85b0-b87134a35c62', 'delete_category', 'Delete Category', '2021-12-07 03:35:09'),
('7769944e-8708-486b-a0cf-c012d0c29a7a', 'delete_topic', 'Delete Topic', '2021-12-07 03:35:09'),
('8b063d9e-daba-41ac-b32c-28f0515a02e1', 'modify_user', 'Modify User', '2021-12-07 03:35:09'),
('a5f3db50-1941-4a25-b9bf-7264f4c53c5b', 'delete_course', 'Delete Course', '2021-12-07 03:35:09'),
('dcdb24be-7a7a-4554-bfc4-8ef268280d3a', 'modify_course', 'Modify Course', '2021-12-07 03:35:09'),
('e1088c04-09e2-4559-b9a1-365040ba8fda', 'delete_lesson', 'Delete Lesson', '2021-12-07 03:35:09'),
('f8f9408a-94c9-4c31-ac6e-b01b3e3eee7f', 'create_course', 'Create Course', '2021-12-07 03:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` varchar(64) NOT NULL,
  `answer_text` varchar(255) DEFAULT NULL,
  `question_id` varchar(64) DEFAULT NULL,
  `correct` enum('yes','no') NOT NULL,
  `answer_position` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `answer_text`, `question_id`, `correct`, `answer_position`, `created`, `modified`) VALUES
('058dcace-0460-4b3f-a75a-dfe0bf81a8cc', '2', 'cbf196b0-d358-4e81-a4d3-723d1602f955', 'yes', 1, '2021-12-06 22:05:11', '2021-12-07 04:05:11'),
('074a5557-cb9a-4548-b342-808ccb8c0e28', '20', '769af362-1c54-45ad-aa57-7919c3d5edff', 'yes', 1, '2021-12-10 20:47:35', '2021-12-11 02:47:35'),
('09a7cec3-3afe-49c2-8ec6-bd1981b2f2eb', '26', '49a2e78f-461a-4d65-adf8-0e9ada5afd37', 'no', 2, '2021-12-12 15:31:22', '2021-12-12 21:31:22'),
('105251bf-540a-4b19-a4ac-7fd97c00d570', '2', '58c6bae5-1128-4926-8749-0b2bd46aba83', 'yes', 1, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('14587c5a-c222-4394-b26d-20cac1ee666f', '25', '49a2e78f-461a-4d65-adf8-0e9ada5afd37', 'yes', 1, '2021-12-12 15:31:22', '2021-12-12 21:31:22'),
('191452cd-75ea-4559-a2a1-41d592824039', '5', '58b35965-b647-4471-a93a-f133ff14f997', 'no', 2, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('1e984eb0-2c7c-41b8-9a2e-8e6aced4aba5', '2', '3a815229-8c22-4f6a-bca0-a25245adec0d', 'no', 2, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('23f20700-c296-4367-83b6-215c5f423105', '21', '769af362-1c54-45ad-aa57-7919c3d5edff', 'no', 2, '2021-12-10 20:47:35', '2021-12-11 02:47:35'),
('2c1ca823-afbb-41a3-8520-e427772065d1', '48', 'bef42807-942f-43c7-a2d7-eb087ffa95cd', 'no', 2, '2021-12-12 16:15:23', '2021-12-12 22:15:23'),
('2e1f02c5-1c2f-4855-b298-c1afd15b0e2e', '46', 'ec5ffdf6-5ab1-48a3-b7e9-b10280fe19c3', 'no', 2, '2021-12-12 16:15:23', '2021-12-12 22:15:23'),
('2f01c22d-b5ee-460a-af3b-35d8393651ad', 'North Pole', 'd7976888-e7bb-47d6-8b43-0e6643162aaa', 'yes', 1, '2021-12-10 20:48:20', '2021-12-11 02:48:20'),
('31e3ad31-a362-4951-8010-a21c1767fd8b', '2', 'ef4153bd-e17e-4d13-a1b9-529d7007712d', 'no', 2, '2021-12-07 22:44:08', '2021-12-08 04:44:08'),
('394e7c80-f5b5-4156-89b1-8729d3632416', '1', '3a815229-8c22-4f6a-bca0-a25245adec0d', 'yes', 1, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('49237c88-d9ce-4b71-9214-18fe5327f5ea', '3', '5362a81c-fcab-4a53-8344-9d41d89c97ed', 'yes', 1, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('5505c1bc-9a64-453a-816f-f8cd653f6d65', '10', 'd213b25b-ab62-43c1-b6cb-112540ad6ef7', 'yes', 1, '2021-12-07 22:44:08', '2021-12-08 04:44:08'),
('6b703fa4-50c9-44dc-ae83-82113b575e22', '1', 'd213b25b-ab62-43c1-b6cb-112540ad6ef7', 'no', 2, '2021-12-07 22:44:08', '2021-12-08 04:44:08'),
('6feb45e3-fe0e-41c2-9701-0d1000cfa520', '5', '32cd7211-45ca-4629-ad74-85d0c420e49d', 'no', 2, '2021-12-06 22:05:11', '2021-12-07 04:05:11'),
('787dfd0a-8f2b-45f2-a2c2-f6fbccae5e51', '1', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945', 'yes', 1, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('7a47a483-0952-4e73-a52a-8983d9c585ad', '8', 'd63b11c3-b0e8-4771-9da3-b5709017568a', 'no', 2, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('7c633fbc-da3a-4a9d-b351-75b236c90b91', '2', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945', 'no', 2, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('7e90ec3f-350a-4d2b-ae0d-572c121d412a', '40', '85f52d04-c923-4199-aead-785c02230ab6', 'yes', 1, '2021-12-12 15:35:12', '2021-12-12 21:35:12'),
('856ded85-266e-40f7-a360-b089bcecb665', '4', '5362a81c-fcab-4a53-8344-9d41d89c97ed', 'no', 2, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('900f50b0-ed75-452e-9bef-9bd5961bee62', 'here', 'd7976888-e7bb-47d6-8b43-0e6643162aaa', 'no', 2, '2021-12-10 20:48:20', '2021-12-11 02:48:20'),
('98ba14f7-f21a-4414-8177-ae76bab48996', '4', '32cd7211-45ca-4629-ad74-85d0c420e49d', 'yes', 1, '2021-12-06 22:05:11', '2021-12-07 04:05:11'),
('9abc8d1f-7355-465c-88c5-86c56faa6a6a', '0', 'ef4153bd-e17e-4d13-a1b9-529d7007712d', 'yes', 1, '2021-12-07 22:44:08', '2021-12-08 04:44:08'),
('a6a4fb8b-fece-4900-b34e-306db390f9b9', '64', 'ec5ffdf6-5ab1-48a3-b7e9-b10280fe19c3', 'yes', 1, '2021-12-12 16:15:23', '2021-12-12 22:15:23'),
('acda1742-b15d-4fb6-aa2e-f738b7e210dd', '2', '58b35965-b647-4471-a93a-f133ff14f997', 'yes', 1, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('b1de3e02-e1d3-4ab6-ab16-b328c4753fdd', '3', 'cbf196b0-d358-4e81-a4d3-723d1602f955', 'no', 2, '2021-12-06 22:05:11', '2021-12-07 04:05:11'),
('b4d74b7e-c7b3-465b-9eef-98182cf0eb3d', '6', 'd63b11c3-b0e8-4771-9da3-b5709017568a', 'yes', 1, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('c54ffda7-2277-4530-94a8-aafc4a9d99cd', '41', '85f52d04-c923-4199-aead-785c02230ab6', 'no', 2, '2021-12-12 15:35:12', '2021-12-12 21:35:12'),
('cbfb7a25-9c2a-409f-85a0-ee72051f3b9b', 'yes', '690f3d28-c529-465a-8f5f-f3e46c18962f', 'yes', 1, '2021-12-10 14:09:31', '2021-12-10 20:09:31'),
('d74af675-a2bd-4300-9c74-cbbf0409a8fd', '49', 'bef42807-942f-43c7-a2d7-eb087ffa95cd', 'yes', 1, '2021-12-12 16:15:23', '2021-12-12 22:15:23'),
('dac0fbe0-084d-41e4-850d-5a417987d367', 'no', '690f3d28-c529-465a-8f5f-f3e46c18962f', 'no', 2, '2021-12-10 14:09:31', '2021-12-10 20:09:31'),
('dfe548b0-5675-415f-9005-6bbe9947e52a', 'no', '84fa10ae-0fa7-4c09-90a3-53098aa95f4d', 'no', 2, '2021-12-10 23:20:19', '2021-12-11 05:20:19'),
('eaa53b30-368c-48b4-a229-b1a9080b14ec', 'yes', '84fa10ae-0fa7-4c09-90a3-53098aa95f4d', 'yes', 1, '2021-12-10 23:20:19', '2021-12-11 05:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` varchar(64) NOT NULL,
  `category_name` text DEFAULT NULL,
  `access_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `access_id`, `admin_id`, `timestamp`) VALUES
('00f270bd-3591-4fd0-941c-3fa8e943f76b', 'General Education', 1, 1, '2021-12-07 03:35:09'),
('02852328-299f-4641-8b1d-ba6fc73903cf', 'Public Speaking', 2, 1, '2021-12-07 03:35:09'),
('0c0b7ed8-d54e-47b9-a32c-fb28c857ac1b', 'Washington University', 2, 2, '2021-12-07 03:35:09'),
('0fed849c-cb14-4121-bbd5-a4d458bd6a5f', 'Lindenwood University', 2, 1, '2021-12-07 03:35:09'),
('1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', 'Advanced Education', 2, 1, '2021-12-07 03:35:09'),
('22243a35-60e2-4370-8cd2-835e2a2f0066', 'St. Louis Community College - Meremac', 1, 1, '2021-12-07 03:35:09'),
('2defc588-d4ac-4f4c-8d45-b7e48b029ef7', 'Webster University', 2, 1, '2021-12-07 03:35:09'),
('41e378c9-28f5-460c-8c50-df3efd68bcf6', 'HISET', 1, 1, '2021-12-07 03:35:09'),
('427f7c30-3167-4fdc-b20e-dab356fb914b', 'Dog Training', 2, 1, '2021-12-07 03:35:09'),
('5392d522-5427-44ea-abf4-abc440a1dd1c', 'Reentry', 1, 1, '2021-12-07 03:35:09'),
('701fd3bc-f867-4cc2-b461-f4f8e9fc9a90', 'Missouri Baptist University', 2, 1, '2021-12-07 03:35:09'),
('9419d847-d80f-42b4-aeda-675b100bd0b9', 'Programming', 1, 1, '2021-12-07 03:35:09'),
('94876a68-f185-4967-b5fb-f90859ffd5a8', 'Unassigned Topics and Courses', 1, 1, '2021-12-07 03:35:09'),
('973e9471-fe48-4384-88f2-48a127432933', 'STEM', 2, 1, '2021-12-07 03:35:09'),
('af365ac9-b1c7-46ec-9e1d-b7e360e20f9a', 'Logan Chiropractic College', 2, 1, '2021-12-07 03:35:09'),
('ce240c1c-2e71-4ada-a11a-8fe1b033860d', 'Criminal Justice', 2, 1, '2021-12-07 03:35:09'),
('d1ec555c-a4f1-4ae9-bc95-396220863ddc', 'Fontbonne University', 2, 1, '2021-12-07 03:35:09'),
('d711973f-3355-41f6-91eb-e2a7392a67f6', 'University of Missouri - St. Louis', 2, 1, '2021-12-07 03:35:09'),
('e34ede21-286d-4d78-88af-77291475483d', 'Health', 2, 1, '2021-12-07 03:35:09'),
('efc711cb-42b3-4a9c-9d79-b5cb3775110d', 'St. Louis Community College - Florissant Valley', 1, 1, '2021-12-07 03:35:09'),
('f399890a-b56b-4ef4-bd64-02d4301ee09b', 'St. Louis University', 2, 1, '2021-12-07 03:35:09'),
('f5541a36-0ef2-4483-b849-bc6967a7ce6b', 'Braille', 2, 1, '2021-12-07 03:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `category_administrators`
--

CREATE TABLE `category_administrators` (
  `category_id` varchar(64) DEFAULT NULL,
  `administrator_id` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_administrators`
--

INSERT INTO `category_administrators` (`category_id`, `administrator_id`) VALUES
('0c0b7ed8-d54e-47b9-a32c-fb28c857ac1b', '110765fa-66d8-40d1-9e44-0d3b35b71441'),
('1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', '29ec4b36-707a-46a2-9886-f87ce769c49f');

-- --------------------------------------------------------

--
-- Table structure for table `category_enrollments`
--

CREATE TABLE `category_enrollments` (
  `category_id` varchar(64) DEFAULT NULL,
  `student_id` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_enrollments`
--

INSERT INTO `category_enrollments` (`category_id`, `student_id`) VALUES
('1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', 'ce996607-1d29-4d3f-8eb7-db225a683c3e'),
('1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b'),
('1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', '85ae34e3-107c-4fd2-8e0d-e5183cb3b7d2'),
('1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008'),
('1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd'),
('1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', '67d5a50f-fc6b-400b-8675-14286c455add');

-- --------------------------------------------------------

--
-- Table structure for table `cohorts`
--

CREATE TABLE `cohorts` (
  `id` varchar(64) NOT NULL,
  `cohort_name` varchar(64) DEFAULT NULL,
  `facilitator_id` varchar(64) DEFAULT NULL,
  `course_id` varchar(64) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cohorts`
--

INSERT INTO `cohorts` (`id`, `cohort_name`, `facilitator_id`, `course_id`, `created`) VALUES
('03314c28-93b5-422f-bccd-1e7a812acc85', 'Geo Class', 'b2412235-ed3b-4b8d-af97-51849a2a430d', 'd282710f-a2c2-45c3-ab4b-b3e7fa7dea0f', '2021-12-09 17:32:43'),
('065f6ea2-b5d6-412e-8421-ca89ca793cb6', 'Beta Cohort', 'b2412235-ed3b-4b8d-af97-51849a2a430d', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '2021-12-09 17:15:28'),
('429532b6-5230-4c45-8a34-ed4d367998bf', 'Alpha Cohort', '110765fa-66d8-40d1-9e44-0d3b35b71441', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '2021-12-08 17:09:59'),
('cb498b95-e5f9-4792-99e6-6a9c99de9f3c', 'First Period', '3eddef98-6e0e-4f15-bda9-53824126a33e', 'b1029147-4f54-47c5-8d73-9efea20d2e0c', '2021-12-13 23:36:11'),
('cbad12f9-e816-484a-b55a-9b6f2b3812dc', 'Kappa Cohort', '29ec4b36-707a-46a2-9886-f87ce769c49f', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '2021-12-10 00:18:39'),
('e949c11c-e4a9-4ce7-b443-4617b1ca8ab8', 'Hello Cohort', '3eddef98-6e0e-4f15-bda9-53824126a33e', 'd282710f-a2c2-45c3-ab4b-b3e7fa7dea0f', '2021-12-09 21:32:51');

-- --------------------------------------------------------

--
-- Table structure for table `cohort_enrollments`
--

CREATE TABLE `cohort_enrollments` (
  `cohort_id` varchar(64) DEFAULT NULL,
  `student_id` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cohort_enrollments`
--

INSERT INTO `cohort_enrollments` (`cohort_id`, `student_id`) VALUES
('429532b6-5230-4c45-8a34-ed4d367998bf', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008'),
('429532b6-5230-4c45-8a34-ed4d367998bf', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd'),
('065f6ea2-b5d6-412e-8421-ca89ca793cb6', '67d5a50f-fc6b-400b-8675-14286c455add'),
('03314c28-93b5-422f-bccd-1e7a812acc85', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008'),
('e949c11c-e4a9-4ce7-b443-4617b1ca8ab8', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd'),
('e949c11c-e4a9-4ce7-b443-4617b1ca8ab8', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b'),
('cbad12f9-e816-484a-b55a-9b6f2b3812dc', '67d5a50f-fc6b-400b-8675-14286c455add'),
('cbad12f9-e816-484a-b55a-9b6f2b3812dc', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008'),
('cb498b95-e5f9-4792-99e6-6a9c99de9f3c', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` varchar(64) NOT NULL,
  `course_name` text DEFAULT NULL,
  `course_desc` text DEFAULT NULL,
  `topic_id` varchar(64) DEFAULT NULL,
  `course_img` text DEFAULT NULL,
  `iframe` text DEFAULT NULL,
  `access_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `course_desc`, `topic_id`, `course_img`, `iframe`, `access_id`, `admin_id`, `timestamp`) VALUES
('23ef30b6-62d1-4001-a0e9-74d55dfbf66d', 'Algebra II', 'A description of my course will go here', '05c2ef38-9caa-477e-8c8f-dd2b8e4630cb', '', '', 1, 1, '2021-12-07 03:35:09'),
('7c436aeb-9c1a-4325-a9b7-858b1a766bec', 'Algebra I', 'A description of my course will go here', '05c2ef38-9caa-477e-8c8f-dd2b8e4630cb', '', '', 1, 1, '2021-12-07 03:35:09'),
('b1029147-4f54-47c5-8d73-9efea20d2e0c', 'Linear Algebra', 'A description of my course will go here', '05c2ef38-9caa-477e-8c8f-dd2b8e4630cb', '', '', 4, 1, '2021-12-07 03:35:09'),
('b1efc484-f14c-4a48-8f67-ae08cb924e0e', 'Drama and Comparative Literature', 'This is a survey course of 19th Century Literature (maybe).', '44d57fe5-1615-4b43-b338-b3558ffdff70', '', '', 3, 1, '2021-12-07 03:35:09'),
('d282710f-a2c2-45c3-ab4b-b3e7fa7dea0f', 'Geometry', 'A description of my course will go here', '05c2ef38-9caa-477e-8c8f-dd2b8e4630cb', '', '', 3, 1, '2021-12-07 03:35:09'),
('fb6767c0-7f17-44a4-8b68-787a516f2edf', 'Drama and Comparative Literature II', '', '44d57fe5-1615-4b43-b338-b3558ffdff70', '', '', 3, 1, '2021-12-07 16:58:28'),
('fd15388d-ea7a-4274-9a2d-078a6e248969', 'Precalulus', 'A description of my course will go here', '05c2ef38-9caa-477e-8c8f-dd2b8e4630cb', '', '', 3, 1, '2021-12-07 03:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `course_administrators`
--

CREATE TABLE `course_administrators` (
  `course_id` varchar(64) DEFAULT NULL,
  `administrator_id` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course_administrators`
--

INSERT INTO `course_administrators` (`course_id`, `administrator_id`) VALUES
('b1efc484-f14c-4a48-8f67-ae08cb924e0e', 'ae52856f-65b9-45c8-bf9f-3ae6f3e6a5f2'),
('d282710f-a2c2-45c3-ab4b-b3e7fa7dea0f', 'ae52856f-65b9-45c8-bf9f-3ae6f3e6a5f2');

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `message_id` varchar(64) NOT NULL,
  `recipient_ids` varchar(1024) NOT NULL,
  `recipient_names` varchar(1024) NOT NULL,
  `recipient_colors` varchar(1024) NOT NULL,
  `sender_ids` varchar(1024) NOT NULL,
  `sender_names` varchar(1024) NOT NULL,
  `sender_colors` varchar(1024) NOT NULL,
  `date_time` datetime DEFAULT NULL,
  `read_unread` tinyint(1) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `message` longtext NOT NULL,
  `sender_folder` varchar(16) NOT NULL,
  `recipient_folder` varchar(16) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` varchar(64) NOT NULL,
  `lesson_name` text DEFAULT NULL,
  `course_id` varchar(64) DEFAULT NULL,
  `editor_html` text DEFAULT NULL,
  `media_dir` text DEFAULT NULL,
  `access_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `lesson_name`, `course_id`, `editor_html`, `media_dir`, `access_id`, `admin_id`, `timestamp`) VALUES
('2d8b341e-425f-4750-8ecd-055f1c3fb502', 'Basic Shapes', 'd282710f-a2c2-45c3-ab4b-b3e7fa7dea0f', NULL, 'media/', 1, 1, '2021-12-07 17:10:43'),
('3affc0e3-0d5e-449b-b42a-d1107550b5d3', 'Lesson 1', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '<h3>Lesson 1 Instructions: </h1><p>Watch videos 1-3 below.</p><p>Complete exercises 1-36 on page 59 of the workbook.</p>', 'media/', 1, 1, '2021-12-07 03:35:09'),
('955a9b04-418d-4fe9-b8d9-d54d420f27b6', 'Linear Lesson', 'b1029147-4f54-47c5-8d73-9efea20d2e0c', NULL, 'media/', 1, 1, '2021-12-10 20:08:54'),
('fae0255f-d67f-4d37-8b3a-c49c135082e3', 'Intermediate Math', '23ef30b6-62d1-4001-a0e9-74d55dfbf66d', NULL, 'media/', 1, 1, '2021-12-08 04:43:01');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` varchar(64) NOT NULL,
  `course_id` varchar(64) DEFAULT NULL,
  `lesson_id` varchar(64) DEFAULT NULL,
  `parent_dir` text DEFAULT NULL,
  `src_path` text DEFAULT NULL,
  `order_pos` int(11) DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `display_name` text DEFAULT NULL,
  `access_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `required` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `course_id`, `lesson_id`, `parent_dir`, `src_path`, `order_pos`, `icon`, `display_name`, `access_id`, `admin_id`, `timestamp`, `required`) VALUES
('545a5b2e-4216-43f8-bd9e-afea773178f6', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 'media', 'media/1/thepowerofbelieving.ogg', 0, 'icon-play', 'The Power of Believing Two', 1, 1, '2021-12-07 03:35:09', 0),
('a91333f7-f09c-440e-a36c-c783fa124f4e', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 'media', 'media/1/thepowerofbelieving.ogg', 0, 'icon-play', 'The Power of Believing One', 1, 1, '2021-12-07 03:35:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `media_progress`
--

CREATE TABLE `media_progress` (
  `id` varchar(64) NOT NULL,
  `course_id` varchar(64) DEFAULT NULL,
  `lesson_id` varchar(64) DEFAULT NULL,
  `student_id` text DEFAULT NULL,
  `media_id` varchar(64) DEFAULT NULL,
  `file_location` text DEFAULT NULL,
  `file_type` text DEFAULT NULL,
  `file_name` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `current_pos` int(11) DEFAULT NULL,
  `completed` tinyint(4) DEFAULT NULL,
  `reflection` text DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `required` tinyint(4) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `media_progress`
--

INSERT INTO `media_progress` (`id`, `course_id`, `lesson_id`, `student_id`, `media_id`, `file_location`, `file_type`, `file_name`, `duration`, `current_pos`, `completed`, `reflection`, `deleted`, `required`, `timestamp`) VALUES
('00648e5c-7b83-4457-8ae3-f3ce199e26e0', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', '3eddef98-6e0e-4f15-bda9-53824126a33e', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'n/a', 'n/a', 'Subtracting', 0, 0, 1, '', 0, 0, '2021-12-07 04:13:19'),
('545a5b2e-4216-43f8-bd9e-afea773178f6', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', '67d5a50f-fc6b-400b-8675-14286c455add', '545a5b2e-4216-43f8-bd9e-afea773178f6', 'media/2/thepowerofbelieving.ogg', 'video', 'The Power of Believing Two', 0, 0, 0, '', 0, 0, '2021-12-07 03:35:09'),
('860f799f-8c14-4ff5-86a0-eb257e332207', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'n/a', 'n/a', 'Subtracting', 0, 0, 1, '', 0, 0, '2021-12-07 20:17:50'),
('9380d294-01e5-4c19-b641-76693514ece4', '23ef30b6-62d1-4001-a0e9-74d55dfbf66d', 'fae0255f-d67f-4d37-8b3a-c49c135082e3', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'aaa147b5-9e99-40bd-b048-7993bdcb4c96', 'n/a', 'n/a', 'Yet another quiz', 0, 0, 1, '', 0, 0, '2021-12-11 02:50:13'),
('a8a03827-e72a-40d8-96f5-2dbd915ae2a1', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 'ce996607-1d29-4d3f-8eb7-db225a683c3e', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'n/a', 'n/a', 'Adding', 0, 0, 1, '', 0, 0, '2021-12-07 20:15:01'),
('a91333f7-f09c-440e-a36c-c783fa124f4e', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', '67d5a50f-fc6b-400b-8675-14286c455add', 'a91333f7-f09c-440e-a36c-c783fa124f4e', 'media/1/thepowerofbelieving.ogg', 'video', 'The Power of Believing One', 0, 0, 0, '', 0, 0, '2021-12-07 03:35:09'),
('be77b8d2-ae6a-47dd-b1fe-132d1b116672', '23ef30b6-62d1-4001-a0e9-74d55dfbf66d', 'fae0255f-d67f-4d37-8b3a-c49c135082e3', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008', 'f763b6e4-3ef4-41a2-ac50-f2699045f0e5', 'n/a', 'n/a', 'sdfb', 0, 0, 1, '', 0, 0, '2021-12-11 05:20:49'),
('ca695cdc-a063-4134-a3bb-dde979dacb8d', '23ef30b6-62d1-4001-a0e9-74d55dfbf66d', 'fae0255f-d67f-4d37-8b3a-c49c135082e3', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', '26734a8c-a57a-4039-a603-205f721dee0d', 'n/a', 'n/a', 'can you dig it?', 0, 0, 1, '', 0, 0, '2021-12-08 04:46:22'),
('cdaf8e1c-afdb-4759-af46-e061ac1ac6ab', '23ef30b6-62d1-4001-a0e9-74d55dfbf66d', 'fae0255f-d67f-4d37-8b3a-c49c135082e3', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd', '3ae42fd6-1cc3-49f8-b514-d95a3fd349c4', 'n/a', 'n/a', 'Another Quiz', 0, 0, 1, '', 0, 0, '2021-12-11 02:50:02'),
('db8ec456-ebc0-450c-88c3-557508867bcf', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'n/a', 'n/a', 'Adding', 0, 0, 1, '', 0, 0, '2021-12-08 23:11:00'),
('ed1fc202-37b6-4da1-860a-37444a49ab8a', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 'e214a147-0d06-4322-9380-6bdd8c2367c0', 'b35365bd-56ed-4bec-bdeb-a46c5067de5a', 'n/a', 'n/a', 'Multiplying', 0, 0, 1, '', 0, 0, '2021-12-12 22:15:56'),
('ee6662df-6fa8-4484-9878-0af603f47d07', '7c436aeb-9c1a-4325-a9b7-858b1a766bec', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', '1dc1407d-c52f-48fb-9d5e-8e08c41e8982', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'n/a', 'n/a', 'Adding', 0, 0, 1, '', 0, 0, '2021-12-07 04:12:03');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` varchar(64) NOT NULL,
  `question_text` text DEFAULT NULL,
  `bank_id` varchar(64) DEFAULT '1',
  `admin_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_text`, `bank_id`, `admin_id`, `created`, `modified`) VALUES
('32cd7211-45ca-4629-ad74-85d0c420e49d', '<p>2 + 2 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-06 22:05:11', '2021-12-07 04:05:11'),
('3a815229-8c22-4f6a-bca0-a25245adec0d', '<p>7 / 7 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('49a2e78f-461a-4d65-adf8-0e9ada5afd37', '<p>5 * 5 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 15:31:19', '2021-12-12 21:31:19'),
('4fd62e33-19e6-4588-b387-a99242029363', '<p>5 * 6 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 15:31:23', '2021-12-12 21:31:23'),
('5362a81c-fcab-4a53-8344-9d41d89c97ed', '<p>9 / 3 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('58b35965-b647-4471-a93a-f133ff14f997', '<p>4 - 2 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('58c6bae5-1128-4926-8749-0b2bd46aba83', '<p>8 / 4 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('690f3d28-c529-465a-8f5f-f3e46c18962f', '<p>Are you here?</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-10 14:09:31', '2021-12-10 20:09:31'),
('769af362-1c54-45ad-aa57-7919c3d5edff', '<p>5 + 15 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-10 20:47:35', '2021-12-11 02:47:35'),
('7867e12f-cd2d-4d2a-8009-d4f3c5a1b945', '<p>10 - 9 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('84fa10ae-0fa7-4c09-90a3-53098aa95f4d', '<p>say yes</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-10 23:20:19', '2021-12-11 05:20:19'),
('85f52d04-c923-4199-aead-785c02230ab6', '<p>5 * 8 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 15:35:12', '2021-12-12 21:35:12'),
('bef42807-942f-43c7-a2d7-eb087ffa95cd', '<p>7 * 7 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 16:15:23', '2021-12-12 22:15:23'),
('cbf196b0-d358-4e81-a4d3-723d1602f955', '<p>1 + 1 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-06 22:05:11', '2021-12-07 04:05:11'),
('d213b25b-ab62-43c1-b6cb-112540ad6ef7', '<p>5 + 5 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-07 22:44:08', '2021-12-08 04:44:08'),
('d63b11c3-b0e8-4771-9da3-b5709017568a', '<p>8 - 2 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-06 22:06:37', '2021-12-07 04:06:37'),
('d7976888-e7bb-47d6-8b43-0e6643162aaa', '<p>where&#39;s santa?</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-10 20:48:20', '2021-12-11 02:48:20'),
('e762a501-e1c1-4bdf-8999-5ca23529e602', '<p>5 * 1 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 15:35:12', '2021-12-12 21:35:12'),
('ec5ffdf6-5ab1-48a3-b7e9-b10280fe19c3', '<p>8 * 8 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-12 16:15:23', '2021-12-12 22:15:23'),
('ef4153bd-e17e-4d13-a1b9-529d7007712d', '<p>2 - 2 =</p>\n', 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 1, '2021-12-07 22:44:08', '2021-12-08 04:44:08');

-- --------------------------------------------------------

--
-- Table structure for table `question_bank`
--

CREATE TABLE `question_bank` (
  `id` varchar(64) NOT NULL,
  `bank_name` varchar(64) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_bank`
--

INSERT INTO `question_bank` (`id`, `bank_name`, `created`, `modified`) VALUES
('c5312268-5404-4eeb-afc2-5b9c2f63d9bd', 'Unfiled Questions', '2021-12-06 21:35:09', '2021-12-07 03:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` varchar(64) NOT NULL,
  `quiz_name` text DEFAULT NULL,
  `quiz_desc` text DEFAULT NULL,
  `lesson_id` varchar(64) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `quiz_name`, `quiz_desc`, `lesson_id`, `admin_id`, `created`, `modified`) VALUES
('26734a8c-a57a-4039-a603-205f721dee0d', 'Polynomials', '<p>What is</p>\n', 'fae0255f-d67f-4d37-8b3a-c49c135082e3', 1, '2021-12-07 22:44:08', '2021-12-08 04:44:08'),
('3ae42fd6-1cc3-49f8-b514-d95a3fd349c4', 'Factoring', '<p>This is another quiz.</p>\n', 'fae0255f-d67f-4d37-8b3a-c49c135082e3', 1, '2021-12-10 20:47:35', '2021-12-11 02:47:35'),
('43bb9a83-5cf2-48f3-a31e-db631aa38d48', 'Division', '<p>This is a test of your division skills.</p>\n', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 1, '2021-12-12 16:13:06', '2021-12-12 22:13:06'),
('58aa8c08-feff-4ba5-a118-55e5be960d09', 'Adding', '<p>asdf</p>\n', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 1, '2021-12-06 22:05:11', '2021-12-07 04:05:11'),
('aaa147b5-9e99-40bd-b048-7993bdcb4c96', 'Quadratic Equation', '<p>This is it?</p>\n', 'fae0255f-d67f-4d37-8b3a-c49c135082e3', 1, '2021-12-10 20:48:20', '2021-12-11 02:48:20'),
('b35365bd-56ed-4bec-bdeb-a46c5067de5a', 'Multiplying', '<p>This is a test of your multiplying skills.</p>\n', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 1, '2021-12-12 16:15:23', '2021-12-12 22:15:23'),
('f763b6e4-3ef4-41a2-ac50-f2699045f0e5', 'Binomials', '<p>sdfasf</p>\n', 'fae0255f-d67f-4d37-8b3a-c49c135082e3', 1, '2021-12-10 23:20:19', '2021-12-11 05:20:19'),
('f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'Subtracting', '<p>poiu</p>\n', '3affc0e3-0d5e-449b-b42a-d1107550b5d3', 1, '2021-12-06 22:06:36', '2021-12-07 04:06:36'),
('f9394e5e-35d6-4784-86a4-7bd66c52d2ea', 'Bogus Quiz', '<p>This is a bogus quiz.</p>\n', '955a9b04-418d-4fe9-b8d9-d54d420f27b6', 1, '2021-12-10 14:09:31', '2021-12-10 20:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `quiz_id` varchar(64) DEFAULT NULL,
  `question_id` varchar(64) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `question_position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`quiz_id`, `question_id`, `points`, `question_position`) VALUES
('58aa8c08-feff-4ba5-a118-55e5be960d09', 'cbf196b0-d358-4e81-a4d3-723d1602f955', 50, 1),
('58aa8c08-feff-4ba5-a118-55e5be960d09', '32cd7211-45ca-4629-ad74-85d0c420e49d', 50, 2),
('f80d4fc2-b73d-4c20-96ea-18a32eb0c233', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945', 33, 1),
('f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'd63b11c3-b0e8-4771-9da3-b5709017568a', 33, 2),
('f80d4fc2-b73d-4c20-96ea-18a32eb0c233', '58b35965-b647-4471-a93a-f133ff14f997', 33, 3),
('26734a8c-a57a-4039-a603-205f721dee0d', 'ef4153bd-e17e-4d13-a1b9-529d7007712d', 10, 1),
('26734a8c-a57a-4039-a603-205f721dee0d', 'd213b25b-ab62-43c1-b6cb-112540ad6ef7', 10, 2),
('f9394e5e-35d6-4784-86a4-7bd66c52d2ea', '690f3d28-c529-465a-8f5f-f3e46c18962f', 100, 1),
('3ae42fd6-1cc3-49f8-b514-d95a3fd349c4', '769af362-1c54-45ad-aa57-7919c3d5edff', 100, 1),
('aaa147b5-9e99-40bd-b048-7993bdcb4c96', 'd7976888-e7bb-47d6-8b43-0e6643162aaa', 100, 1),
('f763b6e4-3ef4-41a2-ac50-f2699045f0e5', '84fa10ae-0fa7-4c09-90a3-53098aa95f4d', 1, 1),
('43bb9a83-5cf2-48f3-a31e-db631aa38d48', '3a815229-8c22-4f6a-bca0-a25245adec0d', 10, 1),
('43bb9a83-5cf2-48f3-a31e-db631aa38d48', '58c6bae5-1128-4926-8749-0b2bd46aba83', 10, 2),
('43bb9a83-5cf2-48f3-a31e-db631aa38d48', '5362a81c-fcab-4a53-8344-9d41d89c97ed', 10, 3),
('b35365bd-56ed-4bec-bdeb-a46c5067de5a', 'bef42807-942f-43c7-a2d7-eb087ffa95cd', 10, 1),
('b35365bd-56ed-4bec-bdeb-a46c5067de5a', 'ec5ffdf6-5ab1-48a3-b7e9-b10280fe19c3', 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` varchar(64) NOT NULL,
  `setting` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL,
  `read_only` tinyint(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting`, `value`, `read_only`, `timestamp`) VALUES
('bc1853ad-66e0-4bbb-a5fe-d79632d07b1d', 'site_url', 'unlockedlabs.com', 1, '2021-12-07 03:35:09'),
('bd1853ad-66e0-4bbb-a5fe-d79632d07b1d', 'gamification_enabled', 'true', 0, '2021-12-07 03:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` varchar(64) NOT NULL,
  `assignment_id` varchar(64) DEFAULT NULL,
  `student_id` varchar(64) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `attempt` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `grade` varchar(32) DEFAULT NULL,
  `questions` text DEFAULT NULL,
  `submitted_answers` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `submitted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `assignment_id`, `student_id`, `type`, `attempt`, `score`, `grade`, `questions`, `submitted_answers`, `comments`, `submitted`) VALUES
('06b4f0a9-25c2-4ee5-b000-cc987d2b5698', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'quiz', 1, 66, '66.67', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945,d63b11c3-b0e8-4771-9da3-b5709017568a,58b35965-b647-4471-a93a-f133ff14f997', '1,6,5', '', '2021-12-08 23:11:56'),
('0adc96f2-061c-4700-8af7-f532d620ca3e', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008', 'quiz', 1, 50, '50', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '2,5', '', '2021-12-08 23:10:42'),
('0fdd76b9-882e-4895-8886-417e08e0f900', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'quiz', 1, 66, '66.67', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945,d63b11c3-b0e8-4771-9da3-b5709017568a,58b35965-b647-4471-a93a-f133ff14f997', '1,6,5', '', '2021-12-07 20:17:23'),
('169ed143-314b-4406-a6a0-72f1b53aca91', '58aa8c08-feff-4ba5-a118-55e5be960d09', '85ae34e3-107c-4fd2-8e0d-e5183cb3b7d2', 'quiz', 1, 50, '50', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '2,5', '', '2021-12-07 20:24:56'),
('2f24d634-512a-4270-a6f2-4869b0b37a75', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'ce996607-1d29-4d3f-8eb7-db225a683c3e', 'quiz', 2, 50, '50', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '3,4', '', '2021-12-07 20:15:12'),
('318c0983-5e92-4c33-a4fa-b92b77c305b7', 'f763b6e4-3ef4-41a2-ac50-f2699045f0e5', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008', 'quiz', 1, 1, '100', '84fa10ae-0fa7-4c09-90a3-53098aa95f4d', 'yes', '', '2021-12-11 05:20:49'),
('38c4104d-1cd1-4aad-b472-e19223ba9aba', '26734a8c-a57a-4039-a603-205f721dee0d', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'quiz', 2, 10, '50', 'ef4153bd-e17e-4d13-a1b9-529d7007712d,d213b25b-ab62-43c1-b6cb-112540ad6ef7', '0,1', '', '2021-12-11 02:49:48'),
('3f23f9ff-84d8-4c9d-b587-955dcedd1cc4', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'ce996607-1d29-4d3f-8eb7-db225a683c3e', 'quiz', 3, 0, '0', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '3,5', '', '2021-12-07 20:15:32'),
('403f143c-59fe-47fa-a8d3-0272c675b4a2', '26734a8c-a57a-4039-a603-205f721dee0d', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'quiz', 1, 10, '50', 'ef4153bd-e17e-4d13-a1b9-529d7007712d,d213b25b-ab62-43c1-b6cb-112540ad6ef7', '2,10', '', '2021-12-09 19:35:28'),
('56ac1d87-5f4b-4f10-b0ea-f7c80d190646', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'ce996607-1d29-4d3f-8eb7-db225a683c3e', 'quiz', 1, 33, '33.33', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945,d63b11c3-b0e8-4771-9da3-b5709017568a,58b35965-b647-4471-a93a-f133ff14f997', '1,8,5', '', '2021-12-07 20:15:49'),
('57a1a490-983d-4b3e-a361-aaef597d35aa', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008', 'quiz', 1, 33, '33.33', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945,d63b11c3-b0e8-4771-9da3-b5709017568a,58b35965-b647-4471-a93a-f133ff14f997', '2,6,5', '', '2021-12-11 03:56:13'),
('6e4a73ba-dec7-4dcd-9ec6-410fe9dc6c1e', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'ce996607-1d29-4d3f-8eb7-db225a683c3e', 'quiz', 2, 66, '66.67', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945,d63b11c3-b0e8-4771-9da3-b5709017568a,58b35965-b647-4471-a93a-f133ff14f997', '1,6,5', '', '2021-12-07 20:16:05'),
('90f7d6a1-7731-40ab-aa6d-4fc97706600e', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'quiz', 1, 50, '50', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '2,5', '', '2021-12-07 20:16:36'),
('97ce2a9c-d0eb-45f7-8fbb-eeac3a1b66b9', 'aaa147b5-9e99-40bd-b048-7993bdcb4c96', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'quiz', 1, 100, '100', 'd7976888-e7bb-47d6-8b43-0e6643162aaa', 'North Pole', '', '2021-12-11 02:50:13'),
('a20362b0-9761-4ce7-93d6-eb64aa72cfe5', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'quiz', 2, 66, '66.67', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945,d63b11c3-b0e8-4771-9da3-b5709017568a,58b35965-b647-4471-a93a-f133ff14f997', '2,6,2', '', '2021-12-07 20:17:36'),
('a4d3f6da-2fbe-4ed1-8c08-97761158e875', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'quiz', 1, 50, '50', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '2,5', '', '2021-12-08 23:11:33'),
('ab02230f-46d1-4de9-8c36-edde6ec8daef', 'b35365bd-56ed-4bec-bdeb-a46c5067de5a', 'e214a147-0d06-4322-9380-6bdd8c2367c0', 'quiz', 1, 20, '100', 'bef42807-942f-43c7-a2d7-eb087ffa95cd,ec5ffdf6-5ab1-48a3-b7e9-b10280fe19c3', '49,64', '', '2021-12-12 22:15:56'),
('ae0edbd4-21f1-4116-89a1-9ba12d5b0e92', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'b6dfe7b9-7c42-46a2-aee9-fc13fd563008', 'quiz', 2, 100, '100', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '2,4', '', '2021-12-08 23:11:00'),
('b78284ac-6016-4ed8-9b69-3e7be90da4fc', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'quiz', 2, 0, '0', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '3,5', '', '2021-12-07 20:16:53'),
('d7ea5d2a-44f0-42df-b8f0-45e4d96e8f0d', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'quiz', 3, 50, '50', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '2,5', '', '2021-12-07 20:17:07'),
('d85b9e2d-c72c-448f-9ac3-cb7d7eac256c', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'quiz', 3, 99, '100', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945,d63b11c3-b0e8-4771-9da3-b5709017568a,58b35965-b647-4471-a93a-f133ff14f997', '1,6,2', '', '2021-12-07 20:17:50'),
('dd8273e7-a866-4e39-81be-bf38479fc144', '58aa8c08-feff-4ba5-a118-55e5be960d09', 'ce996607-1d29-4d3f-8eb7-db225a683c3e', 'quiz', 1, 100, '100', 'cbf196b0-d358-4e81-a4d3-723d1602f955,32cd7211-45ca-4629-ad74-85d0c420e49d', '2,4', '', '2021-12-07 20:15:01'),
('e8cdcb47-62af-48fd-b125-93d8041a1a3f', '26734a8c-a57a-4039-a603-205f721dee0d', 'e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'quiz', 1, 20, '100', 'ef4153bd-e17e-4d13-a1b9-529d7007712d,d213b25b-ab62-43c1-b6cb-112540ad6ef7', '0,10', '', '2021-12-08 04:46:22'),
('ea1e4a94-944f-40b6-9603-6fe89395e80b', 'f80d4fc2-b73d-4c20-96ea-18a32eb0c233', '85ae34e3-107c-4fd2-8e0d-e5183cb3b7d2', 'quiz', 1, 33, '33.33', '7867e12f-cd2d-4d2a-8009-d4f3c5a1b945,d63b11c3-b0e8-4771-9da3-b5709017568a,58b35965-b647-4471-a93a-f133ff14f997', '1,8,5', '', '2021-12-07 20:25:16'),
('ec1f84ac-0ac4-43ba-a0cc-70d0a4e84d4b', '3ae42fd6-1cc3-49f8-b514-d95a3fd349c4', 'bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'quiz', 1, 100, '100', '769af362-1c54-45ad-aa57-7919c3d5edff', '20', '', '2021-12-11 02:50:01');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` varchar(64) NOT NULL,
  `topic_name` text DEFAULT NULL,
  `category_id` varchar(64) DEFAULT NULL,
  `iframe` text DEFAULT NULL,
  `access_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `topic_name`, `category_id`, `iframe`, `access_id`, `admin_id`, `timestamp`) VALUES
('03a13e51-4569-4e98-9761-2d77e823a5f3', 'Engineering', '22243a35-60e2-4370-8cd2-835e2a2f0066', '', 1, 1, '2021-12-07 03:35:09'),
('05c2ef38-9caa-477e-8c8f-dd2b8e4630cb', 'Math', '1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774', '', 1, 1, '2021-12-07 03:35:09'),
('0cf08ac4-6bab-41d8-8cc6-4bbbb4a14e99', 'Nursing', '22243a35-60e2-4370-8cd2-835e2a2f0066', '', 1, 1, '2021-12-07 03:35:09'),
('20efa169-e00e-48e9-9798-2bd7fd1646a1', 'College of Criminology', 'd711973f-3355-41f6-91eb-e2a7392a67f6', '', 1, 1, '2021-12-07 03:35:09'),
('438323df-c4d7-4a84-b5ca-d0078aedd7a7', 'Women\'s Studies', '0fed849c-cb14-4121-bbd5-a4d458bd6a5f', '', 1, 1, '2021-12-07 03:35:09'),
('44d57fe5-1615-4b43-b338-b3558ffdff70', 'Wash U. Homebrew Courses', '0c0b7ed8-d54e-47b9-a32c-fb28c857ac1b', '', 2, 1, '2021-12-07 03:35:09'),
('875cb191-b59b-4e8c-81ff-4a7d47572f09', 'UMSL Homebrew Courses', 'd711973f-3355-41f6-91eb-e2a7392a67f6', '', 1, 1, '2021-12-07 03:35:09'),
('9d6ad529-282b-4bc9-be16-53fc30504e94', 'UMSL Canvas Courses', 'd711973f-3355-41f6-91eb-e2a7392a67f6', 'http://localhost:3000/', 1, 1, '2021-12-07 03:35:09'),
('a6228817-6967-4897-8856-b9cb3fb622aa', 'Language Arts', '0fed849c-cb14-4121-bbd5-a4d458bd6a5f', '', 2, 1, '2021-12-07 03:35:09'),
('ad1853ad-66e0-4bbb-a5fe-d79632d07b1d', 'Unassigned Courses', '94876a68-f185-4967-b5fb-f90859ffd5a8', '', 1, 1, '2021-12-07 03:35:09'),
('f5bd24fb-81a7-4a95-9cdf-5b09183bcada', 'Wikipedia for Schools', 'ce240c1c-2e71-4ada-a11a-8fe1b033860d', 'http://thelearningcenter.doc.mo.gov:8080/en-wikipedia_for_schools-static/index.html', 1, 1, '2021-12-07 03:35:09'),
('f8613e73-a309-4396-9002-d9dfc56ae8f1', 'Wash U. Canvas Courses', '0c0b7ed8-d54e-47b9-a32c-fb28c857ac1b', 'http://localhost:3000/', 2, 2, '2021-12-07 03:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(64) NOT NULL,
  `username` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `oid` varchar(128) DEFAULT NULL,
  `access_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `logged_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `oid`, `access_id`, `admin_id`, `timestamp`, `logged_in`) VALUES
('110765fa-66d8-40d1-9e44-0d3b35b71441', 'Robert Henke', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'robert@washu.edu', NULL, 1, 4, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('1dc1407d-c52f-48fb-9d5e-8e08c41e8982', 'jody', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'jody@gmail.com', NULL, 1, 1, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('29ec4b36-707a-46a2-9886-f87ce769c49f', 'jessica', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'jessica@gmail.com', NULL, 1, 4, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('3eddef98-6e0e-4f15-bda9-53824126a33e', 'greg', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'greg@gmail.com', NULL, 1, 2, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('493701e6-a0bd-46db-b3f5-ea0831bc5571', 'bill', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'bill@gmail.com', NULL, 1, 1, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('67d5a50f-fc6b-400b-8675-14286c455add', 'joshua', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'joshua@gmail.com', NULL, 1, 1, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('85ae34e3-107c-4fd2-8e0d-e5183cb3b7d2', 'John Smith', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'john.smith@gmail.com', NULL, 1, 1, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('ae52856f-65b9-45c8-bf9f-3ae6f3e6a5f2', 'ashley', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'ashley@gmail.com', NULL, 2, 3, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('b2412235-ed3b-4b8d-af97-51849a2a430d', 'ben', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'ben@gmail.com', NULL, 2, 3, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('b6dfe7b9-7c42-46a2-aee9-fc13fd563008', 'mike', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'mike@gmail.com', NULL, 1, 1, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'chris', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'chris@gmail.com', NULL, 1, 1, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('ce996607-1d29-4d3f-8eb7-db225a683c3e', 'brandon', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'brandon@gmail.com', NULL, 2, 2, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('e214a147-0d06-4322-9380-6bdd8c2367c0', 'haley', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'haley@gmail.com', NULL, 1, 5, '2021-12-07 03:35:09', '0000-00-00 00:00:00'),
('e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'steve', '$2y$10$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La', 'steve@gmail.com', NULL, 1, 1, '2021-12-07 03:35:09', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_gamification`
--

CREATE TABLE `user_gamification` (
  `id` varchar(64) NOT NULL,
  `username` text DEFAULT NULL,
  `coins` int(11) DEFAULT NULL,
  `coin_balance` int(11) DEFAULT NULL,
  `user_status` varchar(16) DEFAULT NULL,
  `logins` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_gamification`
--

INSERT INTO `user_gamification` (`id`, `username`, `coins`, `coin_balance`, `user_status`, `logins`, `timestamp`) VALUES
('110765fa-66d8-40d1-9e44-0d3b35b71441', 'Robert Henke', 500, 0, 'NEW USER', 36, '2021-12-07 03:35:09'),
('1dc1407d-c52f-48fb-9d5e-8e08c41e8982', 'jody', 500, 0, 'NEW USER', 4, '2021-12-07 03:35:09'),
('29ec4b36-707a-46a2-9886-f87ce769c49f', 'jessica', 500, 0, 'NEW USER', 34, '2021-12-07 03:35:09'),
('3eddef98-6e0e-4f15-bda9-53824126a33e', 'greg', 500, 0, 'NEW USER', 6, '2021-12-07 03:35:09'),
('493701e6-a0bd-46db-b3f5-ea0831bc5571', 'bill', 0, 0, 'NEW USER', 0, '2021-12-07 03:35:09'),
('67d5a50f-fc6b-400b-8675-14286c455add', 'joshua', 500, 0, 'NEW USER', 4, '2021-12-07 03:35:09'),
('85ae34e3-107c-4fd2-8e0d-e5183cb3b7d2', 'John Smith', 500, 0, 'NEW USER', 3, '2021-12-07 03:35:09'),
('ae52856f-65b9-45c8-bf9f-3ae6f3e6a5f2', 'ashley', 500, 0, 'NEW USER', 23, '2021-12-07 03:35:09'),
('b2412235-ed3b-4b8d-af97-51849a2a430d', 'ben', 500, 0, 'NEW USER', 35, '2021-12-07 03:35:09'),
('b6dfe7b9-7c42-46a2-aee9-fc13fd563008', 'mike', 500, 0, 'NEW USER', 50, '2021-12-07 03:35:09'),
('bb4fb53f-c1f1-4163-bee0-b1163af688cd', 'chris', 500, 0, 'NEW USER', 31, '2021-12-07 03:35:09'),
('ce996607-1d29-4d3f-8eb7-db225a683c3e', 'brandon', 500, 0, 'NEW USER', 5, '2021-12-07 03:35:09'),
('e214a147-0d06-4322-9380-6bdd8c2367c0', 'haley', 500, 0, 'NEW USER', 288, '2021-12-07 03:35:09'),
('e3b58672-a8f8-4992-bdad-44cb3b855a3b', 'steve', 500, 0, 'NEW USER', 7, '2021-12-07 03:35:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_levels`
--
ALTER TABLE `access_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_levels`
--
ALTER TABLE `admin_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_privileges`
--
ALTER TABLE `admin_privileges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `access_id` (`access_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `category_administrators`
--
ALTER TABLE `category_administrators`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `administrator_id` (`administrator_id`);

--
-- Indexes for table `category_enrollments`
--
ALTER TABLE `category_enrollments`
  ADD KEY `category_id` (`category_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `cohorts`
--
ALTER TABLE `cohorts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facilitator_id` (`facilitator_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `cohort_enrollments`
--
ALTER TABLE `cohort_enrollments`
  ADD KEY `cohort_id` (`cohort_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `access_id` (`access_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `course_administrators`
--
ALTER TABLE `course_administrators`
  ADD KEY `course_id` (`course_id`),
  ADD KEY `administrator_id` (`administrator_id`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `access_id` (`access_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `access_id` (`access_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `media_progress`
--
ALTER TABLE `media_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_id` (`bank_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `question_bank`
--
ALTER TABLE `question_bank`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_name` (`bank_name`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `access_id` (`access_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `access_id` (`access_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `user_gamification`
--
ALTER TABLE `user_gamification`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_levels`
--
ALTER TABLE `access_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin_levels`
--
ALTER TABLE `admin_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `access_levels` (`id`),
  ADD CONSTRAINT `categories_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_levels` (`id`);

--
-- Constraints for table `category_administrators`
--
ALTER TABLE `category_administrators`
  ADD CONSTRAINT `category_administrators_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_administrators_ibfk_2` FOREIGN KEY (`administrator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `category_enrollments`
--
ALTER TABLE `category_enrollments`
  ADD CONSTRAINT `category_enrollments_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cohorts`
--
ALTER TABLE `cohorts`
  ADD CONSTRAINT `cohorts_ibfk_1` FOREIGN KEY (`facilitator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `cohorts_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cohort_enrollments`
--
ALTER TABLE `cohort_enrollments`
  ADD CONSTRAINT `cohort_enrollments_ibfk_1` FOREIGN KEY (`cohort_id`) REFERENCES `cohorts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cohort_enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `access_levels` (`id`),
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_levels` (`id`);

--
-- Constraints for table `course_administrators`
--
ALTER TABLE `course_administrators`
  ADD CONSTRAINT `course_administrators_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_administrators_ibfk_2` FOREIGN KEY (`administrator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `access_levels` (`id`),
  ADD CONSTRAINT `lessons_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_levels` (`id`);

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `access_levels` (`id`),
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_levels` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`bank_id`) REFERENCES `question_bank` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quiz_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `access_levels` (`id`),
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_levels` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`access_id`) REFERENCES `access_levels` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin_levels` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
