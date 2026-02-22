<?php

use \Config\Globals;

if (strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = str_replace('/index.php', '', $_SERVER['REQUEST_URI']);

    $newUrl = $protocol . '://' . $host . $uri;

    if ($newUrl !== ($protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])) {
        header('Location: ' . $newUrl, true, 301);
        exit();
    }
}

//auth check
if (!function_exists('langBaseUrl')) {
    function langBaseUrl($route = null)
    {
        if (!empty($route)) {
            return Globals::$langBaseUrl . '/' . $route;
        }
        return Globals::$langBaseUrl;
    }
}

//generate base url by language
if (!function_exists('generateBaseUrl')) {
    function generateBaseUrl($langId)
    {
        if ($langId == Globals::$generalSettings->site_lang) {
            return base_url();
        } else {
            $shortForm = "";
            if (!empty(Globals::$languages)) {
                foreach (Globals::$languages as $language) {
                    if ($langId == $language->id) {
                        $shortForm = $language->short_form;
                    }
                }
            }
            if (!empty($shortForm)) {
                return base_url($shortForm . "/");
            }
        }
        return base_url();
    }
}

//generate base URL by language id
if (!function_exists('generateBaseURLByLangId')) {
    function generateBaseURLByLangId($langId)
    {
        if ($langId == Globals::$generalSettings->site_lang) {
            return base_url() . '/';
        } else {
            $shortForm = '';
            $language = getLanguageClient($langId);
            if (!empty($language)) {
                $shortForm = $language->short_form;
            }
            if ($shortForm != '') {
                return base_url($shortForm) . '/';
            }
        }
        return base_url() . '/';
    }
}

//generate base url by language short name
if (!function_exists('generateBaseUrlByShortForm')) {
    function generateBaseUrlByShortForm($shortForm)
    {
        if ($shortForm == Globals::$activeLang->short_form) {
            return base_url();
        }
        return base_url($shortForm . "/");
    }
}

//admin url
if (!function_exists('adminUrl')) {
    function adminUrl($route = null)
    {
        if (!empty($route)) {
            $route = Globals::$generalSettings->admin_route . '/' . $route;
            return base_url($route);
        }
        return base_url(Globals::$generalSettings->admin_route);
    }
}

//redirect to back URL
if (!function_exists('redirectToBackURL')) {
    function redirectToBackURL()
    {
        $backURL = inputPost('back_url');
        if (!empty($backURL) && strpos($backURL, base_url()) !== false) {
            redirectToUrl($backURL);
            exit();
        }
        redirectToUrl(langBaseUrl());
        exit();
    }
}

//get language by id
if (!function_exists('getLanguageById')) {
    function getLanguageById($id)
    {
        $model = new \App\Models\LanguageModel();
        return $model->getLanguage($id);
    }
}

//get active lang
if (!function_exists('getActiveLang')) {
    function getActiveLang()
    {
        return Globals::$activeLang;
    }
}

//get active lang id
if (!function_exists('getActiveLangId')) {
    function getActiveLangId()
    {
        $lang = getActiveLang();
        if (!empty($lang)) {
            return $lang->id;
        }
        return 1;
    }
}

//auth check
if (!function_exists('authCheck')) {
    function authCheck()
    {
        return Globals::$authCheck;
    }
}

//user
if (!function_exists('user')) {
    function user()
    {
        return Globals::$authUser;
    }
}

//is super admin
if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        if (!empty(Globals::$authUserRole)) {
            if (Globals::$authUserRole->is_super_admin == 1) {
                return true;
            }
        }
        return false;
    }
}

//check admin
if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if (!empty(Globals::$authUserRole)) {
            if (Globals::$authUserRole->is_super_admin == 1 || Globals::$authUserRole->is_admin == 1) {
                return true;
            }
        }
        return false;
    }
}

//check author
if (!function_exists('isAuthor')) {
    function isAuthor()
    {
        if (!empty(Globals::$authUserRole)) {
            if (Globals::$authUserRole->is_author == 1) {
                return true;
            }
        }
        return false;
    }
}

//get user by id
if (!function_exists('getUser')) {
    function getUser($id)
    {
        $model = new \App\Models\AuthModel();
        return $model->getUser($id);
    }
}

if (!function_exists('isUserOnline')) {
    function isUserOnline($timestamp)
    {
        if (!$timestamp || !strtotime($timestamp)) {
            return false;
        }

        $timeAgo = strtotime($timestamp);
        $currentTime = time();
        $timeDifference = $currentTime - $timeAgo;

        return $timeDifference <= 120;
    }
}

//check user follows
if (!function_exists('isUserFollows')) {
    function isUserFollows($followingId, $followerId)
    {
        $model = new \App\Models\AuthModel();
        return $model->isUserFollows($followingId, $followerId);
    }
}

//print meta tag
if (!function_exists('escMeta')) {
    function escMeta($str)
    {
        if (!empty($str)) {
            return esc($str, 'html', 'UTF-8');
        }
        return '';
    }
}

//get request
if (!function_exists('inputGet')) {
    function inputGet($inputName, $removeForbidden = false)
    {
        $input = \Config\Services::request()->getGet($inputName);
        if (isset($input) && !is_array($input)) {
            $input = trim($input ?? '');
        }
        if ($removeForbidden) {
            $input = removeForbiddenCharacters($input);
        }
        return $input;
    }
}

//post request
if (!function_exists('inputPost')) {
    function inputPost($inputName, $removeForbidden = false)
    {
        $input = \Config\Services::request()->getPost($inputName);
        if (isset($input) && !is_array($input)) {
            $input = trim($input ?? '');
        }
        if ($removeForbidden) {
            $input = removeForbiddenCharacters($input);
        }
        return $input;
    }
}

//current full url
if (!function_exists('currentFullUrl')) {
    function currentFullUrl()
    {
        $url = current_url();
        $query = $_SERVER['QUERY_STRING'] ?? '';

        return $query ? $url . '?' . $query : $url;
    }
}

