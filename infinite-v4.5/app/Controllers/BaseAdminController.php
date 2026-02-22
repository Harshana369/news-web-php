<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Globals;
use App\Models\AuthModel;
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
abstract class BaseAdminController extends Controller
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
    public $aiWriter;
    public $perPage;

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
        //check auth
        if (!isAdmin() && !isAuthor()) {
            redirectToUrl(adminUrl('login'));
            exit();
        }

        $this->settingsModel = new SettingsModel();
        //general settings
        $this->generalSettings = Globals::$generalSettings;
        //settings
        $this->settings = Globals::$settings;
        //languages
        $this->languages = Globals::$languages;
        //set Admin language
        Globals::setActiveLanguage($this->session->get('inf_admin_lang_id'));
        //active language
        $this->activeLang = Globals::$activeLang;
        //ai writer
        $this->aiWriter = aiWriter();
        //per page
        $this->perPage = 15;
        if (!empty(clrNum(inputGet('show')))) {
            $this->perPage = clrNum(inputGet('show'));
        }

        //view variables
        $view = \Config\Services::renderer();
        $view->setData(['activeLang' => $this->activeLang, 'generalSettings' => $this->generalSettings, 'settings' => $this->settings, 'languages' => $this->languages, 'baseAIWriter' => $this->aiWriter]);

        //maintenance mode
        if ($this->generalSettings->maintenance_mode_status == 1) {
            $router = \Config\Services::router();
            if (strpos($router->controllerName(), 'CommonController') === false) {
                if (!isAdmin()) {
                    $authModel = new AuthModel();
                    $authModel->logout();
                    redirectToUrl(adminUrl('login'));
                    exit();
                }
            }
        }
    }
}
