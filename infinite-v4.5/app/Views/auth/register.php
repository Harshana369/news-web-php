<section id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item active"><?= trans("register"); ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <div class="auth-container">
                    <div class="auth-card card">
                        <div class="card-header">
                            <h1><?= trans("register"); ?></h1>
                        </div>
                        <div class="card-body">
                            <?= view('partials/_messages'); ?>
                            <form action="<?= base_url('register-post'); ?>" method="post" id="form_validate" class="validate-check-inputs">
                                <?= csrf_field(); ?>
                                <div class="mb-3">
                                    <input type="text" name="username" class="form-control form-input" placeholder="<?= trans("username"); ?>" value="<?= old("username"); ?>" required maxlength="250">
                                </div>
                                <div class="mb-3">
                                    <input type="email" name="email" class="form-control form-input" placeholder="<?= trans("email"); ?>" value="<?= old("email"); ?>" required maxlength="250">
                                </div>
                                <div class="mb-3 position-relative">
                                    <input type="password" name="password" id="inputPassword" class="form-control form-input" placeholder="<?= trans("password"); ?>" value="<?= old("password"); ?>" required>
                                    <span class="password-toggle" id="togglePassword"><i class="icon-eye-fill"></i></span>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="confirm_password" id="inputConfirmPassword" class="form-control form-input" placeholder="<?= trans("confirm_password"); ?>" value="<?= old("confirm_password"); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check d-flex">
                                        <input class="form-check-input required-check-input" type="checkbox" id="checkboxTerms">
                                        <label class="form-check-label" for="checkboxTerms">
                                            <?= trans("terms_conditions_exp"); ?>&nbsp;<a href="<?= langBaseUrl('terms-conditions'); ?>" class="link-terms" target="_blank"><strong><?= trans("terms_conditions"); ?></strong></a>
                                        </label>
                                    </div>
                                </div>
                                <?php if (isRecaptchaEnabled($generalSettings)): ?>
                                    <div class="d-flex justify-content-center mb-3">
                                        <?php reCaptcha('generate', $generalSettings); ?>
                                    </div>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-block btn-default mb-3"><?= trans("register"); ?></button>
                                <?= view("auth/_social_login"); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>