<div class="d-flex align-items-center flex-wrap post-meta">
    <?php if (!empty($postMetaAuthor)): ?>
        <a href="<?= langBaseUrl('profile/' . esc($item->user_slug)); ?>" class="fw-semibold"><?= esc($item->username); ?></a>
    <?php endif; ?>
    <span><i class="icon-clock"></i>&nbsp;<?= dateFormatDefault($item->created_at); ?></span>
    <?php if ($generalSettings->comment_system == 1) : ?>
        <span><i class="icon-comment"></i>&nbsp;<?= numberFormatShort($item->comment_count); ?></span>
    <?php endif; ?>
    <?php if ($generalSettings->show_pageviews == 1) : ?>
        <span><i class="icon-eye"></i>&nbsp;<?= numberFormatShort($item->pageviews); ?></span>
    <?php endif; ?>
</div>