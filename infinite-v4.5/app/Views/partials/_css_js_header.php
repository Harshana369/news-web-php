<style>.logo{width: <?= getLogoSize('width'); ?>px; height: <?= getLogoSize('height'); ?>px}.modal-newsletter .image {background-image: url('<?= getNewsletterImage();?>');}
<?php if (!empty($adSpaces)):
foreach ($adSpaces as $item):
if (!empty($item->desktop_width) && !empty($item->desktop_height)):
echo '.bn-ds-' . $item->id . '{width: ' . $item->desktop_width . 'px; height: ' . $item->desktop_height . 'px;}';
echo '.bn-mb-' . $item->id . '{width: ' . $item->mobile_width . 'px; height: ' . $item->mobile_height . 'px;}';
endif;
endforeach;?>
<?php endif; ?></style>
<script>var InfConfig = {baseUrl: '<?= base_url(); ?>', csrfTokenName: '<?= csrf_token(); ?>', sysLangId: <?= (int) $activeLang->id; ?>, rtl: <?= $activeLang->text_direction === 'rtl' ? 'true' : 'false'; ?>, isRecaptchaEnabled: <?= isRecaptchaEnabled($generalSettings) ? 1 : 0; ?>, textOk: "<?= esc(trans("ok"), 'js'); ?>", textCancel: "<?= esc(trans("cancel"), 'js'); ?>"};</script>