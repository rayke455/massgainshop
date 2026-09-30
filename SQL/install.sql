-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 01:49 PM
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
-- Database: `smm_5`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `image_driver` varchar(50) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `admin_access` text DEFAULT NULL,
  `last_login` varchar(50) DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `two_fa` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=>inactive, 1=>active',
  `two_fa_verify` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=>inactive, 1=>active',
  `two_fa_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `email`, `password`, `image`, `image_driver`, `phone`, `address`, `admin_access`, `last_login`, `last_seen`, `status`, `remember_token`, `two_fa`, `two_fa_verify`, `two_fa_code`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '$2y$10$SO42YHlHb5T4g0w3fdqO3OKhz/AnUPiY7xtn1pN1UmGK0466gGETm', 'adminProfileImage/M8OqVZo7MGBPLQU7TLMGZoT712hSfz.webp', 'local', '+4455541455', 'NY City, USA', NULL, '2025-10-26 18:27:18', '2025-10-26 18:49:14', 1, 'FzrqZbtORSL3edQWh023HKMbLp7mGQh2gpORnnbjhsHSKP5CY2vGfLGqbyPP', 0, 1, 'SA2XZB4NTSNALHKZ', NULL, '2025-10-26 12:49:14');

-- --------------------------------------------------------

--
-- Table structure for table `api_providers`
--

CREATE TABLE `api_providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `api_name` varchar(191) DEFAULT NULL,
  `url` varchar(191) DEFAULT NULL,
  `api_key` varchar(191) DEFAULT NULL,
  `balance` double(8,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(191) DEFAULT NULL,
  `conversion_rate` double(18,8) NOT NULL DEFAULT 0.00000000,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_driver` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0:inactive, 1:active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `image`, `image_driver`, `status`, `created_at`, `updated_at`) VALUES
(1, 'blog/km09l8i4tKYofMkcTQ0tZHXtBCZxYY.webp', 'local', 1, '2024-08-30 12:45:11', '2024-10-01 20:17:36'),
(2, 'blog/THqaDKUZB00Ca1xKHi4QEYPscMW0Wz.webp', 'local', 1, '2024-08-30 14:01:57', '2024-08-30 14:01:57'),
(3, 'blog/mZmD4vDGOfqk2J2hxj4UuRgyyI93sb.webp', 'local', 1, '2024-11-06 22:31:20', '2024-11-06 22:31:20');

-- --------------------------------------------------------

--
-- Table structure for table `author_details`
--

CREATE TABLE `author_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `social_media` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `author_details`
--

INSERT INTO `author_details` (`id`, `author_id`, `language_id`, `name`, `slug`, `address`, `description`, `social_media`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Harry Porter', 'harry-porter', 'London, United Kingdom', '<p>Harry Porter is a blog author known for his insightful and engaging content. He often writes about technology, programming, and web development, providing practical solutions and tutorials for developers. His expertise spans areas like Laravel, Vue.js, and various web frameworks, making his articles valuable for both beginners and experienced developers.</p>', '[{\"social_media_name\":\"Facebook\",\"icon\":\"fab fa-accessible-icon\",\"link\":\"https:\\/\\/facebook.com\\/\"},{\"social_media_name\":\"Twitter\",\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/\"},{\"social_media_name\":\"Linkedin\",\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"},{\"social_media_name\":\"Instagram\",\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}]', '2024-08-30 12:45:11', '2024-10-01 20:19:02'),
(2, 2, 1, 'Jubayer Islam', 'jubayer-islam', 'Dhaka, Bangladesh', '<p>Jubayer Islam is a blog author known for his insightful and engaging content. He often writes about technology, programming, and web development, providing practical solutions and tutorials for developers. His expertise spans areas like Laravel, Vue.js, and various web frameworks, making his articles valuable for both beginners and experienced developers.</p>', '[{\"social_media_name\":\"Facebook\",\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/facebook.com\\/\"},{\"social_media_name\":\"Twitter\",\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/\"},{\"social_media_name\":\"Linkedin\",\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"},{\"social_media_name\":\"Instagram\",\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}]', '2024-08-30 14:01:57', '2024-10-29 20:41:40'),
(8, 3, 1, 'Hermione Granger', 'hermione-granger', 'Dublin, Ireland', '<p>Passionate about transforming complex ideas into engaging stories, Hermione Granger is a skilled blog writer with a knack for creating content that resonates with readers.A voracious reader and meticulous researcher, they have a talent for weaving facts into compelling narratives that inform and enchant.</p>', '[{\"social_media_name\":\"Facebook\",\"icon\":\"fab fa-accessible-icon\",\"link\":\"https:\\/\\/facebook.com\\/\"},{\"social_media_name\":\"Twitter\",\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/\"},{\"social_media_name\":\"Linkedin\",\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"},{\"social_media_name\":\"Instagram\",\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}]', '2024-11-06 22:31:20', '2024-11-06 22:31:20'),
(9, 3, 2, 'Hermione Granger', 'hermione-granger', 'Dublin, Ireland', '<p>Apasionada por transformar ideas complejas en historias atractivas, Hermione Granger es una hábil escritora de blogs con una habilidad especial para crear contenido que resuene en los lectores. Lectora voraz e investigadora meticulosa, tiene talento para entrelazar hechos en narrativas convincentes que informan y encantan.</p>', '[{\"social_media_name\":\"Facebook\",\"icon\":\"fab fa-accessible-icon\",\"link\":\"https:\\/\\/facebook.com\\/\"},{\"social_media_name\":\"Twitter\",\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/\"},{\"social_media_name\":\"Linkedin\",\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"},{\"social_media_name\":\"Instagram\",\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}]', '2024-11-06 22:32:50', '2024-11-06 22:34:18'),
(10, 2, 2, 'Jubayer Islam', 'jubayer-islam', 'Dhaka, Bangladesh', '<p>Jubayer Islam es un autor de blog conocido por su contenido revelador y atractivo. A menudo escribe sobre tecnología, programación y desarrollo web, brindando soluciones prácticas y tutoriales para desarrolladores. Su experiencia abarca áreas como Laravel, Vue.js y varios marcos web, lo que hace que sus artículos sean valiosos tanto para principiantes como para desarrolladores experimentados.</p>', '[{\"social_media_name\":\"Facebook\",\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/facebook.com\\/\"},{\"social_media_name\":\"Twitter\",\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/\"},{\"social_media_name\":\"Linkedin\",\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"},{\"social_media_name\":\"Instagram\",\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}]', '2024-11-06 22:44:15', '2024-11-06 22:44:15'),
(11, 1, 2, 'Harry Porter', 'harry-porter', 'London, United Kingdom', '<p>Harry Porter es un autor de blogs conocido por su contenido interesante y revelador. A menudo escribe sobre tecnología, programación y desarrollo web, brindando soluciones prácticas y tutoriales para desarrolladores. Su experiencia abarca áreas como Laravel, Vue.js y varios marcos web, lo que hace que sus artículos sean valiosos tanto para principiantes como para desarrolladores experimentados.</p>', '[{\"social_media_name\":\"Facebook\",\"icon\":\"fab fa-accessible-icon\",\"link\":\"https:\\/\\/facebook.com\\/\"},{\"social_media_name\":\"Twitter\",\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/\"},{\"social_media_name\":\"Linkedin\",\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"},{\"social_media_name\":\"Instagram\",\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}]', '2024-11-06 22:46:50', '2024-11-06 22:46:50');

-- --------------------------------------------------------

--
-- Table structure for table `basic_controls`
--

CREATE TABLE `basic_controls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `user_dashboard` varchar(255) DEFAULT NULL,
  `site_title` varchar(255) DEFAULT NULL,
  `time_zone` varchar(50) DEFAULT NULL,
  `base_currency` varchar(20) DEFAULT NULL,
  `currency_symbol` varchar(20) DEFAULT NULL,
  `child_panel_price` double(8,2) NOT NULL DEFAULT 0.00,
  `admin_prefix` varchar(191) DEFAULT NULL,
  `is_currency_position` varchar(191) NOT NULL DEFAULT 'left' COMMENT 'left, right',
  `has_space_between_currency_and_amount` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => no space, 1 => space',
  `is_force_ssl` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active\r\n',
  `is_maintenance_mode` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => off, 1 => on',
  `paginate` int(11) DEFAULT NULL,
  `strong_password` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active',
  `registration` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => disable, 1 => enable',
  `fraction_number` int(11) DEFAULT NULL,
  `sender_email` varchar(255) DEFAULT NULL,
  `sender_email_name` varchar(255) DEFAULT NULL,
  `email_description` text DEFAULT NULL,
  `push_notification` tinyint(1) NOT NULL DEFAULT 0,
  `in_app_notification` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active',
  `email_notification` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active',
  `email_verification` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active',
  `sms_notification` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active',
  `sms_verification` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active',
  `tawk_id` varchar(255) DEFAULT NULL,
  `tawk_status` tinyint(1) NOT NULL DEFAULT 0,
  `fb_messenger_status` tinyint(1) NOT NULL DEFAULT 0,
  `fb_app_id` varchar(255) DEFAULT NULL,
  `fb_page_id` varchar(255) DEFAULT NULL,
  `manual_recaptcha` tinyint(1) DEFAULT 0 COMMENT '0 =>inactive, 1 => active ',
  `google_recaptcha` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=>inactive, 1 =>active',
  `manual_recaptcha_admin_login` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active ',
  `manual_recaptcha_login` tinyint(1) DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `manual_recaptcha_register` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `google_recaptcha_admin_login` tinyint(1) DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `google_recaptcha_login` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `google_recaptcha_register` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `measurement_id` varchar(255) DEFAULT NULL,
  `analytic_status` tinyint(1) DEFAULT NULL,
  `error_log` tinyint(1) DEFAULT NULL,
  `is_active_cron_notification` tinyint(1) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `logo_driver` varchar(20) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `favicon_driver` varchar(20) DEFAULT NULL,
  `admin_logo` varchar(255) DEFAULT NULL,
  `admin_logo_driver` varchar(20) DEFAULT NULL,
  `admin_dark_mode_logo` varchar(255) DEFAULT NULL,
  `admin_dark_mode_logo_driver` varchar(50) DEFAULT NULL,
  `currency_layer_access_key` varchar(255) DEFAULT NULL,
  `currency_layer_auto_update_at` varchar(255) DEFAULT NULL,
  `currency_layer_auto_update` varchar(1) DEFAULT NULL,
  `coin_market_cap_app_key` varchar(255) DEFAULT NULL,
  `coin_market_cap_auto_update_at` varchar(255) NOT NULL,
  `coin_market_cap_auto_update` tinyint(1) DEFAULT NULL,
  `deposit_commission` tinyint(4) DEFAULT 0 COMMENT '0 => active, 1 => deactive',
  `automatic_currency_update_permission` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active',
  `auto_currency_update` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => off, 1 => on',
  `date_time_format` varchar(255) DEFAULT NULL,
  `cookie_title` varchar(191) DEFAULT NULL,
  `cookie_sub_title` varchar(255) DEFAULT NULL,
  `cookie_url` varchar(255) DEFAULT NULL,
  `cookie_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: active, 0: inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `basic_controls`
--

INSERT INTO `basic_controls` (`id`, `theme`, `user_dashboard`, `site_title`, `time_zone`, `base_currency`, `currency_symbol`, `child_panel_price`, `admin_prefix`, `is_currency_position`, `has_space_between_currency_and_amount`, `is_force_ssl`, `is_maintenance_mode`, `paginate`, `strong_password`, `registration`, `fraction_number`, `sender_email`, `sender_email_name`, `email_description`, `push_notification`, `in_app_notification`, `email_notification`, `email_verification`, `sms_notification`, `sms_verification`, `tawk_id`, `tawk_status`, `fb_messenger_status`, `fb_app_id`, `fb_page_id`, `manual_recaptcha`, `google_recaptcha`, `manual_recaptcha_admin_login`, `manual_recaptcha_login`, `manual_recaptcha_register`, `google_recaptcha_admin_login`, `google_recaptcha_login`, `google_recaptcha_register`, `measurement_id`, `analytic_status`, `error_log`, `is_active_cron_notification`, `logo`, `logo_driver`, `favicon`, `favicon_driver`, `admin_logo`, `admin_logo_driver`, `admin_dark_mode_logo`, `admin_dark_mode_logo_driver`, `currency_layer_access_key`, `currency_layer_auto_update_at`, `currency_layer_auto_update`, `coin_market_cap_app_key`, `coin_market_cap_auto_update_at`, `coin_market_cap_auto_update`, `deposit_commission`, `automatic_currency_update_permission`, `auto_currency_update`, `date_time_format`, `cookie_title`, `cookie_sub_title`, `cookie_url`, `cookie_status`, `created_at`, `updated_at`) VALUES
(1, 'light_green', 'user_dashboard_style_one', 'SMM Matrix', 'Asia/Dhaka', 'USD', '$', 0.00, 'admin', 'right', 1, 0, 0, 20, 0, 1, 2, 'support@achi.com', 'Bug Admin', '<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n<meta name=\"viewport\" content=\"width=device-width\">\r\n<style type=\"text/css\">\r\n    @media only screen and (min-width: 620px) {\r\n        * [lang=x-wrapper] h1 {\r\n        }\r\n\r\n        * [lang=x-wrapper] h1 {\r\n            font-size: 26px !important;\r\n            line-height: 34px !important\r\n        }\r\n\r\n        * [lang=x-wrapper] h2 {\r\n        }\r\n\r\n        * [lang=x-wrapper] h2 {\r\n            font-size: 20px !important;\r\n            line-height: 28px !important\r\n        }\r\n\r\n        * [lang=x-wrapper] h3 {\r\n        }\r\n\r\n        * [lang=x-layout__inner] p,\r\n        * [lang=x-layout__inner] ol,\r\n        * [lang=x-layout__inner] ul {\r\n        }\r\n\r\n        * div [lang=x-size-8] {\r\n            font-size: 8px !important;\r\n            line-height: 14px !important\r\n        }\r\n\r\n        * div [lang=x-size-9] {\r\n            font-size: 9px !important;\r\n            line-height: 16px !important\r\n        }\r\n\r\n        * div [lang=x-size-10] {\r\n            font-size: 10px !important;\r\n            line-height: 18px !important\r\n        }\r\n\r\n        * div [lang=x-size-11] {\r\n            font-size: 11px !important;\r\n            line-height: 19px !important\r\n        }\r\n\r\n        * div [lang=x-size-12] {\r\n            font-size: 12px !important;\r\n            line-height: 19px !important\r\n        }\r\n\r\n        * div [lang=x-size-13] {\r\n            font-size: 13px !important;\r\n            line-height: 21px !important\r\n        }\r\n\r\n        * div [lang=x-size-14] {\r\n            font-size: 14px !important;\r\n            line-height: 21px !important\r\n        }\r\n\r\n        * div [lang=x-size-15] {\r\n            font-size: 15px !important;\r\n            line-height: 23px !important\r\n        }\r\n\r\n        * div [lang=x-size-16] {\r\n            font-size: 16px !important;\r\n            line-height: 24px !important\r\n        }\r\n\r\n        * div [lang=x-size-17] {\r\n            font-size: 17px !important;\r\n            line-height: 26px !important\r\n        }\r\n\r\n        * div [lang=x-size-18] {\r\n            font-size: 18px !important;\r\n            line-height: 26px !important\r\n        }\r\n\r\n        * div [lang=x-size-18] {\r\n            font-size: 18px !important;\r\n            line-height: 26px !important\r\n        }\r\n\r\n        * div [lang=x-size-20] {\r\n            font-size: 20px !important;\r\n            line-height: 28px !important\r\n        }\r\n\r\n        * div [lang=x-size-22] {\r\n            font-size: 22px !important;\r\n            line-height: 31px !important\r\n        }\r\n\r\n        * div [lang=x-size-24] {\r\n            font-size: 24px !important;\r\n            line-height: 32px !important\r\n        }\r\n\r\n        * div [lang=x-size-26] {\r\n            font-size: 26px !important;\r\n            line-height: 34px !important\r\n        }\r\n\r\n        * div [lang=x-size-28] {\r\n            font-size: 28px !important;\r\n            line-height: 36px !important\r\n        }\r\n\r\n        * div [lang=x-size-30] {\r\n            font-size: 30px !important;\r\n            line-height: 38px !important\r\n        }\r\n\r\n        * div [lang=x-size-32] {\r\n            font-size: 32px !important;\r\n            line-height: 40px !important\r\n        }\r\n\r\n        * div [lang=x-size-34] {\r\n            font-size: 34px !important;\r\n            line-height: 43px !important\r\n        }\r\n\r\n        * div [lang=x-size-36] {\r\n            font-size: 36px !important;\r\n            line-height: 43px !important\r\n        }\r\n\r\n        * div [lang=x-size-40] {\r\n            font-size: 40px !important;\r\n            line-height: 47px !important\r\n        }\r\n\r\n        * div [lang=x-size-44] {\r\n            font-size: 44px !important;\r\n            line-height: 50px !important\r\n        }\r\n\r\n        * div [lang=x-size-48] {\r\n            font-size: 48px !important;\r\n            line-height: 54px !important\r\n        }\r\n\r\n        * div [lang=x-size-56] {\r\n            font-size: 56px !important;\r\n            line-height: 60px !important\r\n        }\r\n\r\n        * div [lang=x-size-64] {\r\n            font-size: 64px !important;\r\n            line-height: 63px !important\r\n        }\r\n    }\r\n</style>\r\n<style type=\"text/css\">\r\n    body {\r\n        margin: 0;\r\n        padding: 0;\r\n    }\r\n\r\n    table {\r\n        border-collapse: collapse;\r\n        table-layout: fixed;\r\n    }\r\n\r\n    * {\r\n        line-height: inherit;\r\n    }\r\n\r\n    [x-apple-data-detectors],\r\n    [href^=\"tel\"],\r\n    [href^=\"sms\"] {\r\n        color: inherit !important;\r\n        text-decoration: none !important;\r\n    }\r\n\r\n    .wrapper .footer__share-button a:hover,\r\n    .wrapper .footer__share-button a:focus {\r\n        color: #ffffff !important;\r\n    }\r\n\r\n    .btn a:hover,\r\n    .btn a:focus,\r\n    .footer__share-button a:hover,\r\n    .footer__share-button a:focus,\r\n    .email-footer__links a:hover,\r\n    .email-footer__links a:focus {\r\n        opacity: 0.8;\r\n    }\r\n\r\n    .preheader,\r\n    .header,\r\n    .layout,\r\n    .column {\r\n        transition: width 0.25s ease-in-out, max-width 0.25s ease-in-out;\r\n    }\r\n\r\n    .layout,\r\n    .header {\r\n        max-width: 400px !important;\r\n        -fallback-width: 95% !important;\r\n        width: calc(100% - 20px) !important;\r\n    }\r\n\r\n    div.preheader {\r\n        max-width: 360px !important;\r\n        -fallback-width: 90% !important;\r\n        width: calc(100% - 60px) !important;\r\n    }\r\n\r\n    .snippet,\r\n    .webversion {\r\n        Float: none !important;\r\n    }\r\n\r\n    .column {\r\n        max-width: 400px !important;\r\n        width: 100% !important;\r\n    }\r\n\r\n    .fixed-width.has-border {\r\n        max-width: 402px !important;\r\n    }\r\n\r\n    .fixed-width.has-border .layout__inner {\r\n        box-sizing: border-box;\r\n    }\r\n\r\n    .snippet,\r\n    .webversion {\r\n        width: 50% !important;\r\n    }\r\n\r\n    .ie .btn {\r\n        width: 100%;\r\n    }\r\n\r\n    .ie .column,\r\n    [owa] .column,\r\n    .ie .gutter,\r\n    [owa] .gutter {\r\n        display: table-cell;\r\n        float: none !important;\r\n        vertical-align: top;\r\n    }\r\n\r\n    .ie div.preheader,\r\n    [owa] div.preheader,\r\n    .ie .email-footer,\r\n    [owa] .email-footer {\r\n        max-width: 560px !important;\r\n        width: 560px !important;\r\n    }\r\n\r\n    .ie .snippet,\r\n    [owa] .snippet,\r\n    .ie .webversion,\r\n    [owa] .webversion {\r\n        width: 280px !important;\r\n    }\r\n\r\n    .ie .header,\r\n    [owa] .header,\r\n    .ie .layout,\r\n    [owa] .layout,\r\n    .ie .one-col .column,\r\n    [owa] .one-col .column {\r\n        max-width: 600px !important;\r\n        width: 600px !important;\r\n    }\r\n\r\n    .ie .fixed-width.has-border,\r\n    [owa] .fixed-width.has-border,\r\n    .ie .has-gutter.has-border,\r\n    [owa] .has-gutter.has-border {\r\n        max-width: 602px !important;\r\n        width: 602px !important;\r\n    }\r\n\r\n    .ie .two-col .column,\r\n    [owa] .two-col .column {\r\n        width: 300px !important;\r\n    }\r\n\r\n    .ie .three-col .column,\r\n    [owa] .three-col .column,\r\n    .ie .narrow,\r\n    [owa] .narrow {\r\n        width: 200px !important;\r\n    }\r\n\r\n    .ie .wide,\r\n    [owa] .wide {\r\n        width: 400px !important;\r\n    }\r\n\r\n    .ie .two-col.has-gutter .column,\r\n    [owa] .two-col.x_has-gutter .column {\r\n        width: 290px !important;\r\n    }\r\n\r\n    .ie .three-col.has-gutter .column,\r\n    [owa] .three-col.x_has-gutter .column,\r\n    .ie .has-gutter .narrow,\r\n    [owa] .has-gutter .narrow {\r\n        width: 188px !important;\r\n    }\r\n\r\n    .ie .has-gutter .wide,\r\n    [owa] .has-gutter .wide {\r\n        width: 394px !important;\r\n    }\r\n\r\n    .ie .two-col.has-gutter.has-border .column,\r\n    [owa] .two-col.x_has-gutter.x_has-border .column {\r\n        width: 292px !important;\r\n    }\r\n\r\n    .ie .three-col.has-gutter.has-border .column,\r\n    [owa] .three-col.x_has-gutter.x_has-border .column,\r\n    .ie .has-gutter.has-border .narrow,\r\n    [owa] .has-gutter.x_has-border .narrow {\r\n        width: 190px !important;\r\n    }\r\n\r\n    .ie .has-gutter.has-border .wide,\r\n    [owa] .has-gutter.x_has-border .wide {\r\n        width: 396px !important;\r\n    }\r\n\r\n    .ie .fixed-width .layout__inner {\r\n        border-left: 0 none white !important;\r\n        border-right: 0 none white !important;\r\n    }\r\n\r\n    .ie .layout__edges {\r\n        display: none;\r\n    }\r\n\r\n    .mso .layout__edges {\r\n        font-size: 0;\r\n    }\r\n\r\n    .layout-fixed-width,\r\n    .mso .layout-full-width {\r\n        background-color: #ffffff;\r\n    }\r\n\r\n    @media only screen and (min-width: 620px) {\r\n\r\n        .column,\r\n        .gutter {\r\n            display: table-cell;\r\n            Float: none !important;\r\n            vertical-align: top;\r\n        }\r\n\r\n        div.preheader,\r\n        .email-footer {\r\n            max-width: 560px !important;\r\n            width: 560px !important;\r\n        }\r\n\r\n        .snippet,\r\n        .webversion {\r\n            width: 280px !important;\r\n        }\r\n\r\n        .header,\r\n        .layout,\r\n        .one-col .column {\r\n            max-width: 600px !important;\r\n            width: 600px !important;\r\n        }\r\n\r\n        .fixed-width.has-border,\r\n        .fixed-width.ecxhas-border,\r\n        .has-gutter.has-border,\r\n        .has-gutter.ecxhas-border {\r\n            max-width: 602px !important;\r\n            width: 602px !important;\r\n        }\r\n\r\n        .two-col .column {\r\n            width: 300px !important;\r\n        }\r\n\r\n        .three-col .column,\r\n        .column.narrow {\r\n            width: 200px !important;\r\n        }\r\n\r\n        .column.wide {\r\n            width: 400px !important;\r\n        }\r\n\r\n        .two-col.has-gutter .column,\r\n        .two-col.ecxhas-gutter .column {\r\n            width: 290px !important;\r\n        }\r\n\r\n        .three-col.has-gutter .column,\r\n        .three-col.ecxhas-gutter .column,\r\n        .has-gutter .narrow {\r\n            width: 188px !important;\r\n        }\r\n\r\n        .has-gutter .wide {\r\n            width: 394px !important;\r\n        }\r\n\r\n        .two-col.has-gutter.has-border .column,\r\n        .two-col.ecxhas-gutter.ecxhas-border .column {\r\n            width: 292px !important;\r\n        }\r\n\r\n        .three-col.has-gutter.has-border .column,\r\n        .three-col.ecxhas-gutter.ecxhas-border .column,\r\n        .has-gutter.has-border .narrow,\r\n        .has-gutter.ecxhas-border .narrow {\r\n            width: 190px !important;\r\n        }\r\n\r\n        .has-gutter.has-border .wide,\r\n        .has-gutter.ecxhas-border .wide {\r\n            width: 396px !important;\r\n        }\r\n    }\r\n\r\n    @media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {\r\n        .fblike {\r\n            background-image: url(https://i3.createsend1.com/static/eb/customise/13-the-blueprint-3/images/fblike@2x.png) !important;\r\n        }\r\n\r\n        .tweet {\r\n            background-image: url(https://i4.createsend1.com/static/eb/customise/13-the-blueprint-3/images/tweet@2x.png) !important;\r\n        }\r\n\r\n        .linkedinshare {\r\n            background-image: url(https://i6.createsend1.com/static/eb/customise/13-the-blueprint-3/images/lishare@2x.png) !important;\r\n        }\r\n\r\n        .forwardtoafriend {\r\n            background-image: url(https://i5.createsend1.com/static/eb/customise/13-the-blueprint-3/images/forward@2x.png) !important;\r\n        }\r\n    }\r\n\r\n    @media (max-width: 321px) {\r\n        .fixed-width.has-border .layout__inner {\r\n            border-width: 1px 0 !important;\r\n        }\r\n\r\n        .layout,\r\n        .column {\r\n            min-width: 320px !important;\r\n            width: 320px !important;\r\n        }\r\n\r\n        .border {\r\n            display: none;\r\n        }\r\n    }\r\n\r\n    .mso div {\r\n        border: 0 none white !important;\r\n    }\r\n\r\n    .mso .w560 .divider {\r\n        margin-left: 260px !important;\r\n        margin-right: 260px !important;\r\n    }\r\n\r\n    .mso .w360 .divider {\r\n        margin-left: 160px !important;\r\n        margin-right: 160px !important;\r\n    }\r\n\r\n    .mso .w260 .divider {\r\n        margin-left: 110px !important;\r\n        margin-right: 110px !important;\r\n    }\r\n\r\n    .mso .w160 .divider {\r\n        margin-left: 60px !important;\r\n        margin-right: 60px !important;\r\n    }\r\n\r\n    .mso .w354 .divider {\r\n        margin-left: 157px !important;\r\n        margin-right: 157px !important;\r\n    }\r\n\r\n    .mso .w250 .divider {\r\n        margin-left: 105px !important;\r\n        margin-right: 105px !important;\r\n    }\r\n\r\n    .mso .w148 .divider {\r\n        margin-left: 54px !important;\r\n        margin-right: 54px !important;\r\n    }\r\n\r\n    .mso .font-avenir,\r\n    .mso .font-cabin,\r\n    .mso .font-open-sans,\r\n    .mso .font-ubuntu {\r\n        font-family: sans-serif !important;\r\n    }\r\n\r\n    .mso .font-bitter,\r\n    .mso .font-merriweather,\r\n    .mso .font-pt-serif {\r\n        font-family: Georgia, serif !important;\r\n    }\r\n\r\n    .mso .font-lato,\r\n    .mso .font-roboto {\r\n        font-family: Tahoma, sans-serif !important;\r\n    }\r\n\r\n    .mso .font-pt-sans {\r\n        font-family: \"Trebuchet MS\", sans-serif !important;\r\n    }\r\n\r\n    .mso .footer__share-button p {\r\n        margin: 0;\r\n    }\r\n\r\n    @media only screen and (min-width: 620px) {\r\n        .wrapper .size-8 {\r\n            font-size: 8px !important;\r\n            line-height: 14px !important;\r\n        }\r\n\r\n        .wrapper .size-9 {\r\n            font-size: 9px !important;\r\n            line-height: 16px !important;\r\n        }\r\n\r\n        .wrapper .size-10 {\r\n            font-size: 10px !important;\r\n            line-height: 18px !important;\r\n        }\r\n\r\n        .wrapper .size-11 {\r\n            font-size: 11px !important;\r\n            line-height: 19px !important;\r\n        }\r\n\r\n        .wrapper .size-12 {\r\n            font-size: 12px !important;\r\n            line-height: 19px !important;\r\n        }\r\n\r\n        .wrapper .size-13 {\r\n            font-size: 13px !important;\r\n            line-height: 21px !important;\r\n        }\r\n\r\n        .wrapper .size-14 {\r\n            font-size: 14px !important;\r\n            line-height: 21px !important;\r\n        }\r\n\r\n        .wrapper .size-15 {\r\n            font-size: 15px !important;\r\n            line-height: 23px !important;\r\n        }\r\n\r\n        .wrapper .size-16 {\r\n            font-size: 16px !important;\r\n            line-height: 24px !important;\r\n        }\r\n\r\n        .wrapper .size-17 {\r\n            font-size: 17px !important;\r\n            line-height: 26px !important;\r\n        }\r\n\r\n        .wrapper .size-18 {\r\n            font-size: 18px !important;\r\n            line-height: 26px !important;\r\n        }\r\n\r\n        .wrapper .size-20 {\r\n            font-size: 20px !important;\r\n            line-height: 28px !important;\r\n        }\r\n\r\n        .wrapper .size-22 {\r\n            font-size: 22px !important;\r\n            line-height: 31px !important;\r\n        }\r\n\r\n        .wrapper .size-24 {\r\n            font-size: 24px !important;\r\n            line-height: 32px !important;\r\n        }\r\n\r\n        .wrapper .size-26 {\r\n            font-size: 26px !important;\r\n            line-height: 34px !important;\r\n        }\r\n\r\n        .wrapper .size-28 {\r\n            font-size: 28px !important;\r\n            line-height: 36px !important;\r\n        }\r\n\r\n        .wrapper .size-30 {\r\n            font-size: 30px !important;\r\n            line-height: 38px !important;\r\n        }\r\n\r\n        .wrapper .size-32 {\r\n            font-size: 32px !important;\r\n            line-height: 40px !important;\r\n        }\r\n\r\n        .wrapper .size-34 {\r\n            font-size: 34px !important;\r\n            line-height: 43px !important;\r\n        }\r\n\r\n        .wrapper .size-36 {\r\n            font-size: 36px !important;\r\n            line-height: 43px !important;\r\n        }\r\n\r\n        .wrapper .size-40 {\r\n            font-size: 40px !important;\r\n            line-height: 47px !important;\r\n        }\r\n\r\n        .wrapper .size-44 {\r\n            font-size: 44px !important;\r\n            line-height: 50px !important;\r\n        }\r\n\r\n        .wrapper .size-48 {\r\n            font-size: 48px !important;\r\n            line-height: 54px !important;\r\n        }\r\n\r\n        .wrapper .size-56 {\r\n            font-size: 56px !important;\r\n            line-height: 60px !important;\r\n        }\r\n\r\n        .wrapper .size-64 {\r\n            font-size: 64px !important;\r\n            line-height: 63px !important;\r\n        }\r\n    }\r\n\r\n    .mso .size-8,\r\n    .ie .size-8 {\r\n        font-size: 8px !important;\r\n        line-height: 14px !important;\r\n    }\r\n\r\n    .mso .size-9,\r\n    .ie .size-9 {\r\n        font-size: 9px !important;\r\n        line-height: 16px !important;\r\n    }\r\n\r\n    .mso .size-10,\r\n    .ie .size-10 {\r\n        font-size: 10px !important;\r\n        line-height: 18px !important;\r\n    }\r\n\r\n    .mso .size-11,\r\n    .ie .size-11 {\r\n        font-size: 11px !important;\r\n        line-height: 19px !important;\r\n    }\r\n\r\n    .mso .size-12,\r\n    .ie .size-12 {\r\n        font-size: 12px !important;\r\n        line-height: 19px !important;\r\n    }\r\n\r\n    .mso .size-13,\r\n    .ie .size-13 {\r\n        font-size: 13px !important;\r\n        line-height: 21px !important;\r\n    }\r\n\r\n    .mso .size-14,\r\n    .ie .size-14 {\r\n        font-size: 14px !important;\r\n        line-height: 21px !important;\r\n    }\r\n\r\n    .mso .size-15,\r\n    .ie .size-15 {\r\n        font-size: 15px !important;\r\n        line-height: 23px !important;\r\n    }\r\n\r\n    .mso .size-16,\r\n    .ie .size-16 {\r\n        font-size: 16px !important;\r\n        line-height: 24px !important;\r\n    }\r\n\r\n    .mso .size-17,\r\n    .ie .size-17 {\r\n        font-size: 17px !important;\r\n        line-height: 26px !important;\r\n    }\r\n\r\n    .mso .size-18,\r\n    .ie .size-18 {\r\n        font-size: 18px !important;\r\n        line-height: 26px !important;\r\n    }\r\n\r\n    .mso .size-20,\r\n    .ie .size-20 {\r\n        font-size: 20px !important;\r\n        line-height: 28px !important;\r\n    }\r\n\r\n    .mso .size-22,\r\n    .ie .size-22 {\r\n        font-size: 22px !important;\r\n        line-height: 31px !important;\r\n    }\r\n\r\n    .mso .size-24,\r\n    .ie .size-24 {\r\n        font-size: 24px !important;\r\n        line-height: 32px !important;\r\n    }\r\n\r\n    .mso .size-26,\r\n    .ie .size-26 {\r\n        font-size: 26px !important;\r\n        line-height: 34px !important;\r\n    }\r\n\r\n    .mso .size-28,\r\n    .ie .size-28 {\r\n        font-size: 28px !important;\r\n        line-height: 36px !important;\r\n    }\r\n\r\n    .mso .size-30,\r\n    .ie .size-30 {\r\n        font-size: 30px !important;\r\n        line-height: 38px !important;\r\n    }\r\n\r\n    .mso .size-32,\r\n    .ie .size-32 {\r\n        font-size: 32px !important;\r\n        line-height: 40px !important;\r\n    }\r\n\r\n    .mso .size-34,\r\n    .ie .size-34 {\r\n        font-size: 34px !important;\r\n        line-height: 43px !important;\r\n    }\r\n\r\n    .mso .size-36,\r\n    .ie .size-36 {\r\n        font-size: 36px !important;\r\n        line-height: 43px !important;\r\n    }\r\n\r\n    .mso .size-40,\r\n    .ie .size-40 {\r\n        font-size: 40px !important;\r\n        line-height: 47px !important;\r\n    }\r\n\r\n    .mso .size-44,\r\n    .ie .size-44 {\r\n        font-size: 44px !important;\r\n        line-height: 50px !important;\r\n    }\r\n\r\n    .mso .size-48,\r\n    .ie .size-48 {\r\n        font-size: 48px !important;\r\n        line-height: 54px !important;\r\n    }\r\n\r\n    .mso .size-56,\r\n    .ie .size-56 {\r\n        font-size: 56px !important;\r\n        line-height: 60px !important;\r\n    }\r\n\r\n    .mso .size-64,\r\n    .ie .size-64 {\r\n        font-size: 64px !important;\r\n        line-height: 63px !important;\r\n    }\r\n\r\n    .footer__share-button p {\r\n        margin: 0;\r\n    }\r\n</style>\r\n\r\n<title></title>\r\n<!--[if !mso]><!-->\r\n<style type=\"text/css\">\r\n    @import url(https://fonts.googleapis.com/css?family=Bitter:400,700,400italic|Cabin:400,700,400italic,700italic|Open+Sans:400italic,700italic,700,400);\r\n</style>\r\n<link href=\"https://fonts.googleapis.com/css?family=Bitter:400,700,400italic|Cabin:400,700,400italic,700italic|Open+Sans:400italic,700italic,700,400\" rel=\"stylesheet\" type=\"text/css\">\r\n<!--<![endif]-->\r\n<style type=\"text/css\">\r\n    body {\r\n        background-color: #f5f7fa\r\n    }\r\n\r\n    .mso h1 {\r\n    }\r\n\r\n    .mso h1 {\r\n        font-family: sans-serif !important\r\n    }\r\n\r\n    .mso h2 {\r\n    }\r\n\r\n    .mso h3 {\r\n    }\r\n\r\n    .mso .column,\r\n    .mso .column__background td {\r\n    }\r\n\r\n    .mso .column,\r\n    .mso .column__background td {\r\n        font-family: sans-serif !important\r\n    }\r\n\r\n    .mso .btn a {\r\n    }\r\n\r\n    .mso .btn a {\r\n        font-family: sans-serif !important\r\n    }\r\n\r\n    .mso .webversion,\r\n    .mso .snippet,\r\n    .mso .layout-email-footer td,\r\n    .mso .footer__share-button p {\r\n    }\r\n\r\n    .mso .webversion,\r\n    .mso .snippet,\r\n    .mso .layout-email-footer td,\r\n    .mso .footer__share-button p {\r\n        font-family: sans-serif !important\r\n    }\r\n\r\n    .mso .logo {\r\n    }\r\n\r\n    .mso .logo {\r\n        font-family: Tahoma, sans-serif !important\r\n    }\r\n\r\n    .logo a:hover,\r\n    .logo a:focus {\r\n        color: #859bb1 !important\r\n    }\r\n\r\n    .mso .layout-has-border {\r\n        border-top: 1px solid #b1c1d8;\r\n        border-bottom: 1px solid #b1c1d8\r\n    }\r\n\r\n    .mso .layout-has-bottom-border {\r\n        border-bottom: 1px solid #b1c1d8\r\n    }\r\n\r\n    .mso .border,\r\n    .ie .border {\r\n        background-color: #b1c1d8\r\n    }\r\n\r\n    @media only screen and (min-width: 620px) {\r\n        .wrapper h1 {\r\n        }\r\n\r\n        .wrapper h1 {\r\n            font-size: 26px !important;\r\n            line-height: 34px !important\r\n        }\r\n\r\n        .wrapper h2 {\r\n        }\r\n\r\n        .wrapper h2 {\r\n            font-size: 20px !important;\r\n            line-height: 28px !important\r\n        }\r\n\r\n        .wrapper h3 {\r\n        }\r\n\r\n        .column p,\r\n        .column ol,\r\n        .column ul {\r\n        }\r\n    }\r\n\r\n    .mso h1,\r\n    .ie h1 {\r\n    }\r\n\r\n    .mso h1,\r\n    .ie h1 {\r\n        font-size: 26px !important;\r\n        line-height: 34px !important\r\n    }\r\n\r\n    .mso h2,\r\n    .ie h2 {\r\n    }\r\n\r\n    .mso h2,\r\n    .ie h2 {\r\n        font-size: 20px !important;\r\n        line-height: 28px !important\r\n    }\r\n\r\n    .mso h3,\r\n    .ie h3 {\r\n    }\r\n\r\n    .mso .layout__inner p,\r\n    .ie .layout__inner p,\r\n    .mso .layout__inner ol,\r\n    .ie .layout__inner ol,\r\n    .mso .layout__inner ul,\r\n    .ie .layout__inner ul {\r\n    }\r\n</style>\r\n<meta name=\"robots\" content=\"noindex,nofollow\">\r\n\r\n<meta property=\"og:title\" content=\"Just One More Step\">\r\n\r\n<link href=\"https://css.createsend1.com/css/social.min.css?h=0ED47CE120160920\" media=\"screen,projection\" rel=\"stylesheet\" type=\"text/css\">\r\n\r\n\r\n<div class=\"wrapper\" style=\"min-width: 320px;background-color: #f5f7fa;\" lang=\"x-wrapper\">\r\n    <div class=\"preheader\" style=\"margin: 0 auto;max-width: 560px;min-width: 280px; width: 280px;\">\r\n        <div style=\"border-collapse: collapse;display: table;width: 100%;\">\r\n            <div class=\"snippet\" style=\"display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 140px; width: 140px;padding: 10px 0 5px 0;color: #b9b9b9;\">\r\n            </div>\r\n            <div class=\"webversion\" style=\"display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 139px; width: 139px;padding: 10px 0 5px 0;text-align: right;color: #b9b9b9;\">\r\n            </div>\r\n        </div>\r\n\r\n        <div class=\"layout one-col fixed-width\" style=\"margin: 0 auto;max-width: 600px;min-width: 320px; width: 320px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;\">\r\n            <div class=\"layout__inner\" style=\"border-collapse: collapse;display: table;width: 100%;background-color: #c4e5dc;\" lang=\"x-layout__inner\">\r\n                <div class=\"column\" style=\"text-align: left;color: #60666d;font-size: 14px;line-height: 21px;max-width:600px;min-width:320px;\">\r\n                    <div style=\"margin-left: 20px;margin-right: 20px;margin-top: 24px;margin-bottom: 24px;\">\r\n                        <h1 style=\"margin-top: 0;margin-bottom: 0;font-style: normal;font-weight: normal;color: #44a8c7;font-size: 36px;line-height: 43px;font-family: bitter,georgia,serif;text-align: center;\">\r\n                            <img style=\"width: 200px;\" src=\"https://bug-finder.s3.ap-southeast-1.amazonaws.com/assets/logo/header-logo.svg\" data-filename=\"imageedit_76_3542310111.png\"></h1>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n\r\n            <div class=\"layout one-col fixed-width\" style=\"margin: 0 auto;max-width: 600px;min-width: 320px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;\">\r\n                <div class=\"layout__inner\" style=\"border-collapse: collapse;display: table;width: 100%;background-color: #ffffff;\" lang=\"x-layout__inner\">\r\n                    <div class=\"column\" style=\"text-align: left; background: rgb(237, 241, 235); line-height: 21px; max-width: 600px; min-width: 320px; width: 320px;\">\r\n\r\n                        <div style=\"color: rgb(96, 102, 109); font-size: 14px; margin-left: 20px; margin-right: 20px; margin-top: 24px;\">\r\n                            <div style=\"line-height:10px;font-size:1px\">&nbsp;</div>\r\n                        </div>\r\n\r\n                        <div style=\"margin-left: 20px; margin-right: 20px;\">\r\n\r\n                            <p style=\"color: rgb(96, 102, 109); font-size: 14px; margin-top: 16px; margin-bottom: 0px;\"><strong>Hello [[name]],</strong></p>\r\n                            <p style=\"color: rgb(96, 102, 109); font-size: 14px; margin-top: 20px; margin-bottom: 20px;\"><strong>[[message]]</strong></p>\r\n                            <p style=\"margin-top: 20px; margin-bottom: 20px;\"><strong style=\"color: rgb(96, 102, 109); font-size: 14px;\">Sincerely,<br>Team&nbsp;</strong><font color=\"#60666d\"><b>Bug Finder</b></font></p>\r\n                        </div>\r\n\r\n                    </div>\r\n                </div>\r\n            </div>\r\n\r\n            <div class=\"layout__inner\" style=\"border-collapse: collapse;display: table;width: 100%;background-color: #2c3262; margin-bottom: 20px\" lang=\"x-layout__inner\">\r\n                <div class=\"column\" style=\"text-align: left;color: #60666d;font-size: 14px;line-height: 21px;max-width:600px;min-width:320px;\">\r\n                    <div style=\"margin-top: 5px;margin-bottom: 5px;\">\r\n                        <p style=\"margin-top: 0;margin-bottom: 0;font-style: normal;font-weight: normal;color: #ffffff;font-size: 16px;line-height: 35px;font-family: bitter,georgia,serif;text-align: center;\">\r\n                            2024 ©  All Right Reserved</p>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n\r\n        </div>\r\n\r\n\r\n        <div style=\"border-collapse: collapse;display: table;width: 100%;\">\r\n            <div class=\"snippet\" style=\"display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 140px; width: 140px;padding: 10px 0 5px 0;color: #b9b9b9;\">\r\n            </div>\r\n            <div class=\"webversion\" style=\"display: table-cell;Float: left;font-size: 12px;line-height: 19px;max-width: 280px;min-width: 139px; width: 139px;padding: 10px 0 5px 0;text-align: right;color: #b9b9b9;\">\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>', 0, 0, 0, 0, 0, 0, 'OSLDSF4D', 0, 0, 'KLSDKF789', '654646977', 0, 0, 0, 0, 0, 0, 0, 0, 'aaaaaa', 1, 1, 0, 'logo/Y9bryb3btRgfyeLtk3smwwSrDmu0oD.webp', 'local', 'logo/6fy9Cq0wSqPrQN9uDckKixCAzo14oA.webp', 'local', 'logo/FSJKfpMkv1eY3OJ2TvTxXAh1OZnDYo.webp', 'local', 'logo/9EJNj2XgfhX9vXxPN7Keoz6HSJicPV.webp', 'local', 'dfsgdfg', 'everyMinute', '0', '726ffba5-8523-4071-92d4-1775dbc', 'everyMinute', 0, 0, 0, 0, 'Y/m/d', 'We use cookies!', 'We use cookies to ensure that give you the best experience on your website.', 'https://smm-matrix.bugfinder.app/', 1, '2023-06-13 12:35:41', '2025-06-03 22:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `views` varchar(255) DEFAULT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `thumbnail_image_driver` varchar(50) DEFAULT NULL,
  `thumbnail_image_two` varchar(255) DEFAULT NULL,
  `thumbnail_image_two_driver` varchar(50) DEFAULT NULL,
  `description_image` varchar(255) DEFAULT NULL,
  `description_image_driver` varchar(50) DEFAULT NULL,
  `breadcrumb_image` varchar(255) DEFAULT NULL,
  `breadcrumb_image_driver` varchar(50) DEFAULT NULL,
  `breadcrumb_status` varchar(255) NOT NULL DEFAULT '1' COMMENT '0 => inactive, 1 => active',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `page_title` varchar(100) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `meta_robots` varchar(255) DEFAULT NULL,
  `meta_image` varchar(191) DEFAULT NULL,
  `meta_image_driver` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `category_id`, `author_id`, `views`, `thumbnail_image`, `thumbnail_image_driver`, `thumbnail_image_two`, `thumbnail_image_two_driver`, `description_image`, `description_image_driver`, `breadcrumb_image`, `breadcrumb_image_driver`, `breadcrumb_status`, `status`, `page_title`, `meta_title`, `meta_keywords`, `meta_description`, `og_description`, `meta_robots`, `meta_image`, `meta_image_driver`, `created_at`, `updated_at`) VALUES
(2, 4, 2, '70', 'blog/8q42uXD6TAHHhFLFhBeix8kyFSIeGO.webp', 'local', 'blog/aitarOij2XHiOhBXp11ff9RebDTvn1.webp', 'local', 'blog/ydUfHK8kuakWLwOMqeMZAGap4gD3Ct.webp', 'local', 'blog/TtvxVtsZmoOAqhJGAHbATmBjzwx6HX.webp', 'local', '1', 1, 'Blog Details', NULL, NULL, NULL, NULL, '\"follow\"', NULL, NULL, '2024-03-16 04:07:00', '2024-10-30 03:36:39'),
(3, 2, 2, '116', 'blog/roaSHy1Sxsv4klZLY58zUxk7BCQNmE.webp', 'local', 'blog/WT3Hbq21ahpqAHNF2B8gibIDvZYsdn.webp', 'local', 'blog/St7lvQ6qi6AwyaLiit2aBsgi9XRjyc.webp', 'local', 'blog/MkvG9RK1Nz29Wq2aF78PxPJXlaa821.webp', 'local', '1', 1, 'Blog Details', NULL, NULL, NULL, NULL, '\"index\"', NULL, NULL, '2024-03-16 04:10:51', '2024-12-21 23:39:26'),
(4, 4, 1, '9', 'blog/r1fv9EebUsRZLmVtuzj5icUviL2tBL.webp', 'local', 'blog/j1VFw4kVUb3ApJJ0nzSpn6bhSQPcjD.webp', 'local', 'blog/aI4l7O3YDec2zaesDnyuU9zte6OYbt.webp', 'local', 'blog/B8x9P4zvE1jEFqKFZDfM8GaC3TPdLp.webp', 'local', '1', 1, 'Blog Details', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-17 01:15:49', '2024-10-29 20:44:03'),
(5, 5, 3, '2', 'blog/4yLFUsPTq1Jtz0HuR0AChFuCstHzFZ.webp', 'local', 'blog/UT87ZmP82YeeVI1JD0UKYokA63L8E9.webp', 'local', 'blog/XhzgcEFf07TnGOiBdYkZvQDgp0OtHT.webp', 'local', NULL, NULL, '0', 1, 'Blog Details', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-06 22:57:32', '2024-12-31 06:34:29'),
(6, 3, 1, '30', 'blog/L8tthqqonsqlVgc6ZioUBL2ndPkMyL.webp', 'local', 'blog/0UgpHKh7lRwGJzuNvdDv3FSVU6JJAh.webp', 'local', 'blog/pMS3YQQV0eis5dkNAdtud95OV2ggPP.webp', 'local', NULL, NULL, '0', 1, 'Blog Details', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-07 00:21:00', '2025-01-04 09:06:34'),
(7, 6, 1, '4', 'blog/bifTUyrA6LzRQ8eixOo23La8kj5LiF.webp', 'local', 'blog/zLPpK02lPWO6l2XNb3a31DxFnteUee.webp', 'local', 'blog/Y7XwyiZ9ttxC6J3FKhS9kSntZs43lk.webp', 'local', NULL, NULL, '0', 1, 'Blog Details', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-07 00:28:06', '2024-12-31 06:34:25');

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0:inactive, 1:active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-08-30 09:59:35', '2024-08-30 09:59:35'),
(2, 1, '2024-08-30 10:00:00', '2024-08-30 10:00:00'),
(3, 1, '2024-08-30 10:00:09', '2024-11-06 22:49:18'),
(4, 1, '2024-08-30 10:00:18', '2024-08-30 10:00:18'),
(5, 1, '2024-08-30 10:00:28', '2024-11-06 22:49:11'),
(6, 1, '2024-08-30 10:00:36', '2024-08-30 10:04:50');

-- --------------------------------------------------------

--
-- Table structure for table `blog_category_details`
--

CREATE TABLE `blog_category_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_category_details`
--

INSERT INTO `blog_category_details` (`id`, `category_id`, `language_id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Brand Management', 'brand-management', '2024-08-30 09:59:35', '2024-08-30 09:59:35'),
(2, 2, 1, 'Competitive Intelligence', 'competitive-intelligence', '2024-08-30 10:00:00', '2024-08-30 10:00:00'),
(3, 3, 1, 'Content Marketing', 'content-marketing', '2024-08-30 10:00:09', '2024-08-30 10:00:09'),
(4, 4, 1, 'Influencer Marketing', 'influencer-marketing', '2024-08-30 10:00:18', '2024-08-30 10:00:18'),
(5, 5, 1, 'Social Media Management', 'social-media-management', '2024-08-30 10:00:28', '2024-08-30 10:00:28'),
(6, 6, 1, 'Digital Marketing &amp; PR Tools', 'digital-marketing-amp-pr-tools', '2024-08-30 10:00:36', '2024-08-30 10:00:36'),
(7, 6, 2, 'Herramientas de marketing digital y relaciones públicas', 'herramientas-de-marketing-digital-y-relaciones-publicas', '2024-11-06 22:16:21', '2024-11-06 22:16:21'),
(8, 5, 2, 'Gestión de redes sociales', 'gestion-de-redes-sociales', '2024-11-06 22:16:38', '2024-11-06 22:16:38'),
(9, 4, 2, 'Marketing de influencers', 'marketing-de-influencers', '2024-11-06 22:16:49', '2024-11-06 22:16:49'),
(10, 3, 2, 'Marketing de contenidos', 'marketing-de-contenidos', '2024-11-06 22:17:08', '2024-11-06 22:17:08'),
(11, 2, 2, 'Inteligencia Competitiva', 'inteligencia-competitiva', '2024-11-06 22:17:22', '2024-11-06 22:17:22'),
(12, 1, 2, 'Gestión de marca', 'gestion-de-marca', '2024-11-06 22:17:39', '2024-11-06 22:17:39');

-- --------------------------------------------------------

--
-- Table structure for table `blog_details`
--

CREATE TABLE `blog_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quote` text DEFAULT NULL,
  `quote_author` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_details`
--

INSERT INTO `blog_details` (`id`, `blog_id`, `language_id`, `title`, `slug`, `tags`, `description`, `quote`, `quote_author`, `created_at`, `updated_at`) VALUES
(5, 2, 1, 'Practical Tips and How To Guides', 'practical-tips-and-how-to-guides', '[\"Social Media\",\"Advertising\",\"Campaign\",\"LinkedIn\",\"Pinterest\",\"Analyses\"]', '<p>The Social Media Advertising category of a social marketing blog is dedicated to exploring the intricacies of leveraging paid advertising on various social media platforms to achieve marketing objectives. It covers a wide range of topics including ad formats, targeting options, budgeting strategies, ad creative best practices, and campaign optimization techniques. From beginner\'s guides to advanced tactics, this category provides insights into effectively utilizing platforms such as Facebook, Instagram, Twitter, LinkedIn, Pinterest, and others for advertising purposes. Readers can expect detailed analyses of case studies, updates on platform algorithms and policies, as well as tips for maximizing ROI and achieving business goals through social media advertising campaigns.<br></p>', 'The more time you spend contemplating what you should have done. you lose valuable time planning what you can and will do.', 'Lil Wayne', '2024-03-16 04:07:00', '2024-10-27 19:50:03'),
(6, 3, 1, 'Expert Opinions and Thought Leadership', 'expert-opinions-and-thought-leadership', '[\"Community Management\",\"Facebook\",\"Instagram\",\"Marketing\",\"Social Media\",\"Development\"]', '<p>The Community Manage category of a social marketing blog delves into the strategies, best practices, and tools necessary for fostering and nurturing online communities. It covers topics such as engaging with followers, building rapport, handling feedback and criticism, resolving conflicts, and cultivating a sense of belonging among community members. Additionally, this category explores the role of community managers in maintaining brand loyalty, driving user-generated content, and leveraging community insights for product development and marketing campaigns. Whether it\'s on social media platforms, forums, or other online spaces, readers can expect valuable insights on building and sustaining vibrant communities around their brands.<br></p>', 'Without leaps of imagination or dreaming, we lose the excitement of possibilities. Dreaming, after all is a form of planning.', 'Gloria Steinem', '2024-03-16 04:10:51', '2024-10-28 03:56:45'),
(7, 4, 1, 'Latest Trends and Industry Insights', 'latest-trends-and-industry-insights', '[\"Business\",\"Facebook\",\"Marketing\",\"Social Media\",\"Industry Insights\",\"Trends\",\"Technological\"]', 'The Latest Trends and Industry Insights category of a social marketing blog focuses on providing up-to-date analysis, commentary, and predictions regarding the ever-evolving landscape of social media marketing. This category explores emerging trends, innovative strategies, and shifts in consumer behavior that are shaping the industry. From the adoption of new platforms and features to the impact of cultural movements and technological advancements, readers can expect timely insights and expert perspectives to stay ahead of the curve and adapt their marketing efforts effectively.', 'What I find remarkable is that this next has been the industry\'s standard dummy text ever since some printer in the 1500s took a gallery', 'Douglas lyphe', '2024-03-17 01:15:49', '2024-10-27 19:49:40'),
(8, 4, 2, '¿Cómo sé si la batería de mi automóvil está agotada o recién descargada?', 'latest-trends-and-industry-insights', '[\"Bater\\u00eda\",\"descargada\"]', '<p>Una batería descargada significa que tiene poca energía, pero no necesariamente que no pueda repararse. Puede suceder si las luces o la radio se dejan encendidas durante períodos prolongados sin el motor en marcha. Estas son las señales a las que debe prestar atención: Arranque lento del motor Cuando intenta arrancar el vehículo, el motor arranca lentamente, con más diligencia de lo habitual o suena como si tuviera dificultades para arrancar. Esta suele ser la primera señal de que la batería podría estar descargada. Luces tenues y problemas eléctricos Si los faros delanteros o las luces del tablero de su automóvil son más tenues de lo habitual, o si tiene problemas con los componentes eléctricos de su automóvil, podría ser una señal de que la batería está descargada. Estos componentes requieren un cierto nivel de energía de la batería para funcionar correctamente.<br></p>', 'La mayoría de las baterías de coche tienen una vida útil de 3 a 5 años. Si su batería está dentro de este rango de antigüedad o más, es probable que se agote y necesite ser reemplazada.', 'Fill Foden', '2024-04-03 08:43:05', '2024-05-06 00:50:27'),
(9, 3, 2, 'Dominar el arte de la depilación con cera: una guía paso a paso para lograr un brillo brillante', 'expert-opinions-and-thought-leadership', '[\"Gu\\u00eda\",\"Brillar\",\"Servicio\"]', '<p>Comience su viaje hacia el dominio del cuidado del automóvil con una guía completa sobre cómo mantener el brillo impecable de su vehículo. En esta publicación de blog, profundizaremos en consejos prácticos y efectivos para mantener su automóvil con un aspecto digno de una exposición entre sesiones de detalles profesionales. Analice la importancia del lavado regular para eliminar la suciedad, los contaminantes y los excrementos de pájaros. Resalte las técnicas de lavado adecuadas y la importancia del secado minucioso para evitar manchas de agua. Comience su viaje hacia el dominio del cuidado del automóvil con una guía completa sobre cómo mantener el brillo impecable de su vehículo. En esta publicación de blog, profundizaremos en consejos prácticos y efectivos para mantener su automóvil con un aspecto digno de una exposición entre sesiones de detalles profesionales. Comience su viaje hacia el dominio del cuidado del automóvil con una guía completa sobre cómo mantener el brillo impecable de su vehículo. En esta publicación de blog, profundizaremos en consejos prácticos y efectivos para mantener su automóvil con un aspecto digno de una exposición entre sesiones de detalles profesionales.<br></p>', 'Más allá de la espuma Desentrañando los secretos del revestimiento cerámico para su vehículo', 'Kevin De Bruye', '2024-04-03 08:45:49', '2024-05-06 01:38:48'),
(10, 2, 2, 'Más allá de la espuma Desentrañando los secretos del revestimiento cerámico para su vehículo', 'practical-tips-and-how-to-guides', '[\"Veh\\u00edculo\",\"Cer\\u00e1mica\",\"Auto\"]', '<p>Comience su viaje hacia el dominio del cuidado del automóvil con una guía completa sobre cómo mantener el brillo impecable de su vehículo. En esta publicación de blog, profundizaremos en consejos prácticos y efectivos para mantener su automóvil con un aspecto digno de una exposición entre sesiones de detalles profesionales. Analice la importancia del lavado regular para eliminar la suciedad, los contaminantes y los excrementos de pájaros. Resalte las técnicas de lavado adecuadas y la importancia del secado minucioso para evitar manchas de agua. Comience su viaje hacia el dominio del cuidado del automóvil con una guía completa sobre cómo mantener el brillo impecable de su vehículo. En esta publicación de blog, profundizaremos en consejos prácticos y efectivos para mantener su automóvil con un aspecto digno de una exposición entre sesiones de detalles profesionales. Comience su viaje hacia el dominio del cuidado del automóvil con una guía completa sobre cómo mantener el brillo impecable de su vehículo. En esta publicación de blog, profundizaremos en consejos prácticos y efectivos para mantener su automóvil con un aspecto digno de una exposición entre sesiones de detalles profesionales.<br></p>', 'Comparta ideas sobre el mantenimiento del interior, incluida la aspiración, la limpieza de tapizados y la protección de superficies.', 'David Backham', '2024-04-03 08:52:37', '2024-05-06 01:49:55'),
(12, 5, 1, 'How to Measure the Success of Your Social Media Campaigns', 'how-to-measure-the-success-of-your-social-media-campaigns', '[\"Social Media Analytics\",\"Campaign Measurement\",\"SMM Metrics\"]', '<p>In today’s digital landscape, running a social media campaign is essential for brand visibility and growth. But without proper measurement, you can’t be sure if your efforts are truly paying off. Measuring success isn’t just about counting likes or shares; it’s about understanding the metrics that drive your business goals. Here’s a complete guide to measuring the success of your social media campaigns.<br><br>Learn how to accurately measure the success of your social media campaigns with our in-depth guide. Discover key metrics like engagement rate, reach, and conversions, and explore the best tools for tracking performance across platforms. Whether you\'re new to social media marketing or looking to refine your strategies, this guide will help you turn data into actionable insights for growth.</p>', 'Learn how to accurately measure the success of your social media campaigns with our in-depth guide', 'Fill Bosman', '2024-11-06 22:57:32', '2024-11-06 22:57:32'),
(13, 6, 1, 'How Social Media Marketing Panels Can Help Grow Your Business', 'how-social-media-marketing-panels-can-help-grow-your-business', '[\"SocialMediaMarketing\",\"SMMPanels\",\"DigitalMarketing\",\"SocialMediaGrowth\",\"BoostEngagement\"]', '<p>Discuss how purchasing initial engagement can help new or small businesses appear more popular and credible, making it easier to attract organic followers.Explain how SMM panels save time and resources by consolidating all services into one platform, offering packages that are more affordable than running extensive ad campaigns.<br><br>Describe how some advanced SMM panels offer targeted engagement services, allowing businesses to reach specific demographics or regional audiences.Discuss how an increased number of followers, likes, and comments serves as social proof, encouraging others to engage with the brand, improving brand trust and loyalty.</p>', 'In today’s digital age, social media presence is not just a luxury but a necessity for businesses looking to grow. Social Media Marketing Panels offer a powerful, cost-effective way to boost visibility and engagement, allowing brands to focus on creating meaningful connections with their audience.', 'Mike Tyson', '2024-11-07 00:21:00', '2024-11-07 00:21:00'),
(14, 7, 1, 'The Impact of Video Content on Social Media Engagement', 'the-impact-of-video-content-on-social-media-engagement', '[\"Video Content Marketing\",\"Social Media Engagement\",\"Brand Awareness\",\"Social Media Trends\"]', '<p>Video content has been proven to hold people’s attention far longer than other forms of media. Viewers are more likely to watch videos to the end and are also more likely to remember the message, making it a great way to build brand awareness.Videos are highly shareable, which helps them reach a larger audience. Whether it’s a funny clip, a touching story, or an educational tutorial, videos are one of the most shared forms of content on platforms like Facebook, Instagram, and LinkedIn.<br><br>Most social media algorithms prioritize video content, meaning videos often get more exposure than images or text. Platforms like Instagram and Facebook actively promote video posts, which results in more visibility, higher engagement, and greater reach for businesses using video effectively.Short-form videos, popularized by platforms like TikTok and Instagram Reels, are quick and engaging. These videos are often between 15-60 seconds long, capturing attention immediately and delivering bite-sized, impactful content. They’re perfect for product teasers, behind-the-scenes glimpses, or quick tutorials.</p>', 'Video content is the key to unlocking deeper engagement on social media. It captures attention, tells stories, and leaves lasting impressions, making it a powerful tool for brands looking to connect authentically with their audience.', 'Panaroma Lian', '2024-11-07 00:28:06', '2024-11-07 00:28:06'),
(15, 7, 2, 'El impacto del contenido de vídeo en la participación en las redes sociales', 'the-impact-of-video-content-on-social-media-engagement', '[\"Marketing de contenidos de v\\u00eddeo\",\"Participaci\\u00f3n en las redes sociales\",\"Conocimiento de la marca\",\"Tendencias de las redes sociales\"]', '<p>Se ha demostrado que el contenido de vídeo mantiene la atención de las personas por mucho más tiempo que otras formas de medios. Es más probable que los espectadores vean los videos hasta el final y también recuerden el mensaje, lo que lo convierte en una excelente manera de generar conciencia de marca. Los videos se pueden compartir fácilmente, lo que les ayuda a llegar a una audiencia más amplia. Ya sea un clip divertido, una historia conmovedora o un tutorial educativo, los videos son una de las formas de contenido más compartidas en plataformas como Facebook, Instagram y LinkedIn.<br><br>La mayoría de los algoritmos de las redes sociales dan prioridad al contenido de vídeo, lo que significa que los vídeos suelen tener más exposición que las imágenes o el texto. Plataformas como Instagram y Facebook promueven activamente publicaciones de video, lo que resulta en más visibilidad, mayor participación y mayor alcance para las empresas que usan videos de manera efectiva. Los videos de formato corto, popularizados por plataformas como TikTok e Instagram Reels, son rápidos y atractivos. Estos videos suelen tener entre 15 y 60 segundos de duración, captan la atención de inmediato y ofrecen contenido breve e impactante. Son perfectos para avances de productos, vistazos detrás de escena o tutoriales rápidos.</p>', 'El contenido de video es la clave para desbloquear una participación más profunda en las redes sociales. Capta la atención, cuenta historias y deja impresiones duraderas, lo que la convierte en una herramienta poderosa para las marcas que buscan conectarse auténticamente con su audiencia.', 'Panaroma Lian', '2024-11-07 00:31:42', '2024-11-07 00:31:42'),
(16, 6, 2, 'Cómo los paneles de marketing en redes sociales pueden ayudar a hacer crecer su negocio', 'how-social-media-marketing-panels-can-help-grow-your-business', '[\"Redes SocialesMarketing\",\"Paneles SMMP\",\"Marketing Digital\",\"Crecimiento de las redes sociales\",\"Impulsar el compromiso\"]', '<p>Analice cómo la adquisición de participación inicial puede ayudar a que las empresas nuevas o pequeñas parezcan más populares y creíbles, lo que facilita la atracción de seguidores orgánicos. Explique cómo los paneles SMM ahorran tiempo y recursos al consolidar todos los servicios en una sola plataforma, ofreciendo paquetes que son más asequibles que ejecutarlos de manera integral. campañas publicitarias.<br><br>Describa cómo algunos paneles SMM avanzados ofrecen servicios de participación específicos, lo que permite a las empresas llegar a grupos demográficos o audiencias regionales específicos. Analice cómo un mayor número de seguidores, me gusta y comentarios sirven como prueba social, animando a otros a interactuar con la marca, mejorando la confianza en la marca y lealtad.</p>', 'En la era digital actual, la presencia en las redes sociales no es sólo un lujo sino una necesidad para las empresas que buscan crecer. Los paneles de marketing en redes sociales ofrecen una forma potente y rentable de aumentar la visibilidad y la participación, permitiendo a las marcas centrarse en crear conexiones significativas con su audiencia.', 'Mike Tyson', '2024-11-07 00:35:42', '2024-11-07 00:35:42'),
(17, 5, 2, 'Cómo medir el éxito de sus campañas en las redes sociales', 'how-to-measure-the-success-of-your-social-media-campaigns', '[\"An\\u00e1lisis de redes sociales\",\"Medici\\u00f3n de campa\\u00f1a\",\"M\\u00e9tricas de SMM\"]', '<p>En el panorama digital actual, ejecutar una campaña en las redes sociales es esencial para la visibilidad y el crecimiento de la marca. Pero sin una medición adecuada, no puedes estar seguro de si tus esfuerzos realmente están dando sus frutos. Medir el éxito no se trata sólo de contar los \"me gusta\" o las acciones compartidas; se trata de comprender las métricas que impulsan sus objetivos comerciales. Aquí tienes una guía completa para medir el éxito de tus campañas en las redes sociales.<br><br>Aprenda cómo medir con precisión el éxito de sus campañas en las redes sociales con nuestra guía detallada. Descubra métricas clave como la tasa de participación, el alcance y las conversiones, y explore las mejores herramientas para realizar un seguimiento del rendimiento en todas las plataformas. Ya sea que sea nuevo en el marketing de redes sociales o esté buscando perfeccionar sus estrategias, esta guía lo ayudará a convertir los datos en información útil para el crecimiento.</p>', 'Aprenda cómo medir con precisión el éxito de sus campañas en las redes sociales con nuestra guía detallada', 'Fill Bosman', '2024-11-07 00:37:50', '2024-11-07 00:37:50');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `social_media_id` int(11) DEFAULT NULL,
  `sort_by` int(11) NOT NULL DEFAULT 1,
  `category_title` varchar(191) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `image_driver` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `media` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `name`, `theme`, `type`, `media`, `created_at`, `updated_at`) VALUES
(1, 'hero', 'light_green', 'single', '{\"image\":{\"path\":\"contents\\/XDd2Gyv0bPUo8bfLcL0hGxZidsPbZC.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"video_link\":\"https:\\/\\/www.youtube.com\\/watch?v=XWZ4GUuunVE&amp;ab_channel=HrishiComputerEducation\",\"background_image\":{\"path\":\"contents\\/RLYwQSyk9hqrjcdfqJ50JJDpJgEUEa.webp\",\"driver\":\"local\"}}', '2024-05-05 01:55:05', '2024-10-01 00:28:45'),
(2, 'feature', 'light_green', 'multiple', '{\"icon\":\"far fa-clock\"}', '2024-05-05 02:37:52', '2024-08-30 09:31:27'),
(3, 'feature', 'light_green', 'multiple', '{\"icon\":\"fa-sharp fa-regular fa-lightbulb\"}', '2024-05-05 02:38:35', '2024-05-05 02:38:35'),
(4, 'feature', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-hand-holding-dollar\"}', '2024-05-05 02:39:20', '2024-05-05 02:39:20'),
(5, 'feature', 'lightlight_green', 'multiple', '{\"icon\":\"fa-regular fa-headset\"}', '2024-05-05 02:39:55', '2024-05-05 02:39:55'),
(6, 'about', 'light_green', 'single', '{\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"image_one\":{\"path\":\"contents\\/C4fEEpRnePaAXI9UykX1FbKGSEiZT3.webp\",\"driver\":\"local\"},\"image_two\":{\"path\":\"contents\\/p7WJcIyorWMoM8vLzENdCqIdF5HmmA.webp\",\"driver\":\"local\"}}', '2024-05-05 02:58:48', '2024-05-05 03:08:09'),
(7, 'about', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-bullseye-pointer\"}', '2024-05-05 03:00:56', '2024-05-05 03:00:56'),
(8, 'about', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-timeline-arrow\"}', '2024-05-05 03:01:34', '2024-05-05 03:01:34'),
(9, 'service', 'light_green', 'single', '{\"background_image\":{\"path\":\"contents\\/My1GFMBgJSlBBAlBNIqghCzJpDgmGI.webp\",\"driver\":\"local\"}}', '2024-05-05 03:29:13', '2024-05-05 03:29:13'),
(10, 'service', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-rocket\"}', '2024-05-05 03:30:02', '2024-05-05 03:30:02'),
(11, 'service', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-chart-scatter\"}', '2024-05-05 03:30:47', '2024-05-05 03:30:47'),
(12, 'service', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-cart-shopping\"}', '2024-05-05 03:31:15', '2024-05-05 03:31:15'),
(13, 'service', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-envelope\"}', '2024-05-05 03:31:44', '2024-05-05 03:31:44'),
(14, 'service', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-display\"}', '2024-05-05 03:32:22', '2024-05-05 03:32:22'),
(15, 'service', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-book-open-cover\"}', '2024-05-05 03:32:49', '2024-05-05 03:32:49'),
(16, 'why_choose_us', 'light_green', 'single', '{\"image_one\":{\"path\":\"contents\\/Y1UKlKIn4sGGkvinR1NfrVA5VNtMDx.webp\",\"driver\":\"local\"},\"image_two\":{\"path\":\"contents\\/xAu6J5cPFgBodsCw47SpiQEOviQqy2.webp\",\"driver\":\"local\"}}', '2024-05-05 04:01:39', '2024-05-05 04:01:39'),
(17, 'why_choose_us', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-users\"}', '2024-05-05 04:02:27', '2024-05-05 04:02:27'),
(18, 'why_choose_us', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-briefcase\"}', '2024-05-05 04:03:51', '2024-05-05 04:03:51'),
(19, 'why_choose_us', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-trophy-star\"}', '2024-05-05 04:04:24', '2024-05-05 04:04:24'),
(20, 'why_choose_us', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-monitor-waveform\"}', '2024-05-05 04:04:56', '2024-05-05 19:36:20'),
(21, 'achievement', 'light_green', 'single', NULL, '2024-05-05 19:38:12', '2024-05-05 19:38:12'),
(22, 'achievement', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-users\"}', '2024-05-05 19:38:34', '2024-05-05 19:38:34'),
(23, 'achievement', 'light_green', 'multiple', '{\"icon\":\"fa-sharp fa-regular fa-file-invoice\"}', '2024-05-05 19:38:54', '2024-05-05 19:38:54'),
(24, 'achievement', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-handshake\"}', '2024-05-05 19:39:20', '2024-05-05 19:39:20'),
(25, 'achievement', 'light_green', 'multiple', '{\"icon\":\"fa-regular fa-trophy-star\"}', '2024-05-05 19:39:39', '2024-05-05 19:39:39'),
(26, 'how_it_works', 'light_green', 'single', '{\"image_one\":{\"path\":\"contents\\/nEXc41MnYIVqHvKjLjJKlWvxy15CQN.webp\",\"driver\":\"local\"},\"image_two\":{\"path\":\"contents\\/PNt2aa2x5p0D4j1moMM8ivZa2QkwbI.webp\",\"driver\":\"local\"}}', '2024-05-05 19:54:04', '2024-05-05 19:54:04'),
(27, 'how_it_works', 'light_green', 'multiple', '{\"icon\":\"far fa-address-book\"}', '2024-05-05 19:54:32', '2024-05-05 19:54:32'),
(28, 'how_it_works', 'light_green', 'multiple', '{\"icon\":\"fas fa-hand-holding-usd\"}', '2024-05-05 19:54:48', '2024-05-05 19:54:48'),
(29, 'how_it_works', 'light_green', 'multiple', '{\"icon\":\"far fa-paper-plane\"}', '2024-05-05 19:55:12', '2024-05-05 19:55:12'),
(30, 'how_it_works', 'light_green', 'multiple', '{\"icon\":\"fab fa-angellist\"}', '2024-05-05 19:55:32', '2024-05-05 19:55:32'),
(31, 'testimonial', 'light_green', 'single', NULL, '2024-05-05 20:18:16', '2024-05-05 20:18:16'),
(32, 'testimonial', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/bX8W79StTbyuL7RlJg5dAFoFfrnjyv.webp\",\"driver\":\"local\"}}', '2024-05-05 20:20:03', '2024-05-05 20:20:03'),
(33, 'testimonial', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/dGpEI28LXbz8MbRmLaBd9NWZczjlzK.webp\",\"driver\":\"local\"}}', '2024-05-05 20:22:32', '2024-05-05 20:22:32'),
(34, 'testimonial', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/tymeB5lXvdSx3EXPGcrs3aPkiBKjiZ.webp\",\"driver\":\"local\"}}', '2024-05-05 20:23:57', '2024-05-05 20:23:57'),
(35, 'testimonial', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/jZXOMotFRyg451vt4Io3L4ZlJYbv5o.webp\",\"driver\":\"local\"}}', '2024-05-05 20:24:48', '2024-05-05 20:24:48'),
(36, 'testimonial', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/SaycfjUQHEXStHGSaorbeY8qaPUXvS.webp\",\"driver\":\"local\"}}', '2024-05-05 20:26:37', '2024-05-05 20:26:37'),
(37, 'payment_partner', 'light_green', 'single', NULL, '2024-05-05 20:48:36', '2024-05-05 20:48:36'),
(53, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/LI5Yj4Z49Cub7QdHj8eP2JKeokskG0.webp\",\"driver\":\"local\"}}', '2024-05-05 21:58:37', '2024-05-05 21:58:37'),
(54, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/JGpxXoZ7e88Ypw02br9iYuQxVvPMgz.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:01', '2024-05-05 21:59:01'),
(55, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/5rhNWEFrVn9otGZxGEtV23uZSyTepn.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:05', '2024-05-05 21:59:05'),
(56, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/dUZaJUxcOfrNT0QGCUEntSBQODpT8k.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:09', '2024-05-05 21:59:09'),
(57, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/ETvYOBzsPqChuvd73xMZ8pE2Z0Ayk5.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:16', '2024-05-05 21:59:16'),
(58, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/7NmHYDcbkhcaa4XVxLhyLsB4b2WYhf.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:19', '2024-05-05 21:59:19'),
(59, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/RA1Mh0MCL0AvI7cs4W87bqCmzsOETv.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:24', '2024-05-05 21:59:24'),
(60, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/shvydexZGoz1ri7JuLvVw5nCV7QmDa.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:28', '2024-05-05 21:59:28'),
(61, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/p79qKMNXpwImLifNsK9WGrwY6Xpgmk.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:32', '2024-05-05 21:59:32'),
(62, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/XFJOM3GBxsGqhB9ydc10HhdnwhsPWV.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:37', '2024-05-05 21:59:37'),
(63, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/ue5Ssrj0unrzDasZ4RAjYCcjCRZr9Z.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:41', '2024-05-05 21:59:41'),
(64, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/QZ1Vg8gxnJrhhQg3LPhRQFHXCmKyKH.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:46', '2024-05-05 21:59:46'),
(65, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/F3PVYtLKAlbxprnBGwF4dH9kRd4E4T.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:50', '2024-05-05 21:59:50'),
(66, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/d8gQAWI7YJNzFNCUVWcKlNoZeGWTU4.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:54', '2024-05-05 21:59:54'),
(67, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/gB5IfVLi8SSmUV6BZ7GcslQjstAd5a.webp\",\"driver\":\"local\"}}', '2024-05-05 21:59:59', '2024-05-05 21:59:59'),
(68, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/HExOt1ZkVbPEBAJoUI2sACwGhDX37t.webp\",\"driver\":\"local\"}}', '2024-05-05 22:00:04', '2024-05-05 22:00:04'),
(69, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/o7L7qcmJUPtPOhqFRzxbQNUQ3ekbTm.webp\",\"driver\":\"local\"}}', '2024-05-05 22:00:07', '2024-05-05 22:00:07'),
(70, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/R1QlVHlge21WK065OygNnPhinrYsJf.webp\",\"driver\":\"local\"}}', '2024-05-05 22:00:12', '2024-05-05 22:00:12'),
(71, 'payment_partner', 'light_green', 'multiple', '{\"image\":{\"path\":\"contents\\/DtmmRgWTdb4RiBHrslWWxGDXwVxnYw.webp\",\"driver\":\"local\"}}', '2024-05-05 22:00:16', '2024-05-05 22:00:16'),
(72, 'faq', 'light_green', 'single', '{\"button_link\":\"https:\\/\\/bugfinder.net\\/\"}', '2024-05-05 22:13:40', '2024-05-05 22:13:40'),
(73, 'faq', 'light_green', 'multiple', NULL, '2024-05-05 22:14:17', '2024-05-05 22:14:17'),
(74, 'faq', 'light_green', 'multiple', NULL, '2024-05-05 22:14:35', '2024-05-05 22:14:35'),
(75, 'faq', 'light_green', 'multiple', NULL, '2024-05-05 22:14:52', '2024-05-05 22:14:52'),
(76, 'faq', 'light_green', 'multiple', NULL, '2024-05-05 22:15:08', '2024-05-05 22:15:08'),
(77, 'faq', 'light_green', 'multiple', NULL, '2024-05-05 22:15:24', '2024-05-05 22:15:24'),
(78, 'faq', 'light_green', 'multiple', NULL, '2024-05-05 22:15:43', '2024-05-05 22:15:43'),
(79, 'blog', 'light_green', 'single', NULL, '2024-05-23 02:15:49', '2024-05-23 02:15:49'),
(80, 'resent_blog', 'light_green', 'single', NULL, '2024-05-23 02:35:50', '2024-05-23 02:35:50'),
(81, 'contact', 'light_green', 'single', '{\"map_link\":\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d193595.15830869428!2d-74.119763973046!3d40.69766374874431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1668508535036!5m2!1sen!2sbd\"}', '2024-05-23 02:51:25', '2024-05-23 02:51:25'),
(86, 'contact', 'light_green', 'multiple', '{\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/facebook.com\\/\"}', '2024-05-23 03:13:33', '2024-05-23 03:14:56'),
(87, 'contact', 'light_green', 'multiple', '{\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/twitter.com\\/\"}', '2024-05-23 03:16:34', '2024-05-23 03:16:34'),
(88, 'contact', 'light_green', 'multiple', '{\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"}', '2024-05-23 03:17:02', '2024-05-23 03:17:02'),
(89, 'contact', 'light_green', 'multiple', '{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-05-23 03:17:34', '2024-05-23 03:17:34'),
(90, 'footer', 'light_green', 'single', '{\"overlay_image\":{\"path\":\"contents\\/fdkKNP5OwQFMTn12aPkECmMjFSMFyR.webp\",\"driver\":\"local\"}}', '2024-05-24 19:36:24', '2024-05-24 20:29:09'),
(91, 'footer', 'light_green', 'multiple', '{\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/facebook.com\\/\"}', '2024-05-24 19:36:52', '2024-05-24 19:36:52'),
(92, 'footer', 'light_green', 'multiple', '{\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/twitter.com\\/\"}', '2024-05-24 19:37:24', '2024-05-24 19:37:24'),
(93, 'footer', 'light_green', 'multiple', '{\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"}', '2024-05-24 19:37:48', '2024-05-24 19:37:48'),
(94, 'footer', 'light_green', 'multiple', '{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-05-24 19:38:06', '2024-05-24 19:38:06'),
(95, 'login', 'light_green', 'single', '{\"background_image\":{\"path\":\"contents\\/n0F7i6d2zUAakibNsKS1wraf0QrMZY.webp\",\"driver\":\"local\"}}', '2024-05-25 02:43:38', '2024-05-25 02:44:15'),
(96, 'register', 'light_green', 'single', '{\"background_image\":{\"path\":\"contents\\/wuAQaRAWFP3Ba27MRM6TWmjkei943Q.webp\",\"driver\":\"local\"}}', '2024-05-25 03:03:47', '2024-09-30 20:48:59'),
(97, 'how_mass_order_works', 'light_green', 'single', NULL, '2024-06-25 22:38:01', '2024-06-25 22:38:01'),
(100, 'child_panel_order_notes', 'light_green', 'single', NULL, '2024-09-25 20:15:06', '2024-09-25 20:15:06'),
(101, 'hero', 'dark_voilet', 'single', '{\"button_link\":\"https:\\/\\/bugfinder.net\\/products\",\"video_link\":\"https:\\/\\/www.youtube.com\\/watch?v=XWZ4GUuunVE&amp;ab_channel=HrishiComputerEducation\",\"background_image\":{\"path\":\"contents\\/UBszqwh3KNLuxucp1UrYfzwH6c679T.webp\",\"driver\":\"local\"}}', '2024-10-01 00:31:21', '2024-10-01 02:10:17'),
(102, 'login', 'dark_voilet', 'single', NULL, '2024-10-01 00:37:56', '2024-10-01 00:37:56'),
(103, 'feature', 'dark_voilet', 'multiple', '{\"icon\":\"far fa-clock\"}', '2024-10-01 00:43:39', '2024-10-01 00:43:39'),
(104, 'feature', 'dark_voilet', 'multiple', '{\"icon\":\"far fa-lightbulb\"}', '2024-10-01 00:44:23', '2024-10-01 00:44:23'),
(105, 'feature', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-hand-holding-usd\"}', '2024-10-01 00:45:14', '2024-10-01 00:45:14'),
(106, 'feature', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-headset\"}', '2024-10-01 00:46:09', '2024-10-01 00:46:09'),
(107, 'about', 'dark_voilet', 'single', '{\"image\":{\"path\":\"contents\\/7BYDlYpug3Hf0mhT6VxLQyAQAWZxmW.webp\",\"driver\":\"local\"},\"button_link\":\"http:\\/\\/smm_matrix.test\\/\"}', '2024-10-01 00:48:26', '2025-01-01 05:20:09'),
(108, 'service', 'dark_voilet', 'single', NULL, '2024-10-01 00:49:18', '2024-10-01 00:49:18'),
(109, 'service', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-list\",\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"background_image\":{\"path\":\"contents\\/00yWtX9h3RPEUWc5T887msBoMLvBP1.webp\",\"driver\":\"local\"}}', '2024-10-01 01:00:53', '2024-10-01 01:00:54'),
(110, 'service', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-rocket\",\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"background_image\":{\"path\":\"contents\\/QvNB3emRZYF4BILeXr79JJ2atu358L.webp\",\"driver\":\"local\"}}', '2024-10-01 01:08:08', '2024-10-01 01:08:09'),
(111, 'service', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-cart-plus\",\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"background_image\":{\"path\":\"contents\\/b7HseUG3ajRx1ff3qiiDCP3PM8qDwo.webp\",\"driver\":\"local\"}}', '2024-10-01 01:09:01', '2024-10-01 01:09:02'),
(112, 'service', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-envelope-open\",\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"background_image\":{\"path\":\"contents\\/a90vm3ko9wrBsMQ3Z14YaHIs714eJa.webp\",\"driver\":\"local\"}}', '2024-10-01 01:09:35', '2024-10-01 01:09:36'),
(113, 'service', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-tv\",\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"background_image\":{\"path\":\"contents\\/KM6AU6YOmH8WUmYicKd9LynNnFbMxn.webp\",\"driver\":\"local\"}}', '2024-10-01 01:10:14', '2024-10-01 01:10:14'),
(114, 'service', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-book-open\",\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"background_image\":{\"path\":\"contents\\/BPzgm12yaAzmT6o8Bc7EBWspvYx6GE.webp\",\"driver\":\"local\"}}', '2024-10-01 01:11:02', '2024-10-01 01:11:02'),
(115, 'why_choose_us', 'dark_voilet', 'single', '{\"image\":{\"path\":\"contents\\/g6BTA8XeGuTwYDpY5VWvB1ScsnP9hb.webp\",\"driver\":\"local\"}}', '2024-10-01 01:12:23', '2024-10-01 01:12:23'),
(116, 'why_choose_us', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-users\"}', '2024-10-01 01:13:04', '2024-10-01 01:13:04'),
(117, 'why_choose_us', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-trophy\"}', '2024-10-01 01:13:37', '2024-10-01 01:13:37'),
(118, 'why_choose_us', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-briefcase\"}', '2024-10-01 01:14:16', '2024-10-01 01:14:16'),
(119, 'why_choose_us', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-chart-line\"}', '2024-10-01 01:15:03', '2024-10-01 01:15:03'),
(120, 'achievement', 'dark_voilet', 'single', NULL, '2024-10-01 01:17:24', '2024-10-01 01:17:24'),
(121, 'achievement', 'dark_voilet', 'multiple', '{\"icon\":\"fa-regular fa-users\",\"count\":\"35\"}', '2024-10-01 01:18:22', '2024-10-01 01:19:30'),
(122, 'achievement', 'dark_voilet', 'multiple', '{\"icon\":\"fa-sharp fa-regular fa-file-invoice\",\"count\":\"102\"}', '2024-10-01 01:20:01', '2024-10-01 01:20:01'),
(123, 'achievement', 'dark_voilet', 'multiple', '{\"icon\":\"fa-regular fa-handshake\",\"count\":\"28\"}', '2024-10-01 01:20:19', '2024-10-01 01:20:19'),
(124, 'achievement', 'dark_voilet', 'multiple', '{\"icon\":\"fa-regular fa-trophy-star\",\"count\":\"8\"}', '2024-10-01 01:20:42', '2024-10-01 01:20:42'),
(125, 'how_it_works', 'dark_voilet', 'single', '{\"image\":{\"path\":\"contents\\/jUevXVyHE83HRyM0rQldUL4cB2i8wQ.webp\",\"driver\":\"local\"}}', '2024-10-01 01:21:25', '2024-10-01 01:21:25'),
(126, 'how_it_works', 'dark_voilet', 'multiple', '{\"icon\":\"far fa-address-book\"}', '2024-10-01 01:21:52', '2024-10-01 01:21:52'),
(127, 'how_it_works', 'dark_voilet', 'multiple', '{\"icon\":\"far fa-paper-plane\"}', '2024-10-01 01:22:09', '2024-10-01 01:22:09'),
(128, 'how_it_works', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-angellist\"}', '2024-10-01 01:22:29', '2024-10-01 01:22:29'),
(129, 'testimonial', 'dark_voilet', 'single', NULL, '2024-10-01 01:22:54', '2024-10-01 01:22:54'),
(130, 'testimonial', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/jGpuibbT1YX2cES5N2QTYnucl0SsIo.webp\",\"driver\":\"local\"}}', '2024-10-01 01:25:39', '2024-10-01 01:25:39'),
(131, 'testimonial', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/XlMuD1vx3nyDJ37HT1W3t5eAiAbyh6.webp\",\"driver\":\"local\"}}', '2024-10-01 01:26:28', '2024-10-01 01:26:28'),
(132, 'testimonial', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/jejN3sVsc6q945moVMYLzqtOKYsIoR.webp\",\"driver\":\"local\"}}', '2024-10-01 01:27:07', '2024-10-01 01:27:07'),
(133, 'testimonial', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/PEgV2qgWiIahQfbXegyMx1NCJzxKib.webp\",\"driver\":\"local\"}}', '2024-10-01 01:27:46', '2024-10-01 01:28:10'),
(134, 'testimonial', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/Y1yVKwt6PMdndpuUpDMFRlyE8phrbq.webp\",\"driver\":\"local\"}}', '2024-10-01 01:29:03', '2024-10-01 01:29:03'),
(135, 'payment_partner', 'dark_voilet', 'single', NULL, '2024-10-01 01:29:38', '2024-10-01 01:29:38'),
(136, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/iEM8kti8ApO8aP9U9bs03BlSmOKdzA.webp\",\"driver\":\"local\"}}', '2024-10-01 01:41:43', '2024-10-01 01:41:43'),
(137, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/oJUUikzIubSCKLDOQyrHV9EAdHIm5z.webp\",\"driver\":\"local\"}}', '2024-10-01 01:41:55', '2024-10-01 01:41:56'),
(138, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/e8Et52e1sPquWUjo8wn2YEajrHX61S.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:01', '2024-10-01 01:42:01'),
(139, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/Vf4VawI47v6VSKI6cLQo5Rl9wu5Res.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:06', '2024-10-01 01:42:06'),
(140, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/iUPvnI16Vj4jbwisaQLasSmyzP6cYN.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:11', '2024-10-01 01:42:11'),
(141, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/EcvryyN8xOVKjfZPme9sUUJZyKIK46.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:17', '2024-10-01 01:42:17'),
(142, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/5HqA4GOv5TF1ynb24AjRP4LMyXskMP.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:22', '2024-10-01 01:42:22'),
(143, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/1sbKckyb1L8fSZdKU0UoRtNOfQDO2A.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:28', '2024-10-01 01:42:28'),
(144, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/N1iiaYmk3xwmE1GUhhRPb71wRG2VDQ.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:37', '2024-10-01 01:42:37'),
(145, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/4ysFgneyNtf5bnzouZ7MogDe7yQLho.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:43', '2024-10-01 01:42:43'),
(146, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/tuPe1DLVFNY8AHiyQD6UczgYAfhK3C.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:49', '2024-10-01 01:42:49'),
(147, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/Js2stDmPWeE9HrOWCbadK3FfMJJW7G.webp\",\"driver\":\"local\"}}', '2024-10-01 01:42:59', '2024-10-01 01:42:59'),
(148, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/SM8UtYJ2Yr0WlHIBvYHjuWeIieR0R7.webp\",\"driver\":\"local\"}}', '2024-10-01 01:43:06', '2024-10-01 01:43:06'),
(149, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/4J4CtKwjJe3O6zVntNqyqcPloSZZ4e.webp\",\"driver\":\"local\"}}', '2024-10-01 01:43:12', '2024-10-01 01:43:12'),
(150, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/N2C82tAn6ErJZL1lUbE5UA6MSR59qr.webp\",\"driver\":\"local\"}}', '2024-10-01 01:43:18', '2024-10-01 01:43:18'),
(151, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/XDArYiIwWPq2Dm4vPM6N1CKCMdYyfo.webp\",\"driver\":\"local\"}}', '2024-10-01 01:43:24', '2024-10-01 01:43:24'),
(152, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/BOa0oJdImyCHQ02l9qYk7xBQr7VDtf.webp\",\"driver\":\"local\"}}', '2024-10-01 01:43:29', '2024-10-01 01:43:29'),
(153, 'payment_partner', 'dark_voilet', 'multiple', '{\"image\":{\"path\":\"contents\\/8ve0cjsTdvYmucke6iw3KhyLR3avcs.webp\",\"driver\":\"local\"}}', '2024-10-01 01:43:33', '2024-10-01 01:43:33'),
(154, 'blog', 'dark_voilet', 'single', NULL, '2024-10-01 01:44:15', '2024-10-01 01:44:15'),
(155, 'faq', 'dark_voilet', 'single', '{\"button_link\":\"https:\\/\\/bugfinder.net\\/\"}', '2024-10-01 01:50:09', '2024-10-01 01:50:09'),
(156, 'faq', 'dark_voilet', 'multiple', NULL, '2024-10-01 01:50:58', '2024-10-01 01:50:58'),
(157, 'faq', 'dark_voilet', 'multiple', NULL, '2024-10-01 01:51:19', '2024-10-01 01:51:19'),
(158, 'faq', 'dark_voilet', 'multiple', NULL, '2024-10-01 01:51:33', '2024-10-01 01:51:33'),
(159, 'faq', 'dark_voilet', 'multiple', NULL, '2024-10-01 01:51:48', '2024-10-01 01:51:48'),
(160, 'faq', 'dark_voilet', 'multiple', NULL, '2024-10-01 01:52:05', '2024-10-01 01:52:05'),
(161, 'faq', 'dark_voilet', 'multiple', NULL, '2024-10-01 01:52:30', '2024-10-01 01:52:30'),
(162, 'contact', 'dark_voilet', 'single', '{\"map_link\":\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d193595.15830869428!2d-74.119763973046!3d40.69766374874431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1668508535036!5m2!1sen!2sbd\"}', '2024-10-01 01:55:32', '2024-10-01 01:55:32'),
(163, 'contact', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/www.facebook.com\\/\"}', '2024-10-01 01:56:18', '2024-10-01 01:56:18'),
(164, 'contact', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/\"}', '2024-10-01 01:57:02', '2024-10-01 01:57:02'),
(165, 'contact', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"}', '2024-10-01 01:57:55', '2024-10-01 01:57:55'),
(166, 'contact', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-10-01 01:58:27', '2024-10-01 01:58:27'),
(167, 'footer', 'dark_voilet', 'single', '{\"overlay_image\":{\"path\":\"contents\\/KEPfhhId5F4Cf6v2ctBV4ar5p8aVlr.webp\",\"driver\":\"local\"}}', '2024-10-01 01:59:48', '2024-10-01 01:59:48'),
(168, 'footer', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/www.facebook.com\\/\"}', '2024-10-01 02:00:31', '2024-10-01 02:00:31'),
(169, 'footer', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/x.com\\/\"}', '2024-10-01 02:00:44', '2024-10-01 02:00:44'),
(170, 'footer', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-linkedin\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"}', '2024-10-01 02:01:03', '2024-10-01 02:01:03'),
(171, 'footer', 'dark_voilet', 'multiple', '{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-10-01 02:01:25', '2024-10-01 02:01:25'),
(172, 'register', 'dark_voilet', 'single', NULL, '2024-10-01 02:02:09', '2024-10-01 02:02:09'),
(173, 'how_it_works', 'dark_voilet', 'multiple', '{\"icon\":\"fas fa-hand-holding-usd\"}', '2024-10-01 03:26:18', '2024-10-01 03:26:18'),
(174, 'resent_blog', 'dark_voilet', 'single', NULL, '2024-10-01 20:47:35', '2024-10-01 20:47:35'),
(175, 'how_to_mass_orders', 'dark_voilet', 'single', NULL, '2024-10-02 20:04:41', '2024-10-02 20:04:41'),
(176, 'how_to_mass_orders', 'light_green', 'single', NULL, '2024-10-05 10:18:13', '2024-10-05 10:18:13'),
(177, 'hero', 'minimal', 'single', '{\"image\":{\"path\":\"contents\\/OFT4P9D4u0N7qW0yH76OGGlQ5uJ6nF.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-26 20:30:53', '2024-10-26 20:30:53'),
(178, 'feature', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/EcwElBbOS95PFRk1hcc0Qw7XgAI8PP.webp\",\"driver\":\"local\"}}', '2024-10-26 20:55:40', '2024-10-26 20:59:49'),
(179, 'feature', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/OoNHUTgsI7Y5IvrO7233VwqsELjwF7.webp\",\"driver\":\"local\"}}', '2024-10-26 20:55:59', '2024-10-26 21:00:00'),
(180, 'feature', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/fefsdwwHMQ73s2Ug2rcGxdugfXmVIj.webp\",\"driver\":\"local\"}}', '2024-10-26 20:56:20', '2024-10-26 21:00:07'),
(181, 'about', 'minimal', 'single', '{\"image\":{\"path\":\"contents\\/Z00wizD1pR6a2u1hLEfulyp5WajAZz.webp\",\"driver\":\"local\"}}', '2024-10-26 21:02:35', '2024-10-26 21:02:35'),
(182, 'how_it_works', 'minimal', 'single', NULL, '2024-10-26 21:32:21', '2024-10-26 21:32:21'),
(183, 'how_it_works', 'minimal', 'multiple', '{\"icon\":\"far fa-address-book\"}', '2024-10-26 21:32:58', '2024-10-26 21:32:58'),
(184, 'how_it_works', 'minimal', 'multiple', '{\"icon\":\"fas fa-hand-holding-usd\"}', '2024-10-26 21:33:22', '2024-10-26 21:33:22'),
(185, 'how_it_works', 'minimal', 'multiple', '{\"icon\":\"far fa-paper-plane\"}', '2024-10-26 21:33:48', '2024-10-26 21:33:48'),
(186, 'how_it_works', 'minimal', 'multiple', '{\"icon\":\"fab fa-angellist\"}', '2024-10-26 21:34:19', '2024-10-26 21:34:19'),
(187, 'service', 'minimal', 'single', NULL, '2024-10-26 21:46:45', '2024-10-26 21:46:45'),
(188, 'service', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/beBKXBrITnBi7knY2pbN6TF1yn0Orv.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-26 21:48:29', '2024-10-26 21:48:29'),
(189, 'service', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/ae8N3ut6KmWHf5bsq4WKKK2ungvYnf.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-26 21:49:24', '2024-10-26 21:49:24'),
(190, 'service', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/ek3nDxfoj537untKEWd36sZyJ2EZwB.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-26 21:50:10', '2024-10-26 21:50:10'),
(191, 'counter', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/tgtsasldcnJNtCwwZJCjAeHph8owxA.webp\",\"driver\":\"local\"}}', '2024-10-26 22:02:23', '2024-10-26 22:02:23'),
(192, 'counter', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/Ta9EwOG58bmB6dbWZcS6ox93CdpWpn.webp\",\"driver\":\"local\"}}', '2024-10-26 22:02:50', '2024-10-26 22:02:50'),
(193, 'counter', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/786CuOJ0GtaCnEoFjEfvjWfQMCqKGW.webp\",\"driver\":\"local\"}}', '2024-10-26 22:03:19', '2024-10-26 22:03:19'),
(194, 'counter', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/rayKP8m2YgGlw2M82mG7yisXV1nIyA.webp\",\"driver\":\"local\"}}', '2024-10-26 22:03:57', '2024-10-26 22:03:57'),
(195, 'call_to_action', 'minimal', 'single', '{\"image\":{\"path\":\"contents\\/3DlgHpz89RCw84j0tsbspOfhXEGqr5.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/www.smmforest.com\"}', '2024-10-26 22:17:04', '2024-10-26 22:17:04'),
(196, 'testimonial', 'minimal', 'single', NULL, '2024-10-26 23:58:43', '2024-10-26 23:58:43'),
(197, 'testimonial', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/7uDAFIRQ89tYG8MtQtMb5wqKAn1KCH.webp\",\"driver\":\"local\"}}', '2024-10-26 23:59:38', '2024-10-26 23:59:38'),
(198, 'testimonial', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/2xc6igLYAH1lWCbcFRHi3M9TKEx0s7.webp\",\"driver\":\"local\"}}', '2024-10-27 00:00:11', '2024-10-27 00:15:28'),
(199, 'testimonial', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/o1kpcQblWdA4zfK16G5H8fJA3QXehr.webp\",\"driver\":\"local\"}}', '2024-10-27 00:00:42', '2024-10-27 00:15:48'),
(200, 'testimonial', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/zmdBLEu7E8q9z4DxMQG1AaKIRQyR5u.webp\",\"driver\":\"local\"}}', '2024-10-27 00:01:09', '2024-10-27 00:16:04'),
(201, 'testimonial', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/oT9yakhP6OnDmNu59F8DAzaqBVKVr6.webp\",\"driver\":\"local\"}}', '2024-10-27 00:01:43', '2024-10-27 00:16:14'),
(202, 'blog', 'minimal', 'single', NULL, '2024-10-27 19:39:32', '2024-10-27 19:39:32'),
(203, 'payment_partner', 'minimal', 'single', NULL, '2024-10-27 20:02:00', '2024-10-27 20:02:00'),
(204, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/EKpvpC60IqUFTdmGawp3dFREukrfK3.webp\",\"driver\":\"local\"}}', '2024-10-27 20:03:02', '2024-10-27 20:03:02'),
(205, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/xOh9d4aqyu0HxiVWsck0BmZXtmK2bi.webp\",\"driver\":\"local\"}}', '2024-10-27 20:03:22', '2024-10-27 20:03:22'),
(206, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/5TYxKsDNrAzeyNDHHzzMVOElMWk5OJ.webp\",\"driver\":\"local\"}}', '2024-10-27 20:03:27', '2024-10-27 20:03:27'),
(207, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/3zn3nLuc2PqsQe4hedW4zZrxzUu9r0.webp\",\"driver\":\"local\"}}', '2024-10-27 20:07:31', '2024-10-27 20:07:31'),
(208, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/E3EQuxMpfpk3nrtKB8Ft8kjA0hyBuE.webp\",\"driver\":\"local\"}}', '2024-10-27 20:07:35', '2024-10-27 20:07:35'),
(209, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/ngoVZw6VWcByKxV6bojYw3SN8bUHfb.webp\",\"driver\":\"local\"}}', '2024-10-27 20:07:40', '2024-10-27 20:07:40'),
(210, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/Ah0B4jYb7yQQ9Qfvhj3C23WIbG7Shv.webp\",\"driver\":\"local\"}}', '2024-10-27 20:07:45', '2024-10-27 20:07:45'),
(211, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/A3UHcji1lwdyuZMeM8knW4zZQEeYY6.webp\",\"driver\":\"local\"}}', '2024-10-27 20:07:52', '2024-10-27 20:07:52'),
(212, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/Hp68qUAqc90UYR4N4iZ4u6tB4X4BTe.webp\",\"driver\":\"local\"}}', '2024-10-27 20:07:56', '2024-10-27 20:07:56'),
(213, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/EzArRzZbtnSQv7YZvNZJRwLApM6NaD.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:08', '2024-10-27 20:08:08'),
(214, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/IvqQMjcIsln6qstTaS3eXL33deCN3a.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:13', '2024-10-27 20:08:13'),
(215, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/FRWSvA0K8h3vjsGzpMv6r85YXJkUIv.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:18', '2024-10-27 20:08:18'),
(216, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/oDAMn1a5WMSefqJinxR1yXHuMhkFF7.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:24', '2024-10-27 20:08:24'),
(217, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/6zLJxfwwCVpcWCyGHagjAoHZ5DdIgA.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:28', '2024-10-27 20:08:28'),
(218, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/dNIx7y0swRmwjxWhvKxvKuAfhDM4Z6.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:32', '2024-10-27 20:08:32'),
(219, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/KvlAzmyYQ8HSAC3qH0Mjn4YNgXtKGg.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:37', '2024-10-27 20:08:37'),
(220, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/tLrnfK4tbk8izeFYbpZ9KzPM3yI9o1.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:42', '2024-10-27 20:08:42'),
(221, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/3KEoJsasXQHtYHXET99KEoG7TmqmiO.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:48', '2024-10-27 20:08:48'),
(222, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/juviUFLDyAqCGuzBGi9VJ13wsShIb9.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:53', '2024-10-27 20:08:53'),
(223, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/rqPFBAHj9NaXlRZruAYLLYXK20kSOc.webp\",\"driver\":\"local\"}}', '2024-10-27 20:08:58', '2024-10-27 20:08:58'),
(224, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/8I2nXrAFd8XKH8J4pHjiE8BE8YGGdx.webp\",\"driver\":\"local\"}}', '2024-10-27 20:09:04', '2024-10-27 20:09:04'),
(225, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/1x3md6yUcI4dtUpRD3xR8Eilhmp2VB.webp\",\"driver\":\"local\"}}', '2024-10-27 20:09:16', '2024-10-27 20:09:16'),
(226, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/5rDVdAJmJU8U8lDExPkqhp1BkYGRx6.webp\",\"driver\":\"local\"}}', '2024-10-27 20:09:20', '2024-10-27 20:09:20'),
(227, 'payment_partner', 'minimal', 'multiple', '{\"image\":{\"path\":\"contents\\/elnRj32vJzCRxfEvCitAyZgj7I1pWv.webp\",\"driver\":\"local\"}}', '2024-10-27 20:09:27', '2024-10-27 20:09:27'),
(228, 'footer', 'minimal', 'single', NULL, '2024-10-27 20:12:22', '2024-10-27 20:12:22'),
(229, 'footer', 'minimal', 'multiple', '{\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/www.facebook.com\\/\"}', '2024-10-27 20:13:05', '2024-10-27 20:13:05'),
(230, 'footer', 'minimal', 'multiple', '{\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/twitter.com\\/\"}', '2024-10-27 20:13:39', '2024-10-27 20:13:39'),
(231, 'footer', 'minimal', 'multiple', '{\"icon\":\"fab fa-linkedin-in\",\"link\":\"https:\\/\\/www.linkedin.com\\/\"}', '2024-10-27 20:14:02', '2024-10-27 20:14:02'),
(232, 'footer', 'minimal', 'multiple', '{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-10-27 20:14:21', '2024-10-27 20:14:21'),
(233, 'faq', 'minimal', 'single', NULL, '2024-10-27 21:36:21', '2024-10-27 21:36:21'),
(234, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:37:36', '2024-10-27 21:37:36'),
(235, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:37:57', '2024-10-27 21:37:57'),
(236, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:38:13', '2024-10-27 21:38:13'),
(237, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:38:33', '2024-10-27 21:38:33'),
(239, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:39:09', '2024-10-27 21:39:09'),
(242, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:40:02', '2024-10-27 21:40:02'),
(243, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:41:10', '2024-10-27 21:41:10'),
(244, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:45:30', '2024-10-27 21:45:30'),
(245, 'faq', 'minimal', 'multiple', NULL, '2024-10-27 21:46:14', '2024-10-27 21:46:14'),
(246, 'contact', 'minimal', 'single', NULL, '2024-10-27 22:02:14', '2024-10-27 22:02:14'),
(247, 'login', 'minimal', 'single', NULL, '2024-10-27 22:18:53', '2024-10-27 22:18:53'),
(248, 'register', 'minimal', 'single', NULL, '2024-10-27 23:47:12', '2024-10-27 23:47:12'),
(249, 'forgot_password', 'minimal', 'single', NULL, '2024-10-28 01:09:48', '2024-10-28 01:09:48'),
(250, 'hero', 'deep_blue', 'single', '{\"image\":{\"path\":\"contents\\/7YbAuIW0HdGz9UaRnziHKLjXa2WfPx.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-28 02:39:56', '2024-10-28 02:42:47'),
(251, 'feature', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/FkURiOkmLZMuP2afPKe2dWyfnJgCWF.webp\",\"driver\":\"local\"}}', '2024-10-28 02:47:47', '2024-10-28 02:47:47'),
(252, 'feature', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/6cbArurNzGLNQoNxzaSUgyhUjSFkoq.webp\",\"driver\":\"local\"}}', '2024-10-28 02:48:17', '2024-10-28 02:48:17'),
(253, 'feature', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/FllEm2sdWEe3I9qvNAfQvXWZnU34q6.webp\",\"driver\":\"local\"}}', '2024-10-28 02:48:41', '2024-10-28 02:48:41'),
(254, 'about', 'deep_blue', 'single', '{\"image\":{\"path\":\"contents\\/YBSXgPR86kFzODjIxSYnSlS0Oi7OQC.webp\",\"driver\":\"local\"}}', '2024-10-28 03:06:10', '2024-10-28 03:06:10'),
(255, 'about', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/0NrxvqEGiI8QCQuuqvrdAaPYDyKWtE.webp\",\"driver\":\"local\"}}', '2024-10-28 03:06:55', '2024-10-28 03:06:55'),
(256, 'about', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/RHLyh4H6AOeXxfMrUIE2nCC0VNMOw8.webp\",\"driver\":\"local\"}}', '2024-10-28 03:07:29', '2024-10-28 03:07:29'),
(257, 'about', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/6daP1Qd0VwiU1P7NtedYOSzUAhTYym.webp\",\"driver\":\"local\"}}', '2024-10-28 03:08:00', '2024-10-28 03:08:00'),
(258, 'service', 'deep_blue', 'single', NULL, '2024-10-28 03:12:21', '2024-10-28 03:12:21'),
(259, 'service', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/ywQM0QjxYGoxm8SSwqNPm2C1uMdx2o.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-28 03:14:05', '2024-10-28 03:14:05'),
(260, 'service', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/5FqtbTBzSZJPLGxmThccKQQ2vnPXF1.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-28 03:14:42', '2024-10-28 03:14:42'),
(261, 'service', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/glxPSWAWdKfUC1M48gu1P5DQdGz91f.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-28 03:15:22', '2024-10-28 03:15:23'),
(263, 'how_it_works', 'deep_blue', 'single', NULL, '2024-10-28 03:20:57', '2024-10-28 03:20:57'),
(264, 'how_it_works', 'deep_blue', 'multiple', '{\"icon\":\"far fa-address-book\"}', '2024-10-28 03:21:31', '2024-10-28 03:21:32'),
(265, 'how_it_works', 'deep_blue', 'multiple', '{\"icon\":\"fas fa-hand-holding-usd\"}', '2024-10-28 03:21:47', '2024-10-28 03:21:47'),
(266, 'how_it_works', 'deep_blue', 'multiple', '{\"icon\":\"far fa-paper-plane\"}', '2024-10-28 03:22:15', '2024-10-28 03:22:15'),
(267, 'how_it_works', 'deep_blue', 'multiple', '{\"icon\":\"fab fa-angellist\"}', '2024-10-28 03:22:32', '2024-10-28 03:22:32'),
(268, 'counter', 'deep_blue', 'single', NULL, '2024-10-28 03:28:19', '2024-10-28 03:28:19'),
(269, 'counter', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/Rj1LwSBzNuFkYSpxXAtaw66u4phUvh.webp\",\"driver\":\"local\"}}', '2024-10-28 03:28:52', '2024-10-28 03:28:52'),
(270, 'counter', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/VAJgyc6JoqvDdJujFYuwLTvykAmSi9.webp\",\"driver\":\"local\"}}', '2024-10-28 03:29:23', '2024-10-28 03:29:23'),
(271, 'counter', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/qvVAvu1I3NiTnSt0LE5ynFS50iBL94.webp\",\"driver\":\"local\"}}', '2024-10-28 03:29:44', '2024-10-28 03:29:44'),
(272, 'counter', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/hbYQfaUzcYwtsebP0bsxyRmebDfnPh.webp\",\"driver\":\"local\"}}', '2024-10-28 03:30:13', '2024-10-28 03:30:13'),
(273, 'call_to_action', 'deep_blue', 'single', '{\"image\":{\"path\":\"contents\\/uPxIHQCr35q5tT5W8QwhJgm4qpqR5H.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/www.smmforest.com\"}', '2024-10-28 03:39:08', '2024-10-28 03:39:08'),
(274, 'testimonial', 'deep_blue', 'single', NULL, '2024-10-28 03:43:51', '2024-10-28 03:43:51'),
(275, 'testimonial', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/ULpoBtMczXP47kliXBE4Uqybj1ISZV.webp\",\"driver\":\"local\"}}', '2024-10-28 03:44:44', '2024-10-28 03:44:44'),
(276, 'testimonial', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/4rSPKBadfl1Y8Y5BPr3RwDLK30HaUk.webp\",\"driver\":\"local\"}}', '2024-10-28 03:45:07', '2024-10-28 03:45:07'),
(277, 'testimonial', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/MgZ7n2dRwnGoB3CgtLH7dUuoRxFo3k.webp\",\"driver\":\"local\"}}', '2024-10-28 03:45:29', '2024-10-28 03:45:29'),
(278, 'testimonial', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/RVj5Qpp2mpjSSRMKqeZFpMEa3TUGjk.webp\",\"driver\":\"local\"}}', '2024-10-28 03:45:55', '2024-10-28 03:45:55'),
(279, 'testimonial', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/MYUwNRatg1gB561D6lBQn38G71who6.webp\",\"driver\":\"local\"}}', '2024-10-28 03:46:18', '2024-10-28 03:46:18'),
(280, 'blog', 'deep_blue', 'single', NULL, '2024-10-28 03:51:08', '2024-10-28 03:51:08'),
(281, 'payment_partner', 'deep_blue', 'single', NULL, '2024-10-28 03:58:32', '2024-10-28 03:58:32'),
(282, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/fteea8nvb1Z38ad3H6zfhggfE5uUtV.webp\",\"driver\":\"local\"}}', '2024-10-28 03:58:56', '2024-10-28 03:58:56'),
(283, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/yVajPBQEZON4ItjW74UQlwZshtU6xT.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:02', '2024-10-28 03:59:02'),
(284, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/13v3FVQhDdNquxeQNWhS5KVImmRV73.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:07', '2024-10-28 03:59:07'),
(285, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/pDxiDlaBn4K4vVSQ1IpGXxjUARmrub.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:10', '2024-10-28 03:59:10'),
(286, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/nyMbXf7Br3xIhW9nUmWtNjvOaTLbyH.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:13', '2024-10-28 03:59:13'),
(287, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/BJc8ywcR0TWckb3a3Mr1hLErMYeEE7.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:17', '2024-10-28 03:59:17'),
(288, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/jeRsCbqmI17XAm023fwlu6oNPKbTHp.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:20', '2024-10-28 03:59:20'),
(289, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/MrTOCkkwJeT2t5zldgaFJxf6sVI0yU.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:25', '2024-10-28 03:59:25'),
(290, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/0rXxmZF6ZxU7gJVB5VasF6WI0SWEQ0.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:32', '2024-10-28 03:59:32'),
(291, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/vzYmntt9C7IK2weEoHjRdxblpKTMiA.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:35', '2024-10-28 03:59:35'),
(292, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/uMrDXa1mFZT1663SGHp6aJSeiRkvpc.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:41', '2024-10-28 03:59:41'),
(293, 'payment_partner', 'deep_blue', 'multiple', '{\"image\":{\"path\":\"contents\\/TagUv2aFHdG2LLVCJU68NjobMZB4k1.webp\",\"driver\":\"local\"}}', '2024-10-28 03:59:46', '2024-10-28 03:59:46'),
(294, 'footer', 'deep_blue', 'single', NULL, '2024-10-28 04:04:21', '2024-10-28 04:04:21'),
(295, 'footer', 'deep_blue', 'multiple', '{\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/www.facebook.com\\/\"}', '2024-10-28 04:05:33', '2024-10-28 04:05:33'),
(296, 'footer', 'deep_blue', 'multiple', '{\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/twitter.com\\/\"}', '2024-10-28 04:05:58', '2024-10-28 04:05:58'),
(297, 'footer', 'deep_blue', 'multiple', '{\"icon\":\"fab fa-linkedin-in\",\"link\":\"https:\\/\\/bd.linkedin.com\\/\"}', '2024-10-28 04:06:11', '2024-10-28 04:06:11'),
(298, 'footer', 'deep_blue', 'multiple', '{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-10-28 04:06:22', '2024-10-28 04:06:22'),
(299, 'contact', 'deep_blue', 'single', NULL, '2024-10-28 04:34:28', '2024-10-28 04:34:28'),
(300, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:16:30', '2024-10-28 07:16:30'),
(301, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:16:51', '2024-10-28 07:16:51'),
(302, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:17:13', '2024-10-28 07:17:13'),
(303, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:17:49', '2024-10-28 07:17:49'),
(304, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:18:03', '2024-10-28 07:18:03'),
(305, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:18:20', '2024-10-28 07:18:20'),
(306, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:18:51', '2024-10-28 07:18:51'),
(307, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:19:05', '2024-10-28 07:19:05'),
(308, 'faq', 'deep_blue', 'multiple', NULL, '2024-10-28 07:19:19', '2024-10-28 07:19:19'),
(309, 'login', 'deep_blue', 'single', NULL, '2024-10-28 08:49:35', '2024-10-28 08:49:35'),
(310, 'register', 'deep_blue', 'single', NULL, '2024-10-28 08:52:03', '2024-10-28 08:52:03'),
(311, 'forgot_password', 'deep_blue', 'single', NULL, '2024-10-28 09:00:39', '2024-10-28 09:00:39'),
(312, 'hero', 'light_orange', 'single', '{\"image\":{\"path\":\"contents\\/uzXRl048IwCS2XqEQpHLpXK7z4FOJZ.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-28 19:35:17', '2024-10-28 19:35:18'),
(313, 'feature', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/sK1xpXWBCFrmE1gNbdtHET0MBA3R1I.webp\",\"driver\":\"local\"}}', '2024-10-28 19:43:34', '2024-10-28 19:43:34'),
(314, 'feature', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/C7ROScp1Aqvs5skyJgJdDIYU7P7uo3.webp\",\"driver\":\"local\"}}', '2024-10-28 19:43:55', '2024-10-28 19:43:55'),
(315, 'feature', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/NcWrdtEMDU5vWSwR0ek5b0dVkOEstF.webp\",\"driver\":\"local\"}}', '2024-10-28 19:44:13', '2024-10-28 19:44:13'),
(316, 'about', 'light_orange', 'single', '{\"image\":{\"path\":\"contents\\/cnn34QsZh2PIPUSAkDTQeM1UptRFu8.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/bugfinder.net\\/\",\"youtube_link\":\"https:\\/\\/www.youtube.com\\/embed\\/LXb3EKWsInQ?controls=0\"}', '2024-10-28 19:52:03', '2024-10-28 19:53:20'),
(317, 'service', 'light_orange', 'single', NULL, '2024-10-28 19:59:16', '2024-10-28 19:59:16'),
(318, 'service', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/yKHWMIiIr4qq2VYgxkonCEyAmwsVAh.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-28 20:00:22', '2024-10-28 20:00:22'),
(319, 'service', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/dBqm3JrUTTqxrIF5kqIIodCXEEkvz5.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-28 20:00:57', '2024-10-28 20:00:57'),
(320, 'service', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/xPtaXSSIfe75pZGZH2VRAO6evWGpRQ.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-28 20:01:37', '2024-10-28 20:01:37'),
(321, 'how_it_works', 'light_orange', 'single', NULL, '2024-10-28 20:06:35', '2024-10-28 20:06:35'),
(322, 'how_it_works', 'light_orange', 'multiple', '{\"icon\":\"far fa-address-book\"}', '2024-10-28 20:06:59', '2024-10-28 20:06:59'),
(323, 'how_it_works', 'light_orange', 'multiple', '{\"icon\":\"fas fa-hand-holding-usd\"}', '2024-10-28 20:07:16', '2024-10-28 20:07:16'),
(324, 'how_it_works', 'light_orange', 'multiple', '{\"icon\":\"far fa-paper-plane\"}', '2024-10-28 20:07:34', '2024-10-28 20:07:34'),
(325, 'how_it_works', 'light_orange', 'multiple', '{\"icon\":\"fab fa-angellist\"}', '2024-10-28 20:07:56', '2024-10-28 20:07:56'),
(326, 'counter', 'light_orange', 'single', NULL, '2024-10-28 20:23:02', '2024-10-28 20:23:02'),
(327, 'counter', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/lxS4btd34SpiefBqiMwzGnyujHloRE.webp\",\"driver\":\"local\"}}', '2024-10-28 20:23:39', '2024-10-28 20:23:39'),
(328, 'counter', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/QVJoHbOKCaXFRUfl6NQC9iSsX1WNfO.webp\",\"driver\":\"local\"}}', '2024-10-28 20:24:09', '2024-10-28 20:24:09'),
(329, 'counter', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/K5bZaQ8MfJfOg7rkzsKNQrC9opI8MZ.webp\",\"driver\":\"local\"}}', '2024-10-28 20:24:32', '2024-10-28 20:24:32'),
(330, 'counter', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/LfkRRPFesl0lLG78TtKgaMswuK1wpe.webp\",\"driver\":\"local\"}}', '2024-10-28 20:25:13', '2024-10-28 20:25:13');
INSERT INTO `contents` (`id`, `name`, `theme`, `type`, `media`, `created_at`, `updated_at`) VALUES
(331, 'call_to_action', 'light_orange', 'single', '{\"image\":{\"path\":\"contents\\/mSBfyHkK6SwVWyGfxzW0tRUdcjhjfa.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/www.smmforest.com\"}', '2024-10-28 20:31:27', '2024-10-28 20:31:27'),
(332, 'testimonial', 'light_orange', 'single', NULL, '2024-10-28 20:35:56', '2024-10-28 20:35:56'),
(333, 'testimonial', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/WiuzMJ8w2Pg4h6R344C9VK2olPlk4u.webp\",\"driver\":\"local\"}}', '2024-10-28 20:36:24', '2024-10-28 20:36:24'),
(334, 'testimonial', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/wVOjCFCe1bFTd285Fx4Ja1e0PtqEDH.webp\",\"driver\":\"local\"}}', '2024-10-28 20:36:49', '2024-10-28 20:36:49'),
(335, 'testimonial', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/EZ6HfYcz7yiODWnxmqrmG9331vw2lD.webp\",\"driver\":\"local\"}}', '2024-10-28 20:37:13', '2024-10-28 20:37:13'),
(336, 'testimonial', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/nPzRqYXlIDYDmf5TNsFAZZXlloXFSg.webp\",\"driver\":\"local\"}}', '2024-10-28 20:39:29', '2024-10-28 20:39:29'),
(337, 'testimonial', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/FBblYaYm3Mi4gEqqI1yWwGW0KwY2ls.webp\",\"driver\":\"local\"}}', '2024-10-28 20:39:50', '2024-10-28 20:39:50'),
(338, 'blog', 'light_orange', 'single', NULL, '2024-10-28 20:47:24', '2024-10-28 20:47:24'),
(339, 'payment_partner', 'light_orange', 'single', NULL, '2024-10-28 20:53:58', '2024-10-28 20:53:58'),
(340, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/D5ezxq55TAnauysGCsz5lKgscy9Vmr.webp\",\"driver\":\"local\"}}', '2024-10-28 20:54:19', '2024-10-28 20:54:19'),
(341, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/aGmydBqLHbe3gzef9z9HeevxKtJZJD.webp\",\"driver\":\"local\"}}', '2024-10-28 20:54:24', '2024-10-28 20:54:24'),
(342, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/O11KewGgoNM0cONgxzp6YwJQZ9ExJK.webp\",\"driver\":\"local\"}}', '2024-10-28 20:54:27', '2024-10-28 20:54:27'),
(343, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/RJrR95jgWm9yQPOcMPQr8afH3LIlZ1.webp\",\"driver\":\"local\"}}', '2024-10-28 20:54:32', '2024-10-28 20:54:32'),
(344, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/HxnHtUw2tKEOHLbASnfgp7WbK1hQhV.webp\",\"driver\":\"local\"}}', '2024-10-28 20:54:43', '2024-10-28 20:54:43'),
(345, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/CmMMiZ3mImyHD2bMKz1ALnL6AkAKS6.webp\",\"driver\":\"local\"}}', '2024-10-28 20:54:47', '2024-10-28 20:54:47'),
(346, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/Sc6B3utMGarm7Hdg1et7MkorUoZHX1.webp\",\"driver\":\"local\"}}', '2024-10-28 20:54:50', '2024-10-28 20:54:50'),
(347, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/KbBF1pmsEvQfKZvl3r6vxYtyaLNVgV.webp\",\"driver\":\"local\"}}', '2024-10-28 20:54:55', '2024-10-28 20:54:55'),
(348, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/o9MR1XRYSWOHruvdRkpySNC9pfCLAP.webp\",\"driver\":\"local\"}}', '2024-10-28 20:55:00', '2024-10-28 20:55:00'),
(349, 'payment_partner', 'light_orange', 'multiple', '{\"image\":{\"path\":\"contents\\/64Sec9Sx6Lj4LP5JkAzMYjeiawSeYq.webp\",\"driver\":\"local\"}}', '2024-10-28 20:55:04', '2024-10-28 20:55:04'),
(350, 'news_letter', 'light_orange', 'single', NULL, '2024-10-28 21:00:31', '2024-10-28 21:00:31'),
(351, 'footer', 'light_orange', 'single', NULL, '2024-10-28 21:03:48', '2024-10-28 21:03:48'),
(352, 'footer', 'light_orange', 'multiple', '{\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/www.facebook.com\\/\"}', '2024-10-28 21:04:02', '2024-10-28 21:04:02'),
(353, 'footer', 'light_orange', 'multiple', '{\"icon\":\"fab fa-twitter\",\"link\":\"https:\\/\\/twitter.com\\/\"}', '2024-10-28 21:04:12', '2024-10-28 21:04:12'),
(354, 'footer', 'light_orange', 'multiple', '{\"icon\":\"fab fa-linkedin-in\",\"link\":\"https:\\/\\/bd.linkedin.com\\/\"}', '2024-10-28 21:04:25', '2024-10-28 21:04:25'),
(355, 'footer', 'light_orange', 'multiple', '{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-10-28 21:04:36', '2024-10-28 21:04:36'),
(356, 'faq', 'light_orange', 'single', NULL, '2024-10-28 21:20:24', '2024-10-28 21:20:24'),
(357, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:21:17', '2024-10-28 21:21:17'),
(358, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:21:30', '2024-10-28 21:21:30'),
(359, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:21:43', '2024-10-28 21:21:43'),
(360, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:22:00', '2024-10-28 21:22:00'),
(361, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:22:15', '2024-10-28 21:22:15'),
(362, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:22:27', '2024-10-28 21:22:27'),
(363, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:22:40', '2024-10-28 21:22:40'),
(364, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:22:58', '2024-10-28 21:22:58'),
(365, 'faq', 'light_orange', 'multiple', NULL, '2024-10-28 21:23:12', '2024-10-28 21:23:12'),
(366, 'contact', 'light_orange', 'single', NULL, '2024-10-28 21:31:59', '2024-10-28 21:31:59'),
(367, 'login', 'light_orange', 'single', NULL, '2024-10-28 22:43:46', '2024-10-28 22:43:46'),
(368, 'register', 'light_orange', 'single', NULL, '2024-10-28 22:45:57', '2024-10-28 22:45:57'),
(369, 'forgot_password', 'light_orange', 'single', NULL, '2024-10-28 22:49:36', '2024-10-28 22:49:36'),
(370, 'hero', 'dark_mode', 'single', '{\"image\":{\"path\":\"contents\\/Y3Bzv7bKc3KPG54Lq9V4RVo7KsNRc8.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-29 01:16:30', '2024-10-29 01:16:30'),
(371, 'feature', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/eciHM5GsQL0cpcghntwMp7RDWeXPps.webp\",\"driver\":\"local\"}}', '2024-10-29 01:20:40', '2024-10-29 01:20:40'),
(372, 'feature', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/GE8UHngMh31NZOjPEDFe93uXmAUYqH.webp\",\"driver\":\"local\"}}', '2024-10-29 01:21:02', '2024-10-29 01:21:02'),
(373, 'feature', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/Jlb1PnclVz8Wis2kOci6SNJNmUUznQ.webp\",\"driver\":\"local\"}}', '2024-10-29 01:21:19', '2024-10-29 01:21:19'),
(374, 'about', 'dark_mode', 'single', '{\"image\":{\"path\":\"contents\\/cG9C3u7NCWQ6VodsMzB9BvNug7I1es.webp\",\"driver\":\"local\"}}', '2024-10-29 01:26:15', '2024-10-29 01:26:15'),
(375, 'about', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/aA1o3L10KJa8tUkKDom9vJOOAedPQJ.webp\",\"driver\":\"local\"}}', '2024-10-29 01:26:43', '2024-10-29 01:26:43'),
(376, 'about', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/PsggWiwLmF6d1mhj19V1zKMTqWqKFD.webp\",\"driver\":\"local\"}}', '2024-10-29 01:27:00', '2024-10-29 01:27:01'),
(377, 'about', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/xMoBir1cpLR3Lq7lnFA1JoxlKxNBJM.webp\",\"driver\":\"local\"}}', '2024-10-29 01:27:20', '2024-10-29 01:27:20'),
(378, 'service', 'dark_mode', 'single', NULL, '2024-10-29 01:35:50', '2024-10-29 01:35:50'),
(379, 'service', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/Iil50MLMQ0lPvWzpHWWvvjnBDfQi2p.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/bugfinder.net\\/\"}', '2024-10-29 01:37:56', '2024-10-29 01:37:56'),
(380, 'service', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/92xjoUxHQyuBF69Gn4rbtg9n5bq9Rr.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-29 01:46:43', '2024-10-29 01:46:43'),
(381, 'service', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/qPmYqRXfQATC9I7DHSaveXK0bm8o5R.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/smmforest.com\\/\"}', '2024-10-29 01:47:14', '2024-10-29 01:47:14'),
(382, 'how_it_works', 'dark_mode', 'single', NULL, '2024-10-29 01:51:43', '2024-10-29 01:51:43'),
(383, 'how_it_works', 'dark_mode', 'multiple', '{\"icon\":\"far fa-address-book\"}', '2024-10-29 01:53:41', '2024-10-29 01:53:41'),
(384, 'how_it_works', 'dark_mode', 'multiple', '{\"icon\":\"fas fa-hand-holding-usd\"}', '2024-10-29 01:53:58', '2024-10-29 01:53:58'),
(385, 'how_it_works', 'dark_mode', 'multiple', '{\"icon\":\"far fa-paper-plane\"}', '2024-10-29 01:54:30', '2024-10-29 01:54:30'),
(386, 'how_it_works', 'dark_mode', 'multiple', '{\"icon\":\"fab fa-angellist\"}', '2024-10-29 01:54:51', '2024-10-29 01:54:51'),
(387, 'counter', 'dark_mode', 'single', NULL, '2024-10-29 02:06:28', '2024-10-29 02:06:28'),
(388, 'counter', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/hUecbksM6vPhVP5tdjs1c0bjrhuwuz.webp\",\"driver\":\"local\"}}', '2024-10-29 02:07:02', '2024-10-29 02:07:02'),
(389, 'counter', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/CVfYWMjxVFbnze7vp724VzTsqHDawG.webp\",\"driver\":\"local\"}}', '2024-10-29 02:07:25', '2024-10-29 02:07:25'),
(390, 'counter', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/LNyFrnXUVaXAx3TKDvOzaWw6YQw7Ug.webp\",\"driver\":\"local\"}}', '2024-10-29 02:07:49', '2024-10-29 02:07:49'),
(391, 'counter', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/HVxzO8r9KYod0gnPjDRzUk7hHQ14i0.webp\",\"driver\":\"local\"}}', '2024-10-29 02:08:08', '2024-10-29 02:08:08'),
(392, 'call_to_action', 'dark_mode', 'single', '{\"image\":{\"path\":\"contents\\/JmncgiRaKJpNcvTJ8pkWKHef11OMAD.webp\",\"driver\":\"local\"},\"button_link\":\"https:\\/\\/www.smmforest.com\"}', '2024-10-29 02:10:46', '2024-10-29 02:10:46'),
(393, 'testimonial', 'dark_mode', 'single', NULL, '2024-10-29 02:18:51', '2024-10-29 02:18:51'),
(394, 'testimonial', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/0bwzinrGNMUYfaOZRB3E8PARBNv6jD.webp\",\"driver\":\"local\"}}', '2024-10-29 02:19:36', '2024-10-29 02:19:36'),
(395, 'testimonial', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/MqDClNVtpxCOQhm6f6UGPaBaehiN34.webp\",\"driver\":\"local\"}}', '2024-10-29 02:19:59', '2024-10-29 02:19:59'),
(396, 'testimonial', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/v6RSffD0wm1Wy8Dh9bnBNRFmqif1hq.webp\",\"driver\":\"local\"}}', '2024-10-29 02:20:29', '2024-10-29 02:20:29'),
(397, 'testimonial', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/OT9lCAkPXwmtFggsNLWT2jniX9xh97.webp\",\"driver\":\"local\"}}', '2024-10-29 02:20:51', '2024-10-29 02:20:51'),
(398, 'testimonial', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/MouPxaGbPWGGECN8KBBpqtfDVhyn2i.webp\",\"driver\":\"local\"}}', '2024-10-29 02:21:15', '2024-10-29 02:21:15'),
(399, 'blog', 'dark_mode', 'single', NULL, '2024-10-29 02:24:31', '2024-10-29 02:24:31'),
(400, 'payment_partner', 'dark_mode', 'single', NULL, '2024-10-29 02:25:08', '2024-10-29 02:25:08'),
(401, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/AypGE6vyu1HatBdNRxyk2MhzBPpRRs.webp\",\"driver\":\"local\"}}', '2024-10-29 02:25:27', '2024-10-29 02:25:27'),
(402, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/7IDpYtRvqE7PCEE3XTvbro66tcTOiG.webp\",\"driver\":\"local\"}}', '2024-10-29 02:25:30', '2024-10-29 02:25:30'),
(403, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/IOHSdLoGZo2lmQSKXDMCaXcMSZZtEv.webp\",\"driver\":\"local\"}}', '2024-10-29 02:25:34', '2024-10-29 02:25:34'),
(404, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/3VjHIjkk7KVho5K8Z1x0j8zIpMrJqo.webp\",\"driver\":\"local\"}}', '2024-10-29 02:25:38', '2024-10-29 02:25:38'),
(405, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/oZKF9sPh0CqffcMgjMKfWD4F3BjzmN.webp\",\"driver\":\"local\"}}', '2024-10-29 02:25:41', '2024-10-29 02:25:41'),
(406, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/dcLoUO86dKYEzV4NRIgF6q0SFeIs3t.webp\",\"driver\":\"local\"}}', '2024-10-29 02:25:45', '2024-10-29 02:25:45'),
(407, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/05eN0qTbBVy5BHQj8A3l8q9Z9rFVst.webp\",\"driver\":\"local\"}}', '2024-10-29 02:25:49', '2024-10-29 02:25:49'),
(408, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/vjAnD9qkQWvz3hLtJMbsHuH5HOqfSx.webp\",\"driver\":\"local\"}}', '2024-10-29 02:25:55', '2024-10-29 02:25:55'),
(409, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/vzYtgBcBiYyLPthtIIlRzlbiaLQlso.webp\",\"driver\":\"local\"}}', '2024-10-29 02:26:00', '2024-10-29 02:26:00'),
(410, 'payment_partner', 'dark_mode', 'multiple', '{\"image\":{\"path\":\"contents\\/baZquImjOxUpxMB8CKwCoTQm9xmKQd.webp\",\"driver\":\"local\"}}', '2024-10-29 02:26:05', '2024-10-29 02:26:05'),
(411, 'footer', 'dark_mode', 'single', NULL, '2024-10-29 02:28:06', '2024-10-29 02:28:06'),
(412, 'footer', 'dark_mode', 'multiple', '{\"icon\":\"fab fa-facebook-f\",\"link\":\"https:\\/\\/www.facebook.com\\/\"}', '2024-10-29 02:28:35', '2024-10-29 02:28:35'),
(413, 'footer', 'dark_mode', 'multiple', '{\"icon\":\"fab fa-linkedin-in\",\"link\":\"https:\\/\\/bd.linkedin.com\\/\"}', '2024-10-29 02:28:49', '2024-10-29 02:28:49'),
(414, 'footer', 'dark_mode', 'multiple', '{\"icon\":\"fab fa-linkedin-in\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-10-29 02:29:02', '2024-12-20 22:37:28'),
(415, 'footer', 'dark_mode', 'multiple', '{\"icon\":\"fab fa-instagram\",\"link\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-10-29 02:29:23', '2024-10-29 02:29:23'),
(416, 'contact', 'dark_mode', 'single', NULL, '2024-10-29 03:05:28', '2024-10-29 03:05:28'),
(417, 'login', 'dark_mode', 'single', NULL, '2024-10-29 03:25:45', '2024-10-29 03:25:45'),
(418, 'register', 'dark_mode', 'single', NULL, '2024-10-29 03:27:14', '2024-10-29 03:27:14'),
(419, 'forgot_password', 'dark_mode', 'single', NULL, '2024-10-29 03:28:33', '2024-10-29 03:28:33'),
(420, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:46:45', '2024-10-30 09:46:45'),
(421, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:47:00', '2024-10-30 09:47:00'),
(422, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:47:09', '2024-10-30 09:47:09'),
(423, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:47:18', '2024-10-30 09:47:18'),
(424, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:47:27', '2024-10-30 09:47:27'),
(425, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:47:35', '2024-10-30 09:47:35'),
(426, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:47:44', '2024-10-30 09:47:44'),
(427, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:47:54', '2024-10-30 09:47:54'),
(428, 'faq', 'dark_mode', 'multiple', NULL, '2024-10-30 09:48:03', '2024-10-30 09:48:03'),
(429, 'child_panel_order_notes', 'dark_voilet', 'single', NULL, '2024-11-03 03:54:24', '2024-11-03 03:54:24'),
(430, 'pwa_popup', 'dark_voilet', 'single', '{\"icon_image\":{\"path\":\"contents\\/VtzMQam0oviXakDpbKfb6oAJ5LXSCn.webp\",\"driver\":\"local\"}}', '2024-12-09 02:42:18', '2025-01-01 06:41:22'),
(431, 'child_panel_order_notes', 'minimal', 'single', NULL, '2024-12-21 00:24:20', '2024-12-21 00:24:20'),
(432, 'child_panel_order_notes', 'deep_blue', 'single', NULL, '2024-12-21 00:24:42', '2024-12-21 00:24:42'),
(433, 'child_panel_order_notes', 'dark_mode', 'single', NULL, '2024-12-21 00:25:00', '2024-12-21 00:25:00'),
(434, 'child_panel_order_notes', 'light_orange', 'single', NULL, '2024-12-21 00:25:21', '2024-12-21 00:25:21'),
(435, 'how_to_mass_orders', 'minimal', 'single', NULL, '2024-12-21 23:24:34', '2024-12-21 23:24:34'),
(436, 'how_to_mass_orders', 'deep_blue', 'single', NULL, '2024-12-21 23:25:48', '2024-12-21 23:25:48'),
(437, 'how_to_mass_orders', 'dark_mode', 'single', NULL, '2024-12-21 23:26:07', '2024-12-21 23:26:07'),
(438, 'how_to_mass_orders', 'light_orange', 'single', NULL, '2024-12-21 23:26:57', '2024-12-21 23:26:57'),
(439, 'pwa_popup', 'light_green', 'single', '{\"icon_image\":{\"path\":\"contents\\/0VEroxOLwQz6sD62HWtW1AVqnCV8pD.webp\",\"driver\":\"local\"}}', '2025-01-01 06:40:49', '2025-01-01 06:40:49'),
(440, 'pwa_popup', 'minimal', 'single', '{\"icon_image\":{\"path\":\"contents\\/RgjOqLCktPQ2arsgy2hTvoY5KhUVK5.webp\",\"driver\":\"local\"}}', '2025-01-01 06:41:56', '2025-01-01 06:41:56'),
(441, 'pwa_popup', 'deep_blue', 'single', '{\"icon_image\":{\"path\":\"contents\\/HXHqlXlEyQyFu6bGKaTJr2dfaXDkyk.webp\",\"driver\":\"local\"}}', '2025-01-01 06:42:32', '2025-01-01 06:42:32'),
(442, 'pwa_popup', 'dark_mode', 'single', '{\"icon_image\":{\"path\":\"contents\\/RWIEgP3WpOj6PcBxnt0Lka0CdqUMuo.webp\",\"driver\":\"local\"}}', '2025-01-01 06:43:02', '2025-01-01 06:43:02'),
(443, 'pwa_popup', 'light_orange', 'single', '{\"icon_image\":{\"path\":\"contents\\/tJVaYe3n6hOHIslrDt00ygIKjeKalO.webp\",\"driver\":\"local\"}}', '2025-01-01 06:43:53', '2025-01-01 06:43:53'),
(444, 'pwa_popup', 'light_green', 'multiple', NULL, '2025-01-02 06:53:17', '2025-01-02 06:53:17'),
(445, 'pwa_popup', 'light_green', 'multiple', '{\"carousel_image\":{\"path\":\"contents\\/cQVLnVRM7fvwERy4cyhqbnS38etofc.webp\",\"driver\":\"local\"}}', '2025-01-02 06:55:32', '2025-01-02 06:55:32'),
(446, 'pwa_popup', 'light_green', 'multiple', '{\"carousel_image\":{\"path\":\"contents\\/jwoebqEd5sKNixj0pMzypdTtv7lnRE.webp\",\"driver\":\"local\"}}', '2025-01-02 07:09:06', '2025-01-02 07:09:06'),
(447, 'pwa_popup', 'light_green', 'multiple', '{\"carousel_image\":{\"path\":\"contents\\/5YwVRcs0klWCNVwi95mV8yFNXPiuGf.webp\",\"driver\":\"local\"}}', '2025-01-02 07:09:10', '2025-01-02 07:09:10'),
(448, 'pwa_popup', 'light_green', 'multiple', '{\"carousel_image\":{\"path\":\"contents\\/1fA47F8MprrVOfTJQLwOyeB5w1NNoO.webp\",\"driver\":\"local\"}}', '2025-01-02 07:09:13', '2025-01-02 07:09:13');

-- --------------------------------------------------------

--
-- Table structure for table `content_details`
--

CREATE TABLE `content_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `content_id` bigint(20) DEFAULT NULL,
  `language_id` bigint(20) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `content_details`
--

INSERT INTO `content_details` (`id`, `content_id`, `language_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '{\"title\":\"We Are SMM Matrix\",\"heading\":\"We Will Help You To Grow Your Business\",\"short_description\":\"Social Media Marketing (SMM) is a powerful tool for businesses and individuals to promote their products or services on various social media platforms.\",\"button_name\":\"get started\",\"video_text\":\"WATCH VIDEO\"}', '2024-05-05 01:55:06', '2024-10-01 00:28:45'),
(2, 2, 1, '{\"title\":\"Save Your Time\",\"short_description\":\"Organize your to-do list with ease. Create, edit, and prioritize tasks to ensure nothing falls through the cracks.\"}', '2024-05-05 02:37:52', '2024-08-30 06:56:05'),
(3, 3, 1, '{\"title\":\"Best Strategy\",\"short_description\":\"Craft your success with the best strategy tailored to your goals.\"}', '2024-05-05 02:38:35', '2024-05-05 02:38:35'),
(4, 4, 1, '{\"title\":\"Affordable Price For You\",\"short_description\":\"Get the best value with affordable prices tailored just for you.\"}', '2024-05-05 02:39:20', '2024-05-05 02:39:20'),
(5, 5, 1, '{\"title\":\"Customer Support\",\"short_description\":\"Reliable customer support ready to assist you whenever you need.\"}', '2024-05-05 02:39:55', '2024-05-05 02:39:55'),
(6, 6, 1, '{\"title\":\"About Us\",\"heading\":\"SMM Matrix Redefining Social Media Excellence\",\"button_name\":\"Contact Us\",\"description\":\"<p>At SMM Matrix, we\'re not just a social media marketing agency \\u2014 we\'re your partners in digital success. Established with a passion for propelling businesses to new heights through strategic online presence, we bring a unique blend of creativity, innovation, and expertise to every project.<br><\\/p>\"}', '2024-05-05 02:59:33', '2024-05-05 03:08:09'),
(7, 7, 1, '{\"title\":\"Strategic Brilliance :\",\"description\":\"<p>We don\'t just create content; we craft strategies. Our team of experts meticulously plans every campaign, ensuring that your brand\'s message resonates with your target audience.<br><\\/p>\"}', '2024-05-05 03:00:56', '2024-05-05 03:00:56'),
(8, 8, 1, '{\"title\":\"Time Efficiency :\",\"description\":\"<p>In the fast-paced world of social media, timing is everything. Our streamlined processes and agile approach guarantee timely delivery without compromising on quality.<br><\\/p>\"}', '2024-05-05 03:01:34', '2024-05-05 03:01:34'),
(9, 9, 1, '{\"title\":\"our service\",\"heading\":\"What Service Do We Have For Your Business\",\"description\":\"<p>Welcome to SMM Matrix, where our mission is to empower businesses through a spectrum of digital services that enhance online presence, engage audiences, and drive results.<br><\\/p>\"}', '2024-05-05 03:29:13', '2024-05-05 03:29:13'),
(10, 10, 1, '{\"service_name\":\"Social Marketing\",\"description\":\"<p>Our social media strategy begins with meticulous content planning. We curate content that aligns with your brand voice, resonates with your audience, and strategically communicates your message across various social platforms.<br><\\/p>\"}', '2024-05-05 03:30:02', '2024-05-05 03:30:02'),
(11, 11, 1, '{\"service_name\":\"SEO Strategy\",\"description\":\"<p>Our SEO journey begins with comprehensive keyword research. We identify high-impact keywords relevant to your industry, ensuring that every piece of content is strategically crafted to resonate with both search engines and your target audience.<br><\\/p>\"}', '2024-05-05 03:30:47', '2024-05-05 03:30:47'),
(12, 12, 1, '{\"service_name\":\"Product Branding\",\"description\":\"<p>We work closely with you to understand the essence of your product and your brand vision. From logo design to color palettes and typography, we craft a brand identity that reflects your unique story, ensuring a distinctive and memorable presence in the market.<br><\\/p>\"}', '2024-05-05 03:31:15', '2024-05-05 03:31:15'),
(13, 13, 1, '{\"service_name\":\"Email Marketing\",\"description\":\"<p>Our email marketing journey begins with strategic campaign planning. We work closely with you to understand your business goals, target audience, and unique value propositions. From there, we design email campaigns that guide subscribers through engaging and conversion-focused journeys.<br><\\/p>\"}', '2024-05-05 03:31:44', '2024-05-05 03:31:44'),
(14, 14, 1, '{\"service_name\":\"Digital Marketing\",\"description\":\"We begin by understanding your business goals, target audience, and unique value propositions. Our team then crafts an integrated digital marketing strategy that combines various channels \\u2014 from social media and email marketing to SEO and paid advertising \\u2014 ensuring a cohesive and effective approach.\"}', '2024-05-05 03:32:22', '2024-05-05 03:32:22'),
(15, 15, 1, '{\"service_name\":\"Content Writter\",\"description\":\"<p>Our content writers specialize in the art of storytelling. Whether it\'s a blog post, article, or marketing copy, we infuse your brand\'s unique story into every piece of content, creating narratives that leave a lasting impression on your audience.<br><\\/p>\"}', '2024-05-05 03:32:49', '2024-05-05 03:32:49'),
(16, 16, 1, '{\"title\":\"why choose us\",\"heading\":\"Elevating Your Experience, Amplifying Your Success\",\"description\":\"<p>At SMM Matrix, we recognize that selecting a partner for your business needs is a significant decision. Here\'s why choosing us is not just a choice but an investment in a dynamic and impactful collaboration<br><\\/p>\"}', '2024-05-05 04:01:39', '2024-05-05 04:01:39'),
(17, 17, 1, '{\"title\":\"Professional Team:\",\"description\":\"Our professional team is the backbone of our success. Comprising seasoned experts in their respective fields, we bring a wealth of knowledge and experience to every project.\"}', '2024-05-05 04:02:27', '2024-12-30 22:51:40'),
(18, 18, 1, '{\"title\":\"Experience:\",\"description\":\"<p>Our legacy is built on a foundation of experience. With a proven track record and a portfolio of successful projects, SMM Matrix stands as a testament to our commitment to excellence.<br><\\/p>\"}', '2024-05-05 04:03:51', '2024-05-05 04:03:51'),
(19, 19, 1, '{\"title\":\"Success Guaranteed:\",\"description\":\"<p>Success is not just a goal; it\'s a commitment we make to every client. At SMM Matrix, we stand by our promise to deliver measurable and impactful results.<br><\\/p>\"}', '2024-05-05 04:04:24', '2024-05-05 04:04:24'),
(20, 20, 1, '{\"title\":\"Data-Driven Approach:\",\"description\":\"<p>In a digital age, decisions without data are mere guesses. At SMM Matrix, we pride ourselves on our data-driven approach. Every strategy.<br><\\/p>\"}', '2024-05-05 04:04:56', '2024-12-30 22:51:10'),
(21, 21, 1, '{\"title\":\"Achivement\",\"heading\":\"We Complete 1500+ Project Yearly Successfully &amp; Still Counting\",\"description\":\"<p>At SMM Matrix, our track record speaks volumes about our dedication and proficiency. Year after year, we successfully complete over 1500 projects, each one a testament to our commitment to excellence and client satisfaction.<br><\\/p>\"}', '2024-05-05 19:38:12', '2024-05-05 19:38:12'),
(22, 22, 1, '{\"count\":\"35\",\"title\":\"HAPPY CLIENTS\"}', '2024-05-05 19:38:34', '2024-05-05 19:38:34'),
(23, 23, 1, '{\"count\":\"102\",\"title\":\"PROJECTS DONE\"}', '2024-05-05 19:38:54', '2024-05-05 19:38:54'),
(24, 24, 1, '{\"count\":\"28\",\"title\":\"TEAM ADVISORS\"}', '2024-05-05 19:39:20', '2024-05-05 19:39:20'),
(25, 25, 1, '{\"count\":\"30\",\"title\":\"GLORIOUS YEARS\"}', '2024-05-05 19:39:39', '2024-05-05 19:39:39'),
(26, 26, 1, '{\"title\":\"How It Works\",\"heading\":\"Unveiling the Seamless Path to Success\",\"description\":\"<p>Embark on a seamless journey towards success with SMM Matrix. Our \\\"How It Works\\\" process is designed to simplify your experience while maximizing the impact of our services. Explore the steps below to understand how our collaboration unfolds.<br><\\/p>\"}', '2024-05-05 19:54:04', '2024-05-05 19:54:04'),
(27, 27, 1, '{\"title\":\"Register &amp; Log in\",\"short_description\":\"Creating an account is the first step. then you need to log in\"}', '2024-05-05 19:54:32', '2024-05-05 19:54:32'),
(28, 28, 1, '{\"title\":\"Add Fund\",\"short_description\":\"Next, pick a payment method and add funds to your account\"}', '2024-05-05 19:54:48', '2024-05-05 19:54:48'),
(29, 29, 1, '{\"title\":\"Select a service\",\"short_description\":\"Select the services you want and get ready to receive more publicity.\"}', '2024-05-05 19:55:12', '2024-05-05 19:55:12'),
(30, 30, 1, '{\"title\":\"Enjoy Super Results\",\"short_description\":\"You can enjoy incredible results when your order is complete\"}', '2024-05-05 19:55:32', '2024-05-05 19:55:32'),
(31, 31, 1, '{\"title\":\"Testimonial\",\"heading\":\"what our Clients Say\",\"description\":\"<p>Our clients praise our excellence, personalized approach, and exceptional results. They highlight our innovative solutions, responsiveness, and strategic thinking as key factors in their success.<br><\\/p>\"}', '2024-05-05 20:18:16', '2024-05-05 20:18:16'),
(32, 32, 1, '{\"name\":\"Crol Mickey\",\"address\":\"Sydney, Australia\",\"rating\":\"4\",\"review\":\"I like this panel, I actually face a problem with my first try but support was there, and they help me to fix that problem then I tried YouTube views and it\'s one of the best.\"}', '2024-05-05 20:20:03', '2024-05-05 20:20:03'),
(33, 33, 1, '{\"name\":\"Jim Morison\",\"address\":\"London, UK\",\"rating\":\"4\",\"review\":\"I ordered Facebook page likes &amp; IG followers, I received good quality followers highly recommended, and I\'ll definitely order again Thank you.\"}', '2024-05-05 20:22:32', '2024-05-05 20:39:01'),
(34, 34, 1, '{\"name\":\"Tom Haris\",\"address\":\"New York, USA\",\"rating\":\"4\",\"review\":\"Well, I was testing the Instagram followers service and works really well! Takes about 5-10 minutes to start and complete the followers super fast! So, I like the service.\"}', '2024-05-05 20:23:57', '2024-05-05 20:39:09'),
(35, 35, 1, '{\"name\":\"Alex Cruis\",\"address\":\"Dublin, Ireland\",\"rating\":\"4\",\"review\":\"I\'m thrilled to hear that Binary SMM has been such a positive experience for you with its excellent prices, user-friendly interface, and consistent updates\\u2014keep enjoying the benefits!\"}', '2024-05-05 20:24:48', '2024-05-05 20:41:00'),
(36, 36, 1, '{\"name\":\"Akihisa Daisuke\",\"address\":\"Tokyo, Japan\",\"rating\":\"5\",\"review\":\"Used this service for a week and made 15 orders of different services. There was a pause in supplying likes, but eventually everything was delivered at full.\"}', '2024-05-05 20:26:37', '2024-05-05 20:26:37'),
(37, 37, 1, '{\"title\":\"PAYMENTS\",\"heading\":\"Our Payment Partners\",\"description\":\"<p>At SMM Matrix, we understand that a seamless and secure payment process is crucial for your peace of mind.<br><\\/p>\"}', '2024-05-05 20:48:36', '2024-05-05 20:48:36'),
(53, 53, 1, NULL, '2024-05-05 21:58:37', '2024-05-05 21:58:37'),
(54, 54, 1, NULL, '2024-05-05 21:59:01', '2024-05-05 21:59:01'),
(55, 55, 1, NULL, '2024-05-05 21:59:05', '2024-05-05 21:59:05'),
(56, 56, 1, NULL, '2024-05-05 21:59:09', '2024-05-05 21:59:09'),
(57, 57, 1, NULL, '2024-05-05 21:59:16', '2024-05-05 21:59:16'),
(58, 58, 1, NULL, '2024-05-05 21:59:19', '2024-05-05 21:59:19'),
(59, 59, 1, NULL, '2024-05-05 21:59:24', '2024-05-05 21:59:24'),
(60, 60, 1, NULL, '2024-05-05 21:59:28', '2024-05-05 21:59:28'),
(61, 61, 1, NULL, '2024-05-05 21:59:32', '2024-05-05 21:59:32'),
(62, 62, 1, NULL, '2024-05-05 21:59:37', '2024-05-05 21:59:37'),
(63, 63, 1, NULL, '2024-05-05 21:59:41', '2024-05-05 21:59:41'),
(64, 64, 1, NULL, '2024-05-05 21:59:46', '2024-05-05 21:59:46'),
(65, 65, 1, NULL, '2024-05-05 21:59:50', '2024-05-05 21:59:50'),
(66, 66, 1, NULL, '2024-05-05 21:59:54', '2024-05-05 21:59:54'),
(67, 67, 1, NULL, '2024-05-05 21:59:59', '2024-05-05 21:59:59'),
(68, 68, 1, NULL, '2024-05-05 22:00:04', '2024-05-05 22:00:04'),
(69, 69, 1, NULL, '2024-05-05 22:00:07', '2024-05-05 22:00:07'),
(70, 70, 1, NULL, '2024-05-05 22:00:12', '2024-05-05 22:00:12'),
(71, 71, 1, NULL, '2024-05-05 22:00:16', '2024-05-05 22:00:16'),
(72, 72, 1, '{\"title\":\"Faqs\",\"heading\":\"any questions? we have answers!\",\"button_name\":\"contact us\",\"description\":\"<p>SocialSprint is a cutting-edge communication platform designed to streamline conversations for individuals and businesses. It offers a range of features to enhance collaboration and connectivity.<br><\\/p>\"}', '2024-05-05 22:13:40', '2024-05-05 22:13:40'),
(73, 73, 1, '{\"question\":\"What services does your SMM website offer?\",\"answer\":\"We provide a comprehensive range of social media marketing services, including strategy development, content creation, platform management, advertising campaigns, and analytics. Explore our [Services Page] for a detailed overview.\"}', '2024-05-05 22:14:17', '2024-05-05 22:14:17'),
(74, 74, 1, '{\"question\":\"How can I get started with your SMM services?\",\"answer\":\"<p>Getting started is simple! Visit our [Get Started] page, fill out the form, and our team will reach out to you promptly to discuss your goals and tailor a strategy to meet your specific needs.<br><\\/p>\"}', '2024-05-05 22:14:35', '2024-05-05 22:14:35'),
(75, 75, 1, '{\"question\":\"What social media platforms do you specialize in?\",\"answer\":\"<p>Our expertise spans across major social media platforms, including but not limited to Facebook, Instagram, Twitter, LinkedIn, and Pinterest. We tailor our strategies to align with the unique dynamics of each platform.<br><\\/p>\"}', '2024-05-05 22:14:52', '2024-05-05 22:14:52'),
(76, 76, 1, '{\"question\":\"How do you create content for social media?\",\"answer\":\"<p>Our content creation process involves understanding your brand, target audience, and goals. We craft engaging and shareable content, including images, videos, and captions, to enhance your brand presence and engagement.<br><\\/p>\"}', '2024-05-05 22:15:08', '2024-05-05 22:15:08'),
(77, 77, 1, '{\"question\":\"Can I track the performance of my social media campaigns?\",\"answer\":\"<p>Absolutely. We provide detailed analytics reports that track the performance of your social media campaigns. These reports include metrics such as reach, engagement, conversion rates, and more, giving you valuable insights into the effectiveness of your strategies.<br><\\/p>\"}', '2024-05-05 22:15:24', '2024-05-05 22:15:24'),
(78, 78, 1, '{\"question\":\"Is my information secure when using your services?\",\"answer\":\"<p>Yes, we prioritize the security and confidentiality of your information. Our website and services incorporate industry-standard security measures to protect your data.<br><\\/p>\"}', '2024-05-05 22:15:43', '2024-05-05 22:15:43'),
(79, 79, 1, '{\"title\":\"OUR BLOGS\",\"sub_title\":\"our news &amp; blogs\",\"short_description\":\"Welcome to our dynamic world of insights and information! Our Blogs section is your gateway to staying informed, discovering industry trends, and gaining valuable knowledge to propel your business forward.\"}', '2024-05-23 02:15:49', '2024-05-23 02:27:27'),
(80, 80, 1, '{\"title\":\"OUR BLOGS\",\"sub_title\":\"our news &amp; blogs\",\"short_description\":\"Welcome to our dynamic world of insights and information! Our Blogs section is your gateway to staying informed, discovering industry trends, and gaining valuable knowledge to propel your business forward.\"}', '2024-05-23 02:35:50', '2024-05-23 02:35:50'),
(81, 81, 1, '{\"heading\":\"Contact Information\",\"short_description\":\"Give us a call or drop by anytime, we endeavour to answer all enquiries within 24 hours on business days. We will be happy to answer your questions.\",\"form_heading\":\"Drop Us a Line\",\"form_short_description\":\"A contact form allows website visitors to easily communicate with the owner or support team by submitting their name, email, subject, and message, enhancing engagement and streamlining communication.\",\"phone\":\"+45345847431324\",\"email\":\"demo@example.com\",\"address\":\"22 Baker Street, London\"}', '2024-05-23 02:51:25', '2024-05-23 02:51:25'),
(86, 86, 1, '{\"name\":\"Facebook\"}', '2024-05-23 03:13:33', '2024-05-23 03:13:33'),
(87, 87, 1, '{\"name\":\"Twitter\"}', '2024-05-23 03:16:34', '2024-05-23 03:16:34'),
(88, 88, 1, '{\"name\":\"Linkedin\"}', '2024-05-23 03:17:02', '2024-05-23 03:17:02'),
(89, 89, 1, '{\"name\":\"Instagram\"}', '2024-05-23 03:17:34', '2024-05-23 03:17:34'),
(90, 90, 1, '{\"short_description\":\"Experience the SMM Matrix Advantage - Empowering Social Success with Data-Driven Solutions.\",\"news_letter_heading\":\"Subscribe To Our Mailing List And Stay Up To Date\",\"copyright\":\"Copyright \\u00a92024 SMM Matrix All Rights Reserved\"}', '2024-05-24 19:36:24', '2024-12-19 01:42:41'),
(91, 91, 1, '{\"name\":\"Facebook\"}', '2024-05-24 19:36:52', '2024-05-24 19:36:52'),
(92, 92, 1, '{\"name\":\"Twitter\"}', '2024-05-24 19:37:24', '2024-05-24 19:37:24'),
(93, 93, 1, '{\"name\":\"Linkedin\"}', '2024-05-24 19:37:48', '2024-05-24 19:37:48'),
(94, 94, 1, '{\"name\":\"Instagram\"}', '2024-05-24 19:38:06', '2024-05-24 19:38:06'),
(95, 95, 1, '{\"title\":\"WELCOME BACK!\",\"sub_title\":\"Hey Enter your details to get sign in to your account\"}', '2024-05-25 02:43:38', '2024-11-05 01:47:24'),
(96, 96, 1, '{\"title\":\"WELCOME BACK!\",\"sub_title\":\"Hey Enter your details to get sign in to your account\"}', '2024-05-25 03:03:48', '2024-05-25 03:03:48'),
(97, 97, 1, '{\"description\":\"<p>You put the service ID followed by | followed by the link followed by |\\r\\n                                followed\\r\\n                                by quantity on each line To get the service ID of a service please check here:<\\/p>\\r\\n                            <br>\\r\\n                            <a href=\\\"\\\">https:\\/\\/bugfinder.net\\/services<\\/a>\\r\\n                            <p>Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your\\r\\n                                3\\r\\n                                accounts: abcd, asdf, qwer<\\/p>\\r\\n                            <br>\\r\\n                            <p>From the Services List, the service ID for this service \\u201c[Instagram\\r\\n                                Followers\\r\\n                                [100% Real - 30 Days Guarantee- NEW SERVICE\\u201d is 3740<\\/p>\\r\\n                            <br>\\r\\n                            <p>Let\\u2019s say you want to add 1000 followers for each account, the output will\\r\\n                                be\\r\\n                                like this: ID|Link|Quantity<\\/p>\\r\\n                            <br>\\r\\n                            <p>or in this example:<\\/p>\\r\\n                            <ul>\\r\\n                                <li>3740|1000|https:\\/\\/bugfinder.net\\/<\\/li>\\r\\n                                <li>3740|1000|https:\\/\\/bugfinder.net\\/<\\/li>\\r\\n                                <li>3740|1000|https:\\/\\/bugfinder.net\\/<\\/li>\\r\\n                            <\\/ul>\"}', '2024-06-25 22:38:01', '2024-06-26 00:57:21'),
(102, 100, 1, '{\"short_description\":\"<h4>What is a Child Panel?<br><\\/h4><p style=\\\"font-weight:400;font-size:16px;\\\">From its name, a child panel refers to a panel that has its connection derived from a regular panel. A child panel has fewer ranges of features when compared with a regular panel, and because of this, the user experience might be a little bit lesser.<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">SMM Panel are known as sites where you can purchase marketing services for your customers,<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">They are of two types namely regular panels and child panels. This article sets out to inform you about child panels and how they differ from the more popular regular cheap SMM panel.<br><br><\\/p><h4>Differences between child panel and regular panel<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">As stated earlier, a child panel is not the same as a regular panel, even though they are both SMM panels. The following are some of the differences that exist between a child panel and a regular panel.<br><br><\\/p><h4>How does the Child Panel work?<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">A child panel works in a way that you can specify the category of services that you want to provide to your customers. Whether you want to buy from a regular panel and sell to your customer\\u2019s likes, followers, or video views on Facebook and Instagram, you can easily do this on your child panel. All you have to do is export all the services after selecting the categories of choice to indicate the services you want to provide from your seller panel. You can sell to your customer and manage your customer orders without knowing about your seller panel. Your customer will never know about your seller panel. Your Childpanel will only connect with your seller panel by API.<\\/p>\"}', '2024-09-25 20:15:06', '2024-09-25 20:22:22'),
(103, 101, 1, '{\"title\":\"We Are SMM Matrix\",\"heading\":\"We Will Help You To Grow Your Business\",\"short_description\":\"Social Media Marketing (SMM) is a powerful tool for businesses and individuals to promote their products or services on various social media platforms.\",\"button_name\":\"our services\",\"video_text\":\"WATCH VIDEO\",\"login_title\":\"Welcome back!\",\"login_subtitle\":\"Hey Enter your details to get sign in to your account\"}', '2024-10-01 00:31:21', '2024-10-01 02:12:44'),
(104, 102, 1, '{\"title\":\"Welcome back!\",\"sub_title\":\"Hey Enter your details to get sign in to your account\"}', '2024-10-01 00:37:56', '2024-10-01 00:37:56'),
(105, 103, 1, '{\"title\":\"Save Your Time\",\"short_description\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus luctus neculam\"}', '2024-10-01 00:43:39', '2024-10-01 00:43:39'),
(106, 104, 1, '{\"title\":\"Best Strategy\",\"short_description\":\"Best Strategy offers the most effective plan of action to achieve optimal results in a given situation\"}', '2024-10-01 00:44:23', '2024-10-01 00:44:23'),
(107, 105, 1, '{\"title\":\"Affordable Price\",\"short_description\":\"Affordable Price ensures high-quality products or services at a cost that fits within a reasonable budget.\"}', '2024-10-01 00:45:14', '2024-10-01 00:45:14'),
(108, 106, 1, '{\"title\":\"Customer Support\",\"short_description\":\"Customer Support provides prompt and reliable assistance to resolve inquiries and issues for a seamless customer experience.\"}', '2024-10-01 00:46:09', '2024-10-01 00:46:09'),
(109, 107, 1, '{\"title\":\"About Us\",\"heading\":\"SMM Matrix Redefining Social Media Excellence\",\"button_name\":\"Contact Us\",\"description\":\"<p>At SMM Matrix, we\'re not just a social media marketing agency \\u2014 we\'re \\r\\nyour partners in digital success. Established with a passion for \\r\\npropelling businesses to new heights through strategic online presence, \\r\\nwe bring a unique blend of creativity, innovation, and expertise to \\r\\nevery project.<br><\\/p>\"}', '2024-10-01 00:48:27', '2024-10-01 00:48:27'),
(110, 108, 1, '{\"title\":\"Our Service\",\"heading\":\"What Service Do We Have For Your Business\",\"short_description\":\"<p>Welcome to SMM Matrix, where our mission is to empower businesses \\r\\nthrough a spectrum of digital services that enhance online presence, \\r\\nengage audiences, and drive results.<br><\\/p>\"}', '2024-10-01 00:49:18', '2024-10-07 02:57:58'),
(111, 109, 1, '{\"name\":\"SEO Strategy\",\"short_description\":\"<p>Our social media strategy begins with meticulous content planning. We curate content that aligns with your brand voice, resonates with your audience, and strategically communicates your message across various social platforms.<br><\\/p>\",\"button_name\":\"Learn More\"}', '2024-10-01 01:00:54', '2024-10-01 01:02:41'),
(112, 110, 1, '{\"name\":\"Social Marketing\",\"short_description\":\"<p>Our social media strategy begins with meticulous content planning. We curate content that aligns with your brand voice, resonates with your audience, and strategically communicates your message across various social platforms.<br><\\/p>\",\"button_name\":\"Learn more\"}', '2024-10-01 01:08:09', '2024-10-01 01:08:09'),
(113, 111, 1, '{\"name\":\"Product Branding\",\"short_description\":\"<p>We work closely with you to understand the essence of your product and your brand vision. From logo design to color palettes and typography, we craft a brand identity that reflects your unique story, ensuring a distinctive and memorable presence in the market.<br><\\/p>\",\"button_name\":\"Learn more\"}', '2024-10-01 01:09:02', '2024-10-01 01:09:02'),
(114, 112, 1, '{\"name\":\"Email Marketing\",\"short_description\":\"<p>Our email marketing journey begins with strategic campaign planning. We work closely with you to understand your business goals, target audience, and unique value propositions. From there, we design email campaigns that guide subscribers through engaging and conversion-focused journeys.<br><\\/p>\",\"button_name\":\"Learn more\"}', '2024-10-01 01:09:36', '2024-10-01 01:09:36'),
(115, 113, 1, '{\"name\":\"Digital Marketing\",\"short_description\":\"<p>We begin by understanding your business goals, target audience, and unique value propositions. Our team then crafts an integrated digital marketing strategy that combines various channels \\u2014 from social media and email marketing to SEO and paid advertising \\u2014 ensuring a cohesive and effective approach.<br><\\/p>\",\"button_name\":\"Learn more\"}', '2024-10-01 01:10:14', '2024-10-01 01:10:14'),
(116, 114, 1, '{\"name\":\"Content Writter\",\"short_description\":\"<p>Our content writers specialize in the art of storytelling. Whether it\'s a blog post, article, or marketing copy, we infuse your brand\'s unique story into every piece of content, creating narratives that leave a lasting impression on your audience.<br><\\/p>\",\"button_name\":\"Learn more\"}', '2024-10-01 01:11:02', '2024-10-01 01:11:02'),
(117, 115, 1, '{\"title\":\"why choose us\",\"heading\":\"Elevating Your Experience, Amplifying Your Success\",\"short_description\":\"<p>At SMM Matrix, we recognize that selecting a partner for your business \\r\\nneeds is a significant decision. Here\'s why choosing us is not just a \\r\\nchoice but an investment in a dynamic and impactful collaboration<br><\\/p>\"}', '2024-10-01 01:12:23', '2024-10-01 01:12:23'),
(118, 116, 1, '{\"title\":\"Professional Team:\",\"description\":\"<p>At SMM Matrix, our professional team is the backbone of our success. \\r\\nComprising seasoned experts in their respective fields, we bring a \\r\\nwealth of knowledge and experience to every project.<br><\\/p>\"}', '2024-10-01 01:13:04', '2024-10-01 01:13:04'),
(119, 117, 1, '{\"title\":\"Success Guaranteed:\",\"description\":\"<p>Success is not just a goal; it\'s a commitment we make to every client. \\r\\nAt SMM Matrix, we stand by our promise to deliver measurable and \\r\\nimpactful results.<br><\\/p>\"}', '2024-10-01 01:13:37', '2024-10-01 01:13:37'),
(120, 118, 1, '{\"title\":\"Experience:\",\"description\":\"<p>Our legacy is built on a foundation of experience. With a proven track \\r\\nrecord and a portfolio of successful projects, SMM Matrix stands as a \\r\\ntestament to our commitment to excellence.<br><\\/p>\"}', '2024-10-01 01:14:16', '2024-10-01 01:14:16'),
(121, 119, 1, '{\"title\":\"Data-Driven Approach:\",\"description\":\"<p>In a digital age, decisions without data are mere guesses. At SMM \\r\\nMatrix, we pride ourselves on our data-driven approach. Every strategy, \\r\\nand every solution is backed by comprehensive data analysis.<br><\\/p>\"}', '2024-10-01 01:15:03', '2024-10-01 01:15:03'),
(122, 120, 1, '{\"title\":\"Achivement\",\"heading\":\"We Complete 1500+ Project Yearly Successfully &amp; Still Counting\",\"description\":\"<p>At SMM Matrix, our track record speaks volumes about our dedication and \\r\\nproficiency. Year after year, we successfully complete over 1500 \\r\\nprojects, each one a testament to our commitment to excellence and \\r\\nclient satisfaction<br><\\/p>\"}', '2024-10-01 01:17:24', '2024-10-01 01:17:24'),
(123, 121, 1, '{\"title\":\"HAPPY CLIENTS\"}', '2024-10-01 01:18:22', '2024-10-01 01:19:30'),
(124, 122, 1, '{\"title\":\"PROJECTS DONE\"}', '2024-10-01 01:20:01', '2024-10-01 01:20:01'),
(125, 123, 1, '{\"title\":\"TEAM ADVISORS 8+\"}', '2024-10-01 01:20:19', '2024-10-01 01:20:19'),
(126, 124, 1, '{\"title\":\"GLORIOUS YEARS\"}', '2024-10-01 01:20:42', '2024-10-01 01:20:42'),
(127, 125, 1, '{\"title\":\"How It Works\",\"heading\":\"Unveiling the Seamless Path to Success\",\"description\":\"<p>Embark on a seamless journey towards success with SMM Matrix. Our \\\"How It Works\\\" process is designed to simplify your experience while maximizing the impact of our services. Explore the steps below to understand how our collaboration unfolds:<br><\\/p>\"}', '2024-10-01 01:21:25', '2024-10-01 01:21:25'),
(128, 126, 1, '{\"title\":\"Register &amp; Log in\",\"short_description\":\"Creating an account is the first step. then you need to log in\"}', '2024-10-01 01:21:52', '2024-10-01 01:21:52'),
(129, 127, 1, '{\"title\":\"Select a service\",\"short_description\":\"Select the services you want and get ready to receive more publicity\"}', '2024-10-01 01:22:09', '2024-10-01 01:22:09'),
(130, 128, 1, '{\"title\":\"Enjoy Super Results\",\"short_description\":\"You can enjoy incredible results when your order is complete\"}', '2024-10-01 01:22:29', '2024-10-01 01:22:29'),
(131, 129, 1, '{\"title\":\"Testimonial\",\"heading\":\"what our Clients Say\",\"short_description\":\"<p>Help agencies to define their new business objectives and then create professional software. Lorem ipsum, dolor sit amet<br><\\/p>\"}', '2024-10-01 01:22:54', '2024-10-01 01:22:54'),
(132, 130, 1, '{\"name\":\"Crol Mickey\",\"location\":\"Sydney, Australia\",\"rating\":\"5\",\"review\":\"Excellent service, fast delivery, and great results! Highly recommend for boosting social presence.\"}', '2024-10-01 01:25:39', '2024-10-01 01:25:39'),
(133, 131, 1, '{\"name\":\"Jim Morison\",\"location\":\"London, UK\",\"rating\":\"5\",\"review\":\"Affordable, reliable, and quick. My social media engagement has skyrocketed. Will use again!\"}', '2024-10-01 01:26:28', '2024-10-01 01:26:28'),
(134, 132, 1, '{\"name\":\"Tom Haris\",\"location\":\"New York, USA\",\"rating\":\"5\",\"review\":\"Outstanding service! Quick results and excellent communication. My social media has never looked better.\"}', '2024-10-01 01:27:07', '2024-10-01 03:23:17'),
(135, 133, 1, '{\"name\":\"Alex Cruis\",\"location\":\"Dublin, Ireland\",\"rating\":\"5\",\"review\":\"Amazing SMM services at a reasonable price, fast delivery, and effective growth on all platforms.\"}', '2024-10-01 01:27:46', '2024-10-01 01:27:46'),
(136, 134, 1, '{\"name\":\"Mitsubishi\",\"location\":\"Tokyo, Japan\",\"rating\":\"5\",\"review\":\"Top-notch service! Boosted my followers significantly, with excellent support and fast results.\"}', '2024-10-01 01:29:03', '2024-10-01 01:29:03'),
(137, 135, 1, '{\"title\":\"PAYMENTS\",\"heading\":\"Our Payment Partners\",\"description\":\"<p>At SMM Matrix, we understand that a seamless and secure payment process is crucial for your peace of mind.<br><\\/p>\"}', '2024-10-01 01:29:38', '2024-10-01 01:29:38'),
(138, 136, 1, NULL, '2024-10-01 01:41:43', '2024-10-01 01:41:43'),
(139, 137, 1, NULL, '2024-10-01 01:41:56', '2024-10-01 01:41:56'),
(140, 138, 1, NULL, '2024-10-01 01:42:01', '2024-10-01 01:42:01'),
(141, 139, 1, NULL, '2024-10-01 01:42:06', '2024-10-01 01:42:06'),
(142, 140, 1, NULL, '2024-10-01 01:42:11', '2024-10-01 01:42:11'),
(143, 141, 1, NULL, '2024-10-01 01:42:17', '2024-10-01 01:42:17'),
(144, 142, 1, NULL, '2024-10-01 01:42:22', '2024-10-01 01:42:22'),
(145, 143, 1, NULL, '2024-10-01 01:42:28', '2024-10-01 01:42:28'),
(146, 144, 1, NULL, '2024-10-01 01:42:37', '2024-10-01 01:42:37'),
(147, 145, 1, NULL, '2024-10-01 01:42:43', '2024-10-01 01:42:43'),
(148, 146, 1, NULL, '2024-10-01 01:42:49', '2024-10-01 01:42:49'),
(149, 147, 1, NULL, '2024-10-01 01:42:59', '2024-10-01 01:42:59'),
(150, 148, 1, NULL, '2024-10-01 01:43:06', '2024-10-01 01:43:06'),
(151, 149, 1, NULL, '2024-10-01 01:43:12', '2024-10-01 01:43:12'),
(152, 150, 1, NULL, '2024-10-01 01:43:18', '2024-10-01 01:43:18'),
(153, 151, 1, NULL, '2024-10-01 01:43:24', '2024-10-01 01:43:24'),
(154, 152, 1, NULL, '2024-10-01 01:43:29', '2024-10-01 01:43:29'),
(155, 153, 1, NULL, '2024-10-01 01:43:33', '2024-10-01 01:43:33'),
(156, 154, 1, '{\"title\":\"Our blogs\",\"sub_title\":\"our news &amp; blogs\",\"short_description\":\"Welcome to our dynamic world of insights and information! Our Blogs section is your gateway to staying informed, discovering industry trends, and gaining valuable knowledge to propel your business forward.\"}', '2024-10-01 01:44:15', '2024-10-01 01:44:15'),
(157, 155, 1, '{\"title\":\"Faqs\",\"heading\":\"any questions? we have answers!\",\"button_name\":\"contact us\",\"short_description\":\"<p>SMM is a cutting-edge communication platform designed to streamline conversations for individuals and businesses. It offers a range of features to enhance collaboration and connectivity.<br><\\/p>\"}', '2024-10-01 01:50:09', '2024-10-01 01:50:09'),
(158, 156, 1, '{\"question\":\"What services does your SMM website offer?\",\"answer\":\"<p>We provide a comprehensive range of social media marketing services, including strategy development, content creation, platform management, advertising campaigns, and analytics. Explore our [Services Page] for a detailed overview.<br><\\/p>\"}', '2024-10-01 01:50:58', '2024-10-01 01:50:58'),
(159, 157, 1, '{\"question\":\"How can I get started with your SMM services?\",\"answer\":\"<p>Getting started is simple! Visit our [Get Started] page, fill out the form, and our team will reach out to you promptly to discuss your goals and tailor a strategy to meet your specific needs.<br><\\/p>\"}', '2024-10-01 01:51:19', '2024-10-01 01:51:19'),
(160, 158, 1, '{\"question\":\"What social media platforms do you specialize in?\",\"answer\":\"<p>Our expertise spans across major social media platforms, including but not limited to Facebook, Instagram, Twitter, LinkedIn, and Pinterest. We tailor our strategies to align with the unique dynamics of each platform.<br><\\/p>\"}', '2024-10-01 01:51:33', '2024-10-01 01:51:33'),
(161, 159, 1, '{\"question\":\"How do you create content for social media?\",\"answer\":\"<p>Our content creation process involves understanding your brand, target audience, and goals. We craft engaging and shareable content, including images, videos, and captions, to enhance your brand presence and engagement.<br><\\/p>\"}', '2024-10-01 01:51:48', '2024-10-01 01:51:48'),
(162, 160, 1, '{\"question\":\"Can I track the performance of my social media campaigns?\",\"answer\":\"<p>Absolutely. We provide detailed analytics reports that track the performance of your social media campaigns. These reports include metrics such as reach, engagement, conversion rates, and more, giving you valuable insights into the effectiveness of your strategies.<br><\\/p>\"}', '2024-10-01 01:52:05', '2024-10-01 01:52:05'),
(163, 161, 1, '{\"question\":\"Is my information secure when using your services?\",\"answer\":\"Yes, we prioritize the security and confidentiality of your information. Our website and services incorporate industry-standard security measures to protect your data.\"}', '2024-10-01 01:52:30', '2024-10-01 01:52:30'),
(164, 162, 1, '{\"heading\":\"Contact Information\",\"short_description\":\"Give us a call or drop by anytime, we endeavour to answer all enquiries within 24 hours on business days. We will be happy to answer your questions.\",\"form_heading\":\"Drop Us a Line\",\"form_short_description\":\"A contact form enables users to easily reach out with inquiries, feedback, or support requests. It\'s a simple and efficient way to connect with your business directly.\",\"phone\":\"+45345847431324\",\"email\":\"demo@example.com\",\"address\":\"22 Baker Street, London, UK\"}', '2024-10-01 01:55:32', '2024-10-01 01:55:32'),
(165, 163, 1, '{\"name\":\"Facebook\"}', '2024-10-01 01:56:18', '2024-10-01 01:56:18'),
(166, 164, 1, '{\"name\":\"Twitter\"}', '2024-10-01 01:57:02', '2024-10-01 01:57:02'),
(167, 165, 1, '{\"name\":\"Linkedin\"}', '2024-10-01 01:57:55', '2024-10-01 01:57:55'),
(168, 166, 1, '{\"name\":\"Instagram\"}', '2024-10-01 01:58:27', '2024-10-01 01:58:27'),
(169, 167, 1, '{\"site_short_description\":\"Experience the SMM Matrix Advantage - Empowering Social Success with Data-Driven Solutions.\",\"news_letter_heading\":\"Subscribe To Our Mailing List And Stay Up To Date\"}', '2024-10-01 01:59:48', '2024-10-01 01:59:48'),
(170, 168, 1, '{\"name\":\"Facebook\"}', '2024-10-01 02:00:31', '2024-10-01 02:00:31'),
(171, 169, 1, '{\"name\":\"Twitter\"}', '2024-10-01 02:00:44', '2024-10-01 02:00:44'),
(172, 170, 1, '{\"name\":\"Linkedin\"}', '2024-10-01 02:01:03', '2024-10-01 02:01:03'),
(173, 171, 1, '{\"name\":\"Instagram\"}', '2024-10-01 02:01:25', '2024-10-01 02:01:25'),
(174, 172, 1, '{\"title\":\"Welcome back!\",\"sub_title\":\"Hey Enter your details to get sign in to your account\"}', '2024-10-01 02:02:09', '2024-10-01 02:02:09'),
(175, 173, 1, '{\"title\":\"Add Fund\",\"short_description\":\"Next, pick a payment method and add funds to your account\"}', '2024-10-01 03:26:18', '2024-10-01 03:26:18'),
(176, 174, 1, '{\"title\":\"Our blogs\",\"sub_title\":\"Recent blogs\",\"short_description\":\"Welcome to our dynamic world of insights and information! Our Blogs section is your gateway to staying informed, discovering industry trends, and gaining valuable knowledge to propel your business forward.\"}', '2024-10-01 20:47:35', '2024-10-01 20:47:35'),
(177, 175, 1, '{\"heading\":\"How Mass Order works?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here:<\\/p><p>https:\\/\\/bugfinder.com\\/services<\\/p><p>Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer<\\/p><p><span>Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity<\\/span><br><\\/p><p>or in this example:<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p>\"}', '2024-10-02 20:04:41', '2024-10-02 20:06:01'),
(178, 176, 1, '{\"heading\":\"How Mass Order works?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here:<\\/p><p><a href=\\\"https:\\/\\/bugfinder.com\\/services\\\">https:\\/\\/bugfinder.com\\/services<\\/a><\\/p><p><br><\\/p><p>Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer<\\/p><p>Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity<\\/p><p><br><\\/p><p>or in this example:<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p>\"}', '2024-10-05 10:18:13', '2025-01-01 05:25:36'),
(179, 177, 1, '{\"title\":\"Grow your business with our SMM solution\",\"short_description\":\"<p>SMM Forest is simply the fastest, cheapest, biggest panel in the SMM World. The best social media marketing panel for resellers #1Top SMMPanel. We are providing an Opportunity to make handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/p>\",\"button_name\":\"Learn More\"}', '2024-10-26 20:30:53', '2024-10-26 20:30:53'),
(180, 178, 1, '{\"title\":\"Link Building\",\"short_description\":\"We are providing an Opportunity to make handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.\"}', '2024-10-26 20:55:40', '2024-10-26 20:55:40'),
(181, 179, 1, '{\"title\":\"Customer Support\",\"short_description\":\"SMM Forest comes with a dedicated team to drive a world-class customer\\u2019s support. we will Add Daily new service offer and improving support system for fast support\"}', '2024-10-26 20:55:59', '2024-10-26 20:55:59'),
(182, 180, 1, '{\"title\":\"Automatic Payments\",\"short_description\":\"Most SMM Panels make you input payment information every time you make an order. Set up an automatic payment method with  SMM Forest.\"}', '2024-10-26 20:56:20', '2024-10-26 20:56:20'),
(183, 181, 1, '{\"title\":\"About Us\",\"sub_title\":\"WHO WE ARE\",\"short_title\":\"We help to grow your see Smm business.\",\"short_description\":\"<p><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\">SMM Forest is one of the renowned SMM reseller Panel existing on the internet.<\\/span><\\/p><p><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\">We have been providing services for 5+ years and provide services for almost every kind of Social Media. Our Social Media Marketing Services include SMM for Facebook, Instagram, Twitter, Youtube, Tiktok, LinkedIn, Shazam, and many more.<\\/span><\\/p><p><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\">We are providing an Opportunity to make a handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/span><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\"><\\/span><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\"><\\/span><\\/p>\",\"youtube_link\":\"https:\\/\\/www.youtube.com\\/embed\\/LXb3EKWsInQ?controls=0\"}', '2024-10-26 21:02:35', '2024-10-26 21:02:35'),
(184, 182, 1, '{\"title\":\"WORKS\",\"sub_title\":\"HOW IT WORKS\",\"short_title\":\"How We Are Helping\",\"short_description\":\"<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Mollitia praesentium ipsum expedita? Optio adipisci at veniam reiciendis minus nam dolores nobis, consequatur odit consequuntur, quibusdam aperiam cumque porro nostrum assumenda. lorem ipsum, dolor sit amet consecettur adisiscing elit.<\\/p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus animi magni impedit molestias, consequuntur provident. Doloribus eius aut nihil iste! Illum porro quo labore totam provident id repellat vitae facere?<\\/p>\"}', '2024-10-26 21:32:21', '2024-10-26 21:32:21'),
(185, 183, 1, '{\"title\":\"Register &amp; Log in\",\"short_description\":\"<p>Creating an account is the first step. then you need to log in<\\/p>\"}', '2024-10-26 21:32:58', '2024-10-26 21:32:58'),
(186, 184, 1, '{\"title\":\"Add Fund\",\"short_description\":\"<p>Next, pick a payment method and add funds to your account<\\/p>\"}', '2024-10-26 21:33:22', '2024-10-26 21:33:22'),
(187, 185, 1, '{\"title\":\"Select a service\",\"short_description\":\"<p>Select the services you want and get ready to receive more publicity<\\/p>\"}', '2024-10-26 21:33:48', '2024-10-26 21:33:48'),
(188, 186, 1, '{\"title\":\"Enjoy Super Results\",\"short_description\":\"<p>You can enjoy incredible results when your order is complete<\\/p>\"}', '2024-10-26 21:34:19', '2024-10-26 21:34:19'),
(189, 187, 1, '{\"title\":\"Services\",\"sub_title\":\"What We Provide\",\"short_title\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque accusantium, quaerat perferendis consectetur.\"}', '2024-10-26 21:46:45', '2024-10-26 21:46:45'),
(190, 188, 1, '{\"title\":\"Best SMM Panel\",\"short_description\":\"<p>SMM Forest provides the highest quality of promotions. We are one of the best SMM reseller panels including some special services out there online.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-26 21:48:29', '2024-10-26 21:48:29'),
(191, 189, 1, '{\"title\":\"Website Growth\",\"short_description\":\"<p>SMM Forest is a modern and efficient wholesale panel. We try to provide you with instant promotions on different social media platforms.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-26 21:49:24', '2024-10-26 21:49:24'),
(192, 190, 1, '{\"title\":\"SMM Ranking\",\"short_description\":\"<p>We provide guaranteed service on our website SMM server.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-26 21:50:10', '2024-10-26 21:50:10'),
(193, 191, 1, '{\"title\":\"HAPPY CLIENTS\",\"number_of_data\":\"320\"}', '2024-10-26 22:02:23', '2024-10-26 22:02:23'),
(194, 192, 1, '{\"title\":\"PROJECTS DONE\",\"number_of_data\":\"850\"}', '2024-10-26 22:02:50', '2024-10-26 22:02:50'),
(195, 193, 1, '{\"title\":\"TEAM ADVISORS\",\"number_of_data\":\"28\"}', '2024-10-26 22:03:19', '2024-10-26 22:03:19'),
(196, 194, 1, '{\"title\":\"GLORIOUS YEARS\",\"number_of_data\":\"8\"}', '2024-10-26 22:03:57', '2024-10-26 22:03:57'),
(197, 195, 1, '{\"title\":\"We Will Drive More Customers To Your Business Than Any Other Online Source.\",\"sub_title\":\"Multiply your Business\\u2019 Facebook Traffic 10x\",\"button_name\":\"Learn More\"}', '2024-10-26 22:17:04', '2024-10-26 22:17:04'),
(198, 196, 1, '{\"title\":\"Testimonial\",\"sub_title\":\"CLIENTS APPRAISAL\",\"short_title\":\"What Clients Say\",\"short_description\":\"<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque accusantium, quaerat perferendis consectetur.<\\/p>\"}', '2024-10-26 23:58:43', '2024-10-26 23:58:43'),
(199, 197, 1, '{\"name\":\"Maria Jacket\",\"designation\":\"Web Developer\",\"description\":\"<p>I like this panel, I actually face a problem with my first try but support was there, and they help me to fix that problem then I tried YouTube views and it\'s one of the best.<\\/p>\"}', '2024-10-26 23:59:38', '2024-10-26 23:59:38'),
(200, 198, 1, '{\"name\":\"Alica Fox\",\"designation\":\"Team Hunter\",\"description\":\"<p>I ordered Facebook page likes &amp; IG followers, I received good quality followers highly recommended, and I\'ll definitely order again Thank you.<\\/p>\"}', '2024-10-27 00:00:11', '2024-10-27 00:00:11'),
(201, 199, 1, '{\"name\":\"Donald Trump\",\"designation\":\"CEO\",\"description\":\"<p>Well, I was testing the Instagram followers service and works really well! Takes about 5-10 minutes to start and complete the followers super fast! So, I like the service.<\\/p>\"}', '2024-10-27 00:00:42', '2024-10-27 00:00:42'),
(202, 200, 1, '{\"name\":\"Tom Moddy\",\"designation\":\"Business Executive\",\"description\":\"<p>I have been using Binary SMM for a week now and it is easily the best panel I have used. Great prices, easy to use and always updated. Great job!<\\/p>\"}', '2024-10-27 00:01:09', '2024-10-27 00:01:09'),
(203, 201, 1, '{\"name\":\"Oskaa\",\"designation\":\"Head of Ideas\",\"description\":\"<p>Used this service for a week and made 15 orders of different services. There was a pause in supplying likes, but eventually everything was delivered at full.<\\/p>\"}', '2024-10-27 00:01:43', '2024-10-27 00:01:43'),
(204, 202, 1, '{\"title\":\"Blog\",\"sub_title\":\"READ OUR BLOG\",\"short_title\":\"Latest News From Blog\"}', '2024-10-27 19:39:32', '2024-10-27 19:39:32'),
(205, 203, 1, '{\"title\":\"Our Payment Partners\"}', '2024-10-27 20:02:00', '2024-10-27 20:02:00'),
(206, 204, 1, NULL, '2024-10-27 20:03:02', '2024-10-27 20:03:02'),
(207, 205, 1, NULL, '2024-10-27 20:03:22', '2024-10-27 20:03:22'),
(208, 206, 1, NULL, '2024-10-27 20:03:27', '2024-10-27 20:03:27'),
(209, 207, 1, NULL, '2024-10-27 20:07:31', '2024-10-27 20:07:31'),
(210, 208, 1, NULL, '2024-10-27 20:07:36', '2024-10-27 20:07:36'),
(211, 209, 1, NULL, '2024-10-27 20:07:40', '2024-10-27 20:07:40'),
(212, 210, 1, NULL, '2024-10-27 20:07:45', '2024-10-27 20:07:45'),
(213, 211, 1, NULL, '2024-10-27 20:07:52', '2024-10-27 20:07:52'),
(214, 212, 1, NULL, '2024-10-27 20:07:56', '2024-10-27 20:07:56'),
(215, 213, 1, NULL, '2024-10-27 20:08:08', '2024-10-27 20:08:08'),
(216, 214, 1, NULL, '2024-10-27 20:08:13', '2024-10-27 20:08:13'),
(217, 215, 1, NULL, '2024-10-27 20:08:18', '2024-10-27 20:08:18'),
(218, 216, 1, NULL, '2024-10-27 20:08:24', '2024-10-27 20:08:24'),
(219, 217, 1, NULL, '2024-10-27 20:08:28', '2024-10-27 20:08:28'),
(220, 218, 1, NULL, '2024-10-27 20:08:32', '2024-10-27 20:08:32'),
(221, 219, 1, NULL, '2024-10-27 20:08:37', '2024-10-27 20:08:37'),
(222, 220, 1, NULL, '2024-10-27 20:08:42', '2024-10-27 20:08:42'),
(223, 221, 1, NULL, '2024-10-27 20:08:48', '2024-10-27 20:08:48'),
(224, 222, 1, NULL, '2024-10-27 20:08:53', '2024-10-27 20:08:53'),
(225, 223, 1, NULL, '2024-10-27 20:08:58', '2024-10-27 20:08:58'),
(226, 224, 1, NULL, '2024-10-27 20:09:04', '2024-10-27 20:09:04'),
(227, 225, 1, NULL, '2024-10-27 20:09:16', '2024-10-27 20:09:16'),
(228, 226, 1, NULL, '2024-10-27 20:09:20', '2024-10-27 20:09:20'),
(229, 227, 1, NULL, '2024-10-27 20:09:27', '2024-10-27 20:09:27'),
(230, 228, 1, '{\"site_short_description\":\"We focus on the needs of small to middle market businesses to improve and grow their return.\",\"phone\":\"+44-20-4526-2356\",\"email\":\"smm@gmail.com\",\"address\":\"22 Baker Street, London\"}', '2024-10-27 20:12:22', '2024-10-27 20:12:22'),
(231, 229, 1, '{\"name\":\"Facebook\"}', '2024-10-27 20:13:05', '2024-10-27 20:13:05'),
(232, 230, 1, '{\"name\":\"Twitter\"}', '2024-10-27 20:13:39', '2024-10-27 20:13:39'),
(233, 231, 1, '{\"name\":\"Linkedin\"}', '2024-10-27 20:14:02', '2024-10-27 20:14:02'),
(234, 232, 1, '{\"name\":\"Instagram\"}', '2024-10-27 20:14:21', '2024-10-27 20:14:21'),
(235, 233, 1, '{\"title\":\"Frequently Asked Questions\",\"short_details\":\"<p>SMM Forest gives you the best marketing tool so you can topple the Competition.<\\/p>\"}', '2024-10-27 21:36:21', '2024-10-27 21:36:21');
INSERT INTO `content_details` (`id`, `content_id`, `language_id`, `description`, `created_at`, `updated_at`) VALUES
(236, 234, 1, '{\"title\":\"What is Partial status?\",\"description\":\"<p>Partial Status is when we partially refund the remains of an order. Sometimes for some reasons we are unable to deliver a full order, so we refund you the remaining undelivered amount. Example: You bought an order with quantity 10 000 and charges 10$, let\'s say we delivered 9 000 and the remaining 1 000 we couldn\'t deliver, then we will \\\"Partial\\\" the order and refund you the remaining 1 000 (1$ in this example).<\\/p>\"}', '2024-10-27 21:37:36', '2024-10-27 21:37:36'),
(237, 235, 1, '{\"title\":\"What is Drip Feed?\",\"description\":\"<p>Drip Feed is a service that we are offering so you would be able to put the same order multiple times automatically. Example: let\'s say you want to get 1000 likes on your Instagram Post but you want to get 100 likes each 30 minutes, you will put Link: Your Post Link Quantity: 100 Runs: 10 (as you want to run this order 10 times, if you want to get 2000 likes, you will run it 20 times, etc\\u2026) Interval: 30 (because you want to get 100 likes on your post each 30 minutes, if you want each hour, you will put 60 because the time is in minutes) P.S: Never order more quantity than the maximum which is written on the service name (Quantity x Runs), Example if the service\'s max is 4000, you don\\u2019t put Quantity: 500 and Run: 10, because total quantity will be 500x10 = 5000 which is bigger than the service max (4000). Also never put the Interval below the actual start time (some services need 60 minutes to start, don\\u2019t put Interval less than the service start time or it will cause a fail in your order).<\\/p>\"}', '2024-10-27 21:37:57', '2024-10-27 21:37:57'),
(238, 236, 1, '{\"title\":\"How do I use mass order?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here: https:\\/\\/justanotherpanel.com\\/services Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer From the Services List @ https:\\/\\/justanotherpanel.com\\/services, the service ID for this service \\u201cInstagram Followers [15K] [REAL] \\u26a1\\ufe0f\\ud83d\\udca7\\u2b50\\u201d is 102 Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity or in this example: 102|abcd|1000 102|asdf|1000 102|qwer|1000<\\/p>\"}', '2024-10-27 21:38:13', '2024-10-27 21:38:13'),
(239, 237, 1, '{\"title\":\"I want a panel like yours \\/ I want to resell your services how?\",\"description\":\"<p>To get a panel like ours, please check jap to rent a panel, and then you can connect to us via API easily!<\\/p>\"}', '2024-10-27 21:38:33', '2024-10-27 21:38:33'),
(241, 239, 1, '{\"title\":\"Can I get a discount?\",\"description\":\"<p>No, we don\\u2019t offer any discount, the price of our services is fixed!<\\/p>\"}', '2024-10-27 21:39:09', '2024-10-27 21:39:09'),
(244, 242, 1, '{\"title\":\"How to get youtube comment link?\",\"description\":\"<p><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\">Find the timestamp that is located next to your username above your comment (for example: \\\"3 days ago\\\") and hover over it then right click and \\\"Copy Link Address\\\". The link will be something like this: https:\\/\\/www.youtube.com\\/watch?v=12345&amp;lc=a1b21etc instead of just https:\\/\\/www.youtube.com\\/watch?v=12345 To be sure that you got the correct link, paste it in your browser\'s address bar and you will see that the comment is now the first one below the video and it says \\\"Highlighted comment\\\".<\\/span><\\/p>\"}', '2024-10-27 21:40:02', '2024-10-27 21:40:02'),
(245, 243, 1, '{\"title\":\"Which youtube view service can be used with monetizable video?\",\"description\":\"<p>The one that has \\\"Monetized\\\" in its service\' name.<\\/p>\"}', '2024-10-27 21:41:10', '2024-10-27 21:41:10'),
(246, 244, 1, '{\"title\":\"What is \'Instagram Saves\', and what does it do?\",\"description\":\"<p>Instagram Saves is when a user saves a post to his history on Instagram (by pressing the save button near the like button). A lot of saves for a post increase its impression.<\\/p>\"}', '2024-10-27 21:45:30', '2024-10-27 21:45:30'),
(247, 245, 1, '{\"title\":\"The link must be added before the user goes live or after?\",\"description\":\"<p>After he goes live, or just 5 second before he goes!<\\/p>\"}', '2024-10-27 21:46:14', '2024-10-27 21:46:14'),
(248, 246, 1, '{\"heading\":\"GET IN TOUCH\",\"sub_heading\":\"Have Questions?\",\"title\":\"Contact Us\",\"sub_title\":\"Give us a call or drop by anytime, we endeavour to answer all enquiries within 24 hours on business days. We will be happy to answer your questions.\",\"address\":\"22 Baker Street, London\",\"email\":\"smm@gmail.com\",\"phone\":\"+44-20-4526-2356\",\"footer_short_details\":\"<p>We focus on the needs of small to middle market businesses to improve and grow their return.<\\/p>\"}', '2024-10-27 22:02:14', '2024-10-27 22:02:14'),
(249, 247, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><span>Facebook\\u00a0<\\/span>:\\u00a0SMM Matrix<\\/li><li><span>Channel\\u00a0<\\/span>: SMM Matrix<\\/li><li><p><span>Phone\\u00a0<\\/span>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-27 22:18:53', '2024-10-27 22:18:53'),
(250, 248, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-27 23:47:12', '2024-10-27 23:47:12'),
(251, 249, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-28 01:09:48', '2024-10-28 01:10:04'),
(252, 250, 1, '{\"title\":\"Grow your business with our SMM solution\",\"short_description\":\"<p>SMM Forest is simply the fastest, cheapest, biggest panel in the SMM World. The best social media marketing panel for resellers #1Top SMMPanel. We are providing an Opportunity to make handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/p>\",\"button_name\":\"Learn More\"}', '2024-10-28 02:39:56', '2024-10-28 02:39:56'),
(253, 251, 1, '{\"title\":\"Link Building\",\"short_description\":\"<p>We are providing an Opportunity to make handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/p>\"}', '2024-10-28 02:47:47', '2024-10-28 02:47:47'),
(254, 252, 1, '{\"title\":\"Customer Support\",\"short_description\":\"<p>SMM Forest comes with a dedicated team to drive a world-class customer\\u2019s support. we will Add Daily new service offer and improving support system for fast support<\\/p>\"}', '2024-10-28 02:48:17', '2024-10-28 02:48:17'),
(255, 253, 1, '{\"title\":\"Automatic Payments\",\"short_description\":\"<p>Most SMM Panels make you input payment information every time you make an order. Set up an automatic payment method with\\u00a0 SMM Forest.<\\/p>\"}', '2024-10-28 02:48:41', '2024-10-28 02:48:41'),
(256, 254, 1, '{\"title\":\"About Us\",\"sub_title\":\"We help to grow your see Smm business.\"}', '2024-10-28 03:06:10', '2024-10-28 03:06:10'),
(257, 255, 1, '{\"title\":\"Amazing Work Process\",\"short_description\":\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing edivt, sed do eiusmod tempor incididunt<\\/p>\"}', '2024-10-28 03:06:55', '2024-10-28 03:06:55'),
(258, 256, 1, '{\"title\":\"Connecting Potential\",\"short_description\":\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing edivt, sed do eiusmod tempor incididunt<\\/p>\"}', '2024-10-28 03:07:29', '2024-10-28 03:07:29'),
(259, 257, 1, '{\"title\":\"Increase Productivity\",\"short_description\":\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing edivt, sed do eiusmod tempor incididunt<\\/p>\"}', '2024-10-28 03:08:00', '2024-10-28 03:08:00'),
(260, 258, 1, '{\"title\":\"Services\",\"sub_title\":\"What We Provide\",\"short_title\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque accusantium, quaerat perferendis consectetur.\"}', '2024-10-28 03:12:21', '2024-10-28 03:12:21'),
(261, 259, 1, '{\"title\":\"Best SMM Panel\",\"short_description\":\"<p>SMM Forest provides the highest quality of promotions. We are one of the best SMM reseller panels including some special services out there online.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-28 03:14:05', '2024-10-28 03:14:05'),
(262, 260, 1, '{\"title\":\"Website Growth\",\"short_description\":\"<p>SMM Forest is a modern and efficient wholesale panel. We try to provide you with instant promotions on different social media platforms.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-28 03:14:42', '2024-10-28 03:14:42'),
(263, 261, 1, '{\"title\":\"SMM Ranking\",\"short_description\":\"<p>We provide guaranteed service on our website SMM server.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-28 03:15:23', '2024-10-28 03:15:23'),
(265, 263, 1, '{\"title\":\"HOW IT WORKS\",\"short_title\":\"How We Are Helping\",\"short_description\":\"<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Mollitia praesentium ipsum expedita? Optio adipisci at veniam reiciendis minus nam dolores nobis, consequatur odit consequuntur, quibusdam aperiam cumque porro nostrum assumenda. lorem ipsum, dolor sit amet consecettur adisiscing elit.<\\/p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus animi magni impedit molestias, consequuntur provident. Doloribus eius aut nihil iste! Illum porro quo labore totam provident id repellat vitae facere?<\\/p>\"}', '2024-10-28 03:20:57', '2024-10-28 03:26:53'),
(266, 264, 1, '{\"title\":\"Register &amp; Log in\",\"short_description\":\"<p>Creating an account is the first step. then you need to log in<\\/p>\"}', '2024-10-28 03:21:32', '2024-10-28 03:21:32'),
(267, 265, 1, '{\"title\":\"Add Fund\",\"short_description\":\"<p>Next, pick a payment method and add funds to your account<\\/p>\"}', '2024-10-28 03:21:47', '2024-10-28 03:21:47'),
(268, 266, 1, '{\"title\":\"Select a service\",\"short_description\":\"<p>Select the services you want and get ready to receive more publicity<\\/p>\"}', '2024-10-28 03:22:16', '2024-10-28 03:22:16'),
(269, 267, 1, '{\"title\":\"Enjoy Super Results\",\"short_description\":\"<p><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\">You can enjoy incredible results when your order is complete<\\/span><\\/p>\"}', '2024-10-28 03:22:32', '2024-10-28 03:22:32'),
(270, 268, 1, '{\"title\":\"We Complete 1500+ Project Yearly Successfully &amp; Still Counting\"}', '2024-10-28 03:28:19', '2024-10-28 03:28:19'),
(271, 269, 1, '{\"title\":\"HAPPY CLIENTS\",\"number_of_data\":\"320\"}', '2024-10-28 03:28:52', '2024-10-28 03:28:52'),
(272, 270, 1, '{\"title\":\"PROJECTS DONE\",\"number_of_data\":\"850\"}', '2024-10-28 03:29:23', '2024-10-28 03:29:23'),
(273, 271, 1, '{\"title\":\"TEAM ADVISORS\",\"number_of_data\":\"28\"}', '2024-10-28 03:29:44', '2024-10-28 03:29:44'),
(274, 272, 1, '{\"title\":\"GLORIOUS YEARS\",\"number_of_data\":\"8\"}', '2024-10-28 03:30:13', '2024-10-28 03:30:13'),
(275, 273, 1, '{\"title\":\"We Will Drive More Customers To Your Business Than Any Other Online Source.\",\"sub_title\":\"Multiply your Business\\u2019 Facebook Traffic 10x\",\"button_name\":\"Learn More\"}', '2024-10-28 03:39:08', '2024-10-28 03:39:08'),
(276, 274, 1, '{\"title\":\"Testimonial\",\"short_title\":\"What Clients Say\",\"short_description\":\"<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque accusantium, quaerat perferendis consectetur.<\\/p>\"}', '2024-10-28 03:43:51', '2024-10-28 03:43:51'),
(277, 275, 1, '{\"name\":\"Maria Jacket\",\"designation\":\"Web Developer\",\"description\":\"<p>I like this panel, I actually face a problem with my first try but support was there, and they help me to fix that problem then I tried YouTube views and it\'s one of the best.<\\/p>\"}', '2024-10-28 03:44:44', '2024-10-28 03:44:44'),
(278, 276, 1, '{\"name\":\"Alica Fox\",\"designation\":\"Team Hunter\",\"description\":\"<p>I ordered Facebook page likes &amp; IG followers, I received good quality followers highly recommended, and I\'ll definitely order again Thank you.<\\/p>\"}', '2024-10-28 03:45:07', '2024-10-28 03:45:07'),
(279, 277, 1, '{\"name\":\"Donald Trump\",\"designation\":\"CEO\",\"description\":\"<p>Well, I was testing the Instagram followers service and works really well! Takes about 5-10 minutes to start and complete the followers super fast! So, I like the service.<\\/p>\"}', '2024-10-28 03:45:29', '2024-10-28 03:45:29'),
(280, 278, 1, '{\"name\":\"Tom Moddy\",\"designation\":\"Business Executive\",\"description\":\"<p>I have been using Binary SMM for a week now and it is easily the best panel I have used. Great prices, easy to use and always updated. Great job!<\\/p>\"}', '2024-10-28 03:45:55', '2024-10-28 03:45:55'),
(281, 279, 1, '{\"name\":\"Oskaa\",\"designation\":\"Head of Ideas\",\"description\":\"<p><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\">Used this service for a week and made 15 orders of different services. There was a pause in supplying likes, but eventually everything was delivered at full.<\\/span><\\/p>\"}', '2024-10-28 03:46:18', '2024-10-28 03:46:18'),
(282, 280, 1, '{\"title\":\"READ OUR BLOG\",\"short_title\":\"Latest News From Blog\"}', '2024-10-28 03:51:08', '2024-10-28 03:51:08'),
(283, 281, 1, '{\"title\":\"Our Payment Partners\"}', '2024-10-28 03:58:32', '2024-10-28 03:58:32'),
(284, 282, 1, NULL, '2024-10-28 03:58:56', '2024-10-28 03:58:56'),
(285, 283, 1, NULL, '2024-10-28 03:59:02', '2024-10-28 03:59:02'),
(286, 284, 1, NULL, '2024-10-28 03:59:07', '2024-10-28 03:59:07'),
(287, 285, 1, NULL, '2024-10-28 03:59:10', '2024-10-28 03:59:10'),
(288, 286, 1, NULL, '2024-10-28 03:59:13', '2024-10-28 03:59:13'),
(289, 287, 1, NULL, '2024-10-28 03:59:17', '2024-10-28 03:59:17'),
(290, 288, 1, NULL, '2024-10-28 03:59:20', '2024-10-28 03:59:20'),
(291, 289, 1, NULL, '2024-10-28 03:59:25', '2024-10-28 03:59:25'),
(292, 290, 1, NULL, '2024-10-28 03:59:32', '2024-10-28 03:59:32'),
(293, 291, 1, NULL, '2024-10-28 03:59:35', '2024-10-28 03:59:35'),
(294, 292, 1, NULL, '2024-10-28 03:59:41', '2024-10-28 03:59:41'),
(295, 293, 1, NULL, '2024-10-28 03:59:46', '2024-10-28 03:59:46'),
(296, 294, 1, '{\"site_short_description\":\"We focus on the needs of small to middle market businesses to improve and grow their return.\",\"newsletter_heading\":\"subscribe newsletter\"}', '2024-10-28 04:04:21', '2024-10-28 04:04:21'),
(297, 295, 1, '{\"name\":\"Facebook\"}', '2024-10-28 04:05:33', '2024-10-28 04:05:33'),
(298, 296, 1, '{\"name\":\"Twitter\"}', '2024-10-28 04:05:58', '2024-10-28 04:05:58'),
(299, 297, 1, '{\"name\":\"Linkedin\"}', '2024-10-28 04:06:11', '2024-10-28 04:06:11'),
(300, 298, 1, '{\"name\":\"Instagram\"}', '2024-10-28 04:06:22', '2024-10-28 04:06:22'),
(301, 299, 1, '{\"heading\":\"GET IN TOUCH\",\"sub_heading\":\"Have Questions?\",\"title\":\"Contact Us\",\"sub_title\":\"Give us a call or drop by anytime, we endeavour to answer all enquiries within 24 hours on business days. We will be happy to answer your questions.\",\"address\":\"22 Baker Street, London\",\"email\":\"smm@gmail.com\",\"phone\":\"+44-20-4526-2356\"}', '2024-10-28 04:34:28', '2024-10-28 04:34:28'),
(302, 300, 1, '{\"title\":\"What is Partial status?\",\"description\":\"<p>Partial Status is when we partially refund the remains of an order. Sometimes for some reasons we are unable to deliver a full order, so we refund you the remaining undelivered amount. Example: You bought an order with quantity 10 000 and charges 10$, let\'s say we delivered 9 000 and the remaining 1 000 we couldn\'t deliver, then we will \\\"Partial\\\" the order and refund you the remaining 1 000 (1$ in this example).<\\/p>\"}', '2024-10-28 07:16:30', '2024-10-28 07:16:30'),
(303, 301, 1, '{\"title\":\"What is Drip Feed?\",\"description\":\"<p>Drip Feed is a service that we are offering so you would be able to put the same order multiple times automatically. Example: let\'s say you want to get 1000 likes on your Instagram Post but you want to get 100 likes each 30 minutes, you will put Link: Your Post Link Quantity: 100 Runs: 10 (as you want to run this order 10 times, if you want to get 2000 likes, you will run it 20 times, etc\\u2026) Interval: 30 (because you want to get 100 likes on your post each 30 minutes, if you want each hour, you will put 60 because the time is in minutes) P.S: Never order more quantity than the maximum which is written on the service name (Quantity x Runs), Example if the service\'s max is 4000, you don\\u2019t put Quantity: 500 and Run: 10, because total quantity will be 500x10 = 5000 which is bigger than the service max (4000). Also never put the Interval below the actual start time (some services need 60 minutes to start, don\\u2019t put Interval less than the service start time or it will cause a fail in your order).<\\/p>\"}', '2024-10-28 07:16:51', '2024-10-28 07:16:51'),
(304, 302, 1, '{\"title\":\"How do I use mass order?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here: https:\\/\\/justanotherpanel.com\\/services Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer From the Services List @ https:\\/\\/justanotherpanel.com\\/services, the service ID for this service \\u201cInstagram Followers [15K] [REAL] \\u26a1\\ufe0f\\ud83d\\udca7\\u2b50\\u201d is 102 Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity or in this example: 102|abcd|1000 102|asdf|1000 102|qwer|1000<\\/p>\"}', '2024-10-28 07:17:13', '2024-10-28 07:17:13'),
(305, 303, 1, '{\"title\":\"I want a panel like yours \\/ I want to resell your services how?\",\"description\":\"<p>To get a panel like ours, please check jap to rent a panel, and then you can connect to us via API easily!<\\/p>\"}', '2024-10-28 07:17:49', '2024-10-28 07:17:49'),
(306, 304, 1, '{\"title\":\"Can I get a discount?\",\"description\":\"<p>No, we don\\u2019t offer any discount, the price of our services is fixed!<\\/p>\"}', '2024-10-28 07:18:03', '2024-10-28 07:18:03'),
(307, 305, 1, '{\"title\":\"How to get youtube comment link?\",\"description\":\"<p>Find the timestamp that is located next to your username above your comment (for example: \\\"3 days ago\\\") and hover over it then right click and \\\"Copy Link Address\\\". The link will be something like this: https:\\/\\/www.youtube.com\\/watch?v=12345&amp;lc=a1b21etc instead of just https:\\/\\/www.youtube.com\\/watch?v=12345 To be sure that you got the correct link, paste it in your browser\'s address bar and you will see that the comment is now the first one below the video and it says \\\"Highlighted comment\\\".<\\/p>\"}', '2024-10-28 07:18:20', '2024-10-28 07:18:20'),
(308, 306, 1, '{\"title\":\"Which youtube view service can be used with monetizable video?\",\"description\":\"<p>The one that has \\\"Monetized\\\" in its service\' name.<\\/p>\"}', '2024-10-28 07:18:51', '2024-10-28 07:18:51'),
(309, 307, 1, '{\"title\":\"What is \'Instagram Saves\', and what does it do?\",\"description\":\"<p>Instagram Saves is when a user saves a post to his history on Instagram (by pressing the save button near the like button). A lot of saves for a post increase its impression.<\\/p>\"}', '2024-10-28 07:19:05', '2024-10-28 07:19:05'),
(310, 308, 1, '{\"title\":\"The link must be added before the user goes live or after?\",\"description\":\"<p>After he goes live, or just 5 second before he goes!<\\/p>\"}', '2024-10-28 07:19:19', '2024-10-28 07:19:19'),
(311, 309, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-28 08:49:35', '2024-10-28 08:49:35'),
(312, 310, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-28 08:52:03', '2024-10-28 08:52:03'),
(313, 311, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-28 09:00:39', '2024-10-28 09:00:39'),
(314, 312, 1, '{\"title\":\"Grow your business with our SMM solution\",\"short_description\":\"<p>SMM Forest is simply the fastest, cheapest, biggest panel in the SMM World. The best social media marketing panel for resellers #1Top SMMPanel. We are providing an Opportunity to make handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/p>\",\"button_name\":\"Learn More\"}', '2024-10-28 19:35:18', '2024-10-28 19:35:18'),
(315, 313, 1, '{\"title\":\"Link Building\",\"short_description\":\"<p>We are providing an Opportunity to make handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/p>\"}', '2024-10-28 19:43:34', '2024-10-28 19:43:34'),
(316, 314, 1, '{\"title\":\"Customer Support\",\"short_description\":\"<p>SMM Forest comes with a dedicated team to drive a world-class customer\\u2019s support. we will Add Daily new service offer and improving support system for fast support<\\/p>\"}', '2024-10-28 19:43:55', '2024-10-28 19:43:55'),
(317, 315, 1, '{\"title\":\"Automatic Payments\",\"short_description\":\"<p>Most SMM Panels make you input payment information every time you make an order. Set up an automatic payment method with\\u00a0 SMM Forest.<\\/p>\"}', '2024-10-28 19:44:13', '2024-10-28 19:44:13'),
(318, 316, 1, '{\"title\":\"About Us\",\"sub_title\":\"WHO WE ARE\",\"short_description\":\"<p>We help to grow your see Smm business.<\\/p><p>SMM Forest is one of the renowned SMM reseller Panel existing on the internet.<\\/p><p>We have been providing services for 5+ years and provide services for almost every kind of Social Media. Our Social Media Marketing Services include SMM for Facebook, Instagram, Twitter, Youtube, Tiktok, LinkedIn, Shazam, and many more.<\\/p><p>We are providing an Opportunity to make a handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/p>\",\"button_name\":\"More About\"}', '2024-10-28 19:52:03', '2024-10-28 19:55:11'),
(319, 317, 1, '{\"title\":\"Services\",\"sub_title\":\"What We Provide\",\"short_title\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque accusantium, quaerat perferendis consectetur.\"}', '2024-10-28 19:59:16', '2024-10-28 19:59:16'),
(320, 318, 1, '{\"title\":\"Best SMM Panel\",\"short_description\":\"<p>SMM Forest provides the highest quality of promotions. We are one of the best SMM reseller panels including some special services out there online.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-28 20:00:22', '2024-10-28 20:00:22'),
(321, 319, 1, '{\"title\":\"Website Growth\",\"short_description\":\"<p>SMM Forest is a modern and efficient wholesale panel. We try to provide you with instant promotions on different social media platforms.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-28 20:00:57', '2024-10-28 20:00:57'),
(322, 320, 1, '{\"title\":\"SMM Ranking\",\"short_description\":\"<p>We provide guaranteed service on our website SMM server.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-28 20:01:37', '2024-10-28 20:01:37'),
(323, 321, 1, '{\"title\":\"HOW IT WORKS\",\"short_title\":\"How We Are Helping\",\"short_description\":\"<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Mollitia praesentium ipsum expedita? Optio adipisci at veniam reiciendis minus nam dolores nobis, consequatur odit consequuntur, quibusdam aperiam cumque porro nostrum assumenda. lorem ipsum, dolor sit amet consecettur adisiscing elit.<\\/p><p><\\/p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.\\r\\n\\r\\nDucimus animi magni impedit molestias, consequuntur provident. Doloribus eius aut nihil iste! Illum porro quo labore totam provident id repellat vitae facere?<\\/p>\"}', '2024-10-28 20:06:35', '2024-10-28 20:19:47'),
(324, 322, 1, '{\"title\":\"Register &amp; Log in\",\"short_description\":\"<p>Creating an account is the first step. then you need to log in<\\/p>\"}', '2024-10-28 20:06:59', '2024-10-28 20:06:59'),
(325, 323, 1, '{\"title\":\"Add Fund\",\"short_description\":\"<p>Next, pick a payment method and add funds to your account<\\/p>\"}', '2024-10-28 20:07:16', '2024-10-28 20:07:16'),
(326, 324, 1, '{\"title\":\"Select a service\",\"short_description\":\"<p><span style=\\\"color:rgb(124,135,152);font-family:Rubik, sans-serif;font-size:16px;\\\">Select the services you want and get ready to receive more publicity<\\/span><\\/p>\"}', '2024-10-28 20:07:34', '2024-10-28 20:07:34'),
(327, 325, 1, '{\"title\":\"Enjoy Super Results\",\"short_description\":\"<p>You can enjoy incredible results when your order is complete<\\/p>\"}', '2024-10-28 20:07:56', '2024-10-28 20:07:56'),
(328, 326, 1, '{\"title\":\"We Complete 1500+ Project Yearly Successfully &amp; Still Counting\"}', '2024-10-28 20:23:02', '2024-10-28 20:23:02'),
(329, 327, 1, '{\"title\":\"HAPPY CLIENTS\",\"number_of_data\":\"320\"}', '2024-10-28 20:23:39', '2024-10-28 20:23:39'),
(330, 328, 1, '{\"title\":\"PROJECTS DONE\",\"number_of_data\":\"850\"}', '2024-10-28 20:24:09', '2024-10-28 20:24:09'),
(331, 329, 1, '{\"title\":\"TEAM ADVISORS\",\"number_of_data\":\"28\"}', '2024-10-28 20:24:32', '2024-10-28 20:24:32'),
(332, 330, 1, '{\"title\":\"GLORIOUS YEARS\",\"number_of_data\":\"8\"}', '2024-10-28 20:25:13', '2024-10-28 20:25:13'),
(333, 331, 1, '{\"title\":\"We Will Drive More Customers To Your Business Than Any Other Online Source.\",\"sub_title\":\"Multiply your Business\\u2019 Facebook Traffic 10x\",\"button_name\":\"Learn More\"}', '2024-10-28 20:31:27', '2024-10-28 20:31:27'),
(334, 332, 1, '{\"title\":\"Testimonial\",\"short_title\":\"What Clients Say\",\"short_description\":\"<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque accusantium, quaerat perferendis consectetur.<\\/p>\"}', '2024-10-28 20:35:56', '2024-10-28 20:35:56'),
(335, 333, 1, '{\"name\":\"Maria Jacket\",\"designation\":\"Web Developer\",\"description\":\"<p>I like this panel, I actually face a problem with my first try but support was there, and they help me to fix that problem then I tried YouTube views and it\'s one of the best.<\\/p>\"}', '2024-10-28 20:36:24', '2024-10-28 20:36:24'),
(336, 334, 1, '{\"name\":\"Alica Fox\",\"designation\":\"Team Hunter\",\"description\":\"<p>I ordered Facebook page likes &amp; IG followers, I received good quality followers highly recommended, and I\'ll definitely order again Thank you.<\\/p>\"}', '2024-10-28 20:36:49', '2024-10-28 20:36:49'),
(337, 335, 1, '{\"name\":\"Donald Trump\",\"designation\":\"CEO\",\"description\":\"<p>Well, I was testing the Instagram followers service and works really well! Takes about 5-10 minutes to start and complete the followers super fast! So, I like the service.<\\/p>\"}', '2024-10-28 20:37:13', '2024-10-28 20:37:13'),
(338, 336, 1, '{\"name\":\"Tom Moddy\",\"designation\":\"Business Executive\",\"description\":\"<p>I have been using Binary SMM for a week now and it is easily the best panel I have used. Great prices, easy to use and always updated. Great job!<\\/p>\"}', '2024-10-28 20:39:29', '2024-10-28 20:39:29'),
(339, 337, 1, '{\"name\":\"Oskaa\",\"designation\":\"Head of Ideas\",\"description\":\"<p>Used this service for a week and made 15 orders of different services. There was a pause in supplying likes, but eventually everything was delivered at full.<\\/p>\"}', '2024-10-28 20:39:50', '2024-10-28 20:39:50'),
(340, 338, 1, '{\"title\":\"READ OUR BLOG\",\"short_title\":\"Latest News From Blog\"}', '2024-10-28 20:47:24', '2024-10-28 20:47:24'),
(341, 339, 1, '{\"title\":\"PAYMENTS\",\"sub_title\":\"Our Payment Partners\"}', '2024-10-28 20:53:58', '2024-10-28 20:53:58'),
(342, 340, 1, NULL, '2024-10-28 20:54:19', '2024-10-28 20:54:19'),
(343, 341, 1, NULL, '2024-10-28 20:54:24', '2024-10-28 20:54:24'),
(344, 342, 1, NULL, '2024-10-28 20:54:27', '2024-10-28 20:54:27'),
(345, 343, 1, NULL, '2024-10-28 20:54:32', '2024-10-28 20:54:32'),
(346, 344, 1, NULL, '2024-10-28 20:54:43', '2024-10-28 20:54:43'),
(347, 345, 1, NULL, '2024-10-28 20:54:47', '2024-10-28 20:54:47'),
(348, 346, 1, NULL, '2024-10-28 20:54:50', '2024-10-28 20:54:50'),
(349, 347, 1, NULL, '2024-10-28 20:54:55', '2024-10-28 20:54:55'),
(350, 348, 1, NULL, '2024-10-28 20:55:00', '2024-10-28 20:55:00'),
(351, 349, 1, NULL, '2024-10-28 20:55:04', '2024-10-28 20:55:04'),
(352, 350, 1, '{\"title\":\"Join our newsletter\"}', '2024-10-28 21:00:31', '2024-10-28 21:00:31'),
(353, 351, 1, '{\"site_short_description\":\"We focus on the needs of small to middle market businesses to improve and grow their return.\"}', '2024-10-28 21:03:48', '2024-10-28 21:03:48'),
(354, 352, 1, '{\"name\":\"Facebook\"}', '2024-10-28 21:04:02', '2024-10-28 21:04:02'),
(355, 353, 1, '{\"name\":\"Twitter\"}', '2024-10-28 21:04:12', '2024-10-28 21:04:12'),
(356, 354, 1, '{\"name\":\"Linkedin\"}', '2024-10-28 21:04:25', '2024-10-28 21:04:25'),
(357, 355, 1, '{\"name\":\"Instagram\"}', '2024-10-28 21:04:36', '2024-10-28 21:04:36'),
(358, 356, 1, '{\"title\":\"Frequently Asked Questions\",\"sub_title\":\"SMM Forest gives you the best marketing tool so you can topple the Competition.\"}', '2024-10-28 21:20:24', '2024-10-28 21:20:24'),
(359, 357, 1, '{\"title\":\"What is Partial status?\",\"description\":\"<p>Partial Status is when we partially refund the remains of an order. Sometimes for some reasons we are unable to deliver a full order, so we refund you the remaining undelivered amount. Example: You bought an order with quantity 10 000 and charges 10$, let\'s say we delivered 9 000 and the remaining 1 000 we couldn\'t deliver, then we will \\\"Partial\\\" the order and refund you the remaining 1 000 (1$ in this example).<\\/p>\"}', '2024-10-28 21:21:17', '2024-10-28 21:21:17'),
(360, 358, 1, '{\"title\":\"What is Drip Feed?\",\"description\":\"<p>Drip Feed is a service that we are offering so you would be able to put the same order multiple times automatically. Example: let\'s say you want to get 1000 likes on your Instagram Post but you want to get 100 likes each 30 minutes, you will put Link: Your Post Link Quantity: 100 Runs: 10 (as you want to run this order 10 times, if you want to get 2000 likes, you will run it 20 times, etc\\u2026) Interval: 30 (because you want to get 100 likes on your post each 30 minutes, if you want each hour, you will put 60 because the time is in minutes) P.S: Never order more quantity than the maximum which is written on the service name (Quantity x Runs), Example if the service\'s max is 4000, you don\\u2019t put Quantity: 500 and Run: 10, because total quantity will be 500x10 = 5000 which is bigger than the service max (4000). Also never put the Interval below the actual start time (some services need 60 minutes to start, don\\u2019t put Interval less than the service start time or it will cause a fail in your order).<\\/p>\"}', '2024-10-28 21:21:30', '2024-10-28 21:21:30'),
(361, 359, 1, '{\"title\":\"How do I use mass order?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here: https:\\/\\/justanotherpanel.com\\/services Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer From the Services List @ https:\\/\\/justanotherpanel.com\\/services, the service ID for this service \\u201cInstagram Followers [15K] [REAL] \\u26a1\\ufe0f\\ud83d\\udca7\\u2b50\\u201d is 102 Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity or in this example: 102|abcd|1000 102|asdf|1000 102|qwer|1000<\\/p>\"}', '2024-10-28 21:21:43', '2024-10-28 21:21:43'),
(362, 360, 1, '{\"title\":\"I want a panel like yours \\/ I want to resell your services how?\",\"description\":\"To get a panel like ours, please check jap to rent a panel, and then you can connect to us via API easily!\"}', '2024-10-28 21:22:00', '2024-10-28 21:22:00'),
(363, 361, 1, '{\"title\":\"Can I get a discount?\",\"description\":\"<p>No, we don\\u2019t offer any discount, the price of our services is fixed!<\\/p>\"}', '2024-10-28 21:22:15', '2024-10-28 21:22:15'),
(364, 362, 1, '{\"title\":\"How to get youtube comment link?\",\"description\":\"<p>Find the timestamp that is located next to your username above your comment (for example: \\\"3 days ago\\\") and hover over it then right click and \\\"Copy Link Address\\\". The link will be something like this: https:\\/\\/www.youtube.com\\/watch?v=12345&amp;lc=a1b21etc instead of just https:\\/\\/www.youtube.com\\/watch?v=12345 To be sure that you got the correct link, paste it in your browser\'s address bar and you will see that the comment is now the first one below the video and it says \\\"Highlighted comment\\\".<\\/p>\"}', '2024-10-28 21:22:27', '2024-10-28 21:22:27'),
(365, 363, 1, '{\"title\":\"Which youtube view service can be used with monetizable video?\",\"description\":\"<p>The one that has \\\"Monetized\\\" in its service\' name.<\\/p>\"}', '2024-10-28 21:22:40', '2024-10-28 21:22:40'),
(366, 364, 1, '{\"title\":\"What is \'Instagram Saves\', and what does it do?\",\"description\":\"<p>Instagram Saves is when a user saves a post to his history on Instagram (by pressing the save button near the like button). A lot of saves for a post increase its impression.<\\/p>\"}', '2024-10-28 21:22:58', '2024-10-28 21:22:58'),
(367, 365, 1, '{\"title\":\"The link must be added before the user goes live or after?\",\"description\":\"<p>After he goes live, or just 5 second before he goes!<\\/p>\"}', '2024-10-28 21:23:12', '2024-10-28 21:23:12'),
(368, 366, 1, '{\"heading\":\"GET IN TOUCH\",\"sub_heading\":\"Have Questions?\",\"title\":\"Contact Us\",\"sub_title\":\"Give us a call or drop by anytime, we endeavour to answer all enquiries within 24 hours on business days. We will be happy to answer your questions.\",\"address\":\"22 Baker Street, London\",\"email\":\"smm@gmail.com\",\"phone\":\"+44-20-4526-2356\"}', '2024-10-28 21:31:59', '2024-10-28 21:31:59'),
(369, 367, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-28 22:43:46', '2024-10-28 22:43:55'),
(370, 368, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-28 22:45:57', '2024-10-28 22:45:57'),
(371, 369, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-28 22:49:36', '2024-10-28 22:49:36'),
(372, 370, 1, '{\"title\":\"Grow your business with our SMM solution\",\"short_description\":\"<p>SMM Forest is simply the fastest, cheapest, biggest panel in the SMM World. The best social media marketing panel for resellers #1Top SMMPanel. We are providing an Opportunity to make handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/p>\",\"button_name\":\"Learn More\"}', '2024-10-29 01:16:30', '2024-10-29 01:16:30'),
(373, 371, 1, '{\"title\":\"Link Building\",\"short_description\":\"<p>We are providing an Opportunity to make handsome amount of money by reselling our social media services on your own social media marketing or by selling them on Various Marketplace.<\\/p>\"}', '2024-10-29 01:20:40', '2024-10-29 01:20:40'),
(374, 372, 1, '{\"title\":\"Customer Support\",\"short_description\":\"<p>SMM Forest comes with a dedicated team to drive a world-class customer\\u2019s support. we will Add Daily new service offer and improving support system for fast support<\\/p>\"}', '2024-10-29 01:21:02', '2024-10-29 01:21:02'),
(375, 373, 1, '{\"title\":\"Automatic Payments\",\"short_description\":\"<p>Most SMM Panels make you input payment information every time you make an order. Set up an automatic payment method with\\u00a0 SMM Forest.<\\/p>\"}', '2024-10-29 01:21:19', '2024-10-29 01:21:19'),
(376, 374, 1, '{\"title\":\"About Us\",\"sub_title\":\"We help to grow your see Smm business.\"}', '2024-10-29 01:26:15', '2024-10-29 01:26:15'),
(377, 375, 1, '{\"title\":\"Amazing Work Process\",\"short_description\":\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing edivt, sed do eiusmod tempor incididunt<\\/p>\"}', '2024-10-29 01:26:43', '2024-10-29 01:26:43'),
(378, 376, 1, '{\"title\":\"Connecting Potential\",\"short_description\":\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing edivt, sed do eiusmod tempor incididunt<\\/p>\"}', '2024-10-29 01:27:01', '2024-10-29 01:27:01'),
(379, 377, 1, '{\"title\":\"Increase Productivity\",\"short_description\":\"<p>Lorem ipsum dolor sit amet, consectetur adipiscing edivt, sed do eiusmod tempor incididunt<\\/p>\"}', '2024-10-29 01:27:20', '2024-10-29 01:27:20'),
(380, 378, 1, '{\"title\":\"Services\",\"sub_title\":\"What We Provide\",\"short_title\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque accusantium, quaerat perferendis consectetur.\"}', '2024-10-29 01:35:50', '2024-10-29 01:49:56'),
(381, 379, 1, '{\"title\":\"Best SMM Panel\",\"short_description\":\"<p>SMM Forest provides the highest quality of promotions. We are one of the best SMM reseller panels including some special services out there online.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-29 01:37:56', '2024-10-29 01:49:30'),
(382, 380, 1, '{\"title\":\"Website Growth\",\"short_description\":\"<p>SMM Forest is a modern and efficient wholesale panel. We try to provide you with instant promotions on different social media platforms.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-29 01:46:43', '2024-10-29 01:46:43'),
(383, 381, 1, '{\"title\":\"SMM Ranking\",\"short_description\":\"<p>We provide guaranteed service on our website SMM server.<\\/p>\",\"button_name\":\"Read More\"}', '2024-10-29 01:47:14', '2024-10-29 01:47:14'),
(384, 382, 1, '{\"title\":\"HOW IT WORKS\",\"short_title\":\"How We Are Helping\",\"short_description\":\"<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Mollitia praesentium ipsum expedita? Optio adipisci at veniam reiciendis minus nam dolores nobis, consequatur odit consequuntur, quibusdam aperiam cumque porro nostrum assumenda. lorem ipsum, dolor sit amet consecettur adisiscing elit.<\\/p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus animi magni impedit molestias, consequuntur provident. Doloribus eius aut nihil iste! Illum porro quo labore totam provident id repellat vitae facere?<\\/p>\"}', '2024-10-29 01:51:43', '2024-10-29 01:51:43'),
(385, 383, 1, '{\"title\":\"Register &amp; Log in\",\"short_description\":\"<p>Creating an account is the first step. then you need to log in<\\/p>\"}', '2024-10-29 01:53:41', '2024-10-29 01:53:41'),
(386, 384, 1, '{\"title\":\"Add Fund\",\"short_description\":\"<p>Next, pick a payment method and add funds to your account<\\/p>\"}', '2024-10-29 01:53:58', '2024-10-29 01:53:58'),
(387, 385, 1, '{\"title\":\"Select a service\",\"short_description\":\"<p>Select the services you want and get ready to receive more publicity<\\/p>\"}', '2024-10-29 01:54:30', '2024-10-29 01:54:30'),
(388, 386, 1, '{\"title\":\"Enjoy Super Results\",\"short_description\":\"<p>You can enjoy incredible results when your order is complete<\\/p>\"}', '2024-10-29 01:54:51', '2024-10-29 01:54:51'),
(389, 387, 1, '{\"title\":\"We Complete 1500+ Project Yearly Successfully &amp; Still Counting\"}', '2024-10-29 02:06:28', '2024-10-29 02:06:28'),
(390, 388, 1, '{\"title\":\"HAPPY CLIENTS\",\"number_of_data\":\"320\"}', '2024-10-29 02:07:02', '2024-10-29 02:07:02'),
(391, 389, 1, '{\"title\":\"PROJECTS DONE\",\"number_of_data\":\"850\"}', '2024-10-29 02:07:25', '2024-10-29 02:07:25'),
(392, 390, 1, '{\"title\":\"TEAM ADVISORS\",\"number_of_data\":\"28\"}', '2024-10-29 02:07:49', '2024-10-29 02:07:49'),
(393, 391, 1, '{\"title\":\"GLORIOUS YEARS\",\"number_of_data\":\"8\"}', '2024-10-29 02:08:08', '2024-10-29 02:08:08'),
(394, 392, 1, '{\"title\":\"We Will Drive More Customers To Your Business Than Any Other Online Source.\",\"sub_title\":\"Multiply your Business\\u2019 Facebook Traffic 10x\",\"button_name\":\"Learn More\"}', '2024-10-29 02:10:46', '2024-10-29 02:10:46'),
(395, 393, 1, '{\"title\":\"Testimonial\",\"short_title\":\"What Clients Say\",\"short_description\":\"<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque accusantium, quaerat perferendis consectetur.<\\/p>\"}', '2024-10-29 02:18:51', '2024-10-29 02:18:51'),
(396, 394, 1, '{\"name\":\"Maria Jacket\",\"designation\":\"Web Developer\",\"description\":\"<p>I like this panel, I actually face a problem with my first try but support was there, and they help me to fix that problem then I tried YouTube views and it\'s one of the best.<\\/p>\"}', '2024-10-29 02:19:36', '2024-10-29 02:19:36'),
(397, 395, 1, '{\"name\":\"Alica Fox\",\"designation\":\"Team Hunter\",\"description\":\"<p>I ordered Facebook page likes &amp; IG followers, I received good quality followers highly recommended, and I\'ll definitely order again Thank you.<\\/p>\"}', '2024-10-29 02:19:59', '2024-10-29 02:19:59'),
(398, 396, 1, '{\"name\":\"Donald Trump\",\"designation\":\"CEO\",\"description\":\"<p>Well, I was testing the Instagram followers service and works really well! Takes about 5-10 minutes to start and complete the followers super fast! So, I like the service.<\\/p>\"}', '2024-10-29 02:20:29', '2024-10-29 02:20:29'),
(399, 397, 1, '{\"name\":\"Tom Moddy\",\"designation\":\"Business Executive\",\"description\":\"<p>I have been using Binary SMM for a week now and it is easily the best panel I have used. Great prices, easy to use and always updated. Great job!<\\/p>\"}', '2024-10-29 02:20:51', '2024-10-29 02:20:51'),
(400, 398, 1, '{\"name\":\"Oskaa\",\"designation\":\"Head of Ideas\",\"description\":\"<p>Used this service for a week and made 15 orders of different services. There was a pause in supplying likes, but eventually everything was delivered at full.<\\/p>\"}', '2024-10-29 02:21:15', '2024-10-29 02:21:15'),
(401, 399, 1, '{\"title\":\"READ OUR BLOG\",\"short_title\":\"Latest News From Blog\"}', '2024-10-29 02:24:31', '2024-10-29 02:24:31'),
(402, 400, 1, '{\"title\":\"Our Payment Partners\"}', '2024-10-29 02:25:08', '2024-10-29 02:25:08'),
(403, 401, 1, NULL, '2024-10-29 02:25:27', '2024-10-29 02:25:27'),
(404, 402, 1, NULL, '2024-10-29 02:25:30', '2024-10-29 02:25:30'),
(405, 403, 1, NULL, '2024-10-29 02:25:34', '2024-10-29 02:25:34'),
(406, 404, 1, NULL, '2024-10-29 02:25:38', '2024-10-29 02:25:38'),
(407, 405, 1, NULL, '2024-10-29 02:25:41', '2024-10-29 02:25:41'),
(408, 406, 1, NULL, '2024-10-29 02:25:45', '2024-10-29 02:25:45'),
(409, 407, 1, NULL, '2024-10-29 02:25:49', '2024-10-29 02:25:49'),
(410, 408, 1, NULL, '2024-10-29 02:25:55', '2024-10-29 02:25:55'),
(411, 409, 1, NULL, '2024-10-29 02:26:00', '2024-10-29 02:26:00'),
(412, 410, 1, NULL, '2024-10-29 02:26:05', '2024-10-29 02:26:05'),
(413, 411, 1, '{\"site_short_description\":\"We focus on the needs of small to middle market businesses to improve and grow their return.\",\"newsletter_heading\":\"subscribe newsletter\"}', '2024-10-29 02:28:06', '2024-10-29 02:28:06'),
(414, 412, 1, '{\"name\":\"Facebook\"}', '2024-10-29 02:28:35', '2024-10-29 02:28:35'),
(415, 413, 1, '{\"name\":\"Twitter\"}', '2024-10-29 02:28:49', '2024-10-29 02:28:49'),
(416, 414, 1, '{\"name\":\"Linkedin\"}', '2024-10-29 02:29:02', '2024-12-20 22:37:28'),
(417, 415, 1, '{\"name\":\"Instagram\"}', '2024-10-29 02:29:23', '2024-10-29 02:29:23'),
(418, 416, 1, '{\"heading\":\"GET IN TOUCH\",\"sub_heading\":\"Have Questions?\",\"title\":\"Contact Us\",\"sub_title\":\"Give us a call or drop by anytime, we endeavour to answer all enquiries within 24 hours on business days. We will be happy to answer your questions.\",\"address\":\"22 Baker Street, London\",\"email\":\"smm@gmail.com\",\"phone\":\"+44-20-4526-2356\"}', '2024-10-29 03:05:28', '2024-10-29 03:05:28'),
(419, 417, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-29 03:25:45', '2024-10-29 03:25:45'),
(420, 418, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-29 03:27:14', '2024-10-29 03:27:14'),
(421, 419, 1, '{\"title\":\"Proclamations About Us\",\"description\":\"<ul><li><b>Facebook\\u00a0<\\/b>:\\u00a0SMM Matrix<\\/li><li><b>Channel\\u00a0<\\/b>: SMM Matrix<\\/li><li><p><b>Phone\\u00a0<\\/b>:\\u00a0\\u00a00913XXXX82<\\/p><\\/li><\\/ul>\"}', '2024-10-29 03:28:33', '2024-10-29 03:28:33'),
(422, 420, 1, '{\"title\":\"What is Partial status?\",\"description\":\"<p>Partial Status is when we partially refund the remains of an order. Sometimes for some reasons we are unable to deliver a full order, so we refund you the remaining undelivered amount. Example: You bought an order with quantity 10 000 and charges 10$, let\'s say we delivered 9 000 and the remaining 1 000 we couldn\'t deliver, then we will \\\"Partial\\\" the order and refund you the remaining 1 000 (1$ in this example).<\\/p>\"}', '2024-10-30 09:46:45', '2024-10-30 09:46:45'),
(423, 421, 1, '{\"title\":\"What is Drip Feed?\",\"description\":\"<p>Drip Feed is a service that we are offering so you would be able to put the same order multiple times automatically. Example: let\'s say you want to get 1000 likes on your Instagram Post but you want to get 100 likes each 30 minutes, you will put Link: Your Post Link Quantity: 100 Runs: 10 (as you want to run this order 10 times, if you want to get 2000 likes, you will run it 20 times, etc\\u2026) Interval: 30 (because you want to get 100 likes on your post each 30 minutes, if you want each hour, you will put 60 because the time is in minutes) P.S: Never order more quantity than the maximum which is written on the service name (Quantity x Runs), Example if the service\'s max is 4000, you don\\u2019t put Quantity: 500 and Run: 10, because total quantity will be 500x10 = 5000 which is bigger than the service max (4000). Also never put the Interval below the actual start time (some services need 60 minutes to start, don\\u2019t put Interval less than the service start time or it will cause a fail in your order).<\\/p>\"}', '2024-10-30 09:47:00', '2024-10-30 09:47:00'),
(424, 422, 1, '{\"title\":\"How do I use mass order?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here: https:\\/\\/justanotherpanel.com\\/services Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer From the Services List @ https:\\/\\/justanotherpanel.com\\/services, the service ID for this service \\u201cInstagram Followers [15K] [REAL] \\u26a1\\ufe0f\\ud83d\\udca7\\u2b50\\u201d is 102 Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity or in this example: 102|abcd|1000 102|asdf|1000 102|qwer|1000<\\/p>\"}', '2024-10-30 09:47:09', '2024-10-30 09:47:09'),
(425, 423, 1, '{\"title\":\"I want a panel like yours \\/ I want to resell your services how?\",\"description\":\"<p>To get a panel like ours, please check jap to rent a panel, and then you can connect to us via API easily!<\\/p>\"}', '2024-10-30 09:47:18', '2024-10-30 09:47:18'),
(426, 424, 1, '{\"title\":\"Can I get a discount?\",\"description\":\"<p>No, we don\\u2019t offer any discount, the price of our services is fixed!<\\/p>\"}', '2024-10-30 09:47:27', '2024-10-30 09:47:27'),
(427, 425, 1, '{\"title\":\"How to get youtube comment link?\",\"description\":\"<p>Find the timestamp that is located next to your username above your comment (for example: \\\"3 days ago\\\") and hover over it then right click and \\\"Copy Link Address\\\". The link will be something like this: https:\\/\\/www.youtube.com\\/watch?v=12345&amp;lc=a1b21etc instead of just https:\\/\\/www.youtube.com\\/watch?v=12345 To be sure that you got the correct link, paste it in your browser\'s address bar and you will see that the comment is now the first one below the video and it says \\\"Highlighted comment\\\".<\\/p>\"}', '2024-10-30 09:47:35', '2024-10-30 09:47:35');
INSERT INTO `content_details` (`id`, `content_id`, `language_id`, `description`, `created_at`, `updated_at`) VALUES
(428, 426, 1, '{\"title\":\"Which youtube view service can be used with monetizable video?\",\"description\":\"<p>The one that has \\\"Monetized\\\" in its service\' name.<\\/p>\"}', '2024-10-30 09:47:44', '2024-10-30 09:47:44'),
(429, 427, 1, '{\"title\":\"What is \'Instagram Saves\', and what does it do?\",\"description\":\"<p>Instagram Saves is when a user saves a post to his history on Instagram (by pressing the save button near the like button). A lot of saves for a post increase its impression.<\\/p>\"}', '2024-10-30 09:47:54', '2024-10-30 09:47:54'),
(430, 428, 1, '{\"title\":\"The link must be added before the user goes live or after?\",\"description\":\"<p>After he goes live, or just 5 second before he goes!<\\/p>\"}', '2024-10-30 09:48:03', '2024-10-30 09:48:03'),
(431, 429, 1, '{\"short_description\":\"<h4>What is a Child Panel?<br><\\/h4><p style=\\\"font-weight:400;font-size:16px;\\\">From its name, a child panel refers to a panel that has its connection derived from a regular panel. A child panel has fewer ranges of features when compared with a regular panel, and because of this, the user experience might be a little bit lesser.<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">SMM Panel are known as sites where you can purchase marketing services for your customers,<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">They are of two types namely regular panels and child panels. This article sets out to inform you about child panels and how they differ from the more popular regular cheap SMM panel.<br><br><\\/p><h4>Differences between child panel and regular panel<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">As stated earlier, a child panel is not the same as a regular panel, even though they are both SMM panels. The following are some of the differences that exist between a child panel and a regular panel.<br><br><\\/p><h4>How does the Child Panel work?<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">A child panel works in a way that you can specify the category of services that you want to provide to your customers. Whether you want to buy from a regular panel and sell to your customer\\u2019s likes, followers, or video views on Facebook and Instagram, you can easily do this on your child panel. All you have to do is export all the services after selecting the categories of choice to indicate the services you want to provide from your seller panel. You can sell to your customer and manage your customer orders without knowing about your seller panel. Your customer will never know about your seller panel. Your Childpanel will only connect with your seller panel by API.<\\/p>\"}', '2024-11-03 03:54:24', '2024-11-03 03:54:24'),
(836, 430, 1, '{\"title\":\"PWA\",\"domain_name\":\"https:\\/\\/smm-matrix.bugfinder.app\\/\",\"short_description\":\"Install Progressive Web Application\",\"description\":\"This site has app functionality. Install it on your device for extensive experience and easy access.\"}', '2024-12-09 03:06:04', '2025-01-01 06:41:22'),
(837, 431, 1, '{\"short_description\":\"<h4>What is a Child Panel?<br><\\/h4><p style=\\\"font-weight:400;font-size:16px;\\\">From its name, a child panel refers to a panel that has its connection derived from a regular panel. A child panel has fewer ranges of features when compared with a regular panel, and because of this, the user experience might be a little bit lesser.<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">SMM Panel are known as sites where you can purchase marketing services for your customers,<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">They are of two types namely regular panels and child panels. This article sets out to inform you about child panels and how they differ from the more popular regular cheap SMM panel.<br><br><\\/p><h4>Differences between child panel and regular panel<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">As stated earlier, a child panel is not the same as a regular panel, even though they are both SMM panels. The following are some of the differences that exist between a child panel and a regular panel.<br><br><\\/p><h4>How does the Child Panel work?<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">A child panel works in a way that you can specify the category of services that you want to provide to your customers. Whether you want to buy from a regular panel and sell to your customer\\u2019s likes, followers, or video views on Facebook and Instagram, you can easily do this on your child panel. All you have to do is export all the services after selecting the categories of choice to indicate the services you want to provide from your seller panel. You can sell to your customer and manage your customer orders without knowing about your seller panel. Your customer will never know about your seller panel. Your Childpanel will only connect with your seller panel by API.<\\/p>\"}', '2024-12-21 00:24:20', '2024-12-21 00:24:20'),
(838, 432, 1, '{\"short_description\":\"<h4>What is a Child Panel?<br><\\/h4><p style=\\\"font-weight:400;font-size:16px;\\\">From its name, a child panel refers to a panel that has its connection derived from a regular panel. A child panel has fewer ranges of features when compared with a regular panel, and because of this, the user experience might be a little bit lesser.<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">SMM Panel are known as sites where you can purchase marketing services for your customers,<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">They are of two types namely regular panels and child panels. This article sets out to inform you about child panels and how they differ from the more popular regular cheap SMM panel.<br><br><\\/p><h4>Differences between child panel and regular panel<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">As stated earlier, a child panel is not the same as a regular panel, even though they are both SMM panels. The following are some of the differences that exist between a child panel and a regular panel.<br><br><\\/p><h4>How does the Child Panel work?<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">A child panel works in a way that you can specify the category of services that you want to provide to your customers. Whether you want to buy from a regular panel and sell to your customer\\u2019s likes, followers, or video views on Facebook and Instagram, you can easily do this on your child panel. All you have to do is export all the services after selecting the categories of choice to indicate the services you want to provide from your seller panel. You can sell to your customer and manage your customer orders without knowing about your seller panel. Your customer will never know about your seller panel. Your Childpanel will only connect with your seller panel by API.<\\/p>\"}', '2024-12-21 00:24:42', '2024-12-21 00:24:42'),
(839, 433, 1, '{\"short_description\":\"<h4>What is a Child Panel?<br><\\/h4><p style=\\\"font-weight:400;font-size:16px;\\\">From its name, a child panel refers to a panel that has its connection derived from a regular panel. A child panel has fewer ranges of features when compared with a regular panel, and because of this, the user experience might be a little bit lesser.<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">SMM Panel are known as sites where you can purchase marketing services for your customers,<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">They are of two types namely regular panels and child panels. This article sets out to inform you about child panels and how they differ from the more popular regular cheap SMM panel.<br><br><\\/p><h4>Differences between child panel and regular panel<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">As stated earlier, a child panel is not the same as a regular panel, even though they are both SMM panels. The following are some of the differences that exist between a child panel and a regular panel.<br><br><\\/p><h4>How does the Child Panel work?<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">A child panel works in a way that you can specify the category of services that you want to provide to your customers. Whether you want to buy from a regular panel and sell to your customer\\u2019s likes, followers, or video views on Facebook and Instagram, you can easily do this on your child panel. All you have to do is export all the services after selecting the categories of choice to indicate the services you want to provide from your seller panel. You can sell to your customer and manage your customer orders without knowing about your seller panel. Your customer will never know about your seller panel. Your Childpanel will only connect with your seller panel by API.<\\/p>\"}', '2024-12-21 00:25:00', '2024-12-21 00:25:00'),
(840, 434, 1, '{\"short_description\":\"<h4>What is a Child Panel?<br><\\/h4><p style=\\\"font-weight:400;font-size:16px;\\\">From its name, a child panel refers to a panel that has its connection derived from a regular panel. A child panel has fewer ranges of features when compared with a regular panel, and because of this, the user experience might be a little bit lesser.<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">SMM Panel are known as sites where you can purchase marketing services for your customers,<\\/p><p style=\\\"font-weight:400;font-size:16px;\\\">They are of two types namely regular panels and child panels. This article sets out to inform you about child panels and how they differ from the more popular regular cheap SMM panel.<br><br><\\/p><h4>Differences between child panel and regular panel<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">As stated earlier, a child panel is not the same as a regular panel, even though they are both SMM panels. The following are some of the differences that exist between a child panel and a regular panel.<br><br><\\/p><h4>How does the Child Panel work?<\\/h4><h2><\\/h2><p style=\\\"font-weight:400;font-size:16px;\\\">A child panel works in a way that you can specify the category of services that you want to provide to your customers. Whether you want to buy from a regular panel and sell to your customer\\u2019s likes, followers, or video views on Facebook and Instagram, you can easily do this on your child panel. All you have to do is export all the services after selecting the categories of choice to indicate the services you want to provide from your seller panel. You can sell to your customer and manage your customer orders without knowing about your seller panel. Your customer will never know about your seller panel. Your Childpanel will only connect with your seller panel by API.<\\/p>\"}', '2024-12-21 00:25:21', '2024-12-21 00:25:21'),
(841, 435, 1, '{\"heading\":\"How Mass Order works?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here:<\\/p><p><a href=\\\"https:\\/\\/bugfinder.com\\/services\\\">https:\\/\\/bugfinder.com\\/services<\\/a><\\/p><p><br><\\/p><p>Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer<\\/p><p>Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity<\\/p><p><br><\\/p><p>or in this example:<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p>\"}', '2024-12-21 23:24:34', '2024-12-21 23:24:51'),
(842, 436, 1, '{\"heading\":\"How Mass Order works?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here:<\\/p><p><a href=\\\"https:\\/\\/bugfinder.com\\/services\\\">https:\\/\\/bugfinder.com\\/services<\\/a><\\/p><p><br><\\/p><p>Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer<\\/p><p>Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity<\\/p><p><br><\\/p><p>or in this example:<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p>\"}', '2024-12-21 23:25:48', '2024-12-21 23:25:48'),
(843, 437, 1, '{\"heading\":\"How Mass Order works?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here:<\\/p><p><a href=\\\"https:\\/\\/bugfinder.com\\/services\\\">https:\\/\\/bugfinder.com\\/services<\\/a><\\/p><p><br><\\/p><p>Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer<\\/p><p>Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity<\\/p><p><br><\\/p><p>or in this example:<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p>\"}', '2024-12-21 23:26:07', '2024-12-21 23:26:07'),
(844, 438, 1, '{\"heading\":\"How Mass Order works?\",\"description\":\"<p>You put the service ID followed by | followed by the link followed by | followed by quantity on each line To get the service ID of a service please check here:<\\/p><p><a href=\\\"https:\\/\\/bugfinder.com\\/services\\\">https:\\/\\/bugfinder.com\\/services<\\/a><\\/p><p><br><\\/p><p>Let\\u2019s say you want to use the Mass Order to add Instagram Followers to your 3 accounts: abcd, asdf, qwer<\\/p><p>Let\\u2019s say you want to add 1000 followers for each account, the output will be like this: ID|Link|Quantity<\\/p><p><br><\\/p><p>or in this example:<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p><p>3740|abcd|1000<\\/p>\"}', '2024-12-21 23:26:57', '2024-12-21 23:26:57'),
(852, 439, 1, '{\"title\":\"PWA\",\"domain_name\":\"https:\\/\\/smm-matrix.bugfinder.app\\/\",\"short_description\":\"Install Progressive Web Application\",\"description\":\"This site has app functionality. Install it on your device for extensive experience and easy access.\"}', '2025-01-01 06:40:49', '2025-01-01 06:40:49'),
(853, 440, 1, '{\"title\":\"PWA\",\"domain_name\":\"https:\\/\\/smm-matrix.bugfinder.app\\/\",\"short_description\":\"Install Progressive Web Application\",\"description\":\"This site has app functionality. Install it on your device for extensive experience and easy access.\"}', '2025-01-01 06:41:56', '2025-01-01 06:41:56'),
(854, 441, 1, '{\"title\":\"PWA\",\"domain_name\":\"https:\\/\\/smm-matrix.bugfinder.app\\/\",\"short_description\":\"Install Progressive Web Application\",\"description\":\"This site has app functionality. Install it on your device for extensive experience and easy access.\"}', '2025-01-01 06:42:32', '2025-01-01 06:42:32'),
(855, 442, 1, '{\"title\":\"PWA\",\"domain_name\":\"https:\\/\\/smm-matrix.bugfinder.app\\/\",\"short_description\":\"Install Progressive Web Application\",\"description\":\"This site has app functionality. Install it on your device for extensive experience and easy access.\"}', '2025-01-01 06:43:02', '2025-01-01 06:43:02'),
(856, 443, 1, '{\"title\":\"PWA\",\"domain_name\":\"https:\\/\\/smm-matrix.bugfinder.app\\/\",\"short_description\":\"Install Progressive Web Application\",\"description\":\"This site has app functionality. Install it on your device for extensive experience and easy access.\"}', '2025-01-01 06:43:53', '2025-01-01 06:43:53'),
(862, 445, 1, NULL, '2025-01-02 06:55:32', '2025-01-02 06:55:32'),
(863, 446, 1, NULL, '2025-01-02 07:09:06', '2025-01-02 07:09:06'),
(864, 447, 1, NULL, '2025-01-02 07:09:10', '2025-01-02 07:09:10'),
(865, 448, 1, NULL, '2025-01-02 07:09:13', '2025-01-02 07:09:13');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `symbol` varchar(20) DEFAULT NULL,
  `conversion_rate` double(8,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `sort_by` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `conversion_rate`, `status`, `sort_by`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', '$', 1.00, 1, 1, '2024-07-05 18:47:28', '2024-07-17 16:47:39');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `depositable_id` int(11) DEFAULT NULL,
  `depositable_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `child_panel_id` int(11) DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method_currency` varchar(255) DEFAULT NULL,
  `amount` decimal(18,8) NOT NULL DEFAULT 0.00000000,
  `percentage_charge` decimal(18,8) NOT NULL DEFAULT 0.00000000,
  `fixed_charge` decimal(18,8) NOT NULL DEFAULT 0.00000000,
  `payable_amount` decimal(18,8) NOT NULL DEFAULT 0.00000000 COMMENT 'Amount payed',
  `base_currency_charge` double(18,8) DEFAULT 0.00000000,
  `payable_amount_in_base_currency` double(18,8) NOT NULL DEFAULT 0.00000000,
  `btc_amount` decimal(18,8) DEFAULT NULL,
  `btc_wallet` varchar(255) DEFAULT NULL,
  `payment_id` varchar(191) DEFAULT NULL,
  `information` text DEFAULT NULL,
  `trx_id` char(36) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=pending, 1=success, 2=request, 3=rejected',
  `note` text DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => child panel user, 1 => main user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `draft_mass_orders`
--

CREATE TABLE `draft_mass_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `child_panel_id` int(11) DEFAULT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `price` double(18,8) NOT NULL DEFAULT 0.00000000,
  `remarks` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ' 0 => invalid, 1 => valid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `file_storages`
--

CREATE TABLE `file_storages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `driver` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 => active, 0 => inactive',
  `parameters` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_storages`
--

INSERT INTO `file_storages` (`id`, `code`, `name`, `logo`, `driver`, `status`, `parameters`, `created_at`, `updated_at`) VALUES
(1, 's3', 'Amazon S3', 'driver/GJrBdvIxtnEprk0kHylgzNh6LcGcfOUcA205IIK5.png', 'local', 0, '{\"access_key_id\":\"xys6\",\"secret_access_key\":\"xys\",\"default_region\":\"xys5\",\"bucket\":\"xys6\",\"url\":\"xysds\"}', NULL, '2024-03-05 20:13:56'),
(2, 'sftp', 'SFTP', 'driver/q8E08YsobyRZGOLHHeKGhwysWsi25F186EbaNNRx.png', 'local', 0, '{\"sftp_username\":\"xys6\",\"sftp_password\":\"xys\"}', NULL, '2023-06-10 11:28:03'),
(3, 'do', 'Digitalocean Spaces', 'driver/iA8q685PBCnOAkmctLXZWhyqSoh7cJMOewpW4S8r.png', 'local', 0, '{\"spaces_key\":\"hj\",\"spaces_secret\":\"vh\",\"spaces_endpoint\":\"jk\",\"spaces_region\":\"sfo2\",\"spaces_bucket\":\"assets-coral\"}', NULL, '2023-06-10 11:45:21'),
(4, 'ftp', 'FTP', 'driver/wIwEOAJ45KgVGw0PL80WNfcbosB4IuUlxStfeHCX.png', 'local', 0, '{\"ftp_host\":\"xys6\",\"ftp_username\":\"xys\",\"ftp_password\":\"xys6\"}', NULL, '2023-06-10 11:27:43'),
(5, 'local', 'Local Storage', '', NULL, 1, NULL, NULL, '2024-03-05 20:13:56');

-- --------------------------------------------------------

--
-- Table structure for table `fire_base_tokens`
--

CREATE TABLE `fire_base_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_id` int(11) DEFAULT NULL,
  `tokenable_type` varchar(255) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `sort_by` int(11) DEFAULT 1,
  `image` varchar(191) DEFAULT NULL,
  `driver` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: inactive, 1: active',
  `parameters` text DEFAULT NULL,
  `currencies` text DEFAULT NULL,
  `extra_parameters` text DEFAULT NULL,
  `supported_currency` varchar(255) DEFAULT NULL,
  `receivable_currencies` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `currency_type` tinyint(1) NOT NULL DEFAULT 1,
  `is_sandbox` tinyint(1) NOT NULL DEFAULT 0,
  `environment` enum('test','live') NOT NULL DEFAULT 'live',
  `is_manual` tinyint(1) DEFAULT 1,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `code`, `name`, `sort_by`, `image`, `driver`, `status`, `parameters`, `currencies`, `extra_parameters`, `supported_currency`, `receivable_currencies`, `description`, `currency_type`, `is_sandbox`, `environment`, `is_manual`, `note`, `created_at`, `updated_at`) VALUES
(1, 'paypal', 'Paypal', 11, 'gateway/cCmKX4VMzHorJkQ9omsZdOLIZLXA56.avif', 'local', 0, '{\"cleint_id\":\"\",\"secret\":\"\"}', '{\"0\":{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"USD\"}}', NULL, '[\"USD\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(2, 'stripe', 'Stripe ', 1, 'gateway/Fpn6DbOj8Kh0qEqmDcqzPLaYetzHdU.avif', 'local', 0, '{\"secret_key\":\"\",\"publishable_key\":\"\"}', '{\"0\":{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}}', NULL, '[\"USD\",\"AUD\",\"GBP\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"1\",\"min_limit\":\"9\",\"max_limit\":\"100000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0.5\"},{\"name\":\"AUD\",\"currency_symbol\":\"AUD\",\"conversion_rate\":\"1.51\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"},{\"name\":\"GBP\",\"currency_symbol\":\"GBP\",\"conversion_rate\":\"0.77\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(3, 'skrill', 'Skrill', 4, 'gateway/sFW8RqOtyTiIo8369MLJFmMsfHtYHX.avif', 'local', 0, '{\"pay_to_email\":\"\",\"secret_key\":\"\"}', '{\"0\":{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"MAD\":\"MAD\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PLN\":\"PLN\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"SAR\":\"SAR\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TND\":\"TND\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\",\"COP\":\"COP\"}}', NULL, '[\"AED\",\"AUD\",\"USD\",\"CAD\"]', '[{\"name\":\"AED\",\"currency_symbol\":\"AUD\",\"conversion_rate\":\"0.014\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"},{\"name\":\"AUD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"15000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"},{\"name\":\"USD\",\"currency_symbol\":\"AED\",\"conversion_rate\":\"0.012\",\"min_limit\":\"1\",\"max_limit\":\"20000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"},{\"name\":\"CAD\",\"currency_symbol\":\"CAD\",\"conversion_rate\":\"0.012\",\"min_limit\":\"1\",\"max_limit\":\"32000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(4, 'perfectmoney', 'Perfect Money', 9, 'gateway/B1uwuCo5fk4FVyBSm8yxErDtezvo9R.avif', 'local', 0, '{\"passphrase\":\"\",\"payee_account\":\"\"}', '{\"0\":{\"USD\":\"USD\",\"EUR\":\"EUR\"}}', NULL, '[\"USD\",\"EUR\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0\"},{\"name\":\"EUR\",\"currency_symbol\":\"EUR\",\"conversion_rate\":\"0.0083\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(5, 'paytm', 'PayTM', 23, 'gateway/9OxY8ZDv4JGt3MS7zPEquDtQ9b1vWU.avif', 'local', 0, '{\"MID\":\"\",\"merchant_key\":\"\",\"WEBSITE\":\"\",\"INDUSTRY_TYPE_ID\":\"\",\"CHANNEL_ID\":\"\",\"environment_url\":\"\",\"process_transaction_url\":\"\"}', '{\"0\":{\"AUD\":\"AUD\",\"ARS\":\"ARS\",\"BDT\":\"BDT\",\"BRL\":\"BRL\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"GEL\":\"GEL\",\"GHS\":\"GHS\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"NGN\":\"NGN\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"UGX\":\"UGX\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"GBP\":\"GBP\",\"USD\":\"USD\",\"VND\":\"VND\",\"XOF\":\"XOF\"}}', NULL, '[\"AUD\",\"CAD\"]', '[{\"name\":\"AUD\",\"currency_symbol\":\"AUD\",\"conversion_rate\":\"0.014\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"CAD\",\"currency_symbol\":\"CAD\",\"conversion_rate\":\"0.012\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(6, 'payeer', 'Payeer', 17, 'gateway/7HTCjJpFcRmHqM1kJSpaRuTA0MzNqG.avif', 'local', 0, '{\"merchant_id\":\"\",\"secret_key\":\"\"}', '{\"0\":{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}}', '{\"status\":\"ipn\"}', '[\"USD\",\"RUB\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"RUB\",\"currency_symbol\":\"RUD\",\"conversion_rate\":\"0.81\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(7, 'paystack', 'PayStack', 6, 'gateway/Km8ogMTUmpEdjbHRvLma7enfvafO3N.avif', 'local', 0, '{\"public_key\":\"\",\"secret_key\":\"\"}', '{\"0\":{\"USD\":\"USD\",\"NGN\":\"NGN\"}}', '{\"callback\":\"ipn\",\"webhook\":\"ipn\"}\r\n', '[\"USD\",\"NGN\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"NGN\",\"currency_symbol\":\"NGN\",\"conversion_rate\":\"7.40\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(8, 'voguepay', 'VoguePay', 33, 'gateway/x6HOsziQhmuJ7iu46zMKdBEewDSesm.avif', 'local', 0, '{\"merchant_id\":\"\"}', '{\"0\":{\"NGN\":\"NGN\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"ZAR\":\"ZAR\",\"JPY\":\"JPY\",\"INR\":\"INR\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PLN\":\"PLN\"}}\n\n\n\n\n\n\n\n\n', NULL, '[\"NGN\",\"EUR\"]', '[{\"name\":\"NGN\",\"currency_symbol\":\"NGN\",\"conversion_rate\":\"7.40\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"EUR\",\"currency_symbol\":\"EUR\",\"conversion_rate\":\"0.0083\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(9, 'flutterwave', 'Flutterwave', 5, 'gateway/SUpub5TEkx7MOcetX340zn7LGSH0Sa.avif', 'local', 0, '{\"public_key\":\"\",\"secret_key\":\"\",\"encryption_key\":\"\"}', '{\"0\":{\"KES\":\"KES\",\"GHS\":\"GHS\",\"NGN\":\"NGN\",\"USD\":\"USD\",\"GBP\":\"GBP\",\"EUR\":\"EUR\",\"UGX\":\"UGX\",\"TZS\":\"TZS\"}}', NULL, '[\"GHS\",\"NGN\",\"USD\"]', '[{\"name\":\"GHS\",\"currency_symbol\":\"GHS\",\"conversion_rate\":\"0.11\",\"min_limit\":\"1\",\"max_limit\":\"50000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"NGN\",\"currency_symbol\":\"NGN\",\"conversion_rate\":\"7.40\",\"min_limit\":\"1\",\"max_limit\":\"50000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'test', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(10, 'razorpay', 'RazorPay', 7, 'gateway/HvTfH2WAQtw0pcN4ZzssUT5l86FMCZ.avif', 'local', 0, '{\"key_id\":\"\",\"key_secret\":\"\"}', '{\"0\":{\"INR\":\"INR\"}}', NULL, '[\"INR\"]', '[{\"name\":\"INR\",\"currency_symbol\":\"INR\",\"conversion_rate\":84.08,\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(11, 'instamojo', 'instamojo', 14, 'gateway/rwXQ1P62ePQcvJBIUZRkHMumLbWF73.avif', 'local', 0, '{\"api_key\":\"\",\"auth_token\":\"\",\"salt\":\"\"}', '{\"0\":{\"INR\":\"INR\"}}', NULL, '[\"INR\"]', '[{\"name\":\"INR\",\"currency_symbol\":\"INR\",\"conversion_rate\":\"0.76\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(12, 'mollie', 'Mollie', 27, 'gateway/S83QZxmVtxCkvl8OGWFGgChxmUcQhc.avif', 'local', 0, '{\"api_key\":\"\"}', '{\"0\":{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}}', NULL, '[\"AUD\",\"BRL\"]', '[{\"name\":\"AUD\",\"currency_symbol\":\"AUD\",\"conversion_rate\":\"0.014\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"BRL\",\"currency_symbol\":\"BRL\",\"conversion_rate\":\"0.045\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(13, 'twocheckout', '2checkout', 12, 'gateway/bmAgQ5rUbx2rktlaztA89GEQCKYTxJ.avif', 'local', 0, '{\"merchant_code\":\"\",\"secret_key\":\"\"}', '{\"0\":{\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"DZD\":\"DZD\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"AZN\":\"AZN\",\"BSD\":\"BSD\",\"BDT\":\"BDT\",\"BBD\":\"BBD\",\"BZD\":\"BZD\",\"BMD\":\"BMD\",\"BOB\":\"BOB\",\"BWP\":\"BWP\",\"BRL\":\"BRL\",\"GBP\":\"GBP\",\"BND\":\"BND\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"XCD\":\"XCD\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"FJD\":\"FJD\",\"GTQ\":\"GTQ\",\"HKD\":\"HKD\",\"HNL\":\"HNL\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JMD\":\"JMD\",\"JPY\":\"JPY\",\"KZT\":\"KZT\",\"KES\":\"KES\",\"LAK\":\"LAK\",\"MMK\":\"MMK\",\"LBP\":\"LBP\",\"LRD\":\"LRD\",\"MOP\":\"MOP\",\"MYR\":\"MYR\",\"MVR\":\"MVR\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NIO\":\"NIO\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PGK\":\"PGK\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"WST\":\"WST\",\"SAR\":\"SAR\",\"SCR\":\"SCR\",\"SGD\":\"SGD\",\"SBD\":\"SBD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"SYP\":\"SYP\",\"THB\":\"THB\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TRY\":\"TRY\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"USD\":\"USD\",\"VUV\":\"VUV\",\"VND\":\"VND\",\"XOF\":\"XOF\",\"YER\":\"YER\"}}', '{\"approved_url\":\"ipn\"}', '[\"AFN\",\"ARS\"]', '[{\"name\":\"AFN\",\"currency_symbol\":\"AFN\",\"conversion_rate\":\"0.63\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"ARS\",\"currency_symbol\":\"ARS\",\"conversion_rate\":\"3.24\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(14, 'authorizenet', 'Authorize.Net', 8, 'gateway/kY6uyYr0nPgU0SyM69Yy4ei7aAowCu.avif', 'local', 0, '{\"login_id\":\"\",\"current_transaction_key\":\"\"}', '{\"0\":{\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"USD\":\"USD\"}}', NULL, '[\"AUD\",\"CAD\"]', '[{\"name\":\"AUD\",\"currency_symbol\":\"AUD\",\"conversion_rate\":\"0.014\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0\"},{\"name\":\"CAD\",\"currency_symbol\":\"CAD\",\"conversion_rate\":\"0.012\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(16, 'payumoney', 'PayUmoney', 28, 'gateway/TjSy1hfABIV2RzIRECRJcwmN04sGEh.avif', 'local', 0, '{\"merchant_key\":\"\",\"salt\":\"\"}', '{\"0\":{\"INR\":\"INR\"}}', NULL, '[\"INR\"]', '[{\"name\":\"INR\",\"currency_symbol\":\"INR\",\"conversion_rate\":\"0.76\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(17, 'mercadopago', 'Mercado Pago', 18, 'gateway/2UlZWhhkfVSWQepk1uBKecw4FrepZx.avif', 'local', 0, '{\"access_token\":\"\"}', '{\"0\":{\"ARS\":\"ARS\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"DOP\":\"DOP\",\"EUR\":\"EUR\",\"GTQ\":\"GTQ\",\"HNL\":\"HNL\",\"MXN\":\"MXN\",\"NIO\":\"NIO\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PYG\":\"PYG\",\"USD\":\"USD\",\"UYU\":\"UYU\",\"VEF\":\"VEF\",\"VES\":\"VES\"}}', NULL, '[\"ARS\"]', '[{\"name\":\"ARS\",\"currency_symbol\":\"ARS\",\"conversion_rate\":\"3.24\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(18, 'coingate', 'Coingate', 19, 'gateway/uxKFypl7GtiL0YnJhshsLKyGzf2YKt.avif', 'local', 0, '{\"api_key\":\"\"}', '{\"0\":{\"USD\":\"USD\",\"EUR\":\"EUR\"}}', NULL, '[\"USD\",\"EUR\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"EUR\",\"currency_symbol\":\"EUR\",\"conversion_rate\":\"0.0083\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(19, 'coinbasecommerce', 'Coinbase Commerce', 16, 'gateway/POaHQGEUctnNpM9YgAvIIwq0R9aXnw.avif', 'local', 0, '{\"api_key\":\"\",\"secret\":\"\"}', '{\"0\":{\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CHF\":\"CHF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"EUR\":\"EUR\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GBP\":\"GBP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HKD\":\"HKD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"INR\":\"INR\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NOK\":\"NOK\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RUB\":\"RUB\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TRY\":\"TRY\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZAR\":\"ZAR\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}}', '{\"webhook\":\"ipn\"}', '[\"AED\",\"ALL\"]', '[{\"name\":\"AED\",\"currency_symbol\":\"AED\",\"conversion_rate\":\"0.033\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"ALL\",\"currency_symbol\":\"ALL\",\"conversion_rate\":\"0.85\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(20, 'monnify', 'Monnify', 20, 'gateway/N9ZZ4F4YeYM4m78gZW0Gnm8HTu037v.avif', 'local', 0, '{\"api_key\":\"\",\"secret_key\":\"\",\"contract_code\":\"\"}', '{\"0\":{\"NGN\":\"NGN\"}}', NULL, '[\"NGN\"]', '[{\"name\":\"NGN\",\"currency_symbol\":\"NGN\",\"conversion_rate\":\"7.40\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(22, 'coinpayments', 'CoinPayments', 21, 'gateway/truY5ILTjTIFunGBf7Hn5vcWSxYw6Q.avif', 'local', 0, '{\"merchant_id\":\"\",\"private_key\":\"\",\"public_key\":\"\"}', '{\"0\":{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\"},\"1\":{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}}', '{\"callback\":\"ipn\"}', '[\"USD\",\"AUD\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"AUD\",\"currency_symbol\":\"AUD\",\"conversion_rate\":\"0.014\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(23, 'blockchain', 'Blockchain', 24, 'gateway/20zn8YG4VPgOUSBQHvj0GeKMHwL4ZY.avif', 'local', 0, '{\"api_key\":\"\",\"xpub_code\":\"\"}', '{\"1\":{\"BTC\":\"BTC\"}}', NULL, '[\"BTC\"]', '[{\"name\":\"BTC\",\"currency_symbol\":\"BTC\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"50\",\"max_limit\":\"500000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 0, 0, 'live', NULL, NULL, '2020-09-09 21:05:02', '2025-01-04 09:16:23'),
(25, 'cashmaal', 'cashmaal', 25, 'gateway/7Y3IZE7VY61XHwNxRzrgWVFZx8zUu0.avif', 'local', 0, '{\"web_id\":\"\",\"ipn_key\":\"\"}', '{\"0\":{\"PKR\":\"PKR\",\"USD\":\"USD\"}}', '{\"ipn_url\":\"ipn\"}', '[\"PKR\",\"USD\"]', '[{\"name\":\"PKR\",\"currency_symbol\":\"PKR\",\"conversion_rate\":\"2.56\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, NULL, '2025-01-04 09:16:23'),
(26, 'midtrans', 'Midtrans', 2, 'gateway/7fRFCClfGcMefCb35AVzgnEJevUi37.avif', 'local', 0, '{\"client_key\":\"\",\"server_key\":\"\"}', '{\"0\":{\"IDR\":\"IDR\"}}', '{\"payment_notification_url\":\"ipn\", \"finish redirect_url\":\"ipn\", \"unfinish redirect_url\":\"failed\",\"error redirect_url\":\"failed\"}', '[\"IDR\"]', '[{\"name\":\"IDR\",\"currency_symbol\":\"IDR\",\"conversion_rate\":\"141.38\",\"min_limit\":\"1\",\"max_limit\":\"50000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'test', NULL, '', '2020-09-08 15:05:02', '2025-01-04 09:16:23'),
(27, 'peachpayments', 'peachpayments', 34, 'gateway/4aJggeZFR2SBLYMw9DewRUOByPaRez.avif', 'local', 0, '{\"Authorization_Bearer\":\"\",\"Entity_ID\":\"\",\"Recur_Channel\":\"\"}', '{\"0\":{\"AED\":\"AED\",\"AFA\":\"AFA\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"AWG\":\"AWG\",\"AZM\":\"AZM\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYR\":\"BYR\",\"BZD\":\"BZD\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CYP\":\"CYP\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EEK\":\"EEK\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"EUR\":\"EUR\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GBP\":\"GBP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHC\":\"GHC\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HKD\":\"HKD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"INR\":\"INR\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LTL\":\"LTL\",\"LVL\":\"LVL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MTL\":\"MTL\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"MZM\":\"MZM\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NOK\":\"NOK\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PTS\":\"PTS\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDD\":\"SDD\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"SHP\":\"SHP\",\"SIT\":\"SIT\",\"SKK\":\"SKK\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SPL\":\"SPL\",\"SRD\":\"SRD\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMM\":\"TMM\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TRL\":\"TRL\",\"TRY\":\"TRY\",\"TTD\":\"TTD\",\"TVD\":\"TVD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZAR\":\"ZAR\",\"ZMK\":\"ZMK\",\"ZWD\":\"ZWD\"}}', NULL, '[\"CAD\",\"AED\"]', '[{\"name\":\"CAD\",\"currency_symbol\":\"CAD\",\"conversion_rate\":\"0.012\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"AED\",\"currency_symbol\":\"AED\",\"conversion_rate\":\"0.033\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'live', NULL, '', '2020-09-08 21:05:02', '2025-01-04 09:16:23'),
(28, 'nowpayments', 'Nowpayments', 26, 'gateway/Z5wvvbRZN7nZUC6GgPTqMyf1lM2WBf.avif', 'local', 0, '{\"api_key\":\"\"}', '{\"1\":{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}}', '{\"cron\":\"ipn\"}', '[\"ETH\",\"XEM\"]', '[{\"name\":\"ETH\",\"currency_symbol\":\"XEM\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"10\",\"max_limit\":\"500000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"},{\"name\":\"XEM\",\"currency_symbol\":\"ETH\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"10\",\"max_limit\":\"500000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 0, 1, 'live', NULL, '', '2020-09-08 15:05:02', '2025-01-04 09:16:23'),
(29, 'khalti', 'Khalti Payment', 29, 'gateway/x4BeAPBkYuM494NvWfAkrxTfk1tbUt.avif', 'local', 0, '{\"secret_key\":\"\",\"public_key\":\"\"}', '{\"0\":{\"NPR\":\"NPR\"}}', NULL, '[\"NPR\"]', '[{\"name\":\"NPR\",\"currency_symbol\":\"NPR\",\"conversion_rate\":\"1.21\",\"min_limit\":\"1\",\"max_limit\":\"50000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, '', '2020-09-08 15:05:02', '2025-01-04 09:16:23'),
(30, 'swagger', 'MAGUA PAY', 22, 'gateway/j8bFL5e5LOn6YkquKQiy6com8w1uj2.avif', 'local', 0, '{\"MAGUA_PAY_ACCOUNT\":\"\",\"MerchantKey\":\"\",\"Secret\":\"\"}', '{\"0\":{\"EUR\":\"EUR\"}}', NULL, '[\"EUR\"]', '[{\"name\":\"EUR\",\"currency_symbol\":\"EUR\",\"conversion_rate\":\"0.0083\",\"min_limit\":\"1\",\"max_limit\":\"50000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, '', '2020-09-08 15:05:02', '2025-01-04 09:16:23'),
(31, 'freekassa', 'Free kassa', 35, 'gateway/VqJR12ZLuhmisIpUbpm6p2OCqm4hHC.avif', 'local', 0, '{\"merchant_id\":\"\",\"merchant_key\":\"\",\"secret_word\":\"\",\"secret_word2\":\"\"}', '{\"0\":{\"RUB\":\"RUB\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"UAH\":\"UAH\",\"KZT\":\"KZT\"}}', '{\"ipn_url\":\"ipn\"}', '[\"RUB\",\"USD\"]', '[{\"name\":\"RUB\",\"currency_symbol\":\"RUB\",\"conversion_rate\":\"0.81\",\"min_limit\":\"1\",\"max_limit\":\"15000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"50000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, '', '2020-09-08 15:05:02', '2025-01-04 09:16:23'),
(32, 'konnect', 'Konnect', 30, 'gateway/DIWitJin1UBjkwTLrSPcstnUDGmTz3.avif', 'local', 0, '{\"api_key\":\"\",\"receiver_wallet_Id\":\"\"}', '{\"0\":{\"TND\":\"TND\",\"EUR\":\"EUR\",\"USD\":\"USD\"}}', '{\"webhook\":\"ipn\"}', '[\"USD\",\"TND\",\"EUR\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"15000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"name\":\"TND\",\"currency_symbol\":\"TND\",\"conversion_rate\":\"0.028\",\"min_limit\":\"1\",\"max_limit\":\"20000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"},{\"name\":\"EUR\",\"currency_symbol\":\"EUR\",\"conversion_rate\":\"0.0083\",\"min_limit\":\"1\",\"max_limit\":\"60000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'live', NULL, '', '2020-09-08 15:05:02', '2025-01-04 09:16:23'),
(33, 'mypay', 'Mypay Np', 32, 'gateway/kkBeSnA5MFdlLrrSOpF3dJp1JwMxIB.avif', 'local', 0, '{\"merchant_username\":\"\",\"merchant_api_password\":\"\",\"merchant_id\":\"\",\"api_key\":\"\"}', '{\"0\":{\"NPR\":\"NPR\"}}', NULL, '[\"NPR\"]', '[{\"name\":\"NPR\",\"currency_symbol\":\"NPR\",\"conversion_rate\":\"1.21\",\"min_limit\":\"1\",\"max_limit\":\"15000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'live', NULL, '', '2020-09-08 15:05:02', '2025-01-04 09:16:23'),
(35, 'imepay', 'IME PAY', 10, 'gateway/YuBFrsBWuxf17sqB6z8y039xgdxyat.avif', 'local', 0, '{\"MerchantModule\":\"\",\"MerchantCode\":\"\",\"username\":\"\",\"password\":\"\"}', '{\"0\":{\"NPR\":\"NPR\"}}', NULL, '[\"NPR\"]', '[{\"name\":\"NPR\",\"currency_symbol\":\"NPR\",\"conversion_rate\":134.51,\"min_limit\":\"10\",\"max_limit\":\"15000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"1.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, '', '2020-09-08 15:05:02', '2025-01-04 09:16:23'),
(36, 'cashonexHosted', 'Cashonex Hosted', 15, 'gateway/GAcL1CamWpPaeDGaD6aSInqXknXK50.avif', 'local', 0, '{\"idempotency_key\":\"\",\"salt\":\"\"}', '{\"0\":{\"USD\":\"USD\"}}', NULL, '[\"USD\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"15000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2023-04-02 12:31:33', '2025-01-04 09:16:23'),
(37, 'cashonex', 'cashonex', 31, 'gateway/rbbey8zLDMKdNPftwRdOSY79eVEGLi.avif', 'local', 0, '{\"idempotency_key\":\"\",\"salt\":\"\"}', '{\"0\":{\"USD\":\"USD\"}}', NULL, '[\"USD\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"15000\",\"percentage_charge\":\"0.0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, NULL, '2023-04-02 12:34:54', '2025-01-04 09:16:23'),
(38, 'binance', 'Binance', 13, 'gateway/bZ7w2koAzATHG9gp8k6JzRhhusXTpH.avif', 'local', 0, '{\"mercent_api_key\":\"\",\"mercent_secret\":\"\"}', '{\"1\":{\"ADA\":\"ADA\",\"ATOM\":\"ATOM\",\"AVA\":\"AVA\",\"BCH\":\"BCH\",\"BNB\":\"BNB\",\"BTC\":\"BTC\",\"BUSD\":\"BUSD\",\"CTSI\":\"CTSI\",\"DASH\":\"DASH\",\"DOGE\":\"DOGE\",\"DOT\":\"DOT\",\"EGLD\":\"EGLD\",\"EOS\":\"EOS\",\"ETC\":\"ETC\",\"ETH\":\"ETH\",\"FIL\":\"FIL\",\"FRONT\":\"FRONT\",\"FTM\":\"FTM\",\"GRS\":\"GRS\",\"HBAR\":\"HBAR\",\"IOTX\":\"IOTX\",\"LINK\":\"LINK\",\"LTC\":\"LTC\",\"MANA\":\"MANA\",\"MATIC\":\"MATIC\",\"NEO\":\"NEO\",\"OM\":\"OM\",\"ONE\":\"ONE\",\"PAX\":\"PAX\",\"QTUM\":\"QTUM\",\"STRAX\":\"STRAX\",\"SXP\":\"SXP\",\"TRX\":\"TRX\",\"TUSD\":\"TUSD\",\"UNI\":\"UNI\",\"USDC\":\"USDC\",\"USDT\":\"USDT\",\"WRX\":\"WRX\",\"XLM\":\"XLM\",\"XMR\":\"XMR\",\"XRP\":\"XRP\",\"XTZ\":\"XTZ\",\"XVS\":\"XVS\",\"ZEC\":\"ZEC\",\"ZIL\":\"ZIL\"}}', NULL, '[\"BTC\"]', '[{\"name\":\"BTC\",\"currency_symbol\":\"BTC\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"5\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 0, 0, 'live', NULL, NULL, '2023-04-02 13:36:14', '2025-01-04 09:16:23'),
(39, 'cinetpay', 'CinetPay ', 36, 'gateway/9WCd4Kn4EvlDX8y4V3bEV7eazCTlla.avif', 'local', 0, '{\"apiKey\":\"\",\"site_id\":\"\"}', '{\"0\":{\"XOF\":\"XOF\",\"XAF\":\"XAF\",\"CDF\":\"CDF\",\"GNF\":\"GNF\",\"USD\":\"USD\"}}', 'NULL', '[\"XOF\"]', '[{\"name\":\"XOF\",\"currency_symbol\":\"XOF\",\"conversion_rate\":\"5.45\",\"min_limit\":\"1\",\"max_limit\":\"50000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'test', NULL, NULL, '2023-04-02 13:36:14', '2025-01-04 09:16:23'),
(40, 'bkash', 'Bkash ', 3, 'gateway/Hi2rsJIiOmjaIyfGSvQcpqtSyKHIuj.webp', 'local', 0, '{\"username\":\"\",\"password\":\"\",\"app_key\":\"\",\"app_secret\":\"\"}', '{\"0\":{\"BDT\":\"BDT\"}}', '', '[\"BDT\"]', '[{\"name\":\"BDT\",\"currency_symbol\":\"BDT\",\"conversion_rate\":\"100\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0.5\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2023-04-02 13:36:14', '2025-01-04 09:16:23'),
(41, 'nagad', 'Nagad', 37, 'gateway/bJ6RRqJdGzXk6rgMeiIi9nsAy6sPEM.webp', 'local', 0, '{\"merchant_id\":\"\",\"merchant_phone\":\"\",\"public_key\":\"\",\"private_key\":\"\"}', '{\"0\":{\"BDT\":\"BDT\"}}', '', '[\"BDT\"]', '[{\"name\":\"BDT\",\"currency_symbol\":\"BDT\",\"conversion_rate\":\"100\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\".5\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2023-04-02 13:36:14', '2025-01-04 09:16:23'),
(42, 'toyyibpay', 'Toyyibpay', 38, 'gateway/lMJHSgtYaOzIUpnmygdK6gjZCcR5A2.webp', 'local', 0, '{\"category_code\":\"\",\"secret_key\":\"\"}', '{\"0\":{\"MYR\":\"MYR\"}}', '', '[\"MYR\"]', '[{\"name\":\"MYR\",\"currency_symbol\":\"MYR\",\"conversion_rate\":\"4.27\",\"min_limit\":\"1\",\"max_limit\":\"1000000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2023-04-02 13:36:14', '2025-01-04 09:16:23'),
(43, 'cryptomus', 'Cryptomus', 38, 'gateway/l2V5gtDZF8zOjcF0TyKjzbjGciuF3o.webp', 'local', 0, '{\"mercent_id\":\"837erewra0ec-949705c54770\",\"api_key\":\"\"}', '{\"0\":{\"USD\":\"USD\"}}', '', '[\"USD\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"1000000000\",\"percentage_charge\":\"2.5\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2023-04-02 13:36:14', '2025-06-03 02:01:06'),
(44, 'square', 'Square', 11, 'gateway/XgwHMxPlHrxFg4fHjrzXmI0yz6nIKB.webp', 'local', 0, '{\"access_token\":\"EAAAl3CJOGbMel-6a_WqNjZUtwPCtxpaIhCd29zskQUvE0jqCCNORUJXZWmKDMjl\",\"location_id\":\"L8PXGEZTY200T\"}', '{\"0\":{\"USD\":\"USD\",\"CAD\":\"CAD\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"ARS\":\"ARS\",\"IDR\":\"IDR\",\"ZAR\":\"ZAR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"GBP\":\"GBP\",\"NZD\":\"NZD\",\"USD\":\"USD\"}}', NULL, '[\"USD\"]', '[{\"name\":\"USD\",\"currency_symbol\":\"USD\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 21:05:02', '2025-10-08 22:53:34'),
(45, 'iyzico', 'Iyzico', 11, 'gateway/DHgLjePJ5f0N5JcsxIWQqGVMdcAzly.webp', 'local', 0, '{\"api_key\":\"sandbox-PZSlZgUVyF1ql7boLsFiTHIFqSnOLJFx\",\"secret_key\":\"sandbox-fKJlOjUdR3Hwa6NgDMgZ3ytaLp658DCh\", \"base_url\":\"https://sandbox-api.iyzipay.com\"}', '{\"0\":{\"TRY\":\"TRY\",\"EUR\":\"EUR\",\"USD\":\"USD\",\"GBP\":\"GBP\",\"IRR\":\"IRR\",\"NOK\":\"NOK\",\"RUB\":\"RUB\",\"CHF\":\"CHF\"}}', NULL, '[\"TRY\"]', '[{\"name\":\"TRY\",\"currency_symbol\":\"TRY\",\"conversion_rate\":\"0.0091\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 21:05:02', '2025-10-08 22:53:47'),
(46, 'gcash', 'Gcash', 11, 'gateway/HTatWvTMgk4NkcM00EBPDGunp86ZcU.webp', 'local', 0, '{\"public_key\":\"\",\"secret_key\":\"\"}', '{\"0\":{\"PHP\":\"PHP\"}}', NULL, '[\"PHP\"]', '[{\"name\":\"PHP\",\"currency_symbol\":\"PHP\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 21:05:02', '2025-10-05 03:53:04'),
(47, 'ecitizen', 'ecitizen', 23, 'gateway/2AjLXrBvZSXFBCEsaQBi1mEmcfJ8wS.webp', 'local', 0, '{\"api_client_ID\":\"45\",\"service_ID\":\"48499\",\"key\":\"\",\"secret\":\"\"}', '{\"0\":{\"KES\":\"KES\"}}', NULL, '[\"KES\"]', '[{\"name\":\"KES\",\"currency_symbol\":\"KES\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"10000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 21:05:02', '2025-10-05 07:07:19'),
(48, 'uddoktapay', 'Uddoktapay', 1, 'gateway/vUraO5bpUkRZagQBfhQgoId7t7GfzF.webp', 'local', 0, '{\"api_key\":\"\",\"base_url\":\"https://sandbox.uddoktapay.com/\"}', '{\"0\":{\"BDT\":\"BDT\"}}', NULL, '[\"BDT\"]', '[{\"name\":\"BDT\",\"currency_symbol\":\"BDT\",\"conversion_rate\":\"120\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send form your payment gateway. your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 15:05:02', '2025-10-05 23:42:09'),
(49, 'mpesa', 'M-Pesa', 1, 'gateway/kW9E40kQahTarH9rtERPOd6Z0hafp4.webp', 'local', 0, '{\"consumer_key\":\"\", \"consumer_secret\":\"\", \"short_code\":\"174379\", \"pass_key\":\"\",\"webhook\":\"https://mydomain.com/path\"}', '{\"0\":{\"KES\":\"KES\"}}', NULL, '[\"KES\"]', '[{\"name\":\"KES\",\"currency_symbol\":\"KES\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send form your payment gateway. your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 15:05:02', '2025-10-07 08:04:31'),
(50, 'korapay', 'Korapay', 1, 'gateway/p7lvoIZp4bjNuTmhnlopgyZ3tOWE12.webp', 'local', 0, '{\"public_key\":\"\", \"secret_key\":\"\"}', '{\"0\":{\"GHS\":\"GHS\"}}', NULL, '[\"GHS\"]', '[{\"name\":\"GHS\",\"currency_symbol\":\"GHS\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"100000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0\"}]', 'Send form your payment gateway. your bank may charge you a cash advance fee.', 1, 1, 'test', NULL, NULL, '2020-09-09 15:05:02', '2025-10-06 23:02:50'),
(1000, 'bank-transfer', 'Bank Transfer', 1, 'gateway/A2zYpiPKpPWcByCCys7mpnCugQEHvv.avif', 'local', 1, '{\"AccountNumber\":{\"field_name\":\"AccountNumber\",\"field_label\":\"Account Number\",\"type\":\"text\",\"validation\":\"required\"},\"BeneficiaryName\":{\"field_name\":\"BeneficiaryName\",\"field_label\":\"Beneficiary Name\",\"type\":\"text\",\"validation\":\"required\"},\"NID\":{\"field_name\":\"NID\",\"field_label\":\"NID\",\"type\":\"file\",\"validation\":\"required\"}}', NULL, NULL, '[\"USD\",\"EUR\"]', '[{\"currency\":\"USD\",\"conversion_rate\":\"1\",\"min_limit\":\"1\",\"max_limit\":\"1000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"},{\"currency\":\"EUR\",\"conversion_rate\":\"0.92\",\"min_limit\":\"1\",\"max_limit\":\"1000\",\"percentage_charge\":\"0\",\"fixed_charge\":\"0.5\"}]', 'Send from your payment gateway. Your bank may charge you a cash advance fee.', 1, 0, 'live', NULL, 'Send form your payment gateway. your bank may charge you a cash advance fee.Send form your payment gateway. your bank may charge you a cash advance fee.Send form your payment gateway. your bank may charge you a cash advance fee.Send form your payment gateway. your bank may charge you a cash advance fee.Send form your payment gateway. your bank may charge you a cash advance fee.', NULL, '2024-12-22 05:35:46');

-- --------------------------------------------------------

--
-- Table structure for table `in_app_notifications`
--

CREATE TABLE `in_app_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `in_app_notificationable_id` int(11) NOT NULL,
  `in_app_notificationable_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `kycs`
--

CREATE TABLE `kycs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `input_form` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0 COMMENT '1 => Active, 0 => Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kycs`
--

INSERT INTO `kycs` (`id`, `name`, `slug`, `input_form`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Passport Verification', 'passport-verification', '{\"FullName\":{\"field_name\":\"FullName\",\"field_label\":\"Full Name\",\"type\":\"text\",\"validation\":\"required\"},\"PassportNumber\":{\"field_name\":\"PassportNumber\",\"field_label\":\"Passport Number\",\"type\":\"number\",\"validation\":\"required\"},\"DateOfBirth\":{\"field_name\":\"DateOfBirth\",\"field_label\":\"Date Of Birth\",\"type\":\"date\",\"validation\":\"optional\"},\"File\":{\"field_name\":\"File\",\"field_label\":\"File\",\"type\":\"file\",\"validation\":\"optional\"},\"Address\":{\"field_name\":\"Address\",\"field_label\":\"Address\",\"type\":\"textarea\",\"validation\":\"required\"}}', 0, '2024-05-30 00:36:35', '2025-01-04 08:44:14'),
(2, 'Address Verification', 'address-verification', '{\"Country\":{\"field_name\":\"Country\",\"field_label\":\"Country\",\"type\":\"text\",\"validation\":\"required\"},\"Capital\":{\"field_name\":\"Capital\",\"field_label\":\"Capital\",\"type\":\"text\",\"validation\":\"required\"},\"State\":{\"field_name\":\"State\",\"field_label\":\"State\",\"type\":\"text\",\"validation\":\"required\"}}', 0, '2024-07-03 22:00:53', '2024-10-30 22:33:27'),
(3, 'NID Verification', 'nid-verification', '{\"NIDNUMBER\":{\"field_name\":\"NIDNUMBER\",\"field_label\":\"NID NUMBER\",\"type\":\"number\",\"validation\":\"required\"},\"IMAGE\":{\"field_name\":\"IMAGE\",\"field_label\":\"IMAGE\",\"type\":\"file\",\"validation\":\"required\"}}', 0, '2024-10-06 20:48:08', '2024-11-08 05:12:39');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_name` varchar(20) DEFAULT NULL,
  `flag` varchar(100) DEFAULT NULL,
  `flag_driver` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => Inactive, 1 => Active',
  `rtl` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => Inactive, 1 => Active ',
  `default_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => Inactive, 1 => Active ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `short_name`, `flag`, `flag_driver`, `status`, `rtl`, `default_status`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 'language/zKyVGhLfN88CaZqOB4kqE6ryEwKwE3.webp', 'local', 1, 0, 1, '2023-06-16 16:35:53', '2024-12-01 20:00:51');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_modes`
--

CREATE TABLE `maintenance_modes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_driver` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_modes`
--

INSERT INTO `maintenance_modes` (`id`, `heading`, `description`, `image`, `image_driver`, `created_at`, `updated_at`) VALUES
(1, 'The website under maintenance!', '<p>We are currently undergoing scheduled maintenance to improve our services and enhance your user experience. During this time, our website/system will be temporarily unavailable.\r\n</p><p><br></p><p>\r\nWe apologize for any inconvenience this may cause and appreciate your patience. Please rest assured that we are working diligently to complete the maintenance as quickly as possible.</p>', 'maintenanceMode/3jXAnm42OZuYy3kVDcHKUjW3gyiG8eSo96rlgg19.png', 'local', '2023-10-03 16:44:32', '2024-02-04 22:00:13');

-- --------------------------------------------------------

--
-- Table structure for table `manage_menus`
--

CREATE TABLE `manage_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `menu_section` varchar(50) DEFAULT NULL,
  `menu_items` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `manage_menus`
--

INSERT INTO `manage_menus` (`id`, `theme`, `menu_section`, `menu_items`, `created_at`, `updated_at`) VALUES
(3, 'light_green', 'header', '[\"home\",\"about\",\"faq\",\"blogs\",\"service\",\"contact\"]', '2023-10-15 14:54:10', '2024-10-29 20:33:37'),
(4, 'light_green', 'footer', '{\"useful_link\":[\"home\",\"about\",\"faq\",\"blogs\",\"contact\"],\"support_link\":[\"api docs\",\"privacy &amp; policy\",\"terms and conditions\",\"refund policy\",\"cookie policy\"]}', '2023-10-15 14:54:10', '2024-10-29 21:31:45'),
(5, 'dark_voilet', 'header', '[\"home\",\"about\",\"faq\",\"blogs\",\"service\",\"contact\"]', '2023-10-15 14:54:10', '2024-10-29 22:41:42'),
(6, 'dark_voilet', 'footer', '{\"useful_link\":[\"home\",\"about\",\"faq\",\"blogs\",\"contact\"],\"support_link\":[\"api docs\",\"privacy &amp; policy\",\"terms and conditions\",\"refund policy\",\"cookie policy\"]}', '2023-10-15 14:54:10', '2024-10-30 00:34:48'),
(7, 'minimal', 'header', '[\"home\",\"about\",\"blogs\",\"Services\",\"contact\"]', '2023-10-15 14:54:10', '2024-10-30 01:49:31'),
(8, 'minimal', 'footer', '{\"useful_link\":[\"home\",\"about\",\"blogs\",\"faq\",\"contact\"],\"support_link\":[\"api docs\",\"privacy policy\",\"terms and  conditions\",\"refund policy\",\"cookie policy\"]}', '2023-10-15 14:54:10', '2024-10-30 03:28:04'),
(75, 'deep_blue', 'header', '[\"home\",\"about\",\"blogs\",\"Services\",\"contact\"]', '2023-10-15 14:54:10', '2024-10-30 08:33:39'),
(76, 'deep_blue', 'footer', '{\"useful_link\":[\"home\",\"about\",\"blogs\",\"faq\",\"contact\"],\"support_link\":[\"api docs\",\"privacy policy\",\"terms conditions\",\"refund policy\",\"cookie policy\"]}', '2023-10-15 14:54:10', '2024-10-30 08:56:50'),
(77, 'light_orange', 'header', '[\"home\",\"about\",\"faq\",\"blogs\",\"Services\",\"contact\"]', '2023-10-15 14:54:10', '2024-10-30 20:20:07'),
(78, 'light_orange', 'footer', '{\"useful_link\":[\"home\",\"about\",\"blogs\",\"faq\",\"contact\"],\"support_link\":[\"api docs\",\"privacy &amp; policy\",\"terms and conditions\",\"refund policy\",\"cookie policy\"]}', '2023-10-15 14:54:10', '2024-10-30 10:34:25'),
(79, 'dark_mode', 'header', '[\"home\",\"about\",\"blogs\",\"Services\",\"contact\"]', '2023-10-15 14:54:10', '2024-10-30 09:13:07'),
(80, 'dark_mode', 'footer', '{\"useful_link\":[\"home\",\"about\",\"blogs\",\"faq\",\"contact\"],\"support_link\":[\"api docs\",\"privacy &amp; policy\",\"terms and conditions\",\"refund policy\",\"cookie policy\"]}', '2023-10-15 14:54:10', '2024-10-30 10:02:01');

-- --------------------------------------------------------

--
-- Table structure for table `manual_sms_configs`
--

CREATE TABLE `manual_sms_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action_method` varchar(255) DEFAULT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `header_data` text DEFAULT NULL,
  `param_data` text DEFAULT NULL,
  `form_data` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `manual_sms_configs`
--

INSERT INTO `manual_sms_configs` (`id`, `action_method`, `action_url`, `header_data`, `param_data`, `form_data`, `created_at`, `updated_at`) VALUES
(1, 'POST', 'https://rest.nexmo.com/sms/json', '{\"Content-Type\":\"application\\/x-www-form-urlencoded\"}', NULL, '{\"from\":\"Rownak\",\"text\":\"[[message]]\",\"to\":\"[[receiver]]\",\"api_key\":\"930cc608\",\"api_secret\":\"2pijsaMOUw5YKOK5\"}', NULL, '2023-10-18 21:03:34');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_06_07_064911_create_admins_table', 2),
(6, '2014_10_12_100000_create_password_resets_table', 3),
(7, '2023_06_10_061241_create_basic_controls_table', 4),
(8, '2023_06_10_123329_create_file_storages_table', 4),
(9, '2023_06_15_102426_create_firebase_notifies_table', 5),
(10, '2023_06_17_085447_create_languages_table', 6),
(11, '2023_06_19_082042_create_sms_controls_table', 7),
(12, '2023_06_20_080624_create_support_tickets_table', 8),
(13, '2023_06_20_080731_create_support_ticket_messages_table', 8),
(14, '2023_06_20_080833_create_support_ticket_attachments_table', 8),
(15, '2023_06_20_212143_create_fire_base_tokens_table', 9),
(16, '2023_06_21_124322_create_in_app_notifications_table', 10),
(17, '2023_06_22_084256_create_gateways_table', 11),
(18, '2023_07_15_162549_create_kycs_table', 12),
(19, '2023_07_17_094844_create_manage_pages_table', 13),
(20, '2023_07_17_101515_create_manage_sections_table', 14),
(21, '2023_07_18_084411_create_pages_table', 15),
(22, '2023_07_22_130913_create_manage_menus_table', 16),
(23, '2023_07_26_193156_create_email_controls_table', 17),
(24, '2023_08_10_153005_create_google_sheet_apis_table', 18),
(25, '2023_08_20_140757_create_contents_table', 19),
(26, '2023_08_20_140808_create_content_details_table', 19),
(27, '2023_08_20_140815_create_content_media_table', 19),
(28, '2023_09_07_151706_create_user_logins_table', 20),
(29, '2023_09_09_105217_create_transactions_table', 21),
(30, '2023_09_09_105305_create_payout_logs_table', 21),
(31, '2023_09_09_105353_create_funds_table', 21),
(32, '2023_09_19_131540_create_deposits_table', 22),
(33, '2023_09_20_093121_create_payouts_table', 23),
(34, '2023_09_21_085103_create_wallets_table', 24),
(35, '2023_10_01_125109_create_pages_table', 25),
(36, '2023_10_02_162152_create_page_details_table', 26),
(37, '2023_10_04_102054_create_maintenance_modes_table', 27),
(38, '2023_10_05_124404_create_email_templates_table', 28),
(39, '2023_10_05_124445_create_notify_templates_table', 28),
(40, '2023_10_05_132313_create_email_sms_templates_table', 29),
(41, '2023_10_05_145420_create_push_notification_templates_table', 30),
(42, '2023_10_05_150447_create_in_app_notification_templates_table', 31),
(43, '2023_10_19_140559_create_manual_sms_configs_table', 32),
(44, '2023_10_19_161530_create_jobs_table', 33),
(45, '2023_12_10_085818_create_blog_categories_table', 34),
(46, '2023_12_10_094858_create_blogs_table', 35),
(47, '2023_12_10_094925_create_blog_details_table', 35),
(48, '2024_05_07_075258_create_authors_table', 36),
(53, '2024_05_23_125210_add_some_column_to_blogs_table', 37),
(54, '2024_05_25_080924_create_subscribes_table', 38),
(57, '2024_05_30_084443_delete_description_column_api_providers_table', 40),
(58, '2024_05_30_092705_create_social_media_table', 41),
(64, '2024_06_10_101859_create_user_service_rates_table', 45),
(65, '2024_05_30_135032_create_categories_table', 46),
(66, '2024_06_01_134019_create_services_table', 47),
(67, '2024_05_26_135926_create_api_providers_table', 48),
(71, '2024_06_23_102936_create_orders_table', 49),
(77, '2024_06_25_133108_create_draft_mass_orders_table', 50),
(78, '2024_07_06_075626_create_notices_table', 51),
(80, '2024_06_09_075700_create_currencies_table', 52),
(81, '2024_07_07_154509_create_notice_details_table', 53),
(82, '2024_07_11_095238_create_referrals_table', 53),
(83, '2024_07_14_094409_create_referral_bonuses_table', 53),
(89, '2024_08_03_141041_create_page_seos_table', 55),
(97, '2024_08_11_103107_create_cookies_table', 61),
(110, '2024_08_30_214515_create_blog_categories_table', 69),
(111, '2024_08_30_214538_create_blog_category_details_table', 69),
(112, '2024_08_31_001521_create_authors_table', 70),
(113, '2024_08_31_001537_create_author_details_table', 70),
(114, '2024_05_23_001521_create_authors_table', 71),
(115, '2024_05_23_001537_create_author_details_table', 72),
(116, '2024_05_23_214515_create_blog_categories_table', 73),
(117, '2024_05_23_214538_create_blog_category_details_table', 73),
(119, '2024_11_02_074522_create_theme_colors_table', 74),
(143, '2025_01_08_164835_add_google_id_to_users_table', 75),
(144, '2025_01_12_175659_add_two_column_to_users_table', 75),
(145, '2025_01_30_114548_add_price_percentage_increase_to_services_table', 76),
(146, '2025_01_30_135323_add_original_price_to_services_table', 76),
(147, '2025_06_01_133028_add_percentage_column_to_user_service_rates_table', 77),
(148, '2025_06_01_171445_add_two_fa_columns_to_admins_table', 77),
(149, '2025_06_03_152726_add_sort_by_to_social_media_table', 78),
(150, '2025_06_03_152800_add_sort_by_to_currencies_table', 78),
(151, '2025_08_19_113440_add_custom_comments_column_orders_table', 79),
(152, '2025_10_15_105749_add_column_to_orders_table', 80),
(153, '2025_10_20_175117_add_column_completed_at_to_orders_table', 80);

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `image_driver` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notice_details`
--

CREATE TABLE `notice_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `notice_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email_from` varchar(191) DEFAULT NULL,
  `template_key` varchar(255) DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `short_keys` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `sms` text DEFAULT NULL,
  `in_app` text DEFAULT NULL,
  `push` text DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL COMMENT 'mail = 0(inactive), mail = 1(active),\r\nsms = 0(inactive), sms = 1(active),\r\nin_app = 0(inactive), in_app = 1(active),\r\npush = 0(inactive), push = 1(active),\r\n ',
  `notify_for` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => user, 1 => admin, 2 => child panel',
  `lang_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `language_id`, `name`, `email_from`, `template_key`, `subject`, `short_keys`, `email`, `sms`, `in_app`, `push`, `status`, `notify_for`, `lang_code`, `created_at`, `updated_at`) VALUES
(1, 1, 'New User Added', 'support@achi.com', 'ADDED_USER', 'User has been joined.', '{\"username\":\"Username\"}', '[[username]] has been joined.', '[[username]] has been joined.', '[[username]] has been joined.', '[[username]] has been joined.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-10-03 01:20:18'),
(2, 1, 'Add Balance', 'support@achi.com', 'ADD_BALANCE', 'Your Account has been credited', '{\"transaction\":\"Transaction Number\",\"amount\":\"Request Amount By Admin\",\"main_balance\":\"Users Balance After this operation\"}', '[[amount]] credited in your account. \n\n\nYour Current Balance [[main_balance]]\n\nTransaction: #[[transaction]]', '[[amount]] credited in your account. \n\n\nYour Current Balance [[main_balance]]\n\nTransaction: #[[transaction]]', '[[amount]] credited in your account. \n\n\nYour Current Balance [[main_balance]]\n\nTransaction: #[[transaction]]', '[[amount]] credited in your account. \n\n\nYour Current Balance [[main_balance]]\n\nTransaction: #[[transaction]]', '{\"mail\":\"0\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-10-03 01:20:18'),
(3, 1, 'Balance deducted by Admin', 'support@achi.com', 'DEDUCTED_BALANCE', 'Your Account has been debited', '{\"transaction\":\"Transaction Number\",\"amount\":\"Request Amount By Admin\",\"main_balance\":\"Users Balance After this operation\"}', '[[amount]] [[currency]] debited in your account.\r\n\r\nYour Current Balance [[main_balance]][[currency]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] [[currency]] debited in your account.\r\n\r\nYour Current Balance [[main_balance]][[currency]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] [[currency]] debited in your account.\r\n\r\nYour Current Balance [[main_balance]][[currency]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] [[currency]] debited in your account.\r\n\r\nYour Current Balance [[main_balance]][[currency]]\r\n\r\nTransaction: #[[transaction]]', '{\"mail\":\"0\",\"sms\":\"0\",\"in_app\":\"0\",\"push\":\"0\"}', 1, 'en', '2021-08-02 06:05:43', '2024-09-21 03:29:45'),
(4, 1, 'Support Ticket Create', 'support@achi.com', 'SUPPORT_TICKET_CREATE', 'Support Ticket Create', '{\"ticket_id\":\"Support Ticket ID\",\"username\":\"username\"}', '[[username]] create a ticket\r\nTicket : [[ticket_id]]', '[[username]] create a ticket\r\nTicket : [[ticket_id]]', '[[username]] create a ticket\r\nTicket : [[ticket_id]]', '[[username]] create a ticket\r\nTicket : [[ticket_id]]', '{\"mail\":\"0\",\"sms\":\"0\",\"in_app\":\"0\",\"push\":\"0\"}', 1, 'en', '2021-08-02 06:05:43', '2024-12-05 02:30:21'),
(6, 1, 'Support Ticket Replied', 'support@achi.com', 'SUPPORT_TICKET_REPLIED', 'Support Ticket Replied', '{\"ticket_id\":\"Support Ticket ID\",\"username\":\"username\"}', '[[username]] replied  ticket\r\nTicket : [[ticket_id]]', '[[username]] replied  ticket\r\nTicket : [[ticket_id]]', '[[username]] replied  ticket\r\nTicket : [[ticket_id]]', '[[username]] replied  ticket\r\nTicket : [[ticket_id]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2021-08-02 06:05:43', '2024-02-04 22:06:47'),
(7, 1, 'Admin Support Ticket Replied', 'support@achi.com', 'ADMIN_REPLIED_TICKET', 'Admin Support Ticket Replied', '{\"ticket_id\":\"Support Ticket ID\",\"ticket_subject\":\"Ticket Subject\",\"reply\":\"Reply Message\"}', 'Admin replied subject: [[ticket_subject]] message: [[reply]] Ticket : [[ticket_id]]', 'Admin replied subject: [[ticket_subject]] message: [[reply]]\r\nTicket : [[ticket_id]]', 'Admin replied subject: [[ticket_subject]] message: [[reply]]\nTicket : [[ticket_id]]', 'Admin replied subject: [[ticket_subject]] message: [[reply]]\nTicket: #[[ticket_id]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2021-08-02 06:05:43', '2024-02-04 22:06:47'),
(11, 1, 'KYC Approved', 'support@achi.com', 'KYC_APPROVED', 'Your KYC has been approved', '{\"username\":\"Username\"}', '[[username]] your kyc has been approved.', 'Your KYC has been approved.', 'Your KYC has been approved.', 'Your KYC has been approved.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(12, 1, 'KYC Rejected', 'support@achi.com', 'KYC_REJECTED', 'Your KYC has been rejected.', '{\"username\":\"Username\"}', '[[username]] your kyc has been rejected.', '[[username]] your kyc has been rejected.', '[[username]] your kyc has been rejected.', '[[username]] your kyc has been rejected.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(15, 1, 'Payment Request', 'support@achi.com', 'PAYMENT_REQUEST', 'Payment Request', '{\"gateway\":\"gateway\",\"currency\":\"currency\",\"username\":\"username\"}', '[[username]] deposit request [[amount]] via [[gateway]]\r\n', '[[username]] deposit request [[amount]] via [[gateway]]\r\n', '[[username]] deposit request [[amount]] via [[gateway]]\r\n', '[[username]] deposit request [[amount]] via [[gateway]]\r\n', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-02-04 22:06:47'),
(16, 1, 'Payment Approved', 'support@achi.com', 'PAYMENT_APPROVED', 'Payment Approved', '{\"amount\":\"amount\",\"feedback\":\"Admin feedback\",\"charge\":\"Payment Charge\",\"transaction\":\"Transaction Id\",\"gateway_name\":\"Gateway Name\"}', '[[username]] deposit request [[amount]] via [[gateway]] has been approved.', '[[username]] deposit request [[amount]] via [[gateway]] has been approved', '[[username]] deposit request [[amount]] via [[gateway]] has been approved', '[[username]] deposit request [[amount]] via [[gateway]] has been approved', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-03-06 22:18:55'),
(17, 1, 'Payment Rejected', 'support@achi.com', 'PAYMENT_REJECTED', 'Payment Rejected', '{\"amount\":\"amount\",\"feedback\":\"Admin feedback\",\"charge\",\"Payment Charge\",\"gateway_name\":\"Gateway Name\",\"transaction\":\"Transaction Id\"}', '[[username]] deposit request [[amount]] via [[gateway]] payment rejected', '[[username]] deposit request [[amount]] via [[gateway]] payment rejected', '[[username]] deposit request [[amount]] via [[gateway]] payment rejected', '[[username]] deposit request [[amount]] via [[gateway]] payment rejected', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(18, 1, 'Add Fund User', 'support@achi.com', 'ADD_FUND_USER_USER', 'Add Fund User', '{\"amount\":\"Request Amount\",\"transaction\":\"Transaction Number\"}', 'you add fund money amount [[amount]] . Transaction: #[[transaction]]', 'you add fund money amount [[amount]] [[currency]] . Transaction: #[[transaction]]', 'you add fund money amount [[amount]]. Transaction: #[[transaction]]', 'you add fund money amount [[amount]] . Transaction: #[[transaction]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(19, 1, 'Add Fund User Admin', 'support@achi.com', 'ADD_FUND_USER_ADMIN', 'Add Fund User Admin', '{\"username\":\"Username\", \"amount\":\"Request Amount\",\"currency\":\"Request Currency\",\"transaction\":\"Transaction Number\"}', '[[username]] add fund money amount [[amount]] . Transaction: #[[transaction]]', '[[username]] add fund money amount [[amount]]. Transaction: #[[transaction]]', '[[username]] add fund money amount [[amount]] . Transaction: #[[transaction]]', '[[username]] add fund money amount [[amount]] . Transaction: #[[transaction]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-02-04 22:06:47'),
(24, 1, 'Reset Password Notification', 'support@achi.com', 'PASSWORD_RESET', 'Reset Password Notification', '{\"message\":\"message\"}', 'You are receiving this email because we received a password reset request for your account.[[message]]\r\n\r\n\r\nThis password reset link will expire in 60 minutes.\r\n\r\nIf you did not request a password reset, no further action is required.', 'You are receiving this email because we received a password reset request for your account.[[message]]\r\n\r\n\r\nThis password reset link will expire in 60 minutes.\r\n\r\nIf you did not request a password reset, no further action is required.', 'You are receiving this email because we received a password reset request for your account.[[message]]\r\n\r\n\r\nThis password reset link will expire in 60 minutes.\r\n\r\nIf you did not request a password reset, no further action is required.', 'You are receiving this email because we received a password reset request for your account.[[message]]\r\n\r\n\r\nThis password reset link will expire in 60 minutes.\r\n\r\nIf you did not request a password reset, no further action is required.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(25, 1, 'Verification Code', 'support@achi.com', 'VERIFICATION_CODE', 'Verification Code', '{\"code\":\"code\"}', 'Your Email verification Code  [[code]]', 'Your SMS verification Code  [[code]]', 'Your Email verification Code  [[code]]', 'Your Email verification Code  [[code]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(26, 1, 'Two step enabled.', 'support@achi.com', 'TWO_STEP_ENABLED', 'Two step enabled.', '{\"action\":\"Enabled Or Disable\",\"ip\":\"Device Ip\",\"browser\":\"browser and Operating System \",\"time\":\"Time\",\"code\":\"code\"}', 'Your verification code is: {{code}}', 'Your verification code is: {{code}}', 'Your verification code is: {{code}}', 'Your verification code is: {{code}}', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(27, 1, 'Two Step disabled', 'support@achi.com', 'TWO_STEP_DISABLED', 'Two Step disabled', '{\"action\":\"Enabled Or Disable\",\"ip\":\"Device Ip\",\"browser\":\"browser and Operating System \",\"time\":\"Time\"}', 'Google two factor verification is disabled.', 'Google two factor verification is disabled.', 'Google two factor verification is disabled.', 'Google two factor verification is disabled.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(32, 1, 'Order Create', 'support@achi.com', 'ORDER_CREATE', 'Order created successfully.', '{\"price\":\"Order Price\", \"order_id\":\"Order id\",\"currency\":\"currency\",\"username\":\"username\"}', 'Order placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'Order placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'Order placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'Order placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(33, 1, 'Order Confirm', 'support@achi.com', 'CHILD_PANEL_USER_ORDER_CONFIRM', 'Your Order Has Been Confirmed', '{\"order_id\":\"order ID\",\"order_at\":\"order At\",\"service\":\"Service\", \"status\":\"status\",\"paid_amount\":\"paid amount\",\"transaction\":\"transaction ID\",\"remaining_balance\":\"Remaining Balance\",\"currency\":\"currency\"}', 'Your Order has been confirmed\n\n\nOrder Id [[order_id]] \n\nOrder At [[order_at]] \n\nService [[service]]\n\nStatus [[status]]\n\nPaid Amount [[paid_amount]] [[currency]]\n\nYour Current Balance [[remaining_balance]] [[currency]]\n\nTransaction: #[[transaction]]', 'Your Order has been confirmed\r\n\r\nOrder Id [[order_id]] \r\n\r\nOrder At [[order_at]] \r\n\r\nService [[service]]\r\n\r\nStatus [[status]]\r\n\r\nPaid Amount [[paid_amount]] [[currency]]\r\n\r\nYour Current Balance [[remaining_balance]] [[currency]]\r\n\r\nTransaction: #[[transaction]]', 'Order #[[order_id]] confirmed – Paid: [[paid_amount]] [[currency]], Status: [[status]].', 'Order #[[order_id]] confirmed – Paid: [[paid_amount]] [[currency]], Status: [[status]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(35, 1, 'Child Panel Place Order', 'support@achi.com', 'CHILD_PANEL_PLACE_ORDER', 'Child Panel Place Order.', '{\"order_id\":\"order ID\",\"order_at\":\"order At\",\"username\":\"username\"}', 'Child panel order #[[order_id]] has been placed at [[order_at]] by @[[username]].', 'Child panel order #[[order_id]] has been placed at [[order_at]] by @[[username]].', 'Child panel order #[[order_id]] has been placed at [[order_at]] by @[[username]].', 'Child panel order #[[order_id]] has been placed at [[order_at]] by @[[username]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(36, 1, 'Child Panel Order Confirm', 'support@achi.com', 'CHILD_PANEL_ORDER_CONFIRM', 'Child Panel Order Confirm.', '{\"order_id\":\"order ID\",\"order_at\":\"order At\",\"paid_amount\":\"paid amount\",\"transaction\":\"transaction ID\",\"remaining_balance\":\"Remaining Balance\",\"currency\":\"currency\"}', 'Order [[order_id]] placed on [[order_at]] has been confirmed. The paid amount is [[paid_amount]] [[currency]] with transaction ID [[transaction]]. Your remaining balance is [[remaining_balance]] [[currency]].', 'Order [[order_id]] placed on [[order_at]] has been confirmed. The paid amount is [[paid_amount]] [[currency]] with transaction ID [[transaction]]. Your remaining balance is [[remaining_balance]] [[currency]].', 'Order [[order_id]] placed on [[order_at]] has been confirmed. The paid amount is [[paid_amount]] [[currency]] with transaction ID [[transaction]]. Your remaining balance is [[remaining_balance]] [[currency]].', 'Order [[order_id]] placed on [[order_at]] has been confirmed. The paid amount is [[paid_amount]] [[currency]] with transaction ID [[transaction]]. Your remaining balance is [[remaining_balance]] [[currency]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(37, 1, 'Reject child panel order.', 'support@achi.com', 'REJECT_CHILD_PANEL_ORDER', 'Reject child panel order.', '{\"order_id\":\"order ID\",\"paid_amount\":\"paid amount\",\"transaction\":\"transaction ID\",\"currency\":\"currency\"}', 'Child panel order id #[[order_id]] has been rejected. The refunded amount is [[paid_amount]] [[currency]] with transaction ID [[transaction]].', 'Child panel order id #[[order_id]] has been rejected. The refunded amount is [[paid_amount]] [[currency]] with transaction ID [[transaction]].', 'Child panel order id #[[order_id]] has been rejected. The refunded amount is [[paid_amount]] [[currency]] with transaction ID [[transaction]].', 'Child panel order id #[[order_id]] has been rejected. The refunded amount is [[paid_amount]] [[currency]] with transaction ID [[transaction]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(38, 1, 'Approved child panel order.', 'support@achi.com', 'APPROVED_CHILD_PANEL_ORDER', 'Approved child panel order.', '{\"order_id\":\"order ID\", \"domain\":\"domain name\"}', 'The admin has approved your child panel order. The order ID is #[[order_id]], and the domain name is [[domain]].', 'The admin has approved your child panel order. The order ID is #[[order_id]], and the domain name is [[domain]].', 'The admin has approved your child panel order. The order ID is #[[order_id]], and the domain name is [[domain]].', 'The admin has approved your child panel order. The order ID is #[[order_id]], and the domain name is [[domain]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(39, 1, 'Approved renew request child panel.', 'support@achi.com', 'APPROVED_RENEW_REQUEST_CHILD_PANEL', 'Approved renew request child panel.', '{\"domain\":\"domain name\"}', 'The admin has approved your child panel renew request. The domain name is [[domain]].', 'The admin has approved your child panel renew request. The domain name is [[domain]].', 'The admin has approved your child panel renew request. The domain name is [[domain]].', 'The admin has approved your child panel renew request. The domain name is [[domain]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(40, 1, 'Rejected renew request child panel.', 'support@achi.com', 'REJECTED_RENEW_REQUEST_CHILD_PANEL', 'Rejected renew request child panel.', '{\"domain\":\"domain name\"}', 'The admin has rejected your child panel renew request. The domain name is [[domain]].', 'The admin has rejected your child panel renew request. The domain name is [[domain]].', 'The admin has rejected your child panel renew request. The domain name is [[domain]].', 'The admin has rejected your child panel renew request. The domain name is [[domain]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(41, 1, 'Add Balance', 'support@achi.com', 'CHILD_PANEL_USER_ADD_BALANCE', 'Your account has been credited.', '{\"transaction\":\"Transaction Number\",\"amount\":\"Request Amount By Admin\",\"main_balance\":\"Users Balance After this operation\"}', '[[amount]] credited to your account. \r\n\r\nYour Current Balance [[main_balance]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] credited to your account. \r\n\r\nYour Current Balance [[main_balance]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] credited to your account. \r\n\r\nYour Current Balance [[main_balance]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] credited to your account. \n\nYour Current Balance [[main_balance]]\n\nTransaction: #[[transaction]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-10-03 01:20:18'),
(42, 1, 'Balance deducted by Admin', 'support@achi.com', 'CHILD_PANEL_DEDUCTED_BALANCE', 'Your Account has been debited', '{\"transaction\":\"Transaction Number\",\"amount\":\"Request Amount By Admin\",\"main_balance\":\"Users Balance After this operation\"}', '[[amount]] [[currency]] debited in your account.\r\n\r\nYour Current Balance [[main_balance]][[currency]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] [[currency]] debited in your account.\r\n\r\nYour Current Balance [[main_balance]][[currency]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] [[currency]] debited in your account.\r\n\r\nYour Current Balance [[main_balance]][[currency]]\r\n\r\nTransaction: #[[transaction]]', '[[amount]] [[currency]] debited in your account.\r\n\r\nYour Current Balance [[main_balance]][[currency]]\r\n\r\nTransaction: #[[transaction]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2021-08-02 06:05:43', '2024-09-21 03:29:45'),
(43, 1, 'The admin support ticket has been replied', 'support@achi.com', 'CHILD_PANEL_ADMIN_REPLIED_TICKET', 'The admin support ticket has been replied', '{\"ticket_id\":\"Support Ticket ID\",\"ticket_subject\":\"Ticket Subject\",\"reply\":\"Reply Message\"}', 'Admin replied to the ticket. Subject: [[ticket_subject]]. Message: [[reply]]. Ticket ID: [[ticket_id]].', 'Admin replied to the ticket. Subject: [[ticket_subject]]. Message: [[reply]]. Ticket ID: [[ticket_id]].', 'Admin replied to the ticket. Ticket ID: [[ticket_id]].', 'Admin replied to the ticket. Ticket ID: [[ticket_id]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2021-08-02 06:05:43', '2024-02-04 22:06:47'),
(45, 1, 'Child panel user create order', 'support@achi.com', 'CHILD_PANEL_USER_ORDER_CREATE', 'A child panel user order has been created', '{\"price\":\"Order Price\", \"order_id\":\"Order id\",\"currency\":\"currency\",\"username\":\"username\"}', 'The child panel user order was placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'The child panel user order was placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'The child panel user order was placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'The child panel user order was placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(47, 1, 'Order refill request', 'support@achi.com', 'ORDER_REFILL_REQUEST', 'Order refill request has been submitted.', '{\"order_id\":\"Order id\"}', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(52, 1, 'Resend fail order', 'support@achi.com', 'RESEND_FAIL_ORDER', 'Resend fail order', '{\"order_id\":\"ORDER ID\",\"child_panel\":\"Child Panel\"}', '[[child_panel]]: child panel resend order due to failure. Order ID: [[order_id]].', '[[child_panel]]: child panel resend order due to failure. Order ID: [[order_id]].', '[[child_panel]]: child panel resend order due to failure. Order ID: [[order_id]].', '[[child_panel]]: child panel resend order due to failure. Order ID: [[order_id]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2021-08-02 06:05:43', '2024-02-04 22:06:47'),
(53, 1, 'Refund for order', 'support@achi.com', 'REFUND_FOR_ORDER', 'Refund the user balance for the order.', '{\"transaction\":\"Transaction Number\",\"amount\":\"Request Amount By Admin\",\"main_balance\":\"Users Balance After this operation\", \"order_id\":\"Order Id\"}', '[[amount]] was debited from your account. \n\nYour Current Balance [[main_balance]]\n\nTransaction: #[[transaction]]\n\nRefunded for order.Order ID:[[order_id]]', '[[amount]] was debited from your account. \r\n\r\nYour Current Balance [[main_balance]]\r\n\r\nTransaction: #[[transaction]]\r\n\r\nRefunded for order.Order ID:[[order_id]]', '[[amount]] was debited from your account. Transaction: #[[transaction]].\r\nRefund for order [[order_id]].', '[[amount]] was debited from your account. Transaction: #[[transaction]].\r\nRefund for order [[order_id]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(54, 1, 'Child Panel Order Status Changed', 'support@achi.com', 'CHILD_PANEL_ORDER_STATUS_CHANGED', 'Order status changed', '{\"child_panel\":\"Child Panel\",\"order_id\":\"Order Id\",\"order_status\":\"Order Status\"}', 'Order status changed. Order ID: [[order_id]], status changed to [[order_status]].', 'Order status changed. Order ID: [[order_id]], status changed to [[order_status]].', 'Order status changed. Order ID: [[order_id]], status changed to [[order_status]].', 'Order status changed. Order ID: [[order_id]], status changed to [[order_status]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(55, 1, 'Automatically renew the child panel.', 'support@achi.com', 'AUTOMATICALLY_RENEW_CHILD_PANEL', 'Automatically renew the child panel.', '{\"domain\":\"domain name\", \"username:username\"}', '@[[username]] Automatically renew the child panel. The domain name is [[domain]].', '@[[username]] Automatically renew the child panel. The domain name is [[domain]].', '@[[username]] Automatically renew the child panel. The domain name is [[domain]].', '@[[username]] Automatically renew the child panel. The domain name is [[domain]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(56, 1, 'Fail automatically renew the child panel.', 'support@achi.com', 'FAIL_AUTOMATICALLY_RENEW_CHILD_PANEL', 'Fail automatically renew the child panel.', '{\"domain\":\"domain name\", \"username:username\"}', '@[[username]] fails to renew the child panel automatically.', '@[[username]] fails to renew the child panel automatically.', '@[[username]] fails to renew the child panel automatically.', '@[[username]] fails to renew the child panel automatically.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 0, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(57, 1, 'User order has been created', 'support@achi.com', 'CHILD_PANEL_USER_ORDER_CREATE', 'A user order has been created', '{\"price\":\"Order Price\", \"order_id\":\"Order id\",\"currency\":\"currency\",\"username\":\"username\"}', 'Order placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'Order placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'Order placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', 'Order placed by [[username]].Order id is #[[order_id]] and order amount is [[price]].', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 2, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(58, 1, 'Add Fund User Admin', 'support@achi.com', 'ADD_FUND_USER_CHILD_PANEL_ADMIN', 'Add Fund User Admin', '{\"username\":\"username\", \"amount\":\"Request Amount\",\"currency\":\"Request Currency\",\"transaction\":\"Transaction Number\"}', '[[username]] add fund money amount [[amount]] . Transaction: #[[transaction]]', '[[username]] add fund money amount [[amount]]. Transaction: #[[transaction]]', '[[username]] add fund money amount [[amount]] . Transaction: #[[transaction]]', '[[username]] add fund money amount [[amount]] . Transaction: #[[transaction]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 2, 'en', '2023-10-07 16:18:47', '2024-02-04 22:06:47'),
(59, 1, 'Order refill request', 'support@achi.com', 'ORDER_REFILL_REQUEST_CHILD_PANEL', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', '{\"order_id\":\"Order id\"}', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', 'Order refill request has been submitted.\r\nOrder ID:[[order_id]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 2, 'en', '2023-10-07 16:18:47', '2024-09-21 03:29:45'),
(60, 1, 'Support Ticket Create', 'support@achi.com', 'CHILD_PANEL_USER_SUPPORT_TICKET_CREATE', 'Support Ticket Create', '{\"ticket_id\":\"Support Ticket ID\",\"username\":\"username\"}', '[[username]] create a ticket.\r\nTicket: [[ticket_id]]', '[[username]] create a ticket.\r\nTicket: [[ticket_id]]', '[[username]] create a ticket.\r\nTicket: [[ticket_id]]', '[[username]] create a ticket.\r\nTicket: [[ticket_id]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 2, 'en', '2021-08-02 06:05:43', '2024-09-21 03:29:45'),
(62, 1, 'New User Added', 'support@achi.com', 'ADDED_USER_CHILD_PANEL', 'User has been joined.', 'New User Added', '[[username]] has been joined.', '[[username]] has been joined.', '[[username]] has been joined.', '[[username]] has been joined.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 2, 'en', '2023-10-07 16:18:47', '2024-10-03 01:20:18'),
(63, 1, 'Reset Password Notification', 'support@achi.com', 'PASSWORD_RESET_CHILD_PANEL_ADMIN', 'Reset Password Notification', '{\"message\":\"message\"}', 'You are receiving this email because we received a password reset request for your account.[[message]]\r\n\r\n\r\nThis password reset link will expire in 60 minutes.\r\n\r\nIf you did not request a password reset, no further action is required.', 'You are receiving this email because we received a password reset request for your account.[[message]]\r\n\r\n\r\nThis password reset link will expire in 60 minutes.\r\n\r\nIf you did not request a password reset, no further action is required.', 'You are receiving this email because we received a password reset request for your account.[[message]]\r\n\r\n\r\nThis password reset link will expire in 60 minutes.\r\n\r\nIf you did not request a password reset, no further action is required.', 'You are receiving this email because we received a password reset request for your account.[[message]]\r\n\r\n\r\nThis password reset link will expire in 60 minutes.\r\n\r\nIf you did not request a password reset, no further action is required.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 2, 'en', '2023-10-07 16:18:47', '2024-12-05 02:27:19'),
(64, 1, 'Support Ticket Replied', 'support@achi.com', 'CHILD_PANEL_USER_TICKET_REPLIED', 'Support Ticket Replied', '{\"ticket_id\":\"Support Ticket ID\",\"username\":\"username\"}', '[[username]] replied  ticket\r\nTicket : [[ticket_id]]', '[[username]] replied  ticket\r\nTicket : [[ticket_id]]', '[[username]] replied  ticket\r\nTicket : [[ticket_id]]', '[[username]] replied  ticket\r\nTicket : [[ticket_id]]', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 2, 'en', '2021-08-02 06:05:43', '2024-02-04 22:06:47'),
(66, 1, 'Suspended Child Panel', 'support@achi.com', 'SUSPENDED CHILD PANEL', 'Child Suspended Panel', '{\"username\":\"Username\", \"domain\":\"Domain\"}', 'Your child panel on the domain [[domain]] has been suspended.', 'Your child panel on the domain [[domain]] has been suspended.', 'Your child panel on the domain [[domain]] has been suspended.', 'Your child panel on the domain [[domain]] has been suspended.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-10-03 01:20:18'),
(67, 1, 'Two step enabled.', 'support@achi.com', 'TWO_STEP_ENABLED', 'Two step enabled.', '{\"action\":\"Enabled Or Disable\",\"ip\":\"Device Ip\",\"browser\":\"browser and Operating System \",\"time\":\"Time\",\"code\":\"code\"}', 'Your verification code is: {{code}}', 'Your verification code is: {{code}}', 'Your verification code is: {{code}}', 'Your verification code is: {{code}}', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45'),
(68, 1, 'Two Step disabled', 'support@achi.com', 'TWO_STEP_DISABLED', 'Two Step disabled', '{\"action\":\"Enabled Or Disable\",\"ip\":\"Device Ip\",\"browser\":\"browser and Operating System \",\"time\":\"Time\"}', 'Google two factor verification is disabled.', 'Google two factor verification is disabled.', 'Google two factor verification is disabled.', 'Google two factor verification is disabled.', '{\"mail\":\"1\",\"sms\":\"1\",\"in_app\":\"1\",\"push\":\"1\"}', 1, 'en', '2023-10-07 16:18:47', '2024-12-18 01:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `child_panel_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `api_order_id` int(11) DEFAULT NULL,
  `api_refill_id` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT NULL,
  `price` double(18,8) DEFAULT NULL,
  `custom_comments` longtext DEFAULT NULL,
  `order_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `refill_status` varchar(255) DEFAULT NULL,
  `status_description` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `agree` tinyint(4) DEFAULT NULL,
  `start_counter` bigint(20) DEFAULT NULL,
  `remains` bigint(20) DEFAULT NULL,
  `runs` tinyint(4) DEFAULT NULL,
  `interval` tinyint(4) DEFAULT NULL,
  `drip_feed` tinyint(4) DEFAULT NULL,
  `refilled_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_type_info` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `template_name` varchar(191) DEFAULT NULL,
  `custom_link` varchar(255) DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `meta_robots` varchar(255) DEFAULT NULL,
  `meta_image` varchar(255) DEFAULT NULL,
  `meta_image_driver` varchar(50) DEFAULT NULL,
  `breadcrumb_image` varchar(255) DEFAULT NULL,
  `breadcrumb_image_driver` varchar(50) DEFAULT NULL,
  `breadcrumb_status` tinyint(1) DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `status` tinyint(1) DEFAULT 1 COMMENT '0 => unpublish, 1 => publish',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => admin create, 1 => developer create, 2 => create for menu, 3 => custom links',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `template_name`, `custom_link`, `page_title`, `meta_title`, `meta_keywords`, `meta_description`, `og_description`, `meta_robots`, `meta_image`, `meta_image_driver`, `breadcrumb_image`, `breadcrumb_image_driver`, `breadcrumb_status`, `status`, `type`, `created_at`, `updated_at`) VALUES
(1, 'home', '/', 'light_green', NULL, 'Home', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Social Media Marketing\",\"SMM Services\",\"Buy Followers\",\"Increase Engagement\",\"Social Media Growth\",\"Instagram Followers\",\"Boost Social Media\",\"Affordable SMM\",\"Social Media Advertising\"]', 'Boost your social media presence with our powerful SMM Panel. Buy real, high-quality social media services like Instagram followers, Facebook likes, and more. Affordable and efficient marketing solutions for individuals, brands, and resellers.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,follow', NULL, NULL, NULL, 'local', 0, 1, 0, '2024-05-04 21:58:49', '2024-11-07 01:55:24'),
(2, 'about', 'about', 'light_green', NULL, 'About Us', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/LKt5m14pnbg45ZiW5EOWVGRCc5r4hm.avif', 'local', 1, 1, 0, '2024-05-05 00:41:22', '2024-08-29 23:31:54'),
(3, 'faq', 'faq', 'light_green', NULL, 'Faq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/UHDS2jMazpG1rGoPaUj5JKLKoht7Sp.avif', 'local', 1, 1, 0, '2024-05-05 00:43:22', '2024-05-06 02:46:02'),
(4, 'blogs', 'blogs', 'light_green', NULL, 'Blogs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/Q86SNAGPMy0hCHC7Qp71FHJRXaRk9D.avif', 'local', 1, 1, 0, '2024-05-05 00:43:55', '2024-10-29 20:40:38'),
(5, 'contact', 'contact', 'light_green', NULL, 'Contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/9KlUw4PhTeffsoPJit5IMWvyw9hH7Q.avif', 'local', 1, 1, 0, '2024-05-05 00:44:24', '2024-05-06 02:46:26'),
(6, 'login', 'login', 'light_green', NULL, 'Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/1zb4JGjqFu99jvPlYYV0AT7osbchDx.avif', 'local', 1, 1, 2, '2024-05-24 21:09:52', '2024-10-02 02:36:52'),
(7, 'register', 'register', 'light_green', NULL, 'Register', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/WjBbtlS0Qequn1JP6J0yjKXfGb9Vkk.avif', 'local', 1, 1, 2, '2024-05-24 21:09:52', '2024-10-02 02:36:52'),
(8, 'service', 'services', 'light_green', NULL, 'Services', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Instagram Followers\",\"Facebook Likes\",\"Twitter Followers\",\"YouTube Views\",\"Social Media Boost\"]', 'Discover a wide range of SMM services to boost your social media presence. From Instagram followers to Facebook likes, we offer affordable, high-quality solutions to enhance your online profile, drive engagement, and grow your brand.', 'Explore our premium SMM services designed to accelerate your social media growth. Whether you need Instagram followers, Facebook likes, or YouTube views, our services provide the boost your social profiles need. Fast, reliable, and cost-effective solutions.', 'index,follow', NULL, NULL, 'pagesImage/z6fQyUFhp8DfDHIpPuGFIIWMX9HMCL.avif', 'local', 1, 1, 2, '2024-06-09 21:25:55', '2024-11-07 02:31:55'),
(9, 'privacy &amp; policy', 'privacy-policy', 'light_green', NULL, 'privacy &amp; policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/cdtS4ojCjxiNVxVR7UXVypoJj1TTXv.avif', 'local', 1, 1, 0, '2024-08-06 19:55:45', '2024-10-07 04:09:25'),
(10, 'terms and conditions', 'terms-and-conditions', 'light_green', NULL, 'Terms and Conditions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/unVXuKlbp9hFh4bz7WTNsK63rWxdOA.avif', 'local', 1, 1, 0, '2024-08-06 20:27:30', '2024-11-07 01:52:11'),
(11, 'Forgot Password', 'forgot-password', 'light_green', NULL, 'Forgot Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/ZnaDhWvbmfLtAeYFIKa1dEHilevvbb.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(12, 'Reset Password', 'reset-password', 'light_green', NULL, 'Reset Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/HgOThsYDoOlDT8C4m5CjfJv7p1WqUf.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(13, 'Email Verification', 'email-verification', 'light_green', NULL, 'Email Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/ILZlPXkpzw1ocqQBKOPxhjVVBRr6ba.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(14, 'SMS Verification', 'sms-verification', 'light_green', NULL, 'SMS Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/HJU3isCkWdPE6YOKgIlO0W6qJpcmDM.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(15, '2FA Verification', 'two-fa-verification', 'light_green', NULL, 'Two FA Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/oaamJEj10jubi442yn9fg8vXJMuY1x.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(17, 'home', '/', 'dark_voilet', NULL, 'Home', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Social Media Marketing\",\"SMM Services\",\"Buy Followers\",\"Boost Social Media\",\"Social Media Growth\"]', 'Boost your social media presence with our powerful SMM Panel. Buy real, high-quality social media services like Instagram followers, Facebook likes, and more. Affordable and efficient marketing solutions for individuals, brands, and resellers.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,follow', NULL, NULL, NULL, 'local', 0, 1, 0, '2024-09-30 21:53:09', '2024-11-07 02:00:52'),
(18, 'about', 'about', 'dark_voilet', NULL, 'About', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/O8Gp7kwE0BfWeaXf6VhsOqNhr5DOXo.avif', 'local', 1, 1, 0, '2024-09-30 22:08:21', '2024-10-01 04:28:32'),
(19, 'faq', 'faq', 'dark_voilet', NULL, 'Faq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/cbnxAar7TYPO5cXVlNRQztmTvMMNw1.avif', 'local', 1, 1, 0, '2024-10-01 04:26:17', '2024-10-07 03:20:21'),
(20, 'blogs', 'blogs', 'dark_voilet', NULL, 'Blogs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/efNbIQP94BE3e3JQ8o2taABmZAcBR2.avif', 'local', 1, 1, 0, '2024-10-01 04:26:44', '2024-10-29 22:44:23'),
(21, 'contact', 'contact', 'dark_voilet', NULL, 'Contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/LhYl8tkxiC8Pmefd3hCgSZWXcoxL84.avif', 'local', 1, 1, 0, '2024-10-01 04:27:33', '2024-10-01 20:23:26'),
(22, 'login', 'login', 'dark_voilet', NULL, 'Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/1zb4JGjqFu99jvPlYYV0AT7osbchDx.avif', 'local', 1, 1, 2, '2024-05-24 21:09:52', '2024-10-02 02:36:52'),
(23, 'register', 'register', 'dark_voilet', NULL, 'Register', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/WjBbtlS0Qequn1JP6J0yjKXfGb9Vkk.avif', 'local', 1, 1, 2, '2024-05-24 21:09:52', '2024-10-02 02:36:52'),
(24, 'service', 'services', 'dark_voilet', NULL, 'Services', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"TikTok Followers\",\"Social Media Growth\",\"Social Media Marketing\",\"Digital Marketing Services\",\"Social Media Services for Resellers\"]', 'Discover a wide range of SMM services to boost your social media presence. From Instagram followers to Facebook likes, we offer affordable, high-quality solutions to enhance your online profile, drive engagement, and grow your brand.', 'Discover a wide range of SMM services to boost your social media presence. From Instagram followers to Facebook likes, we offer affordable, high-quality solutions to enhance your online profile, drive engagement, and grow your brand.', 'index,follow', NULL, NULL, 'pagesImage/z6fQyUFhp8DfDHIpPuGFIIWMX9HMCL.avif', 'local', 1, 1, 2, '2024-06-09 21:25:55', '2024-11-07 02:30:31'),
(25, 'privacy &amp; policy', 'privacy-policy', 'dark_voilet', NULL, 'Privacy &amp; Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/yI8N0m3kyymhlr23qs9o2SKy7PyfGR.avif', 'local', 1, 1, 0, '2024-08-06 19:55:45', '2024-11-07 01:59:51'),
(26, 'terms and conditions', 'terms-and-conditions', 'dark_voilet', NULL, 'Terms and Conditions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/x5nUR4Ng1q7YhskFd61pbpUT3A8Cnd.avif', 'local', 1, 1, 0, '2024-08-06 20:27:30', '2024-11-07 01:59:35'),
(27, 'Forgot Password', 'forgot-password', 'dark_voilet', NULL, 'Forgot Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/ZnaDhWvbmfLtAeYFIKa1dEHilevvbb.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(28, 'Reset Password', 'reset-password', 'dark_voilet', NULL, 'Reset Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/HgOThsYDoOlDT8C4m5CjfJv7p1WqUf.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(29, 'Email Verification', 'email-verification', 'dark_voilet', NULL, 'Email Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/ILZlPXkpzw1ocqQBKOPxhjVVBRr6ba.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(30, 'SMS Verification', 'sms-verification', 'dark_voilet', NULL, 'SMS Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/HJU3isCkWdPE6YOKgIlO0W6qJpcmDM.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(31, '2FA Verification', 'two-fa-verification', 'dark_voilet', NULL, 'Two FA Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/oaamJEj10jubi442yn9fg8vXJMuY1x.avif', 'local', 1, 1, 2, '2024-08-29 23:23:29', '2024-10-02 02:36:52'),
(32, 'home', '/', 'minimal', NULL, 'Home', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Media Marketing\",\"SMM Services\",\"Increase Engagement\",\"Social Media Growth\",\"Instagram Followers\",\"Boost Social Media\"]', 'Boost your social media presence with our powerful SMM Panel. Buy real, high-quality social media services like Instagram followers, Facebook likes, and more. Affordable and efficient marketing solutions for individuals, brands, and resellers.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,follow', NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-26 19:43:11', '2024-11-07 02:03:20'),
(33, 'about', 'about', 'minimal', NULL, 'About', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/eFOXdbso4zULgtJhNDfyi3eYNf9bKW.avif', 'local', 1, 1, 0, '2024-10-26 20:36:47', '2024-10-30 01:44:48'),
(34, 'blogs', 'blogs', 'minimal', NULL, 'Blogs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/eTCVjFIwHpNUXsD0JRjHarqKHfTpyk.avif', 'local', 1, 1, 0, '2024-10-27 21:14:24', '2024-11-06 21:26:06'),
(35, 'faq', 'faq', 'minimal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/wBUqFu0VI82VcQ9bV9eRH2iHkb2M1f.avif', 'local', 0, 1, 0, '2024-10-27 21:35:04', '2024-11-06 21:26:18'),
(36, 'contact', 'contact', 'minimal', NULL, 'Contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/xGaUC2Wcqzx05qwCqgjzMzBTME7I7H.avif', 'local', 0, 1, 0, '2024-10-27 22:05:25', '2024-11-06 21:22:02'),
(37, 'Services', 'services', 'minimal', NULL, 'Services', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Social Media Boost\",\"Social Media Automation\",\"TikTok Followers\",\"Social Media Growth\",\"Social Media Marketing\"]', 'Discover a wide range of SMM services to boost your social media presence. From Instagram followers to Facebook likes, we offer affordable, high-quality solutions to enhance your online profile, drive engagement, and grow your brand.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,noindex', NULL, NULL, 'pagesImage/45q6wlUhZFP62X2bFqwFdVWWHIuNEo.avif', 'local', 1, 1, 1, '2024-10-27 22:15:09', '2024-11-07 02:28:43'),
(38, 'Login', 'login', 'minimal', NULL, 'Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/Wsbs003d4d8tVum7XoK73dUXNVQNIl.avif', 'local', 1, 1, 2, '2024-10-27 22:15:09', '2024-11-08 06:26:45'),
(39, 'Register', 'register', 'minimal', NULL, 'Register', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/Bx247OlunWjkEH8uPF3ZNJYaqUX4pF.avif', 'local', 1, 1, 2, '2024-10-27 22:15:09', '2024-11-08 06:26:55'),
(40, 'Forgot Password', 'forgot-password', 'minimal', NULL, 'Forgot Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/NAMQJb2q8JGGyMDIoWkKV0Pg0U5yoZ.avif', 'local', 1, 1, 2, '2024-10-27 22:15:09', '2024-11-08 06:26:22'),
(41, 'Reset Password', 'reset-password', 'minimal', NULL, 'Reset Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/qKRdjbisswV9CaEKWmlyIsb97idC8q.avif', 'local', 1, 1, 2, '2024-10-27 22:15:09', '2024-11-08 06:27:07'),
(42, 'Email Verification', 'email-verification', 'minimal', NULL, 'Email Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/bTP7dFJjB6LP3VuSTdK2xAYV4K8Hbw.avif', 'local', 1, 1, 2, '2024-10-27 22:15:09', '2024-11-08 06:27:22'),
(43, 'SMS Verification', 'sms-verification', 'minimal', NULL, 'SMS Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/Qt8VT1pWOJ2PSsQf7AbVls1KyIzIiw.avif', 'local', 1, 1, 2, '2024-10-27 22:15:09', '2024-11-08 06:27:34'),
(44, '2FA Verification', 'two-fa-verification', 'minimal', NULL, 'Two FA Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/gOcIfUUuPPjTyoWcq7uJvwh47XLTLf.avif', 'local', 1, 1, 2, '2024-10-27 22:15:09', '2024-11-08 06:27:45'),
(45, 'home', '/', 'deep_blue', NULL, 'Home', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Social Media Marketing\",\"Social Media Advertising\",\"Instagram Followers\",\"Increase Engagement\",\"Social Media Growth\"]', 'Boost your social media presence with our powerful SMM Panel. Buy real, high-quality social media services like Instagram followers, Facebook likes, and more. Affordable and efficient marketing solutions for individuals, brands, and resellers.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,follow', NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-28 02:21:39', '2024-11-07 02:04:51'),
(46, 'about', 'about', 'deep_blue', NULL, 'About Us', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/zhAhGAK7twziB8eET0xP2xp1DPpxBv.avif', 'local', 1, 1, 0, '2024-10-28 04:14:47', '2024-10-28 04:28:22'),
(47, 'blogs', 'blogs', 'deep_blue', NULL, 'Blogs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/QZeFG0M4WMS0LExa3u5urxx7HAVR9Z.avif', 'local', 1, 1, 0, '2024-10-28 04:17:30', '2024-10-28 04:28:36'),
(48, 'contact', 'contact', 'deep_blue', NULL, 'Contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/CZJPQx3BZ1DmwLQUGhOVVhj6wcXiS4.avif', 'local', 0, 1, 0, '2024-10-28 04:35:22', '2024-11-06 21:43:50'),
(49, 'faq', 'faq', 'deep_blue', NULL, 'Faq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '/j20zxnnJgATW91ovbqhYQm0dqwICM7.avif', 'local', 0, 1, 0, '2024-10-28 07:12:27', '2024-11-06 21:45:19'),
(50, 'Service', 'services', 'deep_blue', NULL, 'Services', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Instagram Followers\",\"Facebook Likes\",\"Twitter Followers\",\"YouTube Views\",\"TikTok Followers\"]', 'Boost your social media presence with our powerful SMM Panel. Buy real, high-quality social media services like Instagram followers, Facebook likes, and more. Affordable and efficient marketing solutions for individuals, brands, and resellers.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,follow', NULL, NULL, NULL, NULL, 0, 1, 1, '2024-10-28 21:45:41', '2024-11-07 02:27:23'),
(51, 'Login', 'login', 'deep_blue', NULL, 'Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-28 21:45:41', '2024-10-30 08:37:44'),
(52, 'Register', 'register', 'deep_blue', NULL, 'Register', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-28 21:45:41', '2024-10-30 08:47:52'),
(53, 'Forgot Password', 'forgot-password', 'deep_blue', NULL, 'Forgot Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-28 21:45:41', '2024-10-30 08:48:44'),
(54, 'Reset Password', 'reset-password', 'deep_blue', NULL, 'Reset Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-28 21:45:41', '2024-11-07 02:05:50'),
(55, 'Email Verification', 'email-verification', 'deep_blue', NULL, 'Email Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-28 21:45:41', '2024-11-07 02:06:05'),
(56, 'SMS Verification', 'sms-verification', 'deep_blue', NULL, 'SMS Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-28 21:45:41', '2024-11-07 02:06:16'),
(57, '2FA Verification', 'two-fa-verification', 'deep_blue', NULL, 'Two Fa Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-28 21:45:41', '2024-11-07 02:06:30'),
(58, 'home', '/', 'light_orange', NULL, 'Home', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Social Media Marketing\",\"SMM Services\",\"Increase Engagement\",\"Social Media Growth\",\"Boost Social Media\"]', 'Boost your social media presence with our powerful SMM Panel. Buy real, high-quality social media services like Instagram followers, Facebook likes, and more. Affordable and efficient marketing solutions for individuals, brands, and resellers.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,follow', NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-28 10:03:13', '2024-11-07 02:17:07'),
(59, 'about', 'about', 'light_orange', NULL, 'About', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/QRYMfhrinyUYNK7BpYQtHoWRKkVp7w.avif', 'local', 1, 1, 0, '2024-10-28 21:15:50', '2024-11-07 01:09:44'),
(60, 'blogs', 'blogs', 'light_orange', NULL, 'Blogs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/u1lUlpu3VnrsxFakAFGtZRi9XCFMKi.avif', 'local', 1, 1, 0, '2024-10-28 21:16:42', '2024-11-07 01:09:53'),
(61, 'faq', 'faq', 'light_orange', NULL, 'Faq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/2BpMkxznZrgc1xpSXg0isO0sTruMvT.avif', 'local', 1, 1, 0, '2024-10-28 21:18:23', '2024-10-30 10:36:43'),
(62, 'contact', 'contact', 'light_orange', NULL, 'Contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/vmmcrTskJL605x5tUU6AHe5lKk1gjE.avif', 'local', 1, 1, 0, '2024-10-28 21:30:49', '2024-10-30 10:36:54'),
(73, 'Service', 'services', 'light_orange', NULL, 'Services', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Instagram Followers\",\"Facebook Likes\",\"Twitter Followers\",\"Social Media Boost\",\"Social Media Marketing\"]', 'Discover a wide range of SMM services to boost your social media presence. From Instagram followers to Facebook likes, we offer affordable, high-quality solutions to enhance your online profile, drive engagement, and grow your brand.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,follow', NULL, NULL, 'pagesImage/OMCz6nRu2bR9bQmuAEJWE8JJao4tU2.avif', 'local', 1, 1, 1, '2024-10-28 21:50:21', '2024-11-07 02:22:28'),
(74, 'Login', 'login', 'light_orange', NULL, 'Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/26K0UcKxPgFgLWXS24K19gWk1P1unN.avif', 'local', 1, 1, 2, '2024-10-28 21:50:21', '2024-11-07 01:10:37'),
(75, 'Register', 'register', 'light_orange', NULL, 'Register', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/9hooViMlwKmSFj7YFgZ5oPdfk59iY5.avif', 'local', 1, 1, 2, '2024-10-28 21:50:21', '2024-11-07 01:10:48'),
(76, 'Forgot Password', 'forgot-password', 'light_orange', NULL, 'Forgot Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/bu3iJuHoPBAwXEUQCj1ynHj8jbB1I2.avif', 'local', 1, 1, 2, '2024-10-28 21:50:21', '2024-11-07 01:10:57'),
(77, 'Reset Password', 'reset-password', 'light_orange', NULL, 'Reset Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/uAqEVVbjcwwW5OZzdZmWnfFAxGWY4r.avif', 'local', 1, 1, 2, '2024-10-28 21:50:21', '2024-11-07 01:11:06'),
(78, 'Email Verification', 'email-verification', 'light_orange', NULL, 'Email Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/hbVvZzLkj6QANCXwLPtPBgHAtSWwJx.avif', 'local', 1, 1, 2, '2024-10-28 21:50:21', '2024-11-07 02:23:47'),
(79, 'SMS Verification', 'sms-verification', 'light_orange', NULL, 'SMS Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/OH4IARipWDQZYb2EECZ9fpdix8Df5D.avif', 'local', 1, 1, 2, '2024-10-28 21:50:21', '2024-10-30 10:37:35'),
(80, '2FA Verification', 'two-fa-verification', 'light_orange', NULL, 'Two FA Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/q5HPI65aiJStNwbFPpgMNlVkbqZqP7.avif', 'local', 1, 1, 2, '2024-10-28 21:50:21', '2024-10-30 10:36:22'),
(81, 'home', '/', 'dark_mode', NULL, 'Home', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"Instagram Followers\",\"Boost Social Media\",\"Affordable SMM\",\"Social Media Advertising\",\"Increase Engagement\"]', 'Boost your social media presence with our powerful SMM Panel. Buy real, high-quality social media services like Instagram followers, Facebook likes, and more. Affordable and efficient marketing solutions for individuals, brands, and resellers.', 'Elevate your online presence with our SMM Panel. Access top-quality services to grow your Instagram, Facebook, Twitter, and more. Fast, affordable, and reliable solutions for all your social media needs.', 'index,follow', NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-29 01:13:43', '2024-11-07 02:14:38'),
(82, 'about', 'about', 'dark_mode', NULL, 'About Us', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-29 02:37:41', '2024-10-29 02:40:50'),
(83, 'blogs', 'blogs', 'dark_mode', NULL, 'Blogs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-29 02:45:49', '2024-10-30 09:13:28'),
(84, 'contact', 'contact', 'dark_mode', NULL, 'Contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-29 02:50:04', '2024-10-29 03:06:02'),
(85, 'Service', 'services', 'dark_mode', NULL, 'Services', 'Affordable Social Media Marketing Services | Boost Your Online Presence', '[\"TikTok Followers\",\"Social Media Growth\",\"Social Media Marketing\",\"Facebook Likes\",\"Twitter Followers\"]', 'Discover a wide range of SMM services to boost your social media presence. From Instagram followers to Facebook likes, we offer affordable, high-quality solutions to enhance your online profile, drive engagement, and grow your brand.', 'Explore our premium SMM services designed to accelerate your social media growth. Whether you need Instagram followers, Facebook likes, or YouTube views, our services provide the boost your social profiles need. Fast, reliable, and cost-effective solutions.', 'index,follow', NULL, NULL, NULL, NULL, 0, 1, 1, '2024-10-29 03:06:57', '2024-11-07 02:26:15'),
(86, 'Login', 'login', 'dark_mode', NULL, 'Login', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-29 03:06:57', '2024-10-30 09:15:04'),
(87, 'Register', 'register', 'dark_mode', NULL, 'Register', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-29 03:06:57', '2024-10-30 09:14:53'),
(88, 'Forgot Password', 'forgot-password', 'dark_mode', NULL, 'Forgot Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-29 03:06:57', '2024-10-30 09:18:59'),
(89, 'Reset Password', 'reset-password', 'dark_mode', NULL, 'Reset Password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-29 03:06:57', '2024-10-30 09:19:15'),
(90, 'Email Verification', 'email-verification', 'dark_mode', NULL, 'Email Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-29 03:06:57', '2024-10-30 09:19:32'),
(91, 'SMS Verification', 'sms-verification', 'dark_mode', NULL, 'SMS Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-29 03:06:57', '2024-10-30 09:19:52'),
(92, '2FA Verification', 'two-fa-verification', 'dark_mode', NULL, 'Two Fa Verification', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 2, '2024-10-29 03:06:57', '2024-10-30 09:20:21'),
(93, 'refund policy', 'refund-policy', 'light_green', NULL, 'Refund Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/c18YBngsgfKlGW3EJ6xL11SBSg2ao9.avif', 'local', 1, 1, 0, '2024-10-29 20:49:16', '2024-10-30 10:06:06'),
(94, 'cookie policy', 'cookie-policy', 'light_green', NULL, 'Cookie Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/tOcG7KYBmxV7Qp16GaMWqZ55wA9es9.avif', 'local', 1, 1, 0, '2024-10-29 21:11:45', '2024-10-30 10:05:38'),
(96, 'api docs', 'api-docs', 'light_green', NULL, 'Api Docs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/gQM9aKbAswVudSo0JjjStpaHQm0rUb.avif', 'local', 1, 1, 0, '2024-10-29 21:31:33', '2024-10-30 10:04:59'),
(97, 'refund policy', 'refund-policy', 'dark_voilet', NULL, 'Refund Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/9MGnGRFKQIcQ0PltqFfIn4vzljQHKZ.avif', 'local', 1, 1, 0, '2024-10-30 00:30:55', '2024-11-07 09:56:39'),
(98, 'cookie policy', 'cookie-policy', 'dark_voilet', NULL, 'Cookie Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/bsplsXDwPaQgpWjJyJjBvh2f3Unf1x.avif', 'local', 1, 1, 0, '2024-10-30 00:31:32', '2024-11-07 09:56:03'),
(99, 'api docs', 'api-docs', 'dark_voilet', NULL, 'Api Docs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/SZ15E7Ov5BKJqqEffhNU5QDpswXvs3.avif', 'local', 1, 1, 0, '2024-10-30 00:33:59', '2024-11-07 09:55:37'),
(100, 'privacy policy', 'privacy-policy', 'minimal', NULL, 'Privacy Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/xXTTZOowsIBCSbaHW3hv73i7ZdmOQ6.webp', 'local', 1, 1, 0, '2024-10-30 03:22:37', '2024-10-30 03:36:14'),
(101, 'terms and  conditions', 'terms-and-conditions', 'minimal', NULL, 'Terms and Conditions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/osuZuff1fC3AplGDzGK8xZdeRjmzCH.webp', 'local', 1, 1, 0, '2024-10-30 03:23:48', '2024-10-30 03:35:57'),
(102, 'refund policy', 'refund-policy', 'minimal', NULL, 'Refund Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/yFaKdEKdyhcmPFX8XhoiTq4LNLw5u8.webp', 'local', 1, 1, 0, '2024-10-30 03:24:21', '2024-10-30 03:35:37'),
(103, 'cookie policy', 'cookie-policy', 'minimal', NULL, 'Cookie Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/rX5RwnoRkbjLgSA9gC5Gs8a5p9JHGS.webp', 'local', 1, 1, 0, '2024-10-30 03:25:04', '2024-10-30 03:34:56'),
(104, 'api docs', 'api-docs', 'minimal', NULL, 'Api Docs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/onen2nFgI5SCSxoXmhrDaineCTCgKo.webp', 'local', 1, 1, 0, '2024-10-30 03:27:22', '2024-10-30 03:34:43'),
(105, 'privacy policy', 'privacy-policy', 'deep_blue', NULL, 'Privacy Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 08:50:56', '2024-10-30 08:54:04'),
(106, 'terms conditions', 'terms-conditions', 'deep_blue', NULL, 'Terms Conditions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 08:51:39', '2024-10-30 08:54:18'),
(107, 'refund policy', 'refund-policy', 'deep_blue', NULL, 'Refund Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 08:52:13', '2024-10-30 08:54:32'),
(108, 'cookie policy', 'cookie-policy', 'deep_blue', NULL, 'Cookie Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 08:52:39', '2024-10-30 08:54:46'),
(109, 'api docs', 'api-docs', 'deep_blue', NULL, 'Api Docs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 08:56:40', '2024-10-30 08:57:12'),
(110, 'faq', 'faq', 'dark_mode', NULL, 'Faq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 09:48:46', '2024-10-30 09:52:08'),
(111, 'privacy policy', 'privacy-policy', 'dark_mode', NULL, 'Privacy Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 09:58:06', '2024-10-30 10:01:25'),
(112, 'terms and conditions', 'terms-and-conditions', 'dark_mode', NULL, 'Terms and Conditions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 09:58:31', '2024-10-30 10:06:42'),
(113, 'refund policy', 'refund-policy', 'dark_mode', NULL, 'Refund Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 09:58:55', '2024-10-30 10:01:04'),
(114, 'cookie policy', 'cookie-policy', 'dark_mode', NULL, 'Cookie Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 09:59:11', '2024-10-30 10:00:52'),
(115, 'api docs', 'api-docs', 'dark_mode', NULL, 'Api Docs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'local', 0, 1, 0, '2024-10-30 10:00:30', '2024-10-30 10:00:42'),
(116, 'privacy &amp; policy', 'privacy-policy', 'light_orange', NULL, 'Privacy &amp; Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/Wx1W3mvz444XVHiUchynJ3UA3mglp5.webp', 'local', 1, 1, 0, '2024-10-30 10:30:21', '2024-10-30 10:35:37'),
(117, 'terms and conditions', 'terms-and-conditions', 'light_orange', NULL, 'Terms and Conditions', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/UL2vUivmYaPJg5MUuOQOw56zo41HZe.webp', 'local', 1, 1, 0, '2024-10-30 10:30:47', '2024-10-30 10:35:26'),
(118, 'refund policy', 'refund-policy', 'light_orange', NULL, 'Refund Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/zey3nCMInelKZytgOGWlryCfRzMyPv.webp', 'local', 1, 1, 0, '2024-10-30 10:31:12', '2024-10-30 10:35:16'),
(119, 'cookie policy', 'cookie-policy', 'light_orange', NULL, 'Cookie Policy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/HTQsuq02bmolsw0EitlUe8Sl4YImdC.webp', 'local', 1, 1, 0, '2024-10-30 10:31:39', '2024-10-30 10:35:05'),
(120, 'api docs', 'api-docs', 'light_orange', NULL, 'Api Docs', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pagesImage/v8CTRBc1Wsq4cOxXOZxX9MIIZnPAlx.webp', 'local', 1, 1, 0, '2024-10-30 10:33:38', '2024-10-30 10:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `page_details`
--

CREATE TABLE `page_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `sections` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_details`
--

INSERT INTO `page_details` (`id`, `page_id`, `language_id`, `name`, `content`, `sections`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Home', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[hero]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[service]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[why_choose_us]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[achievement]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[how_it_works]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"hero\",\"feature\",\"about\",\"service\",\"why_choose_us\",\"achievement\",\"how_it_works\",\"testimonial\",\"payment_partner\",\"blog\",\"footer\"]', '2024-05-04 21:58:49', '2024-07-01 20:46:19'),
(2, 2, 1, 'About', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[faq]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"about\",\"feature\",\"faq\",\"testimonial\",\"blog\",\"footer\"]', '2024-05-05 00:41:22', '2024-11-07 02:33:18'),
(3, 3, 1, 'Faq', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[faq]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"faq\",\"footer\"]', '2024-05-05 00:43:22', '2024-05-05 00:43:22'),
(4, 4, 1, 'Blogs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"blog\",\"footer\"]', '2024-05-05 00:43:55', '2024-10-29 20:24:06'),
(5, 5, 1, 'Contact', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[contact]]</div>\n                    <span class=\"delete-block\">×</span>\n                    <span class=\"up-block\">↑</span>\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[map]]</div>\n                    <span class=\"delete-block\">×</span>\n                    <span class=\"up-block\">↑</span>\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\n                    <span class=\"delete-block\">×</span>\n                    <span class=\"up-block\">↑</span>\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"contact\",\"map\",\"footer\"]', '2024-05-05 00:44:24', '2024-05-05 00:44:24'),
(6, 6, 1, 'Login', NULL, NULL, '2024-05-24 21:12:00', '2024-05-24 21:12:00'),
(7, 7, 1, 'Register', NULL, NULL, '2024-05-24 21:12:18', '2024-05-24 21:12:18'),
(8, 8, 1, 'Services', NULL, NULL, '2024-06-09 21:29:32', '2024-10-29 20:23:48'),
(9, 9, 1, 'Privacy &amp; Policy', '<div><h3>Privacy Policy</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>How We Use Your Information</h5><p>We use your information to provide our services and maintain your account.</p><p>We use your information to understand how our services are used and make improvements.</p><p>We may use your contact information to send you important updates, newsletters, or respond to your inquiries.</p><p><br></p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Privacy Policy</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p><div class=\"policy-list mt-10\"><div class=\"item\"></div></div><h5><br></h5>', NULL, '2024-08-06 19:55:45', '2024-10-07 04:05:05'),
(10, 10, 1, 'Terms and Conditions', '<div><h3>Terms and Conditions</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>Modifications to the Terms</h5><p>We reserve the right to modify these terms at any time. It is your responsibility to review these terms regularly. Continued use of the service after any changes implies acceptance of the new terms.</p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Terms</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p>', NULL, '2024-08-06 20:27:30', '2024-10-07 04:19:30'),
(11, 11, 1, 'Forgot Password', NULL, NULL, '2024-08-29 23:35:22', '2024-08-29 23:35:22'),
(12, 12, 1, 'Reset Password', NULL, NULL, '2024-08-29 23:35:44', '2024-08-29 23:35:44'),
(13, 13, 1, 'Email Verification', NULL, NULL, '2024-08-29 23:36:01', '2024-08-29 23:36:01'),
(14, 14, 1, 'SMS Verification', NULL, NULL, '2024-08-29 23:36:15', '2024-08-29 23:36:15'),
(15, 15, 1, 'Two Fa Verification', NULL, NULL, '2024-08-29 23:37:01', '2024-09-30 19:35:19'),
(17, 17, 1, 'Home', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[hero]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[service]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[why_choose_us]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[achievement]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[how_it_works]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"hero\",\"feature\",\"about\",\"service\",\"why_choose_us\",\"achievement\",\"how_it_works\",\"testimonial\",\"payment_partner\",\"blog\",\"footer\"]', '2024-09-30 21:53:09', '2024-10-01 03:28:49'),
(18, 18, 1, 'About', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[faq]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"feature\",\"about\",\"faq\",\"testimonial\",\"footer\"]', '2024-09-30 22:08:21', '2024-11-06 20:53:46'),
(19, 19, 1, 'Faq', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[faq]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"faq\",\"footer\"]', '2024-10-01 04:26:17', '2024-10-01 04:26:17'),
(20, 20, 1, 'Blogs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"blog\",\"footer\"]', '2024-10-01 04:26:44', '2024-10-29 22:41:31'),
(21, 21, 1, 'Contact', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[contact]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"contact\",\"footer\"]', '2024-10-01 04:27:33', '2024-10-01 04:27:33'),
(22, 22, 1, 'Login', NULL, NULL, '2024-10-02 02:54:43', '2024-10-02 02:54:43'),
(23, 23, 1, 'Register', NULL, NULL, '2024-10-02 02:55:27', '2024-10-02 02:55:27'),
(24, 24, 1, 'Service', NULL, NULL, '2024-10-02 02:55:40', '2024-10-02 02:55:40'),
(25, 25, 1, 'Privacy &amp; Policy', '<div>\r\n                        <h3>Privacy Policy</h3></div>\r\n                    <p>\r\n                        At SMM, we value your privacy and are committed to protecting your personal\r\n                        information.\r\n                        This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide\r\n                        while using our classified ads\r\n                        platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p><span>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</span></p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>How We Use Your Information</h5><p>We use your information to provide our services and maintain your account.</p><p>We use your information to understand how our services are used and make improvements.</p><p>We may use your contact information to send you important updates, newsletters, or respond to your inquiries.</p><p><br></p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Privacy Policy</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p><div class=\"policy-list mt-10\">\r\n                        <div class=\"item\">\r\n                            </div></div><h5><br></h5>', NULL, '2024-10-02 02:58:08', '2024-10-07 04:04:07'),
(26, 30, 1, 'SMS Verification', NULL, NULL, '2024-10-02 03:02:58', '2024-10-02 03:02:58'),
(27, 31, 1, 'Two Fa Verification', NULL, NULL, '2024-10-02 03:03:10', '2024-10-02 03:03:10'),
(28, 29, 1, 'Email Verification', NULL, NULL, '2024-10-02 03:03:19', '2024-10-02 03:03:19'),
(29, 28, 1, 'Reset Password', NULL, NULL, '2024-10-02 03:03:27', '2024-10-02 03:03:27'),
(30, 27, 1, 'Forgot Password', NULL, NULL, '2024-10-02 03:03:34', '2024-10-02 03:03:34'),
(31, 26, 1, 'Terms And Conditions', '<div><h3>Terms and Conditions</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>Modifications to the Terms</h5><p>We reserve the right to modify these terms at any time. It is your responsibility to review these terms regularly. Continued use of the service after any changes implies acceptance of the new terms.</p><p><br></p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Terms</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p>', NULL, '2024-10-02 03:05:34', '2024-10-07 04:20:49'),
(32, 32, 1, 'Home', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[hero]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[how_it_works]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[service]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[counter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[call_to_action]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"hero\",\"feature\",\"about\",\"how_it_works\",\"service\",\"counter\",\"call_to_action\",\"testimonial\",\"blog\",\"payment_partner\",\"footer\"]', '2024-10-26 19:43:11', '2024-10-27 20:19:28'),
(33, 33, 1, 'About', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[counter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"feature\",\"about\",\"counter\",\"testimonial\",\"payment_partner\",\"footer\"]', '2024-10-26 20:36:47', '2024-10-27 21:08:20'),
(34, 34, 1, 'Blogs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"blog\",\"footer\"]', '2024-10-27 21:14:24', '2024-10-30 01:49:01'),
(35, 35, 1, 'FAQ', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[faq]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"faq\",\"footer\"]', '2024-10-27 21:35:04', '2024-10-27 21:57:52'),
(36, 36, 1, 'Contact', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[contact]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"contact\",\"footer\"]', '2024-10-27 22:05:25', '2024-10-27 22:05:25'),
(37, 37, 1, 'Services', NULL, NULL, '2024-10-27 23:55:43', '2024-10-27 23:55:43'),
(38, 45, 1, 'Home', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[hero]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[service]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[how_it_works]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[counter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[call_to_action]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"hero\",\"feature\",\"about\",\"service\",\"how_it_works\",\"counter\",\"call_to_action\",\"testimonial\",\"blog\",\"payment_partner\",\"footer\"]', '2024-10-28 02:21:39', '2024-10-28 04:06:39'),
(39, 46, 1, 'About', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[counter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"feature\",\"about\",\"counter\",\"testimonial\",\"payment_partner\",\"footer\"]', '2024-10-28 04:14:47', '2024-10-28 04:14:47'),
(40, 47, 1, 'Blogs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"blog\",\"footer\"]', '2024-10-28 04:17:30', '2024-10-28 04:17:30'),
(41, 48, 1, 'Contact', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[contact]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"contact\",\"footer\"]', '2024-10-28 04:35:22', '2024-10-28 04:35:22'),
(42, 49, 1, 'Faq', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[faq]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"faq\",\"footer\"]', '2024-10-28 07:12:27', '2024-10-28 08:25:45'),
(43, 58, 1, 'Home', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[hero]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[service]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[how_it_works]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[counter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[call_to_action]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[news_letter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"hero\",\"feature\",\"about\",\"service\",\"how_it_works\",\"counter\",\"call_to_action\",\"testimonial\",\"blog\",\"payment_partner\",\"news_letter\",\"footer\"]', '2024-10-28 10:03:13', '2024-10-28 21:07:06'),
(44, 59, 1, 'About', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[counter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"feature\",\"about\",\"counter\",\"testimonial\",\"payment_partner\",\"footer\"]', '2024-10-28 21:15:50', '2024-10-28 21:15:50'),
(45, 60, 1, 'Blogs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"blog\",\"footer\"]', '2024-10-28 21:16:42', '2024-10-30 10:08:35'),
(46, 61, 1, 'Faq', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[faq]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"faq\",\"footer\"]', '2024-10-28 21:18:23', '2024-10-28 21:18:23'),
(47, 62, 1, 'Contact', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[contact]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[news_letter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"contact\",\"news_letter\",\"footer\"]', '2024-10-28 21:30:49', '2024-10-28 21:30:49'),
(48, 73, 1, 'Services', NULL, NULL, '2024-10-28 22:26:21', '2024-10-30 10:08:46'),
(49, 74, 1, 'Login', NULL, NULL, '2024-10-28 22:42:50', '2024-10-28 22:42:50'),
(50, 75, 1, 'Register', NULL, NULL, '2024-10-28 22:47:45', '2024-10-28 22:47:45'),
(51, 76, 1, 'Forgot Password', NULL, NULL, '2024-10-28 22:49:10', '2024-10-28 22:49:10');
INSERT INTO `page_details` (`id`, `page_id`, `language_id`, `name`, `content`, `sections`, `created_at`, `updated_at`) VALUES
(52, 81, 1, 'Home', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[hero]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[service]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[how_it_works]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[counter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[call_to_action]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"hero\",\"feature\",\"about\",\"service\",\"how_it_works\",\"counter\",\"call_to_action\",\"testimonial\",\"blog\",\"payment_partner\",\"footer\"]', '2024-10-29 01:13:43', '2024-10-29 02:13:30'),
(53, 82, 1, 'About', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[feature]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[about]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[counter]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[testimonial]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[payment_partner]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"feature\",\"about\",\"counter\",\"testimonial\",\"payment_partner\",\"footer\"]', '2024-10-29 02:37:41', '2024-10-29 02:37:41'),
(54, 83, 1, 'Blogs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[blog]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"blog\",\"footer\"]', '2024-10-29 02:45:49', '2024-10-30 09:12:58'),
(55, 84, 1, 'Contact', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[contact]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"contact\",\"footer\"]', '2024-10-29 02:50:04', '2024-10-29 02:50:04'),
(57, 85, 1, 'Services', NULL, NULL, '2024-10-29 03:19:01', '2024-10-29 03:19:01'),
(58, 93, 1, 'Refund Policy', '<p><strong><span style=\"font-size:24px;\">Refund Policy</span></strong><br></p><p><strong>1. Introduction</strong></p><p>Thank you for shopping with us. We strive to ensure that you are completely satisfied with your purchase. This refund policy outlines our procedures for processing refunds and returns.</p><p><strong>2. Refund Eligibility</strong></p><p>To be eligible for a refund, you must meet the following criteria:</p><ul><li>The item must be returned within  +14582252545 days of receipt.</li><li>The item must be in its original condition, unused, and with all original packaging and tags.</li><li>Proof of purchase is required to process any refund.<br><br></li></ul><p><strong>3. Non-Refundable Items</strong></p><p>The following items are non-refundable:</p><ul><li>Downloadable software or digital content</li><li>Custom-made or personalized items</li><li>Clearance or sale items<br><br></li></ul><p><strong>4. Refund Process</strong></p><p>If you meet the eligibility criteria, please follow these steps to request a refund:</p><ol><li>Contact our customer service team atupport@nextDestina.com or +14582252545 to initiate the return process.</li><li>Provide your order number and details of the item(s) you wish to return.</li><li>Our team will provide you with return instructions and a return authorization number.</li><li>Return the item(s) to the address provided, using a trackable shipping method. You are responsible for return shipping costs.</li></ol><p>Once we receive and inspect the returned item(s), we will process your refund within +14582252545 business days. The refund will be issued to the original payment method used for the purchase.<br><br></p><p><strong>5. Exchanges</strong></p><p>If you wish to exchange an item for a different size, color, or product, please follow the return process and place a new order for the desired item.<br><br></p><p><strong>6. Damaged or Defective Items</strong></p><p>If you receive a damaged or defective item, please contact our customer service team immediately. We will arrange for a replacement or refund, including any return shipping costs if applicable.<br><br></p><p><strong>7. Changes to This Policy</strong></p><p>We reserve the right to modify or update this refund policy at any time. Any changes will be posted on our website.<br><br></p><p><strong>8. Contact Us</strong></p><p>If you have any questions or concerns about our refund policy, please contact us at support@nextDestina.com or +14582252545.<br><br></p>', NULL, '2024-10-29 20:49:16', '2024-10-29 21:08:45'),
(59, 94, 1, 'Cookie Policy', '<p></p><h3>Cookie Policy</h3><p>We use cookies on our website to enhance your experience, personalize content, and analyze our traffic. By using our site, you consent to our use of cookies in accordance with this policy.<br><br></p><p><strong>1. What Are Cookies?</strong>\r\nCookies are small text files that are stored on your device when you visit a website. They help websites remember your preferences and activities to provide a more personalized and seamless experience.<br><br></p><p><strong>2. Types of Cookies We Use</strong></p><ul><li><strong>Essential Cookies:</strong> These cookies are necessary for the website to function properly, allowing you to navigate and use essential features.</li><li><strong>Performance Cookies:</strong> These cookies collect information on how you interact with our website, helping us optimize performance and improve user experience.</li><li><strong>Functional Cookies:</strong> These cookies enable additional functionality, such as remembering your preferences or settings.</li><li><strong>Targeting/Advertising Cookies:</strong> These cookies track your browsing habits and are used to deliver relevant advertisements based on your interests.<br><br></li></ul><p><strong>3. How We Use Cookies</strong>\r\nWe use cookies to:</p><ul><li>Provide a secure and effective browsing experience</li><li>Analyze user behavior and website performance</li><li>Customize content and advertisements based on your preferences<br><br></li></ul><p><strong>4. Third-Party Cookies</strong>\r\nWe may also use third-party cookies from partners such as advertising networks and analytics providers to help deliver content, monitor performance, and display ads relevant to you.<br><br></p><p><strong>5. Managing Cookies</strong>\r\nYou can manage your cookie preferences through your browser settings. You can choose to block or delete cookies; however, this may affect the functionality of certain parts of our website.<br><br></p><p><strong>6. Updates to This Policy</strong>\r\nWe may update this cookie policy from time to time. Any changes will be reflected on this page, and your continued use of our website will signify your acceptance of the updated terms.</p>', NULL, '2024-10-29 21:11:45', '2024-10-29 21:14:42'),
(60, 95, 1, 'Api Docs', NULL, NULL, '2024-10-29 21:25:51', '2024-10-29 21:25:51'),
(61, 96, 1, 'Api Docs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[api_docs]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"api_docs\",\"footer\"]', '2024-10-29 21:31:33', '2024-10-30 10:03:52'),
(62, 97, 1, 'Refund Policy', '<p><strong><span style=\"font-size:24px;\">Refund Policy</span></strong><br></p><p><strong>1. Introduction</strong></p><p>Thank you for shopping with us. We strive to ensure that you are completely satisfied with your purchase. This refund policy outlines our procedures for processing refunds and returns.</p><p><strong>2. Refund Eligibility</strong></p><p>To be eligible for a refund, you must meet the following criteria:</p><ul><li>The item must be returned within  +14582252545 days of receipt.</li><li>The item must be in its original condition, unused, and with all original packaging and tags.</li><li>Proof of purchase is required to process any refund.<br><br></li></ul><p><strong>3. Non-Refundable Items</strong></p><p>The following items are non-refundable:</p><ul><li>Downloadable software or digital content</li><li>Custom-made or personalized items</li><li>Clearance or sale items<br><br></li></ul><p><strong>4. Refund Process</strong></p><p>If you meet the eligibility criteria, please follow these steps to request a refund:</p><ol><li>Contact our customer service team atupport@nextDestina.com or +14582252545 to initiate the return process.</li><li>Provide your order number and details of the item(s) you wish to return.</li><li>Our team will provide you with return instructions and a return authorization number.</li><li>Return the item(s) to the address provided, using a trackable shipping method. You are responsible for return shipping costs.</li></ol><p>Once we receive and inspect the returned item(s), we will process your refund within +14582252545 business days. The refund will be issued to the original payment method used for the purchase.<br><br></p><p><strong>5. Exchanges</strong></p><p>If you wish to exchange an item for a different size, color, or product, please follow the return process and place a new order for the desired item.<br><br></p><p><strong>6. Damaged or Defective Items</strong></p><p>If you receive a damaged or defective item, please contact our customer service team immediately. We will arrange for a replacement or refund, including any return shipping costs if applicable.<br><br></p><p><strong>7. Changes to This Policy</strong></p><p>We reserve the right to modify or update this refund policy at any time. Any changes will be posted on our website.<br><br></p><p><strong>8. Contact Us</strong></p><p>If you have any questions or concerns about our refund policy, please contact us at support@nextDestina.com or +14582252545.<br><br></p>', NULL, '2024-10-30 00:30:56', '2024-10-30 00:30:56'),
(63, 98, 1, 'Cookie Policy', '<p></p><h3>Cookie Policy</h3><p>We use cookies on our website to enhance your experience, personalize content, and analyze our traffic. By using our site, you consent to our use of cookies in accordance with this policy.<br><br></p><p><strong>1. What Are Cookies?</strong>\r\nCookies are small text files that are stored on your device when you visit a website. They help websites remember your preferences and activities to provide a more personalized and seamless experience.<br><br></p><p><strong>2. Types of Cookies We Use</strong></p><ul><li><strong>Essential Cookies:</strong> These cookies are necessary for the website to function properly, allowing you to navigate and use essential features.</li><li><strong>Performance Cookies:</strong> These cookies collect information on how you interact with our website, helping us optimize performance and improve user experience.</li><li><strong>Functional Cookies:</strong> These cookies enable additional functionality, such as remembering your preferences or settings.</li><li><strong>Targeting/Advertising Cookies:</strong> These cookies track your browsing habits and are used to deliver relevant advertisements based on your interests.<br><br></li></ul><p><strong>3. How We Use Cookies</strong>\r\nWe use cookies to:</p><ul><li>Provide a secure and effective browsing experience</li><li>Analyze user behavior and website performance</li><li>Customize content and advertisements based on your preferences<br><br></li></ul><p><strong>4. Third-Party Cookies</strong>\r\nWe may also use third-party cookies from partners such as advertising networks and analytics providers to help deliver content, monitor performance, and display ads relevant to you.<br><br></p><p><strong>5. Managing Cookies</strong>\r\nYou can manage your cookie preferences through your browser settings. You can choose to block or delete cookies; however, this may affect the functionality of certain parts of our website.<br><br></p><p><strong>6. Updates to This Policy</strong>\r\nWe may update this cookie policy from time to time. Any changes will be reflected on this page, and your continued use of our website will signify your acceptance of the updated terms.</p>', NULL, '2024-10-30 00:31:32', '2024-10-30 00:31:32'),
(64, 99, 1, 'Api Docs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[api_docs]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"api_docs\",\"footer\"]', '2024-10-30 00:33:59', '2024-10-30 00:33:59'),
(65, 100, 1, 'Privacy Policy', '<div><h3>Privacy Policy</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>How We Use Your Information</h5><p>We use your information to provide our services and maintain your account.</p><p>We use your information to understand how our services are used and make improvements.</p><p>We may use your contact information to send you important updates, newsletters, or respond to your inquiries.</p><p><br></p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Privacy Policy</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p><div class=\"policy-list mt-10\"><div class=\"item\"></div></div><h5><br></h5>', NULL, '2024-10-30 03:22:37', '2024-10-30 03:22:37'),
(66, 101, 1, 'Terms and  Conditions', '<div><h3>Terms and Conditions</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>Modifications to the Terms</h5><p>We reserve the right to modify these terms at any time. It is your responsibility to review these terms regularly. Continued use of the service after any changes implies acceptance of the new terms.</p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Terms</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p>', NULL, '2024-10-30 03:23:48', '2024-10-30 03:23:48'),
(67, 102, 1, 'Refund Policy', '<p><strong><span style=\"font-size:24px;\">Refund Policy</span></strong><br></p><p><strong>1. Introduction</strong></p><p>Thank you for shopping with us. We strive to ensure that you are completely satisfied with your purchase. This refund policy outlines our procedures for processing refunds and returns.<br><br></p><p><strong>2. Refund Eligibility</strong></p><p>To be eligible for a refund, you must meet the following criteria:</p><ul><li>The item must be returned within  +14582252545 days of receipt.</li><li>The item must be in its original condition, unused, and with all original packaging and tags.</li><li>Proof of purchase is required to process any refund.<br><br></li></ul><p><strong>3. Non-Refundable Items</strong></p><p>The following items are non-refundable:</p><ul><li>Downloadable software or digital content</li><li>Custom-made or personalized items</li><li>Clearance or sale items<br><br></li></ul><p><strong>4. Refund Process</strong></p><p>If you meet the eligibility criteria, please follow these steps to request a refund:</p><ol><li>Contact our customer service team atupport@nextDestina.com or +14582252545 to initiate the return process.</li><li>Provide your order number and details of the item(s) you wish to return.</li><li>Our team will provide you with return instructions and a return authorization number.</li><li>Return the item(s) to the address provided, using a trackable shipping method. You are responsible for return shipping costs.</li></ol><p>Once we receive and inspect the returned item(s), we will process your refund within +14582252545 business days. The refund will be issued to the original payment method used for the purchase.<br><br></p><p><strong>5. Exchanges</strong></p><p>If you wish to exchange an item for a different size, color, or product, please follow the return process and place a new order for the desired item.<br><br></p><p><strong>6. Damaged or Defective Items</strong></p><p>If you receive a damaged or defective item, please contact our customer service team immediately. We will arrange for a replacement or refund, including any return shipping costs if applicable.<br><br></p><p><strong>7. Changes to This Policy</strong></p><p>We reserve the right to modify or update this refund policy at any time. Any changes will be posted on our website.<br><br></p><p><strong>8. Contact Us</strong></p><p>If you have any questions or concerns about our refund policy, please contact us at support@nextDestina.com or +14582252545.<br><br></p>', NULL, '2024-10-30 03:24:21', '2024-10-30 03:35:17'),
(68, 103, 1, 'Cookie Policy', '<p></p><h3>Cookie Policy</h3><p>We use cookies on our website to enhance your experience, personalize content, and analyze our traffic. By using our site, you consent to our use of cookies in accordance with this policy.<br><br></p><p><strong>1. What Are Cookies?</strong>\r\nCookies are small text files that are stored on your device when you visit a website. They help websites remember your preferences and activities to provide a more personalized and seamless experience.<br><br></p><p><strong>2. Types of Cookies We Use</strong></p><ul><li>Essential Cookies<strong>:</strong> These cookies are necessary for the website to function properly, allowing you to navigate and use essential features.</li><li>Performance Cookies<strong>:</strong> These cookies collect information on how you interact with our website, helping us optimize performance and improve user experience.</li><li>Functional Cookies<strong>:</strong> These cookies enable additional functionality, such as remembering your preferences or settings.</li><li>Targeting/Advertising Cookies<strong>:</strong> These cookies track your browsing habits and are used to deliver relevant advertisements based on your interests.<br><br></li></ul><p><strong>3. How We Use Cookies</strong>\r\nWe use cookies to:</p><ul><li>Provide a secure and effective browsing experience</li><li>Analyze user behavior and website performance</li><li>Customize content and advertisements based on your preferences<br><br></li></ul><p><strong>4. Third-Party Cookies</strong>\r\nWe may also use third-party cookies from partners such as advertising networks and analytics providers to help deliver content, monitor performance, and display ads relevant to you.<br><br></p><p><strong>5. Managing Cookies</strong>\r\nYou can manage your cookie preferences through your browser settings. You can choose to block or delete cookies; however, this may affect the functionality of certain parts of our website.<br><br></p><p><strong>6. Updates to This Policy</strong>\r\nWe may update this cookie policy from time to time. Any changes will be reflected on this page, and your continued use of our website will signify your acceptance of the updated terms.</p>', NULL, '2024-10-30 03:25:04', '2024-10-30 03:34:10'),
(69, 104, 1, 'Api Docs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[api_docs]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"api_docs\",\"footer\"]', '2024-10-30 03:27:22', '2024-10-30 03:27:22'),
(70, 50, 1, 'Services', NULL, NULL, '2024-10-30 08:30:18', '2024-10-30 08:30:18'),
(71, 51, 1, 'Login', NULL, NULL, '2024-10-30 08:37:44', '2024-10-30 08:37:44'),
(72, 52, 1, 'Register', NULL, NULL, '2024-10-30 08:47:52', '2024-10-30 08:47:52'),
(73, 53, 1, 'Forgot Password', NULL, NULL, '2024-10-30 08:48:26', '2024-10-30 08:48:26'),
(74, 105, 1, 'Privacy Policy', '<div><h3>Privacy Policy</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>How We Use Your Information</h5><p>We use your information to provide our services and maintain your account.</p><p>We use your information to understand how our services are used and make improvements.</p><p>We may use your contact information to send you important updates, newsletters, or respond to your inquiries.</p><p><br></p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Privacy Policy</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p><div class=\"policy-list mt-10\"><div class=\"item\"></div></div><h5><br></h5>', NULL, '2024-10-30 08:50:56', '2024-10-30 08:50:56'),
(75, 106, 1, 'Terms Conditions', '<div><h3>Terms and Conditions</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>Modifications to the Terms</h5><p>We reserve the right to modify these terms at any time. It is your responsibility to review these terms regularly. Continued use of the service after any changes implies acceptance of the new terms.</p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Terms</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p>', NULL, '2024-10-30 08:51:39', '2024-10-30 08:51:39'),
(76, 107, 1, 'Refund Policy', '<p><strong><span style=\"font-size:24px;\">Refund Policy</span></strong><br></p><p><strong>1. Introduction</strong></p><p>Thank you for shopping with us. We strive to ensure that you are completely satisfied with your purchase. This refund policy outlines our procedures for processing refunds and returns.</p><p><strong>2. Refund Eligibility</strong></p><p>To be eligible for a refund, you must meet the following criteria:</p><ul><li>The item must be returned within  +14582252545 days of receipt.</li><li>The item must be in its original condition, unused, and with all original packaging and tags.</li><li>Proof of purchase is required to process any refund.<br><br></li></ul><p><strong>3. Non-Refundable Items</strong></p><p>The following items are non-refundable:</p><ul><li>Downloadable software or digital content</li><li>Custom-made or personalized items</li><li>Clearance or sale items<br><br></li></ul><p><strong>4. Refund Process</strong></p><p>If you meet the eligibility criteria, please follow these steps to request a refund:</p><ol><li>Contact our customer service team atupport@nextDestina.com or +14582252545 to initiate the return process.</li><li>Provide your order number and details of the item(s) you wish to return.</li><li>Our team will provide you with return instructions and a return authorization number.</li><li>Return the item(s) to the address provided, using a trackable shipping method. You are responsible for return shipping costs.</li></ol><p>Once we receive and inspect the returned item(s), we will process your refund within +14582252545 business days. The refund will be issued to the original payment method used for the purchase.<br><br></p><p><strong>5. Exchanges</strong></p><p>If you wish to exchange an item for a different size, color, or product, please follow the return process and place a new order for the desired item.<br><br></p><p><strong>6. Damaged or Defective Items</strong></p><p>If you receive a damaged or defective item, please contact our customer service team immediately. We will arrange for a replacement or refund, including any return shipping costs if applicable.<br><br></p><p><strong>7. Changes to This Policy</strong></p><p>We reserve the right to modify or update this refund policy at any time. Any changes will be posted on our website.<br><br></p><p><strong>8. Contact Us</strong></p><p>If you have any questions or concerns about our refund policy, please contact us at support@nextDestina.com or +14582252545.<br><br></p>', NULL, '2024-10-30 08:52:13', '2024-10-30 08:52:13'),
(77, 108, 1, 'Cookie Policy', '<p></p><h3>Cookie Policy</h3><p>We use cookies on our website to enhance your experience, personalize content, and analyze our traffic. By using our site, you consent to our use of cookies in accordance with this policy.<br><br></p><p><strong>1. What Are Cookies?</strong>\r\nCookies are small text files that are stored on your device when you visit a website. They help websites remember your preferences and activities to provide a more personalized and seamless experience.<br><br></p><p><strong>2. Types of Cookies We Use</strong></p><ul><li><strong>Essential Cookies:</strong> These cookies are necessary for the website to function properly, allowing you to navigate and use essential features.</li><li><strong>Performance Cookies:</strong> These cookies collect information on how you interact with our website, helping us optimize performance and improve user experience.</li><li><strong>Functional Cookies:</strong> These cookies enable additional functionality, such as remembering your preferences or settings.</li><li><strong>Targeting/Advertising Cookies:</strong> These cookies track your browsing habits and are used to deliver relevant advertisements based on your interests.<br><br></li></ul><p><strong>3. How We Use Cookies</strong>\r\nWe use cookies to:</p><ul><li>Provide a secure and effective browsing experience</li><li>Analyze user behavior and website performance</li><li>Customize content and advertisements based on your preferences<br><br></li></ul><p><strong>4. Third-Party Cookies</strong>\r\nWe may also use third-party cookies from partners such as advertising networks and analytics providers to help deliver content, monitor performance, and display ads relevant to you.<br><br></p><p><strong>5. Managing Cookies</strong>\r\nYou can manage your cookie preferences through your browser settings. You can choose to block or delete cookies; however, this may affect the functionality of certain parts of our website.<br><br></p><p><strong>6. Updates to This Policy</strong>\r\nWe may update this cookie policy from time to time. Any changes will be reflected on this page, and your continued use of our website will signify your acceptance of the updated terms.</p>', NULL, '2024-10-30 08:52:39', '2024-10-30 08:52:39'),
(78, 109, 1, 'Api Docs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[api_docs]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"api_docs\",\"footer\"]', '2024-10-30 08:56:40', '2024-10-30 08:56:40'),
(79, 54, 1, 'Reset Password', NULL, NULL, '2024-10-30 08:58:37', '2024-10-30 08:58:37'),
(80, 55, 1, 'Email Verification', NULL, NULL, '2024-10-30 08:58:48', '2024-10-30 08:58:48'),
(81, 56, 1, 'SMS Verification', NULL, NULL, '2024-10-30 08:58:56', '2024-10-30 08:58:56'),
(82, 57, 1, 'Two Fa Verification', NULL, NULL, '2024-10-30 08:59:52', '2024-10-30 08:59:52'),
(83, 86, 1, 'Login', NULL, NULL, '2024-10-30 09:14:35', '2024-10-30 09:14:35'),
(84, 87, 1, 'Register', NULL, NULL, '2024-10-30 09:14:43', '2024-10-30 09:14:43'),
(85, 88, 1, 'Forgot Password', NULL, NULL, '2024-10-30 09:18:53', '2024-10-30 09:18:53'),
(86, 89, 1, 'Reset Password', NULL, NULL, '2024-10-30 09:19:09', '2024-10-30 09:19:09'),
(87, 90, 1, 'Email Verification', NULL, NULL, '2024-10-30 09:19:27', '2024-10-30 09:19:27'),
(88, 91, 1, 'SMS Verification', NULL, NULL, '2024-10-30 09:19:44', '2024-10-30 09:19:44'),
(89, 92, 1, 'Two Fa Verification', NULL, NULL, '2024-10-30 09:20:12', '2024-10-30 09:20:12'),
(90, 110, 1, 'Faq', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[faq]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"faq\",\"footer\"]', '2024-10-30 09:48:46', '2024-10-30 09:52:32'),
(91, 111, 1, 'Privacy Policy', '<div><h3>Privacy Policy</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>How We Use Your Information</h5><p>We use your information to provide our services and maintain your account.</p><p>We use your information to understand how our services are used and make improvements.</p><p>We may use your contact information to send you important updates, newsletters, or respond to your inquiries.</p><p><br></p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Privacy Policy</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p><div class=\"policy-list mt-10\"><div class=\"item\"></div></div><h5><br></h5>', NULL, '2024-10-30 09:58:06', '2024-10-30 09:58:06'),
(92, 112, 1, 'Terms and Conditions', '<div><h3>Terms and Conditions</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>Modifications to the Terms</h5><p>We reserve the right to modify these terms at any time. It is your responsibility to review these terms regularly. Continued use of the service after any changes implies acceptance of the new terms.</p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Terms</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p>', NULL, '2024-10-30 09:58:31', '2024-10-30 09:58:31'),
(93, 113, 1, 'Refund Policy', '<p><strong><span style=\"font-size:24px;\">Refund Policy</span></strong><br></p><p><strong>1. Introduction</strong></p><p>Thank you for shopping with us. We strive to ensure that you are completely satisfied with your purchase. This refund policy outlines our procedures for processing refunds and returns.</p><p><strong>2. Refund Eligibility</strong></p><p>To be eligible for a refund, you must meet the following criteria:</p><ul><li>The item must be returned within  +14582252545 days of receipt.</li><li>The item must be in its original condition, unused, and with all original packaging and tags.</li><li>Proof of purchase is required to process any refund.<br><br></li></ul><p><strong>3. Non-Refundable Items</strong></p><p>The following items are non-refundable:</p><ul><li>Downloadable software or digital content</li><li>Custom-made or personalized items</li><li>Clearance or sale items<br><br></li></ul><p><strong>4. Refund Process</strong></p><p>If you meet the eligibility criteria, please follow these steps to request a refund:</p><ol><li>Contact our customer service team atupport@nextDestina.com or +14582252545 to initiate the return process.</li><li>Provide your order number and details of the item(s) you wish to return.</li><li>Our team will provide you with return instructions and a return authorization number.</li><li>Return the item(s) to the address provided, using a trackable shipping method. You are responsible for return shipping costs.</li></ol><p>Once we receive and inspect the returned item(s), we will process your refund within +14582252545 business days. The refund will be issued to the original payment method used for the purchase.<br><br></p><p><strong>5. Exchanges</strong></p><p>If you wish to exchange an item for a different size, color, or product, please follow the return process and place a new order for the desired item.<br><br></p><p><strong>6. Damaged or Defective Items</strong></p><p>If you receive a damaged or defective item, please contact our customer service team immediately. We will arrange for a replacement or refund, including any return shipping costs if applicable.<br><br></p><p><strong>7. Changes to This Policy</strong></p><p>We reserve the right to modify or update this refund policy at any time. Any changes will be posted on our website.<br><br></p><p><strong>8. Contact Us</strong></p><p>If you have any questions or concerns about our refund policy, please contact us at support@nextDestina.com or +14582252545.<br><br></p>', NULL, '2024-10-30 09:58:55', '2024-10-30 09:58:55');
INSERT INTO `page_details` (`id`, `page_id`, `language_id`, `name`, `content`, `sections`, `created_at`, `updated_at`) VALUES
(94, 114, 1, 'Cookie Policy', '<p></p><h3>Cookie Policy</h3><p>We use cookies on our website to enhance your experience, personalize content, and analyze our traffic. By using our site, you consent to our use of cookies in accordance with this policy.<br><br></p><p><strong>1. What Are Cookies?</strong>\r\nCookies are small text files that are stored on your device when you visit a website. They help websites remember your preferences and activities to provide a more personalized and seamless experience.<br><br></p><p><strong>2. Types of Cookies We Use</strong></p><ul><li><strong>Essential Cookies:</strong> These cookies are necessary for the website to function properly, allowing you to navigate and use essential features.</li><li><strong>Performance Cookies:</strong> These cookies collect information on how you interact with our website, helping us optimize performance and improve user experience.</li><li><strong>Functional Cookies:</strong> These cookies enable additional functionality, such as remembering your preferences or settings.</li><li><strong>Targeting/Advertising Cookies:</strong> These cookies track your browsing habits and are used to deliver relevant advertisements based on your interests.<br><br></li></ul><p><strong>3. How We Use Cookies</strong>\r\nWe use cookies to:</p><ul><li>Provide a secure and effective browsing experience</li><li>Analyze user behavior and website performance</li><li>Customize content and advertisements based on your preferences<br><br></li></ul><p><strong>4. Third-Party Cookies</strong>\r\nWe may also use third-party cookies from partners such as advertising networks and analytics providers to help deliver content, monitor performance, and display ads relevant to you.<br><br></p><p><strong>5. Managing Cookies</strong>\r\nYou can manage your cookie preferences through your browser settings. You can choose to block or delete cookies; however, this may affect the functionality of certain parts of our website.<br><br></p><p><strong>6. Updates to This Policy</strong>\r\nWe may update this cookie policy from time to time. Any changes will be reflected on this page, and your continued use of our website will signify your acceptance of the updated terms.</p>', NULL, '2024-10-30 09:59:11', '2024-10-30 09:59:11'),
(95, 115, 1, 'Api Docs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[api_docs]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"api_docs\",\"footer\"]', '2024-10-30 10:00:30', '2024-10-30 10:00:30'),
(96, 116, 1, 'Privacy &amp; Policy', '<div><h3>Privacy Policy</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>How We Use Your Information</h5><p>We use your information to provide our services and maintain your account.</p><p>We use your information to understand how our services are used and make improvements.</p><p>We may use your contact information to send you important updates, newsletters, or respond to your inquiries.</p><p><br></p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Privacy Policy</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p><div class=\"policy-list mt-10\"><div class=\"item\"></div></div><h5><br></h5>', NULL, '2024-10-30 10:30:21', '2024-10-30 10:30:21'),
(97, 117, 1, 'Terms and Conditions', '<div><h3>Terms and Conditions</h3></div><p>At SMM, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, disclose, and protect the data you provide while using our classified ads platform.<br><br></p><h5>Information We Collect</h5><p>We may collect personally identifiable information, such as your name, email address, phone number, and other information you provide when you register, contact us, or use our services.</p><p>We automatically collect information that your browser sends whenever you visit our website. This may include your IP address, browser type, browser version, the pages you visit, and the time and date of your visit.</p><p>We use cookies to collect information and improve your experience. You can configure your browser to block or disable cookies, but this may affect your ability to use our services.</p><p><br></p><h5>Modifications to the Terms</h5><p>We reserve the right to modify these terms at any time. It is your responsibility to review these terms regularly. Continued use of the service after any changes implies acceptance of the new terms.</p><h5>Data Sharing and Disclosure</h5><p>We may share your information with trusted third parties for purposes such as data analysis, payment processing, customer support, and other services. We do not sell, rent, or trade your personal information to third parties for marketing purposes.</p><p><br></p><h5>Security</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Changes to this Terms</h5><p>We take reasonable measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.</p><p><br></p><h5>Contact Us</h5><p>If you have any questions or concerns about this Privacy Policy, please contact us at demo@example.com</p><p><br></p>', NULL, '2024-10-30 10:30:47', '2024-10-30 10:30:47'),
(98, 118, 1, 'Refund Policy', '<p><strong><span style=\"font-size:24px;\">Refund Policy</span></strong><br></p><p><strong>1. Introduction</strong></p><p>Thank you for shopping with us. We strive to ensure that you are completely satisfied with your purchase. This refund policy outlines our procedures for processing refunds and returns.</p><p><strong>2. Refund Eligibility</strong></p><p>To be eligible for a refund, you must meet the following criteria:</p><ul><li>The item must be returned within  +14582252545 days of receipt.</li><li>The item must be in its original condition, unused, and with all original packaging and tags.</li><li>Proof of purchase is required to process any refund.<br><br></li></ul><p><strong>3. Non-Refundable Items</strong></p><p>The following items are non-refundable:</p><ul><li>Downloadable software or digital content</li><li>Custom-made or personalized items</li><li>Clearance or sale items<br><br></li></ul><p><strong>4. Refund Process</strong></p><p>If you meet the eligibility criteria, please follow these steps to request a refund:</p><ol><li>Contact our customer service team atupport@nextDestina.com or +14582252545 to initiate the return process.</li><li>Provide your order number and details of the item(s) you wish to return.</li><li>Our team will provide you with return instructions and a return authorization number.</li><li>Return the item(s) to the address provided, using a trackable shipping method. You are responsible for return shipping costs.</li></ol><p>Once we receive and inspect the returned item(s), we will process your refund within +14582252545 business days. The refund will be issued to the original payment method used for the purchase.<br><br></p><p><strong>5. Exchanges</strong></p><p>If you wish to exchange an item for a different size, color, or product, please follow the return process and place a new order for the desired item.<br><br></p><p><strong>6. Damaged or Defective Items</strong></p><p>If you receive a damaged or defective item, please contact our customer service team immediately. We will arrange for a replacement or refund, including any return shipping costs if applicable.<br><br></p><p><strong>7. Changes to This Policy</strong></p><p>We reserve the right to modify or update this refund policy at any time. Any changes will be posted on our website.<br><br></p><p><strong>8. Contact Us</strong></p><p>If you have any questions or concerns about our refund policy, please contact us at support@nextDestina.com or +14582252545.<br><br></p>', NULL, '2024-10-30 10:31:12', '2024-10-30 10:31:12'),
(99, 119, 1, 'Cookie Policy', '<p></p><h3>Cookie Policy</h3><p>We use cookies on our website to enhance your experience, personalize content, and analyze our traffic. By using our site, you consent to our use of cookies in accordance with this policy.<br><br></p><p><strong>1. What Are Cookies?</strong>\r\nCookies are small text files that are stored on your device when you visit a website. They help websites remember your preferences and activities to provide a more personalized and seamless experience.<br><br></p><p><strong>2. Types of Cookies We Use</strong></p><ul><li><strong>Essential Cookies:</strong> These cookies are necessary for the website to function properly, allowing you to navigate and use essential features.</li><li><strong>Performance Cookies:</strong> These cookies collect information on how you interact with our website, helping us optimize performance and improve user experience.</li><li><strong>Functional Cookies:</strong> These cookies enable additional functionality, such as remembering your preferences or settings.</li><li><strong>Targeting/Advertising Cookies:</strong> These cookies track your browsing habits and are used to deliver relevant advertisements based on your interests.<br><br></li></ul><p><strong>3. How We Use Cookies</strong>\r\nWe use cookies to:</p><ul><li>Provide a secure and effective browsing experience</li><li>Analyze user behavior and website performance</li><li>Customize content and advertisements based on your preferences<br><br></li></ul><p><strong>4. Third-Party Cookies</strong>\r\nWe may also use third-party cookies from partners such as advertising networks and analytics providers to help deliver content, monitor performance, and display ads relevant to you.<br><br></p><p><strong>5. Managing Cookies</strong>\r\nYou can manage your cookie preferences through your browser settings. You can choose to block or delete cookies; however, this may affect the functionality of certain parts of our website.<br><br></p><p><strong>6. Updates to This Policy</strong>\r\nWe may update this cookie policy from time to time. Any changes will be reflected on this page, and your continued use of our website will signify your acceptance of the updated terms.</p>', NULL, '2024-10-30 10:31:39', '2024-10-30 10:31:39'),
(100, 120, 1, 'Api Docs', '<div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[api_docs]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p><div class=\"custom-block\" contenteditable=\"false\"><div class=\"custom-block-content\">[[footer]]</div>\r\n                    <span class=\"delete-block\">×</span>\r\n                    <span class=\"up-block\">↑</span>\r\n                    <span class=\"down-block\">↓</span></div><p><br></p>', '[\"api_docs\",\"footer\"]', '2024-10-30 10:33:38', '2024-10-30 10:33:38'),
(101, 80, 1, 'Two FA Verification', NULL, NULL, '2024-10-30 10:36:11', '2024-10-30 10:36:11'),
(102, 78, 1, 'Email Verification', NULL, NULL, '2024-10-30 10:37:15', '2024-10-30 10:37:15'),
(103, 79, 1, 'SMS Verification', NULL, NULL, '2024-10-30 10:37:35', '2024-10-30 10:37:35'),
(104, 77, 1, 'Reset Password', NULL, NULL, '2024-10-30 10:38:02', '2024-10-30 10:38:02'),
(105, 38, 1, 'Login', NULL, NULL, '2024-11-05 22:30:21', '2024-11-05 22:30:21'),
(106, 39, 1, 'Register', NULL, NULL, '2024-11-05 22:30:33', '2024-11-05 22:30:33'),
(107, 40, 1, 'Forgot Password', NULL, NULL, '2024-11-05 22:30:52', '2024-11-05 22:30:52'),
(108, 41, 1, 'Reset Password', NULL, NULL, '2024-11-05 22:31:07', '2024-11-05 22:31:07'),
(109, 42, 1, 'Email Verification', NULL, NULL, '2024-11-05 22:31:13', '2024-11-05 22:31:13'),
(110, 43, 1, 'SMS Verification', NULL, NULL, '2024-11-05 22:31:27', '2024-11-05 22:31:27'),
(111, 44, 1, 'Two FA Verification', NULL, NULL, '2024-11-05 22:31:46', '2024-11-05 22:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `page_seos`
--

CREATE TABLE `page_seos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `meta_robots` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_seos`
--

INSERT INTO `page_seos` (`id`, `page_name`, `meta_title`, `meta_keywords`, `meta_description`, `og_description`, `meta_robots`, `created_at`, `updated_at`) VALUES
(1, 'home', NULL, NULL, NULL, NULL, NULL, '2024-08-03 02:22:22', '2024-08-03 02:22:22'),
(2, 'about', 'sfdsfdsdf', '[\"sdfsdf\"]', 'sdfsdf', 'sdfdsf', 'nofollow', '2024-08-03 02:22:22', '2024-08-03 03:07:33'),
(3, 'faq', NULL, NULL, NULL, NULL, NULL, '2024-08-03 02:22:22', '2024-08-03 02:22:22'),
(4, 'service', NULL, NULL, NULL, NULL, NULL, '2024-08-03 02:22:22', '2024-08-03 02:22:22'),
(5, 'blog', NULL, NULL, NULL, NULL, NULL, '2024-08-03 02:22:22', '2024-08-03 02:22:22'),
(6, 'contact', NULL, NULL, NULL, NULL, NULL, '2024-08-03 02:22:22', '2024-08-03 02:22:22');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `razorpay_contacts`
--

CREATE TABLE `razorpay_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` varchar(255) DEFAULT NULL,
  `entity` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commission_type` varchar(50) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `percent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_bonuses`
--

CREATE TABLE `referral_bonuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `amount` decimal(18,8) NOT NULL DEFAULT 0.00000000,
  `main_balance` decimal(18,8) NOT NULL DEFAULT 0.00000000,
  `transaction` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_title` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `min_amount` int(11) DEFAULT NULL,
  `max_amount` int(11) DEFAULT NULL,
  `original_price` text DEFAULT NULL,
  `price` varchar(191) DEFAULT NULL,
  `price_percentage_increase` decimal(8,2) NOT NULL DEFAULT 0.00,
  `service_type` varchar(191) DEFAULT NULL,
  `service_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `api_provider_id` int(11) DEFAULT NULL,
  `api_service_id` int(11) DEFAULT NULL,
  `api_provider_price` varchar(191) DEFAULT NULL,
  `drip_feed` tinyint(4) DEFAULT NULL COMMENT '0 => false, 1 => true',
  `refill` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 => false, 1 => true',
  `is_refill_automatic` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => manual, 1 => automatic',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_driver` varchar(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `sort_by` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribes`
--

CREATE TABLE `subscribes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `child_panel_id` int(11) DEFAULT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0 COMMENT '0 =>  Open, 1 => Answered, 2 => Replied, 3 => Closed',
  `last_reply` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_attachments`
--

CREATE TABLE `support_ticket_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_message_id` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `driver` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_messages`
--

CREATE TABLE `support_ticket_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theme_colors`
--

CREATE TABLE `theme_colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `light_green_primary_color` varchar(100) DEFAULT NULL,
  `light_green_secondary_color` varchar(100) DEFAULT NULL,
  `light_green_hero_color` varchar(100) DEFAULT NULL,
  `dark_violet_primary_color` varchar(100) DEFAULT NULL,
  `dark_violet_secondary_color` varchar(100) DEFAULT NULL,
  `minimal_primary_color` varchar(100) DEFAULT NULL,
  `minimal_secondary_color` varchar(100) DEFAULT NULL,
  `minimal_sub_heading_color` varchar(100) DEFAULT NULL,
  `minimal_bg_left_color` varchar(100) DEFAULT NULL,
  `minimal_bg_right_color` varchar(100) DEFAULT NULL,
  `minimal_button_left_color` varchar(100) DEFAULT NULL,
  `minimal_bg_left_two_color` varchar(100) DEFAULT NULL,
  `minimal_copy_right_bg_color` varchar(100) DEFAULT NULL,
  `deep_blue_primary_color` varchar(100) DEFAULT NULL,
  `deep_blue_secondary_color` varchar(100) DEFAULT NULL,
  `dark_mode_primary_color` varchar(100) DEFAULT NULL,
  `dark_mode_secondary_color` varchar(100) DEFAULT NULL,
  `light_orange_primary_color` varchar(100) DEFAULT NULL,
  `light_orange_theme_light_color` varchar(100) DEFAULT NULL,
  `light_orange_secondary_color` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `theme_colors`
--

INSERT INTO `theme_colors` (`id`, `light_green_primary_color`, `light_green_secondary_color`, `light_green_hero_color`, `dark_violet_primary_color`, `dark_violet_secondary_color`, `minimal_primary_color`, `minimal_secondary_color`, `minimal_sub_heading_color`, `minimal_bg_left_color`, `minimal_bg_right_color`, `minimal_button_left_color`, `minimal_bg_left_two_color`, `minimal_copy_right_bg_color`, `deep_blue_primary_color`, `deep_blue_secondary_color`, `dark_mode_primary_color`, `dark_mode_secondary_color`, `light_orange_primary_color`, `light_orange_theme_light_color`, `light_orange_secondary_color`, `created_at`, `updated_at`) VALUES
(1, '#706fc7', '#ffa200', '#0b07cf', '#f2a516', '#03292e', '#c64fff', '#fe5268', '#7b3fff', '#7b3fff', '#5900ff', '#c64fff', '#c64fff', '#1d43db', '#ff8503', '#000000', '#febd00', '#000000', '#fa673b', '#fb6738', '#fe5268', '2025-01-04 06:27:27', '2025-01-04 07:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `transactional_id` int(11) DEFAULT NULL,
  `transactional_type` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `child_panel_id` int(11) DEFAULT NULL,
  `amount` double(11,2) DEFAULT NULL,
  `balance` varchar(20) DEFAULT NULL,
  `charge` decimal(11,2) NOT NULL DEFAULT 0.00,
  `trx_type` varchar(10) DEFAULT NULL,
  `remarks` varchar(191) NOT NULL,
  `trx_id` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(191) DEFAULT NULL,
  `lastname` varchar(191) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `child_panel_id` int(11) DEFAULT NULL,
  `referral_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country_code` varchar(20) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `phone_code` varchar(20) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `balance` decimal(11,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `image_driver` varchar(50) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL COMMENT 'Zip Or Postal Code',
  `address_one` text DEFAULT NULL,
  `address_two` text DEFAULT NULL,
  `provider` varchar(191) DEFAULT NULL,
  `provider_id` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `two_fa` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 => inactive, 1 => active',
  `two_fa_verify` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => inactive, 1 => active',
  `two_fa_code` varchar(50) DEFAULT NULL,
  `email_verification` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => unverified, 1 => verified',
  `sms_verification` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 => unverified, 1 => verified',
  `verify_code` varchar(50) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `github_id` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `time_zone` varchar(191) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `api_token` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_kycs`
--

CREATE TABLE `user_kycs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kyc_id` int(11) DEFAULT NULL,
  `kyc_type` varchar(191) DEFAULT NULL,
  `kyc_info` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=>pending , 1=> verified, 2=>rejected',
  `reason` longtext DEFAULT NULL COMMENT 'rejected reason',
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `child_panel_id` int(11) DEFAULT NULL,
  `longitude` varchar(191) DEFAULT NULL,
  `latitude` varchar(191) DEFAULT NULL,
  `country_code` varchar(50) DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `browser` varchar(191) DEFAULT NULL,
  `os` varchar(191) DEFAULT NULL,
  `get_device` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_service_rates`
--

CREATE TABLE `user_service_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `price` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `price_percentage` double(8,2) NOT NULL DEFAULT 0.00,
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
  ADD UNIQUE KEY `admins_username_unique` (`username`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `api_providers`
--
ALTER TABLE `api_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `author_details`
--
ALTER TABLE `author_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `basic_controls`
--
ALTER TABLE `basic_controls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_category_details`
--
ALTER TABLE `blog_category_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_details`
--
ALTER TABLE `blog_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_details`
--
ALTER TABLE `content_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deposits_user_id_foreign` (`user_id`),
  ADD KEY `deposits_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `draft_mass_orders`
--
ALTER TABLE `draft_mass_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `file_storages`
--
ALTER TABLE `file_storages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fire_base_tokens`
--
ALTER TABLE `fire_base_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gateways_code_unique` (`code`);

--
-- Indexes for table `in_app_notifications`
--
ALTER TABLE `in_app_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `kycs`
--
ALTER TABLE `kycs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_modes`
--
ALTER TABLE `maintenance_modes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manage_menus`
--
ALTER TABLE `manage_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manual_sms_configs`
--
ALTER TABLE `manual_sms_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_details`
--
ALTER TABLE `notice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_templates_language_id_foreign` (`language_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_details`
--
ALTER TABLE `page_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_seos`
--
ALTER TABLE `page_seos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `razorpay_contacts`
--
ALTER TABLE `razorpay_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_bonuses`
--
ALTER TABLE `referral_bonuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribes`
--
ALTER TABLE `subscribes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_ticket_attachments`
--
ALTER TABLE `support_ticket_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `theme_colors`
--
ALTER TABLE `theme_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_kycs`
--
ALTER TABLE `user_kycs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_kycs_user_id_index` (`user_id`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_service_rates`
--
ALTER TABLE `user_service_rates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `api_providers`
--
ALTER TABLE `api_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `author_details`
--
ALTER TABLE `author_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `basic_controls`
--
ALTER TABLE `basic_controls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blog_category_details`
--
ALTER TABLE `blog_category_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `blog_details`
--
ALTER TABLE `blog_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=449;

--
-- AUTO_INCREMENT for table `content_details`
--
ALTER TABLE `content_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=866;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `draft_mass_orders`
--
ALTER TABLE `draft_mass_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_storages`
--
ALTER TABLE `file_storages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fire_base_tokens`
--
ALTER TABLE `fire_base_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

--
-- AUTO_INCREMENT for table `in_app_notifications`
--
ALTER TABLE `in_app_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kycs`
--
ALTER TABLE `kycs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `maintenance_modes`
--
ALTER TABLE `maintenance_modes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `manage_menus`
--
ALTER TABLE `manage_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `manual_sms_configs`
--
ALTER TABLE `manual_sms_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notice_details`
--
ALTER TABLE `notice_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `page_details`
--
ALTER TABLE `page_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `page_seos`
--
ALTER TABLE `page_seos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `razorpay_contacts`
--
ALTER TABLE `razorpay_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_bonuses`
--
ALTER TABLE `referral_bonuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribes`
--
ALTER TABLE `subscribes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_ticket_attachments`
--
ALTER TABLE `support_ticket_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theme_colors`
--
ALTER TABLE `theme_colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_kycs`
--
ALTER TABLE `user_kycs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_service_rates`
--
ALTER TABLE `user_service_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD CONSTRAINT `notification_templates_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
