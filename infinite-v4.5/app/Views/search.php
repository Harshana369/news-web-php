<section id="main">
    <div class="container-xl">
        <div class="row gx-main">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item active"><?= trans("search"); ?></li>
                    </ol>
                </nav>
            </div>

            <div class="col-md-12 col-lg-8">
                <h1 class="page-title"> <?= trans("search"); ?>&nbsp;:&nbsp;<?= esc($q); ?></h1>
                <div class="row" id="searchPostsLoadMoreContent">
                    <?php $i = 0;
                    foreach ($posts as $item):
                        if ($i < $generalSettings->pagination_per_page) {
                            echo view('post/_post_item', ['item' => $item]);
                        }
                        if ($i == 1) {
                            echo view('partials/_ad_spaces', ['adSpace' => 'posts_top', 'class' => 'm-b-30']);
                        }
                        $i++;
                    endforeach;
                    if (empty($posts)): ?>
                        <div class="col-12">
                            <p class="text-center text-muted"><?= trans("no_results_found"); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <?= view('partials/_ad_spaces', ['adSpace' => 'posts_bottom', 'class' => 'm-b-30']); ?>
                </div>
                <?php if (countItems($posts) > $postsPerPage): ?>
                    <div class="row">
                        <div id="load_posts_spinner" class="col-sm-12 load-more-spinner">
                            <div class="row">
                                <div class="spinner">
                                    <div class="bounce1"></div>
                                    <div class="bounce2"></div>
                                    <div class="bounce3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-4">
                            <button type="button" class="btn-load-more" onclick="loadMoreSearchPosts(<?= $activeLang->id; ?>);" aria-label="load more posts">
                                <?= trans("load_more_posts"); ?>&nbsp;&nbsp;
                                <svg width="14" height="14" viewBox="0 0 1792 1792" fill="currentColor" class="m-l-5" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1664 256v448q0 26-19 45t-45 19h-448q-42 0-59-40-17-39 14-69l138-138q-148-137-349-137-104 0-198.5 40.5t-163.5 109.5-109.5 163.5-40.5 198.5 40.5 198.5 109.5 163.5 163.5 109.5 198.5 40.5q119 0 225-52t179-147q7-10 23-12 15 0 25 9l137 138q9 8 9.5 20.5t-7.5 22.5q-109 132-264 204.5t-327 72.5q-156 0-298-61t-245-164-164-245-61-298 61-298 164-245 245-164 298-61q147 0 284.5 55.5t244.5 156.5l130-129q29-31 70-14 39 17 39 59z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-12 col-lg-4">
                <?= view('partials/_sidebar'); ?>
            </div>
        </div>
    </div>
</section>