//limit character
if (!function_exists('limitCharacter')) {
    function limitCharacter($str, $limit, $postfix = '...')
    {
        if (mb_strlen($str, 'UTF-8') > $limit) {
            return mb_substr($str, 0, $limit, 'UTF-8') . $postfix;
        }

        return $str;
    }
}

//set page meta data
if (!function_exists('setPageMeta')) {
    function setPageMeta($pageTitle, $data = null)
    {
        if ($data == null) {
            $data = array();
        }
        $data['title'] = $pageTitle;
        $data['description'] = $pageTitle . ' - ' . Globals::$settings->site_title;
        $data['keywords'] = $pageTitle . ', ' . Globals::$settings->application_name;
        return $data;
    }
}

//unserialize data
if (!function_exists('unserializeData')) {
    function unserializeData($serializedData)
    {
        if (!is_string($serializedData)) {
            return null;
        }

        $trimmedData = trim($serializedData);
        if ($trimmedData == '') {
            return null;
        }

        // Safe unserialize operation
        try {
            $data = unserialize($trimmedData, ['allowed_classes' => false]);

            // Return null if unserialize fails (returns false)
            // However, handle the special case of 'b:0;' (which represents boolean false)
            if ($data == false && $trimmedData !== 'b:0;') {
                return null;
            }

            return $data;
        } catch (Throwable) {
            return null;
        }
    }
}

//parse serialized name array
if (!function_exists('parseSerializedNameArray')) {
    function parseSerializedNameArray($nameArray, $langId, $getMainName = true)
    {
        if (empty($nameArray)) {
            return '';
        }
        if (!is_array($nameArray)) {
            $nameArray = unserializeData($nameArray);
        }
        if (!is_array($nameArray) || empty($nameArray)) {
            return '';
        }

        foreach ($nameArray as $item) {
            if (($item['lang_id'] ?? null) == $langId && !empty($item['name'])) {
                return $item['name'];
            }
        }

        if ($getMainName && isset(Globals::$generalSettings->site_lang)) {
            $defaultLangId = Globals::$generalSettings->site_lang;
            foreach ($nameArray as $item) {
                if (($item['lang_id'] ?? null) == $defaultLangId && !empty($item['name'])) {
                    return $item['name'];
                }
            }
        }

        return '';
    }
}

//count items
if (!function_exists('countItems')) {
    function countItems($items)
    {
        return (is_array($items) && !empty($items)) ? count($items) : 0;
    }
}

//get logo
if (!function_exists('getLogo')) {
    function getLogo($generalSettings)
    {
        if (!empty($generalSettings)) {
            if (!empty($generalSettings->logo_path) && file_exists(FCPATH . $generalSettings->logo_path)) {
                return base_url($generalSettings->logo_path);
            }
        }
        return base_url("assets/img/logo.png");
    }
}

//get mobile logo
if (!function_exists('getMobileLogo')) {
    function getMobileLogo($generalSettings)
    {
        if (!empty($generalSettings)) {
            if (!empty($generalSettings->logo_darkmode_path) && file_exists(FCPATH . $generalSettings->logo_darkmode_path)) {
                return base_url($generalSettings->logo_darkmode_path);
            }
        }
        return base_url("assets/img/logo-mobile.png");
    }
}

//get logo size
if (!function_exists('getLogoSize')) {
    function getLogoSize($param)
    {
        $defaultSizes = [
            'width' => 182,
            'height' => 37
        ];

        $logoSize = unserializeData(Globals::$generalSettings->logo_size);

        if (!is_array($logoSize)) {
            $logoSize = [];
        }

        return $logoSize[$param] ?? $defaultSizes[$param] ?? 0;
    }
}

//get favicon
if (!function_exists('getFavicon')) {
    function getFavicon($generalSettings)
    {
        if (!empty($generalSettings)) {
            if (!empty($generalSettings->favicon_path) && file_exists(FCPATH . $generalSettings->favicon_path)) {
                return base_url($generalSettings->favicon_path);
            }
        }
        return base_url("assets/img/favicon.png");
    }
}

//get user avatar
if (!function_exists('getUserAvatar')) {
    function getUserAvatar($avatar)
    {
        if (!empty($avatar)) {
            if (file_exists(FCPATH . $avatar)) {
                return base_url($avatar);
            }
            return $avatar;
        }
        return base_url('assets/img/user.png');
    }
}

