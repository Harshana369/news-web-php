<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\PostModel;

class ProfileController extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    /**
     * Profile Page
     */
    public function profile($slug)
    {
        $model = new AuthModel();
        $data['user'] = $model->getUserBySlug($slug);
        if (empty($data["user"])) {
            return redirect()->to(langBaseUrl());
        }
        $data = setPageMeta($data['user']->username, $data);

        $postModel = new PostModel();
        $numRows = $postModel->getPostCountByUser($data['user']->id);
        $data['pager'] = paginate($this->paginationPerPage, $numRows);
        $data['posts'] = [];
        if ($numRows > 0) {
            $data['posts'] = $postModel->getUserPostsPaginated($data['user']->id, $this->paginationPerPage, $data['pager']->offset);
        }

        $data["following"] = $model->getFollowingUsers($data['user']->id);
        $data["followers"] = $model->getFollowers($data['user']->id);

        echo view('partials/_header', $data);
        echo view('profile/profile', $data);
        echo view('partials/_footer');
    }

    /**
     * Edit Profile
     */
    public function editProfile()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $data = setPageMeta(trans("update_profile"));
        $data["user"] = user();
        $data["activeTab"] = "update_profile";

        echo view('partials/_header', $data);
        echo view('settings/edit_profile', $data);
        echo view('partials/_footer');
    }

    /**
     * Edit Profile Post
     */
    public function editProfilePost()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $val = \Config\Services::validation();
        $val->setRule('username', trans("username"), 'required|max_length[255]');
        $val->setRule('email', trans("email"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $data = [
                'username' => inputPost('username'),
                'slug' => inputPost('slug'),
                'email' => inputPost('email'),
                'about_me' => inputPost('about_me'),
                'show_email_on_profile' => !empty(inputPost('show_email_on_profile')) ? 1 : 0,
                'show_rss_feeds' => !empty(inputPost('show_rss_feeds')) ? 1 : 0,
            ];
            $model = new AuthModel();
            $user = user();
            //is email unique
            if (!$model->isUniqueEmail($data["email"], $user->id)) {
                setErrorMessage("email_unique_error");
                return redirect()->to(langBaseUrl('settings'));
            }
            //is username unique
            if (!$model->isUniqueUsername($data["username"], $user->id)) {
                setErrorMessage("msg_username_unique_error");
                return redirect()->to(langBaseUrl('settings'));
            }
            //is slug unique
            if (!$model->isSlugUnique($data["slug"], $user->id)) {
                setErrorMessage("msg_slug_used");
                return redirect()->to(langBaseUrl('settings'));
            }
            if ($model->updateProfile($data, $user)) {
                setSuccessMessage("msg_updated");
                return redirect()->to(langBaseUrl('settings'));
            } else {
                setErrorMessage("msg_error");
                return redirect()->to(langBaseUrl('settings'));
            }
        }
        return redirect()->to(langBaseUrl('settings'));
    }

    /**
     * Social Accounts
     */
    public function socialAccounts()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $data = setPageMeta(trans("social_accounts"));
        $data["user"] = user();
        $data["activeTab"] = "social_accounts";

        echo view('partials/_header', $data);
        echo view('settings/social_accounts', $data);
        echo view('partials/_footer');
    }

    /**
     * Social Accounts Post
     */
    public function socialAccountsPost()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $model = new AuthModel();
        if ($model->editSocialAccounts()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(langBaseUrl('settings/social-accounts'));
    }

    /**
     * Change Password
     */
    public function changePassword()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $data = setPageMeta(trans("change_password"));
        $data["user"] = user();
        $data["activeTab"] = "change_password";

        echo view('partials/_header', $data);
        echo view('settings/change_password', $data);
        echo view('partials/_footer');
    }

    /**
     * Change Password Post
     */
    public function changePasswordPost()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $val = \Config\Services::validation();
        $val->setRule('password', trans("password"), 'required|min_length[4]|max_length[255]');
        $val->setRule('password_confirm', trans("confirm_password"), 'required|matches[password]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $model = new AuthModel();
            if ($model->changePassword()) {
                setSuccessMessage("message_change_password");
            } else {
                setErrorMessage("change_password_error");
            }
        }
        return redirect()->to(langBaseUrl('settings/change-password'));
    }

    /**
     * Delete Account
     */
    public function deleteAccount()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $data = setPageMeta(trans("delete_account"));
        $data["user"] = user();
        $data["activeTab"] = "delete_account";

        echo view('partials/_header', $data);
        echo view('settings/delete_account', $data);
        echo view('partials/_footer');
    }

    /**
     * Delete Account Post
     */
    public function deleteAccountPost()
    {
        if (!authCheck()) {
            return redirect()->to(langBaseUrl());
        }
        $confirm = inputPost('confirm');
        $password = inputPost('password');
        if (empty($confirm)) {
            setErrorMessage("msg_error");
            redirectToBackURL();
        }
        if (!password_verify($password, user()->password)) {
            setErrorMessage("wrong_password");
            redirectToBackURL();
        }

        //delete account
        $this->authModel->deleteUser(user()->id);
        return redirect()->to(langBaseUrl());
    }

    /**
     * Follow Unfollow User
     */
    public function followUnfollowUser()
    {
        if (!authCheck()) {
            redirect(langBaseUrl());
        }
        $model = new AuthModel();
        $model->followUnfollowUser();
        return redirect()->back();
    }

}
