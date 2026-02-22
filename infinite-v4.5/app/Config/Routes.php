<?php

use CodeIgniter\Router\RouteCollection;

$languages = \Config\Globals::$languages;
$generalSettings = \Config\Globals::$generalSettings;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->post('contact-post', 'HomeController::contactPost');
$routes->post('edit-profile-post', 'ProfileController::editProfilePost');
$routes->post('social-accounts-post', 'ProfileController::socialAccountsPost');
$routes->post('delete-account-post', 'ProfileController::deleteAccountPost');
$routes->post('change-password-post', 'ProfileController::changePasswordPost');
$routes->post('follow-unfollow-user', 'ProfileController::followUnfollowUser');
$routes->post('add-remove-reading-list-post', 'HomeController::addRemoveFromReadingListPost');
$routes->post('download-file', 'HomeController::downloadFile');
/*
 * --------------------------------------------------------------------
 * Auth Routes
 * --------------------------------------------------------------------
 */

$routes->post('login-post', 'AuthController::loginPost');
$routes->post('register-post', 'AuthController::registerPost');
$routes->get('forgot-password', 'AuthController::forgotPassword');
$routes->post('forgot-password-post', 'AuthController::forgotPasswordPost');
$routes->get('reset-password', 'AuthController::resetPassword');
$routes->post('reset-password-post', 'AuthController::resetPasswordPost');
$routes->get('connect-with-facebook', 'AuthController::connectWithFacebook');
$routes->get('facebook-callback', 'AuthController::facebookCallback');
$routes->get('connect-with-google', 'AuthController::connectWithGoogle');

/*
 * --------------------------------------------------------------------
 * Cron Routes
 * --------------------------------------------------------------------
 */

$routes->get('cron/update-sitemap', 'CronController::updateSitemap');
$routes->get('cron/update-feeds', 'CronController::checkFeedPosts');

/*
 * --------------------------------------------------------------------
 * Admin Routes
 * --------------------------------------------------------------------
 */

$routeAdmin = !empty($generalSettings->admin_route) ? $generalSettings->admin_route : 'admin';
$routes->get($routeAdmin . '/login', 'CommonController::adminLogin');
$routes->post($routeAdmin . '/login-post', 'CommonController::adminLoginPost');

$routes->group($routeAdmin, ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->get('themes', 'AdminController::themes');
    $routes->get('navigation', 'AdminController::navigation');
    $routes->get('edit-menu-link/(:num)', 'AdminController::editMenuLink/$1');
    $routes->get('add-page', 'AdminController::addPage');
    $routes->get('pages', 'AdminController::pages');
    $routes->get('edit-page/(:num)', 'AdminController::editPage/$1');
    //post
    $routes->get('add-post', 'PostController::addPost');
    $routes->get('edit-post/(:num)', 'PostController::editPost/$1');
    $routes->get('add-video', 'PostController::addVideo');
    $routes->get('posts', 'PostController::posts');
    $routes->get('auto-post-deletion', 'PostController::autoPostDeletion');
    //rss feeds
    $routes->get('import-feed', 'RssController::importFeed');
    $routes->get('feeds', 'RssController::rssFeeds');
    $routes->get('edit-feed/(:num)', 'RssController::editFeed/$1');
    //categorry
    $routes->get('categories', 'CategoryController::categories');
    $routes->get('add-category', 'CategoryController::addCategory');
    $routes->get('edit-category/(:num)', 'CategoryController::editCategory/$1');
    $routes->get('tags', 'CategoryController::tags');
    //gallery
    $routes->get('gallery', 'GalleryController::gallery');
    $routes->get('edit-gallery-image/(:num)', 'GalleryController::editGalleryImage/$1');
    $routes->get('gallery-albums', 'GalleryController::galleryAlbums');
    $routes->get('edit-gallery-album/(:num)', 'GalleryController::editGalleryAlbum/$1');
    $routes->get('gallery-categories', 'GalleryController::galleryCategories');
    $routes->get('edit-gallery-category/(:num)', 'GalleryController::editGallerCategory/$1');
    //comments
    $routes->get('comments', 'AdminController::comments');
    //poll
    $routes->get('add-poll', 'AdminController::addPoll');
    $routes->get('add-poll-post', 'AdminController::addPollPost');
    $routes->get('polls', 'AdminController::polls');
    $routes->get('edit-poll/(:num)', 'AdminController::editPoll/$1');
    //seo tools
    $routes->get('seo-tools', 'AdminController::seoTools');
    //cache
    $routes->get('cache-system', 'AdminController::cacheSystem');
    $routes->get('contact-messages', 'AdminController::contactMessages');
    //newsletter
    $routes->get('newsletter', 'AdminController::newsletter');
    $routes->get('newsletter-send-email', 'AdminController::newsletterSendEmail');
    //users
    $routes->get('users', 'AdminController::users');
    $routes->get('add-user', 'AdminController::addUser');
    $routes->get('edit-user/(:num)', 'AdminController::editUser/$1');
    $routes->get('roles-permissions', 'AdminController::rolesPermissions');
    $routes->get('add-role', 'AdminController::addRole');
    $routes->get('edit-role/(:num)', 'AdminController::editRole/$1');
    //settings
    $routes->get('storage', 'AdminController::storage');
    $routes->get('social-login-settings', 'AdminController::socialLoginSettings');
    $routes->get('ad-spaces', 'AdminController::adSpaces');
    $routes->get('preferences', 'AdminController::preferences');
    $routes->get('settings', 'AdminController::settings');
    $routes->get('font-settings', 'AdminController::fontSettings');
    $routes->get('edit-font/(:num)', 'AdminController::editFont/$1');
    $routes->get('language-settings', 'LanguageController::languageSettings');
    $routes->get('edit-language/(:num)', 'LanguageController::editLanguage/$1');
    $routes->get('translations/(:num)', 'LanguageController::translations/$1');
    $routes->get('email-settings', 'AdminController::emailSettings');
});

