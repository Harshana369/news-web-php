<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\PostAdminModel;
use App\Models\SettingsModel;

class CommonController extends BaseController
{
    /**
     * Admin Login
     */
    public function adminLogin()
    {
        if (authCheck()) {
            if (isAdmin() || isAuthor()) {
                return redirect()->to(adminUrl());
            }
            return redirect()->to(base_url());
        }

        $data['title'] = trans("login");
        $data['description'] = trans("login") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("login") . ', ' . $this->settings->application_name;
        echo view('admin/login', $data);
    }

    /**
     * Admin Login Post
     */
    public function adminLoginPost()
    {
        if (authCheck()) {
            return redirect()->to(adminUrl());
        }
        $val = \Config\Services::validation();
        $val->setRule('username', trans("username"), 'required|max_length[255]');
        $val->setRule('password', trans("password"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $username = inputPost('username');
            $user = $this->authModel->getUserByUsername($username);
            if (empty($user)) {
                $user = $this->authModel->getUserByEmail($username);
            }
            if (!empty($user)) {
                //maintenance mode
                if ($this->generalSettings->maintenance_mode_status == 1) {
                    $userRole = $this->authModel->getRole($user->role_id);
                    if ($userRole->is_super_admin != 1 && $userRole->is_admin != 1) {
                        setErrorMessage("Site under construction! Please try again later.", false);
                        return redirect()->to(adminUrl('login'));
                    }
                }
                $result = $this->authModel->login();
                if ($result == "banned") {
                    setErrorMessage("message_ban_error");
                    return redirect()->to()->back();
                } elseif ($result == "success") {
                    return redirect()->to(adminUrl());
                }
            }
        }
        setErrorMessage("login_error");
        return redirect()->to(adminUrl('login'))->withInput();
    }


    /**
     * Logout
     */
    public function logout()
    {
        $this->authModel->logout();
        return redirect()->back();
    }
}
