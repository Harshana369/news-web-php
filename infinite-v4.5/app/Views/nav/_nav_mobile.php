<div class="d-flex align-items-center justify-content-between nav-header py-2">
<button class="btn" id="btnOpenMobileNav" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavMobile" aria-label="button-open-mobile-nav">
<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
<path d="M3 12h18"/>
<path d="M3 18h18"/>
<path d="M3 6h18"/>
</svg>
</button>
<div class="d-flex align-items-center justify-content-center logo">
<a href="<?= langBaseUrl(); ?>" aria-label="logo" class="d-inline-block"><img src="<?= getMobileLogo($generalSettings); ?>" alt="logo" width="<?= getLogoSize('width'); ?>" height="<?= getLogoSize('height'); ?>"></a>
</div>
<button class="btn" type="button" id="btnOpenSearchMobile" aria-label="button-mobile-search">
<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
<circle cx="11" cy="11" r="8"/>
<path d="m21 21-4.3-4.3"/>
</svg>
</button>
</div>

<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasNavMobile">
<div class="offcanvas-header">
<div class="d-flex justify-content-between align-items-center w-100">
<label class="theme-switcher">
<input type="checkbox" class="dark-mode-toggle" aria-label="dark-mode-switcher-mobile" <?= $darkMode ? 'checked' : ''; ?>>
<span class="slider"></span>
</label>
<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
</div>
<div class="offcanvas-body">
<?php if (authCheck()): ?>

<div class="d-flex justify-content-center user-dropdown">
<div class="flex-shrink-0">
<img src="<?= getUserAvatar(user()->avatar); ?>" alt="<?= esc(user()->username); ?>" width="50" height="50" class="rounded-circle">
</div>
<div class="flex-grow-1 ms-2">
<div class="dropdown mt-2">
<button class="button-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-label="btn-user-dropdown-mobile">
<strong class="fw-semibold"><?= esc(limitCharacter(user()->username, 20, '...')); ?>&nbsp;</strong><i class="icon-arrow-down font-size-14"></i>
</button>
<ul class="dropdown-menu">
<?= view('nav/_user_profile_links.php'); ?>
</ul>
</div>
</div>
</div>

<?php else: ?>

<div class="d-flex justify-content-center align-items-center gap-2 mb-3">
<a href="<?= langBaseUrl('login'); ?>" class="btn btn-default">
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
<polyline points="10 17 15 12 10 7"/>
<line x1="15" x2="3" y1="12" y2="12"/>
</svg>&nbsp;<?= trans("login"); ?>
</a>
<a href="<?= langBaseUrl('register'); ?>" class="btn btn-default">
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
<circle cx="9" cy="7" r="4"/>
<line x1="19" x2="19" y1="8" y2="14"/>
<line x1="22" x2="16" y1="11" y2="11"/>
</svg>&nbsp;<?= trans("register"); ?>
</a>
</div>
<?php endif; ?>

<ul class="navbar-nav">
<li class="nav-item"><a href="<?= langBaseUrl(); ?>" class="nav-link"><?= trans("home"); ?></a></li>
<?php if (!empty($menuLinks)):
foreach ($menuLinks as $menuItem):
if (($menuItem->item_location == 'header' || $menuItem->item_location == 'top') && $menuItem->item_parent_id == '0'):
$subLinks = getSubMenuLinks($menuLinks, $menuItem->item_id, $menuItem->item_type);
if (!empty($subLinks)): ?>
<li class="nav-item dropdown">
<button class="nav-link dropdown-toggle d-flex justify-content-between align-items-center w-100" type="button" data-bs-toggle="dropdown" aria-label="nav-mobile-dropdown-<?= $menuItem->item_id; ?>">
<span><?= esc($menuItem->item_name); ?></span>
<i class="icon-arrow-down"></i>
</button>
<ul class="dropdown-menu">
<?php if ($menuItem->item_type == "category"): ?>
<li><a href="<?= generateMenuItemUrl($menuItem); ?>" class="dropdown-item"><?= trans("all"); ?></a></li>
<?php endif;
foreach ($subLinks as $subItem): ?>
<li><a href="<?= generateMenuItemUrl($subItem); ?>" class="dropdown-item"><?= esc($subItem->item_name); ?></a></li>
<?php endforeach; ?>
</ul>
</li>
<?php else: ?>
<li class="nav-item"><a href="<?= generateMenuItemUrl($menuItem); ?>" class="nav-link"><?= esc($menuItem->item_name); ?></a></li>
<?php endif;
endif;
endforeach;
endif;
if ($generalSettings->multilingual_system == 1 && countItems($languages) > 1): ?>
<li class="nav-item dropdown">
<button class="nav-link dropdown-toggle d-flex justify-content-between align-items-center w-100" type="button" data-bs-toggle="dropdown" aria-label="language-options-mobile">
<span><?= esc($activeLang->name); ?></span>
<i class="icon-arrow-down dropdown-custom-icon"></i>
</button>
<ul class="dropdown-menu">
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
</li>
<?php endif; ?>
</ul>
</div>
</div>