<section id="main">
    <div class="container-xl">
        <div class="row gx-main">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <?php if (!empty($categoryArray['subcategory']) && !empty($categoryArray['parentCategory'])): ?>
                            <li class="breadcrumb-item">
                                <a href="<?= generateCategoryUrl($categoryArray['parentCategory']); ?>"><?= esc($categoryArray['parentCategory']->name); ?></a>
                            </li>
                            <li class="breadcrumb-item active">
                                <?= esc($categoryArray['subcategory']->name); ?>
                            </li>
                        <?php else:
                            if (!empty($categoryArray['parentCategory'])):?>
                                <li class="breadcrumb-item active">
                                    <?= esc($categoryArray['parentCategory']->name); ?>
                                </li>
                            <?php endif;
                        endif; ?>
                    </ol>
                </nav>
            </div>
            <div class="col-md-12 col-lg-8">
                <h1 class="page-title"> <?= trans("category"); ?>: <?= esc($category->name); ?></h1>
                <div class="row">
                    <?php $i = 0;
                    if (!empty($posts)):
                        foreach ($posts as $item):
                            echo view('post/_post_item', ['item' => $item]);
                            if ($i == 1) {
                                echo view('partials/_ad_spaces', ['adSpace' => 'posts_top', 'class' => 'm-b-30']);
                            }
                            $i++;
                        endforeach;
                    endif; ?>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?= view('partials/_ad_spaces', ['adSpace' => 'posts_bottom', 'class' => 'm-b-30']); ?>
                    </div>
                    <div class="col-12">
                        <?= $pager->links; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-4">
                <?= view('partials/_sidebar'); ?>
            </div>

        </div>
    </div>
</section>