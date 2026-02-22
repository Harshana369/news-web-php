<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\NewsletterModel;
use App\Models\PollModel;
use App\Models\PostModel;
use App\Models\ReactionModel;
use App\Models\TagModel;
use Config\Globals;

class AjaxController extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        if (!$this->request->isAJAX()) {
            exit();
        }
        $langId = inputPost('sysLangId');
        if (!empty($langId)) {
            Globals::setActiveLanguage($langId);
        }
    }

    /**
     * AI Writer
     */
    public function generateTextAI()
    {
        hasPermission("ai_writer");
        $model = inputPost('model');
        $temperature = inputPost('temperature');
        $tone = inputPost('tone');
        $length = inputPost('length');
        $topic = inputPost('topic');
        $langId = inputPost('sysLangId');

        // Get language code
        $lang = getLanguageById($langId);
        $langCode = (!empty($lang)) ? $lang->short_form : 'en';

        $data = \Config\AIWriter::generateText($model, $temperature, $tone, $length, $topic, $langCode);
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Switch Theme Mode
     */
    public function switchThemeMode()
    {
        $mode = inputPost('theme_mode');
        if ($mode == 'dark') {
            helperSetCookie('theme_mode', 'dark');
        } else {
            helperSetCookie('theme_mode', 'light');
        }
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

    /**
     * Load More Search Posts
     */
    public function loadMoreSearchPosts()
    {
        $langId = clrNum(inputPost('lang_id'));
        $page = clrNum(inputPost('page'));
        $type = inputPost('type');
        $q = inputPost('q');
        if ($page < 1) {
            $page = 1;
        }

        $perPage = $this->generalSettings->pagination_per_page;
        $offset = ($page - 1) * $perPage;

        if (!empty($q)) {
            $q = strip_tags($q);
        }
        $postModel = new PostModel();
        $posts = $postModel->getSearchPostsPaginated($langId, $q, $perPage, $offset);
        Globals::setActiveLanguage($langId);
        $data = ['result' => 0];
        if (!empty($posts)) {
            $htmlContent = '';
            $i = 0;
            foreach ($posts as $post) {
                if ($i < $perPage) {
                    $htmlContent .= view('post/_post_item', ['item' => $post]);
                }
                $i++;
            }
            $data = [
                'result' => 1,
                'htmlContent' => $htmlContent,
                'hasMore' => countItems($posts) > $perPage ? true : false
            ];
        }

        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Load More Users
     */
    public function loadMoreUsers()
    {
        checkPermission('newsletter');
        $page = clrNum(inputPost('page'));
        $q = inputPost('q');
        $perPage = 500;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;
        $authModel = new \App\Models\AuthModel();
        $users = $authModel->loadMoreUsers($q, $perPage, $offset);
        $htmlContent = '';
        if (!empty($users)) {
            foreach ($users as $user) {
                $htmlContent .= '<tr><td><input type="checkbox" name="user_id[]" value="' . $user->id . '"></td><td>' . $user->id . '</td><td>' . esc($user->username) . '</td><td>' . esc($user->email) . '</td></tr>';
            }
        } else {
            $htmlContent .= '<tr><td colspan="5"><p class="text-muted text-center">' . esc(trans("no_results_found")) . '</p></td></tr>';
        }
        $data = [
            'result' => 1,
            'htmlContent' => $htmlContent
        ];
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Load More Subscribers
     */
    public function loadMoreSubscribers()
    {
        checkPermission('newsletter');
        $page = clrNum(inputPost('page'));
        $q = inputPost('q');
        $perPage = 500;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;
        $model = new NewsletterModel();
        $subscribers = $model->loadMoreSubscribers($q, $perPage, $offset);
        $htmlContent = '';
        if (!empty($subscribers)) {
            foreach ($subscribers as $subscriber) {
                $htmlContent .= '<tr>
                    <td><input type="checkbox" name="subscriber_id[]" value="' . esc($subscriber->id) . '"></td>
                    <td>' . $subscriber->id . '</td>
                    <td>' . esc($subscriber->email) . '</td>
                    <td>
                        <a href="javascript:void(0)" 
                           onclick="deleteItem(\'Admin/deleteSubscriberPost\', \'' . $subscriber->id . '\', \'' . clrQuotes(trans("confirm_item")) . '\');" 
                           class="text-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;' . trans('delete') . '
                        </a>
                    </td>
                </tr>';
            }
        } else {
            $htmlContent .= '<tr><td colspan="5"><p class="text-muted text-center">' . esc(trans("no_results_found")) . '</p></td></tr>';
        }
        $data = [
            'result' => 1,
            'htmlContent' => $htmlContent
        ];
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Add Reaction
     */
    public function addReactionPost()
    {
        $postId = clrNum(inputPost('post_id'));
        $reaction = cleanStr(inputPost('reaction'));
        $data['post'] = getPostById($postId);
        $data['resultArray'] = array();
        $reactionModel = new ReactionModel();
        if (!empty($data['post'])) {
            $data['resultArray'] = $reactionModel->addReaction($postId, $reaction);
        }
        $data['reactions'] = $reactionModel->getReaction($postId);
        $jsonData = [
            'result' => 1,
            'htmlContent' => view('partials/_emoji_reactions', $data)
        ];

        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($jsonData);
    }

    /**
     * Add Poll Vote
     */
    public function addPollVotePost()
    {
        $pollId = inputPost('poll_id');
        $optionId = inputPost('option_id');
        $response = "";
        if (empty($optionId)) {
            $response = "required";
        } else {
            $model = new PollModel();
            $result = $model->addVote($pollId, $optionId);
            if ($result == "success") {
                $data["poll"] = $model->getPoll($pollId);
                $response = view('partials/_poll_results', $data);
            } else {
                $response = "voted";
            }
        }
        $dataJson = [
            'result' => 1,
            'response' => $response
        ];

        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($dataJson);
    }

    /**
     * Add Comment
     */
    public function addCommentPost()
    {
        if ($this->generalSettings->comment_system != 1) {
            exit();
        }
        $limit = inputPost('limit');
        $postId = inputPost('post_id');
        $model = new CommentModel();
        if (authCheck()) {
            $model->addComment();
        } else {
            if (reCAPTCHA('validate', $this->generalSettings) != 'invalid') {
                $model->addComment();
            }
        }
        $dataJson = [];
        if (!hasPermission('comments') || $this->generalSettings->comment_approval_system != 1) {
            $dataJson = [
                'type' => 'message',
                'message' => "<p class='comment-success-message'><i class='icon-check'></i>&nbsp;&nbsp;" . trans("msg_comment_sent_successfully") . "</p>"
            ];
        } else {
            $postModel = new PostModel();
            $data["post"] = $postModel->getPost($postId);
            $data['comments'] = $model->getComments($postId, $limit);
            $data['commentLimit'] = $limit;

            $dataJson = [
                'type' => 'comments',
                'message' => view('post/_comments', $data)
            ];
        }

        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($dataJson);
    }

    /**
     * Load More Comments
     */
    public function loadMoreCommentPost()
    {
        $postId = inputPost('post_id');
        $limit = inputPost('limit');
        $newLimit = $limit + COMMENTS_LIMIT;

        $model = new CommentModel();
        $postModel = new PostModel();
        $data = [
            'post' => $postModel->getPost($postId),
            'comments' => $model->getComments($postId, $newLimit),
            'commentLimit' => $newLimit
        ];
        $dataJson = [
            'result' => 1,
            'content' => view('post/_comments', $data)
        ];
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($dataJson);
    }

    //load subcomment box
    public function loadSubcommentBox()
    {
        $commentId = inputPost('comment_id');
        $limit = inputPost('limit');

        $model = new CommentModel();
        $data["parentComment"] = $model->getComment($commentId);
        $data["commentLimit"] = $limit;
        $dataJson = [
            'result' => 1,
            'content' => view('post/_add_subcomment', $data)
        ];

        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($dataJson);
    }

    //delete comment
    public function deleteCommentPost()
    {
        $commentId = inputPost('id');
        $postId = inputPost('post_id');
        $limit = inputPost('limit');

        $model = new CommentModel();
        $comment = $model->getComment($commentId);
        if (authCheck() && !empty($comment)) {
            if (hasPermission('comments') || user()->id == $comment->user_id) {
                $model->deleteComment($commentId);
            }
        }

        $postModel = new PostModel();
        $data = [
            'post' => $postModel->getPost($postId),
            'comments' => $model->getComments($postId, $limit),
            'commentLimit' => $limit
        ];
        $dataJson = [
            'result' => 1,
            'content' => view('post/_comments', $data)
        ];

        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($dataJson);
    }

    /**
     * Add to Newsletter
     */
    public function addToNewsletterPost()
    {
        $vld = inputPost('url');
        if (!empty($vld)) {
            exit();
        }
        $data = [
            'result' => 0,
            'message' => "",
            'is_success' => "",
        ];
        $email = cleanStr(inputPost('email'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['message'] = clrQuotes(trans("message_invalid_email"));
            $data['response'] = '<p class="text-danger m-t-5">' . trans("message_invalid_email") . '</p>';
        } else {
            if ($email) {
                $model = new NewsletterModel();
                if (empty($model->getSubscriber($email))) {
                    if ($model->addSubscriber($email)) {
                        $data['message'] = clrQuotes(trans("message_newsletter_success"));
                        $data['is_success'] = 1;
                    }
                } else {
                    $data['message'] = clrQuotes(trans("message_newsletter_error"));
                }
            }
        }
        $data['result'] = 1;
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Get Tag Suggestions
     */
    public function getTagSuggestions()
    {
        hasPermission("add_post");
        $q = inputPost('searchTerm');
        $tagModel = new TagModel();
        $tags = $tagModel->getTagSuggestions($q);

        $data = ['result' => 0];
        if (!empty($tags)) {
            $data = [
                'result' => 1,
                'tags' => $tags
            ];
        }
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setJSON($data);
    }

    /**
     * Close Cookies Warning
     */
    public function closeCookiesWarningPost()
    {
        helperSetSession('cookies_warning', '1');
        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash());
    }

}
