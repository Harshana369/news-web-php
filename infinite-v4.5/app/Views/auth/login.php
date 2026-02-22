<section id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item active"><?= trans("login"); ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <div class="auth-container">
                    <div class="auth-card card">
                        <div class="card-header">
                            <h1><?= trans("login"); ?></h1>
                        </div>
                        <div class="card-body">
                            <?= view('partials/_messages'); ?>
                            <form action="<?= base_url('login-post'); ?>" method="post" id="form_validate">
                                <?= csrf_field(); ?>
                                <div class="mb-3">
                                    <input type="text" name="username" class="form-control form-input" value="<?= esc(old('username')); ?>" placeholder="<?= trans("username_or_email"); ?>" required maxlength="250">
                                </div>

                                <div class="mb-3 position-relative">
                                    <input type="password" name="password" id="inputPassword" class="form-control form-input" value="<?= esc(old('password')); ?>" placeholder="<?= trans("password"); ?>" required maxlength="250">
                                    <span class="password-toggle" id="togglePassword"><i class="icon-eye-fill"></i></span>
                                </div>

                                <button type="submit" class="btn btn-block btn-default mb-3"><?= trans("login"); ?></button>
                                <div class="mb-3 text-end">
                                    <a href="<?= langBaseUrl('forgot-password'); ?>" class="forgot-password"><?= trans("forgot_password"); ?>?</a>
                                </div>
                                <?= view("auth/_social_login"); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

