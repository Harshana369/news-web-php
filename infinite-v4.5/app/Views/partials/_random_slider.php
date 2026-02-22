<div class="widget-title">
    <h4 class="title"><?= trans("random_posts"); ?></h4>
</div>
<div class="widget-body">
    <?php $randomPosts = getRandomPosts($activeLang->id);
    if (!empty($randomPosts)):?>
        <div class="single-slider-container random-slider-container">
            <div id="randomSlider" class="single-slider random-slider">
                <?php foreach ($randomPosts as $item) : ?>
                    <div class="slider-item position-relative">
                        <a href="<?= generatePostUrl($item); ?>" aria-label="<?= esc($item->title); ?>">
                            <div class="post-image-container post-image-slider">
                                <?php if ($item->post_type == 'video'): ?>
                                    <div class="post-media-icon post-media-icon-lg"><i class="icon-play-circle"></i></div>
                                <?php endif; ?>
                                <img data-lazy="<?= getPostImage($item, 'mid'); ?>" class="img-fluid" alt="<?= esc($item->title); ?>" width="412" height="278">
                            </div>
                        </a>
                        <div class="caption redirect-onclik" data-url="<?= generatePostUrl($item); ?>">
                            <div class="d-flex position-relative justify-content-start">
                                <?= view("post/_post_category_label", ['postCategoryId' => $item->category_id]); ?>
                            </div>
                            <h3 class="title">
                                <a href="<?= generatePostUrl($item); ?>"><?= esc(limitCharacter($item->title, POST_TITLE_DISPLAY_LIMIT, '...')); ?></a>
                            </h3>
                            <?= view("post/_post_meta", ['item' => $item, 'postMetaAuthor' => true]); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>