<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\AwsModel;
use App\Models\CategoryModel;
use App\Models\CommentModel;
use App\Models\CommonModel;
use App\Models\FileModel;
use App\Models\GalleryModel;
use App\Models\PageModel;
use App\Models\PostModel;
use App\Models\ReactionModel;
use App\Models\RssModel;
use App\Models\TagModel;

class HomeController extends BaseController
{
    protected $postsPerPage;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->postsPerPage = $this->generalSettings->pagination_per_page;
    }

    /*
     * Index
     */
    public function index()
    {
        $data['title'] = $this->settings->home_title;
        $data['description'] = $this->settings->site_description;
        $data['keywords'] = $this->settings->keywords;
        $data['homeTitle'] = $this->settings->home_title;

        $postModel = new PostModel();
        //slider posts
        $data['sliderPosts'] = $postModel->getSliderPosts($this->activeLang->id);
        

        $numRows = $postModel->getPostCount($this->activeLang->id);
        $data['pager'] = paginate($this->postsPerPage, $numRows);
        $data['posts'] = $postModel->getPostsPaginated($this->activeLang->id, $this->postsPerPage, $data['pager']->offset);

        echo view('partials/_header', $data);
        echo view('index', $data);
        echo view('partials/_footer');
    }

    /**
     * Gallery Page
     */
    public function gallery()
    {
        $model = new PageModel();
        $data['page'] = $model->getPageBySlug('gallery');
        $data['isGalleryPage'] = true;
        //check page auth
        $this->checkPageAuth($data['page']);
        if ($data['page']->page_active == 0) {
            $this->error404();
        } else {
            $data['title'] = $data['page']->title;
            $data['description'] = $data['page']->page_description;
            $data['keywords'] = $data['page']->page_keywords;
            
            //get gallery categories
            $model = new GalleryModel();
            $data['galleryAlbums'] = $model->getAlbumsBySelectedLang();

            echo view('partials/_header', $data);
            echo view('gallery/gallery', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Gallery Album Page
     */
    public function galleryAlbum($id)
    {
        $model = new PageModel();
        $data['page'] = $model->getPageBySlug('gallery');
        $data['isGalleryPage'] = true;
        //check page auth
        $this->checkPageAuth($data['page']);
        if ($data['page']->page_active == 0) {
            $this->error404();
        } else {
            $data['title'] = $data['page']->title;
            $data['description'] = $data['page']->page_description;
            $data['keywords'] = $data['page']->page_keywords;
            
            //get album
            $model = new GalleryModel();
            $data['album'] = $model->getAlbum($id);
            if (empty($data['album'])) {
                return redirect()->back();
            }
            //get gallery images
            $data['galleryImages'] = $model->getImagesByAlbum($data['album']->id);
            $data['galleryCategories'] = $model->getCategoriesByAlbum($data['album']->id);

            echo view('partials/_header', $data);
            echo view('gallery/gallery_album', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Contact Page
     */
    public function contact()
    {
        $model = new PageModel();
        $data['page'] = $model->getPageBySlug('contact');
        //check page auth
        $this->checkPageAuth($data['page']);
        if ($data['page']->page_active == 0) {
            $this->error404();
        } else {
            $data['title'] = $data['page']->title;
            $data['description'] = $data['page']->page_description;
            $data['keywords'] = $data['page']->page_keywords;
            

            echo view('partials/_header', $data);
            echo view('contact', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Contact Page Post
     */
    public function contactPost()
    {
        $val = \Config\Services::validation();
        $val->setRule('name', trans("name"), 'required|max_length[200]');
        $val->setRule('email', trans("email"), 'required|valid_email|max_length[200]');
        $val->setRule('message', trans("message"), 'required|max_length[5000]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            if (reCAPTCHA('validate', $this->generalSettings) == 'invalid') {
                setErrorMessage("msg_recaptcha");
                return redirect()->back()->withInput();
            }
            $model = new CommonModel();
            if ($model->addContactMessage()) {
                setSuccessMessage("message_contact_success");
            } else {
                setErrorMessage("message_contact_error");
            }
        }
        return redirect()->to(base_url('contact'));
    }

    /**
     * Tag Page
     */
    public function tag($tagSlug)
    {
        $model = new TagModel();
        $postModel = new PostModel();
        $data['tag'] = $model->getTagBySlug($tagSlug, $this->activeLang->id);
        if (empty($data['tag'])) {
            return redirect()->to(langBaseUrl());
        }
        $data['title'] = esc($data['tag']->tag);
        $data['description'] = trans("tag") . ': ' . esc($data['tag']->tag);
        $data['keywords'] = trans("tag") . ', ' . esc($data['tag']->tag);
        
        $numRows = $postModel->getPostCountByTag($data['tag']->id, $this->activeLang->id);
        $data['pager'] = paginate($this->postsPerPage, $numRows);
        $data['posts'] = array();
        if ($numRows > 0) {
            $data['posts'] = $postModel->getTagPostsPaginated($data['tag']->id, $this->activeLang->id, $this->postsPerPage, $data['pager']->offset);
        }

        echo view('partials/_header', $data);
        echo view('tag', $data);
        echo view('partials/_footer');
    }

    /**
     * Reading List Page
     */
    public function readingList()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $data = setPageMeta(trans("reading_list"));
        
        $postModel = new PostModel();
        $data['numRows'] = $postModel->getReadingListCount(user()->id);
        $data['pager'] = paginate($this->postsPerPage, $data['numRows']);
        $data['posts'] = $postModel->getReadingListPaginated(user()->id, $this->postsPerPage, $data['pager']->offset);

        echo view('partials/_header', $data);
        echo view('reading_list', $data);
        echo view('partials/_footer');
    }

    /**
     * Search Page
     */
    public function search()
    {
        $q = inputGet('q', true);
        $q = strip_tags($q ?? '');
        $q = removeForbiddenCharacters($q);
        if (empty($q)) {
            return redirect()->back();
        }
        $data['q'] = $q;
        $data['title'] = trans("search") . ': ' . esc($q);
        $data['description'] = trans("search") . ': ' . esc($q);
        $data['keywords'] = trans("search") . ', ' . esc($q);

        $postModel = new PostModel();
        $data['postsPerPage'] = $this->postsPerPage;
        $data['posts'] = $postModel->getSearchPostsPaginated($this->activeLang->id, $q, $this->postsPerPage, 0);
        

        echo view('partials/_header', $data);
        echo view('search', $data);
        echo view('partials/_footer');
    }

    /**
     * Dynamic Page by Name Slug
     */
    public function any($slug)
    {
        $slug = cleanSlug($slug);
        if (empty($slug)) {
            return redirect()->to(langBaseUrl());
        }
        if ($slug == $this->activeLang->short_form) {
            return redirect()->to(base_url());
        }
        
        $pageModel = new PageModel();
        $data['page'] = $pageModel->getPageByLang($slug, $this->activeLang->id);
        if (!empty($data['page'])) {
            $this->page($data['page']);
        } else {
            $categoryModel = new CategoryModel();
            $data['category'] = $categoryModel->getCategoryBySlug($slug);
            if (!empty($data['category'])) {
                $this->category($data['category']);
            } else {
                $this->post($slug);
            }
        }
    }

    /**
     * Page
     */
    private function page($page)
    {
        $data['page'] = $page;
        $this->checkPageAuth($data['page']);
        if (empty($data['page']) || $data['page'] == null) {
            $this->error404();
        } else if ($data['page']->page_active == 0 || $data['page']->link != '') {
            $this->error404();
        } else {
            $data['title'] = $data['page']->title;
            $data['description'] = $data['page']->page_description;
            $data['keywords'] = $data['page']->page_keywords;

            echo view('partials/_header', $data);
            echo view('page', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Post Page
     */
    private function post($slug)
    {
        $model = new PostModel();
        $tagModel = new TagModel();
        $commentModel = new CommentModel();
        $reactionModel = new ReactionModel();

        $data['post'] = $model->getPostBySlug($slug);
        if (empty($data['post'])) {
            $this->error404();
        } else {
            $id = $data['post']->id;
            if (!authCheck() && $data['post']->need_auth == 1) {
                setErrorMessage("message_post_auth");
                redirectToUrl(langBaseUrl('login'));
                exit();
            }
            if ($data['post']->visibility != 1) {
                return redirect()->to(langBaseUrl());
            }

            $data['category'] = getCategoryClient($data['post']->category_id, $this->categories);
            $data['categoryArray'] = getCategoryArray($data['post']->category_id);
            $data['additionalImages'] = $model->getPostAdditionalImages($id);
            $data['postUser'] = $this->authModel->getUser($data['post']->user_id);
            $data['relatedPosts'] = $model->getRelatedPosts($data['post']->category_id, $id);
            $data['postTags'] = $tagModel->getPostTags($id);

            $data['comments'] = $commentModel->getComments($id, COMMENTS_LIMIT);
            $data['commentLimit'] = COMMENTS_LIMIT;
            $data['is_reading_list'] = $model->isPostInReadingList($id);
            $data['pageType'] = "post";
            //set og tags
            $data['ogType'] = "article";
            $data['ogUrl'] = generatePostUrl($data['post']);
            $data['ogImage'] = getPostImage($data['post'], 'mid');
            $data['ogTags'] = $data['postTags'];
            if (!empty($data['post']->image_url)) {
                $data['ogImage'] = $data['post']->image_url;
            }

            $data['title'] = $data['post']->title;
            $data['description'] = $data['post']->summary;
            $data['keywords'] = $data['post']->keywords;
            $data["reactions"] = $reactionModel->getReaction($id);

            if (!empty($data['post']->feed_id)) {
                $rssModel = new RssModel();
                $data['feed'] = $rssModel->getFeed($data['post']->feed_id);
            }
            $data['postJsonLD'] = $data['post'];
            $data['postJsonLD']->category_name = !empty($data['category']) ? $data['category']->name : '';

            echo view('partials/_header', $data);
            echo view('post/post', $data);
            echo view('partials/_footer', $data);
            //increase post pageviews
            $model->increasePostPageViews($data['post']);
        }
    }

    /**
     * Category Page
     */
    private function category($category, $type = 'parent')
    {
        //check category exists
        if (empty($category)) {
            return redirect()->to(langBaseUrl());
        }
        if ($category->parent_id != 0 && $type == 'parent') {
            $this->error404();
        } else {
            $data['category'] = $category;
            $data['title'] = $data['category']->name;
            $data['description'] = $data['category']->description;
            $data['keywords'] = $data['category']->keywords;

            $postModel = new PostModel();
            $categoryTree = getCategoryTreeIdsArray($category->id);
            $numRows = $postModel->getPostCountByCategory($categoryTree);
            $data['pager'] = paginate($this->postsPerPage, $numRows);
            $data['posts'] = $postModel->getCategoryPostsPaginated($categoryTree, $this->postsPerPage, $data['pager']->offset);
            $data['categoryArray'] = getCategoryArray($category->id);

            echo view('partials/_header', $data);
            echo view('category', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Subcategory Page
     */
    public function subcategory($parentSlug, $slug)
    {
        $model = new CategoryModel();
        $category = $model->getCategoryBySlug($slug);
        
        if (empty($category)) {
            return redirect()->to(langBaseUrl());
        }
        $this->category($category, 'subcategory');
    }

    /**
     * Rss Page
     */
    public function rssFeeds()
    {
        if ($this->generalSettings->show_rss == 0) {
            $this->error404();
        } else {
            $data['title'] = trans("rss_feeds");
            $data['description'] = trans("rss_feeds") . " - " . $this->settings->application_name;
            $data['keywords'] = trans("rss_feeds") . "," . $this->settings->application_name;
            
            echo view('partials/_header', $data);
            echo view('rss_feeds', $data);
            echo view('partials/_footer');
        }
    }

    /**
     * Rss Latest Posts
     */
    public function rssLatestPosts()
    {
        if ($this->generalSettings->show_rss == 1) {
            helper('xml');
            $data['feedName'] = $this->settings->site_title . ' - ' . trans("latest_posts");
            $data['encoding'] = 'utf-8';
            $data['feedURL'] = langBaseUrl('rss/posts');
            $data['pageDescription'] = $this->settings->site_title . ' - ' . trans("latest_posts");
            $data['pageLanguage'] = $this->activeLang->short_form;
            $data['creatorEmail'] = '';
            $postModel = new PostModel();
            $data['posts'] = $postModel->getRssPosts($this->activeLang->id, null, null);
            header('Content-Type: application/rss+xml; charset=utf-8');
            return $this->response->setXML(view('rss', $data));
        } else {
            $this->error404();
        }
    }

    /**
     * Rss By Category
     */
    public function rssByCategory($slug, $id)
    {
        if ($this->generalSettings->show_rss == 1) {
            $categoryModel = new CategoryModel();
            $data['category'] = $categoryModel->getCategory($id);
            if (empty($data['category']) || $data['category']->slug != $slug) {
                return redirect()->to(langBaseUrl());
            }
            helper('xml');
            $data['feedName'] = $this->settings->site_title . " - " . trans("category") . ": " . $data['category']->name;
            $data['encoding'] = 'utf-8';
            $data['feedURL'] = langBaseUrl("rss/category/" . $data['category']->slug);
            $data['pageDescription'] = $this->settings->site_title . ' - ' . $data['category']->name;
            $data['pageLanguage'] = $this->activeLang->short_form;
            $data['creatorEmail'] = '';
            $categoryTree = getCategoryTreeIdsArray($data['category']->id);
            $postModel = new PostModel();
            $data['posts'] = $postModel->getRssPosts($this->activeLang->id, null, $data['category']->id);
            header('Content-Type: application/rss+xml; charset=utf-8');
            return $this->response->setXML(view('rss', $data));
        } else {
            $this->error404();
        }
    }

    /**
     * Rss By User
     */
    public function rssByUser($slug)
    {
        if ($this->generalSettings->show_rss == 1) {
            $authModel = new AuthModel();
            $user = $authModel->getUserBySlug($slug);
            if (empty($user)) {
                return redirect()->to(generateURL('rss_feeds'));
            }
            helper('xml');
            $data['feedName'] = $this->settings->site_title . ' - ' . esc($user->username);
            $data['encoding'] = 'utf-8';
            $data['feedURL'] = langBaseUrl('rss/author/') . esc($user->slug);
            $data['pageDescription'] = $this->settings->site_title . " - " . esc($user->username);
            $data['pageLanguage'] = $this->activeLang->short_form;
            $data['creatorEmail'] = '';
            $postModel = new PostModel();
            $data['posts'] = $postModel->getRssPosts($this->activeLang->id, $user->id, null);
            header('Content-Type: application/rss+xml; charset=utf-8');
            return $this->response->setXML(view('rss', $data));
        } else {
            $this->error404();
        }
    }

    /**
     * Add or Delete from Reading List
     */
    public function addRemoveFromReadingListPost()
    {
        $postId = inputPost('post_id');
        if (empty($postId)) {
            return redirect()->back();
        }
        $model = new PostModel();
        $inList = $model->isPostInReadingList($postId);
        if ($inList == true) {
            $model->deleteFromReadingList($postId);
        } else {
            $model->addToReadingList($postId);
        }
        return redirect()->back();
    }

    /**
     * Download File
     */
    public function downloadFile()
    {
        $id = inputPost('id');
        if (empty($id)) {
            return redirect()->back();
        }

        $fileModel = new FileModel();
        $file = $fileModel->getFile($id);

        if (empty($file)) {
            return redirect()->back();
        }

        if ($file->storage === 'aws_s3') {
            $awsModel = new AwsModel();
            $awsModel->downloadFile($file->file_name, $file->file_path);
            return;
        }

        $path = FCPATH . $file->file_path;

        if (!is_file($path)) {
            return redirect()->back();
        }

        return !empty($file->file_name) ? $this->response->download($path, null)->setFileName($file->file_name) : $this->response->download($path, null);
    }

    //check page auth
    private function checkPageAuth($page)
    {
        if (!empty($page)) {
            if (!authCheck() && $page->need_auth == 1) {
                setErrorMessage("message_page_auth");
                redirectToUrl(langBaseUrl('login'));
            }
        }
    }

    //error 404
    public function error404()
    {
        header("HTTP/1.0 404 Not Found");
        $data = setPageMeta("404");
        $data['homeTitle'] = $this->settings->home_title;
        $data['isPage404'] = true;

        echo view('partials/_header', $data);
        echo view('errors/html/error_404', $data);
        echo view('partials/_footer', $data);
    }
}