//delete file from server
if (!function_exists('deleteFileFromServer')) {
    function deleteFileFromServer($path)
    {
        if (empty($path)) {
            return false;
        }

        $fullPath = realpath(FCPATH . ltrim($path, '/'));
        if ($fullPath == false || strpos($fullPath, realpath(FCPATH)) !== 0) {
            return false;
        }

        if (is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }
}

//get sub menu links
if (!function_exists('getSubMenuLinks')) {
    function getSubMenuLinks($menuLinks, $parentId, $type)
    {
        $subLinks = array();
        if (!empty($menuLinks)) {
            $subLinks = array_filter($menuLinks, function ($item) use ($parentId, $type) {
                return $item->item_type == $type && $item->item_parent_id == $parentId;
            });
        }
        return $subLinks;
    }
}

//translation
if (!function_exists('trans')) {
    function trans($string)
    {
        if (isset(Globals::$languageTranslations[$string])) {
            return Globals::$languageTranslations[$string];
        }
        return "";
    }
}

//generate category url
if (!function_exists('generateCategoryURL')) {
    function generateCategoryURL($category)
    {
        if (!empty($category)) {
            if (!empty($category->parent_slug)) {
                return langBaseUrl($category->parent_slug . "/" . $category->slug);
            }
            return langBaseUrl($category->slug);
        }
        return '#';
    }
}

//generate menu item url
if (!function_exists('generateMenuItemUrl')) {
    function generateMenuItemUrl($item)
    {
        if (empty($item)) {
            return langBaseUrl('#');
        }

        return match ($item->item_type) {
            'page' => !empty($item->item_link) ? $item->item_link : langBaseUrl($item->item_slug),
            'category' => langBaseUrl(!empty($item->item_parent_slug) ? "{$item->item_parent_slug}/{$item->item_slug}" : $item->item_slug),
            default => langBaseUrl('#'),
        };
    }
}

//generate post url
if (!function_exists('generatePostUrl')) {
    function generatePostUrl($post, $baseUrl = null)
    {
        $baseUrl = $baseUrl ? rtrim($baseUrl, '/') : rtrim(langBaseUrl(), '/');

        return !empty($post?->slug) ? "{$baseUrl}/{$post->slug}" : "#";
    }
}

//generate profile url
if (!function_exists('generateProfileUrl')) {
    function generateProfileUrl($slug)
    {
        if (!empty($slug)) {
            return langBaseUrl("profile/" . esc($slug));
        }
        return "#";
    }
}

//get post by id
if (!function_exists('getPostById')) {
    function getPostById($id)
    {
        $model = new \App\Models\PostModel();
        return $model->getPost($id);
    }
}

//get latest posts
if (!function_exists('getLatestPosts')) {
    function getLatestPosts($langId, $limit)
    {
        $model = new \App\Models\PostModel();
        return $model->getLatestPosts($langId, $limit);
    }
}

//get popular posts
if (!function_exists('getPopularPosts')) {
    function getPopularPosts($langId)
    {
        $model = new \App\Models\PostModel();
        return $model->getPopularPosts($langId);
    }
}

//get our picks
if (!function_exists('getOurPicks')) {
    function getOurPicks($langId)
    {
        $model = new \App\Models\PostModel();
        return $model->getOurPicks($langId);
    }
}

//get random posts
if (!function_exists('getRandomPosts')) {
    function getRandomPosts($langId)
    {
        $model = new \App\Models\PostModel();
        return $model->getRandomPosts($langId);
    }
}

//check post is published
if (!function_exists('isPostPublished')) {
    function isPostPublished($post)
    {
        if ($post->status != 1 || $post->visibility != 1) {
            return false;
        }
        return true;
    }
}

//get polls
if (!function_exists('getPolls')) {
    function getPolls($langId)
    {
        $model = new \App\Models\PollModel();
        return $model->getPolls($langId);
    }
}

//get post image
if (!function_exists('getPostImage')) {
    function getPostImage($post, $imageSize)
    {
        if (empty($post)) {
            return '';
        }
        if (!empty($post->image_url)) {
            return $post->image_url;
        }

        $storage = (!empty($post->storage) && $post->storage == 'aws_s3') ? 'aws_s3' : 'local';
        $imgPath = '';
        switch ($imageSize) {
            case 'big':
                $imgPath = $post->image_big ?? '';
                break;
            case 'mid':
                $imgPath = $post->image_mid ?? '';
                break;
            case 'small':
                $imgPath = $post->image_small ?? '';
                break;
        }

        if (empty($imgPath)) {
            return '';
        }
        $imgUrl = ($storage == 'aws_s3') ? getAWSBaseURL() . $imgPath : base_url($imgPath);

        return $imgUrl;
    }
}

//get post additional images
if (!function_exists('getPostAdditionalImages')) {
    function getPostAdditionalImages($postId)
    {
        $model = new \App\Models\PostModel();
        return $model->getPostAdditionalImages($postId);
    }
}

//get categories
if (!function_exists('getCategories')) {
    function getCategories()
    {
        $model = new \App\Models\CategoryModel();
        return $model->getCategories();
    }
}

//get category
if (!function_exists('getCategory')) {
    function getCategory($id)
    {
        $model = new \App\Models\CategoryModel();
        return $model->getCategory($id);
    }
}

//get language client
if (!function_exists('getLanguageClient')) {
    function getLanguageClient($langId)
    {
        foreach (Globals::$languagesAll as $language) {
            if ($language->id == $langId) {
                return $language;
            }
        }

        return Globals::$defaultLang;
    }
}

//get role client
if (!function_exists('getRoleClient')) {
    function getRoleClient($roleId, $roles)
    {
        foreach ($roles as $role) {
            if ($role->id == $roleId) {
                return $role;
            }
        }
        return null;
    }
}

//get category client
if (!function_exists('getCategoryClient')) {
    function getCategoryClient($id, $categories)
    {
        foreach ($categories as $category) {
            if ($category->id == $id) {
                return $category;
            }
        }
        return null;
    }
}

//get subcategories by parent id
if (!function_exists('getSubcategoriesClient')) {
    function getSubcategoriesClient($categories, $parentId)
    {
        if (empty($categories) || $parentId <= 0) {
            return [];
        }

        return array_values(array_filter($categories, function ($item) use ($parentId) {
            return $item->parent_id == $parentId;
        }));
    }
}

//get categories
if (!function_exists('getCategories')) {
    function getCategories()
    {
        $model = new \App\Models\CategoryModel();
        return $model->getCategories();
    }
}

//get category array
if (!function_exists('getCategoryArray')) {
    function getCategoryArray($id)
    {
        $model = new \App\Models\CategoryModel();
        return $model->getCategoryArray($id);
    }
}

//get category tree
if (!function_exists('getCategoryTree')) {
    function getCategoryTree($id)
    {
        $model = new \App\Models\CategoryModel();
        return $model->getCategoryTree($id);
    }
}

//get category tree
if (!function_exists('getCategoryTreeIdsArray')) {
    function getCategoryTreeIdsArray($id)
    {
        $model = new \App\Models\CategoryModel();
        return $model->getCategoryTreeIdsArray($id);
    }
}

//get popular tags
if (!function_exists('getPopularTags')) {
    function getPopularTags($langId)
    {
        $model = new \App\Models\TagModel();
        return $model->getPopularTags($langId);
    }
}

//get gallery cover image
if (!function_exists('getGalleryCoverImage')) {
    function getGalleryCoverImage($albumId)
    {
        $model = new \App\Models\GalleryModel();
        return $model->getGalleryCoverImage($albumId);
    }
}

//get post files
if (!function_exists('getPostFiles')) {
    function getPostFiles($postId)
    {
        $model = new \App\Models\PostModel();
        return $model->getPostFiles($postId);
    }
}

//get feed posts count
if (!function_exists('getFeedPostsCount')) {
    function getFeedPostsCount($feedId)
    {
        $model = new \App\Models\RssModel();
        return $model->getFeedPostsCount($feedId);
    }
}

//format keywords for RSS
if (!function_exists('formatKeywordsForRss')) {
    function formatKeywordsForRss($keywords, $limit = 10)
    {
        if (empty($keywords)) {
            return '';
        }

        $array = explode(',', $keywords);
        $array = array_map('trim', $array);
        $array = array_filter($array, fn($k) => !empty($k));
        $array = array_slice($array, 0, $limit);
        $array = array_map(fn($k) => htmlspecialchars($k, ENT_XML1 | ENT_QUOTES, 'UTF-8'), $array);

        return implode(', ', $array);
    }
}

//get reactions array
if (!function_exists('getReactionsArray')) {
    function getReactionsArray()
    {
        return ['like', 'dislike', 'love', 'funny', 'angry', 'sad', 'wow'];
    }
}

//is reaction voted
if (!function_exists('isReactionVoted')) {
    function isReactionVoted($postId, $reaction)
    {
        if (!empty(helperGetSession('reaction_' . $reaction . '_' . $postId))) {
            return true;
        }
        if (!empty(helperGetCookie('reaction_' . $reaction . '_' . $postId))) {
            return true;
        }
        return false;
    }
}

//set session
if (!function_exists('helperSetSession')) {
    function helperSetSession($name, $value)
    {
        $session = \Config\Services::session();
        $session->set($name, $value);
    }
}

//get session
if (!function_exists('helperGetSession')) {
    function helperGetSession($name)
    {
        $session = \Config\Services::session();
        if ($session->get($name) !== null) {
            return $session->get($name);
        }
        return null;
    }
}

//delete session
if (!function_exists('helperDeleteSession')) {
    function helperDeleteSession($name)
    {
        $session = \Config\Services::session();
        if ($session->get($name) !== null) {
            $session->remove($name);
        }
    }
}

//set cookie
if (!function_exists('helperSetCookie')) {
    function helperSetCookie($name, $value, $time = null)
    {
        if ($time == null) {
            $time = time() + (86400 * 30);
        }
        $params = [
            'expires' => $time,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ];
        if (!empty(getenv('cookie.prefix'))) {
            $name = getenv('cookie.prefix') . $name;
        }
        setcookie($name, $value, $params);
    }
}

//get cookie
if (!function_exists('helperGetCookie')) {
    function helperGetCookie($name)
    {
        if (!empty(getenv('cookie.prefix'))) {
            $name = getenv('cookie.prefix') . $name;
        }
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        return false;
    }
}

//delete cookie
if (!function_exists('helperDeleteCookie')) {
    function helperDeleteCookie($name)
    {
        if (!empty(getenv('cookie.prefix'))) {
            $name = getenv('cookie.prefix') . $name;
        }
        if (!empty(helperGetCookie($name))) {
            helperSetCookie($name, '', time() - 3600);
        }
    }
}

//get cache data
if (!function_exists('getCacheData')) {
    function getCacheData($cacheKey, callable $callback, $cacheType = 'dynamic')
    {
        $isCacheActive = false;
        $time = 7200;

        if ($cacheType == 'static') {
            if (Globals::$generalSettings->static_cache_system == 1) {
                $isCacheActive = true;
                $time = STATIC_CACHE_REFRESH_TIME;
                $cacheKey = 'cstatic_' . $cacheKey;
            }
        } elseif ($cacheType == 'stable') {
            if (defined('STABLE_CACHE_SYSTEM') && STABLE_CACHE_SYSTEM == 1) {
                $isCacheActive = true;
                $time = STABLE_CACHE_REFRESH_TIME;
                $cacheKey = 'cstable_' . $cacheKey;
            }
        } else {
            if (Globals::$generalSettings->cache_system == 1) {
                $isCacheActive = true;
                $time = Globals::$generalSettings->cache_refresh_time;
            }
        }

        if ($isCacheActive) {
            $data = cache($cacheKey);
            if ($data !== null) {
                return $data;
            }

            $data = $callback();
            cache()->save($cacheKey, $data, $time);
            return $data;
        }
        return $callback();
    }
}

//reset cache data
if (!function_exists('resetCacheData')) {
    function resetCacheData($cacheType = 'dynamic', $resetStable = false)
    {
        $cachePath = WRITEPATH . 'cache/';

        if (!is_dir($cachePath)) {
            return;
        }

        $files = glob($cachePath . '*');
        if (empty($files)) {
            return;
        }

        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }

            $fileName = basename($file);

            if ($cacheType == 'static') {
                if (str_starts_with($fileName, 'cstatic_') || ($resetStable && str_starts_with($fileName, 'cstable_'))) {
                    @unlink($file);
                }
            } else {
                if (!str_starts_with($fileName, 'cstatic_') && !str_starts_with($fileName, 'cstable_')) {
                    @unlink($file);
                }
            }
        }
    }
}

