<input type="hidden" value="<?= $commentLimit; ?>" id="post_comment_limit">
<div class="row">
    <div class="col-sm-12">
        <div class="comments">
            <?php if ($post->comment_count > 0): ?>
                <div class="mb-4 mt-3">
                    <label class="fw-bold"><?= trans("comments"); ?> (<?= numberFormatShort($post->comment_count); ?>)</label>
                </div>
            <?php endif; ?>
            <div class="comment-list">
                <?php $i = 0;
                if (!empty($comments)):
                    foreach ($comments as $comment):
                        if ($i < $commentLimit):?>
                            <div class="d-flex m-b-30">
                                <div class="flex-shrink-0">
                                    <?php if (!empty($comment->user_slug)): ?>
                                        <a href="<?= generateProfileUrl($comment->user_slug); ?>">
                                            <img src="<?= getUserAvatar($comment->user_avatar); ?>" alt="<?= esc($comment->name); ?>" width="50" height="50" class="img-fluid rounded-circle img-user">
                                        </a>
                                    <?php else: ?>
                                        <img src="<?= getUserAvatar(''); ?>" alt="<?= esc($comment->name); ?>" width="50" height="50" class="img-fluid rounded-circle img-user">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">
                                        <?= esc($comment->comment); ?>
                                    </p>

                                    <div class="d-block w-100">
                                        <div class="d-flex align-items-center flex-wrap gap-3">
                                            <?php if (!empty($comment->user_slug)): ?>
                                                <a href="<?= generateProfileUrl($comment->user_slug); ?>" class="item-comment-meta fw-semibold">
                                                    <span class="username"><?= esc($comment->user_username); ?></span>
                                                </a>
                                            <?php else: ?>
                                                <span class="item-comment-meta"><?= esc($comment->name); ?></span>
                                            <?php endif; ?>
                                            <span class="item-comment-meta"><?= timeAgo($comment->created_at); ?></span>

                                            <button type="button" class="button-link item-comment-meta" onclick="showCommentBox('<?= $comment->id; ?>');" aria-label="reply-comment"><i class="icon-reply"></i> <?= trans('reply'); ?></button>

                                            <?php if (authCheck()):
                                                if ($comment->user_id == user()->id || hasPermission('comments')): ?>
                                                    <button type="button" class="button-link item-comment-meta fw-semibold" onclick="deleteComment('<?= $comment->id; ?>','<?= $post->id; ?>','<?= clrQuotes(trans("confirm_comment")); ?>');"><?= trans("delete"); ?></button>
                                                <?php endif;
                                            endif; ?>
                                        </div>
                                    </div>

                                    <div class="d-block w-100 sub-comments">
                                        <div id="sub_comment_form_<?= $comment->id; ?>" class="d-block w-100"></div>
                                        <?= view('post/_subcomments', ['parentComment' => $comment]); ?>
                                    </div>

                                </div>
                            </div>
                        <?php endif;
                        $i++;
                    endforeach;
                endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if (countItems($comments) > $commentLimit): ?>
    <div class="row">
        <div id="load_comment_spinner" class="col-12 load-more-spinner">
            <div class="row">
                <div class="spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mt-4">
            <button type="button" class="btn-load-more" onclick="loadMoreComment('<?= $post->id; ?>');" aria-label="load more comments">
                <?= trans("load_more_comments"); ?>&nbsp;&nbsp;
                <svg width="14" height="14" viewBox="0 0 1792 1792" fill="currentColor" class="m-l-5" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1664 256v448q0 26-19 45t-45 19h-448q-42 0-59-40-17-39 14-69l138-138q-148-137-349-137-104 0-198.5 40.5t-163.5 109.5-109.5 163.5-40.5 198.5 40.5 198.5 109.5 163.5 163.5 109.5 198.5 40.5q119 0 225-52t179-147q7-10 23-12 15 0 25 9l137 138q9 8 9.5 20.5t-7.5 22.5q-109 132-264 204.5t-327 72.5q-156 0-298-61t-245-164-164-245-61-298 61-298 164-245 245-164 298-61q147 0 284.5 55.5t244.5 156.5l130-129q29-31 70-14 39 17 39 59z"/>
                </svg>
            </button>
        </div>
    </div>
<?php endif; ?>

