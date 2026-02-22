<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| CUSTOM CONSTANTS
|--------------------------------------------------------------------------
*/
defined('INFINITE_VERSION')                                     || define('INFINITE_VERSION', '4.5');
//database
defined('FORCE_DB_INDEXES')                                     || define('FORCE_DB_INDEXES', TRUE);
//cache
defined('STABLE_CACHE_SYSTEM')                                  || define('STABLE_CACHE_SYSTEM', 1); // Active = 1, InActive = 0
defined('STATIC_CACHE_REFRESH_TIME')                            || define('STATIC_CACHE_REFRESH_TIME', 604800); // 7 days - Cache for Static Data (Settings, Pages, Categories etc.)
defined('STABLE_CACHE_REFRESH_TIME')                            || define('STABLE_CACHE_REFRESH_TIME', 7200); // 2 hours - Cache for Popular Posts, Popular Tags
defined('RSS_CACHE_REFRESH_TIME')                               || define('RSS_CACHE_REFRESH_TIME', 3600); // 10 minutes - Cache for RSS Posts
//application
defined('POSTS_LIMIT_SLIDER')                                   || define('POSTS_LIMIT_SLIDER', 20);
defined('POSTS_LIMIT_POPULAR_POSTS')                            || define('POSTS_LIMIT_POPULAR_POSTS', 5);
defined('POSTS_LIMIT_OUR_PICKS')                                || define('POSTS_LIMIT_OUR_PICKS', 5);
defined('POSTS_LIMIT_RANDOM_POSTS')                             || define('POSTS_LIMIT_RANDOM_POSTS', 5);
defined('COMMENTS_LIMIT')                                       || define('COMMENTS_LIMIT', 6);
defined('POST_TAGS_LIMIT')                                      || define('POST_TAGS_LIMIT', 15);
defined('SIDEBAR_TAGS_LIMIT')                                   || define('SIDEBAR_TAGS_LIMIT', 15);
defined('SITEMAP_URL_LIMIT')                                    || define('SITEMAP_URL_LIMIT', 49000);
defined('RSS_POSTS_LIMIT')                                      || define('RSS_POSTS_LIMIT', 50);
defined('POST_TITLE_DISPLAY_LIMIT')                             || define('POST_TITLE_DISPLAY_LIMIT', 55); //55 characters
defined('POST_TITLE_DISPLAY_LIMIT_LONG')                        || define('POST_TITLE_DISPLAY_LIMIT_LONG', 70); //70 characters
defined('POST_SUMMARY_DISPLAY_LIMIT')                           || define('POST_SUMMARY_DISPLAY_LIMIT', 130); //130 characters
defined('SHOW_NEWSLETTER_POPUP_TIME')                           || define('SHOW_NEWSLETTER_POPUP_TIME', 15); //Show after 15 seconds