//reset cache data on change
if (!function_exists('resetCacheDataOnChange')) {
    function resetCacheDataOnChange()
    {
        if (Globals::$generalSettings->refresh_cache_database_changes == 1) {
            resetCacheData();
        }
    }
}

//redirect
if (!function_exists('redirectToUrl')) {
    function redirectToUrl($url)
    {
        if (!empty($url)) {
            header('Location: ' . $url);
        }
        exit();
    }
}

//clean quotes
if (!function_exists('clrQuotes')) {
    function clrQuotes($str)
    {
        if ($str == null) {
            return '';
        }
        return str_replace(['"', "'"], '', $str);
    }
}

//generate slug
if (!function_exists('strSlug')) {
    function strSlug($str)
    {
        if (!is_scalar($str)) {
            $str = '';
        }

        $str = trim((string)$str);
        return url_title(convert_accented_characters($str), '-', true);
    }
}

//clean slug
if (!function_exists('cleanSlug')) {
    function cleanSlug($slug)
    {
        if (!is_scalar($slug)) {
            $slug = '';
        }

        $slug = trim((string)$slug);
        $slug = urldecode($slug);
        $slug = strip_tags($slug);

        return removeSpecialCharacters($slug);
    }
}

//clean string
if (!function_exists('cleanStr')) {
    function cleanStr($str)
    {
        if (!is_scalar($str)) {
            $str = '';
        }

        $str = trim((string)$str);
        $str = removeSpecialCharacters($str);
        return esc($str);
    }
}

