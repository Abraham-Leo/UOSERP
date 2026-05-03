-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 03, 2026 at 11:58 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cetec_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ap_vouchers`
--

CREATE TABLE `ap_vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `voucher_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `gl_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint UNSIGNED NOT NULL,
  `asset_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_order_id` bigint UNSIGNED DEFAULT NULL,
  `purchase_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `purchase_date` date DEFAULT NULL,
  `bin_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `next_maintenance_date` date DEFAULT NULL,
  `maintenance_frequency_days` int NOT NULL DEFAULT '365',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bin_locations`
--

CREATE TABLE `bin_locations` (
  `id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aisle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `row` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boms`
--

CREATE TABLE `boms` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_part_id` bigint UNSIGNED NOT NULL,
  `revision` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `description` text COLLATE utf8mb4_unicode_ci,
  `labor_estimate_hours` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `overhead_rate` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `is_current` tinyint(1) NOT NULL DEFAULT '1',
  `effective_date` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bom_lines`
--

CREATE TABLE `bom_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `bom_id` bigint UNSIGNED NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `unit_of_measure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EA',
  `sort_order` int NOT NULL DEFAULT '0',
  `reference_designator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_phantom` tinyint(1) NOT NULL DEFAULT '0',
  `substitute_allowed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bom_operations`
--

CREATE TABLE `bom_operations` (
  `id` bigint UNSIGNED NOT NULL,
  `bom_id` bigint UNSIGNED NOT NULL,
  `sequence` int NOT NULL DEFAULT '10',
  `operation_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_center` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `setup_time_hrs` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `run_time_hrs` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `work_instructions` text COLLATE utf8mb4_unicode_ci,
  `outsource` tinyint(1) NOT NULL DEFAULT '0',
  `outsource_vendor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `outsource_lead_days` decimal(8,2) NOT NULL DEFAULT '0.00',
  `machine_setup` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `billing_address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
  `shipping_address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Net 30',
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `taxable` tinyint(1) NOT NULL DEFAULT '1',
  `tax_rate` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `ship_via` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `commission_rate` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `sales_rep_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `date_kit_audit` date DEFAULT NULL,
  `qc_inspector` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_code_format` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_number`, `name`, `company_name`, `email`, `phone`, `fax`, `website`, `account_type`, `billing_address1`, `billing_address2`, `billing_city`, `billing_state`, `billing_zip`, `billing_country`, `shipping_address1`, `shipping_address2`, `shipping_city`, `shipping_state`, `shipping_zip`, `shipping_country`, `payment_terms`, `currency`, `taxable`, `tax_rate`, `ship_via`, `shipping_account`, `credit_limit`, `commission_rate`, `sales_rep_id`, `notes`, `is_active`, `date_kit_audit`, `qc_inspector`, `date_code_format`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CUST-00001', 'Acme Industries', NULL, 'acme@acme.com', '555-100-0001', NULL, NULL, 'customer', NULL, NULL, NULL, NULL, NULL, 'US', NULL, NULL, NULL, NULL, NULL, 'US', 'Net 30', 'USD', 1, '0.0000', NULL, NULL, '0.00', '0.0000', NULL, NULL, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(2, 'CUST-00002', 'TechCorp LLC', NULL, 'orders@techcorp.com', '555-100-0002', NULL, NULL, 'customer', NULL, NULL, NULL, NULL, NULL, 'US', NULL, NULL, NULL, NULL, NULL, 'US', 'Net 45', 'USD', 1, '0.0000', NULL, NULL, '0.00', '0.0000', NULL, NULL, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(3, 'CUST-00003', 'Global Manufacturing', NULL, 'ap@globalMFG.com', '555-100-0003', NULL, NULL, 'customer', NULL, NULL, NULL, NULL, NULL, 'US', NULL, NULL, NULL, NULL, NULL, 'US', 'Net 30', 'USD', 1, '0.0000', NULL, NULL, '0.00', '0.0000', NULL, NULL, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(4, 'CUST-00004', 'Pacific Steel Co', NULL, 'po@pacsteel.com', '555-100-0004', NULL, NULL, 'customer', NULL, NULL, NULL, NULL, NULL, 'US', NULL, NULL, NULL, NULL, NULL, 'US', 'Net 60', 'USD', 1, '0.0000', NULL, NULL, '0.00', '0.0000', NULL, NULL, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(5, 'CUST-00005', 'Nexus Parts Inc', NULL, 'buy@nexusparts.com', '555-100-0005', NULL, NULL, 'customer', NULL, NULL, NULL, NULL, NULL, 'US', NULL, NULL, NULL, NULL, NULL, 'US', 'Net 15', 'USD', 1, '0.0000', NULL, NULL, '0.00', '0.0000', NULL, NULL, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(6, 'CUST-00006', 'Delta Systems', NULL, 'eng@deltasys.com', '555-100-0006', NULL, NULL, 'customer', NULL, NULL, NULL, NULL, NULL, 'US', NULL, NULL, NULL, NULL, NULL, 'US', 'Net 30', 'USD', 1, '0.0000', NULL, NULL, '0.00', '0.0000', NULL, NULL, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(7, 'CUST-00007', 'Precision Tools Ltd', NULL, 'ops@prectools.com', '555-100-0007', NULL, NULL, 'customer', NULL, NULL, NULL, NULL, NULL, 'US', NULL, NULL, NULL, NULL, NULL, 'US', 'COD', 'USD', 1, '0.0000', NULL, NULL, '0.00', '0.0000', NULL, NULL, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_contacts`
