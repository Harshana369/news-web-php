<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= trans('update_poll'); ?></h3>
            </div>
            <form action="<?= base_url('Admin/editPollPost'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="box-body">
                    <input type="hidden" name="id" value="<?= esc($poll->id); ?>">
                    <div class="form-group">
                        <label><?= trans("language"); ?></label>
                        <select name="lang_id" class="form-control max-600">
                            <?php foreach ($languages as $language): ?>
                                <option value="<?= $language->id; ?>" <?= $poll->lang_id == $language->id ? 'selected' : ''; ?>><?= $language->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?= trans('question'); ?></label>
                        <textarea class="form-control text-area" name="question" placeholder="<?= trans('question'); ?>" required><?= esc($poll->question); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?= trans("status"); ?></label>
                        <?= formRadio('status', 1, 0, trans("active"), trans("inactive"), $poll->status, 'col-md-3'); ?>
                    </div>

                    <?php $firstOption = null;
                    if (!empty($pollOptions) && !empty($pollOptions[0])) {
                        $firstOption = $pollOptions[0];
                    } ?>

                    <div class="form-group">
                        <label class="control-label"><?= trans('option'); ?></label>
                        <input type="text" class="form-control" name="option[]" placeholder="<?= esc(trans('option')); ?>" value="<?= !empty($firstOption) ? esc($firstOption->option_text) : ''; ?>" required>
                    </div>

                    <div id="optionsContainer">
                        <?php $i = 0;
                        if (!empty($pollOptions)):
                            foreach ($pollOptions as $option):
                                if ($i > 0):?>
                                    <div class="form-group">
                                        <label class="control-label d-flex justify-content-between align-items-center">
                                            <span><?= trans('option'); ?></span>&nbsp;&nbsp;
                                            <button type="button" class="btn btn-xs btn-danger remove-option">&times;</button>
                                        </label>
                                        <input type="text" class="form-control" name="option[<?= esc($option->id); ?>]" placeholder="<?= esc(trans('option')); ?>" value="<?= esc($option->option_text); ?>" required>
                                    </div>
                                <?php endif;
                                $i++;
                            endforeach;
                        endif; ?>
                    </div>
                    <button type="button" id="addOption" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= trans("add_option") ?></button>

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?= trans('save_changes'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .remove-option {
        font-size: 15px;
        line-height: 1;
        padding: 2px 4px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const optionsContainer = document.getElementById("optionsContainer");
        const addOptionBtn = document.getElementById("addOption");
        addOptionBtn.addEventListener("click", function () {
            const div = document.createElement("div");
            div.classList.add("form-group");
            div.innerHTML = `
            <label class="control-label d-flex justify-content-between align-items-center">
                <span>Option</span>&nbsp;&nbsp;
                <button type="button" class="btn btn-xs btn-danger remove-option">&times;</button>
            </label>
            <input type="text" class="form-control" name="option[]" placeholder="<?= trans('option'); ?>" required>
        `;
            div.querySelector(".remove-option").addEventListener("click", function () {
                div.remove();
            });
            optionsContainer.appendChild(div);
        });
        const removeButtons = document.querySelectorAll(".remove-option");
        removeButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                const formGroup = button.closest(".form-group");
                formGroup.remove();
            });
        });
    });
</script>