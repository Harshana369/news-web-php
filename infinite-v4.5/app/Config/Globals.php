<?php

namespace Config;

use App\Models\PostModel;
use CodeIgniter\Config\BaseConfig;
use \App\Models\AuthModel;

class Globals extends BaseConfig
{
    private static $db = null;
    public static $generalSettings = array();
    public static $settings = array();
    public static $languages = array();
    public static $languagesAll = array();
    public static $defaultLang = array();
    public static $languageTranslations = array();
    public static $activeLang = array();
    public static $langBaseUrl = "";
    public static $authCheck = false;
    public static $authUser = null;
    public static $authUserRole = null;

    public static function setGlobals()
    {
        self::$db = \Config\Database::connect();
        $session = \Config\Services::session();
        //set general settings
        self::$generalSettings = self::$db->table('general_settings')->where('id', 1)->get()->getRow();
        //set timezone
        if (!empty(self::$generalSettings->timezone)) {
            date_default_timezone_set(self::$generalSettings->timezone);
        }
        //set languages
        self::$languagesAll = self::getLanguages();
        self::$languages = array_filter(self::$languagesAll, function ($lang) {
            return isset($lang->status) && $lang->status == 1;
        });

        //set active language
        self::setDefaultLanguage();
        if (empty(self::$defaultLang)) {
            self::$defaultLang = self::$db->table('languages')->get()->getFirstRow();
        }
        $langSegment = getSegmentValue(1);
        $langId = null;
        if (!empty(self::$languages)) {
            foreach (self::$languages as $lang) {
                if ($langSegment == $lang->short_form) {
                    $langId = $lang->id;
                    break;
                }
            }
        }

        if (empty($langId)) {
            $langId = self::$defaultLang->id;
        }
        self::setActiveLanguage($langId);
        if (empty(self::$activeLang)) {
            self::$activeLang = self::$defaultLang;
        }
        $session->set('activeLangId', self::$activeLang->id);
        //set language base URL
        self::$langBaseUrl = base_url(self::$activeLang->short_form);
        if (self::$activeLang->id == self::$defaultLang->id) {
            self::$langBaseUrl = base_url();
        }
        //set settings
        self::$settings = self::getSettings(self::$activeLang->id);
        //authentication
        if (!empty($session->get('auth_user_id')) && !empty($session->get('auth_token'))) {
            $user = self::$db->table('users')->where('id', clrNum($session->get('auth_user_id')))->get()->getRow();
            if (!empty($user) && $user->status == 1 && $user->session_token == $session->get('auth_token')) {
                self::$authCheck = true;
                self::$authUser = $user;
                self::$authUserRole = self::$db->table('roles_permissions')->where('id', $user->role_id)->get()->getRow();
            } else {
                helperDeleteSession('auth_user_id');
                helperDeleteSession('auth_token');
            }
        }
    }

    //set active language
    public static function setActiveLanguage($langId)
    {
        if (!empty(self::$languages)) {
            foreach (self::$languages as $lang) {
                if ($langId == $lang->id) {
                    self::$activeLang = $lang;
                    //set language translations
                    self::$languageTranslations = self::getLanguageTranslations(self::$activeLang->id);
                    $arrayTranslations = array();
                    if (!empty(self::$languageTranslations)) {
                        foreach (self::$languageTranslations as $item) {
                            $arrayTranslations[$item->label] = $item->translation;
                        }
                    }
                    self::$languageTranslations = $arrayTranslations;
                    break;
                }
            }
        }
    }

    //get languages
    private static function getLanguages()
    {
        return getCacheData('languages', function () {
            return self::$db->table('languages')->get()->getResult();
        }, 'static');
    }

    //get language translations
    private static function getLanguageTranslations($langId)
    {
        return getCacheData('language_translations_lang_' . $langId, function () use ($langId) {
            return self::$db->table('language_translations')->where('lang_id', $langId)->get()->getResult();
        }, 'static');
    }

    //get settings
    private static function getSettings($langId)
    {
        return getCacheData('settings_lang_' . $langId, function () use ($langId) {
            return self::$db->table('settings')->where('lang_id', $langId)->get()->getRow();
        }, 'static');
    }

    //set default language
    private static function setDefaultLanguage()
    {
        if (!empty(self::$languages)) {
            foreach (self::$languages as $lang) {
                if (self::$generalSettings->site_lang == $lang->id) {
                    self::$defaultLang = $lang;
                }
            }
        }
    }
}

Globals::setGlobals();
