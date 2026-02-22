<?php namespace App\Models;

use App\Libraries\QueryBuilder;
use CodeIgniter\Model;

class PostModel extends BaseModel
{
    protected $builder;
    protected $builderReadingLists;

    public function __construct()
    {
        parent::__construct();
        $this->builder = new QueryBuilder('posts', $this->db);
        $this->builderReadingLists = $this->db->table('reading_lists');
    }

    public function buildQuery($langId = null, $getByLangId = true)
    {
        if ($langId == null) {
            $langId = $this->activeLangId;
        }

        $tblUsers = FORCE_DB_INDEXES ? 'users FORCE INDEX (PRIMARY)' : 'users';
        $tblImages = FORCE_DB_INDEXES ? 'images FORCE INDEX (PRIMARY)' : 'images';
        $this->builder->join($tblUsers, 'users.id = posts.user_id')->join($tblImages, 'images.id = posts.image_id', 'left')
            ->select("posts.* , users.username as username, users.slug as user_slug, images.image_big, images.image_mid, images.image_small, images.image_mime, images.storage")
            ->where('posts.visibility', 1)->where('posts.status', 1);
        if ($getByLangId) {
            $this->builder->where('posts.lang_id', clrNum($langId));
        }
    }

    //get post
    public function getPostBySlug($slug)
    {
        $this->buildQuery();
        return $this->builder->where('posts.slug', cleanSlug($slug))->get()->getRow();
    }

    //get post by id
    public function getPost($id)
    {
        $this->buildQuery(null, false);
        return $this->builder->where('posts.id', clrNum($id))->get()->getRow();
    }

    //get post count
    public function getPostCount($langId)
    {
        $cacheKey = 'posts_count_lang_' . $langId;
        return getCacheData($cacheKey, function () use ($langId) {
            $this->buildQuery($langId);
            return $this->builder->countAllResults();
        });
    }

    //get posts paginated
    public function getPostsPaginated($langId, $perPage, $offset)
    {
        $cacheKey = 'posts_lang_' . $langId . '_page_' . $perPage . '_' . $offset;
        return getCacheData($cacheKey, function () use ($langId, $perPage, $offset) {
            $this->buildQuery($langId);
            return $this->builder->addUseIndex('idx_created_at')->orderBy('posts.created_at', 'DESC')->limit($perPage, $offset)->get()->getResult();
        });
    }

    //get latest posts
    public function getLatestPosts($langId, $limit)
    {
        $cacheKey = 'posts_latest_lang_' . $langId . '_limit_' . $limit;
        return getCacheData($cacheKey, function () use ($langId, $limit) {
            $this->buildQuery($langId);
            return $this->builder->addUseIndex('idx_created_at')->orderBy('posts.created_at', 'DESC')->limit($limit)->get()->getResult();
        });
    }

    //get slider posts
    public function getSliderPosts($langId)
    {
        $cacheKey = 'posts_slider_lang_' . $langId;
        return getCacheData($cacheKey, function () use ($langId) {
            $this->buildQuery($langId);
            return $this->builder->addUseIndex('idx_posts_slider')->where('is_slider', 1)->orderBy('slider_order')->limit(POSTS_LIMIT_SLIDER)->get()->getResult();
        });
    }

    //get popular posts
    public function getPopularPosts($langId)
    {
        $cacheKey = 'posts_popular_posts_lang_' . $langId;
        return getCacheData($cacheKey, function () use ($langId) {
            $this->buildQuery($langId);
            return $this->builder->addUseIndex('idx_pageviews')->orderBy('pageviews', 'DESC')->limit(POSTS_LIMIT_POPULAR_POSTS)->get()->getResult();
        }, 'stable');
    }

    //get related posts
    public function getRelatedPosts($categoryId, $postId)
    {
        $this->buildQuery();
        $posts = $this->builder->addUseIndex('idx_created_at')->where('posts.id !=', $postId)->where('category_id', $categoryId)->orderBy('posts.created_at', 'DESC')->limit(20)->get()->getResult();
        if (!empty($posts)) {
            shuffle($posts);
            $posts = array_slice($posts, 0, 3);
        }
        return $posts;
    }

    //get picked posts
    public function getOurPicks($langId)
    {
        $cacheKey = 'posts_our_picks_lang_' . $langId;
        return getCacheData($cacheKey, function () use ($langId) {
            $this->buildQuery($langId);
            return $this->builder->addUseIndex('idx_posts_our_picks')->where('is_picked', 1)->orderBy('posts.created_at', 'DESC')->limit(POSTS_LIMIT_OUR_PICKS)->get()->getResult();
        });
    }

    //get random posts
    public function getRandomPosts($langId)
    {
        $cacheKey = 'posts_random_posts_lang_' . $langId;
        return getCacheData($cacheKey, function () use ($langId) {
            $this->buildQuery($langId);
            $posts = $this->builder->addUseIndex('idx_created_at')->orderBy('posts.created_at', 'DESC')->limit(300)->get()->getResult();
            if (!empty($posts)) {
                shuffle($posts);
                $count = countItems($posts);
                $limit = POSTS_LIMIT_POPULAR_POSTS > $count ? $count : POSTS_LIMIT_POPULAR_POSTS;
                $posts = array_slice($posts, 0, $limit);
            }
            return $posts;
        });
    }

    //get category post count
    public function getPostCountByCategory($categoryTree)
    {
        if (empty($categoryTree)) {
            return 0;
        }
        $this->buildQuery();
        return $this->builder->addUseIndex('idx_posts_category')->whereIn('posts.category_id', $categoryTree, false)->where('posts.visibility', 1)->where('posts.status', 1)->countAllResults();
    }

