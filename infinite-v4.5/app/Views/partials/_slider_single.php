<div class="single-slider-container">
    <div id="singleSlider" class="single-slider">
        <?php if (!empty($sliderPosts)):
            foreach ($sliderPosts as $item) : ?>
                <div class="slider-item">
                    <a href="<?= generatePostUrl($item); ?>" aria-label="<?= esc($item->title); ?>">
                        <div class="post-image-container post-image-slider">
                            <?php if ($item->post_type == 'video'): ?>
                                <div class="post-media-icon post-media-icon-lg"><i class="icon-play-circle"></i></div>
                            <?php endif; ?>
                            <img data-lazy="<?= getPostImage($item, 'big'); ?>" class="img-fluid" alt="<?= esc($item->title); ?>" width="640" height="430">
                        </div>
                    </a>
                    <div class="caption redirect-onclik" data-url="<?= generatePostUrl($item); ?>">
                        <div class="d-flex position-relative justify-content-start">
                            <?= view("post/_post_category_label", ['postCategoryId' => $item->category_id]); ?>
                        </div>
                        <h2 class="title">
                            <a href="<?= generatePostUrl($item); ?>"><?= esc(limitCharacter($item->title, POST_TITLE_DISPLAY_LIMIT_LONG, '...')); ?></a>
                        </h2>
                        <?= view("post/_post_meta", ['item' => $item, 'postMetaAuthor' => true]); ?>
                    </div>
                </div>
            <?php endforeach;
        endif; ?>
    </div>
</div>