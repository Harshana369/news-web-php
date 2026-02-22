<?php $menuLimit = $generalSettings->menu_limit;
$activePage = uri_string();
if ($generalSettings->site_lang != $activeLang->id):
$activePage = getSegmentValue(2);
endif;
$activePage = trim($activePage ?? '', '/'); ?>
<div class="nav-main px-0">
<div class="container-xl">
<nav class="navbar navbar-expand-lg">
<a href="<?= langBaseUrl(); ?>" class="navbar-brand p-0">
<div class="logo">
<img src="<?= getLogo($generalSettings); ?>" alt="logo" width="<?= getLogoSize('width'); ?>" height="<?= getLogoSize('height'); ?>">
</div>
</a>
<div class="d-flex justify-content-between w-100">
<ul class="navbar-nav mb-2 mb-lg-0">
<?php if ($generalSettings->show_home_link == 1): ?>
<li class="nav-item">
<a class="nav-link<?= $activePage == 'index' || $activePage == "" ? ' active' : ''; ?>" href="<?= langBaseUrl(); ?>"><?= trans("home"); ?></a>
</li>
<?php endif;
$totalItem = 1;
$i = 1;
if (!empty($menuLinks)):
foreach ($menuLinks as $menuItem):
if ($menuItem->item_location == "header" && $menuItem->item_parent_id == "0"):
if ($i < $menuLimit):
$subLinks = getSubMenuLinks($menuLinks, $menuItem->item_id, $menuItem->item_type);
if (!empty($subLinks)): ?>
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle<?= $activePage == $menuItem->item_slug ? ' active' : ''; ?>" href="<?= generateMenuItemUrl($menuItem); ?>" role="button" aria-expanded="false">
<?= esc($menuItem->item_name); ?>&nbsp;<i class="icon-arrow-down dropdown-custom-icon"></i>
</a>
<ul class="dropdown-menu">
<?php foreach ($subLinks as $subItem): ?>
<li><a class="dropdown-item" href="<?= generateMenuItemUrl($subItem); ?>"><?= esc($subItem->item_name); ?></a></li>
<?php endforeach; ?>
</ul>
</li>
<?php else: ?>
<li class="nav-item">
<a href="<?= generateMenuItemUrl($menuItem); ?>" class="nav-link<?= (!empty($activePage) && $activePage == $menuItem->item_slug) ? ' active' : ''; ?>"><?= esc($menuItem->item_name); ?></a>
</li>
<?php endif;
$i++;
endif;
$totalItem++;
endif;
endforeach;
endif;
if ($totalItem > $menuLimit): ?>
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" role="button" aria-expanded="false"><?= trans("more"); ?>&nbsp;<i class="icon-arrow-down dropdown-custom-icon"></i></a>
<ul class="dropdown-menu">
<?php
$i = 1;
if (!empty($menuLinks)):
foreach ($menuLinks as $menuItem):
if ($menuItem->item_location == "header" && $menuItem->item_parent_id == "0"):
if ($i >= $menuLimit):
$subLinks = getSubMenuLinks($menuLinks, $menuItem->item_id, $menuItem->item_type);
if (!empty($subLinks)): ?>
<li class="dropdown dropdown-sub">
<a class="dropdown-item dropdown-toggle" href="<?= generateMenuItemUrl($menuItem); ?>" role="button" aria-expanded="false">
<?= esc($menuItem->item_name); ?>&nbsp;<i class="icon-arrow-down dropdown-custom-icon"></i>
</a>
<ul class="dropdown-menu">
<?php foreach ($subLinks as $subItem): ?>
<li><a href="<?= generateMenuItemUrl($subItem); ?>" class="dropdown-item"><?= esc($subItem->item_name); ?></a></li>
<?php endforeach; ?>
</ul>
</li>
<?php else: ?>
<li>
<a href="<?= generateMenuItemUrl($menuItem); ?>" class="dropdown-item"><?= esc($menuItem->item_name); ?></a>
</li>
<?php endif;
endif;
$i++;
endif;
endforeach;
endif; ?>
</ul>
</li>
<?php endif; ?>
</ul>
<div class="d-flex align-items-center main-nav-buttons">
<button type="button" id="btnOpenSearch" class="button-link btn-nav-main" aria-label="search">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
<circle cx="11" cy="11" r="8"></circle>
<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
</svg>
</button>
</div>
</div>
</nav>
</div>
</div>