--

CREATE TABLE `customer_contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_contact` tinyint(1) NOT NULL DEFAULT '0',
  `billing_contact` tinyint(1) NOT NULL DEFAULT '0',
  `shipping_contact` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint NOT NULL DEFAULT '0',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revision` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `description` text COLLATE utf8mb4_unicode_ci,
  `tags` json DEFAULT NULL,
  `documentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documentable_id` bigint UNSIGNED NOT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ecos`
--

CREATE TABLE `ecos` (
  `id` bigint UNSIGNED NOT NULL,
  `eco_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'eco',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `description` text COLLATE utf8mb4_unicode_ci,
  `risk_mitigation` text COLLATE utf8mb4_unicode_ci,
  `cost_impact` decimal(15,2) NOT NULL DEFAULT '0.00',
  `part_id` bigint UNSIGNED DEFAULT NULL,
  `rev_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rev_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `initiated_by` bigint UNSIGNED DEFAULT NULL,
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_notifications`
--

CREATE TABLE `erp_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_accounts`
--

CREATE TABLE `gl_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gl_accounts`
--

INSERT INTO `gl_accounts` (`id`, `account_number`, `name`, `type`, `sub_type`, `is_active`, `balance`, `description`, `created_at`, `updated_at`) VALUES
(1, '1000', 'Cash — Checking', 'asset', 'current', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(2, '1100', 'Accounts Receivable', 'asset', 'current', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(3, '1200', 'Inventory Asset', 'asset', 'current', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(4, '1500', 'Fixed Assets', 'asset', 'fixed', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(5, '2000', 'Accounts Payable', 'liability', 'current', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(6, '2100', 'Accrued A/P Liabilities', 'liability', 'current', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(7, '2500', 'Long-Term Debt', 'liability', 'long_term', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(8, '3000', 'Owner Equity', 'equity', NULL, 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(9, '3100', 'Retained Earnings', 'equity', NULL, 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(10, '4000', 'Sales Revenue', 'revenue', NULL, 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(11, '4100', 'Service Revenue', 'revenue', NULL, 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(12, '5000', 'Cost of Goods Sold', 'expense', 'cogs', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(13, '5100', 'Direct Labor', 'expense', 'cogs', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(14, '5200', 'Manufacturing Overhead', 'expense', 'cogs', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(15, '6000', 'Salaries & Wages', 'expense', 'operating', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(16, '6100', 'Rent & Facilities', 'expense', 'operating', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(17, '6200', 'Utilities', 'expense', 'operating', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(18, '6300', 'Marketing & Advertising', 'expense', 'operating', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(19, '6400', 'Shipping & Freight', 'expense', 'operating', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(20, '6500', 'Depreciation', 'expense', 'operating', 1, '0.00', NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38');

-- --------------------------------------------------------

--
-- Table structure for table `gl_entries`
--

CREATE TABLE `gl_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `gl_transaction_id` bigint UNSIGNED NOT NULL,
  `gl_account_id` bigint UNSIGNED NOT NULL,
  `debit` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `credit` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gl_transactions`
--

CREATE TABLE `gl_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `transactionable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transactionable_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` bigint UNSIGNED NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `bin_location_id` bigint UNSIGNED DEFAULT NULL,
  `qty_on_hand` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_reserved` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_on_order` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `shipping` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_due` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Net 30',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `sent_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labor_entries`
--

CREATE TABLE `labor_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `wo_operation_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `clock_in` timestamp NOT NULL,
  `clock_out` timestamp NULL DEFAULT NULL,
  `hours` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `overtime_hours` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `labor_rate` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `labor_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_create_erp_tables', 2),
(5, '2026_04_23_135135_create_permission_tables', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 4),
(6, 'App\\Models\\User', 5),
(7, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 7);

-- --------------------------------------------------------

--
-- Table structure for table `ncrs`
--

CREATE TABLE `ncrs` (
  `id` bigint UNSIGNED NOT NULL,
  `ncr_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'receiving',
  `disposition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `receipt_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `cost_impact` decimal(15,2) NOT NULL DEFAULT '0.00',
  `containment_area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `resolution` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `quote_id` bigint UNSIGNED DEFAULT NULL,
  `sales_rep_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stock',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `order_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `ship_date` date DEFAULT NULL,
  `work_start_date` date DEFAULT NULL,
  `customer_po` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Net 30',
  `ship_via` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `discount_pct` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `internal_notes` text COLLATE utf8mb4_unicode_ci,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `released_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_lines`
--

CREATE TABLE `order_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `line_number` int NOT NULL DEFAULT '1',
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `qty_shipped` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_invoiced` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `discount_pct` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `line_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `due_date` date DEFAULT NULL,
  `line_notes` text COLLATE utf8mb4_unicode_ci,
  `shop_notes` text COLLATE utf8mb4_unicode_ci,
  `work_order_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` bigint UNSIGNED NOT NULL,
  `part_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'component',
  `unit_of_measure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EA',
  `unit_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `standard_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `last_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `average_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `weight` decimal(10,4) DEFAULT NULL,
  `weight_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LB',
  `lead_time_days` decimal(8,2) NOT NULL DEFAULT '0.00',
  `reorder_point` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `economic_order_qty` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `safety_stock` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `preferred_vendor_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `make_buy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'buy',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_purchaseable` tinyint(1) NOT NULL DEFAULT '1',
  `is_saleable` tinyint(1) NOT NULL DEFAULT '0',
  `is_manufactured` tinyint(1) NOT NULL DEFAULT '0',
  `track_serial` tinyint(1) NOT NULL DEFAULT '0',
  `track_lot` tinyint(1) NOT NULL DEFAULT '0',
  `revision` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `custom_fields` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `part_number`, `description`, `category`, `type`, `unit_of_measure`, `unit_cost`, `standard_cost`, `last_cost`, `average_cost`, `unit_price`, `weight`, `weight_unit`, `lead_time_days`, `reorder_point`, `economic_order_qty`, `safety_stock`, `preferred_vendor_id`, `make_buy`, `is_active`, `is_purchaseable`, `is_saleable`, `is_manufactured`, `track_serial`, `track_lot`, `revision`, `notes`, `custom_fields`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'COMP-0042', 'Capacitor 100uF 25V SMD', NULL, 'component', 'EA', '0.1200', '0.1200', '0.1000', '0.1200', '0.1620', NULL, 'LB', '30.00', '63.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(2, 'COMP-0091', 'IC Chip STM32F407VGT6', NULL, 'component', 'EA', '8.4500', '8.4500', '7.8000', '8.4500', '11.4075', NULL, 'LB', '10.00', '55.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(3, 'COMP-0055', 'Resistor 10kΩ 0402 1%', NULL, 'component', 'EA', '0.0080', '0.0080', '0.0060', '0.0080', '0.0108', NULL, 'LB', '17.00', '91.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(4, 'COMP-0078', 'Transformer 24V 2A', NULL, 'component', 'EA', '12.5000', '12.5000', '11.0000', '12.5000', '16.8750', NULL, 'LB', '19.00', '96.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(5, 'COMP-0102', 'MOSFET IRF540N', NULL, 'component', 'EA', '1.8500', '1.8500', '1.6000', '1.8500', '2.4975', NULL, 'LB', '18.00', '48.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(6, 'MECH-0033', 'Bearing 6204 2RS Sealed', NULL, 'component', 'EA', '2.8500', '2.8500', '2.5000', '2.8500', '3.8475', NULL, 'LB', '11.00', '96.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(7, 'MECH-0019', 'Aluminum Bracket L40×40', NULL, 'component', 'EA', '3.5000', '3.5000', '3.0000', '3.5000', '4.7250', NULL, 'LB', '18.00', '64.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(8, 'RAW-0012', 'Steel Sheet 304 SS 2mm', NULL, 'raw_material', 'SQFT', '4.2000', '4.2000', '3.8000', '4.2000', '5.6700', NULL, 'LB', '29.00', '23.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(9, 'RAW-0008', 'Copper Wire 18AWG', NULL, 'raw_material', 'FT', '0.8500', '0.8500', '0.7500', '0.8500', '1.1475', NULL, 'LB', '27.00', '98.0000', '0.0000', '0.0000', NULL, 'buy', 1, 1, 0, 0, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(10, 'SUB-0018', 'Control Board Rev C', NULL, 'subassembly', 'EA', '145.0000', '145.0000', '120.0000', '145.0000', '195.7500', NULL, 'LB', '22.00', '51.0000', '0.0000', '0.0000', NULL, 'make', 1, 0, 0, 1, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(11, 'FG-0001', 'Motor Controller Unit v3', NULL, 'finished_good', 'EA', '482.0000', '482.0000', '400.0000', '482.0000', '650.7000', NULL, 'LB', '15.00', '81.0000', '0.0000', '0.0000', NULL, 'make', 1, 0, 1, 1, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(12, 'FG-0002', 'PCB Assembly X72', NULL, 'finished_good', 'EA', '284.0000', '284.0000', '220.0000', '284.0000', '383.4000', NULL, 'LB', '9.00', '31.0000', '0.0000', '0.0000', NULL, 'make', 1, 0, 1, 1, 0, 0, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payable_id` bigint UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'check',
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_lines`
--

CREATE TABLE `po_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `line_number` int NOT NULL DEFAULT '1',
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `qty_received` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_billed` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `line_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `commit_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `vendor_part_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `buyer_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standard',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `po_date` date NOT NULL,
  `requested_date` date DEFAULT NULL,
  `acknowledged_date` date DEFAULT NULL,
  `vendor_po_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_via` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Net 30',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_billed` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `work_order_id` bigint UNSIGNED DEFAULT NULL,
  `acknowledged` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` bigint UNSIGNED NOT NULL,
  `quote_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `sales_rep_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `quote_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `customer_po` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Net 30',
  `ship_via` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `discount_pct` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `internal_notes` text COLLATE utf8mb4_unicode_ci,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `probability` decimal(5,2) NOT NULL DEFAULT '50.00',
  `converted_order_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` bigint UNSIGNED NOT NULL,
  `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `receipt_date` date NOT NULL,
  `packing_slip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `received_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_lines`
--

CREATE TABLE `receipt_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `receipt_id` bigint UNSIGNED NOT NULL,
  `po_line_id` bigint UNSIGNED NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `bin_location_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `lot_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revision` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inspection_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'accepted',
  `qty_accepted` int NOT NULL DEFAULT '0',
  `qty_rejected` int NOT NULL DEFAULT '0',
  `inspection_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rmas`
--

CREATE TABLE `rmas` (
  `id` bigint UNSIGNED NOT NULL,
  `rma_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'return',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `rma_date` date NOT NULL,
  `handling_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-04-23 06:51:51', '2026-04-23 06:51:51'),
(2, 'sales', 'web', '2026-04-23 06:51:51', '2026-04-23 06:51:51'),
(3, 'production', 'web', '2026-04-23 06:51:51', '2026-04-23 06:51:51'),
(4, 'purchasing', 'web', '2026-04-23 06:51:51', '2026-04-23 06:51:51'),
(5, 'finance', 'web', '2026-04-23 06:51:51', '2026-04-23 06:51:51'),
(6, 'quality', 'web', '2026-04-23 06:51:51', '2026-04-23 06:51:51'),
(7, 'warehouse', 'web', '2026-04-23 06:51:51', '2026-04-23 06:51:51');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `ship_date` date NOT NULL,
  `carrier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(10,4) DEFAULT NULL,
  `dimensions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `freight_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `freight_charge` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_signature` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_floor_only` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `default_warehouse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferences` json DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `employee_id`, `title`, `department`, `phone`, `mobile`, `email_signature`, `avatar`, `shop_floor_only`, `is_active`, `default_warehouse`, `preferences`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'System Admin', 'admin@erp.com', '2026-04-23 06:54:36', '$2y$12$fmORhyReyXTNtEBnzFGOSekznYFfbpo0bADjR64s.//uX3FUEj6qu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, '2026-04-23 06:54:36', '2026-04-23 06:54:36', NULL),
(2, 'Sarah Sales', 'sales@erp.com', '2026-04-23 06:54:36', '$2y$12$8cIsic0xVyj0iwI9rnTeH.Zfm9JhIyXoXds323wlw3TebhKOpnz4i', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, '2026-04-23 06:54:36', '2026-04-23 06:54:36', NULL),
(3, 'Pete Production', 'prod@erp.com', '2026-04-23 06:54:37', '$2y$12$j7Q1v3PG/5tsJqasBpCBD.pR4dVsX1L03mpsYTmoT4OwgKqLXfw4u', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, '2026-04-23 06:54:37', '2026-04-23 06:54:37', NULL),
(4, 'Frank Finance', 'finance@erp.com', '2026-04-23 06:54:37', '$2y$12$rkhTubNYLZKuxri5c8kz..mKjTIrJiBPtUiGRC2nv6Bif.rswMuLq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, '2026-04-23 06:54:37', '2026-04-23 06:54:37', NULL),
(5, 'Qara Quality', 'quality@erp.com', '2026-04-23 06:54:37', '$2y$12$LUw8lL3fY1tyeRZvTpBHp.Kc8Scie0csRUCrMHono6d74jdeDF0mG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, '2026-04-23 06:54:37', '2026-04-23 06:54:37', NULL),
(6, 'Will Warehouse', 'wh@erp.com', '2026-04-23 06:54:38', '$2y$12$xaEwPuWjl2uXLAmh8KZeNuaBLguwkd4EySwc.sVL92Nc9TsJVnRQK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(7, 'Pablo Purchasing', 'purchase@erp.com', '2026-04-23 06:54:38', '$2y$12$PRwEjdK.3xzk5yx/dw8x/uIA9WHja/PTdvCPvS/HHuOwHNevJ9RtS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Net 30',
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `taxable` tinyint(1) NOT NULL DEFAULT '0',
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_via` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimum_order` decimal(15,2) NOT NULL DEFAULT '0.00',
  `buyer_id` bigint UNSIGNED DEFAULT NULL,
  `on_hold` tinyint(1) NOT NULL DEFAULT '0',
  `hold_notes` text COLLATE utf8mb4_unicode_ci,
  `rating` decimal(3,2) NOT NULL DEFAULT '5.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `vendor_number`, `name`, `email`, `phone`, `website`, `billing_address1`, `billing_city`, `billing_state`, `billing_zip`, `billing_country`, `payment_terms`, `currency`, `taxable`, `tax_id`, `vat_id`, `fob`, `ship_via`, `minimum_order`, `buyer_id`, `on_hold`, `hold_notes`, `rating`, `is_active`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'VEND-00001', 'DigiKey Corp', 'orders@digikey.com', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 'Net 30', 'USD', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 0, NULL, '4.50', 1, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(2, 'VEND-00002', 'Acme Electronics', 'ap@acmeelec.com', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 'Net 45', 'USD', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 0, NULL, '4.50', 1, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(3, 'VEND-00003', 'MetalMart Inc', 'po@metalmart.com', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 'Net 30', 'USD', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 0, NULL, '4.50', 1, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(4, 'VEND-00004', 'Global Bearings', 'sales@gbearings.com', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 'Net 30', 'USD', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 0, NULL, '4.50', 1, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(5, 'VEND-00005', 'Wire Works LLC', 'info@wireworks.com', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 'Net 15', 'USD', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 0, NULL, '4.50', 1, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(6, 'VEND-00006', 'Contract Fab Co', 'ops@contractfab.com', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 'Net 45', 'USD', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 0, NULL, '4.50', 1, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL),
(7, 'VEND-00007', 'Mouser Electronics', 'po@mouser.com', NULL, NULL, NULL, NULL, NULL, NULL, 'US', 'Net 30', 'USD', 0, NULL, NULL, NULL, NULL, '0.00', NULL, 0, NULL, '4.50', 1, NULL, '2026-04-23 06:54:38', '2026-04-23 06:54:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `code`, `name`, `address1`, `city`, `state`, `zip`, `country`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'MAIN', 'Main Warehouse', NULL, NULL, NULL, NULL, 'US', 1, 1, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(2, 'WIP', 'WIP Storage', NULL, NULL, NULL, NULL, 'US', 0, 1, '2026-04-23 06:54:38', '2026-04-23 06:54:38'),
(3, 'FG', 'Finished Goods', NULL, NULL, NULL, NULL, 'US', 0, 1, '2026-04-23 06:54:38', '2026-04-23 06:54:38');

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `wo_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `order_line_id` bigint UNSIGNED DEFAULT NULL,
  `bom_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `qty_complete` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_scrapped` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `order_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `work_start_date` date DEFAULT NULL,
  `completed_date` date DEFAULT NULL,
  `unit_cost_estimate` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_cost_actual` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `labor_hrs_estimate` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `labor_hrs_actual` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `material_cost_actual` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `labor_cost_actual` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `overhead_cost_actual` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `outsource_cost_actual` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `released_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wo_materials`
--

CREATE TABLE `wo_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `bom_line_id` bigint UNSIGNED DEFAULT NULL,
  `qty_required` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_picked` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_consumed` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `qty_scrapped` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `lot_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bin_location_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wo_operations`
--

CREATE TABLE `wo_operations` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `sequence` int NOT NULL DEFAULT '10',
  `operation_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_center` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `setup_time_est` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `run_time_est` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `setup_time_actual` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `run_time_actual` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `work_instructions` text COLLATE utf8mb4_unicode_ci,
  `outsource` tinyint(1) NOT NULL DEFAULT '0',
  `assigned_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `ap_vouchers`
--
ALTER TABLE `ap_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ap_vouchers_voucher_number_unique` (`voucher_number`),
  ADD KEY `ap_vouchers_vendor_id_foreign` (`vendor_id`),
  ADD KEY `ap_vouchers_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assets_asset_id_unique` (`asset_id`);

--
-- Indexes for table `bin_locations`
--
ALTER TABLE `bin_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bin_locations_warehouse_id_code_unique` (`warehouse_id`,`code`);

--
-- Indexes for table `boms`
--
ALTER TABLE `boms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boms_parent_part_id_foreign` (`parent_part_id`);

--
-- Indexes for table `bom_lines`
--
ALTER TABLE `bom_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_lines_bom_id_foreign` (`bom_id`),
  ADD KEY `bom_lines_part_id_foreign` (`part_id`);

--
-- Indexes for table `bom_operations`
--
ALTER TABLE `bom_operations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bom_operations_bom_id_foreign` (`bom_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_customer_number_unique` (`customer_number`),
  ADD KEY `customers_customer_number_index` (`customer_number`);

--
-- Indexes for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_contacts_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_documentable_type_documentable_id_index` (`documentable_type`,`documentable_id`);

--
-- Indexes for table `ecos`
--
ALTER TABLE `ecos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ecos_eco_number_unique` (`eco_number`),
  ADD KEY `ecos_part_id_foreign` (`part_id`);

--
-- Indexes for table `erp_notifications`
--
ALTER TABLE `erp_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `erp_notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gl_accounts`
--
ALTER TABLE `gl_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gl_accounts_account_number_unique` (`account_number`);

--
-- Indexes for table `gl_entries`
--
ALTER TABLE `gl_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gl_entries_gl_transaction_id_foreign` (`gl_transaction_id`),
  ADD KEY `gl_entries_gl_account_id_foreign` (`gl_account_id`);

--
-- Indexes for table `gl_transactions`
--
ALTER TABLE `gl_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gl_transactions_transactionable_type_transactionable_id_index` (`transactionable_type`,`transactionable_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_part_id_warehouse_id_bin_location_id_unique` (`part_id`,`warehouse_id`,`bin_location_id`),
  ADD KEY `inventory_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `inventory_bin_location_id_foreign` (`bin_location_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`),
  ADD KEY `invoices_order_id_foreign` (`order_id`),
  ADD KEY `invoices_invoice_number_index` (`invoice_number`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labor_entries`
--
ALTER TABLE `labor_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `labor_entries_work_order_id_foreign` (`work_order_id`),
  ADD KEY `labor_entries_wo_operation_id_foreign` (`wo_operation_id`),
  ADD KEY `labor_entries_user_id_foreign` (`user_id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `ncrs`
--
ALTER TABLE `ncrs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ncrs_ncr_number_unique` (`ncr_number`),
  ADD KEY `ncrs_part_id_foreign` (`part_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `orders_order_number_index` (`order_number`),
  ADD KEY `orders_status_index` (`status`);

--
-- Indexes for table `order_lines`
--
ALTER TABLE `order_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_lines_order_id_foreign` (`order_id`),
  ADD KEY `order_lines_part_id_foreign` (`part_id`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parts_part_number_unique` (`part_number`),
  ADD KEY `parts_part_number_index` (`part_number`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_payment_number_unique` (`payment_number`),
  ADD KEY `payments_payable_type_payable_id_index` (`payable_type`,`payable_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `po_lines`
--
ALTER TABLE `po_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `po_lines_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `po_lines_part_id_foreign` (`part_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_vendor_id_foreign` (`vendor_id`),
  ADD KEY `purchase_orders_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_orders_po_number_index` (`po_number`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotes_quote_number_unique` (`quote_number`),
  ADD KEY `quotes_customer_id_foreign` (`customer_id`),
  ADD KEY `quotes_quote_number_index` (`quote_number`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipts_receipt_number_unique` (`receipt_number`),
  ADD KEY `receipts_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `receipts_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `receipt_lines`
--
ALTER TABLE `receipt_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_lines_receipt_id_foreign` (`receipt_id`),
  ADD KEY `receipt_lines_po_line_id_foreign` (`po_line_id`),
  ADD KEY `receipt_lines_part_id_foreign` (`part_id`),
  ADD KEY `receipt_lines_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `receipt_lines_bin_location_id_foreign` (`bin_location_id`);

--
-- Indexes for table `rmas`
--
ALTER TABLE `rmas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rmas_rma_number_unique` (`rma_number`),
  ADD KEY `rmas_customer_id_foreign` (`customer_id`),
  ADD KEY `rmas_order_id_foreign` (`order_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipments_shipment_number_unique` (`shipment_number`),
  ADD KEY `shipments_order_id_foreign` (`order_id`),
  ADD KEY `shipments_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_employee_id_unique` (`employee_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_vendor_number_unique` (`vendor_number`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouses_code_unique` (`code`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_orders_wo_number_unique` (`wo_number`),
  ADD KEY `work_orders_part_id_foreign` (`part_id`),
  ADD KEY `work_orders_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `work_orders_wo_number_index` (`wo_number`),
  ADD KEY `work_orders_status_index` (`status`);

--
-- Indexes for table `wo_materials`
--
ALTER TABLE `wo_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wo_materials_work_order_id_foreign` (`work_order_id`),
  ADD KEY `wo_materials_part_id_foreign` (`part_id`),
  ADD KEY `wo_materials_bom_line_id_foreign` (`bom_line_id`),
  ADD KEY `wo_materials_bin_location_id_foreign` (`bin_location_id`);

--
-- Indexes for table `wo_operations`
--
ALTER TABLE `wo_operations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wo_operations_work_order_id_foreign` (`work_order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ap_vouchers`
--
ALTER TABLE `ap_vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bin_locations`
--
ALTER TABLE `bin_locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boms`
--
ALTER TABLE `boms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bom_lines`
--
ALTER TABLE `bom_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bom_operations`
--
ALTER TABLE `bom_operations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ecos`
--
ALTER TABLE `ecos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_notifications`
--
ALTER TABLE `erp_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gl_accounts`
--
ALTER TABLE `gl_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `gl_entries`
--
ALTER TABLE `gl_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gl_transactions`
--
ALTER TABLE `gl_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labor_entries`
--
ALTER TABLE `labor_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ncrs`
--
ALTER TABLE `ncrs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_lines`
--
ALTER TABLE `order_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `po_lines`
--
ALTER TABLE `po_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_lines`
--
ALTER TABLE `receipt_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rmas`
--
ALTER TABLE `rmas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wo_materials`
--
ALTER TABLE `wo_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wo_operations`
--
ALTER TABLE `wo_operations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ap_vouchers`
--
ALTER TABLE `ap_vouchers`
  ADD CONSTRAINT `ap_vouchers_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ap_vouchers_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `bin_locations`
--
ALTER TABLE `bin_locations`
  ADD CONSTRAINT `bin_locations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `boms`
--
ALTER TABLE `boms`
  ADD CONSTRAINT `boms_parent_part_id_foreign` FOREIGN KEY (`parent_part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bom_lines`
--
ALTER TABLE `bom_lines`
  ADD CONSTRAINT `bom_lines_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `boms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bom_lines_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bom_operations`
--
ALTER TABLE `bom_operations`
  ADD CONSTRAINT `bom_operations_bom_id_foreign` FOREIGN KEY (`bom_id`) REFERENCES `boms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD CONSTRAINT `customer_contacts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ecos`
--
ALTER TABLE `ecos`
  ADD CONSTRAINT `ecos_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `erp_notifications`
--
ALTER TABLE `erp_notifications`
  ADD CONSTRAINT `erp_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gl_entries`
--
ALTER TABLE `gl_entries`
  ADD CONSTRAINT `gl_entries_gl_account_id_foreign` FOREIGN KEY (`gl_account_id`) REFERENCES `gl_accounts` (`id`),
  ADD CONSTRAINT `gl_entries_gl_transaction_id_foreign` FOREIGN KEY (`gl_transaction_id`) REFERENCES `gl_transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_bin_location_id_foreign` FOREIGN KEY (`bin_location_id`) REFERENCES `bin_locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `labor_entries`
--
ALTER TABLE `labor_entries`
  ADD CONSTRAINT `labor_entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `labor_entries_wo_operation_id_foreign` FOREIGN KEY (`wo_operation_id`) REFERENCES `wo_operations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `labor_entries_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ncrs`
--
ALTER TABLE `ncrs`
  ADD CONSTRAINT `ncrs_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_lines`
--
ALTER TABLE `order_lines`
  ADD CONSTRAINT `order_lines_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_lines_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`);

--
-- Constraints for table `po_lines`
--
ALTER TABLE `po_lines`
  ADD CONSTRAINT `po_lines_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`),
  ADD CONSTRAINT `po_lines_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  ADD CONSTRAINT `purchase_orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `receipts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `receipt_lines`
--
ALTER TABLE `receipt_lines`
  ADD CONSTRAINT `receipt_lines_bin_location_id_foreign` FOREIGN KEY (`bin_location_id`) REFERENCES `bin_locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `receipt_lines_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`),
  ADD CONSTRAINT `receipt_lines_po_line_id_foreign` FOREIGN KEY (`po_line_id`) REFERENCES `po_lines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receipt_lines_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receipt_lines_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `rmas`
--
ALTER TABLE `rmas`
  ADD CONSTRAINT `rmas_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `rmas_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `work_orders_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`),
  ADD CONSTRAINT `work_orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wo_materials`
--
ALTER TABLE `wo_materials`
  ADD CONSTRAINT `wo_materials_bin_location_id_foreign` FOREIGN KEY (`bin_location_id`) REFERENCES `bin_locations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wo_materials_bom_line_id_foreign` FOREIGN KEY (`bom_line_id`) REFERENCES `bom_lines` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wo_materials_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`),
  ADD CONSTRAINT `wo_materials_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wo_operations`
--
ALTER TABLE `wo_operations`
  ADD CONSTRAINT `wo_operations_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
