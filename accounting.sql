-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2021 at 09:51 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounting`
--

-- --------------------------------------------------------

--
-- Table structure for table `acc_advances`
--

CREATE TABLE `acc_advances` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `advance_amount` float NOT NULL,
  `advance_date` datetime NOT NULL,
  `remarks` text NOT NULL,
  `reference_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acc_chart_of_accounts_detail`
--

CREATE TABLE `acc_chart_of_accounts_detail` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `subcategory1` int(11) NOT NULL,
  `subcategory2` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rate` double NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `modified_at` datetime DEFAULT current_timestamp(),
  `modified_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `is_bank` tinyint(1) NOT NULL,
  `is_defaultBank` int(11) NOT NULL DEFAULT 0,
  `is_cash` tinyint(1) NOT NULL,
  `is_deletable` tinyint(4) NOT NULL DEFAULT 1,
  `school_item_id` int(11) NOT NULL DEFAULT 0,
  `school_item_type` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `acc_chart_of_accounts_detail`
--

INSERT INTO `acc_chart_of_accounts_detail` (`id`, `type`, `name`, `category`, `subcategory1`, `subcategory2`, `code`, `description`, `rate`, `status`, `created_at`, `created_by`, `modified_at`, `modified_by`, `financial_year`, `is_bank`, `is_defaultBank`, `is_cash`, `is_deletable`, `school_item_id`, `school_item_type`) VALUES
(1, 3, 'Tuition Fee', 6, 7, 8, 'STF', 'Tuition Fee', 0, 1, '2020-01-22 05:17:08', 1, '2020-01-22 05:17:08', 1, 1, 0, 0, 0, 0, 1, 'fee_type'),
(2, 3, 'Exam Fee', 6, 7, 8, 'SEF', 'Exam Fee', 0, 1, '2020-01-22 05:17:33', 1, '2020-01-22 05:17:33', 1, 1, 0, 0, 0, 0, 2, 'fee_type'),
(3, 3, 'Computer Fee', 6, 7, 8, 'SCF', 'Computer Fee', 0, 1, '2020-01-22 05:18:00', 1, '2020-01-22 05:18:00', 1, 1, 0, 0, 0, 0, 3, 'fee_type'),
(4, 3, 'Library Fee', 6, 7, 8, 'SLF', 'Library Fee', 0, 1, '2020-01-22 05:18:20', 1, '2020-01-22 05:18:20', 1, 1, 0, 0, 0, 0, 4, 'fee_type'),
(5, 3, 'Extra Curricular Fee', 6, 7, 8, 'SECF', 'Extra Curricular Fee', 0, 1, '2020-01-22 05:18:40', 1, '2020-01-22 05:18:40', 1, 1, 0, 0, 0, 0, 5, 'fee_type'),
(6, 3, 'Medical Fee', 6, 7, 8, 'SMF', 'Medical Fee', 0, 1, '2020-01-22 05:19:08', 1, '2020-01-22 05:19:08', 1, 1, 0, 0, 0, 0, 6, 'fee_type'),
(7, 3, 'Fine', 6, 7, 9, 'FINE', 'Fine', 0, 1, '2020-01-24 05:55:09', 1, '2020-01-24 05:55:09', 1, 1, 0, 0, 0, 0, 0, ''),
(8, 3, 'Hostel Fee', 6, 7, 8, 'HOSFEE', 'Hostel Fee', 0, 1, '2020-01-28 06:27:11', 1, '2020-01-28 06:27:47', 1, 1, 0, 0, 0, 0, 0, ''),
(9, 3, 'Transport Fee', 6, 7, 8, 'TRANFEE', 'Transport Fee', 0, 1, '2020-01-28 06:27:33', 1, '2020-01-28 06:27:54', 1, 1, 0, 0, 0, 0, 0, ''),
(10, 1, 'Prabhupay', 10, 15, 18, 'EWALLETPRABHUPAY', 'Wallet for receiving payments done via prabhupay', 0, 1, '2020-02-27 12:20:25', 2, '2020-02-27 12:20:25', 2, 1, 0, 0, 0, 0, 0, ''),
(11, 1, 'Cash in hand', 10, 15, 16, 'CASHINHAND', 'Default cash asset', 0, 1, '2020-02-27 12:21:13', 2, '2020-02-27 12:21:13', 2, 1, 0, 0, 0, 0, 0, ''),
(12, 1, 'Default Bank', 10, 15, 17, 'BNK', 'Default Bank where fees paid via cheque gets credited.', 0, 1, '2020-10-07 16:47:11', 2, '2020-10-07 16:47:11', 2, 1, 1, 1, 0, 0, 0, ''),
(13, 5, 'Profit', 19, 20, 21, 'YRLYPROFIT', 'Yearly profit opening value', 0, 1, '2020-10-07 16:51:58', 2, '2020-10-07 16:51:58', 2, 1, 0, 0, 0, 0, 0, ''),
(14, 1, 'Outstanding Income', 10, 22, 23, 'ACCINCOME', 'Accrued Income', 0, 1, '2021-03-10 10:14:54', 1, '2021-03-10 10:14:54', 1, 1, 0, 0, 0, 0, 0, ''),
(15, 2, 'Outstanding Expenses', 11, 24, 25, 'ACCEXPENSE', 'Accrued Expenses', 0, 1, '2021-03-10 10:15:44', 1, '2021-03-10 10:15:44', 1, 1, 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `acc_coa_categories`
--

CREATE TABLE `acc_coa_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lft` int(11) NOT NULL DEFAULT 0,
  `rgt` int(11) NOT NULL DEFAULT 0,
  `level` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `created_user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_on` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `financial_year` int(11) NOT NULL,
  `deletable` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acc_coa_categories`
--

