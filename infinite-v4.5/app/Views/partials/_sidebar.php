<div class="<?= $generalSettings->sticky_sidebar == 1 ? 'sticky-lg-top' : ''; ?>">
    <div class="row">
        <?= view('partials/_ad_spaces', ['adSpace' => 'sidebar_1', 'class' => 'm-b-30']); ?>
        <div class="col-12 sidebar-widget widget-popular-posts">
            <?= view('partials/_popular_posts'); ?>
        </div>

        <?php $ourPicks = getOurPicks($activeLang->id);
        if (!empty($ourPicks) && countItems($ourPicks) > 0):?>
            <div class="col-12 sidebar-widget">
                <?= view('partials/_our_picks', ['ourPicks' => $ourPicks]); ?>
            </div>
        <?php endif; ?>

        <?php if ($generalSettings->sidebar_categories == 1): ?>
            <div class="col-12 sidebar-widget">
                <?= view('partials/_categories'); ?>
            </div>
        <?php endif; ?>
        <?= view('partials/_ad_spaces', ['adSpace' => 'sidebar_2', 'class' => 'm-b-30']); ?>

        <div class="col-12 sidebar-widget">
            <?= view('partials/_random_slider'); ?>
        </div>
        <div class="col-12 sidebar-widget">
            <?= view('partials/_tags'); ?>
        </div>
        <div class="col-12 sidebar-widget">
            <?= view('partials/_polls'); ?>
        </div>
    </div>
</div>