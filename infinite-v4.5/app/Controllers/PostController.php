<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\PostAdminModel;
use App\Models\CategoryModel;
use App\Models\PostFile1Model;
use App\Models\TagModel;

class PostController extends BaseAdminController
{
    protected $postModel;
    protected $postAdminModel;
    protected $categoryModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->postModel = new PostModel();
        $this->postAdminModel = new PostAdminModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Add Post
     */
    public function addPost()
    {
        checkPermission('add_post');
        $data['title'] = trans("add_post");
        $data['parentCategories'] = $this->categoryModel->getParentCategoriesByLang($this->activeLang->id);
        
        $data['postType'] = "post";

        echo view('admin/includes/_header', $data);
        echo view('admin/post/add_post', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Post Post
     */
    public function addPostPost()
    {
        checkPermission('add_post');
        $backUrl = adminUrl('add-post');
        if (inputPost('post_type') == 'video') {
            $backUrl = adminUrl('add-video');
        }
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        $val->setRule('summary', trans("summary"), 'max_length[5000]');
        $val->setRule('category_id', trans("category"), 'required');
        $val->setRule('optional_url', trans("optional_url"), 'max_length[1000]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->to($backUrl)->withInput();
        } else {
            $postId = $this->postAdminModel->addPost();
            if (!empty($postId)) {
                $this->postAdminModel->updateSlug($postId);
                //add post tags
                $tagModel = new TagModel();
                $tagModel->addEditPostTags($postId, inputPost('tags'));

                $this->postAdminModel->addPostAdditionalImages($postId);
                $this->postAdminModel->addPostFiles($postId);

                resetCacheDataOnChange();
                $msg = trans("msg_updated");
                $post = getPostById($postId);
                if (!empty($post) && isPostPublished($post)) {
                    $baseUrl = generateBaseURLByLangId($post->lang_id);
                    $postUrl = generatePostURL($post, $baseUrl);
                    $msg .= '&nbsp;&nbsp;<a href="' . $postUrl . '" target="_blank" class="alert-post-link">' . trans("view_post") . '&nbsp;&nbsp;<i class="fa fa-external-link"></i></a>';
                }
                setSuccessMessage($msg, false);
                redirectToBackURL();
            }
        }
        setErrorMessage("msg_error");
        return redirect()->to($backUrl)->withInput();
    }

    /**
     * Edit Post
     */
    public function editPost($id)
    {
        $this->checkRolePermission();
        $data['title'] = trans("update_post");
        $data['post'] = $this->postAdminModel->getPost($id);
        if (empty($data['post'])) {
            return redirect()->to(adminUrl('posts'));
        }

        
        //check if author
        if (!isAdmin()) {
            if ($data['post']->user_id != user()->id) {
                return redirect()->to(adminUrl());
            }
        }

        $tagModel = new TagModel();
        $data['tags'] = $tagModel->getPostTagsString($id);
        $data['post_images'] = getPostAdditionalImages($id);
        $data['categories'] = $this->categoryModel->getParentCategoriesByLang($data['post']->lang_id);
        $data['users'] = $this->authModel->getAuthors();
        $data['category_id'] = '';
        $data['subcategory_id'] = '';
        $categoryArray = getCategoryArray($data['post']->category_id);
        if (!empty($categoryArray['parentCategory'])) {
            $data['category_id'] = $categoryArray['parentCategory']->id;
        }
        if (!empty($categoryArray['subcategory'])) {
            $data['subcategory_id'] = $categoryArray['subcategory']->id;
        }
        $data['subcategories'] = $this->categoryModel->getAllSubcategoriesByParentId($data['category_id']);

        echo view('admin/includes/_header', $data);
        if ($data['post']->post_type == "video") {
            echo view('admin/post/edit_video', $data);
        } else {
            echo view('admin/post/edit_post', $data);
        }
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Post Post
     */
    public function editPostPost()
    {
        $this->checkRolePermission();
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        $val->setRule('summary', trans("summary"), 'max_length[5000]');
        $val->setRule('category_id', trans("category"), 'required');
        $val->setRule('optional_url', trans("optional_url"), 'max_length[1000]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $postId = inputPost('id');
            $post = $this->postAdminModel->getPost($postId);
            if (empty($post)) {
                return redirect()->to(adminUrl('posts'));
            }
            //check if author
            if (!isAdmin()) {
                if ($post->user_id != user()->id) {
                    return redirect()->to(adminUrl());
                }
            }
            if ($this->postAdminModel->editPost($postId)) {
                //update slug
                $this->postAdminModel->updateSlug($postId);
                //edit tags
                $tagModel = new TagModel();
                $tagModel->addEditPostTags($postId, inputPost('tags'));

                $this->postAdminModel->addPostAdditionalImages($postId);
                $this->postAdminModel->addPostFiles($postId);

                resetCacheDataOnChange();

                $msg = trans("msg_updated");
                $post = getPostById($postId);
                if (!empty($post) && isPostPublished($post)) {
                    $baseUrl = generateBaseURLByLangId($post->lang_id);
                    $postUrl = generatePostURL($post, $baseUrl);
                    $msg .= '&nbsp;&nbsp;<a href="' . $postUrl . '" target="_blank" class="alert-post-link">' . trans("view_post") . '&nbsp;&nbsp;<i class="fa fa-external-link"></i></a>';
                }
                setSuccessMessage($msg, false);
                return redirect()->to(adminUrl('edit-post') . '/' . $postId);
            }
        }
        setErrorMessage("msg_error");
        redirectToBackURL();
    }

    /**
     * Add Video
     */
    public function addVideo()
    {
        checkPermission('add_post');
        $data['title'] = trans("add_video");
        
        $data['parentCategories'] = $this->categoryModel->getParentCategoriesByLang($this->activeLang->id);
        $data['postType'] = "video";

        echo view('admin/includes/_header', $data);
        echo view('admin/post/add_video', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Posts
     */
    public function posts()
    {
        $this->checkRolePermission();
        $inputList = inputGet('list');
        $listType = 'posts';
        if (!empty($inputList) && ($inputList == 'slider-posts' || $inputList == 'our-picks' || $inputList == 'pending-posts' || $inputList == 'drafts')) {
            $listType = $inputList;
        }

        $data['title'] = trans(str_replace('-', '_', $listType));
        $data['formAction'] = adminUrl('posts') . '?list=' . $listType;
        $data['listType'] = $listType;
        $data['authors'] = $this->authModel->getAuthors();
        
        $numRows = $this->postAdminModel->getPostsCount($listType);
        $data['pager'] = paginate($this->getPostsPerPage(), $numRows);
        $data['posts'] = [];
        if ($numRows > 0) {
            $data['posts'] = $this->postAdminModel->getPostsPaginated($this->getPostsPerPage(), $data['pager']->offset, $listType);
        }

        $data['categories'] = $this->categoryModel->getCategories();
        $data['parentCategories'] = [];
        $data['subCategories'] = [];
        if (!empty($data['categories'])) {
            //parent categories
            $langId = clrNum(inputGet('lang_id'));
            $data['parentCategories'] = array_filter($data['categories'], function ($category) use ($langId) {
                return $category->parent_id == 0 && ($langId ? $category->lang_id == $langId : true);
            });
            //subcategories
            $parentId = clrNum(inputGet('category'));
            $data['subCategories'] = array_filter($data['categories'], function ($category) use ($parentId) {
                return $category->parent_id == $parentId;
            });
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/post/posts', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Auto Post Deletion
     */
    public function autoPostDeletion()
    {
        checkPermission('manage_all_posts');
        $data['title'] = trans('auto_post_deletion');
        echo view('admin/includes/_header', $data);
        echo view('admin/post/auto_post_deletion', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Post Options Post
     */
    public function postOptionsPost()
    {
        $option = inputPost('option');
        $id = inputPost('id');
        $data["post"] = $this->postAdminModel->getPost($id);
        if (empty($data['post'])) {
            redirectToBackURL();
        }
        //if option add remove from slider
        if ($option == 'add-remove-from-slider') {
            checkPermission('manage_all_posts');
            $result = $this->postAdminModel->postAddRemoveSlider($id);
            if ($result == "removed") {
                setSuccessMessage("msg_remove_slider");
            }
            if ($result == "added") {
                setSuccessMessage("msg_add_slider");
            }
        }
        //if option add remove from picked
        if ($option == 'add-remove-from-picked') {
            checkPermission('manage_all_posts');
            $result = $this->postAdminModel->postAddRemovePicked($id);
            if ($result == "removed") {
                setSuccessMessage("msg_remove_picked");
            }
            if ($result == "added") {
                setSuccessMessage("msg_add_picked");
            }
        }
        //if option approve
        if ($option == 'approve') {
            checkPermission('manage_all_posts');
            if (isAdmin()) {
                if ($this->postAdminModel->approvePost($id)) {
                    setSuccessMessage("msg_post_approved");
                } else {
                    setErrorMessage("msg_error");
                }
            }
        }
        //if option publish
        if ($option == 'publish') {
            checkPermission('manage_all_posts');
            if ($this->postAdminModel->publishPost($id)) {
                setSuccessMessage("msg_published");
            } else {
                setErrorMessage("msg_error");
            }
        }
        //if option publish draft
        if ($option == 'publish_draft') {
            $this->checkRolePermission();
            if ($this->postAdminModel->publishDraft($id)) {
                setSuccessMessage("msg_published");
            } else {
                setErrorMessage("msg_error");
            }
        }

        resetCacheDataOnChange();
        redirectToBackURL();
    }

    /**
     * Auto Post Deletion Post
     */
    public function autoPostDeletionPost()
    {
        checkPermission('manage_all_posts');
        if ($this->settingsModel->updateAutoPostDeletionSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('auto-post-deletion'));
    }

    /**
     * Delete Post
     */
    public function deletePost()
    {
        $this->checkRolePermission();
        $id = inputPost('id');
        if ($this->postAdminModel->deletePost($id)) {
            resetCacheDataOnChange();
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Delete Selected Posts
     */
    public function deleteSelectedPosts()
    {
        $this->checkRolePermission();
        $postIds = inputPost('post_ids');
        $this->postAdminModel->deleteMultiPosts($postIds);
        resetCacheDataOnChange();
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Save Home Slider Post Order
     */
    public function homeSliderPostsOrderPost()
    {
        checkPermission('manage_all_posts');
        $postId = inputPost('id');
        $order = inputPost('slider_order');
        $this->postAdminModel->saveHomeSliderPostOrder($postId, $order);
        resetCacheDataOnChange();
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Get Video from URL
     */
    public function getVideoFromURL()
    {
        include(APPPATH . 'Libraries/VideoUrlParser.php');
        $parser = new \VideoUrlParser();
        $url = inputPost('url');
        $data = [
            'video_embed_code' => $parser->getEmbedCode($url),
            'video_thumbnail' => $parser->getThumbnail($url)
        ];
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Delete Post Main Image
     */
    public function deletePostMainImage()
    {
        $this->checkRolePermission();
        $postId = inputPost('post_id');
        $this->postAdminModel->deletePostMainImage($postId);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Delete Additional Image
     */
    public function deletePostAdditionalImage()
    {
        $this->checkRolePermission();
        $fileId = inputPost('file_id');
        $this->postAdminModel->deletePostAdditionalImage($fileId);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Delete Post File
     */
    public function deletePostFile()
    {
        $this->checkRolePermission();
        $fileId = inputPost('file_id');
        $this->postAdminModel->deletePostFile($fileId);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    //get perpage
    public function getPostsPerPage()
    {
        if (!empty(inputGet('show', true))) {
            return clrNum(inputGet('show', true));
        }
        return 15;
    }

    //check role permission
    public function checkRolePermission()
    {
        if (!hasPermission('manage_all_posts') && !hasPermission('add_post')) {
            redirectToUrl(base_url());
            exit();
        }
    }
}
