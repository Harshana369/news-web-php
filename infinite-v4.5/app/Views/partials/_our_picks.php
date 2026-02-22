<div class="widget-title">
    <h4 class="title"><?= trans("our_picks"); ?></h4>
</div>
<div class="widget-body">
    <div class="our-picks">
        <?php if (!empty($ourPicks)):
            foreach ($ourPicks as $item): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="position-relative">
                            <a href="<?= generatePostUrl($item); ?>" aria-label="<?= esc($item->title); ?>">
                                <div class="post-image-container">
                                    <?php if ($item->post_type == 'video'): ?>
                                        <div class="post-media-icon post-media-icon-md"><i class="icon-play-circle"></i></div>
                                    <?php endif; ?>
                                    <img data-src="<?= getPostImage($item, 'mid'); ?>" class="img-fluid lazyload" alt="<?= esc($item->title); ?>" width="412" height="228">
                                </div>
                            </a>
                        </div>
                        <h3 class="title">
                            <a href="<?= generatePostUrl($item); ?>"><?= esc(limitCharacter($item->title, POST_TITLE_DISPLAY_LIMIT, '...')); ?></a>
                        </h3>
                        <?= view("post/_post_meta", ['item' => $item, 'postMetaAuthor' => true]); ?>
                    </div>
                </div>
            <?php endforeach;
        endif; ?>
    </div>
</div>
