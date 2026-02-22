<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?= trans('add_poll'); ?></h3>
                </div>
                <div class="right">
                    <a href="<?= adminUrl('polls'); ?>" class="btn btn-success btn-add-new">
                        <i class="fa fa-bars"></i>
                        <?= trans('polls'); ?>
                    </a>
                </div>
            </div>
            <form action="<?= base_url('Admin/addPollPost'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="box-body">
                    <div class="form-group">
                        <label><?= trans("language"); ?></label>
                        <select name="lang_id" class="form-control max-600">
                            <?php foreach ($languages as $language): ?>
                                <option value="<?= $language->id; ?>" <?= $activeLang->id == $language->id ? 'selected' : ''; ?>><?= $language->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?= trans('question'); ?></label>
                        <textarea class="form-control text-area" name="question" placeholder="<?= trans('question'); ?>" required><?= old('question'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?= trans("status"); ?></label>
                        <?= formRadio('status', 1, 0, trans("active"), trans("inactive"), 1, 'col-md-3'); ?>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?= trans('option'); ?></label>
                        <input type="text" class="form-control" name="option[]" placeholder="<?= trans('option'); ?>" required>
                    </div>

                    <div id="optionsContainer"></div>
                    <button type="button" id="addOption" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?= trans("add_option") ?></button>

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?= trans('add_poll'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .remove-option{
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