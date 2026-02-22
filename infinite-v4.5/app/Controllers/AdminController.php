<?php

namespace App\Controllers;

use App\Models\AdModel;
use App\Models\CommentModel;
use App\Models\CommonModel;
use App\Models\EmailModel;
use App\Models\NewsletterModel;
use App\Models\PageModel;
use App\Models\PollModel;
use App\Models\PostAdminModel;
use App\Models\SettingsModel;
use App\Models\SitemapModel;

class AdminController extends BaseAdminController
{
    protected $commonModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->commonModel = new CommonModel();
    }

    /**
     * Index Page
     */
    public function index()
    {
        $data['title'] = trans("index");
        $data['userCount'] = $this->authModel->getUserCount();
        $commentModel = new CommentModel();
        $data['lastComments'] = $commentModel->getLastComments(5);
        $data['lastPendingComments'] = $commentModel->getLastComments(5, 'pending');
        $data['lastContacts'] = $this->commonModel->getLastContactMessages();
        $data['lastUsers'] = $this->authModel->getLastAddedUsers();
        $postAdminModel = new PostAdminModel();
        
        $data['pendingPostCount'] = $postAdminModel->getPostsCount('pending-posts');
        $data['postCount'] = $postAdminModel->getPostsCount('posts');
        $data['draftCount'] = $postAdminModel->getPostsCount('drafts');

        echo view('admin/includes/_header', $data);
        echo view('admin/index', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Themes
     */
    public function themes()
    {
        checkPermission('themes');
        $data['title'] = trans("themes");
        echo view('admin/includes/_header', $data);
        echo view('admin/themes', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Set Mode Post
     */
    public function setModePost()
    {
        checkPermission('themes');
        $this->settingsModel->setThemeMode();
        return redirect()->to(adminUrl('themes'));
    }

    /**
     * Set Theme Post
     */
    public function setThemePost()
    {
        checkPermission('themes');
        $this->settingsModel->setTheme();
        return redirect()->to(adminUrl('themes'));
    }

    /**
     * Set Theme Settings Post
     */
    public function setThemeSettingsPost()
    {
        checkPermission('themes');
        $this->settingsModel->setThemeSettings();
        setSuccessMessage("msg_updated");
        return redirect()->to(adminUrl('themes'));
    }

    /**
     * Navigation
     */
    public function navigation()
    {
        checkPermission('navigation');
        $data["selectedLang"] = inputGet("lang");
        if (empty($data["selectedLang"])) {
            $data["selectedLang"] = $this->activeLang->id;
            return redirect()->to(adminUrl('navigation?lang=' . $data["selectedLang"]));
        }
        $data['title'] = trans("navigation");
        
        $data['menuLinks'] = $this->commonModel->getMenuLinks($data["selectedLang"], true);

        echo view('admin/includes/_header', $data);
        echo view('admin/navigation/navigation', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Hide Show Home Link
     */
    public function hideShowHomeLink()
    {
        checkPermission('navigation');
        $this->commonModel->hideShowHomeLink();
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Sort Menu Items
     */
    public function sortMenuItems()
    {
        checkPermission('navigation');
        $this->commonModel->sortMenuItems();
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Add Menu Link Post
     */
    public function addMenuLinkPost()
    {
        checkPermission('navigation');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            if ($this->commonModel->addNavLink()) {
                setSuccessMessage("msg_item_added");
                redirectToBackURL();
            }
        }
        setErrorMessage("msg_error");
        return redirect()->back()->withInput();
    }

    /**
     * Edit Menu Link
     */
    public function editMenuLink($id)
    {
        checkPermission('navigation');
        $data['title'] = trans("navigation");
        $model = new PageModel();
        $data['page'] = $model->getPage($id);
        if (empty($data['page'])) {
            redirectToBackURL();
        }
        $data['menuItems'] = $this->commonModel->getMenuLinks($data['page']->lang_id);

        echo view('admin/includes/_header', $data);
        echo view('admin/navigation/edit_navigation', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Update Menü Link Post
     */
    public function editMenuLinkPost()
    {
        checkPermission('navigation');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $id = inputPost('id');
            if ($this->commonModel->editNavLink($id)) {
                setSuccessMessage("msg_updated");
                return redirect()->to(adminUrl('navigation'));
            }
        }
        setErrorMessage("msg_error");
        redirectToBackURL();
    }

    /**
     * Delete Navigation Post
     */
    public function deleteNavigationPost()
    {
        checkPermission('navigation');
        $id = inputPost('id');
        $model = new PageModel();
        if ($model->deletePage($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Menu Limit Post
     */
    public function menuLimitPost()
    {
        checkPermission('navigation');
        if ($this->commonModel->updateMenuLimit()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    //get menu links by language
    public function getMenuLinksByLang()
    {
        $langId = inputPost('lang_id');
        $data = ['status' => 0, 'content' => ''];
        if (!empty($langId)) {
            $menuItems = $this->commonModel->getMenuLinks($langId);
            foreach ($menuItems as $menuItem) {
                if ($menuItem->item_type != "category" && $menuItem->item_location == "header" && $menuItem->item_parent_id == "0") {
                    $data['content'] .= '<option value="' . $menuItem->item_id . '">' . esc($menuItem->item_name) . '</option>';
                }
                $data['status'] = 1;
            }
        }
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * -------------------------------------------------------------------------------------------
     * PAGES
     * -------------------------------------------------------------------------------------------
     */

    /**
     * Add Page
     */
    public function addPage()
    {
        checkPermission('pages');
        $data['title'] = trans("add_page");
        $data['menuItems'] = $this->commonModel->getMenuLinks($this->activeLang->id);
        
        echo view('admin/includes/_header', $data);
        echo view('admin/page/add', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Page Post
     */
    public function addPagePost()
    {
        checkPermission('pages');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $model = new PageModel();
            if ($model->addPage()) {
                setSuccessMessage("msg_item_added");
                redirectToBackURL();
            }
        }
        setErrorMessage("msg_error");
        redirectToBackURL();
    }

    /**
     * Pages
     */
    public function pages()
    {
        checkPermission('pages');
        $data['title'] = trans("pages");
        $model = new PageModel();
        $data['pages'] = $model->getPages();
        
        $data['langSearchColumn'] = 2;

        echo view('admin/includes/_header', $data);
        echo view('admin/page/pages', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Page
     */
    public function editPage($id)
    {
        checkPermission('pages');
        $data['title'] = trans("update_page");
        $model = new PageModel();
        $data['page'] = $model->getPage($id);
        if (empty($data['page'])) {
            redirectToBackURL();
        }
        
        $data['menuItems'] = $this->commonModel->getMenuLinks($data['page']->lang_id);

        echo view('admin/includes/_header', $data);
        echo view('admin/page/update', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Update Page Post
     */
    public function editPagePost()
    {
        checkPermission('pages');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $id = inputPost('id');
            $model = new PageModel();
            if ($model->editPage($id)) {
                setSuccessMessage("msg_updated");
                redirectToBackURL();
            }
        }
        setErrorMessage("msg_error");
        redirectToBackURL();
    }

    /**
     * Delete Page Post
     */
    public function deletePagePost()
    {
        checkPermission('pages');
        $id = inputPost('id');
        $model = new PageModel();
        if ($model->deletePage($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Comments
     */
    public function comments()
    {
        checkPermission('comments');
        $data['title'] = trans("comments");
        $model = new CommentModel();
        
        $numRows = $model->getCommentCount();
        $data['pager'] = paginate($this->perPage, $numRows);
        $data['comments'] = $model->getCommentsPaginated($this->perPage, $data['pager']->offset);

        echo view('admin/includes/_header', $data);
        echo view('admin/comments', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Aprrove Comment Post
     */
    public function approveCommentPost()
    {
        checkPermission('comments');
        $id = inputPost('id');
        $model = new CommentModel();
        if ($model->approveComment($id)) {
            setSuccessMessage("msg_comment_approved");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Delete Comment Post
     */
    public function deleteCommentPost()
    {
        checkPermission('comments');
        $id = inputPost('id');
        $model = new CommentModel();
        if ($model->deleteComment($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Delete Selected Comments
     */
    public function deleteSelectedComments()
    {
        checkPermission('comments');
        $commentIds = inputPost('comment_ids');
        $model = new CommentModel();
        $model->deleteMultiComments($commentIds);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Approve Selected Comments
     */
    public function approveSelectedComments()
    {
        checkPermission('comments');
        $commentIds = inputPost('comment_ids');
        $model = new CommentModel();
        $model->approveMultiComments($commentIds);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Contact Messages
     */
    public function contactMessages()
    {
        checkPermission('contact_messages');
        $data['title'] = trans("contact_messages");
        
        $numRows = $this->commonModel->getContactMessagesCount();
        $data['pager'] = paginate(50, $numRows);
        $data['messages'] = $this->commonModel->getContactMessagesPaginated(50, $data['pager']->offset);

        echo view('admin/includes/_header', $data);
        echo view('admin/contact_messages', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Delete Contact Message Post
     */
    public function deleteContactMessagePost()
    {
        checkPermission('contact_messages');
        $id = inputPost('id');
        if ($this->commonModel->deleteContactMessage($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Delete Contact Messages Post
     */
    public function deleteSelectedContactMessagesPost()
    {
        checkPermission('contact_messages');
        $messageIds = inputPost('message_ids');
        $this->commonModel->deleteMultiContactMessages($messageIds);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Ads
     */
    public function adSpaces()
    {
        checkPermission('ad_spaces');
        $adModel = new AdModel();
        $data['title'] = trans("ad_spaces");
        $data['adSpaceKey'] = inputGet('ad_space');
        $data['langId'] = inputGet('lang');
        if (empty($data['adSpaceKey'])) {
            $data['adSpaceKey'] = 'index_top';
        }
        
        $lang = getLanguageById($data['langId']);
        if (empty($lang)) {
            $data['langId'] = $this->activeLang->id;
        }
        $data['adSpace'] = $adModel->getAdSpace($data['langId'], $data['adSpaceKey']);
        if (empty($data['adSpace'])) {
            return redirect()->to(adminUrl('ad-spaces'));
        }
        $data['arrayAdSpaces'] = [
            'index_top' => trans('ad_space_index_top'),
            'index_bottom' => trans('ad_space_index_bottom'),
            'post_top' => trans('ad_space_post_top'),
            'post_bottom' => trans('ad_space_post_bottom'),
            'posts_top' => trans('ad_space_posts_top'),
            'posts_bottom' => trans('ad_space_posts_bottom'),
            'sidebar_1' => trans('sidebar') . '-1',
            'sidebar_2' => trans('sidebar') . '-2',
            'in_article_1' => trans('ad_space_in_article') . '-1',
            'in_article_2' => trans('ad_space_in_article') . '-2'
        ];

        echo view('admin/includes/_header', $data);
        echo view('admin/ad_spaces', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Ad Spaces Post
     */
    public function adSpacesPost()
    {
        checkPermission('ad_spaces');
        $id = inputPost('id');
        $model = new AdModel();
        if ($model->updateAdSpaces($id)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Google Adsense Code Post
     */
    public function googleAdsenseCodePost()
    {
        checkPermission('ad_spaces');
        $model = new AdModel();
        if ($model->updateGoogleAdsenseCode()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Preferences
     */
    public function preferences()
    {
        checkPermission('settings');
        $data['title'] = trans("preferences");
        $data['roles'] = $this->authModel->getRoles();
        echo view('admin/includes/_header', $data);
        echo view('admin/preferences', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Preferences Post
     */
    public function preferencesPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updatePreferences()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl("preferences"));
    }

    /**
     * AI Writer Post
     */
    public function aiWriterPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateAIWriterSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('preferences'));
    }

    /**
     * File Upload Settings Post
     */
    public function fileUploadSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateFileUploadSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl("preferences"));
    }

    /**
     * Settings
     */
    public function settings()
    {
        checkPermission('settings');
        $data["settingsLangId"] = inputGet("lang", true);
        if (empty($data["settingsLangId"])) {
            $data["settingsLangId"] = $this->generalSettings->site_lang;
            return redirect()->to(adminUrl("settings?lang=" . $data["settingsLangId"]));
        }
        
        $data['title'] = trans("settings");
        $data['formSettings'] = $this->settingsModel->getSettings($data["settingsLangId"]);

        echo view('admin/includes/_header', $data);
        echo view('admin/settings', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Settings Post
     */
    public function settingsPost()
    {
        checkPermission('settings');
        $langId = clrNum(inputPost('lang_id'));
        $activeTab = clrNum(inputPost('active_tab'));
        if (empty($langId)) {
            $langId = $this->activeLang->id;
        }
        if ($this->settingsModel->updateSettings($langId)) {
            $this->settingsModel->updateGeneralSettings();
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        $settings = $this->settingsModel->getGeneralSettings();
        if (!empty($settings)) {
            return redirect()->to(base_url($settings->admin_route . '/settings?lang=' . $langId . '&tab=' . $activeTab));
        }
        redirectToBackURL();
    }

    /**
     * Recaptcha Settings Post
     */
    public function recaptchaSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateRecaptchaSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Maintenance Mode Post
     */
    public function maintenanceModePost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateMaintenanceModeSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Seo Tools
     */
    public function seoTools()
    {
        checkPermission('seo_tools');
        $data['title'] = trans("seo_tools");
        $data["toolsLang"] = inputGet('lang', true);
        if (empty($data["toolsLang"])) {
            $data["toolsLang"] = $this->generalSettings->site_lang;
            return redirect()->to(adminUrl("seo-tools?lang=" . $data["toolsLang"]));
        }

        $postAdminModel = new PostAdminModel();
        $data['seoSettings'] = $this->settingsModel->getSettings($data["toolsLang"]);
        $data['postsCount'] = $postAdminModel->getPostsCount(null);

        $data['numSitemaps'] = 1;
        if ($data['postsCount'] > SITEMAP_URL_LIMIT) {
            $data['numSitemaps'] = ceil($data['postsCount'] / SITEMAP_URL_LIMIT);
        }

        

        echo view('admin/includes/_header', $data);
        echo view('admin/seo_tools', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Seo Tools Post
     */
    public function seoToolsPost()
    {
        checkPermission('seo_tools');
        if ($this->settingsModel->updateSeoSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Sitemap Settings Post
     */
    public function sitemapSettingsPost()
    {
        checkPermission('seo_tools');
        $model = new SitemapModel();
        if ($model->updateSitemapSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Sitemap Post
     */
    public function sitemapPost()
    {
        checkPermission('seo_tools');
        $submit = inputPost('submit');
        $index = inputPost('index');
        $fileName = $index == 0 ? 'sitemap.xml' : 'sitemap-' . $index . '.xml';

        if ($submit == 'generate' || $submit == 'refresh') {
            $model = new SitemapModel();
            $model->generateSitemap($index);
        } elseif ($submit == 'download') {
            if (file_exists(FCPATH . $fileName)) {
                return $this->response->download(FCPATH . $fileName, null)->setFileName($fileName);
            }
        } elseif ($submit == 'delete') {
            if (file_exists(FCPATH . $fileName)) {
                @unlink(FCPATH . $fileName);
            }
        }
        redirectToBackURL();
    }

    /**
     * Storage
     */
    public function storage()
    {
        $data['title'] = trans("storage");

        echo view('admin/includes/_header', $data);
        echo view('admin/storage', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Storage Post
     */
    public function storagePost()
    {
        if ($this->settingsModel->updateStorageSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('storage'));
    }

    /**
     * AWS S3 Post
     */
    public function awsS3Post()
    {
        if ($this->settingsModel->updateAwsS3()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('storage'));
    }

    /**
     * Social Login Settings
     */
    public function socialLoginSettings()
    {
        checkPermission('settings');
        $data['title'] = trans("social_login_settings");
        

        echo view('admin/includes/_header', $data);
        echo view('admin/social_login_settings', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Social Login Post
     */
    public function socialLoginSettingsPost()
    {
        checkPermission('settings');
        $model = new SettingsModel();
        if ($model->editSocialSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('social-login-settings'));
    }

    /**
     * Cache System
     */
    public function cacheSystem()
    {
        checkPermission('settings');
        $data['title'] = trans("cache_system");
        
        echo view('admin/includes/_header', $data);
        echo view('admin/cache_system', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Cache System Post
     */
    public function cacheSystemPost()
    {
        checkPermission('settings');
        if (inputPost('action') == 'reset') {
            resetCacheData();
            setSuccessMessage("msg_reset_cache");
        } elseif (inputPost('action') == 'reset_static') {
            resetCacheData('static', true);
            setSuccessMessage("msg_reset_cache");
        } else {
            if ($this->settingsModel->updateCacheSystem()) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
            }
        }
        return redirect()->to(adminUrl('cache-system'));
    }

    /**
     * Email Settings
     */
    public function emailSettings()
    {
        checkPermission('settings');
        $data['title'] = trans("email_settings");
        
        $data['service'] = inputGet('service');
        $data['protocol'] = inputGet('protocol');
        if (empty($data['service'])) {
            $data['service'] = $this->generalSettings->mail_service;
        }
        if ($data['service'] != 'swift' && $data['service'] != 'php' && $data['service'] != 'mailjet') {
            $data['service'] = 'swift';
        }
        if (empty($data['protocol'])) {
            $data['protocol'] = $this->generalSettings->mail_protocol;
        }
        if ($data['protocol'] != 'smtp' && $data['protocol'] != 'mail') {
            $data['protocol'] = 'smtp';
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/email_settings', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Update Email Settings Post
     */
    public function emailSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateEmailSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('email-settings'));
    }

    /**
     * Email Options Post
     */
    public function emailOptionsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateEmailOptions()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Send Test Email Post
     */
    public function sendTestEmailPost()
    {
        checkPermission('settings');
        $email = inputPost('email');
        $subject = $this->settings->application_name . " Test Email";
        $message = "<p>This is a test email. This e-mail is sent to your e-mail address for test purpose only. If you have received this e-mail, your e-mail system is working.</p>";
        if (!empty($email)) {
            $emailModel = new EmailModel();
            if (!$emailModel->sendTestEmail($email, $subject, $message)) {
                setErrorMessage("msg_error");
            } else {
                setSuccessMessage("msg_email_sent");
            }
        }
        redirectToBackURL();
    }

    /**
     * -------------------------------------------------------------------------------------------
     * POLLS
     * -------------------------------------------------------------------------------------------
     */

    /**
     * Add Poll
     */
    public function addPoll()
    {
        checkPermission('polls');
        $data['title'] = trans("add_poll");
        
        echo view('admin/includes/_header', $data);
        echo view('admin/poll/add', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Poll Post
     */
    public function addPollPost()
    {
        checkPermission('polls');
        $val = \Config\Services::validation();
        $val->setRule('question', trans("question"), 'required');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $model = new PollModel();
            if ($model->addPoll()) {
                setSuccessMessage("msg_item_added");
            } else {
                setErrorMessage("msg_error");
            }
        }
        redirectToBackURL();
    }

    /**
     * Polls
     */
    public function polls()
    {
        checkPermission('polls');
        $data['title'] = trans("polls");
        $model = new PollModel();
        $data['polls'] = $model->getPolls();
        
        $data['langSearchColumn'] = 2;
        echo view('admin/includes/_header', $data);
        echo view('admin/poll/polls', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Poll
     */
    public function editPoll($id)
    {
        checkPermission('polls');
        $data['title'] = trans("update_poll");
        
        $model = new PollModel();
        //find poll
        $data['poll'] = $model->getPoll($id);
        $data['pollOptions'] = $model->getPollOptions($id);
        if (empty($data['poll'])) {
            redirectToBackURL();
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/poll/update', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Poll Post
     */
    public function editPollPost()
    {
        checkPermission('polls');
        $val = \Config\Services::validation();
        $val->setRule('question', trans("question"), 'required');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $model = new PollModel();
            $id = inputPost('id');
            if ($model->editPoll($id)) {
                setSuccessMessage("msg_updated");
                return redirect()->to(adminUrl('polls'));
            } else {
                setErrorMessage("msg_error");
            }
        }
        redirectToBackURL();
    }

    /**
     * Delete Poll Post
     */
    public function deletePollPost()
    {
        checkPermission('polls');
        $id = inputPost('id');
        $model = new PollModel();
        if ($model->deletePoll($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * -------------------------------------------------------------------------------------------
     * USERS
     * -------------------------------------------------------------------------------------------
     */

    /**
     * Users
     */
    public function users()
    {
        checkPermission('membership');
        $data['title'] = trans("users");
        $data['roles'] = $this->authModel->getRoles();
        $numRows = $this->authModel->getUserCount();
        $data['pager'] = paginate($this->perPage, $numRows);
        $data['users'] = $this->authModel->getUsersPaginated($this->perPage, $data['pager']->offset);
        

        echo view('admin/includes/_header', $data);
        echo view('admin/users/users', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add User
     */
    public function addUser()
    {
        checkPermission('membership');
        $data['title'] = trans("add_user");
        
        $data['roles'] = $this->authModel->getRoles();

        echo view('admin/includes/_header', $data);
        echo view('admin/users/add_user');
        echo view('admin/includes/_footer');
    }

    /**
     * Add User Post
     */
    public function addUserPost()
    {
        checkPermission('membership');
        $val = \Config\Services::validation();
        $val->setRule('username', trans("username"), 'required|min_length[4]|max_length[255]|is_unique[users.username]');
        $val->setRule('email', trans("email"), 'required|valid_email|max_length[255]|is_unique[users.email]');
        $val->setRule('password', trans("password"), 'required|min_length[4]|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            if ($this->authModel->addUser()) {
                setSuccessMessage("msg_item_added");
            } else {
                setErrorMessage("msg_error");
            }
        }
        redirectToBackURL();
    }

    /**
     * Edit User
     */
    public function editUser($id)
    {
        checkPermission('membership');
        $data['title'] = trans("edit_user");
        $data['user'] = getUser($id);
        if (empty($data['user'])) {
            return redirect()->to(adminUrl('users'));
        }
        
        $data['role'] = $this->authModel->getRole($data['user']->role_id);

        echo view('admin/includes/_header', $data);
        echo view('admin/users/edit_user');
        echo view('admin/includes/_footer');
    }

    /**
     * Edit User Post
     */
    public function editUserPost()
    {
        checkPermission('membership');
        $val = \Config\Services::validation();
        $val->setRule('username', trans("username"), 'required|min_length[4]|max_length[255]');
        $val->setRule('email', trans("email"), 'required|valid_email|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $id = inputPost('id');
            $user = getUser($id);
            if (empty($user)) {
                redirectToBackURL();
            }
            $data = [
                'email' => inputPost('email'),
                'username' => inputPost('username'),
                'slug' => inputPost('slug')
            ];
            //is email unique
            if (!$this->authModel->isUniqueEmail($data["email"], $user->id)) {
                setErrorMessage("email_unique_error");
                redirectToBackURL();
            }
            //is username unique
            if (!$this->authModel->isUniqueUsername($data["username"], $user->id)) {
                setErrorMessage("msg_username_unique_error");
                redirectToBackURL();
            }
            //is slug unique
            if (!$this->authModel->isSlugUnique($data["slug"], $user->id)) {
                setErrorMessage("msg_slug_used");
                redirectToBackURL();
            }
            if ($this->authModel->editUser($id)) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
            }
        }
        redirectToBackURL();
    }

    /**
     * Change User Role
     */
    public function changeUserRolePost()
    {
        checkPermission('membership');
        $id = inputPost('user_id');
        $roleId = inputPost('role_id');
        $user = $this->authModel->getUser($id);
        if (empty($user)) {
            redirectToBackURL();
        } else {
            if ($this->authModel->changeUserRole($id, $roleId)) {
                setSuccessMessage("msg_role_changed");
            } else {
                setErrorMessage("msg_error");
            }
        }
        redirectToBackURL();
    }

    /**
     * User Options Post
     */
    public function userOptionsPost()
    {
        checkPermission('membership');
        $option = inputPost('option');
        $id = inputPost('id');
        if ($option == 'ban') {
            if ($this->authModel->banUser($id)) {
                setSuccessMessage("msg_user_banned");
            } else {
                setErrorMessage("msg_error");
            }
        }
        if ($option == 'remove_ban') {
            if ($this->authModel->removeUserBan($id)) {
                setSuccessMessage("msg_ban_removed");
            } else {
                setErrorMessage("msg_error");
            }
        }
        redirectToBackURL();
    }

    /**
     * Delete User Post
     */
    public function deleteUserPost()
    {
        checkPermission('membership');
        $id = inputPost('id');
        if ($this->authModel->deleteUser($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Roles Permissions
     */
    public function rolesPermissions()
    {
        checkPermission('membership');
        $data['title'] = trans("roles_permissions");
        $data['roles'] = $this->authModel->getRoles();
        

        echo view('admin/includes/_header', $data);
        echo view('admin/users/roles_permissions');
        echo view('admin/includes/_footer');
    }

    /**
     * Add Role
     */
    public function addRole()
    {
        checkPermission('membership');
        $data['title'] = trans("add_role");
        

        echo view('admin/includes/_header', $data);
        echo view('admin/users/add_role');
        echo view('admin/includes/_footer');
    }


    /**
     * Add Role Post
     */
    public function addRolePost()
    {
        checkPermission('membership');
        if ($this->authModel->addRole()) {
            setSuccessMessage("msg_item_added");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('add-role'));
    }

    /**
     * Edit Role
     */
    public function editRole($id)
    {
        checkPermission('membership');
        $data['title'] = trans("edit_role");
        $data['role'] = $this->authModel->getRole($id);
        if (empty($data['role'])) {
            return redirect()->to(adminUrl('roles-permissions'));
        }
        
        echo view('admin/includes/_header', $data);
        echo view('admin/users/edit_role');
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Role Post
     */
    public function editRolePost()
    {
        checkPermission('membership');
        $id = inputPost('id');
        if ($this->authModel->editRole($id)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('edit-role/' . clrNum($id)));
    }

    /**
     * Delete Role Post
     */
    public function deleteRolePost()
    {
        checkPermission('membership');
        $id = inputPost('id');
        if ($this->authModel->deleteRole($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Role Settings Post
     */
    public function roleSettingsPost()
    {
        checkPermission('membership');
        if ($this->authModel->setDefaultUserRole()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Newsletter
     */
    public function newsletter()
    {
        checkPermission('newsletter');
        $model = new NewsletterModel();
        $data['title'] = trans("newsletter");
        
        $data['subscribersCount'] = $model->getSubscribersCount();
        $data['usersCount'] = $this->authModel->getUserCount();

        echo view('admin/includes/_header', $data);
        echo view('admin/newsletter/newsletter', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Newsletter Select Emails
     */
    public function newsletterSelectEmailsPost()
    {
        checkPermission('newsletter');
        $data['title'] = trans("newsletter");
        $data['submit'] = inputPost('submit');
        $data['emails'] = null;
        $subscriberType = '';
        if ($data['submit'] == 'users') {
            $subscriberType = 'users';
            $ids = inputPost('user_ids');
            $array = null;
            if (!empty($ids)) {
                $array = explode(',', $ids);
            }
            if (!empty($array)) {
                $data['emails'] = $this->authModel->getUserEmailsByIds($array);
            }
        } elseif ($data['submit'] == 'subscribers') {
            $subscriberType = 'subscribers';
            $model = new NewsletterModel();
            $ids = inputPost('subscriber_ids');
            $array = null;
            if (!empty($ids)) {
                $array = explode(',', $ids);
            }
            if (!empty($array)) {
                $data['emails'] = $model->getSubscriberEmailsByIds($array);
            }
        }
        if (empty($data['emails'])) {
            setErrorMessage('newsletter_email_error');
            return redirect()->to(adminUrl('newsletter'));
        }

        $newsletter = [
            'subscriberType' => $subscriberType,
            'emails' => $data['emails']
        ];

        cache()->save('newsletter_data', $newsletter, 86400);//1 day
        return redirect()->to(adminUrl('newsletter-send-email'));
    }

    /**
     * Send Email
     */
    public function newsletterSendEmail()
    {
        checkPermission('newsletter');
        $data = cache('newsletter_data');
        $data['title'] = trans("send_email");

        if (empty($data) || empty($data['subscriberType']) || empty($data['emails'])) {
            return redirect()->to(adminUrl('newsletter'));
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/newsletter/send_email', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Send Email Post
     */
    public function newsletterSendEmailPost()
    {
        checkPermission('newsletter');
        $model = new NewsletterModel();
        if (@$model->sendEmail()) {
            return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON(['result' => 1]);
        }
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON(['result' => 0]);
    }

    /**
     * Newsletter Settings Post
     */
    public function newsletterSettingsPost()
    {
        checkPermission('newsletter');
        $model = new NewsletterModel();
        if ($model->updateSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('newsletter'));
    }

    /**
     * Delete Subscriber Post
     */
    public function deleteSubscriberPost()
    {
        if (!hasPermission('newsletter')) {
            exit();
        }
        $id = inputPost('id');
        $model = new NewsletterModel();
        if ($model->deleteSubscriber($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Font Settings
     */
    public function fontSettings()
    {
        checkPermission('settings');
        $data["fontLangId"] = clrNum(inputGet('lang'));
        if (empty($data["fontLangId"]) || empty(getLanguageById($data["fontLangId"]))) {
            $data["fontLangId"] = $this->generalSettings->site_lang;
            return redirect()->to(adminUrl("font-settings?lang=" . $data["fontLangId"]));
        }
        
        $data['title'] = trans("font_settings");
        $data['fonts'] = $this->settingsModel->getFonts();
        $data['settings'] = $this->settingsModel->getSettings($data["fontLangId"]);

        echo view('admin/includes/_header', $data);
        echo view('admin/font/fonts', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Set Site Font Post
     */
    public function setSiteFontPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->setDefaultFonts()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        $langId = inputPost('lang_id');
        return redirect()->to(adminUrl('font-settings?lang=' . clrNum($langId)));
    }

    /**
     * Add Font Post
     */
    public function addFontPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->addFont()) {
            setSuccessMessage("msg_item_added");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Edit Font
     */
    public function editFont($id)
    {
        checkPermission('settings');
        $data['title'] = trans("update_font");
        $data['font'] = $this->settingsModel->getFont($id);
        if (empty($data['font'])) {
            redirectToBackURL();
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/font/update', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Font Post
     */
    public function editFontPost()
    {
        checkPermission('settings');
        $id = inputPost('id');
        if ($this->settingsModel->editFont($id)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl("font-settings?lang=" . $this->generalSettings->site_lang));
    }

    /**
     * Delete Font Post
     */
    public function deleteFontPost()
    {
        checkPermission('settings');
        $id = inputPost('id');
        if ($this->settingsModel->deleteFont($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Set Active Language Post
     */
    public function setActiveLanguagePost()
    {
        $id = clrNum(inputPost('lang_id'));
        if (!empty($this->languages)) {
            foreach ($this->languages as $language) {
                if ($language->id == $id) {
                    $this->session->set('inf_admin_lang_id', $id);
                    break;
                }
            }
        }
        redirectToBackURL();
    }


    /**
     * Download Database Backup
     */
    public function downloadDatabaseBackup()
    {
        if (user()->role_id != 1) {
            return redirect()->to(adminUrl());
        }
        $response = \Config\Services::response();
        $data = $this->settingsModel->downloadBackup();
        $name = 'db_backup-' . date('Y-m-d H-i-s') . '.sql';
        return $response->download($name, $data);
    }
}
