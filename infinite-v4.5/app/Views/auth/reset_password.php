<section id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item active"><?= trans("reset_password"); ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <div class="auth-container">
                    <div class="auth-card card">
                        <div class="card-header">
                            <h1><?= trans("reset_password"); ?></h1>
                        </div>
                        <div class="card-body">
                            <?= view('partials/_messages'); ?>
                            <form action="<?= base_url('reset-password-post'); ?>" method="post">
                                <?= csrf_field(); ?>
                                <?php if (!empty($user)): ?>
                                    <input type="hidden" name="reset_token" value="<?= esc($user->reset_token); ?>">
                                <?php endif;
                                if (!empty($passResetCompleted)): ?>
                                    <div class="mb-3">
                                        <a href="<?= langBaseUrl('login'); ?>" class="btn btn-block btn-default mb-3"><?= trans("login"); ?></a>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3 position-relative">
                                        <input type="password" name="password" id="inputPassword" class="form-control form-input" placeholder="<?= trans("password"); ?>" required>
                                        <span class="password-toggle" id="togglePassword"><i class="icon-eye-fill"></i></span>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="confirm_password" id="inputConfirmPassword" class="form-control form-input" placeholder="<?= trans("confirm_password"); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-block btn-default mb-3"><?= trans("submit"); ?></button>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>