<?php namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends BaseModel
{
    protected $builder;
    protected $builderFollowers;
    protected $builderRoles;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('users');
        $this->builderFollowers = $this->db->table('followers');
        $this->builderRoles = $this->db->table('roles_permissions');
    }

    //input values
    public function inputValues()
    {
        return [
            'username' => inputPost('username', true),
            'email' => inputPost('email', true),
            'password' => inputPost('password')
        ];
    }

    //login
    public function login()
    {
        $data = $this->inputValues();
        $user = $this->getUserByUsername($data['username']);
        if (empty($user)) {
            $user = $this->getUserByEmail($data['username']);
        }
        if (!empty($user)) {
            if (!password_verify($data['password'], $user->password)) {
                return false;
            }
            if ($user->status == 0) {
                return "banned";
            }
            //check user role
            $role = $this->getRole($user->role_id);
            if (empty($role)) {
                return false;
            }
            //check registration system enabled
            if ($this->generalSettings->registration_system != 1) {
                if ($role->is_super_admin != 1 && !$role->is_admin && !$role->is_author) {
                    return false;
                }
            }
            //login user
            $this->loginUser($user);
            return "success";
        } else {
            return false;
        }
    }

    //login with facebook
    public function loginWithFacebook($fbUser)
    {
        if (!empty($fbUser)) {
            $user = $this->getUserByEmail($fbUser->email);
            if (!empty($user)) {
                if ($user->status != 1) {
                    return false;
                }
            }
            //check if user registered
            if (empty($user)) {
                if (empty($fbUser->name)) {
                    $fbUser->name = "user-" . uniqid();
                }
                $username = $this->generateUniqueUsername($fbUser->name);
                $slug = $this->generateUniqueSlug($username);
                //add user to database
                $data = array(
                    'facebook_id' => $fbUser->id,
                    'email' => $fbUser->email,
                    'username' => $username,
                    'slug' => $slug,
                    'avatar' => "https://graph.facebook.com/" . $fbUser->id . "/picture?type=large",
                    'user_type' => "facebook",
                    'role_id' => !empty($this->generalSettings->default_role_id) ? $this->generalSettings->default_role_id : 3,
                    'session_token' => generateToken(),
                    'reset_token' => generateToken(),
                    'last_seen' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                if (!empty($data['email'])) {
                    $this->builder->insert($data);
                    $user = $this->getUserByEmail($fbUser->email);
                    $this->loginUser($user);
                }
            } else {
                //login
                $this->loginUser($user);
            }
        }
    }

    //login with google
    public function loginWithGoogle($gUser)
    {
        if (!empty($gUser)) {
            $user = $this->getUserByEmail($gUser->email);
            if (!empty($user)) {
                if ($user->status != 1) {
                    return false;
                }
            }
            //check if user registered
            if (empty($user)) {
                if (empty($gUser->name)) {
                    $gUser->name = "user-" . uniqid();
                }
                $username = $this->generateUniqueUsername($gUser->name);
                $slug = $this->generateUniqueSlug($username);
                //add user to database
                $data = array(
                    'google_id' => $gUser->id,
                    'email' => $gUser->email,
                    'username' => $username,
                    'slug' => $slug,
                    'avatar' => $gUser->avatar,
                    'user_type' => "google",
                    'role_id' => !empty($this->generalSettings->default_role_id) ? $this->generalSettings->default_role_id : 3,
                    'session_token' => generateToken(),
                    'reset_token' => generateToken(),
                    'last_seen' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                if (!empty($data['email'])) {
                    $this->builder->insert($data);
                    $user = $this->getUserByEmail($gUser->email);
                    $this->loginUser($user);
                }
            } else {
                //login
                $this->loginUser($user);
            }
        }
    }

    //login user
    public function loginUser($user)
    {
        if (!empty($user)) {
            $this->session->regenerate();
            $userData = array(
                'auth_user_id' => $user->id,
                'auth_token' => $user->session_token,
            );
            $this->session->set($userData);
        }
    }

    //register
    public function register()
    {
        $data = $this->inputValues();
        $data['username'] = str_replace('@', '', $data['username'] ?? '');
        $data['role_id'] = !empty($this->generalSettings->default_role_id) ? $this->generalSettings->default_role_id : 3;
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['slug'] = $this->generateUniqueSlug($data["username"]);
        $data['session_token'] = generateToken();
        $data['reset_token'] = generateToken();
        $data['last_seen'] = date('Y-m-d H:i:s');
        $data["created_at"] = date('Y-m-d H:i:s');
        if ($this->builder->insert($data)) {
            $id = $this->db->insertID();
            $user = $this->getUser($id);
            if (!empty($user)) {
                $this->loginUser($user);
            }
            return true;
        }
        return false;
    }

    //add user
    public function addUser()
    {
        $data = $this->inputValues();
        $data['username'] = str_replace('@', '', $data['username'] ?? '');
        $data['role_id'] = inputPost('role_id');
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['slug'] = $this->generateUniqueSlug($data["username"]);
        $data['session_token'] = generateToken();
        $data['reset_token'] = generateToken();
        $data['last_seen'] = date('Y-m-d H:i:s');
        $data["created_at"] = date('Y-m-d H:i:s');
        return $this->builder->insert($data);
    }

    //generate unique username
    public function generateUniqueUsername($username)
    {
        $newUsername = $username;
        $counter = 1;
        while (!empty($this->getUserByUsername($newUsername))) {
            if ($counter > 100) {
                $newUsername = $username . '-' . uniqid();
                break;
            }
            $newUsername = $username . '-' . $counter;
            $counter++;
        }
        return $newUsername;
    }

    //generate uniqe slug
    public function generateUniqueSlug($username)
    {
        $slug = strSlug($username);
        $originalSlug = $slug;
        $counter = 1;
        while (!empty($this->getUserBySlug($slug))) {
            $slug = strSlug($originalSlug . '-' . $counter);
            $counter++;
            if ($counter > 100) {
                $slug = strSlug($originalSlug . '-' . uniqid());
                break;
            }
        }
        return $slug;
    }

    //logout
    public function logout()
    {
        helperDeleteSession('auth_user_id');
        helperDeleteSession('auth_token');
    }

    //reset password
    public function resetPassword($token)
    {
        $user = $this->getUserByResetToken($token);
        if (!empty($user)) {
            $data = [
                'password' => password_hash(inputPost('password'), PASSWORD_DEFAULT),
                'reset_token' => generateToken()
            ];
            return $this->builder->where('id', $user->id)->update($data);
        }
        return false;
    }

    //change user role
    public function changeUserRole($id, $roleId)
    {
        $user = $this->getUser($id);
        if (!empty($user)) {
            return $this->builder->where('id', $user->id)->update(['role_id' => clrNum($roleId)]);
        }
        return false;
    }

    //delete user
    public function deleteUser($id)
    {
        $user = $this->getUser($id);
        if (!empty($user)) {

            $role = $this->getRole($user->role_id);
            if (!empty($role) && $role->is_super_admin == 1) {
                return false;
            }

            if (file_exists(FCPATH . $user->avatar)) {
                @unlink(FCPATH . $user->avatar);
            }
            $this->db->table('comments')->where('user_id', $user->id)->delete();
            $this->db->table('reading_lists')->where('user_id', $user->id)->delete();
            $this->db->table('rss_feeds')->where('user_id', $user->id)->delete();
            $posts = $this->db->table('posts')->where('user_id', $user->id)->get()->getResult();
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    $postAdminModel = new PostAdminModel();
                    $postAdminModel->deletePost($post->id);
                }
            }
            return $this->builder->where('id', $user->id)->delete();
        }
        return false;
    }

    //ban user
    public function banUser($id)
    {
        $user = $this->getUser($id);
        if (!empty($user)) {
            $role = $this->getRole($user->role_id);
            if (!empty($role) && $role->is_super_admin == 1) {
                return false;
            }

            return $this->builder->where('id', $user->id)->update(['status' => 0]);
        }
        return false;
    }

    //remove user ban
    public function removeUserBan($id)
    {
        $user = $this->getUser($id);
        if (!empty($user)) {
            return $this->builder->where('id', $user->id)->update(['status' => 1]);
        }
        return false;
    }

    //get user by id
    public function getUser($id)
    {
        return $this->builder->where('id', clrNum($id))->get()->getRow();
    }

    //get user by slug
    public function getUserBySlug($slug)
    {
        return $this->builder->where('slug', removeSpecialCharacters($slug))->get()->getRow();
    }

    //get user by username
    public function getUserByUsername($username)
    {
        return $this->builder->where('username', removeForbiddenCharacters($username))->get()->getRow();
    }

    //get user by email
    public function getUserByEmail($email)
    {
        return $this->builder->where('email', removeForbiddenCharacters($email))->get()->getRow();
    }

    //get user by reset token
    public function getUserByResetToken($token)
    {
        return $this->builder->where('reset_token', cleanStr($token))->get()->getRow();
    }

    //get users
    public function getUsers()
    {
        return $this->builder->join('roles_permissions', 'roles_permissions.id = users.role_id')->select('users.*, role_name, permissions, is_super_admin, is_admin, is_author')->get()->getResult();
    }

    //get authors
    public function getAuthors()
    {
        return $this->builder->join('roles_permissions', 'roles_permissions.id = users.role_id')->select('users.*, role_name, permissions, is_super_admin, is_admin, is_author')
            ->where('is_author', 1)->orWhere('is_admin', 1)->get()->getResult();
    }

    //get last added users
    public function getLastAddedUsers()
    {
        return $this->builder->orderBy('users.id', 'DESC')->limit(7)->get()->getResult();
    }

    //user count
    public function getUserCount()
    {
        $this->filterUsers();
        return $this->builder->countAllResults();
    }

    //get paginated users
    public function getUsersPaginated($perPage, $offset)
    {
        $this->filterUsers();
        return $this->builder->orderBy('users.id', 'DESC')->limit($perPage, $offset)->get()->getResult();
    }

    //filter users
    public function filterUsers()
    {
        $q = inputGet('q');
        if (!empty($q)) {
            $this->builder->groupStart()->like('username', cleanStr($q))->orLike('email', cleanStr($q))->groupEnd();
        }
        //status
        $status = inputGet('status');
        if ($status == 'active') {
            $this->builder->where('status', 1);
        } elseif ($status == 'banned') {
            $this->builder->where('status', 0);
        }
        //role
        $role = clrNum(inputGet('role'));
        if (!empty($role)) {
            $this->builder->where('role_id', $role);
        }
    }

    //load more users
    public function loadMoreUsers($q, $perPage, $offset)
    {
        $q = cleanStr($q);
        if (!empty($q)) {
            $this->builder->like('username', $q)->orLike('email', $q);
        }
        return $this->builder->select('id, username, email')->orderBy('id')->limit($perPage, $offset)->get()->getResult();
    }

    //check if email is unique
    public function isUniqueEmail($email, $userId = 0)
    {
        $user = $this->getUserByEmail($email);
        if ($userId == 0) {
            if (!empty($user)) {
                return false;
            }
            return true;
        } else {
            if (!empty($user) && $user->id != $userId) {
                return false;
            }
            return true;
        }
    }

    //check if username is unique
    public function isUniqueUsername($username, $userId = 0)
    {
        $user = $this->getUserByUsername($username);
        if ($userId == 0) {
            if (!empty($user)) {
                return false;
            }
            return true;
        } else {
            if (!empty($user) && $user->id != $userId) {
                return false;
            }
            return true;
        }
    }

    //check if slug is unique
    public function isSlugUnique($slug, $id)
    {
        $user = $this->builder->where('slug', removeSpecialCharacters($slug))->where('id != ', clrNum($id))->get()->getRow();
        if (empty($user)) {
            return true;
        }
        return false;
    }

    //get user emails by ids
    public function getUserEmailsByIds($ids)
    {
        $emails = array();
        $rows = $this->builder->select('email')->whereIn('id', $ids, false)->get()->getResult();
        if (!empty($rows)) {
            $emails = array_map(function ($item) {
                return $item->email;
            }, $rows);
        }
        return $emails;
    }

    //update last seen time
    public function updateLastSeen()
    {
        if (authCheck()) {
            $this->builder->where('id', user()->id)->update(['last_seen' => date('Y-m-d H:i:s')]);
        }
    }

    /**
     * --------------------------------------------------------------------
     * Profile
     * --------------------------------------------------------------------
     */

    //get following users
    public function getFollowingUsers($followerId)
    {
        $this->builderFollowers->join('users', 'followers.following_id = users.id')->select('users.*');
        return $this->builderFollowers->where('follower_id', clrNum($followerId))->get()->getResult();
    }

    //get followers
    public function getFollowers($followingId)
    {
        $this->builderFollowers->join('users', 'followers.follower_id = users.id')->select('users.*');
        return $this->builderFollowers->where('following_id', clrNum($followingId))->get()->getResult();
    }

    //get follow
    public function getFollow($followingId, $followerId)
    {
        return $this->builderFollowers->where('following_id', clrNum($followingId))->where('follower_id', clrNum($followerId))->get()->getRow();
    }

    //is user follows
    public function isUserFollows($followingId, $followerId)
    {
        $follow = $this->getFollow($followingId, $followerId);
        if (!empty($follow)) {
            return true;
        }
        return false;
    }

    //follow user
    public function followUnfollowUser()
    {
        $data = [
            'following_id' => inputPost('following_id'),
            'follower_id' => user()->id
        ];
        $follow = $this->getFollow($data["following_id"], $data["follower_id"]);
        if (empty($follow)) {
            $this->builderFollowers->insert($data);
        } else {
            $this->builderFollowers->where('id', $follow->id)->delete();
        }
    }

    //update profile
    public function updateProfile($data, $user)
    {
        if (!empty($user)) {
            $model = new UploadModel();
            $tempPath = $model->uploadTempImage('file');
            if (!empty($tempPath) && !empty($tempPath['path'])) {
                $data["avatar"] = $model->uploadAvatar($user->id, $tempPath['path']);
                $model->deleteTempFile($tempPath['path']);
                deleteFileFromServer($user->avatar);
            }
            return $this->builder->where('id', $user->id)->update($data);
        }
        return false;
    }

    //edit update social accounts
    public function editSocialAccounts()
    {
        if (authCheck()) {
            $settingsModel = new SettingsModel();
            $social = $settingsModel->getSocialMediaData(true);
            $data['social_media_data'] = !empty($social) ? serialize($social) : '';
            return $this->builder->where('id', user()->id)->update($data);
        }
        return false;
    }

    //edit user
    public function editUser($id)
    {
        $user = $this->getUser($id);
        if (!empty($user)) {
            $data = [
                'email' => inputPost('email'),
                'username' => inputPost('username', true),
                'slug' => inputPost('slug', true),
                'about_me' => inputPost('about_me'),
            ];

            $settingsModel = new SettingsModel();
            $social = $settingsModel->getSocialMediaData(true);
            $data['social_media_data'] = !empty($social) ? serialize($social) : '';

            $model = new UploadModel();
            $tempPath = $model->uploadTempImage('file');
            if (!empty($tempPath) && !empty($tempPath['path'])) {
                $data["avatar"] = $model->uploadAvatar($user->id, $tempPath['path']);
                $model->deleteTempFile($tempPath['path']);
                deleteFileFromServer($user->avatar);
            }
            return $this->builder->where('id', $user->id)->update($data);
        }
        return false;
    }

    //change password
    public function changePassword()
    {
        $data = [
            'old_password' => inputPost('old_password'),
            'password' => inputPost('password'),
            'password_confirm' => inputPost('password_confirm')
        ];
        $user = user();
        if (!empty($user)) {
            if (inputPost('is_pass_exist') == 1) {
                if (!password_verify($data['old_password'], $user->password)) {
                    setErrorMessage("wrong_password_error");
                    return redirect()->back()->withInput();
                }
            }
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $sessionToken = generateToken();
            if ($this->builder->where('id', $user->id)->update(['password' => $password, 'session_token' => $sessionToken])) {
                $this->session->set('auth_token', $sessionToken);
                return true;
            }
        }
        return false;
    }

    //add role
    public function addRole()
    {
        $nameArray = array();
        $permissionsArray = array();
        foreach ($this->languages as $language) {
            $item = [
                'lang_id' => $language->id,
                'name' => inputPost('role_name_' . $language->id)
            ];
            array_push($nameArray, $item);
        }
        $permissions = inputPost('permissions');
        $isAdmin = 0;
        $isAuthor = 0;
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                array_push($permissionsArray, $permission);
                if ($permission != 2) {
                    $isAdmin = 1;
                }
                if ($permission == 2) {
                    $isAuthor = 1;
                }
            }
        }
        $permissionsStr = implode(',', $permissionsArray ?? '');
        $data = [
            'role_name' => serialize($nameArray),
            'permissions' => $permissionsStr,
            'is_super_admin' => 0,
            'is_admin' => $isAdmin,
            'is_author' => $isAuthor,
            'is_default' => 0
        ];
        return $this->builderRoles->insert($data);
    }

    //edit role
    public function editRole($id)
    {
        $role = $this->getRole($id);
        if (!empty($role)) {
            $nameArray = array();
            $permissionsArray = array();
            foreach ($this->languages as $language) {
                $item = [
                    'lang_id' => $language->id,
                    'name' => inputPost('role_name_' . $language->id)
                ];
                array_push($nameArray, $item);
            }
            $data = [
                'role_name' => serialize($nameArray)
            ];
            if ($role->is_default != 1) {
                $permissions = inputPost('permissions');
                $isAdmin = 0;
                $isAuthor = 0;
                if (!empty($permissions)) {
                    foreach ($permissions as $permission) {
                        array_push($permissionsArray, $permission);
                        if ($permission != 2) {
                            $isAdmin = 1;
                        }
                        if ($permission == 2) {
                            $isAuthor = 1;
                        }
                    }
                }
                $permissionsStr = implode(',', $permissionsArray ?? '');
                $data['permissions'] = $permissionsStr;
                $data['is_admin'] = $isAdmin;
                $data['is_author'] = $isAuthor;
            }
            return $this->builderRoles->where('id', $role->id)->update($data);
        }
        return false;
    }

    //get roles
    public function getRoles()
    {
        return $this->builderRoles->get()->getResult();
    }

    //get role
    public function getRole($id)
    {
        return $this->builderRoles->where('id', clrNum($id))->get()->getRow();
    }

    //delete role
    public function deleteRole($id)
    {
        $role = $this->getRole($id);
        if (!empty($role)) {
            return $this->builderRoles->where('id', $role->id)->delete();
        }
        return false;
    }

    //set default user role
    public function setDefaultUserRole()
    {
        $roleId = inputPost('default_role_id');
        $role = $this->getRole($roleId);
        if (!empty($role)) {
            return $this->db->table("general_settings")->where('id', 1)->update(['default_role_id' => $role->id]);
        }
        return false;
    }
}