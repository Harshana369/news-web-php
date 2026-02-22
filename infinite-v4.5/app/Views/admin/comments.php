<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?= $title; ?></h3>
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
                                <form action="<?= adminUrl('comments'); ?>" method="get">
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
                                        <label><?= trans("status"); ?></label>
                                        <select name="status" class="form-control">
                                            <option value="" <?= inputGet('status') != 'approved' && inputGet('status') == 'pending' ? 'selected' : ''; ?>><?= trans("all"); ?></option>
                                            <option value="approved" <?= inputGet('status') == 'approved' ? 'selected' : ''; ?>><?= trans("approved"); ?></option>
                                            <option value="pending" <?= inputGet('status') == 'pending' ? 'selected' : ''; ?>><?= trans("pending"); ?></option>
                                        </select>
                                    </div>

                                    <div class="item-table-filter md-top-10" style="width: 65px; min-width: 65px;">
                                        <label style="display: block">&nbsp;</label>
                                        <button type="submit" class="btn bg-purple"><?= trans("filter"); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr role="row">
                            <th width="20" class="table-no-sort" style="text-align: center !important;"><input type="checkbox" class="checkbox-table" id="checkAll"></th>
                            <th width="20"><?= trans('id'); ?></th>
                            <th><?= trans('user'); ?></th>
                            <th><?= trans('comment'); ?></th>
                            <th style="white-space: nowrap;"><?= trans('ip_address'); ?></th>
                            <th><?= trans('status'); ?></th>
                            <th style="min-width: 10%"><?= trans('date'); ?></th>
                            <th style="width: 160px;"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($comments)):
                            foreach ($comments as $item):
                                $post = getPostById($item->post_id); ?>
                                <tr>
                                    <td style="text-align: center !important;"><input type="checkbox" name="checkbox-table" class="checkbox-table" value="<?= $item->id; ?>"></td>
                                    <td><?= esc($item->id); ?></td>
                                    <td><?= esc($item->name); ?><br><?= esc($item->email); ?></td>
                                    <td class="break-word">
                                        <?= esc($item->comment); ?>
                                        <p>
                                            <?php if (!empty($post)):
                                                $baseURL = generateBaseURLByLangId($post->lang_id); ?>
                                                <a href="<?= generatePostURL($post, $baseURL); ?>" target="_blank"><?= trans("view_post") ?></a>
                                            <?php endif; ?>
                                        </p>
                                    </td>
                                    <td><?= esc($item->ip_address); ?></td>
                                    <td>
                                        <?php if ($item->status == 1): ?>
                                            <label class="label label-success"><?= trans("approved"); ?></label>
                                        <?php else: ?>
                                            <label class="label label-danger"><?= trans("pending"); ?></label>
                                        <?php endif; ?>
                                    </td>
                                    <td class="nowrap"><?= formatDate($item->created_at); ?></td>
                                    <td class="text-right">
                                        <form action="<?= base_url('Admin/approveCommentPost'); ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?= $item->id; ?>">
                                            <?php if ($item->status != 1): ?>
                                                <button type="submit" class="btn btn-sm btn-success btn-edit"><?= trans("approve"); ?></button>&nbsp;
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-default btn-delete" onclick="deleteItem('Admin/deleteCommentPost','<?= $item->id; ?>','<?= clrQuotes(trans("confirm_comment")); ?>');"><i class="fa fa-trash-o"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>

                    <?php if (empty($comments)): ?>
                        <p class="text-center">
                            <?= trans("no_results_found"); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-12 text-right">
                <div class="pull-left">
                    <button class="btn btn-sm btn-danger btn-table-delete" onclick="deleteSelectedComments('<?= clrQuotes(trans("confirm_comments")); ?>');"><i class="fa fa-trash"></i>&nbsp;<?= trans('delete'); ?></button>
                    <button class="btn btn-sm btn-success btn-table-delete" onclick="approveSelectedComments();"><i class="fa fa-check"></i>&nbsp;<?= trans('approve'); ?></button>
                </div>
                <div class="pull-right">
                    <?= $pager->links; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("input:checkbox").prop("checked", false);
    });
</script>