    //get paginated category posts
    public function getCategoryPostsPaginated($categoryTree, $perPage, $offset)
    {
        if (empty($categoryTree)) {
            return [];
        }
        $this->buildQuery();
        return $this->builder->addUseIndex('idx_created_at')->whereIn('posts.category_id', $categoryTree, false)->where('posts.visibility', 1)->where('posts.status', 1)
            ->orderBy('posts.created_at', 'DESC')->limit($perPage, $offset)->get()->getResult();
    }

    //get post count by user
    public function getPostCountByUser($userId)
    {
        $this->buildQuery();
        return $this->builder->where('posts.user_id', clrNum($userId))->countAllResults();
    }

    //get posts by user
    public function getUserPostsPaginated($userId, $perPage, $offset)
    {
        $this->buildQuery();
        return $this->builder->addUseIndex('idx_created_at')->where('posts.user_id', clrNum($userId))->orderBy('posts.created_at', 'DESC')->limit($perPage, $offset)->get()->getResult();
    }

    //get paginated search posts
    public function getSearchPostsPaginated($langId, $q, $perPage, $offset)
    {
        $this->buildQuery($langId);
        return $this->builder->where("MATCH(title, summary, content) AGAINST({$this->db->escape($q)} IN NATURAL LANGUAGE MODE)")->limit(clrNum($perPage) + 1, clrNum($offset))->get()->getResult();
    }

    //get reading list post count
    public function getReadingListCount($userId)
    {
        $this->buildQuery();
        return $this->builder->join('reading_lists', 'posts.id = reading_lists.post_id')->where('reading_lists.user_id', clrNum($userId))->countAllResults();
    }

    //get paginated posts by tag
    public function getReadingListPaginated($userId, $perPage, $offset)
    {
        $this->buildQuery();
        return $this->builder->join('reading_lists', 'posts.id = reading_lists.post_id')
            ->where('reading_lists.user_id', clrNum($userId))->orderBy('posts.created_at', 'DESC')->limit($perPage, $offset)->get()->getResult();
    }

    //get post count by tag
    public function getPostCountByTag($tagId, $langId)
    {
        $this->buildQuery($langId);
        return $this->builder->join('post_tags', 'post_tags.post_id = posts.id')->where('post_tags.tag_id', clrNum($tagId))->countAllResults();
    }

    //get paginated tag posts
    public function getTagPostsPaginated($tagId, $langId, $perPage, $offset)
    {
        $this->buildQuery($langId);
        return $this->builder->join('post_tags', 'post_tags.post_id = posts.id')->where('post_tags.tag_id', clrNum($tagId))->orderBy('posts.created_at DESC')->limit($perPage, $offset)->get()->getResult();
    }

    //increase post pageviews
    public function increasePostPageViews($post)
    {
        if (!empty($post)) {
            if (empty(helperGetSession('post_' . $post->id))) {
                $this->builder->where('id', $post->id)->update(['pageviews' => $post->pageviews + 1]);
                helperSetSession('post_' . $post->id, '1');
            }
        }
    }

    /**
     * --------------------------------------------------------------------
     * FILES
     * --------------------------------------------------------------------
     */

    //get post additional images
    public function getPostAdditionalImages($postId)
    {
        return $this->db->table('post_images')->where('post_id', clrNum($postId))->get()->getResult();
    }

    //get post files
    public function getPostFiles($postId)
    {
        return $this->db->table('post_files')->join('files', 'files.id = post_files.file_id')->select('files.*, post_files.id as post_file_id')->where('post_id', clrNum($postId))->get()->getResult();
    }

    //check post is in the reading list or not
    public function isPostInReadingList($postId)
    {
        if (authCheck()) {
            $user = user();
            if (!empty($user)) {
                if (!empty($this->builderReadingLists->where('post_id', clrNum($postId))->where('user_id', $user->id)->get()->getRow())) {
                    return true;
                }
            }
        }
        return false;
    }

    //add to reading list
    public function addToReadingList($postId)
    {
        if (authCheck() && !empty($postId)) {
            $data = [
                'post_id' => $postId,
                'user_id' => user()->id
            ];
            return $this->builderReadingLists->insert($data);
        }
        return false;
    }

    //get rss posts
    public function getRssPosts($langId, $userId, $categoryId)
    {
        $cacheKey = 'rss_posts_lang_' . clrNum($langId);
        if (!empty($userId)) {
            $cacheKey .= '_author_' . clrNum($userId);
        }
        if (!empty($categoryId)) {
            $cacheKey .= '_cat_' . clrNum($categoryId);
        }
        $posts = cache($cacheKey);
        if (!empty($posts)) {
            return $posts;
        }

        $this->buildQuery($langId);
        if (!empty($userId)) {
            $this->builder->where('posts.user_id', clrNum($userId));
        }
        if (!empty($categoryId)) {
            $categoryTree = getCategoryTreeIdsArray($categoryId);
            if (!empty($categoryTree) && count($categoryTree) > 0) {
                $this->builder->whereIn('posts.category_id', $categoryTree, false);
            }
        }
        $posts = $this->builder->addUseIndex('idx_created_at')->orderBy('posts.created_at DESC')->limit(RSS_POSTS_LIMIT)->get()->getResult();
        cache()->save($cacheKey, $posts, RSS_CACHE_REFRESH_TIME);
        return $posts;
    }

    //delete from reading list
    public function deleteFromReadingList($postId)
    {
        if (authCheck() && !empty($postId)) {
            return $this->builderReadingLists->where('post_id', clrNum($postId))->where('user_id', user()->id)->delete();
        }
        return false;
    }
}
