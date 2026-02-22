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
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr role="row">
                            <th width="20" class="table-no-sort" style="text-align: center !important;"><input type="checkbox" class="checkbox-table" id="checkAll"></th>
                            <th width="20"><?= trans('id'); ?></th>
                            <th><?= trans('name'); ?></th>
                            <th><?= trans('email'); ?></th>
                            <th><?= trans('message'); ?></th>
                            <th style="white-space: nowrap;"><?= trans('ip_address'); ?></th>
                            <th><?= trans('date'); ?></th>
                            <th class="max-width-120"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($messages)):
                            foreach ($messages as $item): ?>
                                <tr>
                                    <td style="text-align: center !important;"><input type="checkbox" name="checkbox-table" class="checkbox-table" value="<?= $item->id; ?>"></td>
                                    <td><?= esc($item->id); ?></td>
                                    <td><?= esc($item->name); ?></td>
                                    <td><?= esc($item->email); ?></td>
                                    <td class="break-word"><?= esc($item->message); ?></td>
                                    <td><?= esc($item->ip_address); ?></td>
                                    <td class="nowrap"><?= formatDate($item->created_at); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-default btn-delete" onclick="deleteItem('Admin/deleteContactMessagePost','<?= $item->id; ?>','<?= clrQuotes(trans("confirm_message")); ?>');"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>

                    <?php if (empty($messages)): ?>
                        <p class="text-center">
                            <?= trans("no_results_found"); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-12 text-right">
                <div class="pull-left">
                    <button class="btn btn-sm btn-danger btn-table-delete" onclick="deleteSelectedContactMessages('<?= clrQuotes(trans("confirm_action")); ?>');"><i class="fa fa-trash"></i>&nbsp;<?= trans('delete'); ?></button>
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