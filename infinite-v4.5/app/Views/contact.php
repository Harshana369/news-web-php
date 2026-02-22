<section id="main">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <?php if ($page->breadcrumb_active == 1): ?>
                            <li class="breadcrumb-item"><a href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a></li>
                            <li class="breadcrumb-item active"><?= esc($page->title); ?></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
            <?php if ($page->title_active == 1): ?>
                <div class="col-12">
                    <h1 class="page-title"><?= esc($page->title); ?></h1>
                </div>
            <?php endif; ?>
            <div class="col-12 page-content">
                <?= $settings->contact_text; ?>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="title-leave-message"><?= trans("leave_message"); ?></h2>
            </div>

            <div class="col-md-12 col-lg-6">
                <?= view('partials/_messages'); ?>
                <form action="<?= base_url('contact-post'); ?>" method="post" id="form_validate" class="validate_terms">
                    <?= csrf_field(); ?>
                    <div class="mb-3">
                        <input type="text" class="form-control form-input" name="name" placeholder="<?= trans("name"); ?>" maxlength="199" minlength="1" pattern=".*\S+.*" value="<?= old('name'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control form-input" name="email" maxlength="199" placeholder="<?= trans("email"); ?>" value="<?= old('email'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control form-input form-textarea" name="message" placeholder="<?= trans("message"); ?>" maxlength="4970" minlength="5" required><?= old('message'); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check d-flex">
                            <input class="form-check-input checkbox_terms_conditions" type="checkbox" id="checkboxTerms">
                            <label class="form-check-label" for="checkboxTerms">
                                <?= trans("terms_conditions_exp"); ?>&nbsp;<a href="<?= langBaseUrl('terms-conditions'); ?>" class="link-terms" target="_blank"><strong><?= trans("terms_conditions"); ?></strong></a>
                            </label>
                        </div>
                    </div>
                    <?php if (isRecaptchaEnabled($generalSettings)): ?>
                        <div class="d-flex justify-content-start mb-3">
                            <?php reCaptcha('generate', $generalSettings); ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-default" aria-label="contact"><?= trans("submit"); ?></button>
                    </div>
                </form>
            </div>

            <div class="col-md-12 col-lg-6">
                <div class="contact-page-icons">
                    <?php if ($settings->contact_phone): ?>
                        <div class="d-flex mb-2">
                            <div class="d-flex justify-content-center align-items-center flex-shrink-0 contact-icon">
                                <i class="icon-phone" aria-hidden="true"></i>
                            </div>
                            <div class="d-flex align-items-center">
                                <?= esc($settings->contact_phone); ?>
                            </div>
                        </div>
                    <?php endif;
                    if ($settings->contact_email): ?>
                        <div class="d-flex mb-2">
                            <div class="d-flex justify-content-center align-items-center flex-shrink-0 contact-icon">
                                <i class="icon-envelope" aria-hidden="true"></i>
                            </div>
                            <div class="d-flex align-items-center">
                                <?= esc($settings->contact_email); ?>
                            </div>
                        </div>
                    <?php endif;
                    if ($settings->contact_address): ?>
                        <div class="d-flex mb-2">
                            <div class="d-flex justify-content-center align-items-center flex-shrink-0 contact-icon">
                                <i class="icon-map-marker" aria-hidden="true"></i>
                            </div>
                            <div class="d-flex align-items-center">
                                <?= esc($settings->contact_address); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<?php if (!empty($settings->contact_address)): ?>
    <div class="container-fluid">
        <div class="row">
            <div class="contact-map-container p0">
                <iframe id="contact_iframe" src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?= $settings->contact_address; ?>&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    var iframe = document.getElementById("contact_iframe");
    if (iframe) {
        iframe.src = iframe.src;
        iframe.src = iframe.src;
    }
</script>
<style>
    #footer {
        margin-top: 0;
    }
</style>
