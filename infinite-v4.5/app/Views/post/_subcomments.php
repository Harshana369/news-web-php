<?php $subComments = getSubComments($parentComment->id);
if (!empty($subComments)):
    foreach ($subComments as $subComment): ?>
        <div class="d-flex w-100 mt-4">
            <div class="flex-shrink-0">
                <?php if (!empty($subComment->user_slug)): ?>
                    <a href="<?= generateProfileUrl($subComment->user_slug); ?>">
                        <img src="<?= getUserAvatar($subComment->user_avatar); ?>" alt="<?= esc($subComment->name); ?>" width="50" height="50" class="img-fluid rounded-circle img-user">
                    </a>
                <?php else: ?>
                    <img src="<?= getUserAvatar(''); ?>" alt="<?= esc($subComment->name); ?>" width="50" height="50" class="img-fluid rounded-circle img-user">
                <?php endif; ?>
            </div>
            <div class="flex-grow-1 ms-3">
                <p class="mb-1">
                    <?= esc($subComment->comment); ?>
                </p>
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <?php if (!empty($subComment->user_slug)): ?>
                        <a href="<?= generateProfileUrl($subComment->user_slug); ?>" class="item-comment-meta fw-semibold">
                            <span class="username"><?= esc($subComment->user_username); ?></span>
                        </a>
                    <?php else: ?>
                        <span class="item-comment-meta"><?= esc($subComment->name); ?></span>
                    <?php endif; ?>
                    <span class="item-comment-meta"><?= timeAgo($subComment->created_at); ?></span>

                    <?php if (authCheck()):
                        if ($subComment->user_id == user()->id || hasPermission('comments')): ?>
                            <button type="button" class="button-link item-comment-meta fw-semibold" onclick="deleteComment('<?= $subComment->id; ?>','<?= $post->id; ?>','<?= clrQuotes(trans("confirm_comment")); ?>');"><?= trans("delete"); ?></button>
                        <?php endif;
                    endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach;
endif; ?>