//clean number
if (!function_exists('clrNum')) {
    function clrNum($num)
    {
        if (!isset($num)) {
            return 0;
        }

        if (!is_scalar($num) && !is_null($num)) {
            return 0;
        }

        return intval($num);
    }
}

//generate unique id
if (!function_exists('generateToken')) {
    function generateToken($isFileName = false)
    {
        $token = str_replace('.', '-', uniqid('', true));

        if ($isFileName) {
            return $token;
        }

        $token .= bin2hex(random_bytes(4));
        return hash('sha1', $token);
    }
}

//paginate
if (!function_exists('paginate')) {
    function paginate($perPage, $total)
    {
        $page = inputGet('page');
        $page = is_numeric($page) ? (int)$page : 1;
        $page = ($page < 1) ? 1 : $page;

        $pager = \Config\Services::pager();
        $pagerLinks = $pager->makeLinks($page, $perPage, $total, 'default_full');

        return (object)[
            'page' => $page,
            'offset' => ($page - 1) * $perPage,
            'links' => $pagerLinks,
        ];
    }
}

//remove forbidden characters
if (!function_exists('removeForbiddenCharacters')) {
    function removeForbiddenCharacters($str)
    {
        if ($str == null || $str == '') {
            return '';
        }

        $str = is_scalar($str) ? (string)$str : '';

        $forbiddenCharacters = [';', '"', '$', '%', '*', '/', '\'', '<', '>', '=', '?', '[', ']', '\\', '^', '`', '{', '}', '|', '~', '+'];
        $str = str_replace($forbiddenCharacters, '', $str);
        return trim($str);
    }
}

//remove special characters
if (!function_exists('removeSpecialCharacters')) {
    function removeSpecialCharacters($str)
    {
        if ($str == null || $str == '') {
            return '';
        }

        $str = removeForbiddenCharacters($str);
        $extraChars = ['#', '!', '(', ')'];
        return str_replace($extraChars, '', $str);
    }
}

//get ad codes
if (!function_exists('getAdCodes')) {
    function getAdCodes($adSpace)
    {
        $adModel = new \App\Models\AdModel();
        return $adModel->getAdCodes($adSpace);
    }
}

//get ad codes client
if (!function_exists('getAdCodesClient')) {
    function getAdCodesClient($adSpaces, $key)
    {
        if (!empty($adSpaces)) {
            foreach ($adSpaces as $adSpace) {
                if ($adSpace->ad_space == $key) {
                    return $adSpace;
                }
            }
        }

        return null;
    }
}

//get subcomments
if (!function_exists('getSubComments')) {
    function getSubcomments($parentId)
    {
        $model = new \App\Models\CommentModel();
        return $model->getSubComments($parentId);
    }
}

//is recaptcha enabled
if (!function_exists('isRecaptchaEnabled')) {
    function isRecaptchaEnabled($generalSettings)
    {
        if (!empty($generalSettings->recaptcha_site_key) && !empty($generalSettings->recaptcha_secret_key)) {
            return true;
        }
        return false;
    }
}

//get recaptcha
if (!function_exists('reCaptcha')) {
    function reCaptcha($action, $generalSettings)
    {
        if (!isRecaptchaEnabled($generalSettings)) {
            return null;
        }
        require_once APPPATH . 'Libraries/reCAPTCHA.php';

        $reCAPTCHA = new reCAPTCHA(
            $generalSettings->recaptcha_site_key,
            $generalSettings->recaptcha_secret_key
        );
        $reCAPTCHA->setLanguage(Globals::$activeLang->short_form);

        if ($action == 'generate') {
            echo $reCAPTCHA->getScript();
            echo $reCAPTCHA->getHtml();
            return null;
        }

        if ($action == 'validate') {
            $response = $_POST['g-recaptcha-response'] ?? '';
            if (!$reCAPTCHA->isValid($response)) {
                return 'invalid';
            }
        }

        return null;
    }
}

if (!function_exists('addHttpsToUrl')) {
    function addHttpsToUrl($url)
    {
        if (!is_scalar($url) || trim((string)$url) == '') {
            return '';
        }
        $url = trim((string)$url);
        if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
            $url = 'https://' . $url;
        }

        return $url;
    }
}

//generate keywords
if (!function_exists('generateKeywordsFromTitle')) {
    function generateKeywordsFromTitle($title)
    {
        if (!is_scalar($title) || trim((string)$title) == '') {
            return '';
        }

        $words = explode(' ', (string)$title);
        $keywords = [];

        foreach ($words as $word) {
            $word = trim((string)$word, " ,");
            if (strlen($word) > 2) {
                $cleaned = removeSpecialCharacters($word);
                if ($cleaned !== '') {
                    $keywords[] = $cleaned;
                }
            }
        }

        return implode(', ', $keywords);
    }
}

//date format
if (!function_exists('formatDate')) {
    function formatDate($timestamp)
    {
        if (!is_scalar($timestamp) || trim((string)$timestamp) == '') {
            return null;
        }

        $time = strtotime((string)$timestamp);
        if ($time == false) {
            return null;
        }

        return date('Y-m-d / H:i', $time);
    }
}

