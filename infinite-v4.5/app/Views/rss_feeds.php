<div id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item active"><?= trans("rss_feeds"); ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <h1 class="page-title"><?= trans("rss_feeds"); ?></h1>
                <div class="page-content page-rss">
                    <div class="d-flex align-items-start gap-3 rss-item">
                        <div class="flex-shrink-0">
                            <div class="d-flex justify-content-center align-items-center icon">
                                <i class="icon-rss"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="feed-name">
                                <a href="<?= langBaseUrl('rss/latest-posts'); ?>" target="_blank"><?= trans("latest_posts"); ?></a>
                                <p class="fw-normal mb-0 hidden-sm">
                                    <a href="<?= langBaseUrl('rss/latest-posts'); ?>" target="_blank"><?= langBaseUrl('rss/latest-posts'); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php $categories = getCategories();
                    if (!empty($categories)):
                        foreach ($categories as $category):
                            if ($category->parent_id == 0 && $category->lang_id == $activeLang->id):
                                $url = langBaseUrl('rss/category/' . $category->slug . '/' . $category->id); ?>
                                <div class="d-flex rss-item">
                                    <div class="flex-shrink-0">
                                        <div class="d-flex justify-content-center align-items-center icon">
                                            <i class="icon-rss"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="feed-name">
                                            <a href="<?= esc($url); ?>" target="_blank"><?= esc($category->name); ?></a>
                                            <p class="fw-normal mb-0 hidden-sm">
                                                <a href="<?= esc($url); ?>" target="_blank"><?= esc($url); ?></a>
                                            </p>
                                        </div>
                                        <?php $subCategories = getSubcategoriesClient($categories, $category->id);
                                        if (!empty($subCategories)):
                                            foreach ($subCategories as $subCategory):
                                                $url = langBaseUrl('rss/category/' . $subCategory->slug . '/' . $subCategory->id); ?>
                                                <div class="d-flex align-items-start mt-2 mb-0 rss-item">
                                                    <div class="flex-shrink-0">
                                                        <div class="d-flex justify-content-center align-items-center icon">
                                                            <i class="icon-rss"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="feed-name">
                                                            <a href="<?= esc($url); ?>" target="_blank"><?= esc($subCategory->name); ?></a>
                                                            <p class="fw-normal mb-0 hidden-sm">
                                                                <a href="<?= esc($url); ?>" target="_blank"><?= esc($url); ?></a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;
                                        endif; ?>
                                    </div>
                                </div>
                            <?php endif;
                        endforeach;
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>