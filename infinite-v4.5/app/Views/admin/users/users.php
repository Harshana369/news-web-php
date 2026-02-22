<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?= trans("users"); ?></h3>
        </div>
        <?php if (hasPermission('membership')): ?>
            <div class="right">
                <a href="<?= adminUrl('add-user'); ?>" class="btn btn-success btn-add-new">
                    <i class="fa fa-plus"></i>
                    <?= trans("add_user"); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <?= view('admin/users/_filters'); ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr role="row">
                            <th width="20"><?= trans('id'); ?></th>
                            <th><?= trans('user'); ?></th>
                            <th><?= trans('email'); ?></th>
                            <th><?= trans('status'); ?></th>
                            <th><?= trans('date'); ?></th>
                            <th class="max-width-120"><?= trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($users)):
                            foreach ($users as $user):
                                $role = getRoleClient($user->role_id, $roles);
                                $roleName = '';
                                if (!empty($role)) {
                                    $roleName = @parseSerializedNameArray($role->role_name, $activeLang->id, true);
                                } ?>
                                <tr>
                                    <td><?= esc($user->id); ?></td>
                                    <td style="width: 30%;">
                                        <div class="media">
                                            <div class="<?= $activeLang->text_direction == 'rtl' ? 'media-right' : 'media-left'; ?>">
                                                <a href="<?= generateProfileUrl($user->slug); ?>" target="_blank">
                                                    <img src="<?= getUserAvatar($user->avatar); ?>" alt="user" style="width: 50px; height: 50px; border-radius: 1px;">
                                                </a>
                                            </div>
                                            <div class="media-body <?= $activeLang->text_direction == 'rtl' ? 'text-right' : 'text-left'; ?>">
                                                <a href="<?= generateProfileUrl($user->slug); ?>" target="_blank" class="table-link">
                                                    <strong class="media-heading font-weight-600"><?= esc($user->username); ?></strong>
                                                </a>
                                                <p>
                                                    <?php if ($role && $role->is_super_admin): ?>
                                                        <label class="label bg-maroon"><?= esc($roleName); ?></label>
                                                    <?php elseif ($role && $role->is_admin): ?>
                                                        <label class="label bg-olive"><?= esc($roleName); ?></label>
                                                    <?php elseif ($role && $role->is_author): ?>
                                                        <label class="label label-warning"><?= esc($roleName); ?></label>
                                                    <?php else: ?>
                                                        <label class="label label-default"><?= esc($roleName); ?></label>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($user->email); ?></td>
                                    <td>
                                        <?php if ($user->status == 1): ?>
                                            <label class="label label-success"><?= trans('active'); ?></label>
                                        <?php else: ?>
                                            <label class="label label-danger"><?= trans('banned'); ?></label>
                                        <?php endif; ?>
                                    </td>
                                    <td class="nowrap"><?= formatDate($user->created_at); ?></td>
                                    <td>
                                        <form action="<?= base_url('Admin/userOptionsPost'); ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?= esc($user->id); ?>">
                                            <div class="dropdown">
                                                <button class="btn bg-purple dropdown-toggle btn-select-option" type="button" data-toggle="dropdown"><?= trans('select_option'); ?>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu options-dropdown">
                                                    <?php if ($role->is_super_admin != 1): ?>
                                                        <li>
                                                            <button type="button" class="btn-list-button" data-toggle="modal" data-target="#myModal" onclick="$('#modal_user_id').val('<?= esc($user->id); ?>');">
                                                                <i class="fa fa-user option-icon"></i><?= trans('change_user_role'); ?>
                                                            </button>
                                                        </li>
                                                        <?php if ($user->status == "1"): ?>
                                                            <li>
                                                                <button type="submit" name="option" value="ban" class="btn-list-button">
                                                                    <i class="fa fa-stop-circle option-icon"></i><?= trans('ban_user'); ?>
                                                                </button>
                                                            </li>
                                                        <?php else: ?>
                                                            <li>
                                                                <button type="submit" name="option" value="remove_ban" class="btn-list-button">
                                                                    <i class="fa fa-stop-circle option-icon"></i><?= trans('remove_ban'); ?>
                                                                </button>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <li>
                                                        <a href="<?= adminUrl(); ?>/edit-user/<?= esc($user->id); ?>"><i class="fa fa-edit option-icon"></i><?= trans('edit'); ?></a>
                                                    </li>
                                                    <?php if ($role->is_super_admin != 1): ?>
                                                        <li>
                                                            <a href="javascript:void(0)" onclick="deleteItem('Admin/deleteUserPost','<?= $user->id; ?>','<?= trans("confirm_user"); ?>');"><i class="fa fa-trash option-icon"></i><?= trans('delete'); ?></a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach;
                        endif; ?>
                        </tbody>
                    </table>
                    <?php if (empty($users)): ?>
                        <p class="text-center text-muted"><?= trans("no_results_found"); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-12 text-right">
                <?= $pager->links; ?>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= trans('change_user_role'); ?></h4>
            </div>
            <form action="<?= base_url('Admin/changeUserRolePost'); ?>" method="post">
                <?= csrf_field(); ?>
                <input type="hidden" name="user_id" id="modal_user_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label"><?php echo trans("role"); ?></label>
                        <select name="role_id" class="form-control" required>
                            <option value=""><?= trans("select"); ?></option>
                            <?php if (!empty($roles)):
                                foreach ($roles as $item):
                                    $roleName = @parseSerializedNameArray($item->role_name, $activeLang->id, true); ?>
                                    <option value="<?= $item->id; ?>"><?= esc($roleName); ?></option>
                                <?php endforeach;
                            endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><?= trans('save'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= trans('close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>