//date format for posts
if (!function_exists('dateFormatDefault')) {
    function dateFormatDefault($datetime)
    {
        if (!is_scalar($datetime) || trim((string)$datetime) == '') {
            return null;
        }
        $timestamp = strtotime((string)$datetime);
        if ($timestamp == false) {
            return null;
        }

        $date = date("M j, Y", $timestamp);
        $months = [
            'Jan' => trans('January'),
            'Feb' => trans('February'),
            'Mar' => trans('March'),
            'Apr' => trans('April'),
            'May' => trans('May'),
            'Jun' => trans('June'),
            'Jul' => trans('July'),
            'Aug' => trans('August'),
            'Sep' => trans('September'),
            'Oct' => trans('October'),
            'Nov' => trans('November'),
            'Dec' => trans('December'),
        ];
        return strtr($date, $months);
    }
}

//date difference in hours
if (!function_exists('dateDifferenceInHours')) {
    function dateDifferenceInHours($date1, $date2)
    {
        if (!is_scalar($date1) || !is_scalar($date2)) {
            return null;
        }

        $datetime1 = date_create((string)$date1);
        $datetime2 = date_create((string)$date2);
        if (!$datetime1 || !$datetime2) {
            return null;
        }

        $diff = date_diff($datetime1, $datetime2);
        return ($diff->days * 24) + $diff->h;
    }
}

//check time difference in hours
if (!function_exists('checkCronTime')) {
    function checkCronTime($hour)
    {
        if (empty(Globals::$generalSettings->last_cron_update) || dateDifferenceInHours(date('Y-m-d H:i:s'), Globals::$generalSettings->last_cron_update) >= $hour) {
            return true;
        }
        return false;
    }
}

//calculate passed time
if (!function_exists('timeAgo')) {
    function timeAgo($timestamp)
    {
        if (empty($timestamp)) {
            return '';
        }
        $timeAgo = strtotime($timestamp);
        if (!$timeAgo) {
            return '';
        }
        $currentTime = time();
        $timeDifference = $currentTime - $timeAgo;
        if ($timeDifference < 60) {
            return trans("just_now");
        }

        $minute = 60;
        $hour = 60 * $minute;
        $day = 24 * $hour;
        $week = 7 * $day;
        $month = 30 * $day;
        $year = 365 * $day;

        switch (true) {
            case ($timeDifference < $hour):
                $minutes = round($timeDifference / $minute);
                return $minutes == 1 ? "1 " . trans("minute_ago") : "$minutes " . trans("minutes_ago");

            case ($timeDifference < $day):
                $hours = round($timeDifference / $hour);
                return $hours == 1 ? "1 " . trans("hour_ago") : "$hours " . trans("hours_ago");

            case ($timeDifference < $month):
                $days = round($timeDifference / $day);
                return $days == 1 ? "1 " . trans("day_ago") : "$days " . trans("days_ago");

            case ($timeDifference < $year):
                $months = round($timeDifference / $month);
                return $months == 1 ? "1 " . trans("month_ago") : "$months " . trans("months_ago");

            default:
                $years = round($timeDifference / $year);
                return $years == 1 ? "1 " . trans("year_ago") : "$years " . trans("years_ago");
        }
    }
}

//check newsletter modal
if (!function_exists('checkNewsletterModal')) {
    function checkNewsletterModal()
    {
        if (Globals::$generalSettings->newsletter_status != 1 || Globals::$generalSettings->newsletter_popup != 1) {
            return false;
        }

        $cookie = helperGetCookie('newsletter_mdl');
        $session = helperGetSession('newsletter_mdl');

        if (!empty($cookie) || !empty($session)) {
            return false;
        }

        $firstVisitTime = helperGetSession('first_visit_time');

        if (empty($firstVisitTime)) {
            helperSetSession('first_visit_time', time());
            return false;
        }

        $elapsed = time() - $firstVisitTime;

        if ($elapsed >= SHOW_NEWSLETTER_POPUP_TIME) {
            helperSetCookie('newsletter_mdl', '1');
            helperSetSession('newsletter_mdl', '1');
            return true;
        }

        return false;
    }
}

//convert xml characters
if (!function_exists('convertToXMLCharacter')) {
    function convertToXMLCharacter($string)
    {
        if (!is_scalar($string) || trim((string)$string) == '') {
            return '';
        }

        $str = str_replace(
            ['&', '<', '>', '\'', '"'],
            ['&amp;', '&lt;', '&gt;', '&apos;', '&quot;'],
            (string)$string
        );

        return str_replace('#45;', '', $str);
    }
}

//get segment value
if (!function_exists('getSegmentValue')) {
    function getSegmentValue($segmentNumber)
    {
        try {
            $uri = service('uri');
            return $uri->getSegment($segmentNumber) ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }
}

//get navigation item edit link
if (!function_exists('getAdminNavItemEditLink')) {
    function getAdminNavItemEditLink($menuItem)
    {
        if (empty($menuItem) || !isset($menuItem->item_type, $menuItem->item_id)) {
            return null;
        }

        return match (true) {
            $menuItem->item_type == 'category' => adminUrl('edit-category/' . $menuItem->item_id),
            !empty($menuItem->item_link) => adminUrl('edit-menu-link/' . $menuItem->item_id),
            default => adminUrl('edit-page/' . $menuItem->item_id),
        };
    }
}

//get navigation item delete function
if (!function_exists('getAdminNavItemDeleteFunction')) {
    function getAdminNavItemDeleteFunction($menuItem)
    {
        if (empty($menuItem) || !isset($menuItem->item_type, $menuItem->item_id)) {
            return null;
        }
        if ($menuItem->item_type == 'category') {
            return "deleteItem('Category/deleteCategoryPost','{$menuItem->item_id}','" . trans('confirm_category') . "');";
        }
        if (!empty($menuItem->item_link)) {
            return "deleteItem('Admin/deleteNavigationPost','{$menuItem->item_id}','" . trans('confirm_link') . "');";
        }

        return "deleteItem('Admin/deletePagePost','{$menuItem->item_id}','" . trans('confirm_page') . "');";
    }
}

//get navigation item type
if (!function_exists('getAdminNavItemType')) {
    function getAdminNavItemType($menuItem)
    {
        if (!$menuItem) {
            return '';
        }
        if ($menuItem->item_type == 'category') {
            return trans('category');
        }
        if (!empty($menuItem->item_link)) {
            return trans('link');
        }
        return trans('page');
    }
}

//check admin nav
if (!function_exists('isAdminNavActive')) {
    function isAdminNavActive($arrayNavItems)
    {
        $segment = getSegmentValue(2);
        if ($segment && in_array($segment, $arrayNavItems, true)) {
            return ' active';
        }
        return '';
    }
}

//check if admin posts nav active
if (!function_exists('isAdminPostsNavActive')) {
    function isAdminPostsNavActive($listType)
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';

        if ($listType == 'posts') {
            $hasPosts = str_contains($requestUri, 'posts');
            $hasNoListParam = !str_contains($requestUri, 'list=');
            $isListPosts = str_contains($requestUri, 'list=posts');

            if ($hasPosts && ($hasNoListParam || $isListPosts)) {
                return ' active';
            }
        } elseif (str_contains($requestUri, 'posts?list=' . $listType)) {
            return ' active';
        }

        return '';
    }
}

