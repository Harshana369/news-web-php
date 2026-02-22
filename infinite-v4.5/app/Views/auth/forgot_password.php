<section id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item active"><?= trans("forgot_password"); ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-12">
                <div class="auth-container">
                    <div class="auth-card card">
                        <div class="card-header">
                            <h1><?= trans("forgot_password"); ?></h1>
                        </div>
                        <div class="card-body">
                            <?= view('partials/_messages'); ?>
                            <form action="<?= base_url('forgot-password-post'); ?>" method="post">
                                <?= csrf_field(); ?>
                                <div class="mb-3">
                                    <input type="email" name="email" class="form-control form-input" placeholder="<?= trans("email"); ?>" required maxlength="250">
                                </div>
                                <button type="submit" class="btn btn-block btn-default mb-3"><?= trans("submit"); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
