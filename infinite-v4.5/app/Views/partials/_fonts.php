<style>@font-face {font-family: 'inf-icons';src: url('<?= base_url('assets/vendor/inf-icons/inf-icons.woff2'); ?>') format('woff2'),url('<?= base_url('assets/vendor/inf-icons/inf-icons.woff'); ?>') format('woff');font-weight: normal;font-style: normal;}
<?php
$localFonts = [];
if (!empty($activeFonts)) {
    foreach ($activeFonts as $font) {
        if ($font->font_source == 'local') {
            $fontIds = array_map(function($f) { return $f->id; }, $localFonts);
            if (!in_array($font->id, $fontIds)) {
                $localFonts[] = $font;
            }
        }
    }
}
if(!empty($localFonts)):
foreach ($localFonts as $font):
if($font->font_source == 'local' && $font->font_key=='open-sans'):
echo "@font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 400; font-display: swap; src: url('".base_url('assets/fonts/open-sans/open-sans-400.woff2')."') format('woff2'), url('".base_url('assets/fonts/open-sans/open-sans-400.woff')."') format('woff')}  @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 600; font-display: swap; src: url('".base_url('assets/fonts/open-sans/open-sans-600.woff2')."') format('woff2'), url('".base_url('assets/fonts/open-sans/open-sans-600.woff')."') format('woff')}  @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 700; font-display: swap; src: url('".base_url('assets/fonts/open-sans/open-sans-700.woff2')."') format('woff2'), url('".base_url('assets/fonts/open-sans/open-sans-700.woff')."') format('woff')}";
endif;if($font->font_source == 'local' && $font->font_key=='inter'):
echo "@font-face {font-family: 'Inter'; font-style: normal; font-weight: 400; font-display: swap; src: url('".base_url('assets/fonts/inter/inter-400.woff2')."') format('woff2'), url('".base_url('assets/fonts/inter/inter-400.woff')."') format('woff')}  @font-face {font-family: 'Inter'; font-style: normal; font-weight: 600; font-display: swap; src: url('".base_url('assets/fonts/inter/inter-600.woff2')."') format('woff2'), url('".base_url('assets/fonts/inter/inter-600.woff')."') format('woff')}  @font-face {font-family: 'Inter'; font-style: normal; font-weight: 700; font-display: swap; src: url('".base_url('assets/fonts/inter/inter-700.woff2')."') format('woff2'), url('".base_url('assets/fonts/inter/inter-700.woff')."') format('woff')}";
endif;if($font->font_source == 'local' && $font->font_key=='roboto'):
echo "@font-face {font-family: 'Roboto'; font-style: normal; font-weight: 400; font-display: swap; src: url('".base_url('assets/fonts/roboto/roboto-400.woff2')."') format('woff2'), url('".base_url('assets/fonts/roboto/roboto-400.woff')."') format('woff')}  @font-face {font-family: 'Roboto'; font-style: normal; font-weight: 500; font-display: swap; src: url('".base_url('assets/fonts/roboto/roboto-500.woff2')."') format('woff2'), url('".base_url('assets/fonts/roboto/roboto-500.woff')."') format('woff')}  @font-face {font-family: 'Roboto'; font-style: normal; font-weight: 700; font-display: swap; url('".base_url('assets/fonts/roboto/roboto-700.woff2')."') format('woff2'), url('".base_url('assets/fonts/roboto/roboto-700.woff')."') format('woff')}";
endif;if($font->font_source == 'local' && $font->font_key=='source-sans-3'):
echo "@font-face {font-family: 'Source Sans 3'; font-style: normal; font-weight: 400; font-display: swap; src: url('".base_url('assets/fonts/source-sans/source-sans-3-400.woff2')."') format('woff2'), url('".base_url('assets/fonts/source-sans/source-sans-3-400.woff')."') format('woff');} @font-face {font-family: 'Source Sans 3';font-style: normal;font-weight: 600;font-display: swap;src: url('".base_url('assets/fonts/source-sans/source-sans-3-600.woff2')."') format('woff2'), url('".base_url('assets/fonts/source-sans/source-sans-3-600.woff')."') format('woff');} @font-face {font-family: 'Source Sans 3';font-style: normal;font-weight: 700;font-display: swap;src: url('".base_url('assets/fonts/source-sans/source-sans-3-700.woff2')."') format('woff2'), url('".base_url('assets/fonts/source-sans/source-sans-3-700.woff')."') format('woff');}";
endif; endforeach;endif; ?>:root {--inf-font-primary: <?= getFontFamily($activeFonts, 'primary', true); ?>;--inf-font-secondary: <?= getFontFamily($activeFonts, 'secondary', true); ?>;--inf-main-color: <?= esc($generalSettings->site_color); ?>;}</style>
<?= getFontURL($activeFonts,'primary'); ?>
<?= getFontURL($activeFonts,'secondary'); ?>