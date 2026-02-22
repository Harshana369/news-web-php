<h1 class="title-index"><?= esc($homeTitle); ?></h1>
<?php if ($generalSettings->layout == "layout_1" || $generalSettings->layout == "layout_2" || $generalSettings->layout == "layout_3"):
    if (!empty($sliderPosts)):?>
        <section id="slider" style="margin-top: 0px;">
            <div class="container-fluid">
                <div class="row">
                    <?php if ($generalSettings->slider_active == 1):
                        echo view('partials/_slider', $sliderPosts);
                    endif; ?>
                </div>
            </div>
        </section>
    <?php endif;
endif; ?>
<section id="main" class="margin-top-30">
    <div class="container-xl">
        <div class="row gx-main">
            <div class="col-md-12 col-lg-8">
                <?php if ($generalSettings->layout == "layout_4" || $generalSettings->layout == "layout_5" || $generalSettings->layout == "layout_6"):
                    if ($generalSettings->slider_active == 1):
                        echo view('partials/_slider_single', $sliderPosts);
                    endif;
                endif; ?>

                <div class="row">
                    <?php $i = 0;
                    foreach ($posts as $item):
                        echo view('post/_post_item', ['item' => $item]);
                        if ($i == 1) {
                            echo view('partials/_ad_spaces', ['adSpace' => 'index_top', 'class' => 'm-b-30']);
                        }
                        $i++;
                    endforeach; ?>
                </div>

                <div class="row">
                    <div class="col-12">
                        <?= view("partials/_ad_spaces", ['adSpace' => 'index_bottom', 'class' => 'm-b-30']); ?>
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