//get file manager images
if (!function_exists('getFileManagerImages')) {
    function getFileManagerImages($limit)
    {
        $model = new \App\Models\FileModel();
        return $model->getImages($limit);
    }
}

//get file manager files
if (!function_exists('getFileManagerFiles')) {
    function getFileManagerFiles($limit)
    {
        $model = new \App\Models\FileModel();
        return $model->getFiles($limit);
    }
}

//get font family
if (!function_exists('getFontFamily')) {
    function getFontFamily($activeFonts, $key, $removeFamilyText = false)
    {
        $fontFamily = $activeFonts[$key]->font_family ?? null;
        if (!$fontFamily) {
            return '';
        }

        if ($removeFamilyText) {
            $fontFamilyArray = explode(':', $fontFamily);
            return $fontFamilyArray[1] ?? $fontFamilyArray[0] ?? '';
        }
        return $fontFamily;
    }
}

//get font url
if (!function_exists('getFontURL')) {
    function getFontURL($activeFonts, $key)
    {
        if (!empty($activeFonts[$key]) && !empty($activeFonts[$key]->font_url) && $activeFonts[$key]->font_source != 'local') {
            return $activeFonts[$key]->font_url;
        }
        return '';
    }
}

//get validation rules
if (!function_exists('getValRules')) {
    function getValRules($val)
    {
        $rules = $val->getRules();
        $newRules = array();
        if (!empty($rules)) {
            foreach ($rules as $key => $rule) {
                $newRules[$key] = [
                    'label' => $rule['label'],
                    'rules' => $rule['rules'],
                    'errors' => [
                        'required' => trans("form_validation_required"),
                        'min_length' => trans("form_validation_min_length"),
                        'max_length' => trans("form_validation_max_length"),
                        'matches' => trans("form_validation_matches"),
                        'is_unique' => trans("form_validation_is_unique")
                    ]
                ];
            }
        }
        return $newRules;
    }
}

//get permissions array
if (!function_exists('getPermissionsArray')) {
    function getPermissionsArray()
    {
        return ['1' => 'manage_all_posts', '2' => 'add_post', '3' => 'themes', '4' => 'navigation', '5' => 'pages', '6' => 'rss_feeds',
            '7' => 'categories', '8' => 'polls', '9' => 'gallery', '10' => 'comments', '11' => 'contact_messages', '12' => 'newsletter',
            '13' => 'ad_spaces', '14' => 'membership', '15' => 'seo_tools', '16' => 'settings', '17' => 'ai_writer', '18' => 'tags'];
    }
}

//get permission index key
if (!function_exists('getPermissionIndex')) {
    function getPermissionIndex($permission)
    {
        $array = getPermissionsArray();
        foreach ($array as $key => $value) {
            if ($value == $permission) {
                return $key;
            }
        }
        return null;
    }
}

//get permission by index
if (!function_exists('getPermissionByIndex')) {
    function getPermissionByIndex($index)
    {
        $array = getPermissionsArray();
        if (isset($array[$index])) {
            return $array[$index];
        }
        return null;
    }
}

//has permission
if (!function_exists('hasPermission')) {
    function hasPermission($permission, $permissions = null)
    {
        if (!empty($permission)) {
            if ($permissions == null) {
                if (!empty(Globals::$authUserRole->permissions)) {
                    $permissions = Globals::$authUserRole->permissions;
                }
            }
            if (!empty($permissions)) {
                if ($permissions == 'all') {
                    return true;
                }
                $array = explode(',', $permissions);
                $index = getPermissionIndex($permission);
                if (!empty($index) && in_array($index, $array)) {
                    return true;
                }
            }
        }
        return false;
    }
}

//check permission
if (!function_exists('checkPermission')) {
    function checkPermission($permission)
    {
        if (!hasPermission($permission)) {
            redirectToUrl(base_url());
            exit();
        }
    }
}

//get social links array
if (!function_exists('getSocialLinksArray')) {
    function getSocialLinksArray($obj = null, $personalWebsite = false)
    {
        $data = null;
        if (!empty($obj->social_media_data)) {
            $data = unserializeData($obj->social_media_data);
        }
        $array = array(
            array('name' => 'facebook', 'value' => !empty($data) && !empty($data['facebook']) ? $data['facebook'] : ''),
            array('name' => 'twitter', 'value' => !empty($data) && !empty($data['twitter']) ? $data['twitter'] : ''),
            array('name' => 'instagram', 'value' => !empty($data) && !empty($data['instagram']) ? $data['instagram'] : ''),
            array('name' => 'tiktok', 'value' => !empty($data) && !empty($data['tiktok']) ? $data['tiktok'] : ''),
            array('name' => 'whatsapp', 'value' => !empty($data) && !empty($data['whatsapp']) ? $data['whatsapp'] : ''),
            array('name' => 'youtube', 'value' => !empty($data) && !empty($data['youtube']) ? $data['youtube'] : ''),
            array('name' => 'discord', 'value' => !empty($data) && !empty($data['discord']) ? $data['discord'] : ''),
            array('name' => 'telegram', 'value' => !empty($data) && !empty($data['telegram']) ? $data['telegram'] : ''),
            array('name' => 'pinterest', 'value' => !empty($data) && !empty($data['pinterest']) ? $data['pinterest'] : ''),
            array('name' => 'linkedin', 'value' => !empty($data) && !empty($data['linkedin']) ? $data['linkedin'] : ''),
            array('name' => 'twitch', 'value' => !empty($data) && !empty($data['twitch']) ? $data['twitch'] : ''),
            array('name' => 'vk', 'value' => !empty($data) && !empty($data['vk']) ? $data['vk'] : '')
        );
        if ($personalWebsite == true) {
            array_push($array, array('name' => 'personal_website_url', 'value' => !empty($data) && !empty($data['personal_website_url']) ? $data['personal_website_url'] : ''));
        }
        return $array;
    }
}

