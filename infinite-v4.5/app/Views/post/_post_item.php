<?php if (isset($postItemProfile)) {
    $postListStyle = 'boxed';
}
if ($postListStyle == 'horizontal'): ?>
    <div class="post-item-horizontal">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="position-relative">
                    <?= view("post/_post_category_label", ['postCategoryId' => $item->category_id, 'labelClass' => '']); ?>
                    <a href="<?= generatePostUrl($item); ?>" aria-label="<?= esc($item->title); ?>">
                        <div class="post-image-container">
                            <?php if ($item->post_type == 'video'): ?>
                                <div class="post-media-icon post-media-icon-md"><i class="icon-play-circle"></i></div>
                            <?php endif; ?>
                            <img data-src="<?= getPostImage($item, 'mid'); ?>" class="img-fluid lazyload" alt="<?= esc($item->title); ?>" width="415" height="260">
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="position-relative">
                    <h3 class="title">
                        <a href="<?= generatePostUrl($item); ?>"><?= esc(limitCharacter($item->title, POST_TITLE_DISPLAY_LIMIT, '...')); ?></a>
                    </h3>
                    <?= view("post/_post_meta", ['item' => $item, 'postMetaAuthor' => true]); ?>
                    <p class="summary"><?= esc(limitCharacter($item->summary, POST_SUMMARY_DISPLAY_LIMIT, '...')); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php elseif ($postListStyle == 'boxed'): ?>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="post-item-boxed">
            <div class="position-relative">
                <?= view("post/_post_category_label", ['postCategoryId' => $item->category_id, 'labelClass' => '']); ?>
                <a href="<?= generatePostUrl($item); ?>" aria-label="<?= esc($item->title); ?>">
                    <div class="post-image-container">
                        <?php if ($item->post_type == 'video'): ?>
                            <div class="post-media-icon post-media-icon-md"><i class="icon-play-circle"></i></div>
                        <?php endif; ?>
                        <img data-src="<?= getPostImage($item, 'mid'); ?>" class="img-fluid lazyload" alt="<?= esc($item->title); ?>" width="415" height="260">
                    </div>
                </a>
            </div>
            <h3 class="title">
                <a href="<?= generatePostUrl($item); ?>" class="d-block"><?= esc(limitCharacter($item->title, POST_TITLE_DISPLAY_LIMIT, '...')); ?></a>
            </h3>
            <?= view("post/_post_meta", ['item' => $item, 'postMetaAuthor' => true]); ?>
            <p class="summary">
                <?= esc(limitCharacter($item->summary, POST_SUMMARY_DISPLAY_LIMIT, '...')); ?>
            </p>
        </div>
    </div>
<?php else: ?>
    <div class="col-sm-12">
        <div class="post-item">
            <div class="position-relative mb-3">
                <a href="<?= generatePostUrl($item); ?>" aria-label="<?= esc($item->title); ?>">
                    <div class="post-image-container">
                        <?php if ($item->post_type == 'video'): ?>
                            <div class="post-media-icon post-media-icon-md"><i class="icon-play-circle"></i></div>
                        <?php endif; ?>
                        <img data-src="<?= getPostImage($item, 'big'); ?>" class="img-fluid lazyload" alt="<?= esc($item->title); ?>" width="854" height="502">
                    </div>
                </a>
            </div>
            <div class="post-footer">
                <div class="mb-3">
                    <?= view("post/_post_category_label", ['postCategoryId' => $item->category_id, 'labelClass' => 'position-relative']); ?>
                </div>
                <h3 class="title mb-3">
                    <a href="<?= generatePostUrl($item); ?>"><?= esc($item->title); ?></a>
                </h3>
                <?= view("post/_post_meta", ['item' => $item, 'postMetaAuthor' => true]); ?>
                <p class="summary mt-3">
                    <?= esc($item->summary); ?>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>