<section id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <?php if ($page->breadcrumb_active == 1): ?>
                            <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                            <li class="breadcrumb-item active"><?= esc($page->title); ?></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>

            <?php if ($page->title_active) : ?>
                <div class="col-12">
                    <h1 class="page-title"><?= esc($page->title); ?></h1>
                </div>
            <?php endif; ?>

            <div class="col-12">
                <div class="page-gallery">
                    <div id="masonry" class="gallery">
                        <div class="grid">
                            <?php if (!empty($galleryAlbums)):
                                foreach ($galleryAlbums as $item):
                                    $cover = getGalleryCoverImage($item->id); ?>
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-12 gallery-item">
                                        <div class="item-inner gallery-image-cover">
                                            <a href="<?= langBaseUrl('gallery/album/' . $item->id); ?>">
                                                <?php if (!empty($cover)):
                                                    $imgBaseUrl = getBaseURLByStorage($cover->storage); ?>
                                                    <img src="<?= $imgBaseUrl . esc($cover->path_small); ?>" alt="<?= esc($item->name); ?>" class="img-fluid" width="300" height="300"/>
                                                <?php else: ?>
                                                    <div class="ratio ratio-1x1">
                                                        <img alt="<?= esc($item->name); ?>" class="img-fluid img-gallery-empty" width="300" height="300"/>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="caption">
                                                    <span class="album-name">
                                                        <?= esc(limitCharacter($item->name, 100, '...')); ?>
                                                    </span>
                                                </div>
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
</section>