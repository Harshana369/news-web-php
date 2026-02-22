<div class="widget-title">
    <h4 class="title"><?= trans("popular_tags"); ?></h4>
</div>
<div class="widget-body">
    <ul class="d-flex flex-wrap popular-tags">
        <?php $tags = getPopularTags($activeLang->id);
        if (!empty($tags)):
            foreach ($tags as $item): ?>
                <li><a href="<?= langBaseUrl('tag/' . esc($item->tag_slug)); ?>"><?= esc($item->tag); ?></a></li>
            <?php endforeach;
        endif; ?>
    </ul>
</div>