/*
 * --------------------------------------------------------------------
 * Static POST Routes
 * --------------------------------------------------------------------
 */

$postRoutesArray = [
    //Admin
    'Admin/adSpacesPost',
    'Admin/googleAdsenseCodePost',
    'Admin/cacheSystemPost',
    'Admin/approveCommentPost',
    'Admin/deleteCommentPost',
    'Admin/deleteContactMessagePost',
    'Admin/emailSettingsPost',
    'Admin/emailOptionsPost',
    'Admin/sendTestEmailPost',
    'Admin/preferencesPost',
    'Admin/aiWriterPost',
    'Admin/fileUploadSettingsPost',
    'Admin/seoToolsPost',
    'Admin/sitemapSettingsPost',
    'Admin/sitemapPost',
    'Admin/settingsPost',
    'Admin/recaptchaSettingsPost',
    'Admin/maintenanceModePost',
    'Admin/socialLoginSettingsPost',
    'Admin/setModePost',
    'Admin/setThemePost',
    'Admin/setThemeSettingsPost',
    'Admin/setSiteFontPost',
    'Admin/addFontPost',
    'Admin/deleteFontPost',
    'Admin/editFontPost',
    'Admin/setActiveLanguagePost',
    'Admin/downloadDatabaseBackup',
    'Admin/deletePagePost',
    'Admin/addMenuLinkPost',
    'Admin/menuLimitPost',
    'Admin/deleteNavigationPost',
    'Admin/editMenuLinkPost',
    'Admin/deleteSubscriberPost',
    'Admin/newsletterSettingsPost',
    'Admin/newsletterSelectEmailsPost',
    'Admin/newsletterSendEmailPost',
    'Admin/awsS3Post',
    'Admin/storagePost',
    'Admin/addPagePost',
    'Admin/editPagePost',
    'Admin/addPollPost',
    'Admin/deletePollPost',
    'Admin/editPollPost',
    'Admin/addRolePost',
    'Admin/addUserPost',
    'Admin/editRolePost',
    'Admin/editUserPost',
    'Admin/deleteRolePost',
    'Admin/roleSettingsPost',
    'Admin/userOptionsPost',
    'Admin/deleteUserPost',
    'Admin/changeUserRolePost',
    'Admin/getMenuLinksByLang',
    'Admin/approveSelectedComments',
    'Admin/deleteSelectedComments',
    'Admin/deleteSelectedContactMessagesPost',
    'Admin/hideShowHomeLink',
    'Admin/sortMenuItems',
    //AJAX
    'Ajax/generateTextAI',
    'Ajax/switchThemeMode',
    'Ajax/loadMoreSearchPosts',
    'Ajax/loadMoreUsers',
    'Ajax/loadMoreSubscribers',
    'Ajax/addReactionPost',
    'Ajax/addPollVotePost',
    'Ajax/addCommentPost',
    'Ajax/loadMoreCommentPost',
    'Ajax/loadSubcommentBox',
    'Ajax/deleteCommentPost',
    'Ajax/addToNewsletterPost',
    'Ajax/getTagSuggestions',
    'Ajax/closeCookiesWarningPost',
    //Category
    'Category/addCategoryPost',
    'Category/deleteCategoryPost',
    'Category/editCategoryPost',
    'Category/deleteCategoryPost',
    'Category/getSubCategories',
    'Category/getParentCategoriesByLang',
    'Category/addTagPost',
    'Category/editTagPost',
    'Category/deleteTagPost',
    //File
    'File/downloadFile',
    'File/uploadFile',
    'File/uploadImageFile',
    'File/getImages',
    'File/deleteImageFile',
    'File/loadMoreImages',
    'File/searchImageFile',
    'File/deleteFile',
    'File/getFiles',
    'File/loadMoreFiles',
    'File/searchFile',
    //Gallery
    'Gallery/addGalleryAlbumPost',
    'Gallery/deleteGalleryAlbumPost',
    'Gallery/addGalleryCategoryPost',
    'Gallery/deleteGalleryCategoryPost',
    'Gallery/editGalleryImagePost',
    'Gallery/editGalleryAlbumPost',
    'Gallery/editGalleryCategoryPost',
    'Gallery/addGalleryImagePost',
    'Gallery/deleteGalleryImagePost',
    'Gallery/galleryAlbumsByLang',
    'Gallery/galleryCategoriesByAlbum',
    'Gallery/setAsAlbumCover',
    //Language
    'Language/setDefaultLanguagePost',
    'Language/exportLanguagePost',
    'Language/deleteLanguagePost',
    'Language/addLanguagePost',
    'Language/importLanguagePost',
    'Language/editTranslationsPost',
    'Language/editLanguagePost',
    //Post
    'Post/addPostPost',
    'Post/autoPostDeletionPost',
    'Post/postOptionsPost',
    'Post/deletePost',
    'Post/editPostPost',
    'Post/homeSliderPostsOrderPost',
    'Post/deleteSelectedPosts',
    'Post/deletePostMainImage',
    'Post/deletePostAdditionalImage',
    'Post/deletePostFile',
    'Post/deletePostMainImage',
    'Post/getVideoFromURL',
    //RSS
    'Rss/checkFeedPosts',
    'Rss/deleteFeedPost',
    'Rss/importFeedPost',
    'Rss/editFeedPost',
];

