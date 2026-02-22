<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\PostAdminModel;
use App\Models\TagModel;

class CategoryController extends BaseAdminController
{
    protected $categoryModel;
    protected $tagModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->categoryModel = new CategoryModel();
        $this->tagModel = new TagModel();
    }

    /**
     * Categories
     */
    public function categories()
    {
        checkPermission('categories');
        $data['title'] = trans("categories");
        
        $numRows = $this->categoryModel->getCategoriesCount();
        $data['pager'] = paginate($this->perPage, $numRows);
        $data['categories'] = $this->categoryModel->getCategoriesPaginated($this->perPage, $data['pager']->offset);

        echo view('admin/includes/_header', $data);
        echo view('admin/category/categories', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Category
     */
    public function addCategory()
    {
        checkPermission('categories');
        $data['title'] = trans("categories");
        
        $data['parentCategories'] = $this->categoryModel->getParentCategoriesByLang($this->activeLang->id);

        echo view('admin/includes/_header', $data);
        echo view('admin/category/add', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Category Post
     */
    public function addCategoryPost()
    {
        checkPermission('categories');
        $val = \Config\Services::validation();
        $val->setRule('name', trans("category_name"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            if ($this->categoryModel->addCategory()) {
                setSuccessMessage("msg_item_added");
                redirectToBackURL();
            }
        }
        setErrorMessage("msg_error");
        redirectToBackURL();
    }

    /**
     * Update Category
     */
    public function editCategory($id)
    {
        checkPermission('categories');
        $data['title'] = trans("update_category");
        $data['category'] = $this->categoryModel->getCategory($id);
        
        if (empty($data['category'])) {
            redirectToBackURL();
        }
        $data['parentCategories'] = $this->categoryModel->getParentCategoriesByLang($data['category']->lang_id);

        echo view('admin/includes/_header', $data);
        echo view('admin/category/edit', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Category Post
     */
    public function editCategoryPost()
    {
        checkPermission('categories');
        $val = \Config\Services::validation();
        $val->setRule('name', trans("category_name"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $id = inputPost('id');
            if ($this->categoryModel->editCategory($id)) {
                setSuccessMessage("msg_updated");
                redirectToBackURL();
            }
        }
        setErrorMessage("msg_error");
        redirectToBackURL();
    }

    //get parent categories by language
    public function getParentCategoriesByLang()
    {
        $langId = inputPost('lang_id');
        $data = ['status' => 0, 'content' => ''];
        if (!empty($langId)) {
            $categories = $this->categoryModel->getParentCategoriesByLang($langId);
            if (!empty($categories)) {
                foreach ($categories as $item) {
                    $data['content'] .= '<option value="' . $item->id . '">' . esc($item->name) . '</option>';
                }
                $data['status'] = 1;
            }
        }
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    //get subcategories
    public function getSubCategories()
    {
        $parentId = inputPost('parent_id');
        $data = ['status' => 0, 'content' => ''];
        if (!empty($parentId)) {
            $subCategories = $this->categoryModel->getAllSubcategoriesByParentId($parentId);
            if (!empty($subCategories)) {
                foreach ($subCategories as $item) {
                    $data['content'] .= '<option value="' . $item->id . '">' . esc($item->name) . '</option>';
                }
                $data['status'] = 1;
            }
        }
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Delete Category Post
     */
    public function deleteCategoryPost()
    {
        checkPermission('categories');
        $id = inputPost('id');
        //check subcategories
        if (countItems($this->categoryModel->getAllSubcategoriesByParentId($id)) > 0) {
            setErrorMessage("msg_delete_subcategories");
            exit();
        }
        //check posts
        $postModel = new PostAdminModel();
        if ($postModel->getPostCountByCategory($id) > 0) {
            setErrorMessage("msg_delete_posts");
            exit();
        }
        if ($this->categoryModel->deleteCategory($id)) {
            setSuccessMessage("msg_deleted");
            exit();
        } else {
            setErrorMessage("msg_error");
            exit();
        }
    }

    /*
     * --------------------------------------------------------------------
     * Tags
     * --------------------------------------------------------------------
     */

    /**
     * Tags
     */
    public function tags()
    {
        checkPermission('tags');
        $data['title'] = trans("tags");
        $numRows = $this->tagModel->getTagsCount();
        $data['pager'] = paginate($this->perPage, $numRows);
        $data['tags'] = $this->tagModel->getTagsPaginated($this->perPage, $data['pager']->offset);

        echo view('admin/includes/_header', $data);
        echo view('admin/tag/tags', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Tag Post
     */
    public function addTagPost()
    {
        checkPermission('tags');
        $tag = inputPost('tag');
        $langId = inputPost('lang_id');

        if ($this->tagModel->addTag($tag, $langId)) {
            setSuccessMessage("msg_added");
        } else {
            setErrorMessage("msg_tag_exists");
        }
        redirectToBackURL();
    }

    /**
     * Edit Tag Post
     */
    public function editTagPost()
    {
        checkPermission('tags');
        $id = inputPost('id');
        $tag = inputPost('tag');
        $langId = inputPost('lang_id');

        if ($this->tagModel->editTag($id, $tag, $langId)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Delete Tag Post
     */
    public function deleteTagPost()
    {
        checkPermission('tags');
        $id = inputPost('id');
        if ($this->tagModel->deleteTag($id)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }
}
