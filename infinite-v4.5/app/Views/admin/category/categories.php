<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans('categories'); ?></h3>
                </div>
                <div class="right">
                    <a href="<?= adminUrl('add-category'); ?>" class="btn btn-success btn-add-new">
                        <i class="fa fa-plus"></i>
                        <?= trans('add_category'); ?>
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <div class="row table-filter-container">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-default filter-toggle collapsed m-b-10" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false">
                                        <i class="fa fa-filter"></i>&nbsp;&nbsp;<?= trans("filter"); ?>
                                    </button>
                                    <div class="collapse navbar-collapse p-0" id="collapseFilter">
                                        <form action="<?= adminUrl('categories'); ?>" method="get">
                                            <div class="item-table-filter" style="width: 80px; min-width: 80px;">
                                                <label><?= trans("show"); ?></label>
                                                <select name="show" class="form-control">
                                                    <option value="15" <?= inputGet('show', true) == '15' ? 'selected' : ''; ?>>15</option>
                                                    <option value="30" <?= inputGet('show', true) == '30' ? 'selected' : ''; ?>>30</option>
                                                    <option value="60" <?= inputGet('show', true) == '60' ? 'selected' : ''; ?>>60</option>
                                                    <option value="100" <?= inputGet('show', true) == '100' ? 'selected' : ''; ?>>100</option>
                                                </select>
                                            </div>

                                            <div class="item-table-filter">
                                                <label><?= trans("language"); ?></label>
                                                <select name="lang_id" class="form-control" onchange="getParentCategoriesByLang(this.value);">
                                                    <option value=""><?= trans("all"); ?></option>
                                                    <?php foreach ($languages as $language): ?>
                                                        <option value="<?= $language->id; ?>" <?= inputGet('lang_id', true) == $language->id ? 'selected' : ''; ?>><?= esc($language->name); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="item-table-filter">
                                                <label><?= trans("search"); ?></label>
                                                <input name="q" class="form-control" placeholder="Search" type="search" value="<?= esc(inputGet('q', true)); ?>">
                                            </div>

                                            <div class="item-table-filter md-top-10" style="width: 65px; min-width: 65px;">
                                                <label style="display: block">&nbsp;</label>
                                                <button type="submit" class="btn bg-purple"><?= trans("filter"); ?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped" role="grid">
                                <thead>
                                <tr role="row">
                                    <th width="20"><?= trans('id'); ?></th>
                                    <th><?= trans('category_name'); ?></th>
                                    <th><?= trans('parent_category'); ?></th>
                                    <th><?= trans('language'); ?></th>
                                    <th><?= trans('order'); ?></th>
                                    <th class="max-width-120"><?= trans('options'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($categories)):
                                    foreach ($categories as $item): ?>
                                        <tr>
                                            <td><?= esc($item->id); ?></td>
                                            <td><?= esc($item->name); ?></td>
                                            <td>
                                                <?php $parent = getCategory($item->parent_id);
                                                if (!empty($parent)) {
                                                    echo esc($parent->name);
                                                } else {
                                                    echo '-';
                                                } ?>
                                            </td>
                                            <td>
                                                <?php $lang = getLanguageClient($item->lang_id);
                                                if (!empty($lang)) {
                                                    echo esc($lang->name);
                                                } ?>
                                            </td>
                                            <td><?= esc($item->category_order); ?></td>
                                            <td style="width: 200px;">
                                                <div class="dropdown">
                                                    <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?= trans('select_option'); ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu options-dropdown">
                                                        <li>
                                                            <a href="<?= adminUrl('edit-category/' . $item->id); ?>"><i class="fa fa-edit option-icon"></i><?= trans('edit'); ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="deleteItem('Category/deleteCategoryPost','<?= $item->id; ?>','<?= trans("confirm_category"); ?>');"><i class="fa fa-trash option-icon"></i><?= trans('delete'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                endif; ?>
                                </tbody>
                            </table>
                            <?php if (empty($categories)): ?>
                                <p class="text-center">
                                    <?= trans("no_results_found"); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right">
                        <?= $pager->links; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>