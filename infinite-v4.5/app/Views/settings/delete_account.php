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
                <form action="<?= base_url('delete-account-post'); ?>" method="post" class="needs-validation">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="back_url" value="<?= esc(currentFullURL()); ?>">
                    <div class="mb-3">
                        <label class="form-label"><?= trans("password"); ?></label>
                        <input type="password" name="password" class="form-control form-input" value="<?= old("password"); ?>" placeholder="<?= trans("password"); ?>" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check d-flex">
                            <input class="form-check-input checkbox_terms_conditions" type="checkbox" id="checkboxTerms" name="confirm">
                            <label class="form-check-label fw-semibold text-danger" for="checkboxTerms">
                                <?= trans("delete_account_confirm"); ?>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-default"><?= trans("delete_account") ?></button>
                </form>
            </div>

        </div>
    </div>
</section>