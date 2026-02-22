<section id="main">
    <div class="container-xl">
        <div class="row gx-main">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl('settings'); ?>"><?= trans("settings"); ?></a></li>
                        <li class="breadcrumb-item active"><?= esc($title); ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <h1 class="page-title"><?= trans("settings"); ?></h1>
            </div>

            <div class="col-md-12 col-lg-3">
                <?= view("settings/_setting_tabs"); ?>
            </div>

            <div class="col-md-12 col-lg-9">
                <?= view('partials/_messages'); ?>
                <form action="<?= base_url('change-password-post'); ?>" method="post" id="form_validate">
                    <?= csrf_field(); ?>
                    <?php if (!empty($user->password)): ?>
                        <div class="mb-3">
                            <label class="form-label"><?= trans("old_password"); ?></label>
                            <input type="password" name="old_password" class="form-control form-input" value="<?= old("old_password"); ?>" placeholder="<?= trans("old_password"); ?>" maxlength="255" required>
                        </div>
                        <input type="hidden" value="1" name="is_pass_exist">
                    <?php else: ?>
                        <input type="hidden" value="0" name="is_pass_exist">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label"><?= trans("password"); ?></label>
                        <input type="password" name="password" class="form-control form-input" value="<?= old("password"); ?>" placeholder="<?= trans("password"); ?>" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= trans("confirm_password"); ?></label>
                        <input type="password" name="password_confirm" class="form-control form-input" value="<?= old("password_confirm"); ?>" placeholder="<?= trans("confirm_password"); ?>" maxlength="255" required>
                    </div>
                    <button type="submit" class="btn btn-default"><?= trans("change_password") ?></button>
                </form>
            </div>

        </div>
    </div>
</section>