//get aws base url
if (!function_exists('getAWSBaseURL')) {
    function getAWSBaseURL()
    {
        return 'https://s3.' . Globals::$generalSettings->aws_region . '.amazonaws.com/' . Globals::$generalSettings->aws_bucket . '/';
    }
}

//get post image base URL
if (!function_exists('getBaseURLByStorage')) {
    function getBaseURLByStorage($storage)
    {
        $baseURL = base_url() . '/';
        if ($storage == 'aws_s3') {
            $baseURL = getAWSBaseURL();
        }
        return $baseURL;
    }
}

//get ai writer
if (!function_exists('aiWriter')) {
    function aiWriter()
    {
        $data = unserializeData(Globals::$generalSettings->ai_writer);
        $aiWriter = new \stdClass();
        $aiWriter->status = !empty($data['status']) ? true : false;
        $aiWriter->apiKey = !empty($data['api_key']) ? $data['api_key'] : '';
        return $aiWriter;
    }
}

//get newsletter image
if (!function_exists('getNewsletterImage')) {
    function getNewsletterImage()
    {
        if (!empty(Globals::$generalSettings->newsletter_image) && file_exists(FCPATH . Globals::$generalSettings->newsletter_image)) {
            return base_url(Globals::$generalSettings->newsletter_image);
        }
        return base_url("assets/img/newsletter.webp");
    }
}

//set success message
if (!function_exists('setSuccessMessage')) {
    function setSuccessMessage($message, $trans = true)
    {
        if (!empty($message)) {
            $session = \Config\Services::session();
            if ($trans == true) {
                $message = trans($message);
            }
            $session->setFlashdata('success', $message);
        }
    }
}

//set error message
if (!function_exists('setErrorMessage')) {
    function setErrorMessage($message, $trans = true)
    {
        if (!empty($message)) {
            $session = \Config\Services::session();
            if ($trans == true) {
                $message = trans($message);
            }
            $session->setFlashdata('error', $message);
        }
    }
}

//convert number short version
if (!function_exists('numberFormatShort')) {
    function numberFormatShort($n, $prec = 1)
    {
        if (!is_numeric($n)) {
            return '0';
        }

        $n = (float)$n;
        $negative = $n < 0;
        $n = abs($n);

        $units = [
            1_000_000_000_000 => trans('number_short_trillion') ?? 'T',
            1_000_000_000 => trans('number_short_billion') ?? 'B',
            1_000_000 => trans('number_short_million') ?? 'M',
            1_000 => trans('number_short_thousand') ?? 'K',
        ];

        foreach ($units as $threshold => $suffix) {
            if ($n >= $threshold) {
                $nFormat = number_format($n / $threshold, $prec);
                break;
            }
        }

        if (!isset($nFormat)) {
            $nFormat = number_format($n, $prec);
            $suffix = '';
        }

        if ($prec > 0) {
            $dotzero = '.' . str_repeat('0', $prec);
            $nFormat = str_replace($dotzero, '', $nFormat);
        }

        return ($negative ? '-' : '') . $nFormat . $suffix;
    }
}

//create form checkbox
if (!function_exists('formCheckbox')) {
    function formCheckbox($inputName, $val, $text, $checkedValue = null)
    {
        $id = 'c' . uniqid();
        $check = $checkedValue == $val ? ' checked' : '';
        return '<div class="custom-control custom-checkbox">' . PHP_EOL .
            '<input type="checkbox" name="' . $inputName . '" value="' . $val . '" id="' . $id . '" class="custom-control-input"' . $check . '>' . PHP_EOL .
            '<label for="' . $id . '" class="custom-control-label">' . $text . '</label>' . PHP_EOL .
            '</div>';
    }
}

//create form radio button
if (!function_exists('formRadio')) {
    function formRadio($inputName, $val1, $val2, $op1Text, $op2Text, $checkedValue = null, $colClass = 'col-md-6')
    {
        $id1 = 'r' . uniqid();
        $id2 = 'r' . uniqid();
        $op1Check = $checkedValue == $val1 ? ' checked' : '';
        $op2Check = $checkedValue != $val1 ? ' checked' : '';
        return
            '<div class="row">' . PHP_EOL .
            '    <div class="' . $colClass . ' col-sm-12">' . PHP_EOL .
            '        <div class="custom-control custom-radio">' . PHP_EOL .
            '            <input type="radio" name="' . $inputName . '" value="' . $val1 . '" id="' . $id1 . '" class="custom-control-input"' . $op1Check . '>' . PHP_EOL .
            '            <label for="' . $id1 . '" class="custom-control-label">' . $op1Text . '</label>' . PHP_EOL .
            '        </div>' . PHP_EOL .
            '    </div>' . PHP_EOL .
            '    <div class="' . $colClass . ' col-sm-12">' . PHP_EOL .
            '         <div class="custom-control custom-radio">' . PHP_EOL .
            '             <input type="radio" name="' . $inputName . '" value="' . $val2 . '" id="' . $id2 . '" class="custom-control-input"' . $op2Check . '>' . PHP_EOL .
            '             <label for="' . $id2 . '" class="custom-control-label">' . $op2Text . '</label>' . PHP_EOL .
            '        </div>' . PHP_EOL .
            '    </div>' . PHP_EOL .
            '</div>';
    }
}