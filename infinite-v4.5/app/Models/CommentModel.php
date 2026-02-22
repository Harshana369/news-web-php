<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Globals;

class CommentModel extends BaseModel
{
    protected $builder;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('comments');
    }

    //add comment
    public function addComment()
    {
        $commentStatus = 1;
        if ($this->generalSettings->comment_approval_system == 1 && !hasPermission('comments')) {
            $commentStatus = 0;
        }
        $data = [
            'parent_id' => inputPost('parent_id'),
            'post_id' => inputPost('post_id'),
            'name' => inputPost('name'),
            'email' => inputPost('email'),
            'comment' => inputPost('comment'),
            'status' => $commentStatus,
            'ip_address' => '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (empty($data['parent_id'])) {
            $data['parent_id'] = 0;
        }
        if (!empty($data['post_id']) && !empty($data['comment'])) {
            $data['user_id'] = 0;
            if (authCheck()) {
                $data['user_id'] = user()->id;
                $data['name'] = user()->username;
                $data['email'] = user()->email;
            }
            $ip = $this->request->getIPAddress();
            if (!empty($ip)) {
                $data['ip_address'] = $ip;
            }
            if ($this->builder->insert($data)) {
                if ($commentStatus == 1) {
                    $this->updatePostTotalComments($data['post_id']);
                }
            }
        }
    }

    //get comment
    public function getComment($id)
    {
        return $this->builder->where('id', clrNum($id))->get()->getRow();
    }

    //get comments
    public function getComments($postId, $limit)
    {
        return $this->builder->join('users', 'users.id = comments.user_id', 'left')->select('comments.*, users.username AS user_username, users.slug AS user_slug, users.avatar AS user_avatar')
            ->where('comments.post_id', clrNum($postId))->where('comments.parent_id = 0')->where('comments.status = 1')->orderBy('comments.id DESC')->get(clrNum($limit) + 1)->getResult();
    }

    //comment count
    public function getCommentCount()
    {
        $this->filterComments();
        return $this->builder->countAllResults();
    }

    //get paginated comments
    public function getCommentsPaginated($perPage, $offset)
    {
        $this->filterComments();
        return $this->builder->orderBy('comments.id', 'DESC')->limit($perPage, $offset)->get()->getResult();
    }

    //filter comments
    public function filterComments()
    {
        //status
        $status = inputGet('status');
        if ($status == 'approved') {
            $this->builder->where('status', 1);
        } elseif ($status == 'pending') {
            $this->builder->where('status', 0);
        }
    }

    //get last comments
    public function getLastComments($limit, $type = 'approved')
    {
        $status = $type == 'pending' ? 0 : 1;
        $this->builder->where('comments.status', $status)->orderBy('comments.id', 'DESC')->get(clrNum($limit))->getResult();
    }

    //get subcomments
    public function getSubComments($parentId)
    {
        return $this->builder->join('users', 'users.id = comments.user_id', 'left')->select('comments.*, users.username AS user_username, users.slug AS user_slug, users.avatar AS user_avatar')
            ->where('comments.parent_id', clrNum($parentId))->where('comments.status = 1')->orderBy('comments.id DESC')->get()->getResult();
    }

    //approve comment
    public function approveComment($id)
    {
        $comment = $this->getComment($id);
        if (!empty($comment)) {
            if ($this->builder->where('id', $comment->id)->update(['status' => 1])) {
                $this->updatePostTotalComments($comment->post_id);
                return true;
            }
        }
        return false;
    }

    //approve multi comments
    public function approveMultiComments($comment_ids)
    {
        if (!empty($comment_ids)) {
            foreach ($comment_ids as $id) {
                $this->approveComment($id);
            }
        }
    }

    //update post total comments
    public function updatePostTotalComments($postId)
    {
        $post = getPostById($postId);
        if (!empty($post)) {
            $count = $this->builder->where('post_id', $post->id)->where('comments.parent_id', 0)->where('status', 1)->countAllResults();
            $this->db->table('posts')->where('id', $post->id)->update(['comment_count' => $count]);
        }
    }

    //delete comment
    public function deleteComment($id)
    {
        $comment = $this->getComment($id);
        if (!empty($comment)) {
            if ($this->builder->where('id', $comment->id)->delete()) {
                $this->builder->where('parent_id', $comment->id)->delete();
                $this->updatePostTotalComments($comment->post_id);
                return true;
            }
        }
        return false;
    }

    //delete multi comments
    public function deleteMultiComments($commentIds)
    {
        if (!empty($commentIds)) {
            foreach ($commentIds as $id) {
                $this->deleteComment($id);
            }
        }
    }
}
