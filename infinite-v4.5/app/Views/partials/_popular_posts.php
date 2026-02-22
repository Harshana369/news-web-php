<div class="widget-title">
    <h4 class="title"><?= trans("popular_posts"); ?></h4>
</div>
<div class="widget-body">
    <?php $popularPosts = getPopularPosts($activeLang->id);
    if (!empty($popularPosts)):
        foreach ($popularPosts as $item):
            echo view('post/_post_item_small', ['postItem' => $item]);
        endforeach;
    endif; ?>
</div>