INSERT INTO `acc_coa_categories` (`id`, `parent_id`, `lft`, `rgt`, `level`, `title`, `slug`, `published`, `created_user_id`, `created_on`, `modified_on`, `financial_year`, `deletable`) VALUES
(1, 0, 1, 12, 1, 'Assets', 'assets', 0, 0, '2019-11-29 16:00:37', '2020-02-27 12:19:07', 0, 0),
(2, 0, 13, 16, 1, 'Liabilities', 'liabilities', 0, 0, '2019-11-29 16:00:50', '2020-02-27 12:19:07', 0, 0),
(3, 0, 17, 26, 1, 'Incomes', 'incomes', 0, 0, '2019-11-29 16:00:58', '2020-02-27 12:19:07', 0, 0),
(4, 0, 27, 34, 1, 'Expenses', 'expenses', 0, 0, '2019-11-29 16:01:04', '2020-02-27 12:19:07', 0, 0),
(5, 0, 35, 36, 1, 'Equity', 'equity', 0, 0, '2019-11-29 16:01:22', '2020-02-27 12:19:07', 0, 0),
(6, 3, 18, 25, 2, 'Regular Income', 'regular-income', 1, 0, '2020-01-22 04:53:39', '2020-02-27 12:19:07', 0, 0),
(7, 6, 19, 24, 3, 'Fees & Charges', 'fees-charges', 1, 0, '2020-01-22 04:53:59', '2020-02-27 12:19:07', 0, 0),
(8, 7, 20, 21, 4, 'Student Fees', 'student-fees', 1, 0, '2020-01-22 04:54:12', '2020-02-27 12:19:07', 0, 0),
(9, 7, 22, 23, 4, 'Charges', 'charges', 1, 0, '2020-01-22 04:54:12', '2020-02-27 12:19:07', 0, 0),
(10, 1, 2, 11, 2, 'Current Assets', 'current-assets', 1, 2, '2020-01-13 15:43:34', '2020-02-27 12:19:07', 0, 0),
(11, 2, 14, 15, 2, 'Current Liabilities', 'current-liabilities', 1, 2, '2020-01-13 15:43:55', '2020-02-27 12:19:07', 0, 0),
(12, 4, 28, 33, 2, 'General Expenses', 'general-expenses', 1, 1, '2020-01-24 06:05:43', '2020-02-27 12:19:07', 0, 0),
(13, 12, 29, 32, 3, 'Discounts & Concession', 'discounts-concession', 1, 1, '2020-01-24 06:06:56', '2020-02-27 12:19:07', 0, 0),
(14, 13, 30, 31, 4, 'Cash Discount', 'cash-discount', 1, 1, '2020-01-24 06:07:07', '2020-02-27 12:19:07', 0, 0),
(15, 10, 3, 10, 3, 'Cash, Banks and E-wallets', 'cash-banks-and-e-wallets', 1, 2, '2020-02-27 12:18:34', '2020-02-27 12:22:10', 0, 0),
(16, 15, 4, 5, 4, 'Cash', 'cash', 1, 2, '2020-02-27 12:18:45', '2020-02-27 12:22:12', 0, 0),
(17, 15, 6, 7, 4, 'Bank', 'bank', 1, 2, '2020-02-27 12:18:55', '2020-02-27 12:22:15', 0, 0),
(18, 15, 8, 9, 4, 'E-wallets', 'e-wallets', 1, 2, '2020-02-27 12:19:07', '2020-02-27 12:22:17', 0, 0),
(19, 5, 36, 41, 2, 'Profit', 'profit', 1, 2, '2020-10-07 16:49:41', '2020-10-07 16:50:34', 0, 0),
(20, 19, 37, 40, 3, 'Profit', 'profit-1', 1, 2, '2020-10-07 16:49:51', '2020-10-07 16:50:29', 0, 0),
(21, 20, 38, 39, 4, 'Profit', 'profit-2', 1, 2, '2020-10-07 16:50:01', '2020-10-07 16:50:26', 0, 0),
(22, 10, 11, 14, 3, 'Current Assets', 'current-assets-1', 1, 1, '2021-03-10 10:05:00', '2021-03-10 10:13:21', 0, 0),
(23, 22, 12, 13, 4, 'Current Assets', 'current-assets-2', 1, 1, '2021-03-10 10:13:21', '2021-03-10 10:13:21', 0, 0),
(24, 11, 19, 22, 3, 'Current Liabilities', 'current-liabilities-1', 1, 1, '2021-03-10 10:13:39', '2021-03-10 10:13:56', 0, 0),
(25, 24, 20, 21, 4, 'Current Liabilities', 'current-liabilities-2', 1, 1, '2021-03-10 10:13:56', '2021-03-10 10:13:56', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `acc_financial_year`
--

CREATE TABLE `acc_financial_year` (
  `id` int(11) NOT NULL,
  `year_starts` date NOT NULL,
  `year_ends` date NOT NULL,
  `is_current` tinyint(1) NOT NULL,
  `year_starts_bs` varchar(50) NOT NULL,
  `year_ends_bs` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acc_financial_year`
--

INSERT INTO `acc_financial_year` (`id`, `year_starts`, `year_ends`, `is_current`, `year_starts_bs`, `year_ends_bs`) VALUES
(1, '2021-07-16', '2022-07-16', 1, '2078-4-1', '2079-3-32');

-- --------------------------------------------------------

--
-- Table structure for table `acc_general_settings`
--

CREATE TABLE `acc_general_settings` (
  `id` int(11) NOT NULL,
  `system_type` int(11) NOT NULL,
  `round_to` int(11) NOT NULL,
  `allow_receipt_edit` int(11) NOT NULL,
  `allow_payment_edit` int(11) NOT NULL,
  `allow_journal_edit` int(11) NOT NULL,
  `invoice_generation_on` int(11) NOT NULL,
  `invoice_prefix` varchar(255) NOT NULL,
  `use_invoice_prefix` tinyint(1) NOT NULL,
  `invoice_start` int(11) NOT NULL,
  `journal_prefix` varchar(255) NOT NULL,
  `use_journal_prefix` tinyint(1) NOT NULL,
  `journal_start` int(11) NOT NULL,
  `general_receipt_prefix` varchar(255) NOT NULL,
  `use_general_receipt_prefix` tinyint(1) NOT NULL,
  `general_receipt_start` int(11) NOT NULL,
  `general_payment_prefix` varchar(255) NOT NULL,
  `use_general_payment_prefix` tinyint(1) NOT NULL,
  `general_payment_start` int(11) NOT NULL,
  `cash_receipt_prefix` varchar(255) NOT NULL,
  `use_cash_receipt_prefix` tinyint(1) NOT NULL,
  `cash_receipt_start` int(11) NOT NULL,
  `date_system` int(11) NOT NULL,
  `year_start` int(11) NOT NULL,
  `year_end` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `due_date_duration` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_year_saved` tinyint(4) NOT NULL DEFAULT 0,
  `is_settings_saved` tinyint(4) NOT NULL DEFAULT 0,
  `opening_balance_date` varchar(255) NOT NULL DEFAULT '',
  `opening_balance_date_bs` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `acc_general_settings`
--

INSERT INTO `acc_general_settings` (`id`, `system_type`, `round_to`, `allow_receipt_edit`, `allow_payment_edit`, `allow_journal_edit`, `invoice_generation_on`, `invoice_prefix`, `use_invoice_prefix`, `invoice_start`, `journal_prefix`, `use_journal_prefix`, `journal_start`, `general_receipt_prefix`, `use_general_receipt_prefix`, `general_receipt_start`, `general_payment_prefix`, `use_general_payment_prefix`, `general_payment_start`, `cash_receipt_prefix`, `use_cash_receipt_prefix`, `cash_receipt_start`, `date_system`, `year_start`, `year_end`, `level`, `due_date_duration`, `created_at`, `modified_at`, `is_year_saved`, `is_settings_saved`, `opening_balance_date`, `opening_balance_date_bs`) VALUES
(1, 1, 1, 0, 0, 0, 0, 'INV', 1, 1, 'JOR', 1, 1, 'GRN', 1, 1, 'GPN', 1, 1, 'CRN', 1, 1, 2, 4, 3, 4, 7, '2020-01-24 11:51:28', '2021-09-17 11:30:30', 1, 1, '2021-07-16', '2078-4-1');

-- --------------------------------------------------------

--
-- Table structure for table `acc_invoice`
--

CREATE TABLE `acc_invoice` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `reference_no` varchar(20) NOT NULL,
  `registered_no` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `invoice_date_bs` date DEFAULT NULL,
  `due_date_bs` date DEFAULT NULL,
  `bs_year` int(11) NOT NULL,
  `bs_month` tinyint(4) NOT NULL,
  `bs_day` tinyint(4) NOT NULL,
  `fee_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_invoice_entry`
--

CREATE TABLE `acc_invoice_entry` (
  `id` int(11) NOT NULL,
  `coa_id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `invoice_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_journal`
--

CREATE TABLE `acc_journal` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `entry_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `reference_no` varchar(20) NOT NULL,
  `narration` text NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `is_cleared` tinyint(1) NOT NULL DEFAULT 0,
  `entry_date_bs` date NOT NULL,
  `due_date_bs` date NOT NULL,
  `bs_year` int(11) NOT NULL,
  `bs_month` tinyint(4) NOT NULL,
  `bs_day` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_journal_entry`
--

CREATE TABLE `acc_journal_entry` (
  `id` int(11) NOT NULL,
  `coa_id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amount_type` varchar(10) NOT NULL DEFAULT 'debit',
  `journal_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_opening_balances`
--

CREATE TABLE `acc_opening_balances` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `coa_id` int(11) NOT NULL,
  `balance` double NOT NULL,
  `balance_type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acc_payment`
--

CREATE TABLE `acc_payment` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `payment_no` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `paid_to` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL DEFAULT 0,
  `payment_mode_details` longtext DEFAULT NULL,
  `description` text NOT NULL,
  `total` float NOT NULL,
  `paid_amount` float NOT NULL,
  `due` tinyint(4) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `send_email` tinyint(4) NOT NULL DEFAULT 1,
  `payment_date_bs` date NOT NULL,
  `bs_year` int(11) NOT NULL,
  `bs_month` tinyint(4) NOT NULL,
  `bs_day` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acc_payment_details`
--

CREATE TABLE `acc_payment_details` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `total` float NOT NULL,
  `remaining_amount` float NOT NULL,
  `paid_amount` float NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acc_personnel`
--

CREATE TABLE `acc_personnel` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `balance_type` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `pan` varchar(50) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acc_receipt`
--

CREATE TABLE `acc_receipt` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `receipt_no` varchar(255) NOT NULL,
  `receipt_date` date NOT NULL,
  `receipt_mode` varchar(255) NOT NULL,
  `received_from` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL DEFAULT 0,
  `receipt_mode_details` longtext DEFAULT NULL,
  `description` text NOT NULL,
  `total` float NOT NULL,
  `received_amount` float NOT NULL,
  `due` tinyint(4) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `send_email` tinyint(4) NOT NULL DEFAULT 1,
  `receipt_date_bs` date NOT NULL,
  `bs_year` int(11) NOT NULL,
  `bs_month` tinyint(4) NOT NULL,
  `bs_day` tinyint(4) NOT NULL,
  `auto_created` int(11) NOT NULL DEFAULT 0,
  `fee_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acc_receipt_details`
--

CREATE TABLE `acc_receipt_details` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `remaining_amount` float NOT NULL,
  `received_amount` float NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acc_transaction_logs`
--

CREATE TABLE `acc_transaction_logs` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_type` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category_type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amount_type` varchar(50) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `financial_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admit_card`
--

CREATE TABLE `admit_card` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `school_address` varchar(500) NOT NULL,
  `background` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `principal_sign` varchar(100) NOT NULL,
  `verifier_sign` varchar(100) NOT NULL,
  `header_color` varchar(100) NOT NULL,
  `validity` varchar(255) NOT NULL,
  `layout` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `examination` varchar(100) NOT NULL,
  `faculty` varchar(100) NOT NULL,
  `enable_admission_no` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_roll_no` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `enable_student_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_class` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_guardian_name` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `enable_fathers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_mothers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_address` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_phone` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_dob` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_blood_group` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `status` tinyint(1) NOT NULL COMMENT '0=disable,1=enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `api_auths`
--

CREATE TABLE `api_auths` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET ascii NOT NULL,
  `user_type` enum('staff','parent','student') NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_type` varchar(25) NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  `payload` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attendence_type`
--

CREATE TABLE `attendence_type` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `key_value` varchar(50) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendence_type`
--

INSERT INTO `attendence_type` (`id`, `type`, `key_value`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Present', '<b class=\"text text-success\">P</b>', 'yes', '2016-06-23 18:11:37', '0000-00-00 00:00:00'),
(2, 'Late With Excuse', '<b class=\"text text-warning\">E</b>', 'no', '2018-05-29 08:19:48', '0000-00-00 00:00:00'),
(3, 'Late', '<b class=\"text text-warning\">L</b>', 'yes', '2016-06-23 18:12:28', '0000-00-00 00:00:00'),
(4, 'Absent', '<b class=\"text text-danger\">A</b>', 'yes', '2016-10-11 11:35:40', '0000-00-00 00:00:00'),
(5, 'Holiday', 'H', 'yes', '2016-10-11 11:35:01', '0000-00-00 00:00:00'),
(6, 'Half Day', '<b class=\"text text-warning\">F</b>', 'yes', '2016-06-23 18:12:28', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_title` varchar(100) NOT NULL,
  `book_no` varchar(50) NOT NULL,
  `isbn_no` varchar(100) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `rack_no` varchar(100) NOT NULL,
  `publish` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `perunitcost` float(10,2) DEFAULT NULL,
  `postdate` date DEFAULT NULL,
  `postdate_bs` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `available` varchar(10) DEFAULT 'yes',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `book_issues`
--

CREATE TABLE `book_issues` (
  `id` int(11) UNSIGNED NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `return_date_bs` varchar(255) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `issue_date_bs` varchar(255) NOT NULL,
  `returned_date` date DEFAULT NULL,
  `returned_date_bs` varchar(255) NOT NULL,
  `is_returned` int(11) DEFAULT 0,
  `member_id` int(11) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `certificate_name` varchar(100) NOT NULL,
  `certificate_text` text NOT NULL,
  `left_header` varchar(100) NOT NULL,
  `center_header` varchar(100) NOT NULL,
  `right_header` varchar(100) NOT NULL,
  `left_footer` varchar(100) NOT NULL,
  `right_footer` varchar(100) NOT NULL,
  `center_footer` varchar(100) NOT NULL,
  `background_image` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_for` tinyint(1) NOT NULL COMMENT '1 = staff, 2 = students',
  `status` tinyint(1) NOT NULL,
  `header_height` int(11) NOT NULL,
  `content_height` int(11) NOT NULL,
  `footer_height` int(11) NOT NULL,
  `content_width` int(11) NOT NULL,
  `enable_student_image` tinyint(1) NOT NULL COMMENT '0=no,1=yes',
  `enable_image_height` int(11) NOT NULL,
  `layout` tinyint(3) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_sections`
--

CREATE TABLE `class_sections` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_teacher`
--

CREATE TABLE `class_teacher` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `is_class_teacher` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `id` int(11) NOT NULL,
  `complaint_type` varchar(15) NOT NULL,
  `source` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `action_taken` varchar(200) NOT NULL,
  `assigned` varchar(50) NOT NULL,
  `note` text NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_type`
--

CREATE TABLE `complaint_type` (
  `id` int(11) NOT NULL,
  `complaint_type` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_public` varchar(10) DEFAULT 'No',
  `class_id` int(11) DEFAULT NULL,
  `cls_sec_id` int(10) NOT NULL,
  `file` varchar(250) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_for`
--

CREATE TABLE `content_for` (
  `id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cron_fee`
--

CREATE TABLE `cron_fee` (
  `id` int(10) UNSIGNED NOT NULL,
  `session_id` int(11) NOT NULL,
  `fee_month` tinyint(3) UNSIGNED NOT NULL,
  `fee_year` smallint(5) UNSIGNED NOT NULL,
  `gen_on` datetime NOT NULL,
  `gen_on_bs` varchar(255) NOT NULL,
  `runner` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department_name` varchar(200) NOT NULL,
  `is_active` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_receive`
--

CREATE TABLE `dispatch_receive` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(50) NOT NULL,
  `to_title` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `note` varchar(500) NOT NULL,
  `from_title` varchar(200) NOT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE `email_config` (
  `id` int(11) UNSIGNED NOT NULL,
  `email_type` varchar(100) DEFAULT NULL,
  `smtpauth_type` enum('0','1') NOT NULL DEFAULT '0',
  `smtp_server` varchar(100) DEFAULT NULL,
  `smtp_port` varchar(100) DEFAULT NULL,
  `smtp_username` varchar(100) DEFAULT NULL,
  `smtp_password` varchar(100) DEFAULT NULL,
  `ssl_tls` varchar(100) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` mediumtext NOT NULL,
  `reference` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL,
  `follow_up_date` date NOT NULL,
  `follow_up_date_bs` varchar(255) NOT NULL,
  `note` mediumtext NOT NULL,
  `source` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `assigned` varchar(100) NOT NULL,
  `class` int(11) NOT NULL,
  `no_of_child` varchar(11) DEFAULT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry_type`
--

CREATE TABLE `enquiry_type` (
  `id` int(11) NOT NULL,
  `enquiry_type` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_title` varchar(200) NOT NULL,
  `event_description` varchar(300) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `start_date_bs` varchar(100) NOT NULL,
  `end_date_bs` varchar(100) NOT NULL,
  `start_time` varchar(10) NOT NULL,
  `end_time` varchar(10) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_color` varchar(200) NOT NULL,
  `event_for` varchar(100) NOT NULL,
  `is_active` varchar(100) NOT NULL,
  `image` varchar(250) NOT NULL,
  `is_holiday` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `sesion_id` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `id` int(11) NOT NULL,
  `attendence` varchar(10) NOT NULL,
  `exam_schedule_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `get_marks` float(10,2) DEFAULT NULL,
  `is_na` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `grade_id` int(11) NOT NULL,
  `exam_result_publish_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_schedules`
--

CREATE TABLE `exam_schedules` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `teacher_subject_id` int(11) DEFAULT NULL,
  `date_of_exam` date DEFAULT NULL,
  `date_of_exam_bs` varchar(255) NOT NULL,
  `start_to` varchar(50) DEFAULT NULL,
  `end_from` varchar(50) DEFAULT NULL,
  `room_no` varchar(50) DEFAULT NULL,
  `full_marks` int(11) DEFAULT NULL,
  `passing_marks` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `exam_publish_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exam_weightage`
--

CREATE TABLE `exam_weightage` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `weight_exam_id` int(11) NOT NULL,
  `weightage` int(11) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `exp_head_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(200) NOT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expense_head`
--

CREATE TABLE `expense_head` (
  `id` int(11) NOT NULL,
  `exp_category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feecategory`
--

CREATE TABLE `feecategory` (
  `id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feemasters`
--

CREATE TABLE `feemasters` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `feetype_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fees_discounts`
--

CREATE TABLE `fees_discounts` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feetype`
--

CREATE TABLE `feetype` (
  `id` int(11) NOT NULL,
  `is_system` int(1) NOT NULL DEFAULT 0,
  `feecategory_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `feetype`
--

INSERT INTO `feetype` (`id`, `is_system`, `feecategory_id`, `type`, `code`, `is_active`, `created_at`, `updated_at`, `description`) VALUES
(1, 1, NULL, 'Tuition Fee', 'Tuition Fee', 'no', '2020-01-02 05:23:35', '0000-00-00 00:00:00', 'Tuition Fee'),
(2, 1, NULL, 'Exam Fee', 'Exam Fee', 'no', '2020-01-02 05:23:32', '0000-00-00 00:00:00', 'Exam Fee'),
(3, 1, NULL, 'Computer Fee', 'Computer Fee', 'no', '2020-01-02 05:23:28', '0000-00-00 00:00:00', ''),
(4, 1, NULL, 'Library Fee', 'Library Fee', 'no', '2020-01-02 05:23:24', '0000-00-00 00:00:00', ''),
(5, 1, NULL, 'Extra Curricular Fee', 'Extra Curricular Fee', 'no', '2020-01-02 05:23:48', '0000-00-00 00:00:00', ''),
(6, 1, NULL, 'Medical Fee', 'Medical Fee', 'no', '2020-01-02 05:23:39', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `fee_fine_payments`
--

CREATE TABLE `fee_fine_payments` (
  `id` int(11) NOT NULL,
  `student_session_fees_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fee_groups`
--

CREATE TABLE `fee_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `fee_month` tinyint(3) UNSIGNED NOT NULL,
  `is_system` int(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fee_groups_feetype`
--

CREATE TABLE `fee_groups_feetype` (
  `id` int(11) NOT NULL,
  `fee_session_group_id` int(11) DEFAULT NULL,
  `fee_groups_id` int(11) DEFAULT NULL,
  `feetype_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `fee_month` tinyint(3) UNSIGNED DEFAULT NULL,
  `discounts` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `due_date_bs` varchar(255) NOT NULL,
  `due_day_bs` tinyint(3) UNSIGNED NOT NULL,
  `due_month_bs` tinyint(3) UNSIGNED NOT NULL,
  `due_year_bs` smallint(5) UNSIGNED NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fee_receipt_no`
--

CREATE TABLE `fee_receipt_no` (
  `id` int(11) NOT NULL,
  `payment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fee_session_groups`
--

CREATE TABLE `fee_session_groups` (
  `id` int(11) NOT NULL,
  `fee_groups_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow_up`
--

CREATE TABLE `follow_up` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `next_date` date NOT NULL,
  `next_date_bs` varchar(255) NOT NULL,
  `response` text NOT NULL,
  `note` text NOT NULL,
  `followup_by` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_media_gallery`
--

CREATE TABLE `front_cms_media_gallery` (
  `id` int(11) NOT NULL,
  `image` varchar(300) DEFAULT NULL,
  `thumb_path` varchar(300) DEFAULT NULL,
  `dir_path` varchar(300) DEFAULT NULL,
  `img_name` varchar(300) DEFAULT NULL,
  `thumb_name` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `file_type` varchar(100) NOT NULL,
  `file_size` varchar(100) NOT NULL,
  `vid_url` mediumtext NOT NULL,
  `vid_title` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_menus`
--

CREATE TABLE `front_cms_menus` (
  `id` int(11) NOT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `open_new_tab` int(10) NOT NULL DEFAULT 0,
  `ext_url` mediumtext NOT NULL,
  `ext_url_link` mediumtext NOT NULL,
  `publish` int(11) NOT NULL DEFAULT 0,
  `content_type` varchar(10) NOT NULL DEFAULT 'manual',
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `front_cms_menus`
--

INSERT INTO `front_cms_menus` (`id`, `menu`, `slug`, `description`, `open_new_tab`, `ext_url`, `ext_url_link`, `publish`, `content_type`, `is_active`, `created_at`) VALUES
(1, 'Main Menu', 'main-menu', 'Main menu', 0, '', '', 0, 'default', 'no', '2018-04-20 14:54:49'),
(2, 'Bottom Menu', 'bottom-menu', 'Bottom Menu', 0, '', '', 0, 'default', 'no', '2018-04-20 14:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_menu_items`
--

CREATE TABLE `front_cms_menu_items` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `ext_url` mediumtext DEFAULT NULL,
  `open_new_tab` int(11) DEFAULT 0,
  `ext_url_link` mediumtext DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `publish` int(11) NOT NULL DEFAULT 0,
  `description` mediumtext DEFAULT NULL,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_pages`
--

CREATE TABLE `front_cms_pages` (
  `id` int(11) NOT NULL,
  `page_type` varchar(10) NOT NULL DEFAULT 'manual',
  `is_homepage` int(1) DEFAULT 0,
  `title` varchar(250) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `meta_title` mediumtext DEFAULT NULL,
  `meta_description` mediumtext DEFAULT NULL,
  `meta_keyword` mediumtext DEFAULT NULL,
  `feature_image` varchar(200) NOT NULL,
  `description` longtext DEFAULT NULL,
  `publish_date` date NOT NULL,
  `publish` int(10) DEFAULT 0,
  `sidebar` int(10) DEFAULT 0,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `front_cms_pages`
--

INSERT INTO `front_cms_pages` (`id`, `page_type`, `is_homepage`, `title`, `url`, `type`, `slug`, `meta_title`, `meta_description`, `meta_keyword`, `feature_image`, `description`, `publish_date`, `publish`, `sidebar`, `is_active`, `created_at`) VALUES
(1, 'default', 1, 'Home', 'page/home', 'page', 'home', '', '', '', '', '<p>home page</p>', '0000-00-00', 1, NULL, 'no', '2018-07-11 18:04:33'),
(2, 'default', 0, 'Complain', 'page/complain', 'page', 'complain', 'Complain form', '                                                                                                                                                                                    complain form                                                                                                                                                                                                                                ', 'complain form', '', '<p>\r\n[form-builder:complain]</p>', '0000-00-00', 1, 1, 'no', '2018-05-09 15:14:34'),
(54, 'default', 0, '404 page', 'page/404-page', 'page', '404-page', '', '                                ', '', '', '<html>\r\n<head>\r\n <title></title>\r\n</head>\r\n<body>\r\n<p>404 page found</p>\r\n</body>\r\n</html>', '0000-00-00', 0, NULL, 'no', '2018-05-18 14:46:04'),
(76, 'default', 0, 'Contact us', 'page/contact-us', 'page', 'contact-us', '', '', '', '', '<title></title>\r\n<section class=\"contact\">\r\n<div class=\"container spacet50 spaceb50\">\r\n<div class=\"row\">\r\n<div class=\"col-md-12 col-sm-12\">[form-builder:contact_us]<!--./row--></div>\r\n<!--./col-md-12--></div>\r\n<!--./row--></div>\r\n<!--./container--></section>', '0000-00-00', 0, NULL, 'no', '2018-07-11 18:03:41'),
(77, 'manual', 0, 'Matcha', 'page/matcha', 'page', 'matcha', '', '', '', 'http://school.neemacademy.com/uploads/gallery/media/c1fd74724eb65a19e4d09cb327474f6b--book-art-parakeets.jpg', '<html>\r\n<head>\r\n	<title></title>\r\n</head>\r\n<body>\r\n<p>https://www.mathcha.io/editor</p>\r\n</body>\r\n</html>', '0000-00-00', 0, 1, 'no', '2018-11-23 05:31:17'),
(78, 'manual', 0, 'Exercise 16.1', 'page/exercise-161', 'page', 'exercise-161', '', '', '', 'http://school.neemacademy.com/uploads/gallery/media/1w.png', '<html>\r\n<head>\r\n	<title></title>\r\n</head>\r\n<body>\r\n<p>Classified Questions for Practice</p>\r\n</body>\r\n</html>', '0000-00-00', 0, NULL, 'no', '2018-11-23 05:31:52');

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_page_contents`
--

CREATE TABLE `front_cms_page_contents` (
  `id` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `content_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_programs`
--

CREATE TABLE `front_cms_programs` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `url` mediumtext DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `event_start` date DEFAULT NULL,
  `start_time` varchar(100) NOT NULL,
  `event_end` date DEFAULT NULL,
  `end_time` varchar(100) NOT NULL,
  `event_venue` mediumtext DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meta_title` mediumtext NOT NULL,
  `meta_description` mediumtext NOT NULL,
  `meta_keyword` mediumtext NOT NULL,
  `feature_image` mediumtext NOT NULL,
  `publish_date` date NOT NULL,
  `publish` varchar(10) DEFAULT '0',
  `sidebar` int(10) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_program_photos`
--

CREATE TABLE `front_cms_program_photos` (
  `id` int(11) NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `media_gallery_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `front_cms_settings`
--

CREATE TABLE `front_cms_settings` (
  `id` int(11) NOT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `is_active_rtl` int(10) DEFAULT 0,
  `is_active_front_cms` int(11) DEFAULT 0,
  `is_active_sidebar` int(1) DEFAULT 0,
  `logo` varchar(200) DEFAULT NULL,
  `contact_us_email` varchar(100) DEFAULT NULL,
  `complain_form_email` varchar(100) DEFAULT NULL,
  `sidebar_options` mediumtext NOT NULL,
  `fb_url` varchar(200) NOT NULL,
  `twitter_url` varchar(200) NOT NULL,
  `youtube_url` varchar(200) NOT NULL,
  `google_plus` varchar(200) NOT NULL,
  `instagram_url` varchar(200) NOT NULL,
  `pinterest_url` varchar(200) NOT NULL,
  `linkedin_url` varchar(200) NOT NULL,
  `google_analytics` mediumtext DEFAULT NULL,
  `footer_text` varchar(500) DEFAULT NULL,
  `fav_icon` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `general_calls`
--

CREATE TABLE `general_calls` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(12) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL,
  `follow_up_date` date NOT NULL,
  `follow_up_date_bs` varchar(255) NOT NULL,
  `call_dureation` varchar(50) NOT NULL,
  `note` mediumtext NOT NULL,
  `call_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `point` float(10,1) DEFAULT NULL,
  `mark_from` float(10,2) DEFAULT NULL,
  `mark_upto` float(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `grade_remark` varchar(255) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `homework`
--

CREATE TABLE `homework` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `homework_date` date NOT NULL,
  `submit_date` date NOT NULL,
  `homework_date_bs` varchar(255) NOT NULL,
  `submit_date_bs` varchar(255) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `create_date` date NOT NULL,
  `document` varchar(200) NOT NULL,
  `created_by` int(11) NOT NULL,
  `evaluated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `homework_evaluation`
--

CREATE TABLE `homework_evaluation` (
  `id` int(11) NOT NULL,
  `homework_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hostel`
--

CREATE TABLE `hostel` (
  `id` int(11) NOT NULL,
  `hostel_name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `intake` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hostel_rooms`
--

CREATE TABLE `hostel_rooms` (
  `id` int(11) NOT NULL,
  `hostel_id` int(11) DEFAULT NULL,
  `room_type_id` int(11) DEFAULT NULL,
  `room_no` varchar(200) DEFAULT NULL,
  `no_of_bed` int(11) DEFAULT NULL,
  `cost_per_bed` float(10,2) DEFAULT 0.00,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `id_card`
--

CREATE TABLE `id_card` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `school_address` varchar(500) NOT NULL,
  `background` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `sign_image` varchar(100) NOT NULL,
  `header_color` varchar(100) NOT NULL,
  `validity` varchar(255) NOT NULL,
  `layout` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `enable_admission_no` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_roll_no` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `enable_student_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_class` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_guardian_name` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `enable_fathers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_mothers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_address` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_phone` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_dob` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_blood_group` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `status` tinyint(1) NOT NULL COMMENT '0=disable,1=enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `inc_head_id` varchar(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(200) NOT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `amount` float DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `documents` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `income_head`
--

CREATE TABLE `income_head` (
  `id` int(255) NOT NULL,
  `income_category` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` varchar(255) NOT NULL DEFAULT 'yes',
  `is_deleted` varchar(255) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `item_category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `item_photo` varchar(225) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `item_store_id` int(11) DEFAULT NULL,
  `item_supplier_id` int(11) DEFAULT NULL,
  `quantity` int(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE `item_category` (
  `id` int(255) NOT NULL,
  `item_category` varchar(255) NOT NULL,
  `is_active` varchar(255) NOT NULL DEFAULT 'yes',
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_issue`
--

CREATE TABLE `item_issue` (
  `id` int(11) NOT NULL,
  `issue_type` varchar(15) DEFAULT NULL,
  `issue_to` int(11) DEFAULT NULL,
  `issue_by` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `issue_date_bs` varchar(255) NOT NULL,
  `return_date` date DEFAULT NULL,
  `return_date_bs` varchar(255) NOT NULL,
  `item_category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(10) NOT NULL,
  `note` text NOT NULL,
  `is_returned` int(2) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` varchar(10) DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_stock`
--

CREATE TABLE `item_stock` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `symbol` varchar(10) NOT NULL DEFAULT '+',
  `store_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `attachment` varchar(250) DEFAULT NULL,
  `description` text NOT NULL,
  `is_active` varchar(10) DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_store`
--

CREATE TABLE `item_store` (
  `id` int(255) NOT NULL,
  `item_store` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_supplier`
--

CREATE TABLE `item_supplier` (
  `id` int(255) NOT NULL,
  `item_supplier` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_person_name` varchar(255) NOT NULL,
  `contact_person_phone` varchar(255) NOT NULL,
  `contact_person_email` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `language` varchar(50) DEFAULT NULL,
  `is_deleted` varchar(10) NOT NULL DEFAULT 'yes',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `language`, `is_deleted`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Azerbaijan', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(2, 'Albanian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(3, 'Amharic', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(4, 'English', 'no', 'no', '2017-04-06 05:08:33', '0000-00-00 00:00:00'),
(5, 'Arabic', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(7, 'Afrikaans', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(8, 'Basque', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(11, 'Bengali', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(13, 'Bosnian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(14, 'Welsh', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(15, 'Hungarian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(16, 'Vietnamese', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(17, 'Haitian (Creole)', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(18, 'Galician', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(19, 'Dutch', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(21, 'Greek', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(22, 'Georgian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(23, 'Gujarati', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(24, 'Danish', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(25, 'Hebrew', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(26, 'Yiddish', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(27, 'Indonesian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(28, 'Irish', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(29, 'Italian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(30, 'Icelandic', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(31, 'Spanish', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(33, 'Kannada', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(34, 'Catalan', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(36, 'Chinese', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(37, 'Korean', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(38, 'Xhosa', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(39, 'Latin', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(40, 'Latvian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(41, 'Lithuanian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(43, 'Malagasy', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(44, 'Malay', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(45, 'Malayalam', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(46, 'Maltese', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(47, 'Macedonian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(48, 'Maori', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(49, 'Marathi', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(51, 'Mongolian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(52, 'German', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(53, 'Nepali', 'no', 'no', '2017-04-06 05:08:33', '0000-00-00 00:00:00'),
(54, 'Norwegian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(55, 'Punjabi', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(57, 'Persian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(59, 'Portuguese', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(60, 'Romanian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(61, 'Russian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(62, 'Cebuano', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(64, 'Sinhala', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(65, 'Slovakian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(66, 'Slovenian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(67, 'Swahili', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(68, 'Sundanese', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(70, 'Thai', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(71, 'Tagalog', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(72, 'Tamil', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(74, 'Telugu', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(75, 'Turkish', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(77, 'Uzbek', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(79, 'Urdu', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(80, 'Finnish', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(81, 'French', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(82, 'Hindi', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(84, 'Czech', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(85, 'Swedish', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(86, 'Scottish', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(87, 'Estonian', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(88, 'Esperanto', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(89, 'Javanese', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00'),
(90, 'Japanese', 'yes', 'no', '2021-09-17 03:35:29', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `is_active` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `libarary_members`
--

CREATE TABLE `libarary_members` (
  `id` int(11) UNSIGNED NOT NULL,
  `library_card_no` varchar(50) DEFAULT NULL,
  `member_type` varchar(50) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `send_mail` varchar(10) DEFAULT '0',
  `send_sms` varchar(10) DEFAULT '0',
  `is_group` varchar(10) DEFAULT '0',
  `is_individual` varchar(10) DEFAULT '0',
  `is_class` int(10) NOT NULL DEFAULT 0,
  `group_list` text DEFAULT NULL,
  `user_list` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification_roles`
--

CREATE TABLE `notification_roles` (
  `id` int(11) NOT NULL,
  `send_notification_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification_setting`
--

CREATE TABLE `notification_setting` (
  `id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `is_mail` varchar(10) DEFAULT '0',
  `is_sms` varchar(10) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notification_setting`
--

INSERT INTO `notification_setting` (`id`, `type`, `is_mail`, `is_sms`, `created_at`) VALUES
(1, 'student_admission', '1', '0', '2018-05-22 13:00:07'),
(2, 'exam_result', '1', '0', '2018-05-22 13:00:07'),
(3, 'fee_submission', '1', '0', '2018-05-22 13:00:07'),
(4, 'absent_attendence', '1', '0', '2018-07-11 17:43:02'),
(5, 'login_credential', '1', '0', '2018-05-22 13:04:19'),
(6, 'send_account_email', '1', '0', '2021-09-17 03:35:40');

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `payment_type` varchar(200) NOT NULL,
  `api_username` varchar(200) DEFAULT NULL,
  `api_secret_key` varchar(200) NOT NULL,
  `salt` varchar(200) NOT NULL,
  `api_publishable_key` varchar(200) NOT NULL,
  `api_password` varchar(200) DEFAULT NULL,
  `api_signature` varchar(200) DEFAULT NULL,
  `api_email` varchar(200) DEFAULT NULL,
  `paypal_demo` varchar(100) NOT NULL,
  `account_no` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `payment_type`, `api_username`, `api_secret_key`, `salt`, `api_publishable_key`, `api_password`, `api_signature`, `api_email`, `paypal_demo`, `account_no`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'paypal', '', '', '', '', '', '', NULL, '', '', 'yes', '2018-07-12 05:26:13', '0000-00-00 00:00:00'),
(2, 'stripe', NULL, '', '', '', NULL, NULL, NULL, '', '', 'no', '2018-07-12 05:26:26', '0000-00-00 00:00:00'),
(3, 'payu', NULL, '', '', '', NULL, NULL, NULL, '', '', 'no', '2018-07-12 05:26:35', '0000-00-00 00:00:00'),
(4, 'ccavenue', NULL, '', '', '', NULL, NULL, NULL, '', '', 'no', '2018-07-12 05:26:45', '0000-00-00 00:00:00'),
(5, 'Prabhupay', NULL, '', '', '', NULL, NULL, NULL, '', '', 'no', '2018-07-12 05:26:55', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `payslip_allowance`
--

CREATE TABLE `payslip_allowance` (
  `id` int(11) NOT NULL,
  `payslip_id` int(11) NOT NULL,
  `allowance_type` varchar(200) NOT NULL,
  `amount` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `cal_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permission_category`
--

CREATE TABLE `permission_category` (
  `id` int(11) NOT NULL,
  `perm_group_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_code` varchar(100) DEFAULT NULL,
  `enable_view` int(11) DEFAULT 0,
  `enable_add` int(11) DEFAULT 0,
  `enable_edit` int(11) DEFAULT 0,
  `enable_delete` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission_category`
--

INSERT INTO `permission_category` (`id`, `perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) VALUES
(1, 1, 'Student', 'student', 1, 1, 1, 1, '2018-06-22 15:47:11'),
(2, 1, 'Import Student', 'import_student', 1, 0, 0, 0, '2018-06-22 15:47:19'),
(3, 1, 'Student Categories', 'student_categories', 1, 1, 1, 1, '2018-06-22 15:47:36'),
(4, 1, 'Student Houses', 'student_houses', 1, 1, 1, 1, '2018-06-22 15:47:53'),
(5, 2, 'Collect Fees', 'collect_fees', 1, 1, 0, 1, '2018-06-22 15:51:03'),
(6, 2, 'Fees Carry Forward', 'fees_carry_forward', 1, 0, 0, 0, '2018-06-27 05:48:15'),
(7, 2, 'Fees Master', 'fees_master', 1, 1, 1, 1, '2018-06-27 05:48:57'),
(8, 2, 'Fees Group', 'fees_group', 1, 1, 1, 1, '2018-06-22 15:51:46'),
(9, 3, 'Income', 'income', 1, 1, 1, 1, '2018-06-22 15:53:21'),
(10, 3, 'Income Head', 'income_head', 1, 1, 1, 1, '2018-06-22 15:52:44'),
(11, 3, 'Search Income', 'search_income', 1, 0, 0, 0, '2018-06-22 15:53:00'),
(12, 4, 'Expense', 'expense', 1, 1, 1, 1, '2018-06-22 15:54:06'),
(13, 4, 'Expense Head', 'expense_head', 1, 1, 1, 1, '2018-06-22 15:53:47'),
(14, 4, 'Search Expense', 'search_expense', 1, 0, 0, 0, '2018-06-22 15:54:13'),
(15, 5, 'Student Attendance', 'student_attendance', 1, 1, 1, 0, '2018-06-22 15:54:49'),
(16, 5, 'Student Attendance Report', 'student_attendance_report', 1, 0, 0, 0, '2018-06-22 15:54:26'),
(17, 6, 'Exam', 'exam', 1, 1, 1, 1, '2018-06-22 15:56:02'),
(19, 6, 'Marks Register', 'marks_register', 1, 1, 1, 0, '2018-06-22 15:56:19'),
(20, 6, 'Marks Grade', 'marks_grade', 1, 1, 1, 1, '2018-06-22 15:55:25'),
(21, 7, 'Class Timetable', 'class_timetable', 1, 1, 1, 0, '2018-06-22 16:01:36'),
(22, 7, 'Assign Subject', 'assign_subject', 1, 1, 1, 1, '2018-06-22 16:01:57'),
(23, 7, 'Subject', 'subject', 1, 1, 1, 1, '2018-06-22 16:02:17'),
(24, 7, 'Class', 'class', 1, 1, 1, 1, '2018-06-22 16:02:35'),
(25, 7, 'Section', 'section', 1, 1, 1, 1, '2018-06-22 16:01:10'),
(26, 7, 'Promote Student', 'promote_student', 1, 0, 0, 0, '2018-06-22 16:02:47'),
(27, 8, 'Upload Content', 'upload_content', 1, 1, 0, 1, '2018-06-22 16:03:19'),
(28, 9, 'Books', 'books', 1, 1, 1, 1, '2018-06-22 16:04:04'),
(29, 9, 'Issue Return Student', 'issue_return', 1, 0, 0, 0, '2018-06-22 16:03:41'),
(30, 9, 'Add Staff Member', 'add_staff_member', 1, 0, 0, 0, '2018-07-02 17:07:00'),
(31, 10, 'Issue Item', 'issue_item', 1, 0, 0, 0, '2018-06-22 16:04:51'),
(32, 10, 'Item Stock', 'item_stock', 1, 1, 1, 1, '2018-06-22 16:05:17'),
(33, 10, 'Item', 'item', 1, 1, 1, 1, '2018-06-22 16:05:40'),
(34, 10, 'Store', 'store', 1, 1, 1, 1, '2018-06-22 16:06:02'),
(35, 10, 'Supplier', 'supplier', 1, 1, 1, 1, '2018-06-22 16:06:25'),
(37, 11, 'Routes', 'routes', 1, 1, 1, 1, '2018-06-22 16:09:17'),
(38, 11, 'Vehicle', 'vehicle', 1, 1, 1, 1, '2018-06-22 16:09:36'),
(39, 11, 'Assign Vehicle', 'assign_vehicle', 1, 1, 1, 1, '2018-06-27 10:09:20'),
(40, 12, 'Hostel', 'hostel', 1, 1, 1, 1, '2018-06-22 16:10:49'),
(41, 12, 'Room Type', 'room_type', 1, 1, 1, 1, '2018-06-22 16:10:27'),
(42, 12, 'Hostel Rooms', 'hostel_rooms', 1, 1, 1, 1, '2018-06-25 11:53:03'),
(43, 13, 'Notice Board', 'notice_board', 1, 1, 1, 1, '2018-06-22 16:11:17'),
(44, 13, 'Email / SMS', 'email_sms', 1, 0, 0, 0, '2018-06-22 16:10:54'),
(46, 13, 'Email / SMS Log', 'email_sms_log', 1, 0, 0, 0, '2018-06-22 16:11:23'),
(47, 1, 'Student Report', 'student_report', 1, 0, 0, 0, '2018-07-03 16:19:36'),
(48, 14, 'Transaction Report', 'transaction_report', 1, 0, 0, 0, '2018-07-06 17:13:32'),
(49, 14, 'User Log', 'user_log', 1, 0, 0, 0, '2018-07-06 17:13:53'),
(53, 15, 'Languages', 'languages', 0, 1, 0, 0, '2018-06-22 16:13:18'),
(54, 15, 'General Setting', 'general_setting', 1, 0, 1, 0, '2018-07-05 14:38:35'),
(55, 15, 'Session Setting', 'session_setting', 1, 1, 1, 1, '2018-06-22 16:14:15'),
(56, 15, 'Notification Setting', 'notification_setting', 1, 0, 1, 0, '2018-07-05 14:38:41'),
(57, 15, 'SMS Setting', 'sms_setting', 1, 0, 1, 0, '2018-07-05 14:38:47'),
(58, 15, 'Email Setting', 'email_setting', 1, 0, 1, 0, '2018-07-05 14:38:51'),
(59, 15, 'Front CMS Setting', 'front_cms_setting', 1, 0, 1, 0, '2018-07-05 14:38:55'),
(60, 15, 'Payment Methods', 'payment_methods', 1, 0, 1, 0, '2018-07-05 14:38:59'),
(61, 16, 'Menus', 'menus', 1, 1, 0, 1, '2018-07-09 09:20:06'),
(62, 16, 'Media Manager', 'media_manager', 1, 1, 0, 1, '2018-07-09 09:20:26'),
(63, 16, 'Banner Images', 'banner_images', 1, 1, 0, 1, '2018-06-22 16:16:02'),
(64, 16, 'Pages', 'pages', 1, 1, 1, 1, '2018-06-22 16:16:21'),
(65, 16, 'Gallery', 'gallery', 1, 1, 1, 1, '2018-06-22 16:17:02'),
(66, 16, 'Event', 'event', 1, 1, 1, 1, '2018-06-22 16:17:20'),
(67, 16, 'News', 'notice', 1, 1, 1, 1, '2018-07-03 14:09:34'),
(68, 2, 'Fees Group Assign', 'fees_group_assign', 1, 0, 0, 0, '2018-06-22 15:50:42'),
(69, 2, 'Fees Type', 'fees_type', 1, 1, 1, 1, '2018-06-22 15:49:34'),
(70, 2, 'Fees Discount', 'fees_discount', 1, 1, 1, 1, '2018-06-22 15:50:10'),
(71, 2, 'Fees Discount Assign', 'fees_discount_assign', 1, 0, 0, 0, '2018-06-22 15:50:17'),
(72, 2, 'Fees Statement', 'fees_statement', 1, 0, 0, 0, '2018-06-22 15:48:56'),
(73, 2, 'Search Fees Payment', 'search_fees_payment', 1, 0, 0, 0, '2018-06-22 15:50:27'),
(74, 2, 'Search Due Fees', 'search_due_fees', 1, 0, 0, 0, '2018-06-22 15:50:35'),
(75, 2, 'Balance Fees Report', 'balance_fees_report', 1, 0, 0, 0, '2018-06-22 15:48:50'),
(76, 6, 'Exam Schedule', 'exam_schedule', 1, 1, 1, 0, '2018-06-22 15:55:40'),
(77, 7, 'Assign Class Teacher', 'assign_class_teacher', 1, 1, 1, 1, '2018-06-22 16:00:52'),
(78, 17, 'Admission Enquiry', 'admission_enquiry', 1, 1, 1, 1, '2018-06-22 16:21:24'),
(79, 17, 'Follow Up Admission Enquiry', 'follow_up_admission_enquiry', 1, 1, 0, 1, '2018-06-22 16:21:39'),
(80, 17, 'Visitor Book', 'visitor_book', 1, 1, 1, 1, '2018-06-22 16:18:58'),
(81, 17, 'Phone Call Log', 'phone_call_log', 1, 1, 1, 1, '2018-06-22 16:20:57'),
(82, 17, 'Postal Dispatch', 'postal_dispatch', 1, 1, 1, 1, '2018-06-22 16:20:21'),
(83, 17, 'Postal Receive', 'postal_receive', 1, 1, 1, 1, '2018-06-22 16:20:04'),
(84, 17, 'Complain', 'complaint', 1, 1, 1, 1, '2018-07-03 14:10:55'),
(85, 17, 'Setup Font Office', 'setup_font_office', 1, 1, 1, 1, '2018-06-22 16:19:24'),
(86, 18, 'Staff', 'staff', 1, 1, 1, 1, '2018-06-22 16:23:31'),
(87, 18, 'Disable Staff', 'disable_staff', 1, 0, 0, 0, '2018-06-22 16:23:12'),
(88, 18, 'Staff Attendance', 'staff_attendance', 1, 1, 1, 0, '2018-06-22 16:23:10'),
(89, 18, 'Staff Attendance Report', 'staff_attendance_report', 1, 0, 0, 0, '2018-06-22 16:22:54'),
(90, 18, 'Staff Payroll', 'staff_payroll', 1, 1, 0, 1, '2018-06-22 16:22:51'),
(91, 18, 'Payroll Report', 'payroll_report', 1, 0, 0, 0, '2018-06-22 16:22:34'),
(93, 19, 'Homework', 'homework', 1, 1, 1, 1, '2018-06-22 16:23:50'),
(94, 19, 'Homework Evaluation', 'homework_evaluation', 1, 1, 0, 0, '2018-06-27 08:37:21'),
(95, 19, 'Homework Report', 'homework_report', 1, 0, 0, 0, '2018-06-22 16:23:54'),
(96, 20, 'Student Certificate', 'student_certificate', 1, 1, 1, 1, '2018-07-06 16:11:07'),
(97, 20, 'Generate Certificate', 'generate_certificate', 1, 0, 0, 0, '2018-07-06 16:07:16'),
(98, 20, 'Student ID Card', 'student_id_card', 1, 1, 1, 1, '2018-07-06 16:11:28'),
(99, 20, 'Generate ID Card', 'generate_id_card', 1, 0, 0, 0, '2018-07-06 16:11:49'),
(102, 21, 'Calendar To Do List', 'calendar_to_do_list', 1, 1, 1, 1, '2018-06-22 16:24:41'),
(104, 10, 'Item Category', 'item_category', 1, 1, 1, 1, '2018-06-22 16:04:33'),
(105, 1, 'Student Parent Login Details', 'student_parent_login_details', 1, 0, 0, 0, '2018-06-22 15:48:01'),
(106, 22, 'Quick Session Change', 'quick_session_change', 1, 0, 0, 0, '2018-06-22 16:24:45'),
(107, 1, 'Disable Student', 'disable_student', 1, 0, 0, 0, '2018-06-25 11:51:34'),
(108, 18, ' Approve Leave Request', 'approve_leave_request', 1, 1, 1, 1, '2018-07-02 15:47:41'),
(109, 18, 'Apply Leave', 'apply_leave', 1, 1, 1, 1, '2018-06-26 09:23:32'),
(110, 18, 'Leave Types ', 'leave_types', 1, 1, 1, 1, '2018-07-02 15:47:56'),
(111, 18, 'Department', 'department', 1, 1, 1, 1, '2018-06-26 09:27:07'),
(112, 18, 'Designation', 'designation', 1, 1, 1, 1, '2018-06-26 09:27:07'),
(113, 22, 'Fees Collection And Expense Monthly Chart', 'fees_collection_and_expense_monthly_chart', 1, 0, 0, 0, '2018-07-03 12:38:15'),
(114, 22, 'Fees Collection And Expense Yearly Chart', 'fees_collection_and_expense_yearly_chart', 1, 0, 0, 0, '2018-07-03 12:38:15'),
(115, 22, 'Monthly Fees Collection Widget', 'Monthly fees_collection_widget', 1, 0, 0, 0, '2018-07-03 12:43:35'),
(116, 22, 'Monthly Expense Widget', 'monthly_expense_widget', 1, 0, 0, 0, '2018-07-03 12:43:35'),
(117, 22, 'Student Count Widget', 'student_count_widget', 1, 0, 0, 0, '2018-07-03 12:43:35'),
(118, 22, 'Staff Role Count Widget', 'staff_role_count_widget', 1, 0, 0, 0, '2018-07-03 12:43:35'),
(119, 1, 'Guardian Report', 'guardian_report', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(120, 1, 'Student History', 'student_history', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(121, 1, 'Student Login Credential', 'student_login_credential', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(122, 5, 'Attendance By Date', 'attendance_by_date', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(123, 9, 'Add Student', 'add_student', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(124, 11, 'Student Transport Report', 'student_transport_report', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(125, 12, 'Student Hostel Report', 'student_hostel_report', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(126, 15, 'User Status', 'user_status', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(127, 18, 'Can See Other Users Profile', 'can_see_other_users_profile', 1, 0, 0, 0, '2018-07-03 14:12:29'),
(128, 1, 'Student Timeline', 'student_timeline', 0, 1, 0, 1, '2018-07-05 13:38:52'),
(129, 18, 'Staff Timeline', 'staff_timeline', 0, 1, 0, 1, '2018-07-05 13:38:52'),
(130, 15, 'Backup', 'backup', 1, 1, 0, 1, '2018-07-09 09:47:17'),
(131, 15, 'Restore', 'restore', 1, 0, 0, 0, '2018-07-09 09:47:17'),
(133, 6, 'Examination Weightage', 'examination_weightage', 1, 1, 1, 1, '2019-03-28 09:33:13'),
(134, 6, 'Marksheet', 'marksheet', 1, 0, 0, 0, '2019-03-28 09:33:31'),
(135, 6, 'Grade Setting', 'grade_setting', 1, 1, 1, 1, '2019-03-28 09:34:48'),
(136, 23, 'Account', 'account', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(137, 23, 'Account General Setting', 'account_general_setting', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(138, 23, 'Account Chart of Accounts', 'account_chart_of_accounts', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(139, 23, 'Account Categories', 'account_categories', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(140, 23, 'Account Customer/Supplier', 'account_personnel', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(141, 23, 'Account Invoice', 'account_invoice', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(142, 23, 'Account Journal', 'account_journal', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(143, 23, 'Account Payments', 'account_payments', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(144, 23, 'Account Receipts', 'account_receipts', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(145, 23, 'Personnel Ledger', 'personnel_ledger', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(146, 23, 'Account Opening Balances', 'account_opening_balances', 1, 1, 1, 1, '2021-09-17 03:35:39'),
(147, 23, 'Account Income and Expense Chart', 'income_and_expense_yearly_chart', 1, 0, 0, 0, '2021-09-17 03:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `permission_group`
--

CREATE TABLE `permission_group` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_code` varchar(100) NOT NULL,
  `is_active` int(11) DEFAULT 0,
  `system` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission_group`
--

INSERT INTO `permission_group` (`id`, `name`, `short_code`, `is_active`, `system`, `created_at`) VALUES
(1, 'Student Information', 'student_information', 1, 1, '2018-06-27 09:09:31'),
(2, 'Fees Collection', 'fees_collection', 1, 0, '2018-07-11 08:19:10'),
(3, 'Income', 'income', 1, 0, '2018-06-27 06:19:05'),
(4, 'Expense', 'expense', 1, 0, '2018-07-04 07:07:33'),
(5, 'Student Attendance', 'student_attendance', 1, 0, '2018-07-02 13:18:08'),
(6, 'Examination', 'examination', 1, 0, '2018-07-11 08:19:08'),
(7, 'Academics', 'academics', 1, 1, '2018-07-02 12:55:43'),
(8, 'Download Center', 'download_center', 1, 0, '2018-07-02 13:19:29'),
(9, 'Library', 'library', 1, 0, '2018-06-28 16:43:14'),
(10, 'Inventory', 'inventory', 1, 0, '2018-12-17 12:02:24'),
(11, 'Transport', 'transport', 1, 0, '2018-06-27 13:21:26'),
(12, 'Hostel', 'hostel', 1, 0, '2018-07-02 13:19:32'),
(13, 'Communicate', 'communicate', 1, 0, '2018-07-02 13:20:00'),
(14, 'Reports', 'reports', 1, 1, '2018-06-27 09:10:22'),
(15, 'System Settings', 'system_settings', 1, 1, '2018-06-27 09:10:28'),
(16, 'Front CMS', 'front_cms', 1, 0, '2018-07-10 10:46:54'),
(17, 'Front Office', 'front_office', 1, 0, '2018-12-21 08:21:34'),
(18, 'Human Resource', 'human_resource', 1, 1, '2018-06-27 09:11:02'),
(19, 'Homework', 'homework', 1, 0, '2018-06-27 06:19:38'),
(20, 'Certificate', 'certificate', 1, 0, '2018-06-27 13:21:29'),
(21, 'Calendar To Do List', 'calendar_to_do_list', 1, 0, '2018-06-27 09:12:25'),
(22, 'Dashboard and Widgets', 'dashboard_and_widgets', 1, 1, '2018-06-27 09:11:17'),
(23, 'Account', 'account', 1, 0, '2021-09-17 03:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `prabhupay_app_payment`
--

CREATE TABLE `prabhupay_app_payment` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `student_session_id` int(11) NOT NULL,
  `student_session_fees_id` int(11) NOT NULL,
  `student_fees_payment_history_id` int(11) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prabhupay_init`
--

CREATE TABLE `prabhupay_init` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` char(36) CHARACTER SET ascii NOT NULL,
  `student_session_fees_id` int(11) UNSIGNED NOT NULL,
  `TransactionId` varchar(255) NOT NULL,
  `ProcessURL` varchar(255) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `read_notification`
--

CREATE TABLE `read_notification` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE `reference` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `is_system` int(1) NOT NULL DEFAULT 0,
  `is_superadmin` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `is_active`, `is_system`, `is_superadmin`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, 0, 1, 0, '2018-06-30 15:39:11', '0000-00-00 00:00:00'),
(2, 'Teacher', NULL, 0, 1, 0, '2018-06-30 15:39:14', '0000-00-00 00:00:00'),
(3, 'Accountant', NULL, 0, 1, 0, '2018-06-30 15:39:17', '0000-00-00 00:00:00'),
(4, 'Librarian', NULL, 0, 1, 0, '2018-06-30 15:39:21', '0000-00-00 00:00:00'),
(6, 'Receptionist', NULL, 0, 1, 0, '2018-07-02 05:39:03', '0000-00-00 00:00:00'),
(7, 'Super Admin', NULL, 0, 1, 1, '2018-07-11 14:11:29', '0000-00-00 00:00:00'),
(8, 'NewRole', NULL, 0, 0, 0, '2018-12-17 06:46:57', '0000-00-00 00:00:00'),
(9, 'General', NULL, 0, 0, 0, '2018-12-17 11:10:06', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `perm_cat_id` int(11) DEFAULT NULL,
  `can_view` int(11) DEFAULT NULL,
  `can_add` int(11) DEFAULT NULL,
  `can_edit` int(11) DEFAULT NULL,
  `can_delete` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_permissions`
--

INSERT INTO `roles_permissions` (`id`, `role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) VALUES
(3, 1, 3, 1, 1, 1, 1, '2018-07-06 15:12:08'),
(4, 1, 4, 1, 1, 1, 1, '2018-07-06 15:13:01'),
(6, 1, 5, 1, 1, 0, 1, '2018-07-02 16:49:46'),
(8, 1, 7, 1, 1, 1, 1, '2018-07-06 15:13:29'),
(9, 1, 8, 1, 1, 1, 1, '2018-07-06 15:13:53'),
(10, 1, 17, 1, 1, 1, 1, '2018-07-06 15:18:56'),
(11, 1, 78, 1, 1, 1, 1, '2018-07-03 06:19:43'),
(13, 1, 69, 1, 1, 1, 1, '2018-07-06 15:14:15'),
(14, 1, 70, 1, 1, 1, 1, '2018-07-06 15:14:39'),
(23, 1, 12, 1, 1, 1, 1, '2018-07-06 15:15:38'),
(24, 1, 13, 1, 1, 1, 1, '2018-07-06 15:18:28'),
(26, 1, 15, 1, 1, 1, 0, '2018-07-02 16:54:21'),
(28, 1, 19, 1, 1, 1, 0, '2018-07-02 17:01:10'),
(29, 1, 20, 1, 1, 1, 1, '2018-07-06 15:19:50'),
(30, 1, 76, 1, 1, 1, 0, '2018-07-02 17:01:10'),
(31, 1, 21, 1, 1, 1, 0, '2018-07-02 17:01:38'),
(32, 1, 22, 1, 1, 1, 1, '2018-07-02 17:02:05'),
(33, 1, 23, 1, 1, 1, 1, '2018-07-06 15:20:17'),
(34, 1, 24, 1, 1, 1, 1, '2018-07-06 15:20:39'),
(35, 1, 25, 1, 1, 1, 1, '2018-07-06 15:22:35'),
(37, 1, 77, 1, 1, 1, 1, '2018-07-06 15:19:50'),
(43, 1, 32, 1, 1, 1, 1, '2018-07-06 15:52:05'),
(44, 1, 33, 1, 1, 1, 1, '2018-07-06 15:52:29'),
(45, 1, 34, 1, 1, 1, 1, '2018-07-06 15:53:59'),
(46, 1, 35, 1, 1, 1, 1, '2018-07-06 15:54:34'),
(47, 1, 104, 1, 1, 1, 1, '2018-07-06 15:53:08'),
(48, 1, 37, 1, 1, 1, 1, '2018-07-06 15:55:30'),
(49, 1, 38, 1, 1, 1, 1, '2018-07-09 10:45:27'),
(53, 1, 43, 1, 1, 1, 1, '2018-07-10 15:00:31'),
(58, 1, 52, 1, 1, 0, 1, '2018-07-09 08:49:43'),
(61, 1, 55, 1, 1, 1, 1, '2018-07-02 14:54:16'),
(67, 1, 61, 1, 1, 0, 1, '2018-07-09 11:29:19'),
(68, 1, 62, 1, 1, 0, 1, '2018-07-09 11:29:19'),
(69, 1, 63, 1, 1, 0, 1, '2018-07-09 09:21:38'),
(70, 1, 64, 1, 1, 1, 1, '2018-07-09 08:32:19'),
(71, 1, 65, 1, 1, 1, 1, '2018-07-09 08:41:21'),
(72, 1, 66, 1, 1, 1, 1, '2018-07-09 08:43:09'),
(73, 1, 67, 1, 1, 1, 1, '2018-07-09 08:44:47'),
(74, 1, 79, 1, 1, 0, 1, '2018-07-02 17:34:53'),
(75, 1, 80, 1, 1, 1, 1, '2018-07-06 15:11:23'),
(76, 1, 81, 1, 1, 1, 1, '2018-07-06 15:11:23'),
(78, 1, 83, 1, 1, 1, 1, '2018-07-06 15:11:23'),
(79, 1, 84, 1, 1, 1, 1, '2018-07-06 15:11:23'),
(80, 1, 85, 1, 1, 1, 1, '2018-07-12 05:46:00'),
(83, 1, 88, 1, 1, 1, 0, '2018-07-03 17:34:20'),
(87, 1, 92, 1, 1, 1, 1, '2018-06-26 09:03:43'),
(88, 1, 93, 1, 1, 1, 1, '2018-07-09 06:54:20'),
(94, 1, 82, 1, 1, 1, 1, '2018-07-06 15:11:23'),
(120, 1, 39, 1, 1, 1, 1, '2018-07-06 15:56:28'),
(140, 1, 110, 1, 1, 1, 1, '2018-07-06 15:25:08'),
(141, 1, 111, 1, 1, 1, 1, '2018-07-06 15:26:28'),
(142, 1, 112, 1, 1, 1, 1, '2018-07-06 15:26:28'),
(145, 1, 94, 1, 1, 0, 0, '2018-07-09 06:50:40'),
(147, 2, 43, 1, 1, 1, 1, '2018-06-30 13:16:24'),
(148, 2, 44, 1, 0, 0, 0, '2018-06-27 16:47:09'),
(149, 2, 46, 1, 0, 0, 0, '2018-06-28 05:56:41'),
(156, 1, 9, 1, 1, 1, 1, '2018-07-06 15:14:53'),
(157, 1, 10, 1, 1, 1, 1, '2018-07-06 15:15:12'),
(159, 1, 40, 1, 1, 1, 1, '2018-07-09 10:39:40'),
(160, 1, 41, 1, 1, 1, 1, '2018-07-06 15:57:09'),
(161, 1, 42, 1, 1, 1, 1, '2018-07-09 10:43:14'),
(169, 1, 27, 1, 1, 0, 1, '2018-07-02 17:06:58'),
(178, 1, 54, 1, 0, 1, 0, '2018-07-05 14:39:22'),
(179, 1, 56, 1, 0, 1, 0, '2018-07-05 14:39:22'),
(180, 1, 57, 1, 0, 1, 0, '2018-07-05 14:39:22'),
(181, 1, 58, 1, 0, 1, 0, '2018-07-05 14:39:22'),
(182, 1, 59, 1, 0, 1, 0, '2018-07-05 14:39:22'),
(183, 1, 60, 1, 0, 1, 0, '2018-07-05 14:39:22'),
(190, 1, 105, 1, 0, 0, 0, '2018-07-02 16:43:25'),
(193, 1, 6, 1, 0, 0, 0, '2018-07-02 16:49:46'),
(194, 1, 68, 1, 0, 0, 0, '2018-07-02 16:49:46'),
(196, 1, 72, 1, 0, 0, 0, '2018-07-02 16:49:46'),
(197, 1, 73, 1, 0, 0, 0, '2018-07-02 16:49:46'),
(198, 1, 74, 1, 0, 0, 0, '2018-07-02 16:49:46'),
(199, 1, 75, 1, 0, 0, 0, '2018-07-02 16:49:46'),
(201, 1, 14, 1, 0, 0, 0, '2018-07-02 16:52:03'),
(203, 1, 16, 1, 0, 0, 0, '2018-07-02 16:54:21'),
(204, 1, 26, 1, 0, 0, 0, '2018-07-02 17:02:05'),
(206, 1, 29, 1, 0, 0, 0, '2018-07-02 17:13:54'),
(207, 1, 30, 1, 0, 0, 0, '2018-07-02 17:13:54'),
(208, 1, 31, 1, 0, 0, 0, '2018-07-02 17:15:36'),
(215, 1, 50, 1, 0, 0, 0, '2018-07-02 17:34:53'),
(216, 1, 51, 1, 0, 0, 0, '2018-07-02 17:34:53'),
(222, 1, 1, 1, 1, 1, 1, '2018-07-10 15:00:31'),
(225, 1, 108, 1, 1, 1, 1, '2018-07-09 07:47:26'),
(227, 1, 91, 1, 0, 0, 0, '2018-07-03 07:19:27'),
(229, 1, 89, 1, 0, 0, 0, '2018-07-03 07:30:53'),
(230, 10, 53, 0, 1, 0, 0, '2018-07-03 09:22:55'),
(231, 10, 54, 0, 0, 1, 0, '2018-07-03 09:22:55'),
(232, 10, 55, 1, 1, 1, 1, '2018-07-03 09:28:42'),
(233, 10, 56, 0, 0, 1, 0, '2018-07-03 09:22:55'),
(235, 10, 58, 0, 0, 1, 0, '2018-07-03 09:22:55'),
(236, 10, 59, 0, 0, 1, 0, '2018-07-03 09:22:55'),
(239, 10, 1, 1, 1, 1, 1, '2018-07-03 09:46:43'),
(241, 10, 3, 1, 0, 0, 0, '2018-07-03 09:53:56'),
(242, 10, 2, 1, 0, 0, 0, '2018-07-03 09:54:39'),
(243, 10, 4, 1, 0, 1, 1, '2018-07-03 10:01:24'),
(245, 10, 107, 1, 0, 0, 0, '2018-07-03 10:06:41'),
(246, 10, 5, 1, 1, 0, 1, '2018-07-03 10:08:18'),
(247, 10, 7, 1, 1, 1, 1, '2018-07-03 10:12:07'),
(248, 10, 68, 1, 0, 0, 0, '2018-07-03 10:12:53'),
(249, 10, 69, 1, 1, 1, 1, '2018-07-03 10:19:46'),
(250, 10, 70, 1, 0, 0, 1, '2018-07-03 10:22:40'),
(251, 10, 72, 1, 0, 0, 0, '2018-07-03 10:26:46'),
(252, 10, 73, 1, 0, 0, 0, '2018-07-03 10:26:46'),
(253, 10, 74, 1, 0, 0, 0, '2018-07-03 10:28:34'),
(254, 10, 75, 1, 0, 0, 0, '2018-07-03 10:28:34'),
(255, 10, 9, 1, 1, 1, 1, '2018-07-03 10:32:22'),
(256, 10, 10, 1, 1, 1, 1, '2018-07-03 10:33:09'),
(257, 10, 11, 1, 0, 0, 0, '2018-07-03 10:33:09'),
(258, 10, 12, 1, 1, 1, 1, '2018-07-03 10:38:40'),
(259, 10, 13, 1, 1, 1, 1, '2018-07-03 10:38:40'),
(260, 10, 14, 1, 0, 0, 0, '2018-07-03 10:38:53'),
(261, 10, 15, 1, 1, 1, 0, '2018-07-03 10:41:28'),
(262, 10, 16, 1, 0, 0, 0, '2018-07-03 10:42:12'),
(263, 10, 17, 1, 1, 1, 1, '2018-07-03 10:44:30'),
(264, 10, 19, 1, 1, 1, 0, '2018-07-03 10:45:45'),
(265, 10, 20, 1, 1, 1, 1, '2018-07-03 10:48:51'),
(266, 10, 76, 1, 0, 0, 0, '2018-07-03 10:51:21'),
(267, 10, 21, 1, 1, 1, 0, '2018-07-03 10:52:45'),
(268, 10, 22, 1, 1, 1, 1, '2018-07-03 10:55:00'),
(269, 10, 23, 1, 1, 1, 1, '2018-07-03 10:57:16'),
(270, 10, 24, 1, 1, 1, 1, '2018-07-03 10:57:49'),
(271, 10, 25, 1, 1, 1, 1, '2018-07-03 10:57:49'),
(272, 10, 26, 1, 0, 0, 0, '2018-07-03 10:58:25'),
(273, 10, 77, 1, 1, 1, 1, '2018-07-03 10:59:57'),
(274, 10, 27, 1, 1, 0, 1, '2018-07-03 11:00:36'),
(275, 10, 28, 1, 1, 1, 1, '2018-07-03 11:03:09'),
(276, 10, 29, 1, 0, 0, 0, '2018-07-03 11:04:03'),
(277, 10, 30, 1, 0, 0, 0, '2018-07-03 11:04:03'),
(278, 10, 31, 1, 0, 0, 0, '2018-07-03 11:04:03'),
(279, 10, 32, 1, 1, 1, 1, '2018-07-03 11:05:42'),
(280, 10, 33, 1, 1, 1, 1, '2018-07-03 11:06:32'),
(281, 10, 34, 1, 1, 1, 1, '2018-07-03 11:08:03'),
(282, 10, 35, 1, 1, 1, 1, '2018-07-03 11:08:41'),
(283, 10, 104, 1, 1, 1, 1, '2018-07-03 11:10:43'),
(284, 10, 37, 1, 1, 1, 1, '2018-07-03 11:12:42'),
(285, 10, 38, 1, 1, 1, 1, '2018-07-03 11:13:56'),
(286, 10, 39, 1, 1, 1, 1, '2018-07-03 11:15:39'),
(287, 10, 40, 1, 1, 1, 1, '2018-07-03 11:17:22'),
(288, 10, 41, 1, 1, 1, 1, '2018-07-03 11:18:54'),
(289, 10, 42, 1, 1, 1, 1, '2018-07-03 11:19:31'),
(290, 10, 43, 1, 1, 1, 1, '2018-07-03 11:21:15'),
(291, 10, 44, 1, 0, 0, 0, '2018-07-03 11:22:06'),
(292, 10, 46, 1, 0, 0, 0, '2018-07-03 11:22:06'),
(293, 10, 50, 1, 0, 0, 0, '2018-07-03 11:22:59'),
(294, 10, 51, 1, 0, 0, 0, '2018-07-03 11:22:59'),
(295, 10, 60, 0, 0, 1, 0, '2018-07-03 11:25:05'),
(296, 10, 61, 1, 1, 1, 1, '2018-07-03 11:26:52'),
(297, 10, 62, 1, 1, 1, 1, '2018-07-03 11:28:53'),
(298, 10, 63, 1, 1, 0, 0, '2018-07-03 11:29:37'),
(299, 10, 64, 1, 1, 1, 1, '2018-07-03 11:30:27'),
(300, 10, 65, 1, 1, 1, 1, '2018-07-03 11:32:51'),
(301, 10, 66, 1, 1, 1, 1, '2018-07-03 11:32:51'),
(302, 10, 67, 1, 0, 0, 0, '2018-07-03 11:32:51'),
(303, 10, 78, 1, 1, 1, 1, '2018-07-04 09:40:04'),
(307, 1, 126, 1, 0, 0, 0, '2018-07-03 14:56:13'),
(310, 1, 119, 1, 0, 0, 0, '2018-07-03 15:45:00'),
(311, 1, 120, 1, 0, 0, 0, '2018-07-03 15:45:00'),
(312, 1, 107, 1, 0, 0, 0, '2018-07-03 15:45:12'),
(313, 1, 122, 1, 0, 0, 0, '2018-07-03 15:49:37'),
(315, 1, 123, 1, 0, 0, 0, '2018-07-03 15:57:03'),
(317, 1, 124, 1, 0, 0, 0, '2018-07-03 15:59:14'),
(320, 1, 47, 1, 0, 0, 0, '2018-07-03 16:31:12'),
(321, 1, 121, 1, 0, 0, 0, '2018-07-03 16:31:12'),
(322, 1, 109, 1, 1, 1, 1, '2018-07-03 16:40:54'),
(369, 1, 102, 1, 1, 1, 1, '2018-07-11 17:31:47'),
(372, 10, 79, 1, 1, 0, 0, '2018-07-04 09:40:04'),
(373, 10, 80, 1, 1, 1, 1, '2018-07-04 09:53:09'),
(374, 10, 81, 1, 1, 1, 1, '2018-07-04 09:53:50'),
(375, 10, 82, 1, 1, 1, 1, '2018-07-04 09:56:54'),
(376, 10, 83, 1, 1, 1, 1, '2018-07-04 09:57:55'),
(377, 10, 84, 1, 1, 1, 1, '2018-07-04 10:00:26'),
(378, 10, 85, 1, 1, 1, 1, '2018-07-04 10:02:54'),
(379, 10, 86, 1, 1, 1, 1, '2018-07-04 10:16:18'),
(380, 10, 87, 1, 0, 0, 0, '2018-07-04 10:19:49'),
(381, 10, 88, 1, 1, 1, 0, '2018-07-04 10:21:20'),
(382, 10, 89, 1, 0, 0, 0, '2018-07-04 10:21:51'),
(383, 10, 90, 1, 1, 0, 1, '2018-07-04 10:25:01'),
(384, 10, 91, 1, 0, 0, 0, '2018-07-04 10:25:01'),
(385, 10, 108, 1, 1, 1, 1, '2018-07-04 10:27:46'),
(386, 10, 109, 1, 1, 1, 1, '2018-07-04 10:28:26'),
(387, 10, 110, 1, 1, 1, 1, '2018-07-04 10:32:43'),
(388, 10, 111, 1, 1, 1, 1, '2018-07-04 10:33:21'),
(389, 10, 112, 1, 1, 1, 1, '2018-07-04 10:35:06'),
(390, 10, 127, 1, 0, 0, 0, '2018-07-04 10:35:06'),
(391, 10, 93, 1, 1, 1, 1, '2018-07-04 10:37:14'),
(392, 10, 94, 1, 1, 0, 0, '2018-07-04 10:38:02'),
(394, 10, 95, 1, 0, 0, 0, '2018-07-04 10:38:44'),
(395, 10, 102, 1, 1, 1, 1, '2018-07-04 10:41:02'),
(396, 10, 106, 1, 0, 0, 0, '2018-07-04 10:41:39'),
(397, 10, 113, 1, 0, 0, 0, '2018-07-04 10:42:37'),
(398, 10, 114, 1, 0, 0, 0, '2018-07-04 10:42:37'),
(399, 10, 115, 1, 0, 0, 0, '2018-07-04 10:48:45'),
(400, 10, 116, 1, 0, 0, 0, '2018-07-04 10:48:45'),
(401, 10, 117, 1, 0, 0, 0, '2018-07-04 10:49:43'),
(402, 10, 118, 1, 0, 0, 0, '2018-07-04 10:49:43'),
(411, 1, 2, 1, 0, 0, 0, '2018-07-04 13:46:10'),
(412, 1, 11, 1, 0, 0, 0, '2018-07-04 14:24:05'),
(416, 2, 3, 1, 1, 1, 1, '2018-07-10 12:17:12'),
(428, 2, 4, 1, 1, 1, 1, '2018-07-05 07:40:38'),
(432, 1, 128, 0, 1, 0, 1, '2018-07-05 13:39:50'),
(434, 1, 125, 1, 0, 0, 0, '2018-07-06 15:29:26'),
(435, 1, 96, 1, 1, 1, 1, '2018-07-09 06:33:54'),
(437, 1, 98, 1, 1, 1, 1, '2018-07-09 06:44:17'),
(444, 1, 99, 1, 0, 0, 0, '2018-07-06 17:11:22'),
(445, 1, 48, 1, 0, 0, 0, '2018-07-06 17:19:35'),
(446, 1, 49, 1, 0, 0, 0, '2018-07-06 17:19:35'),
(448, 1, 71, 1, 0, 0, 0, '2018-07-08 09:17:06'),
(453, 1, 106, 1, 0, 0, 0, '2018-07-09 06:17:33'),
(454, 1, 113, 1, 0, 0, 0, '2018-07-09 06:17:33'),
(455, 1, 114, 1, 0, 0, 0, '2018-07-09 06:17:33'),
(456, 1, 115, 1, 0, 0, 0, '2018-07-09 06:17:33'),
(457, 1, 116, 1, 0, 0, 0, '2018-07-09 06:17:33'),
(458, 1, 117, 1, 0, 0, 0, '2018-07-09 06:17:33'),
(459, 1, 118, 1, 0, 0, 0, '2018-07-09 06:17:33'),
(461, 1, 97, 1, 0, 0, 0, '2018-07-09 06:30:16'),
(462, 1, 95, 1, 0, 0, 0, '2018-07-09 06:48:41'),
(464, 1, 86, 1, 1, 1, 1, '2018-07-09 11:39:48'),
(466, 1, 129, 0, 1, 0, 1, '2018-07-09 07:09:30'),
(467, 1, 87, 1, 0, 0, 0, '2018-07-09 07:11:59'),
(468, 1, 90, 1, 1, 0, 1, '2018-07-09 07:22:50'),
(471, 1, 53, 0, 1, 0, 0, '2018-07-09 08:50:44'),
(474, 1, 130, 1, 1, 0, 1, '2018-07-09 16:26:36'),
(476, 1, 131, 1, 0, 0, 0, '2018-07-09 10:23:32'),
(477, 2, 1, 1, 1, 1, 1, '2018-07-11 12:26:27'),
(478, 2, 2, 1, 0, 0, 0, '2018-07-10 12:17:12'),
(479, 2, 47, 1, 0, 0, 0, '2018-07-10 12:17:12'),
(480, 2, 105, 1, 0, 0, 0, '2018-07-10 12:17:12'),
(482, 2, 119, 1, 0, 0, 0, '2018-07-10 12:17:12'),
(483, 2, 120, 1, 0, 0, 0, '2018-07-10 12:17:12'),
(485, 2, 15, 1, 1, 1, 0, '2018-07-10 12:17:12'),
(486, 2, 16, 1, 0, 0, 0, '2018-07-10 12:17:12'),
(487, 2, 122, 1, 0, 0, 0, '2018-07-10 12:17:12'),
(492, 2, 21, 1, 0, 0, 0, '2018-07-12 05:50:27'),
(493, 2, 22, 1, 0, 0, 0, '2018-07-12 05:50:27'),
(494, 2, 23, 1, 0, 0, 0, '2018-07-12 05:50:27'),
(495, 2, 24, 1, 0, 0, 0, '2018-07-12 05:50:27'),
(496, 2, 25, 1, 0, 0, 0, '2018-07-12 05:50:27'),
(498, 2, 77, 1, 0, 0, 0, '2018-07-12 05:50:27'),
(499, 2, 27, 1, 1, 0, 1, '2018-07-10 12:17:12'),
(502, 2, 93, 1, 1, 1, 1, '2018-07-10 12:17:12'),
(503, 2, 94, 1, 1, 0, 0, '2018-07-10 12:17:12'),
(504, 2, 95, 1, 0, 0, 0, '2018-07-10 12:17:12'),
(505, 3, 5, 1, 1, 0, 1, '2018-07-10 12:37:30'),
(506, 3, 6, 1, 0, 0, 0, '2018-07-10 12:37:30'),
(507, 3, 7, 1, 1, 1, 1, '2018-07-10 12:37:30'),
(508, 3, 8, 1, 1, 1, 1, '2018-07-10 12:37:30'),
(509, 3, 68, 1, 0, 0, 0, '2018-07-10 12:37:30'),
(510, 3, 69, 1, 1, 1, 1, '2018-07-10 12:37:30'),
(511, 3, 70, 1, 1, 1, 1, '2018-07-10 12:37:30'),
(512, 3, 71, 1, 0, 0, 0, '2018-07-10 12:37:30'),
(513, 3, 72, 1, 0, 0, 0, '2018-07-10 12:37:30'),
(514, 3, 73, 1, 0, 0, 0, '2018-07-10 12:37:30'),
(515, 3, 74, 1, 0, 0, 0, '2018-07-10 12:37:30'),
(517, 3, 75, 1, 0, 0, 0, '2018-07-10 12:40:38'),
(518, 3, 9, 1, 1, 1, 1, '2018-07-10 12:40:38'),
(519, 3, 10, 1, 1, 1, 1, '2018-07-10 12:40:38'),
(520, 3, 11, 1, 0, 0, 0, '2018-07-10 12:40:38'),
(521, 3, 12, 1, 1, 1, 1, '2018-07-10 12:47:00'),
(522, 3, 13, 1, 1, 1, 1, '2018-07-10 12:47:00'),
(523, 3, 14, 1, 0, 0, 0, '2018-07-10 12:47:00'),
(524, 3, 86, 1, 1, 1, 1, '2018-07-10 12:48:44'),
(525, 3, 87, 1, 0, 0, 0, '2018-07-10 12:48:44'),
(526, 3, 88, 1, 1, 1, 0, '2018-07-10 12:48:44'),
(527, 3, 89, 1, 0, 0, 0, '2018-07-10 12:48:44'),
(528, 3, 90, 1, 1, 0, 1, '2018-07-10 12:48:44'),
(529, 3, 91, 1, 0, 0, 0, '2018-07-10 12:48:44'),
(530, 3, 108, 1, 1, 1, 1, '2018-07-10 12:48:44'),
(531, 3, 109, 1, 1, 1, 1, '2018-07-10 12:48:44'),
(532, 3, 110, 1, 1, 1, 1, '2018-07-10 12:48:44'),
(533, 3, 111, 1, 1, 1, 1, '2018-07-10 12:48:44'),
(534, 3, 112, 1, 1, 1, 1, '2018-07-10 12:48:44'),
(535, 3, 127, 1, 0, 0, 0, '2018-07-10 12:48:44'),
(536, 3, 129, 0, 1, 0, 1, '2018-07-10 12:48:44'),
(537, 3, 43, 1, 1, 1, 1, '2018-07-10 12:49:49'),
(538, 3, 44, 1, 0, 0, 0, '2018-07-10 12:49:49'),
(539, 3, 46, 1, 0, 0, 0, '2018-07-10 12:49:49'),
(540, 3, 31, 1, 0, 0, 0, '2018-07-10 12:50:38'),
(541, 3, 32, 1, 1, 1, 1, '2018-07-10 12:50:38'),
(542, 3, 33, 1, 1, 1, 1, '2018-07-10 12:50:38'),
(543, 3, 34, 1, 1, 1, 1, '2018-07-10 12:50:38'),
(544, 3, 35, 1, 1, 1, 1, '2018-07-10 12:50:38'),
(545, 3, 104, 1, 1, 1, 1, '2018-07-10 12:50:38'),
(546, 3, 37, 1, 1, 1, 1, '2018-07-10 12:52:17'),
(547, 3, 38, 1, 1, 1, 1, '2018-07-10 12:52:17'),
(548, 3, 39, 1, 1, 1, 1, '2018-07-10 12:52:17'),
(549, 3, 124, 1, 0, 0, 0, '2018-07-10 12:52:17'),
(553, 6, 78, 1, 1, 1, 1, '2018-07-10 13:02:18'),
(554, 6, 79, 1, 1, 0, 1, '2018-07-10 13:02:18'),
(555, 6, 80, 1, 1, 1, 1, '2018-07-10 13:02:18'),
(556, 6, 81, 1, 1, 1, 1, '2018-07-10 13:02:18'),
(557, 6, 82, 1, 1, 1, 1, '2018-07-10 13:02:18'),
(558, 6, 83, 1, 1, 1, 1, '2018-07-10 13:02:18'),
(559, 6, 84, 1, 1, 1, 1, '2018-07-10 13:02:18'),
(560, 6, 85, 1, 1, 1, 1, '2018-07-10 13:02:18'),
(561, 6, 86, 1, 0, 0, 0, '2018-07-10 13:11:10'),
(574, 6, 43, 1, 1, 1, 1, '2018-07-10 13:05:33'),
(575, 6, 44, 1, 0, 0, 0, '2018-07-10 13:05:33'),
(576, 6, 46, 1, 0, 0, 0, '2018-07-10 13:05:33'),
(578, 6, 102, 1, 1, 1, 1, '2018-07-10 13:20:33'),
(579, 4, 28, 1, 1, 1, 1, '2018-07-10 13:23:54'),
(580, 4, 29, 1, 0, 0, 0, '2018-07-10 13:23:54'),
(581, 4, 30, 1, 0, 0, 0, '2018-07-10 13:23:54'),
(582, 4, 123, 1, 0, 0, 0, '2018-07-10 13:23:54'),
(583, 4, 86, 1, 0, 0, 0, '2018-07-10 13:24:13'),
(584, 4, 43, 1, 1, 1, 1, '2018-07-10 13:25:14'),
(585, 4, 44, 1, 0, 0, 0, '2018-07-10 13:25:14'),
(586, 4, 46, 1, 0, 0, 0, '2018-07-10 13:25:14'),
(588, 2, 102, 1, 1, 1, 1, '2018-07-12 05:47:45'),
(589, 2, 106, 1, 0, 0, 0, '2018-07-10 13:25:37'),
(590, 2, 117, 1, 0, 0, 0, '2018-07-10 13:25:37'),
(591, 3, 40, 1, 1, 1, 1, '2018-07-10 13:28:12'),
(592, 3, 41, 1, 1, 1, 1, '2018-07-10 13:28:12'),
(593, 3, 42, 1, 1, 1, 1, '2018-07-10 13:28:12'),
(594, 3, 125, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(595, 3, 48, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(596, 3, 49, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(597, 3, 102, 1, 1, 1, 1, '2018-07-10 13:28:12'),
(598, 3, 106, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(599, 3, 113, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(600, 3, 114, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(601, 3, 115, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(602, 3, 116, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(603, 3, 117, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(604, 3, 118, 1, 0, 0, 0, '2018-07-10 13:28:12'),
(609, 6, 117, 1, 0, 0, 0, '2018-07-10 13:30:48'),
(611, 2, 86, 1, 0, 0, 0, '2018-07-10 13:38:49'),
(612, 1, 44, 1, 0, 0, 0, '2018-07-10 15:00:31'),
(613, 1, 46, 1, 0, 0, 0, '2018-07-10 15:00:31'),
(616, 1, 127, 1, 0, 0, 0, '2018-07-11 08:22:46'),
(617, 2, 17, 1, 1, 1, 1, '2018-07-11 12:25:14'),
(618, 2, 19, 1, 1, 1, 0, '2018-07-11 12:25:14'),
(619, 2, 20, 1, 1, 1, 1, '2018-07-11 12:25:14'),
(620, 2, 76, 1, 1, 1, 0, '2018-07-11 12:25:14'),
(621, 2, 107, 1, 0, 0, 0, '2018-07-11 12:26:27'),
(622, 2, 121, 1, 0, 0, 0, '2018-07-11 12:26:27'),
(623, 2, 128, 0, 1, 0, 1, '2018-07-11 12:26:27'),
(625, 1, 28, 1, 1, 1, 1, '2018-07-11 14:57:18'),
(626, 6, 1, 1, 0, 0, 0, '2018-07-12 05:53:47'),
(627, 6, 21, 1, 0, 0, 0, '2018-07-12 05:53:47'),
(628, 6, 22, 1, 0, 0, 0, '2018-07-12 05:53:47'),
(629, 6, 23, 1, 0, 0, 0, '2018-07-12 05:53:47'),
(630, 6, 24, 1, 0, 0, 0, '2018-07-12 05:53:47'),
(631, 6, 25, 1, 0, 0, 0, '2018-07-12 05:53:47'),
(632, 6, 77, 1, 0, 0, 0, '2018-07-12 05:53:47'),
(633, 6, 106, 1, 0, 0, 0, '2018-07-12 05:53:47'),
(634, 4, 102, 1, 1, 1, 1, '2018-07-12 05:54:23'),
(635, 4, 106, 1, 0, 0, 0, '2018-07-12 05:54:23'),
(636, 4, 117, 1, 0, 0, 0, '2018-07-12 05:54:23'),
(637, 9, 1, 1, 1, 1, 1, '2018-12-17 11:18:12'),
(638, 9, 86, 1, 1, 1, 1, '2018-12-17 11:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `room_type` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `route_stops`
--

CREATE TABLE `route_stops` (
  `id` int(11) UNSIGNED NOT NULL,
  `route_id` int(11) UNSIGNED NOT NULL,
  `stop_name` varchar(255) NOT NULL,
  `fare` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `school_houses`
--

CREATE TABLE `school_houses` (
  `id` int(11) NOT NULL,
  `house_name` varchar(200) NOT NULL,
  `description` varchar(400) NOT NULL,
  `is_active` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sch_settings`
--

CREATE TABLE `sch_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `header_image` varchar(250) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `dise_code` varchar(50) DEFAULT NULL,
  `date_format` varchar(50) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `currency_symbol` varchar(50) NOT NULL,
  `is_rtl` varchar(10) DEFAULT 'disabled',
  `timezone` varchar(30) DEFAULT 'UTC',
  `session_id` int(11) DEFAULT NULL,
  `start_month` varchar(40) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `theme` varchar(200) NOT NULL DEFAULT 'default.jpg',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cron_secret_key` varchar(100) NOT NULL,
  `fee_due_days` tinyint(3) UNSIGNED NOT NULL DEFAULT 20,
  `fee_gen_day` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `class_teacher` varchar(100) NOT NULL,
  `public_holiday` varchar(100) NOT NULL,
  `is_conventional` enum('yes','no') NOT NULL DEFAULT 'no',
  `facebook_link` varchar(250) NOT NULL,
  `youtube_link` varchar(250) NOT NULL,
  `twitter_link` varchar(250) NOT NULL,
  `linkedin_link` varchar(250) NOT NULL,
  `tag_line` text NOT NULL,
  `first_day` varchar(10) NOT NULL,
  `datechooser` enum('ad','bs') NOT NULL DEFAULT 'ad',
  `show_gpa` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `show_marks` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `show_grade` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `show_credit` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `pan_no` varchar(255) NOT NULL,
  `established_on` date DEFAULT NULL,
  `established_on_bs` varchar(255) NOT NULL,
  `fines` text NOT NULL,
  `zoom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sch_settings`
--

INSERT INTO `sch_settings` (`id`, `name`, `header_image`, `email`, `phone`, `address`, `lang_id`, `dise_code`, `date_format`, `currency`, `currency_symbol`, `is_rtl`, `timezone`, `session_id`, `start_month`, `image`, `theme`, `is_active`, `created_at`, `updated_at`, `cron_secret_key`, `fee_due_days`, `fee_gen_day`, `class_teacher`, `public_holiday`, `is_conventional`, `facebook_link`, `youtube_link`, `twitter_link`, `linkedin_link`, `tag_line`, `first_day`, `datechooser`, `show_gpa`, `show_marks`, `show_grade`, `show_credit`, `pan_no`, `established_on`, `established_on_bs`, `fines`, `zoom`) VALUES
(1, 'Your School Name', '', 'yourschoolemail@yoursite.com', 'Your School Phone', 'Your School Address', 4, '', 'm/d/Y', 'USD', '$', 'disabled', 'UTC', 14, '4', 'logo.png', 'default.jpg', 'no', '2018-07-12 05:44:48', '0000-00-00 00:00:00', '', 20, 1, 'no', '', 'no', '', '', '', '', '', '', 'ad', 1, 1, 1, 0, '', NULL, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `send_notification`
--

CREATE TABLE `send_notification` (
  `id` int(11) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `publish_date_bs` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `visible_student` varchar(10) NOT NULL DEFAULT 'no',
  `visible_staff` varchar(10) NOT NULL DEFAULT 'no',
  `visible_parent` varchar(10) NOT NULL DEFAULT 'no',
  `created_by` varchar(60) DEFAULT NULL,
  `created_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `session` varchar(60) DEFAULT NULL,
  `session_bs` varchar(60) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_date` date DEFAULT NULL,
  `start_date_bs` varchar(255) NOT NULL,
  `start_year_bs` smallint(5) UNSIGNED NOT NULL,
  `start_month_bs` tinyint(3) UNSIGNED NOT NULL,
  `start_day_bs` tinyint(3) UNSIGNED NOT NULL,
  `end_date` date DEFAULT NULL,
  `end_date_bs` varchar(255) NOT NULL,
  `end_year_bs` smallint(5) UNSIGNED NOT NULL,
  `end_month_bs` tinyint(3) UNSIGNED NOT NULL,
  `end_day_bs` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `session`, `session_bs`, `is_active`, `created_at`, `updated_at`, `start_date`, `start_date_bs`, `start_year_bs`, `start_month_bs`, `start_day_bs`, `end_date`, `end_date_bs`, `end_year_bs`, `end_month_bs`, `end_day_bs`) VALUES
(7, '2016-17', '2073-74', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(11, '2017-18', '2074-75', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(13, '2018-19', '2075-76', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(14, '2019-20', '2076-77', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(15, '2020-21', '2077-78', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(16, '2021-22', '2078-79', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(18, '2022-23', '2079-80', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(19, '2023-24', '2080-81', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(20, '2024-25', '2081-82', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(21, '2025-26', '2082-83', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(22, '2026-27', '2083-84', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(23, '2027-28', '2084-85', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(24, '2028-29', '2085-86', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0),
(25, '2029-30', '2086-87', 'no', '2019-12-04 06:32:26', '0000-00-00 00:00:00', NULL, '', 0, 0, 0, NULL, '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sms_config`
--

CREATE TABLE `sms_config` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `api_id` varchar(100) NOT NULL,
  `authkey` varchar(100) NOT NULL,
  `senderid` varchar(100) NOT NULL,
  `contact` text DEFAULT NULL,
  `username` varchar(150) DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'disabled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE `source` (
  `id` int(11) NOT NULL,
  `source` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(200) NOT NULL,
  `department` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `qualification` varchar(200) NOT NULL,
  `work_exp` varchar(200) NOT NULL,
  `stn_title` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `surname` varchar(200) NOT NULL,
  `father_name` varchar(200) NOT NULL,
  `mother_name` varchar(200) NOT NULL,
  `contact_no` varchar(200) NOT NULL,
  `emergency_contact_no` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  `dob_bs` varchar(255) NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `date_of_joining` date NOT NULL,
  `date_of_joining_bs` varchar(255) NOT NULL,
  `date_of_leaving` date NOT NULL,
  `date_of_leaving_bs` varchar(255) NOT NULL,
  `local_address` varchar(300) NOT NULL,
  `permanent_address` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `account_title` varchar(200) NOT NULL,
  `bank_account_no` varchar(200) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `pan_no` varchar(200) NOT NULL,
  `bank_branch` varchar(100) NOT NULL,
  `payscale` varchar(200) NOT NULL,
  `basic_salary` varchar(200) NOT NULL,
  `epf_no` varchar(200) NOT NULL,
  `cit_no` varchar(200) NOT NULL,
  `contract_type` varchar(100) NOT NULL,
  `shift` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `linkedin` varchar(200) NOT NULL,
  `instagram` varchar(200) NOT NULL,
  `resume` varchar(200) NOT NULL,
  `joining_letter` varchar(200) NOT NULL,
  `resignation_letter` varchar(200) NOT NULL,
  `other_document_name` varchar(200) NOT NULL,
  `other_document_file` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `verification_code` varchar(100) NOT NULL,
  `pf` varchar(255) NOT NULL,
  `tds` varchar(255) NOT NULL,
  `bonus` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `employee_id`, `department`, `designation`, `qualification`, `work_exp`, `stn_title`, `name`, `surname`, `father_name`, `mother_name`, `contact_no`, `emergency_contact_no`, `email`, `dob`, `dob_bs`, `marital_status`, `date_of_joining`, `date_of_joining_bs`, `date_of_leaving`, `date_of_leaving_bs`, `local_address`, `permanent_address`, `note`, `image`, `password`, `gender`, `account_title`, `bank_account_no`, `bank_name`, `pan_no`, `bank_branch`, `payscale`, `basic_salary`, `epf_no`, `cit_no`, `contract_type`, `shift`, `location`, `facebook`, `twitter`, `linkedin`, `instagram`, `resume`, `joining_letter`, `resignation_letter`, `other_document_name`, `other_document_file`, `user_id`, `is_active`, `verification_code`, `pf`, `tds`, `bonus`) VALUES
(1, '', '', '', '', '', '', 'Super Admin', '', '', '', '', '', 'testuser@yopmail.com', '0000-00-00', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '$2y$10$/RX1B2kf7.KF3bZmy59Z4.4TvYWINiBa1F5ce4WJXK66j1D/UE7me', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 1, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `staff_attendance`
--

CREATE TABLE `staff_attendance` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `year_bs` smallint(5) UNSIGNED NOT NULL,
  `month_bs` tinyint(3) UNSIGNED NOT NULL,
  `day_bs` tinyint(3) UNSIGNED NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staff_attendance_type_id` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_attendance_type`
--

CREATE TABLE `staff_attendance_type` (
  `id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `key_value` varchar(200) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff_attendance_type`
--

INSERT INTO `staff_attendance_type` (`id`, `type`, `key_value`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Present', '<b class=\"text text-success\">P</b>', 'yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Late', '<b class=\"text text-warning\">L</b>', 'yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Absent', '<b class=\"text text-danger\">A</b>', 'yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Half Day', '<b class=\"text text-warning\">F</b>', 'yes', '2018-05-07 01:56:16', '0000-00-00 00:00:00'),
(5, 'Holiday', 'H', 'yes', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff_designation`
--

CREATE TABLE `staff_designation` (
  `id` int(11) NOT NULL,
  `designation` varchar(200) NOT NULL,
  `is_active` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_id_card`
--

CREATE TABLE `staff_id_card` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `school_address` varchar(500) NOT NULL,
  `background` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `principal_sign` varchar(100) NOT NULL,
  `verifier_sign` varchar(100) NOT NULL,
  `header_color` varchar(100) NOT NULL,
  `footer_color` varchar(100) NOT NULL,
  `validity` varchar(255) NOT NULL,
  `layout` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `enable_employee_id` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_department` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `enable_designation` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_email` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_date_of_joining` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `enable_permanent_address` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_dob` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_contact_no` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `status` tinyint(1) NOT NULL COMMENT '0=disable,1=enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_leave_details`
--

CREATE TABLE `staff_leave_details` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `alloted_leave` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_leave_request`
--

CREATE TABLE `staff_leave_request` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `leave_from` date NOT NULL,
  `leave_to` date NOT NULL,
  `leave_days` int(11) NOT NULL,
  `employee_remark` varchar(200) NOT NULL,
  `admin_remark` varchar(200) NOT NULL,
  `status` varchar(100) NOT NULL,
  `applied_by` varchar(200) NOT NULL,
  `document_file` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `leave_from_bs` varchar(255) NOT NULL,
  `leave_to_bs` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_payroll`
--

CREATE TABLE `staff_payroll` (
  `id` int(11) NOT NULL,
  `basic_salary` int(11) NOT NULL,
  `pay_scale` varchar(200) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `is_active` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_payslip`
--

CREATE TABLE `staff_payslip` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `basic` int(11) NOT NULL,
  `total_allowance` int(11) NOT NULL,
  `total_deduction` int(11) NOT NULL,
  `leave_deduction` int(11) NOT NULL,
  `tax` varchar(200) NOT NULL,
  `net_salary` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `month` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `payment_mode` varchar(200) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_date_bs` varchar(255) NOT NULL,
  `remark` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_roles`
--

CREATE TABLE `staff_roles` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff_roles`
--

INSERT INTO `staff_roles` (`id`, `role_id`, `staff_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 0, '2021-09-17 03:35:40', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff_timeline`
--

CREATE TABLE `staff_timeline` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `timeline_date` date NOT NULL,
  `description` varchar(300) NOT NULL,
  `document` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `admission_no` varchar(100) DEFAULT NULL,
  `roll_no` smallint(5) UNSIGNED NOT NULL,
  `admission_date` date DEFAULT NULL,
  `admission_date_bs` varchar(255) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `rte` varchar(20) NOT NULL DEFAULT 'No',
  `image` varchar(100) DEFAULT NULL,
  `mobileno` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `cast` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `dob_bs` varchar(255) NOT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `category_id` varchar(100) DEFAULT NULL,
  `route_id` int(11) NOT NULL,
  `school_house_id` int(11) NOT NULL,
  `blood_group` varchar(200) NOT NULL,
  `vehroute_id` int(11) NOT NULL,
  `route_stop_id` int(11) DEFAULT NULL,
  `hostel_room_id` int(11) NOT NULL,
  `adhar_no` varchar(100) DEFAULT NULL,
  `samagra_id` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `ifsc_code` varchar(100) DEFAULT NULL,
  `guardian_is` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_phone` varchar(100) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_phone` varchar(100) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_relation` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(100) DEFAULT NULL,
  `guardian_occupation` varchar(150) NOT NULL,
  `guardian_address` text DEFAULT NULL,
  `guardian_email` varchar(100) NOT NULL,
  `father_pic` varchar(200) NOT NULL,
  `mother_pic` varchar(200) NOT NULL,
  `guardian_pic` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `previous_school` text DEFAULT NULL,
  `height` varchar(100) NOT NULL,
  `weight` varchar(100) NOT NULL,
  `measurement_date` date NOT NULL,
  `measurement_date_bs` varchar(255) NOT NULL,
  `leave_date` date DEFAULT NULL,
  `leave_date_bs` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `disable_at` date NOT NULL,
  `note` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_attendences`
--

CREATE TABLE `student_attendences` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `year_bs` smallint(5) UNSIGNED NOT NULL,
  `month_bs` tinyint(3) UNSIGNED NOT NULL,
  `day_bs` tinyint(3) UNSIGNED NOT NULL,
  `attendence_type_id` int(11) DEFAULT NULL,
  `remark` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_doc`
--

CREATE TABLE `student_doc` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `doc` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees`
--

CREATE TABLE `student_fees` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `feemaster_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `amount_discount` float(10,2) NOT NULL,
  `amount_fine` float(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `date` date DEFAULT '0000-00-00',
  `payment_mode` varchar(50) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_deposite`
--

CREATE TABLE `student_fees_deposite` (
  `id` int(11) NOT NULL,
  `student_fees_master_id` int(11) DEFAULT NULL,
  `fee_groups_feetype_id` int(11) DEFAULT NULL,
  `amount_detail` text DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_discounts`
--

CREATE TABLE `student_fees_discounts` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `fees_discount_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'assigned',
  `payment_id` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_master`
--

CREATE TABLE `student_fees_master` (
  `id` int(11) NOT NULL,
  `is_system` int(1) NOT NULL DEFAULT 0,
  `student_session_id` int(11) DEFAULT NULL,
  `fee_session_group_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT 0.00,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees_payment_history`
--

CREATE TABLE `student_fees_payment_history` (
  `id` int(11) NOT NULL,
  `student_session_fees_id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `fine_amount` decimal(10,2) NOT NULL,
  `payment_detail` text NOT NULL,
  `payment_date` date NOT NULL,
  `payment_year_bs` smallint(6) NOT NULL,
  `payment_month_bs` tinyint(4) NOT NULL,
  `payment_day_bs` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_fee_advance`
--

CREATE TABLE `student_fee_advance` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `student_session_id` int(11) UNSIGNED DEFAULT NULL,
  `session_id` int(11) UNSIGNED DEFAULT NULL,
  `advance_amount` decimal(8,2) NOT NULL,
  `advance_date` datetime NOT NULL,
  `remarks` text NOT NULL,
  `extra_data` text NOT NULL,
  `student_session_fees_id` int(10) UNSIGNED DEFAULT NULL,
  `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_leaves`
--

CREATE TABLE `student_leaves` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `student_session_id` int(11) UNSIGNED NOT NULL,
  `class_teacher_id` int(11) UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `applied_on` datetime NOT NULL,
  `from_date_bs` varchar(255) NOT NULL,
  `to_date_bs` varchar(255) NOT NULL,
  `applied_on_bs` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_ranks`
--

CREATE TABLE `student_ranks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `student_session_id` int(11) UNSIGNED NOT NULL,
  `class_id` int(11) UNSIGNED NOT NULL,
  `section_id` int(11) UNSIGNED NOT NULL,
  `exam_id` int(11) UNSIGNED NOT NULL,
  `exam_result_publish_id` int(11) UNSIGNED NOT NULL,
  `final_percent` decimal(10,2) NOT NULL,
  `final_result` decimal(10,2) NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_session`
--

CREATE TABLE `student_session` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `route_id` int(11) NOT NULL,
  `hostel_room_id` int(11) NOT NULL,
  `vehroute_id` int(10) DEFAULT NULL,
  `route_stop_id` int(11) DEFAULT NULL,
  `transport_fees` float(10,2) NOT NULL DEFAULT 0.00,
  `fees_discount` float(10,2) NOT NULL DEFAULT 0.00,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_session_fees`
--

CREATE TABLE `student_session_fees` (
  `id` int(10) UNSIGNED NOT NULL,
  `session_id` int(11) NOT NULL,
  `student_session_id` int(11) NOT NULL,
  `student_fees_master_id` int(11) NOT NULL,
  `fee_month` tinyint(3) UNSIGNED DEFAULT NULL,
  `fee_year` smallint(5) UNSIGNED NOT NULL,
  `fee_data` text NOT NULL,
  `discount_data` text NOT NULL,
  `total_fee` decimal(10,2) NOT NULL,
  `total_discount` decimal(10,2) NOT NULL,
  `payable_amount` decimal(10,2) NOT NULL,
  `due_amount` decimal(10,2) NOT NULL,
  `prev_due_amount` decimal(10,2) NOT NULL,
  `previous_dues` mediumtext DEFAULT NULL,
  `is_paid` tinyint(4) NOT NULL DEFAULT 0,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT NULL,
  `fine_details` text DEFAULT NULL,
  `payment_detail` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `due_date_bs` varchar(255) DEFAULT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `receipt_id` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_year_bs` smallint(5) UNSIGNED NOT NULL,
  `payment_month_bs` tinyint(3) UNSIGNED NOT NULL,
  `payment_day_bs` tinyint(3) UNSIGNED NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_sibling`
--

CREATE TABLE `student_sibling` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `sibling_student_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_timeline`
--

CREATE TABLE `student_timeline` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `timeline_date` date NOT NULL,
  `description` varchar(200) NOT NULL,
  `document` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_transport_fees`
--

CREATE TABLE `student_transport_fees` (
  `id` int(11) NOT NULL,
  `student_session_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `amount_discount` float(10,2) NOT NULL,
  `amount_fine` float(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `date` date DEFAULT '0000-00-00',
  `is_active` varchar(255) DEFAULT 'no',
  `payment_mode` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_exam_publish`
--

CREATE TABLE `tbl_exam_publish` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_exam_result_publish`
--

CREATE TABLE `tbl_exam_result_publish` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_grade`
--

CREATE TABLE `tbl_grade` (
  `id` int(11) NOT NULL,
  `grade_letter` varchar(50) NOT NULL,
  `credit_point` float(10,2) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_neema_user`
--

CREATE TABLE `tbl_neema_user` (
  `id` int(11) NOT NULL,
  `institute_code` varchar(250) NOT NULL,
  `institute_userid` int(11) NOT NULL,
  `registration_code` varchar(250) NOT NULL,
  `institute_roleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `id` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `credit_hour` int(11) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE `timetables` (
  `id` int(11) NOT NULL,
  `teacher_subject_id` int(20) DEFAULT NULL,
  `day_name` varchar(50) DEFAULT NULL,
  `start_time` varchar(50) DEFAULT NULL,
  `end_time` varchar(50) DEFAULT NULL,
  `room_no` varchar(50) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transport_route`
--

CREATE TABLE `transport_route` (
  `id` int(11) NOT NULL,
  `route_title` varchar(100) DEFAULT NULL,
  `no_of_vehicle` int(11) DEFAULT NULL,
  `fare` float(10,2) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `user` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `ipaddress` varchar(100) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `login_datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `user`, `role`, `ipaddress`, `user_agent`, `login_datetime`) VALUES
(1, 'superadmin@yopmail.com', '', '172.18.29.105', 'Chrome 73.0.3683.86, Windows 10', '2019-04-04 09:54:25'),
(2, 'superadmin@yopmail.com', '', '172.18.29.105', 'Chrome 73.0.3683.86, Windows 10', '2019-04-04 09:56:23'),
(3, 'superadmin@yopmail.com', '', '172.18.29.105', 'Chrome 73.0.3683.86, Windows 10', '2019-04-04 09:57:27'),
(4, 'superadmin@yopmail.com', '', '172.18.29.105', 'Firefox 66.0, Windows 10', '2019-04-04 09:58:30'),
(5, 'superadmin@yopmail.com', '', '172.18.29.105', 'Firefox 66.0, Windows 10', '2019-04-04 10:05:01'),
(6, 'testuser@yopmail.com', 'Super Admin', '::1', 'Chrome 93.0.4577.82, Windows 10', '2021-09-17 05:34:19'),
(7, 'testuser@yopmail.com', 'Super Admin', '::1', 'Chrome 93.0.4577.82, Windows 10', '2021-09-17 05:51:30'),
(8, 'testuser@yopmail.com', 'Super Admin', '::1', 'Chrome 93.0.4577.82, Windows 10', '2021-09-17 06:48:26'),
(9, 'testuser@yopmail.com', 'Super Admin', '::1', 'Chrome 93.0.4577.82, Windows 10', '2021-09-17 06:54:29'),
(10, 'testuser@yopmail.com', 'Super Admin', '::1', 'Chrome 93.0.4577.82, Windows 10', '2021-09-17 06:56:30'),
(11, 'testuser@yopmail.com', 'Super Admin', '::1', 'Chrome 93.0.4577.82, Windows 10', '2021-09-17 07:12:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `childs` text NOT NULL,
  `role` varchar(30) NOT NULL,
  `verification_code` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(10) UNSIGNED NOT NULL,
  `vehicle_no` varchar(20) DEFAULT NULL,
  `vehicle_model` varchar(100) NOT NULL DEFAULT 'None',
  `manufacture_year` varchar(4) DEFAULT NULL,
  `driver_name` varchar(50) DEFAULT NULL,
  `driver_licence` varchar(50) NOT NULL DEFAULT 'None',
  `driver_contact` varchar(20) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_routes`
--

CREATE TABLE `vehicle_routes` (
  `id` int(11) UNSIGNED NOT NULL,
  `route_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitors_book`
--

CREATE TABLE `visitors_book` (
  `id` int(11) NOT NULL,
  `source` varchar(100) DEFAULT NULL,
  `purpose` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(12) NOT NULL,
  `id_proof` varchar(50) NOT NULL,
  `no_of_pepple` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `in_time` varchar(20) NOT NULL,
  `out_time` varchar(20) NOT NULL,
  `note` mediumtext NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visitors_purpose`
--

CREATE TABLE `visitors_purpose` (
  `id` int(11) NOT NULL,
  `visitors_purpose` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `weekdays`
--

CREATE TABLE `weekdays` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `dayname` varchar(10) NOT NULL,
  `daykey` varchar(10) NOT NULL,
  `sunday` tinyint(3) UNSIGNED NOT NULL,
  `monday` tinyint(3) UNSIGNED NOT NULL,
  `tuesday` tinyint(3) UNSIGNED NOT NULL,
  `wednesday` tinyint(3) UNSIGNED NOT NULL,
  `thursday` tinyint(3) UNSIGNED NOT NULL,
  `friday` tinyint(3) UNSIGNED NOT NULL,
  `saturday` tinyint(3) UNSIGNED NOT NULL,
  `is_holiday` tinyint(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `weekdays`
--

INSERT INTO `weekdays` (`id`, `dayname`, `daykey`, `sunday`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `is_holiday`) VALUES
(1, 'Sunday', 'sunday', 1, 7, 6, 5, 4, 3, 2, 0),
(2, 'Monday', 'monday', 2, 1, 7, 6, 5, 4, 3, 0),
(3, 'Tuesday', 'tuesday', 3, 2, 1, 7, 6, 5, 4, 0),
(4, 'Wednesday', 'wednesday', 4, 3, 2, 1, 7, 6, 5, 0),
(5, 'Thursday', 'thursday', 5, 4, 3, 2, 1, 7, 6, 0),
(6, 'Friday', 'friday', 6, 5, 4, 3, 2, 1, 7, 0),
(7, 'Saturday', 'saturday', 7, 6, 5, 4, 3, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `zoom_meetings`
--

CREATE TABLE `zoom_meetings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `meeting_number` varchar(255) NOT NULL,
  `meeting_password` varchar(255) NOT NULL,
  `meeting_url` varchar(255) NOT NULL,
  `start_url` text NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `starts_on` datetime NOT NULL,
  `duration` tinyint(3) UNSIGNED NOT NULL,
  `created_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zoom_token`
--

CREATE TABLE `zoom_token` (
  `id` int(11) NOT NULL,
  `access_token` text NOT NULL,
  `refresh_token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acc_advances`
--
ALTER TABLE `acc_advances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_chart_of_accounts_detail`
--
ALTER TABLE `acc_chart_of_accounts_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_coa_categories`
--
ALTER TABLE `acc_coa_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tag_idx` (`published`),
  ADD KEY `idx_left_right` (`lft`,`rgt`),
  ADD KEY `idx_alias` (`slug`(100));

--
-- Indexes for table `acc_financial_year`
--
ALTER TABLE `acc_financial_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_general_settings`
--
ALTER TABLE `acc_general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_invoice`
--
ALTER TABLE `acc_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_invoice_entry`
--
ALTER TABLE `acc_invoice_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_journal`
--
ALTER TABLE `acc_journal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_journal_entry`
--
ALTER TABLE `acc_journal_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_opening_balances`
--
ALTER TABLE `acc_opening_balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_payment`
--
ALTER TABLE `acc_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_payment_details`
--
ALTER TABLE `acc_payment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_personnel`
--
ALTER TABLE `acc_personnel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_receipt`
--
ALTER TABLE `acc_receipt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_receipt_details`
--
ALTER TABLE `acc_receipt_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_transaction_logs`
--
ALTER TABLE `acc_transaction_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admit_card`
--
ALTER TABLE `admit_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_auths`
--
ALTER TABLE `api_auths`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_idx` (`uuid`);

--
-- Indexes for table `attendence_type`
--
ALTER TABLE `attendence_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_issues`
--
ALTER TABLE `book_issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `class_teacher`
--
ALTER TABLE `class_teacher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint_type`
--
ALTER TABLE `complaint_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_for`
--
ALTER TABLE `content_for`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cron_fee`
--
ALTER TABLE `cron_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatch_receive`
--
ALTER TABLE `dispatch_receive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_config`
--
ALTER TABLE `email_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquiry_type`
--
ALTER TABLE `enquiry_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_schedule_id` (`exam_schedule_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `exam_schedules`
--
ALTER TABLE `exam_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_subject_id` (`teacher_subject_id`);

--
-- Indexes for table `exam_weightage`
--
ALTER TABLE `exam_weightage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_head`
--
ALTER TABLE `expense_head`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feecategory`
--
ALTER TABLE `feecategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feemasters`
--
ALTER TABLE `feemasters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees_discounts`
--
ALTER TABLE `fees_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `feetype`
--
ALTER TABLE `feetype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_fine_payments`
--
ALTER TABLE `fee_fine_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_groups`
--
ALTER TABLE `fee_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_groups_feetype`
--
ALTER TABLE `fee_groups_feetype`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_session_group_id` (`fee_session_group_id`),
  ADD KEY `fee_groups_id` (`fee_groups_id`),
  ADD KEY `feetype_id` (`feetype_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `fee_receipt_no`
--
ALTER TABLE `fee_receipt_no`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_session_groups`
--
ALTER TABLE `fee_session_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_groups_id` (`fee_groups_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `follow_up`
--
ALTER TABLE `follow_up`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_cms_media_gallery`
--
ALTER TABLE `front_cms_media_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_cms_menus`
--
ALTER TABLE `front_cms_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_cms_menu_items`
--
ALTER TABLE `front_cms_menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_cms_pages`
--
ALTER TABLE `front_cms_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_cms_page_contents`
--
ALTER TABLE `front_cms_page_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `front_cms_programs`
--
ALTER TABLE `front_cms_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_cms_program_photos`
--
ALTER TABLE `front_cms_program_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `front_cms_settings`
--
ALTER TABLE `front_cms_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_calls`
--
ALTER TABLE `general_calls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homework_evaluation`
--
ALTER TABLE `homework_evaluation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hostel`
--
ALTER TABLE `hostel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hostel_rooms`
--
ALTER TABLE `hostel_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `id_card`
--
ALTER TABLE `id_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_head`
--
ALTER TABLE `income_head`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_category`
--
ALTER TABLE `item_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_issue`
--
ALTER TABLE `item_issue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `item_category_id` (`item_category_id`);

--
-- Indexes for table `item_stock`
--
ALTER TABLE `item_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `item_store`
--
ALTER TABLE `item_store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_supplier`
--
ALTER TABLE `item_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`);

--
-- Indexes for table `libarary_members`
--
ALTER TABLE `libarary_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_roles`
--
ALTER TABLE `notification_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `send_notification_id` (`send_notification_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `notification_setting`
--
ALTER TABLE `notification_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_allowance`
--
ALTER TABLE `payslip_allowance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_category`
--
ALTER TABLE `permission_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_group`
--
ALTER TABLE `permission_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prabhupay_app_payment`
--
ALTER TABLE `prabhupay_app_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prabhupay_init`
--
ALTER TABLE `prabhupay_init`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `read_notification`
--
ALTER TABLE `read_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reference`
--
ALTER TABLE `reference`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `route_stops`
--
ALTER TABLE `route_stops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_houses`
--
ALTER TABLE `school_houses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sch_settings`
--
ALTER TABLE `sch_settings`
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `send_notification`
--
ALTER TABLE `send_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_config`
--
ALTER TABLE `sms_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `source`
--
ALTER TABLE `source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `staff_attendance`
--
ALTER TABLE `staff_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_attendance_type`
--
ALTER TABLE `staff_attendance_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_designation`
--
ALTER TABLE `staff_designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_id_card`
--
ALTER TABLE `staff_id_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_leave_details`
--
ALTER TABLE `staff_leave_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_leave_request`
--
ALTER TABLE `staff_leave_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_payroll`
--
ALTER TABLE `staff_payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_payslip`
--
ALTER TABLE `staff_payslip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_roles`
--
ALTER TABLE `staff_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `staff_timeline`
--
ALTER TABLE `staff_timeline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_attendences`
--
ALTER TABLE `student_attendences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_session_id` (`student_session_id`),
  ADD KEY `attendence_type_id` (`attendence_type_id`);

--
-- Indexes for table `student_doc`
--
ALTER TABLE `student_doc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_fees`
--
ALTER TABLE `student_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_fees_deposite`
--
ALTER TABLE `student_fees_deposite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_fees_master_id` (`student_fees_master_id`),
  ADD KEY `fee_groups_feetype_id` (`fee_groups_feetype_id`);

--
-- Indexes for table `student_fees_discounts`
--
ALTER TABLE `student_fees_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_session_id` (`student_session_id`),
  ADD KEY `fees_discount_id` (`fees_discount_id`);

--
-- Indexes for table `student_fees_master`
--
ALTER TABLE `student_fees_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_session_id` (`student_session_id`),
  ADD KEY `fee_session_group_id` (`fee_session_group_id`);

--
-- Indexes for table `student_fees_payment_history`
--
ALTER TABLE `student_fees_payment_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_fee_advance`
--
ALTER TABLE `student_fee_advance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_leaves`
--
ALTER TABLE `student_leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_ranks`
--
ALTER TABLE `student_ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_session`
--
ALTER TABLE `student_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_session_fees`
--
ALTER TABLE `student_session_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_sibling`
--
ALTER TABLE `student_sibling`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_timeline`
--
ALTER TABLE `student_timeline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_transport_fees`
--
ALTER TABLE `student_transport_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_exam_publish`
--
ALTER TABLE `tbl_exam_publish`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_exam_result_publish`
--
ALTER TABLE `tbl_exam_result_publish`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_grade`
--
ALTER TABLE `tbl_grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_neema_user`
--
ALTER TABLE `tbl_neema_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_section_id` (`class_section_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transport_route`
--
ALTER TABLE `transport_route`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_routes`
--
ALTER TABLE `vehicle_routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitors_book`
--
ALTER TABLE `visitors_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitors_purpose`
--
ALTER TABLE `visitors_purpose`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekdays`
--
ALTER TABLE `weekdays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zoom_meetings`
--
ALTER TABLE `zoom_meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zoom_token`
--
ALTER TABLE `zoom_token`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acc_advances`
--
ALTER TABLE `acc_advances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_chart_of_accounts_detail`
--
ALTER TABLE `acc_chart_of_accounts_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `acc_coa_categories`
--
ALTER TABLE `acc_coa_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `acc_financial_year`
--
ALTER TABLE `acc_financial_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `acc_general_settings`
--
ALTER TABLE `acc_general_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `acc_invoice`
--
ALTER TABLE `acc_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_invoice_entry`
--
ALTER TABLE `acc_invoice_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_journal`
--
ALTER TABLE `acc_journal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_journal_entry`
--
ALTER TABLE `acc_journal_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_opening_balances`
--
ALTER TABLE `acc_opening_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_payment`
--
ALTER TABLE `acc_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_payment_details`
--
ALTER TABLE `acc_payment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_personnel`
--
ALTER TABLE `acc_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_receipt`
--
ALTER TABLE `acc_receipt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_receipt_details`
--
ALTER TABLE `acc_receipt_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_transaction_logs`
--
ALTER TABLE `acc_transaction_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admit_card`
--
ALTER TABLE `admit_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_auths`
--
ALTER TABLE `api_auths`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendence_type`
--
ALTER TABLE `attendence_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_issues`
--
ALTER TABLE `book_issues`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_sections`
--
ALTER TABLE `class_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_teacher`
--
ALTER TABLE `class_teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint_type`
--
ALTER TABLE `complaint_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `content_for`
--
ALTER TABLE `content_for`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_fee`
--
ALTER TABLE `cron_fee`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispatch_receive`
--
ALTER TABLE `dispatch_receive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_config`
--
ALTER TABLE `email_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enquiry_type`
--
ALTER TABLE `enquiry_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_schedules`
--
ALTER TABLE `exam_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_weightage`
--
ALTER TABLE `exam_weightage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_head`
--
ALTER TABLE `expense_head`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feecategory`
--
ALTER TABLE `feecategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feemasters`
--
ALTER TABLE `feemasters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees_discounts`
--
ALTER TABLE `fees_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feetype`
--
ALTER TABLE `feetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fee_fine_payments`
--
ALTER TABLE `fee_fine_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_groups`
--
ALTER TABLE `fee_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_groups_feetype`
--
ALTER TABLE `fee_groups_feetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_receipt_no`
--
ALTER TABLE `fee_receipt_no`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_session_groups`
--
ALTER TABLE `fee_session_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_up`
--
ALTER TABLE `follow_up`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_cms_media_gallery`
--
ALTER TABLE `front_cms_media_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_cms_menus`
--
ALTER TABLE `front_cms_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `front_cms_menu_items`
--
ALTER TABLE `front_cms_menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_cms_pages`
--
ALTER TABLE `front_cms_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `front_cms_page_contents`
--
ALTER TABLE `front_cms_page_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_cms_programs`
--
ALTER TABLE `front_cms_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_cms_program_photos`
--
ALTER TABLE `front_cms_program_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_cms_settings`
--
ALTER TABLE `front_cms_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_calls`
--
ALTER TABLE `general_calls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `homework`
--
ALTER TABLE `homework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `homework_evaluation`
--
ALTER TABLE `homework_evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hostel`
--
ALTER TABLE `hostel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hostel_rooms`
--
ALTER TABLE `hostel_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `id_card`
--
ALTER TABLE `id_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_head`
--
ALTER TABLE `income_head`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_category`
--
ALTER TABLE `item_category`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_issue`
--
ALTER TABLE `item_issue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_stock`
--
ALTER TABLE `item_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_store`
--
ALTER TABLE `item_store`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_supplier`
--
ALTER TABLE `item_supplier`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `libarary_members`
--
ALTER TABLE `libarary_members`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_roles`
--
ALTER TABLE `notification_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_setting`
--
ALTER TABLE `notification_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payslip_allowance`
--
ALTER TABLE `payslip_allowance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission_category`
--
ALTER TABLE `permission_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `permission_group`
--
ALTER TABLE `permission_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `prabhupay_app_payment`
--
ALTER TABLE `prabhupay_app_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prabhupay_init`
--
ALTER TABLE `prabhupay_init`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `read_notification`
--
ALTER TABLE `read_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference`
--
ALTER TABLE `reference`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=639;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `route_stops`
--
ALTER TABLE `route_stops`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_houses`
--
ALTER TABLE `school_houses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `send_notification`
--
ALTER TABLE `send_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sms_config`
--
ALTER TABLE `sms_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `source`
--
ALTER TABLE `source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff_attendance`
--
ALTER TABLE `staff_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_attendance_type`
--
ALTER TABLE `staff_attendance_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff_designation`
--
ALTER TABLE `staff_designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_id_card`
--
ALTER TABLE `staff_id_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_leave_details`
--
ALTER TABLE `staff_leave_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_leave_request`
--
ALTER TABLE `staff_leave_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_payroll`
--
ALTER TABLE `staff_payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_payslip`
--
ALTER TABLE `staff_payslip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_roles`
--
ALTER TABLE `staff_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff_timeline`
--
ALTER TABLE `staff_timeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_attendences`
--
ALTER TABLE `student_attendences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_doc`
--
ALTER TABLE `student_doc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fees`
--
ALTER TABLE `student_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fees_deposite`
--
ALTER TABLE `student_fees_deposite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fees_discounts`
--
ALTER TABLE `student_fees_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fees_master`
--
ALTER TABLE `student_fees_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fees_payment_history`
--
ALTER TABLE `student_fees_payment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_fee_advance`
--
ALTER TABLE `student_fee_advance`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_leaves`
--
ALTER TABLE `student_leaves`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_ranks`
--
ALTER TABLE `student_ranks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_session`
--
ALTER TABLE `student_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_session_fees`
--
ALTER TABLE `student_session_fees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_sibling`
--
ALTER TABLE `student_sibling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_timeline`
--
ALTER TABLE `student_timeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_transport_fees`
--
ALTER TABLE `student_transport_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_exam_publish`
--
ALTER TABLE `tbl_exam_publish`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_exam_result_publish`
--
ALTER TABLE `tbl_exam_result_publish`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_grade`
--
ALTER TABLE `tbl_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_neema_user`
--
ALTER TABLE `tbl_neema_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timetables`
--
ALTER TABLE `timetables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transport_route`
--
ALTER TABLE `transport_route`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicle_routes`
--
ALTER TABLE `vehicle_routes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitors_book`
--
ALTER TABLE `visitors_book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitors_purpose`
--
ALTER TABLE `visitors_purpose`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weekdays`
--
ALTER TABLE `weekdays`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `zoom_meetings`
--
ALTER TABLE `zoom_meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zoom_token`
--
ALTER TABLE `zoom_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `content_for`
--
ALTER TABLE `content_for`
  ADD CONSTRAINT `content_for_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `content_for_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fees_discounts`
--
ALTER TABLE `fees_discounts`
  ADD CONSTRAINT `fees_discounts_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_groups_feetype`
--
ALTER TABLE `fee_groups_feetype`
  ADD CONSTRAINT `fee_groups_feetype_ibfk_1` FOREIGN KEY (`fee_session_group_id`) REFERENCES `fee_session_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_groups_feetype_ibfk_2` FOREIGN KEY (`fee_groups_id`) REFERENCES `fee_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_groups_feetype_ibfk_3` FOREIGN KEY (`feetype_id`) REFERENCES `feetype` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_groups_feetype_ibfk_4` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_session_groups`
--
ALTER TABLE `fee_session_groups`
  ADD CONSTRAINT `fee_session_groups_ibfk_1` FOREIGN KEY (`fee_groups_id`) REFERENCES `fee_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_session_groups_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `front_cms_page_contents`
--
ALTER TABLE `front_cms_page_contents`
  ADD CONSTRAINT `front_cms_page_contents_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `front_cms_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `front_cms_program_photos`
--
ALTER TABLE `front_cms_program_photos`
  ADD CONSTRAINT `front_cms_program_photos_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `front_cms_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_issue`
--
ALTER TABLE `item_issue`
  ADD CONSTRAINT `item_issue_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_issue_ibfk_2` FOREIGN KEY (`item_category_id`) REFERENCES `item_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_stock`
--
ALTER TABLE `item_stock`
  ADD CONSTRAINT `item_stock_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_stock_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `item_supplier` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_stock_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `item_store` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_roles`
--
ALTER TABLE `notification_roles`
  ADD CONSTRAINT `notification_roles_ibfk_1` FOREIGN KEY (`send_notification_id`) REFERENCES `send_notification` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
