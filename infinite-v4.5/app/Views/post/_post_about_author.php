<?php if (!empty($postUser)): ?>
    <div class="row">
        <div class="col-12">
            <div class="d-flex about-author">
                <div class="flex-shrink-0">
                    <a href="<?= langBaseUrl('profile/' . esc($postUser->slug)); ?>">
                        <img src="<?= getUserAvatar($postUser->avatar); ?>" alt="<?= esc($postUser->slug); ?>" class="img-fluid rounded-1" width="110" height="110">
                    </a>
                </div>
                <div class="flex-grow-1 ms-3">
                    <p class="fw-bold mb-2">
                        <a href="<?= langBaseUrl('profile/' . esc($postUser->slug)); ?>"> <?= esc($postUser->username); ?> </a>
                    </p>
                    <p class="mb-2">
                        <?= esc($postUser->about_me); ?>
                    </p>
                    <ul class="d-flex align-items-center flex-wrap gap-2 author-social">
                        <?php $socialArray = getSocialLinksArray($postUser);
                        foreach ($socialArray as $item):
                            if (!empty($item['value'])):?>
                                <li><a class="<?= $item['name']; ?>" href="<?= esc($item['value']); ?>" target="_blank"><i class="icon-<?= $item['name']; ?>"></i></a></li>
                            <?php endif;
                        endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>