<section id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item active"><?= esc($user->username); ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <div class="profile-page-top">
                    <?= view("profile/_profile_user_info"); ?>
                </div>
            </div>

            <div class="col-12">
                <div class="profile-page">
                    <div class="row gx-main">
                        <div class="col-md-12 col-lg-3">
                            <div class="widget-followers">
                                <div class="widget-head">
                                    <h3 class="title"><?= trans("following"); ?>&nbsp;(<?= count($following); ?>)</h3>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-content custom-scrollbar">
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php if (!empty($following)):
                                                foreach ($following as $item):?>
                                                    <div class="img-follower">
                                                        <a href="<?= generateProfileUrl($item->slug); ?>" class="d-block">
                                                            <img data-src="<?= getUserAvatar($item->avatar); ?>" alt="<?= esc($item->username); ?>" class="img-fluid lazyload" width="40" height="40">
                                                        </a>
                                                    </div>
                                                <?php endforeach;
                                            endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-followers">
                                <div class="widget-head">
                                    <h3 class="title"><?= trans("followers"); ?>&nbsp;(<?= count($followers); ?>)</h3>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-content custom-scrollbar">
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php if (!empty($followers)):
                                                foreach ($followers as $item):?>
                                                    <div class="img-follower">
                                                        <a href="<?= generateProfileUrl($item->slug); ?>" class="d-block">
                                                            <img data-src="<?= getUserAvatar($item->avatar); ?>" alt="<?= esc($item->username); ?>" class="img-fluid lazyload" width="40" height="40">
                                                        </a>
                                                    </div>
                                                <?php endforeach;
                                            endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-9">
                            <div class="row">
                                <?php $i = 0;
                                foreach ($posts as $item):
                                    echo view('post/_post_item', ['item' => $item, 'postItemProfile' => true]);
                                    if ($i == 1) {
                                        echo view('partials/_ad_spaces', ['adSpace' => 'posts_top', 'class' => 'm-b-30']);
                                    }
                                    $i++;
                                endforeach; ?>
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
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>