<?php
$numCatPosts = array();
if (!empty($baseCategories)) {
    foreach ($baseCategories as $category) {
        if ($category->parent_id > 0) {
            if (!isset($numCatPosts[$category->parent_id])) {
                $numCatPosts[$category->parent_id] = 0;
            }
            $numCatPosts[$category->parent_id] = $numCatPosts[$category->parent_id] + $category->number_of_posts;
        }
    }
} ?>
<div class="widget-title">
    <h4 class="title"><?= trans("categories"); ?></h4>
</div>
<div class="widget-body">
    <div class="d-flex flex-wrap sidebar-categories">
        <?php if (!empty($baseCategories)) :
            foreach ($baseCategories as $item):
                if ($item->parent_id == 0):
                    $number_of_posts = $item->number_of_posts;
                    if (isset($numCatPosts[$item->id])):
                        $number_of_posts += $numCatPosts[$item->id];
                    endif; ?>
                    <a href="<?= generateCategoryUrl($item); ?>" class="btn">
                        <?= esc($item->name); ?>&nbsp;&nbsp;<span class="badge text-bg-secondary"><?= $number_of_posts; ?></span>
                    </a>
                    <?php $subcategories = getSubcategoriesClient($baseCategories, $item->id); ?>
                    <?php if (!empty($subcategories)): ?>
                    <?php foreach ($subcategories as $subcategory) : ?>
                        <a href="<?= generateCategoryUrl($subcategory); ?>" class="btn">
                            <?= esc($subcategory->name); ?>&nbsp;&nbsp;<span class="badge text-bg-secondary"><?= $subcategory->number_of_posts; ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif;
                endif; ?>
            <?php endforeach;
        endif; ?>
    </div>
</div>