foreach ($postRoutesArray as $item) {
    $array = explode('/', $item);
    $routes->post($item, $array[0] . 'Controller::' . $array[1]);
}

/*
 * --------------------------------------------------------------------
 * Dynamic Routes
 * --------------------------------------------------------------------
 */

if (!empty($languages)) {
    foreach ($languages as $language) {
        $key = "";
        if ($generalSettings->site_lang != $language->id) {
            $key = $language->short_form;
            $routes->get($key, 'HomeController::index');
        }

        $routes->get($key . '/error-404', 'HomeController::error404');
        $routes->get($key . '/gallery', 'HomeController::gallery');
        $routes->get($key . '/gallery/album/(:num)', 'HomeController::galleryAlbum/$1');
        $routes->get($key . '/contact', 'HomeController::contact');
        $routes->get($key . '/profile/(:any)', 'ProfileController::profile/$1');
        $routes->get($key . '/tag/(:any)', 'HomeController::tag/$1');
        $routes->get($key . '/reading-list', 'HomeController::readingList');
        $routes->get($key . '/search', 'HomeController::search');
        //rss routes
        $routes->get($key . '/rss-feeds', 'HomeController::rssFeeds');
        $routes->get($key . '/rss/latest-posts', 'HomeController::rssLatestPosts');
        $routes->get($key . '/rss/author/(:any)', 'HomeController::rssByUser/$1');
        $routes->get($key . '/rss/category/(:any)/(:num)', 'HomeController::rssByCategory/$1/$2');
        //profile routes
        $routes->get($key . '/settings', 'ProfileController::editProfile', ['filter' => 'auth']);
        $routes->get($key . '/settings/social-accounts', 'ProfileController::socialAccounts', ['filter' => 'auth']);
        $routes->get($key . '/settings/change-password', 'ProfileController::changePassword', ['filter' => 'auth']);
        $routes->get($key . '/settings/delete-account', 'ProfileController::deleteAccount', ['filter' => 'auth']);
        //auth routes
        $routes->get($key . '/login', 'AuthController::login');
        $routes->get($key . '/register', 'AuthController::register');
        $routes->get($key . '/forgot-password', 'AuthController::forgotPassword');
        $routes->get($key . '/reset-password', 'AuthController::resetPassword');
        $routes->get($key . '/logout', 'CommonController::logout');
        $routes->get($key . '/unsubscribe', 'AuthController::unsubscribe');

        if ($generalSettings->site_lang != $language->id) {
            $routes->get($key . '(:any)/(:any)/(:any)', 'HomeController::error404');
            $routes->get($key . '/(:any)/(:any)', 'HomeController::subcategory/$1/$2');
            $routes->get($key . '/(:any)', 'HomeController::any/$1');
        }
    }
}

$routes->get('(:any)/(:any)/(:any)', 'HomeController::error404');
$routes->get('(:any)/(:any)', 'HomeController::subcategory/$1/$2');
$routes->get('(:any)', 'HomeController::any/$1');