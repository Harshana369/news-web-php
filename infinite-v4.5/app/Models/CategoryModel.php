<?php namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends BaseModel
{
    protected $builder;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('categories');
    }

    //input values
    public function inputValues()
    {
        return [
            'lang_id' => inputPost('lang_id'),
            'name' => inputPost('name'),
            'slug' => inputPost('slug'),
            'parent_id' => inputPost('parent_id'),
            'description' => inputPost('description'),
            'keywords' => inputPost('keywords'),
            'category_order' => inputPost('category_order'),
            'show_on_menu' => inputPost('show_on_menu')
        ];
    }

    //add category
    public function addCategory()
    {
        $data = $this->inputValues();
        if (!empty($data["slug"])) {
            $data["slug"] = removeSpecialCharacters($data["slug"]);
        } else {
            $data["slug"] = strSlug($data["name"]);
        }

        if (empty($data['parent_id'])) {
            $data['parent_id'] = 0;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        if ($this->builder->insert($data)) {
            $id = $this->db->insertID();
            $this->updateSlug($id);
            return true;
        }
        return false;
    }

    //edit category
    public function editCategory($id)
    {
        $category = $this->getCategory($id);
        if (!empty($category)) {
            $data = $this->inputValues();
            if (empty($data["slug"])) {
                $data["slug"] = strSlug($data["name"]);
            }

            if (empty($data['parent_id'])) {
                $data['parent_id'] = 0;
            }

            if ($this->builder->where('id', $category->id)->update($data)) {
                $id = $this->db->insertID();
                $this->updateSlug($id);
                $this->builder->where('parent_id', $category->id)->update(['lang_id' => $data['lang_id']]);
                return true;
            }
        }
        return false;
    }

    //update slug
    public function updateSlug($id)
    {
        $category = $this->getCategory($id);
        if (!empty($category)) {
            if (empty($category->slug) || $category->slug == "-") {
                $data = [
                    'slug' => $category->id
                ];
                $this->builder->where('id', $category->id)->update($data);
            } else {
                if ($this->isSlugUnique($category->slug, $id) == true) {
                    $data = [
                        'slug' => $category->slug . "-" . $category->id
                    ];
                    $this->builder->where('id', $category->id)->update($data);
                }
            }
        }
    }

    //check slug
    public function isSlugUnique($slug, $id)
    {
        $count = $this->builder->where('slug', cleanSlug($slug))->where('categories.id !=', clrNum($id))->countAllResults();
        if ($count > 0) {
            return true;
        }
        return false;
    }

    //get category
    public function getCategory($id)
    {
        return $this->builder->where('id', clrNum($id))->get()->getRow();
    }

    //get category by slug
    public function getCategoryBySlug($slug)
    {
        return $this->builder->where('slug', cleanSlug($slug))->get()->getRow();
    }

    //get categories count
    public function getCategoriesCount()
    {
        $this->filterCategories();
        return $this->builder->countAllResults();
    }

    //get paginated categories
    public function getCategoriesPaginated($perPage, $offset)
    {
        $this->filterCategories();
        return $this->builder->orderBy('id', 'DESC')->limit($perPage, $offset)->get()->getResult();
    }

    //filter categories
    public function filterCategories()
    {
        $q = inputGet('q');
        if (!empty($q)) {
            $this->builder->like('name', cleanStr($q));
        }
        $langId = clrNum(inputGet('lang_id'));
        if (!empty($langId)) {
            $this->builder->where('lang_id', $langId);
        }
    }

    //get categories
    public function getCategories($langId = null)
    {
        $this->builder->select('categories.*, categories.parent_id as category_parent_id, (SELECT slug FROM categories WHERE id = category_parent_id) as parent_slug')
            ->select('(SELECT COUNT(id) FROM posts WHERE category_id = categories.id) as number_of_posts');
        if ($langId != null) {
            $this->builder->where('lang_id', clrNum($langId));
        }
        return $this->builder->orderBy('category_order')->get()->getResult();
    }

    //get parent categories
    public function getParentCategories()
    {
        return $this->builder->where('parent_id', 0)->orderBy('category_order')->get()->getResult();
    }

    //get parent categories by lang
    public function getParentCategoriesByLang($langId)
    {
        $this->builder->select('categories.*, categories.parent_id as category_parent_id, (SELECT slug FROM categories WHERE id = category_parent_id) as parent_slug');
        $this->builder->select('(SELECT COUNT(id) FROM posts WHERE category_id = categories.id) as number_of_posts');
        return $this->builder->where('categories.lang_id', clrNum($langId))->where('categories.parent_id', 0)->orderBy('categories.category_order')->get()->getResult();
    }

    //get subcategories
    public function getSubcategories()
    {
        $this->builder->select('categories.*, categories.parent_id as category_parent_id, (SELECT slug FROM categories WHERE id = category_parent_id) as parent_slug');
        $this->builder->select('(SELECT COUNT(id) FROM posts WHERE category_id = categories.id) as number_of_posts');
        return $this->builder->where('categories.lang_id', clrNum($langId))->where('categories.parent_id !=', 0)->orderBy('categories.category_order')->get()->getResult();
    }

    //get subcategories by parent id
    public function getSubcategoriesByParentId($parentId)
    {
        $this->builder->select('categories.*, categories.parent_id as category_parent_id, (SELECT slug FROM categories WHERE id = category_parent_id) as parent_slug');
        $this->builder->select('(SELECT COUNT(id) FROM posts WHERE category_id = categories.id) as number_of_posts');
        return $this->builder->where('show_on_menu', 1)->where('parent_id', clrNum($parentId))->get()->getResult();
    }

    //get all subcategories by id
    public function getAllSubcategoriesByParentId($parentId)
    {
        $this->builder->select('categories.*, categories.parent_id as category_parent_id, (SELECT slug FROM categories WHERE id = category_parent_id) as parent_slug');
        return $this->builder->where('parent_id', clrNum($parentId))->get()->getResult();
    }

    //get category array
    public function getCategoryArray($id)
    {
        $category = $this->getCategory($id);
        $tree = [
            'parentCategory' => '',
            'subcategory' => ''
        ];
        if (!empty($category)) {
            if ($category->parent_id == 0) {
                $tree['parentCategory'] = $category;
            } else {
                $parent = $this->getCategory($category->parent_id);
                $tree['parentCategory'] = $parent;
                $tree['subcategory'] = $category;
            }
        }
        return $tree;
    }

    //get parent tree
    public function getCategoryTree(): array
    {
        $categories = $this->builder->get()->getResult();
        $tree = [];

        foreach ($categories as $category) {
            $tree[$category->parent_id][] = $category;
        }

        return $tree;
    }

    //get category tree ids
    public function getCategoryTreeIdsArray(int $id, bool $includeParent = true): array
    {
        $tree = $this->getCategoryTree();
        $ids = [];
        if ($includeParent) {
            $ids[] = $id;
        }
        if (isset($tree[$id])) {
            foreach ($tree[$id] as $item) {
                $ids[] = $item->id;
            }
        }

        return $ids;
    }

    //delete category
    public function deleteCategory($id)
    {
        $category = $this->getCategory($id);
        if (!empty($category)) {
            return $this->builder->where('id', $category->id)->delete();
        }
        return false;
    }
}
