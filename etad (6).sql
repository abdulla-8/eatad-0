-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 09:25 PM
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
-- Database: `etad`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@etad.com', NULL, '$2y$12$MuJLjVjuMila0TQ36Esgq./lJv/5RbYnbezvhrpnfmbtfqzUv5Nda', 1, NULL, '2025-06-06 23:54:39', '2025-06-06 23:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(5) NOT NULL,
  `direction` enum('ltr','rtl') DEFAULT 'ltr',
  `is_active` tinyint(1) DEFAULT 1,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `direction`, `is_active`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'العربية', 'ar', 'rtl', 1, 1, '2025-06-07 14:45:07', '2025-06-07 14:45:07'),
(2, 'English', 'en', 'ltr', 1, 0, '2025-06-07 14:45:07', '2025-06-07 14:45:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_06_235129_first_one', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7fZakb67mtBVtzFwTZ650cJYMrMd4iD2LHOMn2zv', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiM21TRFdKNzhMenRYNUlhUXpvSGN2QjZxeXdQRjV2NUxmOU9pVDdDYiI7czoxMzoibGFuZ3VhZ2VfY29kZSI7czoyOiJlbiI7czoxOToiY3VycmVudF9sYW5ndWFnZV9pZCI7aToyO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc3BlY2lhbGl6YXRpb25zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1749410723),
('oSr5eGQMLRzpternqGwC0819o7s666KGpQMCtwAP', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNGV3REFBWGNlQ2FIRHJCQ3JNck8wdmQyTmc2OGJDWEZwakFxMDRiUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMzoibGFuZ3VhZ2VfY29kZSI7czoyOiJlbiI7czoxOToiY3VycmVudF9sYW5ndWFnZV9pZCI7aToyO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vbGFuZ3VhZ2VzIjt9czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1749314595);

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `brand_name_ar` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`id`, `brand_name`, `brand_name_ar`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Toyota', 'تويوتا', 'brands/toyota.png', 1, 1, '2025-06-08 19:09:32', '2025-06-08 16:25:05'),
(2, 'BMW', 'بي إم دبليو', 'brands/bmw.png', 1, 2, '2025-06-08 19:09:32', '2025-06-08 16:24:46'),
(3, 'Mercedes', 'مرسيدس', 'brands/mercedes.png', 1, 3, '2025-06-08 19:09:32', '2025-06-08 16:24:46'),
(4, 'Audi', 'أودي', 'brands/audi.png', 1, 4, '2025-06-08 19:09:32', '2025-06-08 16:24:46'),
(5, 'Hyundai', 'هيونداي', 'brands/hyundai.png', 1, 5, '2025-06-08 19:09:32', '2025-06-08 16:24:46'),
(6, 'Kia', 'كيا', 'brands/kia.png', 1, 6, '2025-06-08 19:09:32', '2025-06-08 16:24:46'),
(7, 'Nissan', 'نيسان', 'brands/nissan.png', 1, 7, '2025-06-08 19:09:32', '2025-06-08 16:24:46'),
(8, 'Honda', 'هوندا', 'brands/honda.png', 1, 8, '2025-06-08 19:09:32', '2025-06-08 16:24:46'),
(9, 'Ford', 'فورد', 'brands/ford.png', 1, 9, '2025-06-08 19:09:32', '2025-06-08 16:24:46'),
(10, 'Chevrolet', 'شيفروليه', 'brands/chevrolet.png', 1, 10, '2025-06-08 19:09:32', '2025-06-08 16:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` bigint(20) NOT NULL,
  `language_id` bigint(20) NOT NULL,
  `translation_key` varchar(255) NOT NULL,
  `translation_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`id`, `language_id`, `translation_key`, `translation_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin.dashboard', 'لوحة التحكم', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(2, 1, 'admin.welcome', 'مرحباً', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(3, 1, 'admin.site_name', 'نظام الاداره', '2025-06-07 15:29:30', '2025-06-07 13:01:21'),
(4, 1, 'admin.admin_panel', 'لوحة الإدارة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(5, 1, 'admin.administrator', 'مدير النظام', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(6, 1, 'admin.login', 'تسجيل الدخول', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(7, 1, 'admin.logout', 'تسجيل الخروج', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(8, 1, 'admin.email', 'البريد الإلكتروني', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(9, 1, 'admin.password', 'كلمة المرور', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(10, 1, 'admin.remember_me', 'تذكرني', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(11, 1, 'admin.all_rights_reserved', 'جميع الحقوق محفوظة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(12, 1, 'admin.admin_logged_in', 'تم تسجيل دخول المدير', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(13, 1, 'admin.languages', 'اللغات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(14, 1, 'admin.language_management', 'إدارة اللغات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(15, 1, 'admin.manage_languages', 'إدارة اللغات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(16, 1, 'admin.language_name', 'اسم اللغة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(17, 1, 'admin.language_code', 'رمز اللغة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(18, 1, 'admin.direction', 'الاتجاه', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(19, 1, 'admin.text_direction', 'اتجاه النص', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(20, 1, 'admin.current_language', 'اللغة الحالية', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(21, 1, 'admin.total_languages', 'إجمالي اللغات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(22, 1, 'admin.switch_language', 'تغيير اللغة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(23, 1, 'admin.switch_to', 'التبديل إلى', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(24, 1, 'admin.language_info', 'معلومات اللغة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(25, 1, 'admin.language_info_desc', 'إدارة لغات النظام وإعداداتها', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(26, 1, 'admin.switch_language_desc', 'تغيير لغة واجهة المستخدم الحالية', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(27, 1, 'admin.no_languages', 'لا توجد لغات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(28, 1, 'admin.no_languages_desc', 'ابدأ بإضافة لغات إلى النظام', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(29, 1, 'admin.translations', 'الترجمات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(30, 1, 'admin.translation_management', 'إدارة الترجمات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(31, 1, 'admin.manage_translations', 'إدارة الترجمات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(32, 1, 'admin.translation_group', 'مجموعة الترجمة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(33, 1, 'admin.translation_key', 'مفتاح الترجمة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(34, 1, 'admin.translation_value', 'قيمة الترجمة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(35, 1, 'admin.add_translation', 'إضافة ترجمة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(36, 1, 'admin.search_translations', 'البحث في الترجمات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(37, 1, 'admin.no_translations', 'لا توجد ترجمات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(38, 1, 'admin.no_translations_desc', 'ابدأ بإضافة ترجمات جديدة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(39, 1, 'admin.save', 'حفظ', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(40, 1, 'admin.edit', 'تعديل', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(41, 1, 'admin.delete', 'حذف', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(42, 1, 'admin.add', 'إضافة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(43, 1, 'admin.cancel', 'إلغاء', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(44, 1, 'admin.actions', 'الإجراءات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(45, 1, 'admin.filter', 'تصفية', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(46, 1, 'admin.reset', 'إعادة تعيين', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(47, 1, 'admin.search', 'بحث', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(48, 1, 'admin.all', 'الكل', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(49, 1, 'admin.total', 'المجموع', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(50, 1, 'admin.active', 'نشط', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(51, 1, 'admin.inactive', 'غير نشط', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(52, 1, 'admin.activate', 'تفعيل', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(53, 1, 'admin.deactivate', 'إلغاء التفعيل', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(54, 1, 'admin.status', 'الحالة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(55, 1, 'admin.default', 'افتراضي', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(56, 1, 'admin.current', 'حالي', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(57, 1, 'admin.system_status', 'حالة النظام', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(58, 1, 'admin.default_language', 'اللغة الافتراضية', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(59, 1, 'admin.default_language_desc', 'تعيين اللغة الافتراضية للنظام', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(60, 1, 'admin.set_default', 'تعيين كافتراضي', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(61, 1, 'admin.confirm_delete', 'هل أنت متأكد من الحذف؟', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(62, 1, 'admin.confirm_default', 'تعيين كلغة افتراضية؟', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(63, 1, 'admin.welcome_message', 'مرحباً بك في لوحة التحكم', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(64, 1, 'admin.dashboard_description', 'إدارة النظام والمحتوى', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(65, 1, 'admin.languages_loaded', 'تم تحميل اللغات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(66, 1, 'admin.system_initialized', 'تم تشغيل النظام', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(67, 1, 'admin.coming_soon', 'قريباً', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(68, 1, 'admin.quick_actions', 'الإجراءات السريعة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(69, 1, 'admin.recent_activity', 'النشاط الأخير', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(70, 1, 'admin.manage_users', 'إدارة المستخدمين', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(71, 1, 'admin.system', 'النظام', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(72, 1, 'admin.system_test', 'اختبار النظام', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(73, 2, 'admin.dashboard', 'Dashboard', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(74, 2, 'admin.welcome', 'Welcome', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(75, 2, 'admin.site_name', 'Etad Admin System', '2025-06-07 15:29:30', '2025-06-07 13:04:05'),
(76, 2, 'admin.admin_panel', 'Admin Panel', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(77, 2, 'admin.administrator', 'Administrator', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(78, 2, 'admin.login', 'Login', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(79, 2, 'admin.logout', 'Logout', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(80, 2, 'admin.email', 'Email', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(81, 2, 'admin.password', 'Password', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(82, 2, 'admin.remember_me', 'Remember Me', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(83, 2, 'admin.all_rights_reserved', 'All rights reserved', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(84, 2, 'admin.admin_logged_in', 'Admin logged in', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(85, 2, 'admin.languages', 'Languages', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(86, 2, 'admin.language_management', 'Language Management', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(87, 2, 'admin.manage_languages', 'Manage Languages', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(88, 2, 'admin.language_name', 'Language Name', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(89, 2, 'admin.language_code', 'Language Code', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(90, 2, 'admin.direction', 'Direction', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(91, 2, 'admin.text_direction', 'Text Direction', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(92, 2, 'admin.current_language', 'Current Language', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(93, 2, 'admin.total_languages', 'Total Languages', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(94, 2, 'admin.switch_language', 'Switch Language', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(95, 2, 'admin.switch_to', 'Switch to', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(96, 2, 'admin.language_info', 'Language Info', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(97, 2, 'admin.language_info_desc', 'Manage system languages and their settings', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(98, 2, 'admin.switch_language_desc', 'Change the current interface language', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(99, 2, 'admin.no_languages', 'No Languages Found', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(100, 2, 'admin.no_languages_desc', 'Start by adding languages to the system', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(101, 2, 'admin.translations', 'Translations', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(102, 2, 'admin.translation_management', 'Translation Management', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(103, 2, 'admin.manage_translations', 'Manage Translations', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(104, 2, 'admin.translation_group', 'Translation Group', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(105, 2, 'admin.translation_key', 'Translation Key', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(106, 2, 'admin.translation_value', 'Translation Value', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(107, 2, 'admin.add_translation', 'Add Translation', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(108, 2, 'admin.search_translations', 'Search Translations', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(109, 2, 'admin.no_translations', 'No Translations Found', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(110, 2, 'admin.no_translations_desc', 'Start by adding new translations', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(111, 2, 'admin.save', 'Save', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(112, 2, 'admin.edit', 'Edit', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(113, 2, 'admin.delete', 'Delete', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(114, 2, 'admin.add', 'Add', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(115, 2, 'admin.cancel', 'Cancel', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(116, 2, 'admin.actions', 'Actions', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(117, 2, 'admin.filter', 'Filter', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(118, 2, 'admin.reset', 'Reset', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(119, 2, 'admin.search', 'Search', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(120, 2, 'admin.all', 'All', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(121, 2, 'admin.total', 'Total', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(122, 2, 'admin.active', 'Active', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(123, 2, 'admin.inactive', 'Inactive', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(124, 2, 'admin.activate', 'Activate', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(125, 2, 'admin.deactivate', 'Deactivate', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(126, 2, 'admin.status', 'Status', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(127, 2, 'admin.default', 'Default', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(128, 2, 'admin.current', 'Current', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(129, 2, 'admin.system_status', 'System Status', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(130, 2, 'admin.default_language', 'Default Language', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(131, 2, 'admin.default_language_desc', 'Set the default language for the system', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(132, 2, 'admin.set_default', 'Set as Default', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(133, 2, 'admin.confirm_delete', 'Are you sure you want to delete?', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(134, 2, 'admin.confirm_default', 'Set as default language?', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(135, 2, 'admin.welcome_message', 'Welcome to the dashboard', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(136, 2, 'admin.dashboard_description', 'Manage your system and content', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(137, 2, 'admin.languages_loaded', 'Languages loaded', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(138, 2, 'admin.system_initialized', 'System initialized', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(139, 2, 'admin.coming_soon', 'Coming Soon', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(140, 2, 'admin.quick_actions', 'Quick Actions', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(141, 2, 'admin.recent_activity', 'Recent Activity', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(142, 2, 'admin.manage_users', 'Manage Users', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(143, 2, 'admin.system', 'System', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(144, 2, 'admin.system_test', 'System Test', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(145, 1, 'language', 'اللغة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(146, 1, 'welcome', 'مرحباً', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(147, 1, 'login', 'تسجيل الدخول', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(148, 1, 'logout', 'تسجيل الخروج', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(149, 1, 'dashboard', 'لوحة التحكم', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(150, 1, 'languages', 'اللغات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(151, 1, 'translations', 'الترجمات', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(152, 1, 'site_name', 'عتاد المورد', '2025-06-07 15:29:30', '2025-06-07 13:01:38'),
(153, 1, 'email', 'البريد الإلكتروني', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(154, 1, 'password', 'كلمة المرور', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(155, 1, 'save', 'حفظ', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(156, 1, 'edit', 'تعديل', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(157, 1, 'delete', 'حذف', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(158, 1, 'add', 'إضافة', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(159, 1, 'cancel', 'إلغاء', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(160, 1, 'active', 'نشط', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(161, 1, 'inactive', 'غير نشط', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(162, 2, 'language', 'Language', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(163, 2, 'welcome', 'Welcome', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(164, 2, 'login', 'Login', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(165, 2, 'logout', 'Logout', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(166, 2, 'dashboard', 'Dashboard', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(167, 2, 'languages', 'Languages', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(168, 2, 'translations', 'Translations', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(169, 2, 'site_name', 'Etad El Mored', '2025-06-07 15:29:30', '2025-06-07 13:04:04'),
(170, 2, 'email', 'Email', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(171, 2, 'password', 'Password', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(172, 2, 'save', 'Save', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(173, 2, 'edit', 'Edit', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(174, 2, 'delete', 'Delete', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(175, 2, 'add', 'Add', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(176, 2, 'cancel', 'Cancel', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(177, 2, 'active', 'Active', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(178, 2, 'inactive', 'Inactive', '2025-06-07 15:29:30', '2025-06-07 15:29:30'),
(179, 1, 'admin.specializations', 'التخصصات', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(180, 1, 'admin.specializations_management', 'إدارة التخصصات', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(181, 1, 'admin.add_specialization', 'إضافة تخصص', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(182, 1, 'admin.edit_specialization', 'تعديل التخصص', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(183, 1, 'admin.specialization_details', 'تفاصيل التخصص', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(184, 1, 'admin.specialization_created', 'تم إضافة التخصص بنجاح', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(185, 1, 'admin.specialization_updated', 'تم تحديث التخصص بنجاح', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(186, 1, 'admin.specialization_deleted', 'تم حذف التخصص بنجاح', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(187, 1, 'admin.specialization_activated', 'تم تفعيل التخصص', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(188, 1, 'admin.specialization_deactivated', 'تم إلغاء تفعيل التخصص', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(189, 1, 'admin.no_specializations', 'لا توجد تخصصات', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(190, 1, 'admin.no_specializations_desc', 'ابدأ بإضافة براندات السيارات للنظام', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(191, 1, 'admin.specialization_info', 'معلومات التخصص', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(192, 1, 'admin.specialization_info_desc', 'يمكنك إدارة براندات السيارات وترتيبها حسب الأولوية', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(193, 1, 'admin.sort_order_info', 'ترتيب العرض', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(194, 1, 'admin.sort_order_info_desc', 'يمكنك سحب وإفلات البراندات لإعادة ترتيبها', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(195, 1, 'admin.image_upload', 'رفع الصور', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(196, 1, 'admin.image_upload_desc', 'ارفع صور بجودة عالية لأفضل عرض للبراندات', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(197, 1, 'admin.car_brands', 'براندات السيارات', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(198, 1, 'admin.brand_name', 'اسم البراند (إنجليزي)', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(199, 1, 'admin.brand_name_ar', 'اسم البراند (عربي)', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(200, 1, 'admin.brand_name_placeholder', 'مثال: Toyota', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(201, 1, 'admin.brand_name_ar_placeholder', 'مثال: تويوتا', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(202, 1, 'admin.brand_image', 'صورة البراند', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(203, 1, 'admin.sort_order', 'ترتيب العرض', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(204, 1, 'admin.sort_order_placeholder', '0', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(205, 1, 'admin.sort_order_help', 'اتركه فارغاً أو 0 للترتيب التلقائي', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(206, 1, 'admin.order', 'ترتيب', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(207, 1, 'admin.image', 'الصورة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(208, 1, 'admin.current_image', 'الصورة الحالية', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(209, 1, 'admin.new_image_preview', 'معاينة الصورة الجديدة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(210, 1, 'admin.image_preview', 'معاينة الصورة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(211, 1, 'admin.upload_image', 'رفع صورة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(212, 1, 'admin.upload_file', 'رفع ملف', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(213, 1, 'admin.choose_file', 'اختر ملف', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(214, 1, 'admin.drag_drop_or', 'أو اسحب واتركه هنا', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(215, 1, 'admin.or_drag_drop', 'أو اسحب واترك', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(216, 1, 'admin.change_image', 'تغيير الصورة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(217, 1, 'admin.upload_new_replace', 'ارفع صورة جديدة لاستبدال الحالية', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(218, 1, 'admin.leave_empty_keep_current', 'اتركه فارغاً للاحتفاظ بالصورة الحالية', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(219, 1, 'admin.supported_formats', 'التنسيقات المدعومة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(220, 1, 'admin.max_size', 'الحد الأقصى', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(221, 1, 'admin.up_to', 'حتى', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(222, 1, 'admin.file_too_large', 'حجم الملف كبير جداً. الحد الأقصى 2 ميجابايت', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(223, 1, 'admin.invalid_file_type', 'نوع الملف غير مدعوم. استخدم JPG, PNG أو GIF', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(224, 1, 'admin.form_guidelines', 'إرشادات النموذج', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(225, 1, 'admin.form_help', 'مساعدة النموذج', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(226, 1, 'admin.form_help_brand_names', 'أدخل اسم البراند بالإنجليزية والعربية', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(227, 1, 'admin.form_help_sort_order', 'الترقيم الأصغر يظهر أولاً', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(228, 1, 'admin.form_help_status', 'فقط البراندات النشطة تظهر للمستخدمين', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(229, 1, 'admin.guideline_brand_names', 'أدخل أسماء البراندات بوضوح ودقة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(230, 1, 'admin.guideline_sort_order', 'استخدم أرقام متسلسلة للترتيب المنطقي', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(231, 1, 'admin.guideline_status', 'تأكد من تفعيل البراندات المطلوبة فقط', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(232, 1, 'admin.image_requirements', 'متطلبات الصورة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(233, 1, 'admin.image_req_format', 'التنسيق: PNG, JPG, GIF', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(234, 1, 'admin.image_req_size', 'الحجم: أقل من 2 ميجابايت', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(235, 1, 'admin.image_req_dimensions', 'الأبعاد المفضلة: 200x200 بكسل', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(236, 1, 'admin.req_format', 'صيغ الصور المدعومة: PNG, JPG, GIF', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(237, 1, 'admin.req_size', 'حجم أقصى: 2 ميجابايت', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(238, 1, 'admin.req_dimensions', 'أبعاد مفضلة: مربعة الشكل', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(239, 1, 'admin.tips', 'نصائح', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(240, 1, 'admin.tip_logo_quality', 'استخدم صور عالية الجودة للحصول على أفضل عرض', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(241, 1, 'admin.tip_consistent_naming', 'استخدم أسماء متسقة وواضحة للبراندات', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(242, 1, 'admin.tip_logical_order', 'رتب البراندات حسب الشهرة أو الأولوية', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(243, 1, 'admin.reset_form', 'إعادة تعيين النموذج', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(244, 1, 'admin.confirm_reset_form', 'هل أنت متأكد من إعادة تعيين النموذج؟ ستفقد جميع التغييرات', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(245, 1, 'admin.confirm_cancel', 'هل أنت متأكد من الإلغاء؟ ستفقد التغييرات غير المحفوظة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(246, 1, 'admin.saving', 'جاري الحفظ', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(247, 1, 'admin.draft_saved', 'تم حفظ المسودة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(248, 1, 'admin.draft_loaded', 'تم تحميل المسودة المحفوظة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(249, 1, 'admin.back_to_list', 'العودة للقائمة', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(250, 1, 'admin.add_new', 'إضافة جديد', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(251, 1, 'admin.update', 'تحديث', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(252, 1, 'admin.error_occurred', 'حدث خطأ أثناء العملية', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(266, 2, 'admin.specializations', 'Specializations', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(267, 2, 'admin.specializations_management', 'Specializations Management', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(268, 2, 'admin.add_specialization', 'Add Specialization', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(269, 2, 'admin.edit_specialization', 'Edit Specialization', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(270, 2, 'admin.specialization_details', 'Specialization Details', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(271, 2, 'admin.specialization_created', 'Specialization created successfully', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(272, 2, 'admin.specialization_updated', 'Specialization updated successfully', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(273, 2, 'admin.specialization_deleted', 'Specialization deleted successfully', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(274, 2, 'admin.specialization_activated', 'Specialization activated', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(275, 2, 'admin.specialization_deactivated', 'Specialization deactivated', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(276, 2, 'admin.no_specializations', 'No Specializations', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(277, 2, 'admin.no_specializations_desc', 'Start by adding car brands to the system', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(278, 2, 'admin.specialization_info', 'Specialization Info', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(279, 2, 'admin.specialization_info_desc', 'You can manage car brands and arrange them by priority', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(280, 2, 'admin.sort_order_info', 'Display Order', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(281, 2, 'admin.sort_order_info_desc', 'You can drag and drop brands to reorder them', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(282, 2, 'admin.image_upload', 'Image Upload', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(283, 2, 'admin.image_upload_desc', 'Upload high-quality images for better brand display', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(284, 2, 'admin.car_brands', 'Car Brands', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(285, 2, 'admin.brand_name', 'Brand Name (English)', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(286, 2, 'admin.brand_name_ar', 'Brand Name (Arabic)', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(287, 2, 'admin.brand_name_placeholder', 'Example: Toyota', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(288, 2, 'admin.brand_name_ar_placeholder', 'Example: تويوتا', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(289, 2, 'admin.brand_image', 'Brand Image', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(290, 2, 'admin.sort_order', 'Sort Order', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(291, 2, 'admin.sort_order_placeholder', '0', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(292, 2, 'admin.sort_order_help', 'Leave empty or 0 for automatic ordering', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(293, 2, 'admin.order', 'Order', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(294, 2, 'admin.image', 'Image', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(295, 2, 'admin.current_image', 'Current Image', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(296, 2, 'admin.new_image_preview', 'New Image Preview', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(297, 2, 'admin.image_preview', 'Image Preview', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(298, 2, 'admin.upload_image', 'Upload Image', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(299, 2, 'admin.upload_file', 'Upload File', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(300, 2, 'admin.choose_file', 'Choose File', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(301, 2, 'admin.drag_drop_or', 'or drag and drop here', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(302, 2, 'admin.or_drag_drop', 'or drag and drop', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(303, 2, 'admin.change_image', 'Change Image', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(304, 2, 'admin.upload_new_replace', 'Upload new image to replace current one', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(305, 2, 'admin.leave_empty_keep_current', 'Leave empty to keep current image', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(306, 2, 'admin.supported_formats', 'Supported Formats', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(307, 2, 'admin.max_size', 'Max Size', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(308, 2, 'admin.up_to', 'up to', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(309, 2, 'admin.file_too_large', 'File size is too large. Maximum size is 2MB', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(310, 2, 'admin.invalid_file_type', 'Invalid file type. Use JPG, PNG or GIF', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(311, 2, 'admin.form_guidelines', 'Form Guidelines', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(312, 2, 'admin.form_help', 'Form Help', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(313, 2, 'admin.form_help_brand_names', 'Enter brand name in both English and Arabic', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(314, 2, 'admin.form_help_sort_order', 'Lower numbers appear first', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(315, 2, 'admin.form_help_status', 'Only active brands are shown to users', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(316, 2, 'admin.guideline_brand_names', 'Enter brand names clearly and accurately', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(317, 2, 'admin.guideline_sort_order', 'Use sequential numbers for logical ordering', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(318, 2, 'admin.guideline_status', 'Make sure to activate only required brands', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(319, 2, 'admin.image_requirements', 'Image Requirements', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(320, 2, 'admin.image_req_format', 'Format: PNG, JPG, GIF', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(321, 2, 'admin.image_req_size', 'Size: Less than 2MB', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(322, 2, 'admin.image_req_dimensions', 'Preferred dimensions: 200x200 pixels', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(323, 2, 'admin.req_format', 'Supported image formats: PNG, JPG, GIF', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(324, 2, 'admin.req_size', 'Maximum size: 2MB', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(325, 2, 'admin.req_dimensions', 'Preferred dimensions: Square format', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(326, 2, 'admin.tips', 'Tips', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(327, 2, 'admin.tip_logo_quality', 'Use high-quality images for best display', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(328, 2, 'admin.tip_consistent_naming', 'Use consistent and clear brand names', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(329, 2, 'admin.tip_logical_order', 'Order brands by popularity or priority', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(330, 2, 'admin.reset_form', 'Reset Form', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(331, 2, 'admin.confirm_reset_form', 'Are you sure you want to reset the form? You will lose all changes', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(332, 2, 'admin.confirm_cancel', 'Are you sure you want to cancel? You will lose unsaved changes', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(333, 2, 'admin.saving', 'Saving', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(334, 2, 'admin.draft_saved', 'Draft Saved', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(335, 2, 'admin.draft_loaded', 'Saved draft loaded', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(336, 2, 'admin.back_to_list', 'Back to List', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(337, 2, 'admin.add_new', 'Add New', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(338, 2, 'admin.update', 'Update', '2025-06-08 19:23:16', '2025-06-08 19:23:16'),
(339, 2, 'admin.error_occurred', 'An error occurred during the operation', '2025-06-08 19:23:16', '2025-06-08 19:23:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `specializations_is_active_index` (`is_active`),
  ADD KEY `specializations_sort_order_index` (`sort_order`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_translation` (`language_id`,`translation_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
