<?php namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends BaseModel
{
    protected $builderContact;

    public function __construct()
    {
        parent::__construct();
        $this->builderContact = $this->db->table('contacts');
    }

    /**
     * --------------------------------------------------------------------
     * Navigation
     * --------------------------------------------------------------------
     */

    public function getMenuLinks($langId = null, $getAll = false)
    {
        $cacheKey = 'menu_links_lang_' . $langId;
        if ($getAll) {
            $cacheKey = 'menu_links_all_lang_' . $langId;
        }
        return getCacheData($cacheKey, function () use ($langId, $getAll) {
            $builder = $this->db->table('categories');
            $builder->select("categories.id AS item_id, categories.lang_id AS item_lang_id, categories.name AS item_name, 
                      categories.slug AS item_slug, categories.category_order AS item_order, 'header' AS item_location, 
                      'category' AS item_type, '#' AS item_link, categories.parent_id AS item_parent_id, 
                      categories.show_on_menu AS item_visibility, 
                      (SELECT slug FROM categories AS c WHERE c.id = categories.parent_id) AS item_parent_slug");
            if ($getAll == false) {
                $builder->where('categories.show_on_menu', 1);
            }

            if ($langId !== null) {
                $builder->where('categories.lang_id', $langId);
            }
            $categories = $builder->get()->getResult();

            $builder = $this->db->table('pages');
            $builder->select("pages.id AS item_id, pages.lang_id AS item_lang_id, pages.title AS item_name, 
                      pages.slug AS item_slug, pages.page_order AS item_order, pages.location AS item_location, 
                      'page' AS item_type, pages.link AS item_link, pages.parent_id AS item_parent_id, 
                      pages.page_active AS item_visibility, 
                      (SELECT slug FROM pages AS p WHERE p.id = pages.parent_id) AS item_parent_slug");
            if ($getAll == false) {
                $builder->where('pages.page_active', 1);
            }

            if ($langId !== null) {
                $builder->where('pages.lang_id', $langId);
            }
            $pages = $builder->get()->getResult();

            $menuItems = array_merge($categories, $pages);
            usort($menuItems, function ($a, $b) {
                return $a->item_order <=> $b->item_order ?: strcmp($a->item_name, $b->item_name);
            });

            return $menuItems;
        }, 'static');
    }

    //navigation input values nav
    public function navInputValues()
    {
        return [
            'lang_id' => inputPost('lang_id'),
            'title' => inputPost('title'),
            'link' => inputPost('link'),
            'page_order' => inputPost('page_order'),
            'page_active' => inputPost('page_active'),
            'parent_id' => inputPost('parent_id'),
            'location' => "header"
        ];
    }

    //add navigation link
    public function addNavLink()
    {
        $data = $this->navInputValues();
        if (empty($data["slug"])) {
            $data["slug"] = strSlug($data["title"]);
        }
        if (empty($data['link'])) {
            $data['link'] = "#";
        }
        return $this->db->table('pages')->insert($data);
    }

    //edit navigation link
    public function editNavLink($id)
    {
        $data = $this->navInputValues();
        if (empty($data["slug"])) {
            $data["slug"] = strSlug($data["title"]);
        }
        return $this->db->table('pages')->where('id', clrNum($id))->update($data);
    }

    //update menu limit
    public function updateMenuLimit()
    {
        $data = [
            'menu_limit' => inputPost('menu_limit')
        ];
        return $this->db->table('general_settings')->where('id', 1)->update($data);
    }

    //get parent navigation link
    public function getParentNavLink($parentId, $type)
    {
        if ($type == "page") {
            return $this->db->table('pages')->where('id', clrNum($parentId))->get()->getRow();
        }
        if ($type == "category") {
            return $this->db->table('categories')->where('id', clrNum($parentId))->get()->getRow();
        }
    }

    //hide show home link
    public function hideShowHomeLink()
    {
        if ($this->generalSettings->show_home_link == 1) {
            $data = ['show_home_link' => 0];
        } else {
            $data = ['show_home_link' => 1];
        }
        $this->db->table('general_settings')->where('id', 1)->update($data);
    }

    //sort menu items
    public function sortMenuItems()
    {
        $jsonMenuItems = inputPost('json_menu_items');
        $menuItems = json_decode($jsonMenuItems);
        if (!empty($menuItems)) {
            foreach ($menuItems as $menuItem) {
                if (!empty($menuItem->item_type)) {
                    if ($menuItem->item_type == 'page') {
                        $pageModel = new \App\Models\PageModel();
                        $page = $pageModel->getPage($menuItem->item_id);
                        if (!empty($page)) {
                            $data = [
                                'parent_id' => clrNum($menuItem->parent_id),
                                'page_order' => clrNum($menuItem->new_order)
                            ];
                            $this->db->table('pages')->where('id', $page->id)->update($data);
                        }
                    } elseif ($menuItem->item_type == 'category') {
                        $categoryModel = new \App\Models\CategoryModel();
                        $category = $categoryModel->getCategory($menuItem->item_id);
                        if (!empty($category)) {
                            $data = [
                                'parent_id' => clrNum($menuItem->parent_id),
                                'category_order' => clrNum($menuItem->new_order)
                            ];
                            $this->db->table('categories')->where('id', $category->id)->update($data);
                        }
                    }
                }
            }
        }
    }

    /**
     * --------------------------------------------------------------------
     * Contact
     * --------------------------------------------------------------------
     */

    //add contact message
    public function addContactMessage()
    {
        $data = [
            'name' => inputPost('name'),
            'email' => inputPost('email'),
            'message' => inputPost('message'),
            'ip_address' => '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $ip = $this->request->getIPAddress();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }

        //send email
        if ($this->generalSettings->send_email_contact_messages == 1) {
            $emailModel = new EmailModel();
            $emailModel->sendEmailContactMessage($data["name"], $data["email"], $data["message"]);
        }
        return $this->builderContact->insert($data);
    }

    //get contact messages count
    public function getContactMessagesCount()
    {
        return $this->builderContact->countAllResults();
    }

    //get paginated contact messages
    public function getContactMessagesPaginated($perPage, $offset)
    {
        return $this->builderContact->orderBy('id', 'DESC')->limit($perPage, $offset)->get()->getResult();
    }

    //get last contact messages
    public function getLastContactMessages()
    {
        return $this->builderContact->orderBy('id', 'DESC')->get(5)->getResult();
    }

    //delete contact message
    public function deleteContactMessage($id)
    {
        return $this->builderContact->where('id', clrNum($id))->delete();
    }

    //delete multi contact messages
    public function deleteMultiContactMessages($messageIds)
    {
        if (!empty($messageIds)) {
            foreach ($messageIds as $id) {
                $this->deleteContactMessage($id);
            }
        }
    }
}
