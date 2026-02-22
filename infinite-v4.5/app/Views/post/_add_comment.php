<div class="row mt-4">
    <div class="col-12">
        <?php if (authCheck()): ?>
            <form id="form_add_comment_registered">
                <input type="hidden" name="parent_id" value="0">
                <input type="hidden" name="post_id" value="<?= $post->id; ?>">
                <div class="mb-3">
                    <textarea name="comment" class="form-control form-input form-textarea" placeholder="<?= trans("leave_your_comment"); ?>" maxlength="4999"></textarea>
                </div>
                <button type="submit" class="btn btn-default" aria-label="post comment"><?= trans("post_comment"); ?></button>
                <div id="message-comment-result" class="message-comment-result"></div>
            </form>
        <?php else: ?>
            <form id="form_add_comment">
                <input type="hidden" name="parent_id" value="0">
                <input type="hidden" name="post_id" value="<?= $post->id; ?>">
                <div class="row mb-3">
                    <div class="col-12 col-md-6 col-lg-6">
                        <label class="form-label"><?= trans("name"); ?></label>
                        <input type="text" name="name" class="form-control form-input" maxlength="40" placeholder="<?= trans("name"); ?>">
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <label class="form-label"><?= trans("email"); ?></label>
                        <input type="email" name="email" class="form-control form-input" maxlength="100" placeholder="<?= trans("email"); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= trans("comment"); ?></label>
                    <textarea name="comment" class="form-control form-input form-textarea" maxlength="4999" placeholder="<?= trans("leave_your_comment"); ?>"></textarea>
                </div>
                <div class="mb-3">
                    <?php reCaptcha('generate', $generalSettings); ?>
                </div>
                <button type="submit" class="btn btn-default" aria-label="post comment"><?= trans("post_comment"); ?></button>
                <div id="message-comment-result" class="message-comment-result"></div>
            </form>
        <?php endif; ?>
    </div>
</div>