-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 24, 2025 at 04:38 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `install_infinite`
--

-- --------------------------------------------------------

--
-- Table structure for table `ad_spaces`
--

CREATE TABLE `ad_spaces` (
  `id` int(11) NOT NULL,
  `lang_id` int(11) DEFAULT 1,
  `ad_space` text DEFAULT NULL,
  `ad_code_desktop` text DEFAULT NULL,
  `desktop_width` int(11) DEFAULT NULL,
  `desktop_height` int(11) DEFAULT NULL,
  `ad_code_mobile` text DEFAULT NULL,
  `mobile_width` int(11) DEFAULT NULL,
  `mobile_height` int(11) DEFAULT NULL,
  `display_category_id` int(11) DEFAULT NULL,
  `paragraph_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `lang_id` tinyint(4) DEFAULT 1,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `keywords` varchar(500) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  `category_order` smallint(6) DEFAULT NULL,
  `show_on_menu` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT 0,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `comment` varchar(5000) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `message` varchar(5000) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `user_id` int(11) DEFAULT 1,
  `storage` varchar(20) DEFAULT 'local'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `following_id` int(11) DEFAULT NULL,
  `follower_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fonts`
--

CREATE TABLE `fonts` (
  `id` int(11) NOT NULL,
  `font_name` varchar(255) DEFAULT NULL,
  `font_key` varchar(255) DEFAULT NULL,
  `font_url` varchar(2000) DEFAULT NULL,
  `font_family` varchar(500) DEFAULT NULL,
  `font_source` varchar(50) DEFAULT 'google',
  `has_local_file` tinyint(1) DEFAULT 0,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fonts`
--

INSERT INTO `fonts` (`id`, `font_name`, `font_key`, `font_url`, `font_family`, `font_source`, `has_local_file`, `is_default`) VALUES
(1, 'Arial', 'arial', NULL, 'font-family: Arial, Helvetica, sans-serif', 'local', 0, 1),
(2, 'Arvo', 'arvo', '<link href=\"https://fonts.googleapis.com/css?family=Arvo:400,700&display=swap\" rel=\"stylesheet\">\r\n', 'font-family: \"Arvo\", Helvetica, sans-serif', 'google', 0, 0),
(3, 'Averia Libre', 'averia-libre', '<link href=\"https://fonts.googleapis.com/css?family=Averia+Libre:300,400,700&display=swap\" rel=\"stylesheet\">\r\n', 'font-family: \"Averia Libre\", Helvetica, sans-serif', 'google', 0, 0),
(4, 'Bitter', 'bitter', '<link href=\"https://fonts.googleapis.com/css?family=Bitter:400,400i,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Bitter\", Helvetica, sans-serif', 'google', 0, 0),
(5, 'Cabin', 'cabin', '<link href=\"https://fonts.googleapis.com/css?family=Cabin:400,500,600,700&display=swap&subset=latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Cabin\", Helvetica, sans-serif', 'google', 0, 0),
(6, 'Cherry Swash', 'cherry-swash', '<link href=\"https://fonts.googleapis.com/css?family=Cherry+Swash:400,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Cherry Swash\", Helvetica, sans-serif', 'google', 0, 0),
(7, 'Encode Sans', 'encode-sans', '<link href=\"https://fonts.googleapis.com/css?family=Encode+Sans:300,400,500,600,700&display=swap&subset=latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Encode Sans\", Helvetica, sans-serif', 'google', 0, 0),
(8, 'Helvetica', 'helvetica', NULL, 'font-family: Helvetica, sans-serif', 'local', 0, 1),
(9, 'Hind', 'hind', '<link href=\"https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">', 'font-family: \"Hind\", Helvetica, sans-serif', 'google', 0, 0),
(10, 'Inter', 'inter', NULL, 'font-family: \"Inter\", sans-serif;', 'local', 1, 0),
(11, 'Josefin Sans', 'josefin-sans', '<link href=\"https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,600,700&display=swap&subset=latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Josefin Sans\", Helvetica, sans-serif', 'google', 0, 0),
(12, 'Kalam', 'kalam', '<link href=\"https://fonts.googleapis.com/css?family=Kalam:300,400,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Kalam\", Helvetica, sans-serif', 'google', 0, 0),
(13, 'Khula', 'khula', '<link href=\"https://fonts.googleapis.com/css?family=Khula:300,400,600,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Khula\", Helvetica, sans-serif', 'google', 0, 0),
(14, 'Lato', 'lato', '<link href=\"https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">', 'font-family: \"Lato\", Helvetica, sans-serif', 'google', 0, 0),
(15, 'Lora', 'lora', '<link href=\"https://fonts.googleapis.com/css?family=Lora:400,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Lora\", Helvetica, sans-serif', 'google', 0, 0),
(16, 'Merriweather', 'merriweather', '<link href=\"https://fonts.googleapis.com/css?family=Merriweather:300,400,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Merriweather\", Helvetica, sans-serif', 'google', 0, 0),
(17, 'Montserrat', 'montserrat', '<link href=\"https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Montserrat\", Helvetica, sans-serif', 'google', 0, 0),
(18, 'Mukta', 'mukta', '<link href=\"https://fonts.googleapis.com/css?family=Mukta:300,400,500,600,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Mukta\", Helvetica, sans-serif', 'google', 0, 0),
(19, 'Nunito', 'nunito', '<link href=\"https://fonts.googleapis.com/css?family=Nunito:300,400,600,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Nunito\", Helvetica, sans-serif', 'google', 0, 0),
(20, 'Open Sans', 'open-sans', NULL, 'font-family: \"Open Sans\", Helvetica, sans-serif', 'local', 1, 0),
(21, 'Oswald', 'oswald', '<link href=\"https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">', 'font-family: \"Oswald\", Helvetica, sans-serif', 'google', 0, 0),
(22, 'Oxygen', 'oxygen', '<link href=\"https://fonts.googleapis.com/css?family=Oxygen:300,400,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Oxygen\", Helvetica, sans-serif', 'google', 0, 0),
(23, 'Poppins', 'poppins', '<link href=\"https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">', 'font-family: \"Poppins\", Helvetica, sans-serif', 'google', 0, 0),
(24, 'PT Sans', 'pt-sans', '<link href=\"https://fonts.googleapis.com/css?family=PT+Sans:400,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"PT Sans\", Helvetica, sans-serif', 'google', 0, 0),
(25, 'PT Serif', 'pt-serif', NULL, 'font-family: \"PT Serif\", serif;', 'local', 1, 0),
(26, 'Raleway', 'raleway', '<link href=\"https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Raleway\", Helvetica, sans-serif', 'google', 0, 0),
(27, 'Roboto', 'roboto', NULL, 'font-family: \"Roboto\", Helvetica, sans-serif', 'local', 1, 0),
(28, 'Roboto Condensed', 'roboto-condensed', '<link href=\"https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Roboto Condensed\", Helvetica, sans-serif', 'google', 0, 0),
(29, 'Roboto Slab', 'roboto-slab', '<link href=\"https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Roboto Slab\", Helvetica, sans-serif', 'google', 0, 0),
(30, 'Rokkitt', 'rokkitt', '<link href=\"https://fonts.googleapis.com/css?family=Rokkitt:300,400,500,600,700&display=swap&subset=latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Rokkitt\", Helvetica, sans-serif', 'google', 0, 0),
(31, 'Source Sans Pro', 'source-sans-pro', '<link href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese\" rel=\"stylesheet\">', 'font-family: \"Source Sans Pro\", Helvetica, sans-serif', 'google', 0, 0),
(32, 'Titillium Web', 'titillium-web', '<link href=\"https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">', 'font-family: \"Titillium Web\", Helvetica, sans-serif', 'google', 0, 0),
(33, 'Ubuntu', 'ubuntu', '<link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext\" rel=\"stylesheet\">', 'font-family: \"Ubuntu\", Helvetica, sans-serif', 'google', 0, 0),
(34, 'Verdana', 'verdana', NULL, 'font-family: Verdana, Helvetica, sans-serif', 'local', 0, 1),
(35, 'Source Sans 3', 'source-sans-3', NULL, 'font-family: \"Source Sans 3\", Helvetica, sans-serif', 'local', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_albums`
--

CREATE TABLE `gallery_albums` (
  `id` int(11) NOT NULL,
  `lang_id` tinyint(4) DEFAULT 1,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_categories`
--

CREATE TABLE `gallery_categories` (
  `id` int(11) NOT NULL,
  `lang_id` tinyint(4) DEFAULT 1,
  `name` varchar(255) DEFAULT NULL,
  `album_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL,
  `lang_id` tinyint(4) DEFAULT 1,
  `title` varchar(500) DEFAULT NULL,
  `album_id` int(11) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL,
  `path_big` varchar(255) DEFAULT NULL,
  `path_small` varchar(255) DEFAULT NULL,
  `is_album_cover` tinyint(1) NOT NULL DEFAULT 0,
  `storage` varchar(20) DEFAULT 'local',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` int(11) NOT NULL,
  `site_lang` tinyint(4) NOT NULL DEFAULT 1,
  `layout` varchar(30) DEFAULT 'layout_1',
  `dark_mode` tinyint(1) DEFAULT 0,
  `admin_route` varchar(255) DEFAULT 'admin',
  `timezone` varchar(255) DEFAULT 'America/New_York',
  `slider_active` tinyint(1) DEFAULT 1,
  `site_color` varchar(30) DEFAULT '#6366f1',
  `show_pageviews` tinyint(1) DEFAULT 1,
  `show_rss` tinyint(1) DEFAULT 1,
  `rss_content_type` varchar(50) DEFAULT 'summary',
  `file_manager_show_all_files` tinyint(1) DEFAULT 0,
  `logo_path` varchar(255) DEFAULT NULL,
  `logo_darkmode_path` varchar(255) DEFAULT NULL,
  `favicon_path` varchar(255) DEFAULT NULL,
  `facebook_app_id` varchar(500) DEFAULT NULL,
  `facebook_app_secret` varchar(500) DEFAULT NULL,
  `google_client_id` varchar(500) DEFAULT NULL,
  `google_client_secret` varchar(500) DEFAULT NULL,
  `google_analytics` text DEFAULT NULL,
  `google_adsense_code` text DEFAULT NULL,
  `mail_service` varchar(100) DEFAULT 'swift',
  `mail_protocol` varchar(100) DEFAULT 'smtp',
  `mail_encryption` varchar(100) NOT NULL DEFAULT 'tls',
  `mail_host` varchar(255) DEFAULT NULL,
  `mail_port` varchar(255) DEFAULT '587',
  `mail_username` varchar(255) DEFAULT NULL,
  `mail_password` varchar(255) DEFAULT NULL,
  `mail_title` varchar(255) DEFAULT NULL,
  `mail_reply_to` varchar(255) DEFAULT ' noreply@domain.com ',
  `mailjet_api_key` varchar(255) DEFAULT NULL,
  `mailjet_secret_key` varchar(255) DEFAULT NULL,
  `mailjet_email_address` varchar(255) DEFAULT NULL,
  `send_email_contact_messages` tinyint(1) DEFAULT 0,
  `mail_options_account` varchar(255) DEFAULT NULL,
  `facebook_comment` text DEFAULT NULL,
  `pagination_per_page` tinyint(4) DEFAULT 12,
  `menu_limit` tinyint(4) DEFAULT 9,
  `multilingual_system` tinyint(1) DEFAULT 1,
  `registration_system` tinyint(1) DEFAULT 1,
  `comment_system` tinyint(1) DEFAULT 1,
  `comment_approval_system` tinyint(1) DEFAULT 1,
  `approve_posts_before_publishing` tinyint(1) DEFAULT 1,
  `emoji_reactions` tinyint(1) DEFAULT 1,
  `auto_post_deletion` tinyint(1) DEFAULT 0,
  `auto_post_deletion_delete_all` tinyint(1) DEFAULT 0,
  `auto_post_deletion_days` int(11) DEFAULT 30,
  `recaptcha_site_key` varchar(500) DEFAULT NULL,
  `recaptcha_secret_key` varchar(500) DEFAULT NULL,
  `cache_system` tinyint(1) DEFAULT 0,
  `static_cache_system` tinyint(1) DEFAULT 0,
  `cache_refresh_time` int(11) DEFAULT 1800,
  `refresh_cache_database_changes` tinyint(1) DEFAULT 0,
  `image_file_format` varchar(30) DEFAULT 'JPG',
  `maintenance_mode_title` varchar(500) DEFAULT 'Coming Soon! ',
  `maintenance_mode_description` varchar(5000) DEFAULT NULL,
  `maintenance_mode_status` tinyint(1) DEFAULT 0,
  `sitemap_frequency` varchar(30) DEFAULT 'auto',
  `sitemap_last_modification` varchar(30) DEFAULT 'auto',
  `sitemap_priority` varchar(30) DEFAULT 'auto',
  `newsletter_status` tinyint(1) DEFAULT 1,
  `newsletter_popup` tinyint(1) DEFAULT 1,
  `newsletter_image` varchar(255) DEFAULT NULL,
  `custom_header_codes` mediumtext DEFAULT NULL,
  `custom_footer_codes` mediumtext DEFAULT NULL,
  `allowed_file_extensions` varchar(500) DEFAULT 'jpg,jpeg,png,gif,svg,csv,doc,docx,pdf,ppt,psd,mp4,mp3,zip',
  `default_role_id` int(11) DEFAULT 3,
  `storage` varchar(20) DEFAULT 'local',
  `aws_key` varchar(255) DEFAULT NULL,
  `aws_secret` varchar(255) DEFAULT NULL,
  `aws_bucket` varchar(255) DEFAULT NULL,
  `aws_region` varchar(255) DEFAULT NULL,
  `sidebar_categories` tinyint(1) DEFAULT 1,
  `logo_size` varchar(255) DEFAULT NULL,
  `ai_writer` text DEFAULT NULL,
  `show_home_link` tinyint(1) DEFAULT 1,
  `sticky_sidebar` tinyint(1) DEFAULT 1,
  `last_cron_update` timestamp NULL DEFAULT current_timestamp(),
  `version` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_lang`, `layout`, `dark_mode`, `admin_route`, `timezone`, `slider_active`, `site_color`, `show_pageviews`, `show_rss`, `rss_content_type`, `file_manager_show_all_files`, `logo_path`, `logo_darkmode_path`, `favicon_path`, `facebook_app_id`, `facebook_app_secret`, `google_client_id`, `google_client_secret`, `google_analytics`, `google_adsense_code`, `mail_service`, `mail_protocol`, `mail_encryption`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_title`, `mail_reply_to`, `mailjet_api_key`, `mailjet_secret_key`, `mailjet_email_address`, `send_email_contact_messages`, `mail_options_account`, `facebook_comment`, `pagination_per_page`, `menu_limit`, `multilingual_system`, `registration_system`, `comment_system`, `comment_approval_system`, `approve_posts_before_publishing`, `emoji_reactions`, `auto_post_deletion`, `auto_post_deletion_delete_all`, `auto_post_deletion_days`, `recaptcha_site_key`, `recaptcha_secret_key`, `cache_system`, `static_cache_system`, `cache_refresh_time`, `refresh_cache_database_changes`, `image_file_format`, `maintenance_mode_title`, `maintenance_mode_description`, `maintenance_mode_status`, `sitemap_frequency`, `sitemap_last_modification`, `sitemap_priority`, `newsletter_status`, `newsletter_popup`, `newsletter_image`, `custom_header_codes`, `custom_footer_codes`, `allowed_file_extensions`, `default_role_id`, `storage`, `aws_key`, `aws_secret`, `aws_bucket`, `aws_region`, `sidebar_categories`, `logo_size`, `ai_writer`, `show_home_link`, `sticky_sidebar`, `last_cron_update`, `version`) VALUES
(1, 1, 'layout_2', 0, 'admin', 'America/New_York', 1, '#6366f1', 1, 1, 'summary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'swift', 'smtp', 'tls', NULL, '587', NULL, NULL, NULL, ' noreply@domain.com ', NULL, NULL, NULL, 0, NULL, NULL, 12, 8, 1, 1, 1, 1, 1, 1, 0, 0, 30, NULL, NULL, 0, 0, 1800, 0, 'JPG', 'Coming Soon! ', NULL, 0, 'auto', 'auto', 'auto', 1, 0, NULL, NULL, NULL, 'jpg,jpeg,png,gif,svg,csv,doc,docx,pdf,ppt,psd,mp4,mp3,zip', 3, 'local', NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, 1, '2025-04-18 09:07:12', '4.5');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `image_big` varchar(255) DEFAULT NULL,
  `image_mid` varchar(255) DEFAULT NULL,
  `image_small` varchar(255) DEFAULT NULL,
  `image_mime` varchar(30) DEFAULT 'jpg',
  `file_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `storage` varchar(20) DEFAULT 'local'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `short_form` varchar(30) NOT NULL,
  `language_code` varchar(30) NOT NULL,
  `text_direction` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `language_order` tinyint(4) NOT NULL DEFAULT 1,
  `text_editor_lang` varchar(20) NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `short_form`, `language_code`, `text_direction`, `status`, `language_order`, `text_editor_lang`) VALUES
(1, 'English', 'en', 'en-US', 'ltr', 1, 1, 'en');

-- --------------------------------------------------------

--
-- Table structure for table `language_translations`
--

CREATE TABLE `language_translations` (
  `id` int(11) NOT NULL,
  `lang_id` smallint(6) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `translation` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `language_translations`
--

INSERT INTO `language_translations` (`id`, `lang_id`, `label`, `translation`) VALUES
(1, 1, 'about', 'About'),
(2, 1, 'about_me', 'About Me'),
(3, 1, 'accept_cookies', 'Accept Cookies'),
(4, 1, 'activate', 'Activate'),
(5, 1, 'activated', 'Activated'),
(6, 1, 'active', 'Active'),
(7, 1, 'additional_images', 'Additional Images'),
(8, 1, 'address', 'Address'),
(9, 1, 'add_album', 'Add Album'),
(10, 1, 'add_category', 'Add Category'),
(11, 1, 'add_font', 'Add Font'),
(12, 1, 'add_image', 'Add Image'),
(13, 1, 'add_images', 'Add images'),
(14, 1, 'add_image_url', 'Add Image Url'),
(15, 1, 'add_language', 'Add Language'),
(16, 1, 'add_link', 'Add Menu Link'),
(17, 1, 'add_option', 'Add Option'),
(18, 1, 'add_page', 'Add Page'),
(19, 1, 'add_picked', 'Add to Our Picks'),
(20, 1, 'add_poll', 'Add Poll'),
(21, 1, 'add_post', 'Add Post'),
(22, 1, 'add_posts_as_draft', 'Add Posts as Draft'),
(23, 1, 'add_reading_list', 'Add to Reading List'),
(24, 1, 'add_role', 'Add Role'),
(25, 1, 'add_slider', 'Add to Slider'),
(26, 1, 'add_tag', 'Add Tag'),
(27, 1, 'add_user', 'Add User'),
(28, 1, 'add_video', 'Add Video'),
(29, 1, 'admin', 'Admin'),
(30, 1, 'admin_emails_will_send', 'Admin emails will be sent to this address'),
(31, 1, 'admin_panel', 'Admin Panel'),
(32, 1, 'admin_panel_link', 'Admin Panel Link'),
(33, 1, 'ad_space', 'Ad Space'),
(34, 1, 'ad_spaces', 'Ad Spaces'),
(35, 1, 'ad_space_index_bottom', 'Index (Bottom)'),
(36, 1, 'ad_space_index_top', 'Index (Top)'),
(37, 1, 'ad_space_in_article', 'In-Article'),
(38, 1, 'ad_space_paragraph_exp', 'The ad will be displayed after the paragraph number you selected'),
(39, 1, 'ad_space_posts_bottom', 'Posts (Bottom)'),
(40, 1, 'ad_space_posts_exp', 'This ad will be displayed on Posts, Category, Profile, Tag, Search and Profile pages'),
(41, 1, 'ad_space_posts_top', 'Posts (Top)'),
(42, 1, 'ad_space_post_bottom', 'Post Details (Bottom)'),
(43, 1, 'ad_space_post_top', 'Post Details (Top)'),
(44, 1, 'ai_content_creator', 'AI Content Creator'),
(45, 1, 'ai_writer', 'AI Writer'),
(46, 1, 'album', 'Album'),
(47, 1, 'albums', 'Albums'),
(48, 1, 'album_cover', 'Album Cover'),
(49, 1, 'album_name', 'Album Name'),
(50, 1, 'all', 'All'),
(51, 1, 'allowed_file_extensions', 'Allowed File Extensions'),
(52, 1, 'all_permissions', 'All Permissions'),
(53, 1, 'all_posts', 'All Posts'),
(54, 1, 'all_users_can_vote', 'All Users Can Vote'),
(55, 1, 'angry', 'Angry'),
(56, 1, 'api_key', 'API Key'),
(57, 1, 'approve', 'Approve'),
(58, 1, 'approved', 'Approved'),
(59, 1, 'approve_posts_before_publishing', 'Approve Posts Before Publishing'),
(60, 1, 'app_id', 'App ID'),
(61, 1, 'app_name', 'Application Name'),
(62, 1, 'app_secret', 'App Secret'),
(63, 1, 'April', 'Apr'),
(64, 1, 'August', 'Aug'),
(65, 1, 'author', 'Author'),
(66, 1, 'automatically_calculated', 'Automatically Calculated'),
(67, 1, 'auto_post_deletion', 'Auto Post Deletion'),
(68, 1, 'auto_update', 'Auto Update'),
(69, 1, 'avatar', 'Avatar'),
(70, 1, 'aws_key', 'AWS Access Key'),
(71, 1, 'aws_secret', 'AWS Secret Key'),
(72, 1, 'aws_storage', 'AWS S3 Storage'),
(73, 1, 'back', 'Back'),
(74, 1, 'banned', 'Banned'),
(75, 1, 'banner', 'Banner'),
(76, 1, 'banner_desktop', 'Desktop Banner'),
(77, 1, 'banner_desktop_exp', 'This ad will be displayed on screens larger than 992px'),
(78, 1, 'banner_mobile', 'Mobile Banner'),
(79, 1, 'banner_mobile_exp', 'This ad will be displayed on screens smaller than 992px'),
(80, 1, 'ban_user', 'Ban User'),
(81, 1, 'browse_files', 'Browse Files'),
(82, 1, 'bucket_name', 'Bucket Name'),
(83, 1, 'cache_refresh_time', 'Cache Refresh Time (Minute)'),
(84, 1, 'cache_refresh_time_exp', 'After this time, your cache files will be refreshed.'),
(85, 1, 'cache_system', 'Cache System'),
(86, 1, 'cancel', 'Cancel'),
(87, 1, 'categories', 'Categories'),
(88, 1, 'category', 'Category'),
(89, 1, 'category_name', 'Category Name'),
(90, 1, 'change_avatar', 'Change Avatar'),
(91, 1, 'change_favicon', 'Change favicon'),
(92, 1, 'change_image', 'Change image'),
(93, 1, 'change_logo', 'Change logo'),
(94, 1, 'change_password', 'Change Password'),
(95, 1, 'change_password_error', 'There was a problem changing your password!'),
(96, 1, 'change_user_role', 'Change User Role'),
(97, 1, 'client_id', 'Client ID'),
(98, 1, 'client_secret', 'Client Secret'),
(99, 1, 'close', 'Close'),
(100, 1, 'comment', 'Comment'),
(101, 1, 'comments', 'Comments'),
(102, 1, 'comment_approval_system', 'Comment Approval System'),
(103, 1, 'comment_system', 'Comment System'),
(104, 1, 'completed', 'Completed'),
(105, 1, 'confirm_action', 'Are you sure you want to perform this action?'),
(106, 1, 'confirm_album', 'Are you sure you want to delete this album?'),
(107, 1, 'confirm_ban', 'Are you sure you want to ban this user?'),
(108, 1, 'confirm_category', 'Are you sure you want to delete this category?'),
(109, 1, 'confirm_comment', 'Are you sure you want to delete this comment?'),
(110, 1, 'confirm_comments', 'Are you sure you want to delete selected comments?'),
(111, 1, 'confirm_delete', 'Are you sure you want to delete this item?'),
(112, 1, 'confirm_email', 'Are you sure you want to delete this email address?'),
(113, 1, 'confirm_image', 'Are you sure you want to delete this image?'),
(114, 1, 'confirm_item', 'Are you sure you want to delete this item?'),
(115, 1, 'confirm_language', 'Are you sure you want to delete this language?'),
(116, 1, 'confirm_link', 'Are you sure you want to delete this link?'),
(117, 1, 'confirm_message', 'Are you sure you want to delete this message?'),
(118, 1, 'confirm_page', 'Are you sure you want to delete this page?'),
(119, 1, 'confirm_password', 'Confirm Password'),
(120, 1, 'confirm_poll', 'Are you sure you want to delete this poll?'),
(121, 1, 'confirm_post', 'Are you sure you want to delete this post?'),
(122, 1, 'confirm_posts', 'Are you sure you want to delete selected posts?'),
(123, 1, 'confirm_remove_ban', 'Are you sure you want to remove ban for this user?'),
(124, 1, 'confirm_subscriber', 'Are you sure you want to delete this subscriber?'),
(125, 1, 'confirm_user', 'Are you sure you want to delete this user?'),
(126, 1, 'connect_with_facebook', 'Connect with Facebook'),
(127, 1, 'connect_with_google', 'Connect with Google'),
(128, 1, 'contact_message', 'Contact Message'),
(129, 1, 'contact_messages', 'Contact Messages'),
(130, 1, 'contact_settings', 'Contact Settings'),
(131, 1, 'contact_text', 'Contact Text'),
(132, 1, 'content', 'Content'),
(133, 1, 'cookies_warning', 'Cookies Warning'),
(134, 1, 'copyright', 'Copyright'),
(135, 1, 'create_ad_exp', 'If you don\'t have an ad code, you can create an ad code by selecting an image and adding an URL'),
(136, 1, 'custom', 'Custom'),
(137, 1, 'custom_footer_codes', 'Custom Footer Codes'),
(138, 1, 'custom_footer_codes_exp', 'These codes will be added to the footer of the site.'),
(139, 1, 'custom_header_codes', 'Custom Header Codes'),
(140, 1, 'custom_header_codes_exp', 'These codes will be added to the header of the site.'),
(141, 1, 'dark_mode', 'Dark Mode'),
(142, 1, 'date', 'Date'),
(143, 1, 'date_publish', 'Date Publish'),
(144, 1, 'days_ago', 'days ago'),
(145, 1, 'day_ago', 'day ago'),
(146, 1, 'December', 'Dec'),
(147, 1, 'default', 'Default'),
(148, 1, 'default_language', 'Default Language'),
(149, 1, 'default_role_members', 'Default Role for New Members'),
(150, 1, 'delete', 'Delete'),
(151, 1, 'delete_account', 'Delete Account'),
(152, 1, 'delete_account_confirm', 'Deleting your account is permanent and will remove all content including comments, avatars and profile settings. Are you sure you want to delete your account?'),
(153, 1, 'delete_all', 'Delete All'),
(154, 1, 'delete_all_posts', 'Delete All Posts'),
(155, 1, 'delete_only_rss_posts', 'Delete only RSS Posts'),
(156, 1, 'delete_reading_list', 'Remove from Reading List'),
(157, 1, 'description', 'Description'),
(158, 1, 'desktop', 'Desktop'),
(159, 1, 'disable', 'Disable'),
(160, 1, 'discord', 'Discord'),
(161, 1, 'dislike', 'Dislike'),
(162, 1, 'distribute_only_post_summary', 'Distribute only Post Summary'),
(163, 1, 'distribute_post_content', 'Distribute Post Content'),
(164, 1, 'dont_want_receive_emails', 'Do not want receive these emails?'),
(165, 1, 'download', 'Download'),
(166, 1, 'download_database_backup', 'Download Database Backup'),
(167, 1, 'download_images_my_server', 'Download Images to My Server'),
(168, 1, 'drafts', 'Drafts'),
(169, 1, 'drag_drop_files_here', 'Drag and drop files here or'),
(170, 1, 'edit', 'Edit'),
(171, 1, 'edited', 'Edited'),
(172, 1, 'edit_role', 'Edit Role'),
(173, 1, 'edit_translations', 'Edit Translations'),
(174, 1, 'edit_user', 'Edit User'),
(175, 1, 'email', 'Email'),
(176, 1, 'email_address', 'Email Address'),
(177, 1, 'email_options', 'Email Options'),
(178, 1, 'email_option_contact_messages', 'Send contact messages to email address'),
(179, 1, 'email_reset_password', 'Please click on the button below to reset your password.'),
(180, 1, 'email_settings', 'Email Settings'),
(181, 1, 'email_unique_error', 'The email has already been taken.'),
(182, 1, 'emoji_reactions', 'Emoji Reactions'),
(183, 1, 'emoji_reactions_type', 'Emoji Reactions Type'),
(184, 1, 'enable', 'Enable'),
(185, 1, 'encryption', 'Encryption'),
(186, 1, 'enter_2_characters', 'Enter at least 2 characters'),
(187, 1, 'enter_topic', 'Enter topic'),
(188, 1, 'enter_url', 'Enter URL'),
(189, 1, 'export', 'Export'),
(190, 1, 'facebook', 'Facebook'),
(191, 1, 'facebook_comments', 'Facebook Comments'),
(192, 1, 'facebook_comments_code', 'Facebook Comments Plugin Code'),
(193, 1, 'favicon', 'Favicon'),
(194, 1, 'February', 'Feb'),
(195, 1, 'feed', 'Feed'),
(196, 1, 'feed_name', 'Feed Name'),
(197, 1, 'feed_url', 'Feed URL'),
(198, 1, 'files', 'Files'),
(199, 1, 'files_exp', 'Downloadable additional files (.pdf, .docx, .zip etc..)'),
(200, 1, 'file_extensions', 'File Extensions'),
(201, 1, 'file_manager', 'File Manager'),
(202, 1, 'file_upload', 'File Upload'),
(203, 1, 'filter', 'Filter'),
(204, 1, 'folder_name', 'Folder Name'),
(205, 1, 'follow', 'Follow'),
(206, 1, 'followers', 'Followers'),
(207, 1, 'following', 'Following'),
(208, 1, 'fonts', 'Fonts'),
(209, 1, 'font_family', 'Font Family'),
(210, 1, 'font_settings', 'Font Settings'),
(211, 1, 'font_source', 'Font Source'),
(212, 1, 'footer', 'Footer'),
(213, 1, 'footer_about_section', 'Footer About Section'),
(214, 1, 'forgot_password', 'Forgot Password'),
(215, 1, 'form_validation_is_unique', 'The {field} field must contain a unique value.'),
(216, 1, 'form_validation_matches', 'The {field} field does not match the {param} field.'),
(217, 1, 'form_validation_max_length', 'The {field} field cannot exceed {param} characters in length.'),
(218, 1, 'form_validation_min_length', 'The {field} field must be at least {param} characters in length.'),
(219, 1, 'form_validation_required', 'The {field} field is required.'),
(220, 1, 'frequency', 'Frequency'),
(221, 1, 'frequency_exp', 'This value indicates how frequently the content at a particular URL is likely to change '),
(222, 1, 'funny', 'Funny'),
(223, 1, 'gallery', 'Gallery'),
(224, 1, 'gallery_albums', 'Gallery Albums'),
(225, 1, 'gallery_categories', 'Gallery Categories'),
(226, 1, 'general', 'General'),
(227, 1, 'general_settings', 'General Settings'),
(228, 1, 'generate', 'Generate'),
(229, 1, 'generated_sitemaps', 'Generated Sitemaps'),
(230, 1, 'generated_text', 'Generated Text'),
(231, 1, 'generate_keywords_from_title', 'Generate Keywords from Title'),
(232, 1, 'generate_sitemap', 'Generate Sitemap'),
(233, 1, 'generate_text', 'Generate Text'),
(234, 1, 'generating_text', 'Generating text...'),
(235, 1, 'get_video', 'Get Video'),
(236, 1, 'get_video_from_url', 'Get Video from Url'),
(237, 1, 'gif_animated', 'GIF (Animated)'),
(238, 1, 'google_adsense_code', 'Google Adsense Code'),
(239, 1, 'google_analytics', 'Google Analytics'),
(240, 1, 'google_analytics_code', 'Google Analytics Code'),
(241, 1, 'google_fonts', 'Google Fonts'),
(242, 1, 'google_recaptcha', 'Google reCAPTCHA'),
(243, 1, 'go_to_home', 'Go to Homepage'),
(244, 1, 'header', 'Header'),
(245, 1, 'height', 'Height'),
(246, 1, 'hidden', 'Hidden'),
(247, 1, 'hide', 'Hide'),
(248, 1, 'home', 'Home'),
(249, 1, 'home_title', 'Home Title'),
(250, 1, 'host', 'Host'),
(251, 1, 'hours_ago', 'hours ago'),
(252, 1, 'hour_ago', 'hour ago'),
(253, 1, 'id', 'Id'),
(254, 1, 'image', 'Image'),
(255, 1, 'images', 'Images'),
(256, 1, 'image_file_format', 'Image File Format'),
(257, 1, 'import_language', 'Import Language'),
(258, 1, 'import_rss_feed', 'Import RSS Feed'),
(259, 1, 'inactive', 'Inactive'),
(260, 1, 'index', 'Index'),
(261, 1, 'instagram', 'Instagram'),
(262, 1, 'invalid_attempt', 'Invalid attempt!'),
(263, 1, 'invalid_file_type', 'Invalid File Type!'),
(264, 1, 'ip_address', 'IP Address'),
(265, 1, 'January', 'Jan'),
(266, 1, 'join_newsletter', 'Join Our Newsletter'),
(267, 1, 'json_language_file', 'JSON Language File'),
(268, 1, 'July', 'Jul'),
(269, 1, 'June', 'Jun'),
(270, 1, 'just_now', 'Just Now'),
(271, 1, 'keywords', 'Keywords'),
(272, 1, 'language', 'Language'),
(273, 1, 'languages', 'Languages'),
(274, 1, 'language_code', 'Language Code'),
(275, 1, 'language_name', 'Language Name'),
(276, 1, 'language_settings', 'Language Settings'),
(277, 1, 'last_modification', 'Last Modification'),
(278, 1, 'last_modification_exp', 'The time the URL was last modified'),
(279, 1, 'last_seen', 'Last seen:'),
(280, 1, 'latest_posts', 'Latest Posts'),
(281, 1, 'layout', 'Layout'),
(282, 1, 'leave_message', 'Leave a Message'),
(283, 1, 'leave_reply', 'Leave a Reply'),
(284, 1, 'leave_your_comment', 'Leave your comment...'),
(285, 1, 'left_to_right', 'Left to Right'),
(286, 1, 'length_of_text', 'Length of Text'),
(287, 1, 'light_mode', 'Light Mode'),
(288, 1, 'like', 'Like'),
(289, 1, 'link', 'Link'),
(290, 1, 'linkedin', 'Linkedin'),
(291, 1, 'load_more_comments', 'Load More Comments'),
(292, 1, 'load_more_posts', 'Load More Posts'),
(293, 1, 'local', 'Local'),
(294, 1, 'local_storage', 'Local Storage'),
(295, 1, 'location', 'Location'),
(296, 1, 'login', 'Login'),
(297, 1, 'login_error', 'Wrong username or password!'),
(298, 1, 'logo', 'Logo'),
(299, 1, 'logout', 'Logout'),
(300, 1, 'logo_email', 'Logo Email'),
(301, 1, 'logo_size', 'Logo Size'),
(302, 1, 'logo_size_exp', 'For better logo quality, you can upload your logo in slightly larger sizes and set smaller sizes by keeping the image ratio the same'),
(303, 1, 'long', 'Long'),
(304, 1, 'love', 'Love'),
(305, 1, 'mail', 'Mail'),
(306, 1, 'mailjet_email_address', 'Mailjet Email Address'),
(307, 1, 'mailjet_email_address_exp', 'The address you created your Mailjet account with'),
(308, 1, 'mail_is_being_sent', 'Mail is being sent. Please do not close this page until the process is finished!'),
(309, 1, 'mail_library', 'Mail Library'),
(310, 1, 'mail_service', 'Mail Service'),
(311, 1, 'mail_title', 'Mail Title'),
(312, 1, 'maintenance_mode', 'Maintenance Mode'),
(313, 1, 'main_image', 'Main Image'),
(314, 1, 'main_menu', 'Main Menu'),
(315, 1, 'main_nav', 'MAIN NAVIGATION'),
(316, 1, 'main_post_image', 'Main post image'),
(317, 1, 'manage_all_posts', 'Manage All Posts'),
(318, 1, 'manage_tags', 'Manage Tags'),
(319, 1, 'March', 'Mar'),
(320, 1, 'May', 'May'),
(321, 1, 'medium', 'Medium'),
(322, 1, 'membership', 'Membership'),
(323, 1, 'member_since', 'Member since'),
(324, 1, 'menu_limit', 'Menu Limit'),
(325, 1, 'message', 'Message'),
(326, 1, 'message_ban_error', 'Your account has been banned!'),
(327, 1, 'message_change_password', 'Your password has been successfully changed!'),
(328, 1, 'message_contact_error', 'There was a problem while sending your message!'),
(329, 1, 'message_contact_success', 'Your message has been sent successfully!'),
(330, 1, 'message_invalid_email', 'Invalid email address!'),
(331, 1, 'message_login_for_comment', 'You need to login to write a comment!'),
(332, 1, 'message_newsletter_error', 'Your email address is already registered!'),
(333, 1, 'message_newsletter_success', 'Your email address has been successfully added!'),
(334, 1, 'message_page_auth', 'You must be logged in to view this page!'),
(335, 1, 'message_post_auth', 'You must be logged in to view this post!'),
(336, 1, 'message_profile', 'Profile updated successfully!'),
(337, 1, 'message_register_error', 'There was a problem during registration. Please try again!'),
(338, 1, 'message_slug_error', 'The slug you entered is being used by another user!'),
(339, 1, 'meta_tag', 'Meta Tag'),
(340, 1, 'minutes_ago', 'minutes ago'),
(341, 1, 'minute_ago', 'minute ago'),
(342, 1, 'mobile', 'Mobile'),
(343, 1, 'model', 'Model'),
(344, 1, 'months_ago', 'months ago'),
(345, 1, 'month_ago', 'month ago'),
(346, 1, 'more', 'More'),
(347, 1, 'more_info', 'More info'),
(348, 1, 'more_main_images', 'More main images (slider will be active)'),
(349, 1, 'msg_add_picked', 'Post added to our picks!'),
(350, 1, 'msg_add_slider', 'Post added to slider!'),
(351, 1, 'msg_ban_removed', 'User ban successfully removed!'),
(352, 1, 'msg_comment_approved', 'Comment successfully approved!'),
(353, 1, 'msg_comment_sent_successfully', 'Your comment has been sent. It will be published after being reviewed by the site management.'),
(354, 1, 'msg_cron_feed', 'With this URL you can automatically update your feeds.'),
(355, 1, 'msg_cron_sitemap', 'With this URL you can automatically update your sitemap.'),
(356, 1, 'msg_deleted', 'Item successfully deleted!'),
(357, 1, 'msg_delete_album', 'Please delete categories belonging to this album first!'),
(358, 1, 'msg_delete_images', 'Please delete images belonging to this category first!'),
(359, 1, 'msg_delete_posts', 'Please delete posts belonging to this category first!'),
(360, 1, 'msg_delete_subcategories', 'Please delete subcategories belonging to this category first!'),
(361, 1, 'msg_email_sent', 'Email successfully sent!'),
(362, 1, 'msg_error', 'An error occurred please try again!'),
(363, 1, 'msg_img_uploaded', 'Image Successfully Uploaded!'),
(364, 1, 'msg_item_added', 'Item successfully added!'),
(365, 1, 'msg_language_delete', 'Default language cannot be deleted!'),
(366, 1, 'msg_page_delete', 'Default pages can not be deleted!'),
(367, 1, 'msg_page_slug_error', 'Invalid page slug!'),
(368, 1, 'msg_post_approved', 'Post approved!'),
(369, 1, 'msg_published', 'Post successfully published!'),
(370, 1, 'msg_recaptcha', 'Please confirm that you are not a robot!'),
(371, 1, 'msg_register_success', 'Your account has been created successfully!'),
(372, 1, 'msg_remove_picked', 'Post removed from our picks!'),
(373, 1, 'msg_remove_slider', 'Post removed from slider!'),
(374, 1, 'msg_request_sent', 'The request has been sent successfully!'),
(375, 1, 'msg_reset_cache', 'All cache files have been deleted!'),
(376, 1, 'msg_reset_password_success', 'We\'ve sent an email for resetting your password to your email address. Please check your email for next steps.'),
(377, 1, 'msg_role_changed', 'User role successfully changed!'),
(378, 1, 'msg_rss_warning', 'If you chose to download the images to your server, adding posts will take more time and will use more resources. If you see any problems, increase \'max_execution_time\' and \'memory_limit\' values from your server settings.'),
(379, 1, 'msg_slug_used', 'The slug you entered is being used by another user!'),
(380, 1, 'msg_subscriber_deleted', 'Subscriber successfully deleted!'),
(381, 1, 'msg_tag_exists', 'This tag already exists!'),
(382, 1, 'msg_topic_empty', 'Topic cannot be empty!'),
(383, 1, 'msg_unsubscribe', 'You will no longer receive emails from us!'),
(384, 1, 'msg_updated', 'Changes successfully saved!'),
(385, 1, 'msg_username_unique_error', 'The username has already been taken.'),
(386, 1, 'msg_user_added', 'User successfully added!'),
(387, 1, 'msg_user_banned', 'User successfully banned!'),
(388, 1, 'multilingual_system', 'Multilingual System'),
(389, 1, 'my_posts', 'My Posts'),
(390, 1, 'name', 'Name'),
(391, 1, 'navigation', 'Navigation'),
(392, 1, 'nav_drag_warning', 'You cannot drag a category below a page or a page below a category link!'),
(393, 1, 'newsletter', 'Newsletter'),
(394, 1, 'newsletter_desc', 'Join our subscribers list to get the latest news, updates and special offers directly in your inbox'),
(395, 1, 'newsletter_email_error', 'Select email addresses that you want to send mail!'),
(396, 1, 'newsletter_exp', 'Subscribe here to get interesting stuff and updates!'),
(397, 1, 'newsletter_popup', 'Newsletter Popup'),
(398, 1, 'newsletter_send_many_exp', 'Some servers do not allow mass mailing. Therefore, instead of sending your mails to all subscribers at once, you can send them part by part (Example: 50 subscribers at once). If your mail server stops sending mail, the sending process will also stop.'),
(399, 1, 'new_password', 'New Password'),
(400, 1, 'no', 'No'),
(401, 1, 'none', 'None'),
(402, 1, 'November', 'Nov'),
(403, 1, 'no_results_found', 'No results found.'),
(404, 1, 'number_of_days', 'Number of Days'),
(405, 1, 'number_of_days_exp', 'If you add 30 here, the system will delete posts older than 30 days'),
(406, 1, 'number_of_links_in_menu', 'The number of links that appear in the menu'),
(407, 1, 'number_of_posts_import', 'Number of Posts to Import'),
(408, 1, 'number_short_billion', 'b'),
(409, 1, 'number_short_million', 'm'),
(410, 1, 'number_short_thousand', 'k'),
(411, 1, 'October', 'Oct'),
(412, 1, 'ok', 'OK'),
(413, 1, 'old_password', 'Old Password'),
(414, 1, 'online', 'Online'),
(415, 1, 'option', 'Option'),
(416, 1, 'optional', 'Optional'),
(417, 1, 'optional_url', 'Optional Url'),
(418, 1, 'optional_url_name', 'Post Optional Url Button Name'),
(419, 1, 'options', 'Options'),
(420, 1, 'or', 'or'),
(421, 1, 'order', 'Order'),
(422, 1, 'or_login_with_email', 'Or login with email'),
(423, 1, 'or_register_with_email', 'Or register with email'),
(424, 1, 'our_picks', 'Our Picks'),
(425, 1, 'page', 'Page'),
(426, 1, 'pages', 'Pages'),
(427, 1, 'pageviews', 'Pageviews'),
(428, 1, 'page_not_found', 'Page not found'),
(429, 1, 'page_not_found_sub', 'The page you are looking for doesn\'t exist.'),
(430, 1, 'page_type', 'Page Type'),
(431, 1, 'pagination_number_posts', 'Number of Posts Per Page (Pagination)'),
(432, 1, 'panel', 'Panel'),
(433, 1, 'paragraph', 'Paragraph'),
(434, 1, 'parent_category', 'Parent Category'),
(435, 1, 'parent_link', 'Parent Link'),
(436, 1, 'password', 'Password'),
(437, 1, 'paste_ad_code', 'Ad Code'),
(438, 1, 'paste_ad_url', 'Ad Url'),
(439, 1, 'pending', 'Pending'),
(440, 1, 'pending_comments', 'Pending Comments'),
(441, 1, 'pending_posts', 'Pending Posts'),
(442, 1, 'permissions', 'Permissions'),
(443, 1, 'personal_website_url', 'Personal Website URL'),
(444, 1, 'phone', 'Phone'),
(445, 1, 'phrases', 'Phrases'),
(446, 1, 'pinterest', 'Pinterest'),
(447, 1, 'please_select_option', 'Please select an option!'),
(448, 1, 'png_not_animated', 'PNG (Not Animated)'),
(449, 1, 'poll', 'Poll'),
(450, 1, 'polls', 'Polls'),
(451, 1, 'popular_posts', 'Popular Posts'),
(452, 1, 'popular_tags', 'Popular Tags'),
(453, 1, 'port', 'Port'),
(454, 1, 'post', 'Post'),
(455, 1, 'posts', 'Posts'),
(456, 1, 'post_comment', 'Post Comment'),
(457, 1, 'post_details', 'Post Details'),
(458, 1, 'post_owner', 'Post Owner'),
(459, 1, 'post_type', 'Post Type'),
(460, 1, 'preferences', 'Preferences'),
(461, 1, 'preview', 'Preview'),
(462, 1, 'primary_font', 'Primary Font (Main)'),
(463, 1, 'priority', 'Priority'),
(464, 1, 'priority_exp', 'The priority of a particular URL relative to other pages on the same site'),
(465, 1, 'profile', 'Profile'),
(466, 1, 'protocol', 'Protocol'),
(467, 1, 'publish', 'Publish'),
(468, 1, 'question', 'Question'),
(469, 1, 'random_posts', 'Random Posts'),
(470, 1, 'reading_list', 'Reading List'),
(471, 1, 'reading_list_empty', 'Your reading list is empty.'),
(472, 1, 'readmore', 'Read More'),
(473, 1, 'read_more_button_text', 'Read More Button Text'),
(474, 1, 'recently_added_comments', 'Recently added comments'),
(475, 1, 'recently_added_contact_messages', 'Recently added contact messages'),
(476, 1, 'recently_added_unapproved_comments', 'Recently added unapproved comments'),
(477, 1, 'recently_registered_users', 'Recently registered users'),
(478, 1, 'refresh', 'Refresh'),
(479, 1, 'refresh_cache_database_changes', 'Refresh Cache Files When Database Changes'),
(480, 1, 'regenerate', 'Regenerate'),
(481, 1, 'region_code', 'Region Code'),
(482, 1, 'register', 'Register'),
(483, 1, 'registered_emails', 'Registered Emails'),
(484, 1, 'registration_system', 'Registration System'),
(485, 1, 'related_posts', 'Related Posts'),
(486, 1, 'remove_ban', 'Remove Ban'),
(487, 1, 'remove_picked', 'Remove From Our Picks'),
(488, 1, 'remove_slider', 'Remove From Slider'),
(489, 1, 'reply', 'Reply'),
(490, 1, 'reply_to', 'Reply to'),
(491, 1, 'reset', 'Reset'),
(492, 1, 'reset_cache', 'Reset Cache'),
(493, 1, 'reset_password', 'Reset Password'),
(494, 1, 'reset_password_error', 'We can\'t find a user with that e-mail address!'),
(495, 1, 'right_to_left', 'Right to Left'),
(496, 1, 'role', 'Role'),
(497, 1, 'roles', 'Roles'),
(498, 1, 'roles_permissions', 'Roles Permissions'),
(499, 1, 'role_name', 'Role Name'),
(500, 1, 'rss', 'RSS'),
(501, 1, 'rss_content', 'RSS Content'),
(502, 1, 'rss_feeds', 'RSS Feeds'),
(503, 1, 'sad', 'Sad'),
(504, 1, 'save', 'Save'),
(505, 1, 'save_changes', 'Save Changes'),
(506, 1, 'save_draft', 'Save as Draft'),
(507, 1, 'search', 'Search'),
(508, 1, 'search_exp', 'Search...'),
(509, 1, 'secondary_font', 'Secondary Font (Titles)'),
(510, 1, 'secret_key', 'Secret Key'),
(511, 1, 'select', 'Select'),
(512, 1, 'select_ad_spaces', 'Select Ad Space'),
(513, 1, 'select_file', 'Select File'),
(514, 1, 'select_image', 'Select image'),
(515, 1, 'select_images', 'Select images'),
(516, 1, 'select_multiple_images', 'You can select multiple images.'),
(517, 1, 'select_option', 'Select an option'),
(518, 1, 'send_email', 'Send Email'),
(519, 1, 'send_reset_link', 'Send Password Reset Link'),
(520, 1, 'send_test_email', 'Send Test Email'),
(521, 1, 'send_test_email_exp', 'You can send a test mail to check if your mail server is working.'),
(522, 1, 'seo_options', 'SEO options'),
(523, 1, 'seo_tools', 'SEO Tools'),
(524, 1, 'September', 'Sep'),
(525, 1, 'settings', 'Settings'),
(526, 1, 'settings_language', 'Settings Language'),
(527, 1, 'set_as_album_cover', 'Set as Album Cover'),
(528, 1, 'set_as_default', 'Set as Default'),
(529, 1, 'share', 'Share'),
(530, 1, 'shared', 'Shared'),
(531, 1, 'short', 'Short'),
(532, 1, 'short_form', 'Short Form'),
(533, 1, 'show', 'Show'),
(534, 1, 'show_all_files', 'Show all Files'),
(535, 1, 'show_breadcrumb', 'Show Breadcrumb'),
(536, 1, 'show_categories_sidebar', 'Show Categories on Sidebar'),
(537, 1, 'show_cookies_warning', 'Show Cookies Warning'),
(538, 1, 'show_email_on_profile', 'Show Email on Profile Page'),
(539, 1, 'show_images_from_original_source', 'Show Images from Original Source'),
(540, 1, 'show_only_own_files', 'Show Only Users Own Files'),
(541, 1, 'show_only_registered', 'Show Only to Registered Users'),
(542, 1, 'show_on_menu', 'Show on Menu'),
(543, 1, 'show_post_view_counts', 'Show Post View Counts'),
(544, 1, 'show_read_more_button', 'Show Read More Button'),
(545, 1, 'show_right_column', 'Show Right Column'),
(546, 1, 'show_title', 'Show Title'),
(547, 1, 'sidebar', 'Sidebar'),
(548, 1, 'sitemap', 'Sitemap'),
(549, 1, 'sitemap_generate_exp', 'If your site has more than 49,000 links, the sitemap.xml file will be created in parts.'),
(550, 1, 'site_color', 'Site Color'),
(551, 1, 'site_description', 'Site Description'),
(552, 1, 'site_font', 'Site Font'),
(553, 1, 'site_key', 'Site Key'),
(554, 1, 'site_language', 'Site Language'),
(555, 1, 'site_title', 'Site Title'),
(556, 1, 'slider', 'Slider'),
(557, 1, 'slider_order', 'Slider Order'),
(558, 1, 'slider_posts', 'Slider Posts'),
(559, 1, 'slug', 'Slug'),
(560, 1, 'slug_exp', 'If you leave it blank, it will be generated automatically.'),
(561, 1, 'smtp', 'SMTP'),
(562, 1, 'social_accounts', 'Social Accounts'),
(563, 1, 'social_login_settings', 'Social Login Settings'),
(564, 1, 'social_media', 'Social Media'),
(565, 1, 'social_media_settings', 'Social Media Settings'),
(566, 1, 'static_cache_system', 'Static Cache System'),
(567, 1, 'status', 'Status'),
(568, 1, 'sticky_sidebar', 'Sticky Sidebar'),
(569, 1, 'storage', 'Storage'),
(570, 1, 'subcategory', 'Subcategory'),
(571, 1, 'subject', 'Subject'),
(572, 1, 'submit', 'Submit'),
(573, 1, 'subscribe', 'Subscribe'),
(574, 1, 'subscribers', 'Subscribers'),
(575, 1, 'summary', 'Summary'),
(576, 1, 'tag', 'Tag'),
(577, 1, 'tags', 'Tags'),
(578, 1, 'telegram', 'Telegram'),
(579, 1, 'temperature_response_diversity', 'Temperature (Response Diversity)'),
(580, 1, 'terms_conditions', 'Terms & Conditions'),
(581, 1, 'terms_conditions_exp', 'I have read and agree to the'),
(582, 1, 'tertiary_font', 'Tertiary Font (Post & Page Text)'),
(583, 1, 'text_direction', 'Text Direction'),
(584, 1, 'text_editor_language', 'Text Editor Language'),
(585, 1, 'themes', 'Themes'),
(586, 1, 'theme_settings', 'Theme Settings'),
(587, 1, 'tiktok', 'Tiktok'),
(588, 1, 'timezone', 'Timezone'),
(589, 1, 'title', 'Title'),
(590, 1, 'tone_academic', 'Academic'),
(591, 1, 'tone_casual', 'Casual'),
(592, 1, 'tone_critical', 'Critical'),
(593, 1, 'tone_formal', 'Formal'),
(594, 1, 'tone_humorous', 'Humorous'),
(595, 1, 'tone_inspirational', 'Inspirational'),
(596, 1, 'tone_persuasive', 'Persuasive'),
(597, 1, 'tone_professional', 'Professional'),
(598, 1, 'tone_style', 'Tone/Style'),
(599, 1, 'topic', 'Topic'),
(600, 1, 'top_menu', 'Top Menu'),
(601, 1, 'total_vote', 'Total Vote:'),
(602, 1, 'translation', 'Translation'),
(603, 1, 'twitch', 'Twitch'),
(604, 1, 'twitter', 'X (Twitter)'),
(605, 1, 'txt_processing', 'Processing...'),
(606, 1, 'type_tag', 'Type tag and hit enter'),
(607, 1, 'unfollow', 'Unfollow'),
(608, 1, 'unsubscribe', 'Unsubscribe'),
(609, 1, 'unsubscribe_successful', 'Unsubscribe Successful!'),
(610, 1, 'update', 'Update'),
(611, 1, 'update_album', 'Update Album'),
(612, 1, 'update_category', 'Update Category'),
(613, 1, 'update_font', 'Update Font'),
(614, 1, 'update_image', 'Update Image'),
(615, 1, 'update_language', 'Update Language'),
(616, 1, 'update_link', 'Update Menu Link'),
(617, 1, 'update_page', 'Update Page'),
(618, 1, 'update_poll', 'Update Poll'),
(619, 1, 'update_post', 'Update Post'),
(620, 1, 'update_profile', 'Update Profile'),
(621, 1, 'update_rss_feed', 'Update Rss Feed'),
(622, 1, 'update_video', 'Update Video'),
(623, 1, 'uploading', 'Uploading...'),
(624, 1, 'upload_image', 'Upload Image'),
(625, 1, 'upload_your_banner', 'Create Ad Code'),
(626, 1, 'url', 'Url'),
(627, 1, 'user', 'User'),
(628, 1, 'username', 'Username'),
(629, 1, 'username_or_email', 'Username or email'),
(630, 1, 'users', 'Users'),
(631, 1, 'use_text', 'Use Text'),
(632, 1, 'very_long', 'Very Long'),
(633, 1, 'very_short', 'Very Short'),
(634, 1, 'video', 'Video'),
(635, 1, 'video_embed_code', 'Video Embed Code'),
(636, 1, 'video_image', 'Video Image'),
(637, 1, 'video_thumbnails', 'Video Thumbnails'),
(638, 1, 'video_url', 'Video Url'),
(639, 1, 'view_all', 'View All'),
(640, 1, 'view_options', 'View Options'),
(641, 1, 'view_post', 'View Post'),
(642, 1, 'view_results', 'View Results'),
(643, 1, 'view_site', 'View Site'),
(644, 1, 'visibility', 'Visibility'),
(645, 1, 'visual_settings', 'Visual Settings'),
(646, 1, 'vk', 'VKontakte'),
(647, 1, 'vote', 'Vote'),
(648, 1, 'voted_message', 'You already voted this poll before.'),
(649, 1, 'voting_poll', 'Voting Poll'),
(650, 1, 'warning', 'Warning!'),
(651, 1, 'whatsapp', 'Whatsapp'),
(652, 1, 'whats_your_reaction', 'What\'s Your Reaction?'),
(653, 1, 'width', 'Width'),
(654, 1, 'wow', 'Wow'),
(655, 1, 'wrong_password', 'Wrong password!'),
(656, 1, 'wrong_password_error', 'Wrong old password!'),
(657, 1, 'years_ago', 'years ago'),
(658, 1, 'year_ago', 'year ago'),
(659, 1, 'yes', 'Yes'),
(660, 1, 'youtube', 'Youtube');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `lang_id` tinyint(4) DEFAULT 1,
  `title` varchar(500) DEFAULT NULL,
  `page_description` varchar(500) DEFAULT NULL,
  `page_keywords` varchar(500) DEFAULT NULL,
  `slug` varchar(500) DEFAULT NULL,
  `is_custom` tinyint(1) DEFAULT 1,
  `page_content` longtext DEFAULT NULL,
  `page_order` int(11) DEFAULT 5,
  `page_active` tinyint(1) DEFAULT 1,
  `title_active` tinyint(1) DEFAULT 1,
  `breadcrumb_active` tinyint(1) DEFAULT 1,
  `right_column_active` tinyint(1) DEFAULT 1,
  `need_auth` tinyint(1) DEFAULT 0,
  `location` varchar(255) DEFAULT 'header',
  `link` varchar(1000) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `lang_id`, `title`, `page_description`, `page_keywords`, `slug`, `is_custom`, `page_content`, `page_order`, `page_active`, `title_active`, `breadcrumb_active`, `right_column_active`, `need_auth`, `location`, `link`, `parent_id`, `created_at`) VALUES
(1, 1, 'Gallery', 'Gallery Page', 'gallery, infinite', 'gallery', 0, NULL, 5, 1, 1, 1, 0, 0, 'header', NULL, 0, '2025-04-18 09:18:45'),
(2, 1, 'Contact', 'Contact Page', 'contact, infinite', 'contact', 0, NULL, 1, 1, 1, 1, 0, 0, 'top', NULL, 0, '2025-04-18 09:18:45'),
(3, 1, 'Terms & Conditions', 'Terms & Conditions Page', 'terms, conditions, infinite', 'terms-conditions', 0, NULL, 1, 1, 1, 1, 0, 0, 'footer', NULL, 0, '2025-04-18 09:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `lang_id` tinyint(4) DEFAULT 1,
  `question` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poll_options`
--

CREATE TABLE `poll_options` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `option_text` varchar(500) DEFAULT NULL,
  `votes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `lang_id` tinyint(4) DEFAULT 1,
  `title` varchar(500) DEFAULT NULL,
  `slug` varchar(500) DEFAULT NULL,
  `title_hash` varchar(500) DEFAULT NULL,
  `summary` varchar(1000) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `keywords` varchar(500) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `is_slider` tinyint(1) DEFAULT 0,
  `is_picked` tinyint(1) DEFAULT 0,
  `pageviews` int(11) DEFAULT 0,
  `comment_count` int(11) DEFAULT 0,
  `slider_order` tinyint(4) DEFAULT 0,
  `optional_url` varchar(1000) DEFAULT NULL,
  `post_type` varchar(30) DEFAULT 'post',
  `video_url` varchar(1000) DEFAULT NULL,
  `video_embed_code` varchar(1000) DEFAULT NULL,
  `image_url` varchar(1000) DEFAULT NULL,
  `need_auth` tinyint(1) DEFAULT 0,
  `feed_id` int(11) DEFAULT NULL,
  `post_url` varchar(1000) DEFAULT NULL,
  `show_post_url` tinyint(1) DEFAULT 1,
  `visibility` tinyint(1) DEFAULT 1,
  `status` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_files`
--

CREATE TABLE `post_files` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `storage` varchar(20) DEFAULT 'local'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `id` bigint(20) NOT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reactions`
--

CREATE TABLE `reactions` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `re_like` int(11) DEFAULT 0,
  `re_dislike` int(11) DEFAULT 0,
  `re_love` int(11) DEFAULT 0,
  `re_funny` int(11) DEFAULT 0,
  `re_angry` int(11) DEFAULT 0,
  `re_sad` int(11) DEFAULT 0,
  `re_wow` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reading_lists`
--

CREATE TABLE `reading_lists` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `id` int(11) NOT NULL,
  `role_name` text DEFAULT NULL,
  `permissions` text DEFAULT NULL,
  `is_super_admin` tinyint(1) DEFAULT 0,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_author` tinyint(1) DEFAULT 0,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles_permissions`
--

INSERT INTO `roles_permissions` (`id`, `role_name`, `permissions`, `is_super_admin`, `is_admin`, `is_author`, `is_default`) VALUES
(1, 'a:1:{i:0;a:2:{s:7:\"lang_id\";s:1:\"1\";s:4:\"name\";s:11:\"Super Admin\";}}', 'all', 1, 1, 1, 1),
(2, 'a:1:{i:0;a:2:{s:7:\"lang_id\";s:1:\"1\";s:4:\"name\";s:6:\"Author\";}}', '2', 0, 0, 1, 1),
(3, 'a:1:{i:0;a:2:{s:7:\"lang_id\";s:1:\"1\";s:4:\"name\";s:6:\"Member\";}}', '', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rss_feeds`
--

CREATE TABLE `rss_feeds` (
  `id` int(11) NOT NULL,
  `lang_id` int(11) DEFAULT 1,
  `feed_name` varchar(500) DEFAULT NULL,
  `feed_url` varchar(1000) DEFAULT NULL,
  `post_limit` smallint(6) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image_saving_method` varchar(30) DEFAULT 'url',
  `generate_keywords_from_title` tinyint(1) NOT NULL DEFAULT 1,
  `auto_update` tinyint(1) DEFAULT 1,
  `read_more_button` tinyint(1) DEFAULT 1,
  `read_more_button_text` varchar(255) DEFAULT 'Read More',
  `user_id` int(11) DEFAULT NULL,
  `add_posts_as_draft` tinyint(1) DEFAULT 0,
  `is_cron_updated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `lang_id` tinyint(4) DEFAULT 1,
  `application_name` varchar(255) DEFAULT 'Infinite',
  `site_title` varchar(255) DEFAULT NULL,
  `home_title` varchar(255) DEFAULT NULL,
  `site_description` varchar(500) DEFAULT NULL,
  `keywords` varchar(500) DEFAULT NULL,
  `primary_font` smallint(6) DEFAULT 20,
  `secondary_font` smallint(6) DEFAULT 10,
  `optional_url_button_name` varchar(500) DEFAULT 'Click Here to Visit',
  `about_footer` varchar(1000) DEFAULT NULL,
  `contact_text` text DEFAULT NULL,
  `contact_address` varchar(500) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `copyright` varchar(500) DEFAULT 'Copyright 2025 Infinite - All Rights Reserved.',
  `cookies_warning` tinyint(1) DEFAULT 0,
  `cookies_warning_text` text DEFAULT NULL,
  `social_media_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `lang_id`, `application_name`, `site_title`, `home_title`, `site_description`, `keywords`, `primary_font`, `secondary_font`, `optional_url_button_name`, `about_footer`, `contact_text`, `contact_address`, `contact_email`, `contact_phone`, `copyright`, `cookies_warning`, `cookies_warning_text`, `social_media_data`) VALUES
(1, 1, 'Infinite', NULL, NULL, NULL, NULL, 20, 10, 'Click Here to Visit', NULL, NULL, NULL, NULL, NULL, 'Copyright 2025 Infinite - All Rights Reserved.', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `tag_slug` varchar(255) DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT 'name@domain.com',
  `password` varchar(255) DEFAULT NULL,
  `role_id` smallint(6) DEFAULT 3,
  `user_type` varchar(30) DEFAULT 'registered',
  `google_id` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `about_me` varchar(5000) DEFAULT NULL,
  `social_media_data` text DEFAULT NULL,
  `last_seen` timestamp NULL DEFAULT NULL,
  `show_email_on_profile` tinyint(1) DEFAULT 1,
  `show_rss_feeds` tinyint(1) DEFAULT 0,
  `session_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ad_spaces`
--
ALTER TABLE `ad_spaces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lang_id` (`lang_id`),
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`),
  ADD KEY `ci_sessions_id` (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_comments_optimized` (`post_id`,`parent_id`,`status`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_following_id` (`following_id`),
  ADD KEY `idx_follower_id` (`follower_id`);

--
-- Indexes for table `fonts`
--
ALTER TABLE `fonts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_categories`
--
ALTER TABLE `gallery_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language_translations`
--
ALTER TABLE `language_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lang_id` (`lang_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lang_id` (`lang_id`),
  ADD KEY `idx_title_slug` (`slug`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_image_id` (`image_id`),
  ADD KEY `idx_is_slider` (`is_slider`),
  ADD KEY `idx_is_picked` (`is_picked`),
  ADD KEY `idx_pageviews` (`pageviews`),
  ADD KEY `idx_slider_order` (`slider_order`),
  ADD KEY `idx_post_type` (`post_type`),
  ADD KEY `idx_visibility` (`visibility`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_posts_optimized` (`visibility`,`status`,`lang_id`,`user_id`,`image_id`),
  ADD KEY `idx_posts_category` (`category_id`,`visibility`,`status`,`lang_id`,`user_id`,`image_id`),
  ADD KEY `idx_posts_slider` (`is_slider`,`visibility`,`status`,`lang_id`,`slider_order`),
  ADD KEY `idx_posts_our_picks` (`is_picked`,`visibility`,`status`,`lang_id`,`created_at`);
ALTER TABLE `posts` ADD FULLTEXT KEY `idx_fulltext` (`title`,`summary`,`content`);

--
-- Indexes for table `post_files`
--
ALTER TABLE `post_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_tag_post` (`tag_id`,`post_id`);

--
-- Indexes for table `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reading_lists`
--
ALTER TABLE `reading_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rss_feeds`
--
ALTER TABLE `rss_feeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ad_spaces`
--
ALTER TABLE `ad_spaces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fonts`
--
ALTER TABLE `fonts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `gallery_albums`
--
ALTER TABLE `gallery_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery_categories`
--
ALTER TABLE `gallery_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `language_translations`
--
ALTER TABLE `language_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=661;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_files`
--
ALTER TABLE `post_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_tags`
--
ALTER TABLE `post_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reading_lists`
--
ALTER TABLE `reading_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rss_feeds`
--
ALTER TABLE `rss_feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
