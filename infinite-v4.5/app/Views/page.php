<section id="main">
    <div class="container-xl">
        <div class="row gx-main">
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

            <?php if ($page->right_column_active == 1): ?>
                <div class="col-md-12 col-lg-8">
                    <div class="row">
                        <?php if ($page->title_active == 1): ?>
                            <div class="col-12">
                                <h1 class="page-title"><?= esc($page->title); ?></h1>
                            </div>
                        <?php endif; ?>
                        <div class="col-12 page-content">
                            <?= $page->page_content; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-4">
                    <?= view('partials/_sidebar'); ?>
                </div>

            <?php else: ?>
                <?php if ($page->title_active == 1): ?>
                    <div class="col-12">
                        <h1 class="page-title"><?= esc($page->title); ?></h1>
                    </div>
                <?php endif; ?>
                <div class="col-12 page-content">
                    <?= $page->page_content; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
