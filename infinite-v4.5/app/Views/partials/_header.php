<!DOCTYPE html>
<html lang="<?= escMeta($activeLang->short_form); ?>" dir="<?= escMeta($activeLang->text_direction); ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= escMeta($title); ?> - <?= escMeta($settings->site_title); ?></title>
<meta name="description" content="<?= escMeta($description); ?>"/>
<meta name="keywords" content="<?= escMeta($keywords); ?>"/>
<meta name="author" content="<?= escMeta($settings->application_name); ?>"/>
<meta name="robots" content="all">
<meta name="revisit-after" content="1 Days"/>
<meta property="og:locale" content="<?= escMeta($activeLang->language_code) ?>"/>
<meta property="og:site_name" content="<?= escMeta($settings->application_name); ?>"/>
<?php if (isset($pageType)): ?>
<meta property="og:type" content="<?= escMeta($ogType); ?>"/>
<meta property="og:title" content="<?= escMeta($post->title); ?>"/>
<meta property="og:description" content="<?= escMeta($post->summary); ?>"/>
<meta property="og:url" content="<?= escMeta($ogUrl); ?>"/>
<meta property="og:image" content="<?= escMeta($ogImage); ?>"/>
<meta property="og:image:width" content="860"/>
<meta property="og:image:height" content="570"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:site" content="<?= escMeta($settings->application_name); ?>"/>
<meta name="twitter:title" content="<?= escMeta($post->title); ?>"/>
<meta name="twitter:description" content="<?= escMeta($post->summary); ?>"/>
<meta name="twitter:image" content="<?= $ogImage; ?>"/>
<?php foreach ($ogTags as $tag): ?>
<meta property="article:tag" content="<?= escMeta($tag->tag); ?>"/>
<?php endforeach;
else: ?>
<meta property="og:image" content="<?= getLogo($generalSettings); ?>"/>
<meta property="og:image:width" content="<?= getLogoSize('width'); ?>"/>
<meta property="og:image:height" content="<?= getLogoSize('height'); ?>"/>
<meta property="og:type" content=website/>
<meta property="og:title" content="<?= escMeta($title); ?> - <?= escMeta($settings->site_title); ?>"/>
<meta property="og:description" content="<?= escMeta($description); ?>"/>
<meta property="og:url" content="<?= langBaseUrl(); ?>"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:site" content="<?= escMeta($settings->application_name); ?>"/>
<meta name="twitter:title" content="<?= escMeta($title); ?> - <?= escMeta($settings->site_title); ?>"/>
<meta name="twitter:description" content="<?= escMeta($description); ?>"/>
<meta name="twitter:image" content="<?= getLogo($generalSettings); ?>"/>
<?php endif; ?>
<?= csrf_meta(); ?>
<link rel="shortcut icon" type="image/png" href="<?= getFavicon($generalSettings); ?>"/>
<link rel="canonical" href="<?= currentFullUrl(); ?>"/>
<link rel="alternate" href="<?= currentFullUrl(); ?>" hreflang="<?= $activeLang->language_code; ?>"/>
<?= view('partials/_fonts'); ?>
<link href="<?= base_url('assets/css/' . ($activeLang->text_direction == "rtl" ? 'plugins-rtl-4.5.css' : 'plugins-4.5.css')); ?>" rel="stylesheet"/>
<link href="<?= base_url('assets/css/style-4.5.min.css'); ?>" rel="stylesheet"/>
<?= view('partials/_css_js_header'); ?>
<?= $generalSettings->custom_header_codes; ?>
<?= $generalSettings->google_adsense_code; ?>
</head>
<body class="<?= $darkMode ? 'dark-mode' : ''; ?>">
<header id="header">
<?= view("nav/_nav_top"); ?>
<?= view("nav/_nav"); ?>

<div class="modal-search">
<form action="<?= langBaseUrl('search'); ?>" method="get">
<div class="container">
<input type="text" name="q" class="form-control" maxlength="300" pattern=".*\S+.*" placeholder="<?= trans("search_exp"); ?>" required>
<i class="icon-close s-close"></i>
</div>
</form>
</div>
</header>

<div class="nav-mobile sticky-top">
<?= view("nav/_nav_mobile.php"); ?>
</div>