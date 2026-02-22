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
                <form action="<?= base_url('social-accounts-post'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <?php $socialArray = getSocialLinksArray(user(), true);
                    foreach ($socialArray as $item):?>
                        <div class="mb-3">
                            <label class="form-label"><?= trans($item['name']); ?></label>
                            <input type="text" class="form-control form-input" name="<?= $item['name']; ?>" placeholder="<?= trans("enter_url"); ?>" value="<?= esc($item['value']); ?>" maxlength="1000">
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-default"><?= trans("save_changes") ?></button>
                </form>
            </div>

        </div>
    </div>
</section>