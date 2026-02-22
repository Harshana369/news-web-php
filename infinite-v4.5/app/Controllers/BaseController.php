<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\CommonModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Globals;
use App\Models\AdModel;
use App\Models\AuthModel;
use App\Models\PostAdminModel;
use App\Models\SettingsModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['text', 'security', 'cookie'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    public $session;
    public $authModel;
    public $settingsModel;
    public $generalSettings;
    public $settings;
    public $languages;
    public $activeLang;
    public $activeFonts;
    public $darkMode;
    public $rtl;
    public $menuLinks;
    public $categories;
    public $paginationPerPage;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();

        $this->authModel = new AuthModel();
        $this->settingsModel = new SettingsModel();
        //general settings
        $this->generalSettings = Globals::$generalSettings;
        //settings
        $this->settings = Globals::$settings;
        //languages
        $this->languages = Globals::$languages;
        //active lang
        $this->activeLang = Globals::$activeLang;
        //site fonts
        $this->activeFonts = $this->getSelectedFonts($this->settings);
        //dark mode
        $this->darkMode = $this->generalSettings->dark_mode;
        if (!empty(helperGetCookie('theme_mode'))) {
            if (helperGetCookie('theme_mode') == 'dark') {
                $this->darkMode = 1;
            } else {
                $this->darkMode = 0;
            }
        }

        //dark mode
        $this->rtl = false;
        //menu links
        $commonModel = new CommonModel();
        $this->menuLinks = $commonModel->getMenuLinks($this->activeLang->id);
        //categories
        $this->categories = $this->getCategories($this->activeLang->id);
        //ad spaces
        $adSpaces = $this->getAdSpaces($this->activeLang->id);
        //pagination
        $this->paginationPerPage = $this->generalSettings->pagination_per_page;

        //post list style
        $postListStyle = 'default';
        if ($this->generalSettings->layout == "layout_3" || $this->generalSettings->layout == "layout_6") {
            $postListStyle = 'boxed';
        }elseif ($this->generalSettings->layout == 'layout_2' || $this->generalSettings->layout == 'layout_5') {
            $postListStyle = 'horizontal';
        }

        if (checkCronTime(1)) {
            //delete old sessions
            $this->settingsModel->deleteOldSessions();
            //delete old posts
            if ($this->generalSettings->auto_post_deletion == 1) {
                $postModel = new PostAdminModel();
                $postModel->deleteOldPosts();
            }
        }

        //update last seen
        $this->authModel->updateLastSeen();

        //view variables
        $view = \Config\Services::renderer();
        $view->setData(['activeLang' => $this->activeLang, 'generalSettings' => $this->generalSettings, 'settings' => $this->settings, 'languages' => $this->languages, 'activeFonts' => $this->activeFonts,
            'darkMode' => $this->darkMode, 'rtl' => $this->rtl, 'menuLinks' => $this->menuLinks, 'baseCategories' => $this->categories, 'adSpaces' => $adSpaces, 'postListStyle' => $postListStyle]);

        //maintenance mode
        if ($this->generalSettings->maintenance_mode_status == 1) {
            $router = \Config\Services::router();
            if (strpos($router->controllerName(), 'CommonController') === false) {
                if (!isAdmin()) {
                    echo view('maintenance');
                }
            }
        }
    }

    //get fonts
    private function getSelectedFonts($settings)
    {
        return getCacheData('fonts_' . $settings->id, function () use ($settings) {
            return $this->settingsModel->getSelectedFonts($settings);
        }, 'static');
    }

    //get categories
    private function getCategories($langId)
    {
        return getCacheData('categories_lang' . $langId, function () use ($langId) {
            $model = new CategoryModel();
            return $model->getCategories($langId);
        }, 'static');
    }

    //get ad spaces
    private function getAdSpaces($langId)
    {
        return getCacheData('ad_spaces_lang' . $langId, function () use ($langId) {
            $model = new AdModel();
            return $model->getAdSpacesByLang($langId);
        }, 'static');
    }
}
