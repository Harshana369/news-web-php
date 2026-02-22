<?php if (!empty($postItem)): ?>
    <div class="d-flex post-item-small">
        <div class="col-left flex-shrink-0">
            <a href="<?= generatePostUrl($postItem); ?>" aria-label="<?= esc($postItem->title); ?>">
                <div class="post-image-container">
                    <?php if ($postItem->post_type == 'video'): ?>
                        <div class="post-media-icon post-media-icon-sm"><i class="icon-play-circle"></i></div>
                    <?php endif; ?>
                    <img data-src="<?= getPostImage($postItem, 'small'); ?>" class="img-fluid lazyload" alt="<?= esc($postItem->title); ?>" width="130" height="90">
                </div>
            </a>
        </div>
        <div class="flex-grow-1">
            <h3 class="title">
                <a href="<?= generatePostUrl($postItem); ?>" class="d-block"><?= esc(limitCharacter($postItem->title, POST_TITLE_DISPLAY_LIMIT, '...')); ?></a>
            </h3>
            <?= view("post/_post_meta", ['item' => $postItem, 'postMetaAuthor' => true]); ?>
        </div>
    </div>
<?php endif; ?>