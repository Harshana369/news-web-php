<div class="row row-sub-comment-form">
    <div class="col-12">
        <?php if (authCheck()): ?>
            <div class="sub-comment-form">
                <form id="form_add_subcomment_<?= $parentComment->post_id; ?>">
                    <div class="mb-3 mt-3">
                        <textarea name="comment" class="form-control form-input form-textarea" maxlength="4999" placeholder="<?= trans("leave_your_comment"); ?>"></textarea>
                    </div>
                    <input type="hidden" name="parent_id" value="<?= $parentComment->id; ?>">
                    <input type="hidden" name="post_id" value="<?= $parentComment->post_id; ?>">
                    <input type="hidden" name="limit" value="<?= COMMENTS_LIMIT; ?>">
                    <button type="button" class="btn btn-default btn-subcomment" data-comment-id="<?= $parentComment->post_id; ?>" aria-label="add comment"><?= trans("post_comment"); ?></button>
                </form>
                <div id="message-subcomment-result-<?= $parentComment->post_id; ?>"></div>
            </div>
        <?php else: ?>
            <div class="sub-comment-form mt-3">
                <form id="form_add_subcomment_<?= $parentComment->post_id; ?>">
                    <div class="row mb-3">
                        <div class="col-12 col-md-6 col-lg-6">
                            <label class="form-label"><?= trans("name"); ?></label>
                            <input type="text" name="name" class="form-control form-input form-comment-name" maxlength="40" placeholder="<?= trans("name"); ?>">
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <label class="form-label"><?= trans("email"); ?></label>
                            <input type="email" name="email" class="form-control form-input form-comment-email" maxlength="100" placeholder="<?= trans("email"); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= trans("comment"); ?></label>
                        <textarea name="comment" class="form-control form-input form-textarea form-comment-text" maxlength="4999" placeholder="<?= trans("leave_your_comment"); ?>"></textarea>
                    </div>
                    <div class="mb-3">
                        <?php reCaptcha('generate', $generalSettings); ?>
                    </div>
                    <input type="hidden" name="limit" value="<?= COMMENTS_LIMIT; ?>">
                    <input type="hidden" name="parent_id" value="<?= $parentComment->id; ?>">
                    <input type="hidden" name="post_id" value="<?= $parentComment->post_id; ?>">
                    <button type="button" class="btn btn-default btn-subcomment mb-3" data-comment-id="<?= $parentComment->post_id; ?>" aria-label="add comment"><?= trans("post_comment"); ?></button>
                </form>
                <div id="message-subcomment-result-<?= $parentComment->post_id; ?>"></div>
            </div>
        <?php endif; ?>
    </div>
</div>