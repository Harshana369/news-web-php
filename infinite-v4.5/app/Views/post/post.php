<section id="main">
    <div class="container-xl">
        <div class="row gx-main">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <?php if (!empty($categoryArray['parentCategory'])): ?>
                            <li class="breadcrumb-item"><a href="<?= generateCategoryUrl($categoryArray['parentCategory']); ?>"><?= esc($categoryArray['parentCategory']->name); ?></a></li>
                        <?php endif;
                        if (!empty($categoryArray['subcategory'])): ?>
                            <li class="breadcrumb-item"><a href="<?= generateCategoryUrl($categoryArray['subcategory']); ?>"><?= esc($categoryArray['subcategory']->name); ?></a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active"><?= esc($post->title); ?></li>
                    </ol>
                </nav>
            </div>

            <div class="col-md-12 col-lg-8">
                <div class="post-content">
                    <h1 class="post-title"><?= esc($post->title); ?></h1>
                    <?php if (!empty($post->summary)): ?>
                        <h2 class="post-summary"><?= $post->summary; ?></h2>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between flex-wrap mb-3">
                        <div class="d-flex gap-4 flex-wrap align-items-center post-content-meta">
                            <?php if (!empty($postUser)): ?>
                                <a href="<?= langBaseUrl('profile/' . esc($postUser->slug)); ?>" class="d-flex">
                                    <div class="d-flex align-items-center post-meta-author">
                                        <div class="flex-shrink-0">
                                            <img src="<?= getUserAvatar($postUser->avatar); ?>" alt="<?= esc($postUser->username); ?>" class="img-fluid rounded-circle" width="32" height="32">
                                        </div>
                                        <div class="flex-grow-1 ms-2 fw-semibold"><?= esc($postUser->username); ?></div>
                                    </div>
                                </a>
                            <?php endif; ?>

                            <div class="d-flex align-items-center flex-wrap">
                                <?= view("post/_post_meta", ['item' => $post, 'postMetaAuthor' => false]); ?>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <?php if (authCheck()) : ?>
                                <form action="<?= base_url('add-remove-reading-list-post'); ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="post_id" value="<?= $post->id; ?>">
                                    <?php if ($is_reading_list == false) : ?>
                                        <button type="submit" class="add-to-reading-list" aria-label="add to reading list"><i class="icon-plus-circle"></i>&nbsp;<?= trans("add_reading_list"); ?></button>
                                    <?php else: ?>
                                        <button type="submit" class="delete-from-reading-list" aria-label="remove from reading list"><i class="icon-negative-circle"></i>&nbsp;<?= trans("delete_reading_list"); ?></button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <a href="<?= langBaseUrl('login'); ?>" class="add-to-reading-list pull-right">
                                    <i class="icon-plus-circle"></i>&nbsp;<?= trans("add_reading_list"); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($post->video_embed_code)): ?>
                        <div class="d-flex mb-3">
                            <div class="ratio ratio-16x9">
                                <iframe src="<?= $post->video_embed_code; ?>" allowfullscreen></iframe>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="d-flex mb-3">
                            <div class="post-image">
                                <?php if (!empty($additionalImages)):
                                    echo view("post/_post_details_slider");
                                else:
                                    if (!empty($post->image_url)):?>
                                        <img src="<?= esc($post->image_url); ?>" class="img-fluid w-100" alt="<?= esc($post->title); ?>" width="860" height="570"/>
                                    <?php else:
                                        if (!empty($post->image_id)): ?>
                                            <img src="<?= getPostImage($post, 'big'); ?>" class="img-fluid w-100" alt="<?= esc($post->title); ?>" width="860" height="570"/>
                                        <?php endif;
                                    endif;
                                endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <?= view("partials/_ad_spaces", ["adSpace" => "post_top", "class" => "m-t-15"]); ?>
                    </div>

                    <div class="post-text">
                        <?= view('post/_post_content'); ?>

                        <?php if (!empty($post->optional_url) || !empty($post->post_url)):
                            $postUrl = $post->post_url;
                            $buttonText = $settings->optional_url_button_name;
                            if (!empty($post->optional_url)) {
                                $postUrl = $post->optional_url;
                            }
                            if (!empty($feed) && !empty($feed->read_more_button_text)) {
                                $buttonText = $feed->read_more_button_text;
                            } ?>
                            <div class="d-flex justify-content-end mt-3">
                                <a href="<?= esc($postUrl); ?>" class="btn btn-default text-decoration-none" target="_blank" rel="nofollow">
                                    <?= esc($buttonText); ?>&nbsp;&nbsp;&nbsp;<i class="icon-long-arrow-right"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php $files = getPostFiles($post->id);
                        if (!empty($files)):?>
                            <div class="post-files mt-3 mb-4">
                                <h3 class="sub-section-title"><?= trans("files"); ?></h3>
                                <?php foreach ($files as $file): ?>
                                    <form action="<?= base_url('download-file'); ?>" method="post">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?= $file->id; ?>">
                                        <div class="file">
                                            <button type="submit" aria-label="download"><i class="icon-file"></i>&nbsp;&nbsp;<?= $file->file_name; ?></button>
                                        </div>
                                    </form>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="post-tags mt-3 mb-5">
                        <?php if (!empty($postTags)): ?>
                            <h3 class="sub-section-title"><?= trans("tags"); ?></h3>
                            <ul class="d-flex align-items-center flex-wrap gap-2 tag-list">
                                <?php foreach ($postTags as $tag) : ?>
                                    <li><a href="<?= langBaseUrl('tag/' . esc($tag->tag_slug)); ?>"><?= esc($tag->tag); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <div class="post-share mb-4">
                        <h3 class="sub-section-title"><?= trans("share"); ?></h3>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <button type="button" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?= langBaseUrl(esc($post->slug)); ?>', 'Share This Post', 'width=640,height=450');return false" class="btn btn-share share facebook" aria-label="share facebook">
                                <i class="icon-facebook"></i>&nbsp;&nbsp;<span>Facebook</span>
                            </button>
                            <button type="button" onclick="window.open('https://twitter.com/intent/tweet?url=<?= langBaseUrl(esc($post->slug)); ?>&amp;text=<?= urlencode($post->title); ?>', 'Share This Post', 'width=640,height=450');return false" class="btn btn-share share twitter" aria-label="share x">
                                <i class="icon-twitter"></i>&nbsp;&nbsp;<span>Twitter</span>
                            </button>
                            <a href="https://api.whatsapp.com/send?text=<?= esc($post->title); ?> - <?= langBaseUrl(esc($post->slug)); ?>" target="_blank" class="btn btn-share share whatsapp">
                                <i class="icon-whatsapp"></i>&nbsp;&nbsp;<span>Whatsapp</span>
                            </a>
                            <button type="button" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?= langBaseUrl(esc($post->slug)); ?>', 'Share This Post', 'width=640,height=450');return false" class="btn btn-share share linkedin" aria-label="share linkedin">
                                <i class="icon-linkedin"></i>&nbsp;&nbsp;<span>Linkedin</span>
                            </button>
                            <button type="button" onclick="window.open('http://pinterest.com/pin/create/button/?url=<?= langBaseUrl(esc($post->slug)); ?>&amp;media=<?= getPostImage($post, 'mid') ?>', 'Share This Post', 'width=640,height=450');return false" class="btn btn-share share pinterest" aria-label="share pinterest">
                                <i class="icon-pinterest"></i>&nbsp;&nbsp;<span>Pinterest</span>
                            </button>
                        </div>
                    </div>

                    <?php if ($generalSettings->emoji_reactions == 1): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="emoji-reactions-container">
                                    <h3 class="sub-section-title"><?= trans("whats_your_reaction"); ?></h3>
                                    <div id="reactions_result" class="noselect">
                                        <?= view('partials/_emoji_reactions', ['reactions' => $reactions]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <?= view("partials/_ad_spaces", ["adSpace" => "post_bottom", "class" => ""]); ?>
                    </div>

                </div>

                <?= view('post/_post_about_author', ['postUser' => $postUser]); ?>

                <?php if (!empty($relatedPosts)): ?>
                    <div class="related-posts">
                        <div class="widget-title">
                            <h4 class="title"><?= trans("related_posts"); ?></h4>
                        </div>
                        <div class="widget-body">
                            <div class="row post-list">
                                <?php foreach ($relatedPosts as $item): ?>
                                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                        <div class="position-relative mb-3">
                                            <a href="<?= generatePostUrl($item); ?>">
                                                <div class="post-image-container">
                                                    <?php if ($item->post_type == 'video'): ?>
                                                        <div class="post-media-icon post-media-icon-md"><i class="icon-play-circle"></i></div>
                                                    <?php endif; ?>
                                                    <img data-src="<?= getPostImage($item, 'big'); ?>" class="img-fluid lazyload" alt="<?= esc($item->title); ?>" width="270" height="180">
                                                </div>
                                            </a>
                                        </div>
                                        <h3 class="title">
                                            <a href="<?= langBaseUrl(esc($item->slug)); ?>" class="d-block"><?= esc(limitCharacter($item->title, POST_TITLE_DISPLAY_LIMIT, '...')); ?></a>
                                        </h3>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="comments-section">
                            <?php if ($generalSettings->comment_system == 1 || !empty(trim($generalSettings->facebook_comment ?? ''))): ?>
                                <ul class="nav nav-tabs">
                                    <?php if ($generalSettings->comment_system == 1): ?>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link active" data-bs-target="#tabComments" data-bs-toggle="tab" role="tab" aria-label="tab comments"><?= trans("comments"); ?></button>
                                        </li>
                                    <?php endif;
                                    if ($generalSettings->comment_system == 1 && !empty(trim($generalSettings->facebook_comment ?? ''))): ?>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" data-bs-target="#tabFacebookComments" data-bs-toggle="tab" role="tab" aria-label="tab Facebook comments"><?= trans("facebook_comments"); ?></button>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                                <div class="tab-content">
                                    <?php if ($generalSettings->comment_system == 1): ?>
                                        <div class="tab-pane fade show active" id="tabComments" role="tabpanel">
                                            <?= view('post/_add_comment'); ?>
                                            <div id="comment-result">
                                                <?= view('post/_comments'); ?>
                                            </div>
                                        </div>
                                        <?php if ($generalSettings->comment_system == 1 && !empty(trim($generalSettings->facebook_comment ?? ''))): ?>
                                            <div class="tab-pane fade" id="tabFacebookComments" role="tabpanel">
                                                <div id="facebook_comments" class="tab-pane <?= ($generalSettings->comment_system != 1) ? 'active' : 'fade'; ?>">
                                                    <div class="fb-comments" data-href="<?= esc(current_url()); ?>" data-width="100%" data-numposts="5"
                                                         data-colorscheme="<?= $darkMode == 1 ? 'dark' : 'light'; ?>"></div>
                                                </div>
                                            </div>
                                        <?php endif;
                                    endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-12 col-lg-4">
                <?= view('partials/_sidebar'); ?>
            </div>

        </div>
    </div>
</section>

<?php if (!empty(trim($generalSettings->facebook_comment ?? ''))):
    echo $generalSettings->facebook_comment;
endif; ?>

<?php if (!empty($post->feed_id)): ?>
    <style>
        .post-text img {
            display: none !important;
        }

        .post-content .post-summary {
            display: none !important;
        }
    </style>
<?php endif; ?>
