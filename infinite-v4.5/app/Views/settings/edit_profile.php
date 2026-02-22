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
                <form action="<?= base_url('edit-profile-post'); ?>" method="post" enctype="multipart/form-data" id="form_validate">
                    <?= csrf_field(); ?>
                    <div class="mb-3">
                        <div class="row mb-2">
                            <div class="col-12 mb-2">
                                <img src="<?= getUserAvatar($user->avatar); ?>" alt="<?= $user->username; ?>" class="form-avatar" width="180" height="180">
                            </div>
                            <div class="col-12">
                                <a class="btn btn-md btn-secondary btn-file-upload">
                                    <?= trans('select_image'); ?>
                                    <input type="file" name="file" size="40" accept=".png, .jpg, .webp, .jpeg, .gif" onchange="$('#upload-file-info').html($(this).val().replace(/.*[\/\\]/, ''));">
                                </a>
                                <p>
                                    <span class="badge bg-secondary" id="upload-file-info"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= trans("email"); ?></label>
                        <input type="email" name="email" class="form-control form-input" value="<?= esc($user->email); ?>" placeholder="<?= trans("email_address"); ?>" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= trans("username"); ?></label>
                        <input type="text" name="username" class="form-control form-input" value="<?= esc($user->username); ?>" placeholder="<?= trans("username"); ?>" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= trans("slug"); ?></label>
                        <input type="text" name="slug" class="form-control form-input" value="<?= esc($user->slug); ?>" placeholder="<?= trans("slug"); ?>" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= trans("about_me"); ?></label>
                        <textarea name="about_me" class="form-control form-textarea" placeholder="<?= trans("about_me"); ?>" maxlength="4999"><?= esc($user->about_me); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="show_email_on_profile" value="1" id="switchEmailProfile" class="form-check-input" role="switch" <?= $user->show_email_on_profile == 1 ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="switchEmailProfile"><?= trans('show_email_on_profile'); ?></label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="show_rss_feeds" value="1" id="switchRss" class="form-check-input" role="switch" <?= $user->show_rss_feeds == 1 ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="switchRss"><?= trans('rss_feeds'); ?></label>
                        </div>
                    </div>
                    <button type="submit" name="submit" value="update" class="btn btn-default"><?= trans("save_changes") ?></button>
                </form>
            </div>

        </div>
    </div>
</section>