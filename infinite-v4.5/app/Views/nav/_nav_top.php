<div class="container-fluid top-bar">
<div class="container-xl">
<div class="d-flex justify-content-between">
<div class="col-left d-flex align-items-center">
<ul class="d-flex flex-wrap gap-3">
<?php if (!empty($menuLinks)):
foreach ($menuLinks as $item):
if ($item->item_location == "top"):?>
<li><a href="<?= generateMenuItemUrl($item); ?>"><?= esc($item->item_name); ?> </a></li>
<?php endif;
endforeach;
endif; ?>
</ul>
</div>
<div class="col-right">
<div class="d-flex align-items-center nav-col-right gap-3">
<?php if (authCheck()): ?>
<div class="dropstart user-dropdown">
<button type="button" class="dropdown-toggle button-link" data-bs-toggle="dropdown" aria-expanded="false" aria-label="profile-icon">
<img src="<?= getUserAvatar(user()->avatar); ?>" alt="<?= esc(user()->username); ?>" width="26" height="26" class="img-fluid user-avatar">&nbsp;&nbsp;<?= esc(user()->username); ?>&nbsp;<i class="icon-arrow-down dropdown-custom-icon"></i>
</button>
<ul class="dropdown-menu dropdown-menu-end">
<?= view('nav/_user_profile_links.php'); ?>
</ul>
</div>
<?php else: ?>
<ul class="d-flex align-items-center nav-auth-links">
<li><a href="<?= langBaseUrl('login'); ?>"><?= trans("login"); ?></a></li>
<li class="seperator">/</li>
<li><a href="<?= langBaseUrl('register'); ?>"><?= trans("register"); ?></a></li>
</ul>
<?php endif;
if ($generalSettings->multilingual_system == 1 && countItems($languages) > 1): ?>
<div class="dropstart">
<button type="button" class="dropdown-toggle button-link d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" aria-label="language options">
<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#777777" viewBox="0 0 16 16">
<path
d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855A8 8 0 0 0 5.145 4H7.5zM4.09 4a9.3 9.3 0 0 1 .64-1.539 7 7 0 0 1 .597-.933A7.03 7.03 0 0 0 2.255 4zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a7 7 0 0 0-.656 2.5zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5zM8.5 5v2.5h2.99a12.5 12.5 0 0 0-.337-2.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5zM5.145 12q.208.58.468 1.068c.552 1.035 1.218 1.65 1.887 1.855V12zm.182 2.472a7 7 0 0 1-.597-.933A9.3 9.3 0 0 1 4.09 12H2.255a7 7 0 0 0 3.072 2.472M3.82 11a13.7 13.7 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm6.853 3.472A7 7 0 0 0 13.745 12H11.91a9.3 9.3 0 0 1-.64 1.539 7 7 0 0 1-.597.933M8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855q.26-.487.468-1.068zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.7 13.7 0 0 1-.312 2.5m2.802-3.5a7 7 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7 7 0 0 0-3.072-2.472c.218.284.418.598.597.933M10.855 4a8 8 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4z"/>
</svg>&nbsp;&nbsp;<span><?= esc($activeLang->name); ?>&nbsp;<i class="icon-arrow-down dropdown-custom-icon"></i></span>
</button>
<ul class="dropdown-menu dropdown-menu-end dropdown-menu-sm">
<li>
<?php if (!empty($languages)):
foreach ($languages as $language):
$langUrl = base_url() . '/' . $language->short_form . '/';
if ($language->id == $generalSettings->site_lang):
$langUrl = base_url();
endif; ?>
<a href="<?= $langUrl; ?>" class="dropdown-item"><?= esc($language->name); ?></a>
<?php endforeach;
endif; ?>
</li>
</ul>
</div>
<?php endif; ?>
<label class="theme-switcher">
<input type="checkbox" class="dark-mode-toggle" aria-label="dark-mode-switcher" <?= $darkMode ? 'checked' : ''; ?>>
<span class="slider"></span>
</label>
</div>
</div>
</div>
</div>
</div>