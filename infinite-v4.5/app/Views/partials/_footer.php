<?php if ($generalSettings->newsletter_status == 1 && $generalSettings->newsletter_popup == 1): ?>
    <div class="modal fade modal-newsletter" id="modalNewsletter" tabindex="-1" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="image"></div>
                    <div class="content">
                        <h5 class="title"><?= trans("join_newsletter"); ?></h5>
                        <p><?= trans("newsletter_desc"); ?></p>
                        <form id="form_newsletter_modal" class="form-newsletter" data-form-type="modal">
                            <input type="email" name="email" class="form-control form-input newsletter-input" placeholder="<?= trans('email') ?>">
                            <button type="submit" class="btn btn-block btn-custom"><?= trans("subscribe"); ?></button>
                            <input type="text" name="url">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= view('partials/_json_ld'); ?>
    <footer id="footer">
        <div class="container-xl">
            <div class="row footer-content">
                <div class="col-md-12 col-lg-4">
                    <div class="footer-widget">
                        <h4 class="title"><?= trans("about"); ?></h4>
                        <div class="title-line"></div>
                        <p><?= esc($settings->about_footer); ?></p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <div class="footer-widget">
                        <h4 class="title"><?= trans("latest_posts"); ?></h4>
                        <div class="title-line"></div>
                        <div class="footer-posts">
                            <?php $latestPosts = getLatestPosts($activeLang->id, 3);
                            if (!empty($latestPosts)):
                                foreach ($latestPosts as $item):
                                    echo view('post/_post_item_small', ['postItem' => $item]);
                                endforeach;
                            endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="footer-widget widget-follow">
                                <h4 class="title"><?= trans("social_media"); ?></h4>
                                <div class="title-line"></div>
                                <ul>
                                    <?php $socialArray = getSocialLinksArray($settings);
                                    foreach ($socialArray as $item):
                                        if (!empty($item['value'])):?>
                                            <li><a class="<?= $item['name']; ?>" href="<?= esc($item['value']); ?>" target="_blank" aria-label="<?= $item['name']; ?>"><i class="icon-<?= $item['name']; ?>"></i></a></li>
                                        <?php endif; endforeach;
                                    if ($generalSettings->show_rss == 1) : ?>
                                        <li><a class="rss" href="<?= langBaseUrl('rss-feeds'); ?>" aria-label="rss"><i class="icon-rss"></i></a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            <?php if ($generalSettings->newsletter_status == 1): ?>
                                <div class="widget-newsletter">
                                    <p><?= trans("newsletter_exp"); ?></p>
                                    <form id="form_newsletter_footer" class="form-newsletter">
                                        <div class="newsletter">
                                            <input type="email" name="email" class="newsletter-input" maxlength="199" placeholder="<?= trans("email"); ?>">
                                            <button type="submit" name="submit" value="form" class="newsletter-button" aria-label="subscribe"><?= trans("subscribe"); ?></button>
                                        </div>
                                        <input type="text" name="url">
                                        <div id="form_newsletter_response"></div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="d-flex justify-content-between flex-wrap">
                    <div class="flext-item copyright">
                        <?= $settings->copyright; ?>
                    </div>
                    <div class="flext-item">
                        <ul class="nav-footer">
                            <?php if (!empty($menuLinks)):
                                foreach ($menuLinks as $item):
                                    if ($item->item_location == "footer"):?>
                                        <li><a href="<?= generateMenuItemUrl($item); ?>"><?= esc($item->item_name); ?> </a></li>
                                    <?php endif;
                                endforeach;
                            endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php if ($settings->cookies_warning && empty(helperGetSession('cookies_warning'))): ?>
    <div class="cookies-warning">
        <button type="button" aria-label="close cookies warning" class="close" onclick="closeCookiesWarning();">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>
        <div class="text">
            <?= $settings->cookies_warning_text; ?>
        </div>
        <button type="button" class="btn btn-default" onclick="closeCookiesWarning();"><?= trans("accept_cookies"); ?></button>
    </div>
<?php endif; ?>
    <a href="#" class="scrollup"><i class="icon-arrow-up"></i></a>
    <script src="<?= base_url('assets/js/jquery-3.7.1.min.js'); ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins-4.5.js'); ?>"></script>
    <script src="<?= base_url('assets/js/script-4.5.min.js'); ?>"></script>
    <script>$('<input>').attr({type: 'hidden', name: 'lang', value: InfConfig.sysLangId}).appendTo('form');</script>
<?php if (checkNewsletterModal()): ?>
<script>$(window).on('load', function () {$('#modalNewsletter').modal('show');});</script>
<?php endif; ?>
<?= view('partials/_js_footer'); ?>
<?= $generalSettings->google_analytics; ?>
<?= $generalSettings->custom_footer_codes; ?>
    </body>
    </html>
<?php if (!empty($isPage404)): exit(); endif; ?>