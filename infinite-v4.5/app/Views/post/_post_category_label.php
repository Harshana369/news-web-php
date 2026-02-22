<?php if (!empty($postCategoryId)):
    $category = getCategoryClient($postCategoryId, $baseCategories);
    if (!empty($category)): ?>
        <a href="<?= generateCategoryURL($category); ?>">
            <span class="label-post-category<?= !empty($labelClass) ? ' ' . $labelClass : ''; ?>"><?= esc($category->name); ?></span>
        </a>
    <?php endif;
endif; ?>