<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= $title; ?></h3>
                </div>
                <?php if (hasPermission('add_post')): ?>
                    <div class="right">
                        <div class="dropdown dropdown-posts-add">
                            <button class="btn btn-success btn-add-new dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-plus"></i><?= trans('add_post'); ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="<?= adminUrl('add-post'); ?>"><?= trans('add_post'); ?></a></li>
                                <li><a href="<?= adminUrl('add-video'); ?>"><?= trans('add_video'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" role="grid">
                                <?= view('admin/post/_filter_posts'); ?>
                                <thead>
                                <tr role="row">
                                    <th width="20"><input type="checkbox" class="checkbox-table" id="checkAll"></th>
                                    <th width="20"><?= trans('id'); ?></th>
                                    <th><?= trans('post'); ?></th>
                                    <th><?= trans('author'); ?></th>
                                    <th><?= trans('post_type'); ?></th>
                                    <th><?= trans('language'); ?></th>
                                    <th><?= trans('category'); ?></th>
                                    <th><?= trans("pageviews"); ?></th>
                                    <?php if ($listType == "slider-posts"): ?>
                                        <th><?= trans('slider_order'); ?></th>
                                    <?php endif; ?>
                                    <th><?= trans('date'); ?></th>
                                    <th class="max-width-120"><?= trans('options'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($posts)):
                                    foreach ($posts as $item):
                                        $lang = getLanguageClient($item->lang_id); ?>
                                        <tr>
                                            <td><input type="checkbox" name="checkbox-table" class="checkbox-table" value="<?= $item->id; ?>"></td>
                                            <td><?= esc($item->id); ?></td>
                                            <td>
                                                <div class="post-item-table">
                                                    <?php if (isPostPublished($item)): ?>
                                                        <a href="<?= generatePostUrl($item, generateBaseUrlByShortForm($lang->short_form)); ?>" target="_blank">
                                                            <div class="post-image">
                                                                <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= getPostImage($item, 'small'); ?>" alt="" class="lazyload img-responsive"/>
                                                            </div>
                                                            <?= esc($item->title); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <div class="post-image">
                                                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= getPostImage($item, 'small'); ?>" alt="" class="lazyload img-responsive"/>
                                                        </div>
                                                        <?= esc($item->title); ?>
                                                    <?php endif; ?>
                                                    <p>
                                                        <?php if ($item->is_slider): ?>
                                                            <label class="label bg-olive label-table"><?= trans('slider'); ?></label>
                                                        <?php endif;
                                                        if ($item->is_picked): ?>
                                                            <label class="label bg-aqua label-table"><?= trans('our_picks'); ?></label>
                                                        <?php endif;
                                                        if ($item->need_auth): ?>
                                                            <label class="label label-warning label-table"><?= trans('only_registered'); ?></label>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="<?= generateProfileUrl($item->user_slug); ?>" target="_blank" class="table-link">
                                                    <strong><?= esc($item->username); ?></strong>
                                                </a>
                                            </td>
                                            <td><?= trans($item->post_type); ?></td>
                                            <td>
                                                <?php if (!empty($lang)):
                                                    echo esc($lang->name);
                                                endif; ?>
                                            </td>
                                            <td><?php $category = getCategoryClient($item->category_id, $categories);
                                                if (!empty($category)):?>
                                                    <label class="label label-table m-r-5 bg-primary">
                                                        <?= esc($category->name); ?>
                                                    </label>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= numberFormatShort($item->pageviews); ?></td>
                                            <?php if ($listType == "slider-posts"): ?>
                                                <td>
                                                    <input type="number" name="slider_order" class="form-control input-slider-order" value="<?= esc($item->slider_order); ?>" data-id="<?= esc($item->id); ?>" min="1" max="99999" style="width: 80px;">
                                                </td>
                                            <?php endif; ?>
                                            <td class="nowrap">
                                                <?= formatDate($item->created_at); ?>
                                                <?php if (!empty($item->updated_at)): ?>
                                                    <div class="text-muted m-t-5">
                                                        <small class="font-600"><?= trans("edited"); ?>:&nbsp;<?= timeAgo($item->updated_at); ?></small>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= base_url('Post/postOptionsPost'); ?>" method="post">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="id" value="<?= esc($item->id); ?>">
                                                    <div class="dropdown">
                                                        <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?= trans('select_option'); ?>
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu options-dropdown">
                                                            <?php if (isAdmin()):
                                                                if ($item->status != 1): ?>
                                                                    <li>
                                                                        <button type="submit" name="option" value="publish_draft" class="btn-list-button">
                                                                            <i class="fa fa-location-arrow option-icon"></i><?= trans('publish'); ?>
                                                                        </button>
                                                                    </li>
                                                                <?php else:
                                                                    if ($item->visibility != 1): ?>
                                                                        <li>
                                                                            <button type="submit" name="option" value="approve" class="btn-list-button">
                                                                                <i class="fa fa-check option-icon"></i><?= trans('approve'); ?>
                                                                            </button>
                                                                        </li>
                                                                    <?php endif;
                                                                    if ($item->is_slider == 1 && $item->status == 1): ?>
                                                                        <li>
                                                                            <button type="submit" name="option" value="add-remove-from-slider" class="btn-list-button">
                                                                                <i class="fa fa-times option-icon"></i><?= trans('remove_slider'); ?>
                                                                            </button>
                                                                        </li>
                                                                    <?php else: ?>
                                                                        <li>
                                                                            <button type="submit" name="option" value="add-remove-from-slider" class="btn-list-button">
                                                                                <i class="fa fa-plus option-icon"></i><?= trans('add_slider'); ?>
                                                                            </button>
                                                                        </li>
                                                                    <?php endif;
                                                                    if ($item->is_picked == 1 && $item->status == 1): ?>
                                                                        <li>
                                                                            <button type="submit" name="option" value="add-remove-from-picked" class="btn-list-button">
                                                                                <i class="fa fa-times option-icon"></i><?= trans('remove_picked'); ?>
                                                                            </button>
                                                                        </li>
                                                                    <?php else: ?>
                                                                        <li>
                                                                            <button type="submit" name="option" value="add-remove-from-picked" class="btn-list-button">
                                                                                <i class="fa fa-plus option-icon"></i><?= trans('add_picked'); ?>
                                                                            </button>
                                                                        </li>
                                                                    <?php endif;
                                                                endif;
                                                            endif; ?>
                                                            <li>
                                                                <a href="<?= adminUrl('edit-post/' . $item->id); ?>"><i class="fa fa-edit option-icon"></i><?= trans('edit'); ?></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" onclick="deleteItem('Post/deletePost','<?= $item->id; ?>','<?= trans("confirm_post"); ?>');"><i class="fa fa-trash option-icon"></i><?= trans('delete'); ?></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                            <div class="col-sm-12">
                                <?php if (empty($posts)): ?>
                                    <p class="text-center text-muted"><?= trans("no_results_found"); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-12 table-ft">
                                <div class="row">
                                    <div class="pull-right">
                                        <?= $pager->links; ?>
                                    </div>
                                    <?php if (countItems($posts) > 0): ?>
                                        <div class="pull-left">
                                            <button class="btn btn-sm btn-danger btn-table-delete" onclick="deleteSelectedPosts('<?= trans("confirm_posts"); ?>');"><?= trans('delete'); ?></button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .options-dropdown {
        left: -40px;
    }
</style>
