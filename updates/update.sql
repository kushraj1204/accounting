INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `system`, `created_at`) VALUES ('Account', 'account', '1', '0', CURRENT_TIMESTAMP);

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) VALUES ('23', 'Account', 'account', '1', '1', '1', '1', CURRENT_TIMESTAMP);

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account General Setting', 'account_general_setting', '1', '1', '1', '1'); 

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Chart of Accounts', 'account_chart_of_accounts', '1', '1', '1', '1'); 

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Categories', 'account_categories', '1', '1', '1', '1'); 

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Customer/Supplier', 'account_personnel', '1', '1', '1', '1');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Invoice', 'account_invoice', '1', '1', '1', '1');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Journal', 'account_journal', '1', '1', '1', '1');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Payments', 'account_payments', '1', '1', '1', '1');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Receipts', 'account_receipts', '1', '1', '1', '1');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Personnel Ledger', 'personnel_ledger', '1', '1', '1', '1');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Opening Balances', 'account_opening_balances', '1', '1', '1', '1');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`) VALUES ('23', 'Account Income and Expense Chart', 'income_and_expense_yearly_chart', '1', '0', '0', '0');


DROP TABLE IF EXISTS `acc_coa_categories`;
CREATE TABLE `acc_coa_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `financial_year` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag_idx` (`published`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`slug`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `acc_coa_categories` ADD COLUMN `deletable` TINYINT(1) DEFAULT 1 NOT NULL AFTER `financial_year`;

--
-- Data for the table `acc_coa_categories`
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
(18, 15, 8, 9, 4, 'E-wallets', 'e-wallets', 1, 2, '2020-02-27 12:19:07', '2020-02-27 12:22:17', 0, 0);
(19, 5, 36, 41, 2, 'Profit', 'profit', 1, 2, '2020-10-07 16:49:41', '2020-10-07 16:50:34', 0, 0),
(20, 19, 37, 40, 3, 'Profit', 'profit-1', 1, 2, '2020-10-07 16:49:51', '2020-10-07 16:50:29', 0, 0),
(21, 20, 38, 39, 4, 'Profit', 'profit-2', 1, 2, '2020-10-07 16:50:01', '2020-10-07 16:50:26', 0, 0),
(22, 10, 11, 14, 3, 'Current Assets', 'current-assets-1', 1, 1, '2021-03-10 10:05:00', '2021-03-10 10:13:21', 0, 0),
(23, 22, 12, 13, 4, 'Current Assets', 'current-assets-2', 1, 1, '2021-03-10 10:13:21', '2021-03-10 10:13:21', 0, 0),
(24, 11, 19, 22, 3, 'Current Liabilities', 'current-liabilities-1', 1, 1, '2021-03-10 10:13:39', '2021-03-10 10:13:56', 0, 0),
(25, 24, 20, 21, 4, 'Current Liabilities', 'current-liabilities-2', 1, 1, '2021-03-10 10:13:56', '2021-03-10 10:13:56', 0, 0);




DROP TABLE IF EXISTS `acc_personnel`;
CREATE TABLE `acc_personnel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `acc_personnel` ADD COLUMN `category` VARCHAR(50) NOT NULL AFTER `description`;
ALTER TABLE `acc_personnel` ADD COLUMN `parent_id` INT DEFAULT 0 NOT NULL AFTER `category`;

DROP TABLE IF EXISTS `acc_chart_of_accounts_detail`;
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
  `status` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `modified_at` datetime DEFAULT current_timestamp(),
  `modified_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `is_bank` tinyint(1) NOT NULL,
  `is_cash` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `acc_chart_of_accounts_detail` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_chart_of_accounts_detail` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `acc_chart_of_accounts_detail` CHANGE `status` `status` TINYINT(4) NOT NULL DEFAULT '1';
ALTER TABLE `acc_chart_of_accounts_detail` ADD COLUMN `is_deletable` TINYINT DEFAULT 1 NOT NULL AFTER `is_cash`;
ALTER TABLE `acc_chart_of_accounts_detail` ADD COLUMN `is_defaultBank` INT NOT NULL DEFAULT '0' AFTER `is_bank`;
ALTER TABLE `acc_chart_of_accounts_detail` ADD COLUMN `school_item_id` INT DEFAULT 0 NOT NULL AFTER `is_deletable`, ADD COLUMN `school_item_type` VARCHAR(50) DEFAULT '' NOT NULL AFTER `school_item_id`;

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


DROP TABLE IF EXISTS `acc_general_settings`;
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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `acc_general_settings` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_general_settings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `acc_general_settings` ADD COLUMN `level` INT NOT NULL AFTER `year_end`;
ALTER TABLE `acc_general_settings` ADD COLUMN `due_date_duration` INT NOT NULL AFTER `level`;
ALTER TABLE `acc_general_settings` ADD COLUMN `is_year_saved` TINYINT NOT NULL DEFAULT '0' AFTER `modified_at`, ADD COLUMN `is_settings_saved` TINYINT NOT NULL DEFAULT '0' AFTER `is_year_saved`;

insert  into `acc_general_settings` (`id`,`system_type`,`round_to`,`allow_receipt_edit`,`allow_payment_edit`,`allow_journal_edit`,`invoice_generation_on`,`invoice_prefix`,`use_invoice_prefix`,`invoice_start`,`journal_prefix`,`use_journal_prefix`,`journal_start`,`general_receipt_prefix`,`use_general_receipt_prefix`,`general_receipt_start`,`general_payment_prefix`,`use_general_payment_prefix`,`general_payment_start`,`cash_receipt_prefix`,`use_cash_receipt_prefix`,`cash_receipt_start`,`date_system`,`year_start`,`year_end`,`level`,`due_date_duration`,`created_at`,`modified_at`) values
(1,1,1,0,0,0,0,'INV',0,1,'JOR',0,1,'GRN',0,1,'GPN',0,1,'CRN',0,1,2,0,0,4,7,'2020-01-24 11:51:28','2020-01-24 16:04:07');

--
-- journal voucher
--
DROP TABLE IF EXISTS `acc_journal`;
CREATE TABLE `acc_journal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `entry_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `reference_no` varchar(20) NOT NULL,
  `narration` text NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `financial_year` int(11) NOT NULL,
  `is_cleared` tinyint(1) NOT NULL DEFAULT '0',
  `entry_date_bs` date NOT NULL,
  `due_date_bs` date NOT NULL,
  `bs_year` int(11) NOT NULL,
  `bs_month` tinyint(4) NOT NULL,
  `bs_day` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `acc_journal_entry`;
CREATE TABLE `acc_journal_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coa_id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amount_type` varchar(10) NOT NULL DEFAULT 'debit',
  `journal_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- opening balances
--
DROP TABLE IF EXISTS `acc_opening_balances`;
CREATE TABLE `acc_opening_balances` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `coa_id` int(11) NOT NULL,
  `balance` double NOT NULL,
  `balance_type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `acc_opening_balances` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_opening_balances` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `acc_opening_balances` ADD COLUMN `financial_year` INT NOT NULL AFTER `modified_by`;

DROP TABLE IF EXISTS `acc_financial_year`;
CREATE TABLE `acc_financial_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year_starts` date NOT NULL,
  `year_ends` date NOT NULL,
  `is_current` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `acc_financial_year`  ADD `year_starts_bs` VARCHAR(50) NOT NULL  AFTER `is_current`,  ADD `year_ends_bs` VARCHAR(50) NOT NULL  AFTER `year_starts_bs`;

--
-- invoice
--
DROP TABLE IF EXISTS `acc_invoice`;
CREATE TABLE `acc_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `acc_invoice` ADD COLUMN `fee_id` INT DEFAULT 0 NOT NULL AFTER `bs_day`;

DROP TABLE IF EXISTS `acc_invoice_entry`;
CREATE TABLE `acc_invoice_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coa_id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `tax` tinyint(1) NOT NULL,
  `tax_rate` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `invoice_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `acc_invoice_entry` DROP COLUMN `tax`, DROP COLUMN `tax_rate`, DROP COLUMN `tax_amount`;

--
-- transaction log
--
DROP TABLE IF EXISTS `acc_transaction_logs`;
CREATE TABLE `acc_transaction_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `parent_type` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category_type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `acc_transaction_logs` ADD COLUMN `amount_type` VARCHAR(50) NOT NULL AFTER `amount`;
ALTER TABLE `acc_transaction_logs` ADD COLUMN `financial_year` INT NOT NULL AFTER `status`;

DROP TABLE IF EXISTS `acc_payment`;
CREATE TABLE `acc_payment` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `payment_no` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `paid_to` int(11) NOT NULL,
  `bank` int(255) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
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
  `cheque_date_bs` date DEFAULT NULL,
  `bs_year` int(11) NOT NULL,
  `bs_month` tinyint(4) NOT NULL,
  `bs_day` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `acc_payment` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_payment` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `acc_payment` CHANGE `bank` `asset_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `acc_payment` DROP `cheque_no`;
ALTER TABLE `acc_payment` DROP `cheque_date`;
ALTER TABLE `acc_payment` DROP `cheque_date_bs`;
ALTER TABLE `acc_payment` ADD `payment_mode_details` JSON NULL DEFAULT NULL AFTER `asset_id`;

--
-- Table structure for table `acc_payment_details`
--
DROP TABLE IF EXISTS `acc_payment_details`;
CREATE TABLE `acc_payment_details` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `total` float NOT NULL,
  `remaining_amount` float NOT NULL,
  `paid_amount` float NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `acc_payment_details` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_payment_details` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Table structure for table `acc_receipt`
--
DROP TABLE IF EXISTS `acc_receipt`;
CREATE TABLE `acc_receipt` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `receipt_no` varchar(255) NOT NULL,
  `receipt_date` date NOT NULL,
  `receipt_mode` varchar(255) NOT NULL,
  `received_from` int(11) NOT NULL,
  `bank` int(255) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
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
  `cheque_date_bs` date DEFAULT NULL,
  `bs_year` int(11) NOT NULL,
  `bs_month` tinyint(4) NOT NULL,
  `bs_day` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `acc_receipt` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_receipt` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `acc_receipt` ADD COLUMN `fee_id` INT DEFAULT 0 NOT NULL AFTER `bs_day`;
ALTER TABLE `acc_receipt` ADD COLUMN `auto_created` INT NOT NULL DEFAULT '0' AFTER `bs_day`;
ALTER TABLE `acc_receipt` CHANGE `bank` `asset_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `acc_receipt` DROP `cheque_no`;
ALTER TABLE `acc_receipt` DROP `cheque_date`;
ALTER TABLE `acc_receipt` DROP `cheque_date_bs`;
ALTER TABLE `acc_receipt` ADD `receipt_mode_details` JSON NULL DEFAULT NULL AFTER `asset_id`;

--
-- Table structure for table `acc_receipt_details`
--
DROP TABLE IF EXISTS `acc_receipt_details`;
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
ALTER TABLE `acc_receipt_details` ADD PRIMARY KEY (`id`);
ALTER TABLE `acc_receipt_details` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `acc_coa_categories` AUTO_INCREMENT=100;
ALTER TABLE `acc_chart_of_accounts_detail` AUTO_INCREMENT=100;
ALTER TABLE `acc_opening_balances` AUTO_INCREMENT=100;

INSERT INTO `notification_setting` (`id`, `type`, `is_mail`) VALUES ('6', 'send_account_email', '1');

ALTER TABLE `acc_general_settings`  ADD `opening_balance_date` VARCHAR(255) NOT NULL  AFTER `is_settings_saved`;
ALTER TABLE `acc_general_settings`  ADD `opening_balance_date_bs` VARCHAR(255) NOT NULL  AFTER `opening_balance_date`;

DROP TABLE IF EXISTS `acc_advances`;
CREATE TABLE `acc_advances` (
  `id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `advance_amount` float NOT NULL,
  `advance_date` datetime NOT NULL,
  `remarks` text NOT NULL,
  `reference_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `acc_advances`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `acc_advances`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

