<div id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <?php if ($page->breadcrumb_active == 1): ?>
                            <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?= langBaseUrl('gallery'); ?>"><?= esc($page->title); ?></a></li>
                            <li class="breadcrumb-item active"><?= esc($album->name); ?></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>

            <?php if ($page->title_active) : ?>
                <div class="col-12">
                    <h1 class="page-title"><?= esc($page->title); ?></h1>
                </div>
            <?php endif; ?>

            <div class="col-12 text-center">
                <h2 class="gallery-category-title"><?= esc($album->name); ?></h2>
            </div>

            <div class="col-12">
                <div class="page-gallery">
                    <?php if (!empty($galleryCategories)): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="filters text-center">
                                    <label data-filter="" class="btn btn-primary active">
                                        <input type="radio" name="options"> <?= trans("all"); ?>
                                    </label>
                                    <?php foreach ($galleryCategories as $category): ?>
                                        <label data-filter="category_<?= $category->id; ?>" class="btn btn-primary">
                                            <input type="radio" name="options"> <?= esc($category->name); ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div id="masonry" class="gallery">
                        <?php if (!empty($galleryImages)):
                            foreach ($galleryImages as $item):
                                $imgBaseUrl = getBaseURLByStorage($item->storage); ?>
                                <div data-filter="category_<?= $item->category_id; ?>" class="col-lg-3 col-md-4 col-sm-6 col-12 gallery-item">
                                    <div class="item-inner">
                                        <a href="<?= $imgBaseUrl . $item->path_big; ?>" class="glightbox" data-glightbox="title: <?= esc($item->title); ?>;">
                                            <img src="<?= $imgBaseUrl . esc($item->path_small); ?>" alt="<?= esc($item->title); ?>" class="img-responsive" width="300" height="300"/>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>