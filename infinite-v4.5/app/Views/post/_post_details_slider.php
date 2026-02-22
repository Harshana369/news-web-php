<div class="single-slider-container post-slider-container">
    <div id="postDetailSlider" class="single-slider">
        <?php if (!empty($post->image_url)): ?>
            <div class="slider-item">
                <img data-lazy="<?= esc($post->image_url); ?>" class="img-fluid" alt="<?= esc($post->title); ?>" width="860" height="570">
            </div>
        <?php else:
            if (!empty($post->image_id)): ?>
                <div class="slider-item">
                    <img data-lazy="<?= getPostImage($post, 'big'); ?>" class="img-fluid" alt="<?= esc($post->title); ?>" width="860" height="570">
                </div>
            <?php endif;
        endif; ?>
        <?php if (!empty($additionalImages)):
            foreach ($additionalImages as $image):
                $imgBaseUrl = getBaseURLByStorage($image->storage); ?>
                <div class="slider-item">
                    <img data-lazy="<?= esc($imgBaseUrl . $image->image_path); ?>" class="img-fluid" alt="<?= esc($post->title); ?>" width="860" height="570">
                </div>
            <?php endforeach;
        endif; ?>
    </div>
</div>