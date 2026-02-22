<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST["btnUpdate"])) {
    $data = [
        'db_host' => $_POST['db_host'],
        'db_user' => $_POST['db_user'],
        'db_password' => $_POST['db_password'],
        'db_name' => $_POST['db_name']
    ];
    try {
        $connection = new mysqli($data['db_host'], $data['db_user'], $data['db_password'], $data['db_name']);
        if ($connection->connect_error) {
            $error = "Failed to connect to database, please check your database credentials!";
        } else {
            $connection->query("SET CHARACTER SET utf8mb4");
            $connection->query("SET NAMES utf8mb4");

            update($connection);
            $success = 'The update has been successfully completed!<br> Please close this tab and delete the "update_database.php" file.';
            $connection->close();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

function runQuery($sql)
{
    global $connection;
    return mysqli_query($connection, $sql);
}

if (isset($_POST["btn_submit"])) {
    update($connection);
    $success = 'The update has been successfully completed! Please delete the "update_database.php" file.';
}

function update()
{
    updateFrom42To43();
    updateFrom43To44();
    sleep(1);
    updateFrom44To45();
}

function updateFrom42To43()
{
    runQuery("DROP TABLE ad_spaces;");
    runQuery("DROP TABLE fonts;");
    $tblAdSpaces = "CREATE TABLE `ad_spaces` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `lang_id` int(11) DEFAULT 1,
      `ad_space` text DEFAULT NULL,
      `ad_code_desktop` text DEFAULT NULL,
      `desktop_width` int(11) DEFAULT NULL,
      `desktop_height` int(11) DEFAULT NULL,
      `ad_code_mobile` text DEFAULT NULL,
      `mobile_width` int(11) DEFAULT NULL,
      `mobile_height` int(11) DEFAULT NULL,
      `paragraph_number` int(11) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $tblFonts = "CREATE TABLE `fonts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `font_name` varchar(255) DEFAULT NULL,
    `font_key` varchar(255) DEFAULT NULL,
    `font_url` varchar(2000) DEFAULT NULL,
    `font_family` varchar(500) DEFAULT NULL,
    `font_source` varchar(50) DEFAULT 'google',
    `has_local_file` tinyint(1) DEFAULT 0,
    `is_default` tinyint(1) DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    runQuery($tblAdSpaces);
    runQuery($tblFonts);
    runQuery("ALTER TABLE general_settings CHANGE mail_library mail_service varchar(100) DEFAULT 'swift';");
    runQuery("ALTER TABLE general_settings ADD COLUMN `mailjet_api_key` varchar(255);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `mailjet_secret_key` varchar(255);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `mailjet_email_address` varchar(255);");
    runQuery("ALTER TABLE general_settings DROP COLUMN `recaptcha_lang`;");

    $sqlFonts = "INSERT INTO `fonts` (`id`, `font_name`, `font_key`, `font_url`, `font_family`, `font_source`, `has_local_file`, `is_default`) VALUES
(1, 'Arial', 'arial', NULL, 'font-family: Arial, Helvetica, sans-serif', 'local', 0, 1),
(2, 'Arvo', 'arvo', '<link href=\"https://fonts.googleapis.com/css?family=Arvo:400,700&display=swap\" rel=\"stylesheet\">\r\n', 'font-family: \"Arvo\", Helvetica, sans-serif', 'google', 0, 0),
(3, 'Averia Libre', 'averia-libre', '<link href=\"https://fonts.googleapis.com/css?family=Averia+Libre:300,400,700&display=swap\" rel=\"stylesheet\">\r\n', 'font-family: \"Averia Libre\", Helvetica, sans-serif', 'google', 0, 0),
(4, 'Bitter', 'bitter', '<link href=\"https://fonts.googleapis.com/css?family=Bitter:400,400i,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Bitter\", Helvetica, sans-serif', 'google', 0, 0),
(5, 'Cabin', 'cabin', '<link href=\"https://fonts.googleapis.com/css?family=Cabin:400,500,600,700&display=swap&subset=latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Cabin\", Helvetica, sans-serif', 'google', 0, 0),
(6, 'Cherry Swash', 'cherry-swash', '<link href=\"https://fonts.googleapis.com/css?family=Cherry+Swash:400,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Cherry Swash\", Helvetica, sans-serif', 'google', 0, 0),
(7, 'Encode Sans', 'encode-sans', '<link href=\"https://fonts.googleapis.com/css?family=Encode+Sans:300,400,500,600,700&display=swap&subset=latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Encode Sans\", Helvetica, sans-serif', 'google', 0, 0),
(8, 'Helvetica', 'helvetica', NULL, 'font-family: Helvetica, sans-serif', 'local', 0, 1),
(9, 'Hind', 'hind', '<link href=\"https://fonts.googleapis.com/css?family=Hind:300,400,500,600,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">', 'font-family: \"Hind\", Helvetica, sans-serif', 'google', 0, 0),
(10, 'Josefin Sans', 'josefin-sans', '<link href=\"https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,600,700&display=swap&subset=latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Josefin Sans\", Helvetica, sans-serif', 'google', 0, 0),
(11, 'Kalam', 'kalam', '<link href=\"https://fonts.googleapis.com/css?family=Kalam:300,400,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Kalam\", Helvetica, sans-serif', 'google', 0, 0),
(12, 'Khula', 'khula', '<link href=\"https://fonts.googleapis.com/css?family=Khula:300,400,600,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Khula\", Helvetica, sans-serif', 'google', 0, 0),
(13, 'Lato', 'lato', '<link href=\"https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">', 'font-family: \"Lato\", Helvetica, sans-serif', 'google', 0, 0),
(14, 'Lora', 'lora', '<link href=\"https://fonts.googleapis.com/css?family=Lora:400,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Lora\", Helvetica, sans-serif', 'google', 0, 0),
(15, 'Merriweather', 'merriweather', '<link href=\"https://fonts.googleapis.com/css?family=Merriweather:300,400,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Merriweather\", Helvetica, sans-serif', 'google', 0, 0),
(16, 'Montserrat', 'montserrat', '<link href=\"https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Montserrat\", Helvetica, sans-serif', 'google', 0, 0),
(17, 'Mukta', 'mukta', '<link href=\"https://fonts.googleapis.com/css?family=Mukta:300,400,500,600,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Mukta\", Helvetica, sans-serif', 'google', 0, 0),
(18, 'Nunito', 'nunito', '<link href=\"https://fonts.googleapis.com/css?family=Nunito:300,400,600,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Nunito\", Helvetica, sans-serif', 'google', 0, 0),
(19, 'Open Sans', 'open-sans', '<link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap\" rel=\"stylesheet\">', 'font-family: \"Open Sans\", Helvetica, sans-serif', 'local', 1, 0),
(20, 'Oswald', 'oswald', '<link href=\"https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese\" rel=\"stylesheet\">', 'font-family: \"Oswald\", Helvetica, sans-serif', 'google', 0, 0),
(21, 'Oxygen', 'oxygen', '<link href=\"https://fonts.googleapis.com/css?family=Oxygen:300,400,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Oxygen\", Helvetica, sans-serif', 'google', 0, 0),
(22, 'Poppins', 'poppins', '<link href=\"https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap&subset=devanagari,latin-ext\" rel=\"stylesheet\">', 'font-family: \"Poppins\", Helvetica, sans-serif', 'google', 0, 0),
(23, 'PT Sans', 'pt-sans', '<link href=\"https://fonts.googleapis.com/css?family=PT+Sans:400,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"PT Sans\", Helvetica, sans-serif', 'google', 0, 0),
(24, 'Raleway', 'raleway', '<link href=\"https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">\r\n', 'font-family: \"Raleway\", Helvetica, sans-serif', 'google', 0, 0),
(25, 'Roboto', 'roboto', '<link href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese\" rel=\"stylesheet\">', 'font-family: \"Roboto\", Helvetica, sans-serif', 'local', 1, 0),
(26, 'Roboto Condensed', 'roboto-condensed', '<link href=\"https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Roboto Condensed\", Helvetica, sans-serif', 'google', 0, 0),
(27, 'Roboto Slab', 'roboto-slab', '<link href=\"https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Roboto Slab\", Helvetica, sans-serif', 'google', 0, 0),
(28, 'Rokkitt', 'rokkitt', '<link href=\"https://fonts.googleapis.com/css?family=Rokkitt:300,400,500,600,700&display=swap&subset=latin-ext,vietnamese\" rel=\"stylesheet\">\r\n', 'font-family: \"Rokkitt\", Helvetica, sans-serif', 'google', 0, 0),
(29, 'Source Sans Pro', 'source-sans-pro', '<link href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese\" rel=\"stylesheet\">', 'font-family: \"Source Sans Pro\", Helvetica, sans-serif', 'google', 0, 0),
(30, 'Titillium Web', 'titillium-web', '<link href=\"https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700&display=swap&subset=latin-ext\" rel=\"stylesheet\">', 'font-family: \"Titillium Web\", Helvetica, sans-serif', 'google', 0, 0),
(31, 'Ubuntu', 'ubuntu', '<link href=\"https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext\" rel=\"stylesheet\">', 'font-family: \"Ubuntu\", Helvetica, sans-serif', 'google', 0, 0),
(32, 'Verdana', 'verdana', NULL, 'font-family: Verdana, Helvetica, sans-serif', 'local', 0, 1);";
    runQuery($sqlFonts);
    runQuery("UPDATE general_settings SET `version` = '4.3' WHERE id = 1;");

    //add new translations
    $p = array();
    $p["ok"] = "OK";
    $p["cancel"] = "Cancel";
    $p["ad_space_index_top"] = "Index (Top)";
    $p["ad_space_index_bottom"] = "Index (Bottom)";
    $p["ad_space_post_top"] = "Post Details (Top)";
    $p["ad_space_post_bottom"] = "Post Details (Bottom)";
    $p["ad_space_posts_top"] = "Posts (Top)";
    $p["ad_space_posts_bottom"] = "Posts (Bottom)";
    $p["sidebar"] = "Sidebar";
    $p["ad_space_in_article"] = "In-Article";
    $p["banner_desktop"] = "Desktop Banner";
    $p["banner_desktop_exp"] = "This ad will be displayed on screens larger than 992px";
    $p["banner_mobile"] = "Mobile Banner";
    $p["banner_mobile_exp"] = "This ad will be displayed on screens smaller than 992px";
    $p["create_ad_exp"] = "If you don not have an ad code, you can create an ad code by selecting an image and adding an URL";
    $p["paragraph"] = "Paragraph";
    $p["ad_space_paragraph_exp"] = "The ad will be displayed after the paragraph number you selected";
    $p["mail_service"] = "Mail Service";
    $p["api_key"] = "API Key";
    $p["mailjet_email_address"] = "Mailjet Email Address";
    $p["mailjet_email_address_exp"] = "The address you created your Mailjet account with";
    $p["mail_title"] = "Mail Title";
    $p["font_source"] = "Font Source";
    $p["local"] = "Local";
    $p["ad_space_posts_exp"] = "This ad will be displayed on Posts, Category, Profile, Tag, Search and Profile pages";
    $p["last_seen"] = "Last seen:";
    addTranslations($p);
    //delete old translations
    runQuery("DELETE FROM language_translations WHERE `label`='category_bottom_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='category_top_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='index_bottom_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='index_top_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='post_bottom_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='post_top_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='profile_bottom_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='profile_top_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='reading_list_bottom_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='reading_list_top_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='search_bottom_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='search_top_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='sidebar_bottom_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='sidebar_top_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='tag_bottom_ad_space';");
    runQuery("DELETE FROM language_translations WHERE `label`='tag_top_ad_space';");
}

function updateFrom43To44()
{
    runQuery("ALTER TABLE general_settings DROP COLUMN IF EXISTS `recaptcha_lang`;");
    runQuery("ALTER TABLE files ADD COLUMN `file_path` varchar(500);");
    runQuery("ALTER TABLE files ADD COLUMN `storage` varchar(20) DEFAULT 'local';");
    runQuery("ALTER TABLE general_settings ADD COLUMN `rss_content_type` varchar(50) DEFAULT 'summary';");
    runQuery("ALTER TABLE general_settings ADD COLUMN `image_file_format` varchar(30) DEFAULT 'JPG';");
    runQuery("ALTER TABLE general_settings ADD COLUMN `default_role_id` INT(11) DEFAULT 3;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `storage` varchar(20) DEFAULT 'local';");
    runQuery("ALTER TABLE general_settings ADD COLUMN `aws_key` varchar(255);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `aws_secret` varchar(255);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `aws_bucket` varchar(255);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `aws_region` varchar(255);");
    runQuery("ALTER TABLE general_settings CHANGE custom_css_codes custom_header_codes mediumtext;");
    runQuery("ALTER TABLE general_settings CHANGE custom_javascript_codes custom_footer_codes mediumtext;");
    runQuery("ALTER TABLE general_settings CHANGE mobile_logo_path logo_darkmode_path varchar(255);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `logo_desktop_width` smallint(6) DEFAULT 180;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `logo_desktop_height` smallint(6) DEFAULT 50;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `logo_mobile_width` smallint(6) DEFAULT 180;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `logo_mobile_height` smallint(6) DEFAULT 50;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `sidebar_categories` tinyint(1) DEFAULT 1;");
    runQuery("ALTER TABLE images ADD COLUMN `storage` varchar(20) DEFAULT 'local';");
    runQuery("ALTER TABLE photos ADD COLUMN `storage` varchar(20) DEFAULT 'local';");
    runQuery("ALTER TABLE posts ADD COLUMN `image_storage` varchar(20) DEFAULT 'local';");
    runQuery("ALTER TABLE post_images ADD COLUMN `storage` varchar(20) DEFAULT 'local';");
    runQuery("ALTER TABLE settings ADD COLUMN `tiktok_url` varchar(500);");
    runQuery("ALTER TABLE settings ADD COLUMN `whatsapp_url` varchar(500);");
    runQuery("ALTER TABLE settings ADD COLUMN `discord_url` varchar(500);");
    runQuery("ALTER TABLE settings ADD COLUMN `twitch_url` varchar(500);");
    runQuery("ALTER TABLE users ADD COLUMN `tiktok_url` varchar(500);");
    runQuery("ALTER TABLE users ADD COLUMN `whatsapp_url` varchar(500);");
    runQuery("ALTER TABLE users ADD COLUMN `discord_url` varchar(500);");
    runQuery("ALTER TABLE users ADD COLUMN `twitch_url` varchar(500);");
    runQuery("UPDATE general_settings SET `version` = '4.4' WHERE id = 1;");

    //update files
    $files = runQuery("SELECT * FROM files ORDER BY id;");
    if (!empty($files->num_rows)) {
        while ($item = mysqli_fetch_array($files)) {
            runQuery("UPDATE files SET `file_path`='uploads/files/" . $item['file_name'] . "' WHERE `id`=" . $item['id'] . ";");
        }
    }

    //add new translations
    $p = array();
    $p["accept_cookies"] = "Accept Cookies";
    $p["aws_key"] = "AWS Access Key";
    $p["aws_secret"] = "AWS Secret Key";
    $p["aws_storage"] = "AWS S3 Storage";
    $p["bucket_name"] = "Bucket Name";
    $p["custom_footer_codes"] = "Custom Footer Codes";
    $p["custom_footer_codes_exp"] = "These codes will be added to the footer of the site.";
    $p["custom_header_codes"] = "Custom Header Codes";
    $p["custom_header_codes_exp"] = "These codes will be added to the header of the site.";
    $p["default_role_members"] = "Default Role for New Members";
    $p["distribute_only_post_summary"] = "Distribute only Post Summary";
    $p["distribute_post_content"] = "Distribute Post Content";
    $p["general"] = "General";
    $p["image_file_format"] = "Image File Format";
    $p["local_storage"] = "Local Storage";
    $p["preferences"] = "Preferences";
    $p["region_code"] = "Region Code";
    $p["rss_content"] = "RSS Content";
    $p["storage"] = "Storage";
    $p["logo_size"] = "Logo Size";
    $p["desktop"] = "Desktop";
    $p["mobile"] = "Mobile";
    $p["width"] = "Width";
    $p["height"] = "Height";
    $p["logo_size_exp"] = "For better logo quality, you can upload your logo in slightly larger sizes and set smaller sizes by keeping the image ratio the same";
    $p["show_categories_sidebar"] = "Show Categories on Sidebar";
    addTranslations($p);
    //delete old translations
    runQuery("DELETE FROM language_translations WHERE `label`='custom_css_codes';");
    runQuery("DELETE FROM language_translations WHERE `label`='custom_css_codes_exp';");
    runQuery("DELETE FROM language_translations WHERE `label`='custom_javascript_codes';");
    runQuery("DELETE FROM language_translations WHERE `label`='custom_javascript_codes_exp';");
    runQuery("DELETE FROM language_translations WHERE `label`='mobile_logo';");

    runQuery("INSERT INTO `fonts` (`font_name`, `font_key`, `font_url`, `font_family`, `font_source`, `has_local_file`, `is_default`) VALUES
('Inter', 'inter', '<link href=\"https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap\" rel=\"stylesheet\">', 'font-family: \"Inter\", sans-serif;', 'local', 1, 0);");

}

function updateFrom44To45()
{
    global $connection;

    runQuery("TRUNCATE TABLE ci_sessions");
    runQuery("RENAME TABLE tags TO tags1;");
    runQuery("RENAME TABLE photos TO gallery_images;");

    $tblTags = "CREATE TABLE `tags` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `tag` varchar(255) DEFAULT NULL,
            `tag_slug` varchar(255) DEFAULT NULL,
            `lang_id` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    $tblPostTags = "CREATE TABLE `post_tags` (
            `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
            `tag_id` int(11) DEFAULT NULL,
            `post_id` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    $tblPollOptions = "CREATE TABLE `poll_options` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `poll_id` int(11) DEFAULT NULL,
            `option_text` varchar(500) DEFAULT NULL,
            `votes` int(11) DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

    runQuery($tblTags);
    runQuery($tblPostTags);
    runQuery($tblPollOptions);

    runQuery("ALTER TABLE comments ADD COLUMN `ip_address` varchar(45);");
    runQuery("ALTER TABLE contacts ADD COLUMN `ip_address` varchar(45);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `static_cache_system` TINYINT(1) DEFAULT 0;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `newsletter_image` varchar(255);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `logo_size` varchar(255);");
    runQuery("ALTER TABLE general_settings ADD COLUMN `ai_writer` TEXT;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `show_home_link` TINYINT(1) DEFAULT 1;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `sticky_sidebar` TINYINT(1) DEFAULT 1;");
    runQuery("ALTER TABLE posts CHANGE title_slug slug varchar(500);");
    runQuery("ALTER TABLE posts CHANGE hit pageviews INT(11);");
    runQuery("ALTER TABLE posts ADD COLUMN `image_id` INT(11) DEFAULT NULL;");
    runQuery("ALTER TABLE posts ADD COLUMN `comment_count` INT(11) DEFAULT 0;");
    runQuery("ALTER TABLE posts ADD COLUMN `updated_at` timestamp NULL DEFAULT NULL;");
    runQuery("ALTER TABLE settings ADD COLUMN `social_media_data` TEXT;");
    runQuery("ALTER TABLE users ADD COLUMN `social_media_data` TEXT;");
    runQuery("ALTER TABLE users ADD COLUMN `session_token` varchar(255);");
    runQuery("ALTER TABLE users ADD COLUMN `reset_token` varchar(255);");
    runQuery("ALTER TABLE users ADD COLUMN `show_rss_feeds` TINYINT(1) DEFAULT 0;");

    runQuery("ALTER TABLE general_settings DROP COLUMN `sitemap_frequency`;");
    runQuery("ALTER TABLE general_settings DROP COLUMN `sitemap_last_modification`;");
    runQuery("ALTER TABLE general_settings DROP COLUMN `sitemap_priority`;");
    runQuery("ALTER TABLE general_settings ADD COLUMN `sitemap_frequency` varchar(30) DEFAULT 'auto'");
    runQuery("ALTER TABLE general_settings ADD COLUMN `sitemap_last_modification` varchar(30) DEFAULT 'auto'");
    runQuery("ALTER TABLE general_settings ADD COLUMN `sitemap_priority` varchar(30) DEFAULT 'auto'");

    //insert new font
    runQuery("INSERT INTO `fonts` ( `font_name`, `font_key`, `font_url`, `font_family`, `font_source`, `has_local_file`, `is_default`) VALUES
    ('Source Sans 3', 'source-sans-3', NULL, 'font-family: \"Source Sans 3\", Helvetica, sans-serif', 'local', 1, 0);");

    //update post images and comment counts
    $query = "SELECT posts.*, 
       (SELECT id FROM images WHERE images.image_big = posts.image_big LIMIT 1) AS img_id,
       (SELECT COUNT(comments.id) FROM comments WHERE comments.post_id = posts.id) AS total_comments
        FROM `posts`";
    $result = runQuery($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imgId = $row['img_id'];
            if (empty($imgId)) {
                $imgId = 0;
            }
            $commentCount = 0;
            if (!empty($row['total_comments'])) {
                $commentCount = $row['total_comments'];
            }
            $updateQuery = $connection->prepare("UPDATE posts SET image_id = ?, comment_count = ? WHERE id = ?");
            $updateQuery->bind_param("iii", $imgId, $commentCount, $row['id']);
            $updateQuery->execute();
        }
    }

    //polls
    $pollsResult = runQuery("SELECT * FROM polls");
    if ($pollsResult && $pollsResult->num_rows > 0) {
        while ($poll = mysqli_fetch_array($pollsResult)) {
            $oldPollId = (int)$poll['id'];

            $voteCounts = [];
            $votesQuery = "SELECT vote, COUNT(*) as vote_count FROM poll_votes WHERE poll_id = ? GROUP BY vote";
            $stmtVotes = $connection->prepare($votesQuery);
            $stmtVotes->bind_param("i", $oldPollId);
            $stmtVotes->execute();
            $votesResult = $stmtVotes->get_result();

            while ($voteRow = $votesResult->fetch_assoc()) {
                $voteKey = $voteRow['vote'];
                $voteCounts[$voteKey] = (int)$voteRow['vote_count'];
            }
            $stmtVotes->close();

            for ($i = 1; $i <= 10; $i++) {
                $optionKey = 'option' . $i;
                if (!empty($poll[$optionKey])) {
                    $optionText = $poll[$optionKey];
                    $voteKey = 'option' . $i;
                    $voteCount = isset($voteCounts[$voteKey]) ? $voteCounts[$voteKey] : 0;

                    $stmtInsert = $connection->prepare("INSERT INTO poll_options (poll_id, option_text, votes) VALUES (?, ?, ?)");
                    $stmtInsert->bind_param("isi", $oldPollId, $optionText, $voteCount);
                    $stmtInsert->execute();
                    $stmtInsert->close();
                }
            }
        }
    }

    //set settings
    $result = runQuery("SELECT * FROM settings ORDER BY id;");
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $data = [
                'facebook' => !empty($row['facebook_url']) ? $row['facebook_url'] : '',
                'twitter' => !empty($row['twitter_url']) ? $row['twitter_url'] : '',
                'instagram' => !empty($row['instagram_url']) ? $row['instagram_url'] : '',
                'tiktok' => !empty($row['tiktok_url']) ? $row['tiktok_url'] : '',
                'whatsapp' => !empty($row['whatsapp_url']) ? $row['whatsapp_url'] : '',
                'youtube' => !empty($row['youtube_url']) ? $row['youtube_url'] : '',
                'discord' => !empty($row['discord_url']) ? $row['discord_url'] : '',
                'telegram' => !empty($row['telegram_url']) ? $row['telegram_url'] : '',
                'pinterest' => !empty($row['pinterest_url']) ? $row['pinterest_url'] : '',
                'linkedin' => !empty($row['linkedin_url']) ? $row['linkedin_url'] : '',
                'twitch' => !empty($row['twitch_url']) ? $row['twitch_url'] : '',
                'vk' => !empty($row['vk_url']) ? $row['vk_url'] : '',
            ];
            $socialMediaData = serialize($data);
            $stmt = $connection->prepare("UPDATE settings SET social_media_data = ? WHERE id = ?");
            $stmt->bind_param("si", $socialMediaData, $row['id']);
            $stmt->execute();
        }
    }

    //set users
    $result = runQuery("SELECT * FROM users;");
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $sessionToken = str_replace('.', '-', uniqid('', true));
            $sessionToken .= bin2hex(random_bytes(4));
            $sessionToken = hash('sha1', $sessionToken);
            $resetToken = str_replace('.', '-', uniqid('', true));
            $resetToken .= bin2hex(random_bytes(4));
            $resetToken = hash('sha1', $resetToken);
            $data = [
                'facebook' => !empty($row['facebook_url']) ? $row['facebook_url'] : '',
                'twitter' => !empty($row['twitter_url']) ? $row['twitter_url'] : '',
                'instagram' => !empty($row['instagram_url']) ? $row['instagram_url'] : '',
                'tiktok' => !empty($row['tiktok_url']) ? $row['tiktok_url'] : '',
                'whatsapp' => !empty($row['whatsapp_url']) ? $row['whatsapp_url'] : '',
                'youtube' => !empty($row['youtube_url']) ? $row['youtube_url'] : '',
                'discord' => !empty($row['discord_url']) ? $row['discord_url'] : '',
                'telegram' => !empty($row['telegram_url']) ? $row['telegram_url'] : '',
                'pinterest' => !empty($row['pinterest_url']) ? $row['pinterest_url'] : '',
                'linkedin' => !empty($row['linkedin_url']) ? $row['linkedin_url'] : '',
                'twitch' => !empty($row['twitch_url']) ? $row['twitch_url'] : '',
                'vk' => !empty($row['vk_url']) ? $row['vk_url'] : ''
            ];
            $socialMediaData = serialize($data);
            $stmt = $connection->prepare("UPDATE users SET social_media_data = ?, session_token = ?, reset_token = ? WHERE id = ?");
            $stmt->bind_param("sssi", $socialMediaData, $sessionToken, $resetToken, $row['id']);
            $stmt->execute();
        }
    }

    //rearrange tags
    runQuery("ALTER TABLE tags1 ADD COLUMN `lang_id` int DEFAULT 1;");
    runQuery("UPDATE tags1 JOIN posts ON tags1.post_id = posts.id SET tags1.lang_id = posts.lang_id;");
    runQuery("INSERT INTO tags (tag, tag_slug, lang_id) SELECT DISTINCT tag, tag_slug, lang_id FROM tags1");
    runQuery("INSERT INTO post_tags (post_id, tag_id) SELECT t.post_id, tg.id FROM tags1 t JOIN tags tg
        ON t.tag = tg.tag AND t.tag_slug = tg.tag_slug AND t.lang_id = tg.lang_id;");

    //change collation
    runQuery("ALTER TABLE `posts` MODIFY content longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    runQuery("ALTER TABLE `posts` MODIFY title varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    runQuery("ALTER TABLE `posts` MODIFY summary varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    runQuery("ALTER TABLE `pages` MODIFY page_content longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    runQuery("ALTER TABLE `comments` MODIFY comment varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");

    //add indexes
    runQuery("ALTER TABLE ci_sessions ADD INDEX ci_sessions_id (id);");
    runQuery("CREATE INDEX idx_comments_optimized ON comments (post_id, parent_id, status)");
    runQuery("ALTER TABLE posts ADD INDEX idx_title_slug (slug);");
    runQuery("ALTER TABLE posts ADD INDEX idx_image_id (image_id);");
    runQuery("ALTER TABLE posts ADD INDEX idx_pageviews (pageviews);");
    runQuery("ALTER TABLE posts ADD INDEX idx_slider_order (slider_order);");
    runQuery("ALTER TABLE posts ADD INDEX idx_post_type (post_type);");
    runQuery("CREATE INDEX idx_posts_optimized ON posts (`visibility`,`status`,`lang_id`,`user_id`,`image_id`)");
    runQuery("CREATE INDEX idx_posts_category ON posts (`category_id`,`visibility`,`status`,`lang_id`,`user_id`,`image_id`)");
    runQuery("CREATE INDEX idx_posts_slider ON posts (`is_slider`,`visibility`,`status`,`lang_id`,`slider_order`)");
    runQuery("CREATE INDEX idx_posts_our_picks ON posts (`is_picked`,`visibility`,`status`,`lang_id`,`created_at`)");
    runQuery("CREATE FULLTEXT INDEX idx_fulltext ON posts (title, summary, content);");
    runQuery("ALTER TABLE post_tags ADD INDEX idx_post_id (post_id);");
    runQuery("CREATE INDEX idx_tag_post ON post_tags (tag_id, post_id)");
    runQuery("ALTER TABLE reading_lists ADD INDEX idx_post_id (post_id);");
    runQuery("ALTER TABLE reading_lists ADD INDEX idx_user_id (user_id);");
    runQuery("ALTER TABLE users ADD INDEX idx_slug (slug);");
    runQuery("ALTER TABLE users ADD INDEX idx_role_id (role_id);");

    $p["pageviews"] = "Pageviews";
    $p["load_more_posts"] = "Load More Posts";
    $p["pending"] = "Pending";
    $p["approved"] = "Approved";
    $p["ip_address"] = "IP Address";
    $p["view_post"] = "View Post";
    $p["confirm_action"] = "Are you sure you want to perform this action?";
    $p["static_cache_system"] = "Static Cache System";
    $p["ai_writer"] = "AI Writer";
    $p["ai_content_creator"] = "AI Content Creator";
    $p["add_tag"] = "Add Tag";
    $p["automatically_calculated"] = "Automatically Calculated";
    $p["enter_2_characters"] = "Enter at least 2 characters";
    $p["enter_topic"] = "Enter topic";
    $p["generated_text"] = "Generated Text";
    $p["generate_text"] = "Generate Text";
    $p["generating_text"] = "Generating text...";
    $p["length_of_text"] = "Length of Text";
    $p["long"] = "Long";
    $p["manage_tags"] = "Manage Tags";
    $p["medium"] = "Medium";
    $p["model"] = "Model";
    $p["msg_request_sent"] = "The request has been sent successfully!";
    $p["msg_tag_exists"] = "This tag already exists!";
    $p["msg_topic_empty"] = "Topic cannot be empty!";
    $p["refresh"] = "Refresh";
    $p["regenerate"] = "Regenerate";
    $p["short"] = "Short";
    $p["temperature_response_diversity"] = "Temperature (Response Diversity)";
    $p["tone_academic"] = "Academic";
    $p["tone_casual"] = "Casual";
    $p["tone_critical"] = "Critical";
    $p["tone_formal"] = "Formal";
    $p["tone_humorous"] = "Humorous";
    $p["tone_inspirational"] = "Inspirational";
    $p["tone_persuasive"] = "Persuasive";
    $p["tone_professional"] = "Professional";
    $p["tone_style"] = "Tone/Style";
    $p["topic"] = "Topic";
    $p["use_text"] = "Use Text";
    $p["very_long"] = "Very Long";
    $p["very_short"] = "Very Short";
    $p["reset"] = "Reset";
    $p["share"] = "Share";
    $p["popular_tags"] = "Popular Tags";
    $p["number_short_thousand"] = "k";
    $p["number_short_million"] = "m";
    $p["number_short_billion"] = "b";
    $p["sitemap_generate_exp"] = "If your site has more than 49,000 links, the sitemap.xml file will be created in parts.";
    $p["generated_sitemaps"] = "Generated Sitemaps";
    $p["download"] = "Download";
    $p["invalid_attempt"] = "Invalid attempt!";
    $p["nav_drag_warning"] = "You cannot drag a category below a page or a page below a category link!";
    $p["hidden"] = "Hidden";
    $p["number_of_links_in_menu"] = "The number of links that appear in the menu";
    $p["no_results_found"] = "No results found.";
    $p["edited"] = "Edited";
    $p["facebook"] = "Facebook";
    $p["twitter"] = "X (Twitter)";
    $p["instagram"] = "Instagram";
    $p["tiktok"] = "Tiktok";
    $p["whatsapp"] = "Whatsapp";
    $p["youtube"] = "Youtube";
    $p["discord"] = "Discord";
    $p["telegram"] = "Telegram";
    $p["pinterest"] = "Pinterest";
    $p["linkedin"] = "Linkedin";
    $p["twitch"] = "Twitch";
    $p["vk"] = "VKontakte";
    $p["personal_website_url"] = "Personal Website URL";
    $p["enter_url"] = "Enter URL";
    $p["sticky_sidebar"] = "Sticky Sidebar";
    $p["delete_account"] = "Delete Account";
    $p["delete_account_confirm"] = "Deleting your account is permanent and will remove all content including comments, avatars and profile settings. Are you sure you want to delete your account?";
    $p["wrong_password"] = "Wrong password!";
    $p["add_option"] = "Add Option";
    $p["option"] = "Option";
    $p["theme_settings"] = "Theme Settings";
    addTranslations($p);

    //delete old translations
    runQuery("DELETE FROM language_translations WHERE `label`='add_subcategory';");
    runQuery("DELETE FROM language_translations WHERE `label`='always';");
    runQuery("DELETE FROM language_translations WHERE `label`='approved_comments';");
    runQuery("DELETE FROM language_translations WHERE `label`='daily';");
    runQuery("DELETE FROM language_translations WHERE `label`='hourly';");
    runQuery("DELETE FROM language_translations WHERE `label`='monthly';");
    runQuery("DELETE FROM language_translations WHERE `label`='msg_suc_added';");
    runQuery("DELETE FROM language_translations WHERE `label`='msg_suc_deleted';");
    runQuery("DELETE FROM language_translations WHERE `label`='msg_suc_updated';");
    runQuery("DELETE FROM language_translations WHERE `label`='never';");
    runQuery("DELETE FROM language_translations WHERE `label`='no_thanks';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_1';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_2';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_3';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_4';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_5';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_6';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_7';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_8';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_9';");
    runQuery("DELETE FROM language_translations WHERE `label`='option_10';");
    runQuery("DELETE FROM language_translations WHERE `label`='priority_none';");
    runQuery("DELETE FROM language_translations WHERE `label`='search_noresult';");
    runQuery("DELETE FROM language_translations WHERE `label`='server_response';");
    runQuery("DELETE FROM language_translations WHERE `label`='site_comments';");
    runQuery("DELETE FROM language_translations WHERE `label`='subcategories';");
    runQuery("DELETE FROM language_translations WHERE `label`='update_subcategory';");
    runQuery("DELETE FROM language_translations WHERE `label`='weekly';");
    runQuery("DELETE FROM language_translations WHERE `label`='yearly';");

    runQuery("UPDATE general_settings SET version = '4.5';");

    //delete tables and columns
    runQuery("ALTER TABLE general_settings DROP COLUMN `logo_desktop_width`;");
    runQuery("ALTER TABLE general_settings DROP COLUMN `logo_desktop_height`;");
    runQuery("ALTER TABLE general_settings DROP COLUMN `logo_mobile_width`;");
    runQuery("ALTER TABLE general_settings DROP COLUMN `logo_mobile_height`;");
    runQuery("ALTER TABLE general_settings DROP COLUMN `newsletter_temp_emails`;");
    runQuery("ALTER TABLE images DROP COLUMN `image_slider`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option1`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option2`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option3`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option4`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option5`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option6`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option7`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option8`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option9`;");
    runQuery("ALTER TABLE polls DROP COLUMN `option10`;");
    runQuery("ALTER TABLE posts DROP COLUMN `image_big`;");
    runQuery("ALTER TABLE posts DROP COLUMN `image_mid`;");
    runQuery("ALTER TABLE posts DROP COLUMN `image_small`;");
    runQuery("ALTER TABLE posts DROP COLUMN `image_slider`;");
    runQuery("ALTER TABLE posts DROP COLUMN `image_mime`;");
    runQuery("ALTER TABLE posts DROP COLUMN `image_storage`;");
    runQuery("ALTER TABLE settings DROP COLUMN `facebook_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `twitter_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `instagram_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `tiktok_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `whatsapp_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `youtube_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `discord_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `telegram_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `pinterest_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `linkedin_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `twitch_url`;");
    runQuery("ALTER TABLE settings DROP COLUMN `vk_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `token`;");
    runQuery("ALTER TABLE users DROP COLUMN `facebook_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `twitter_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `instagram_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `tiktok_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `whatsapp_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `youtube_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `discord_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `telegram_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `pinterest_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `linkedin_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `twitch_url`;");
    runQuery("ALTER TABLE users DROP COLUMN `vk_url`;");
    runQuery("DROP TABLE poll_votes;");
    runQuery("DROP TABLE tags1;");

    //clear cache
    $cacheDir = __DIR__ . '/writable/cache';
    if (is_dir($cacheDir)) {
        $files = glob($cacheDir . '/*');
        if ($files !== false) {
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== 'index.html') {
                    @unlink($file);
                }
            }
        }
    }
}

function addTranslations($translations)
{
    global $connection;

    $languages = runQuery("SELECT * FROM languages;");
    if (!empty($languages->num_rows)) {
        while ($language = mysqli_fetch_array($languages)) {
            foreach ($translations as $key => $value) {
                $trans = runQuery("SELECT * FROM language_translations WHERE label ='" . $key . "' AND lang_id = " . $language['id']);
                if (empty($trans->num_rows)) {
                    $stmt = $connection->prepare("INSERT INTO language_translations (`lang_id`, `label`, `translation`) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $language['id'], $key, $value);
                    $stmt->execute();
                }
            }
        }
    }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Infinite - Update Wizard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #444 !important;
            font-size: 14px;
            background: #007991;
            background: -webkit-linear-gradient(to left, #007991, #6fe7c2);
            background: linear-gradient(to left, #007991, #6fe7c2);
        }

        .logo-cnt {
            text-align: center;
            color: #fff;
            padding: 60px 0 60px 0;
        }

        .logo-cnt .logo {
            font-size: 42px;
            line-height: 42px;
        }

        .logo-cnt p {
            font-size: 22px;
        }

        .install-box {
            width: 100%;
            padding: 30px;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            background-color: #fff;
            border-radius: 4px;
            display: block;
            float: left;
            margin-bottom: 100px;
        }

        .form-input {
            box-shadow: none !important;
            border: 1px solid #ddd;
            height: 44px;
            line-height: 44px;
            padding: 0 20px;
        }

        .form-input:focus {
            border-color: #239CA1 !important;
        }

        .btn-custom {
            background-color: #239CA1 !important;
            border-color: #239CA1 !important;
            border: 0 none;
            border-radius: 4px;
            box-shadow: none;
            color: #fff !important;
            font-size: 16px;
            font-weight: 300;
            height: 40px;
            line-height: 40px;
            margin: 0;
            min-width: 105px;
            padding: 0 20px;
            text-shadow: none;
            vertical-align: middle;
        }

        .btn-custom:hover, .btn-custom:active, .btn-custom:focus {
            background-color: #239CA1;
            border-color: #239CA1;
            opacity: .8;
        }

        .tab-content {
            width: 100%;
            float: left;
            display: block;
        }

        .buttons {
            display: block;
            float: left;
            width: 100%;
            margin-top: 30px;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            margin-top: 0;
            text-align: center;
        }

        .alert {
            text-align: center;
        }

        .alert strong {
            font-weight: 500 !important;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            font-size: 15px;
        }

        .form-control::placeholder {
            color: #9AA2AA;
            opacity: 1;
        }

        .form-control:-ms-input-placeholder {
            color: #9AA2AA;
        }

        .form-control::-ms-input-placeholder {
            color: #9AA2AA;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-8">
            <div class="row">
                <div class="col-sm-12 logo-cnt">
                    <h1>Infinite</h1>
                    <p>Welcome to the Update Wizard</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="install-box">
                        <h2 class="title">Update from v4.2 to v4.5</h2>
                        <br><br>
                        <div class="messages">
                            <?php if (!empty($error)) { ?>
                                <div class="alert alert-danger">
                                    <strong><?= $error; ?></strong>
                                </div>
                            <?php } ?>
                            <?php if (!empty($success)) { ?>
                                <div class="alert alert-success">
                                    <strong><?= $success; ?></strong>
                                    <style>.alert-info {
                                            display: none;
                                        }</style>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="step-contents">
                            <div class="tab-1">
                                <?php if (empty($success)): ?>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                        <input type="hidden" name="license_code" value="<?= !empty($license_code) ? $license_code : ''; ?>">
                                        <input type="hidden" name="purchase_code" value="<?= !empty($purchase_code) ? $purchase_code : ''; ?>">
                                        <div class="tab-content">
                                            <div class="tab_1">
                                                <div class="alert alert-primary" style="text-align: left">
                                                    <p>** Please take a backup of your database before you start. You can export this backup in .sql format using the "export" option in phpMyAdmin.</p>
                                                    <p>** Updating may take some time depending on the number of records in your database. If you have many posts (20k and above), you may need to increase
                                                        the "max_execution_time" value in your PHP settings. Otherwise, your server may stop working before the update process is completed.</p>
                                                    <p>** If there is an error during the update or if it is interrupted, you will need to delete the database, restore your database backup (with the "import" option in phpMyAdmin), and try again.</p>
                                                </div>
                                                <p class="text-success text-center" style="font-weight: 500;">Enter your database credentials and click the button to update the database.</p>
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Host</label>
                                                    <input type="text" class="form-control form-input" name="db_host" placeholder="Host" value="<?= !empty($data['db_host']) ? $data['db_host'] : 'localhost'; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Database Name</label>
                                                    <input type="text" class="form-control form-input" name="db_name" placeholder="Database Name" value="<?= !empty($data['db_name']) ? $data['db_name'] : ''; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Username</label>
                                                    <input type="text" class="form-control form-input" name="db_user" placeholder="Username" value="<?= !empty($data['db_user']) ? $data['db_user'] : ''; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Password</label>
                                                    <input type="text" class="form-control form-input" name="db_password" placeholder="Password" value="<?= !empty($data['db_password']) ? $data['db_password'] : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="buttons text-center">
                                            <button type="submit" name="btnUpdate" class="btn btn-success btn-custom" style="width: 100%; height: 50px;">Update My Database</button>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>