<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= htmlspecialchars($title); ?> - <?= trans("admin"); ?>&nbsp;<?= htmlspecialchars($settings->site_title); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type="image/png" href="<?= getFavicon($generalSettings); ?>"/>
    <?= csrf_meta(); ?>
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/AdminLTE.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/_all-skins.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/datatables/dataTables.bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/datatables/jquery.dataTables_themeroller.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/pace/pace.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/file-manager-4.5/file-manager.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/admin-4.5.min.css'); ?>">
    <script>var directionality = "ltr";</script>
    <?php if ($activeLang->text_direction == "rtl"): ?>
        <link href="<?= base_url('assets/admin/css/rtl.css'); ?>" rel="stylesheet"/>
        <script>directionality = "rtl";</script>
    <?php endif; ?>
    <script src="<?= base_url('assets/admin/js/jquery-3.2.1.min.js'); ?>"></script>
    <script>var InfConfig = {
            baseUrl: "<?= base_url(); ?>",
            csrfTokenName: '<?= csrf_token() ?>',
            sysLangId: '<?= $activeLang->id; ?>',
            textOk: "<?= clrQuotes(trans("ok")); ?>",
            textCancel: "<?= clrQuotes(trans("cancel")); ?>",
            textTopicEmpty: "<?= clrQuotes(trans("msg_topic_empty")); ?>"
        };
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <a href="<?= adminUrl(); ?>" class="logo">
            <span class="logo-mini"></span>
            <span class="logo-lg"><b><?= esc($settings->application_name); ?></b> <?= trans("panel"); ?></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><i class="fa fa-bars" aria-hidden="true"></i></a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li><a class="btn btn-sm btn-success pull-left btn-site-prev" target="_blank" href="<?= base_url(); ?>"><i class="fa fa-eye"></i> <?= trans("view_site"); ?></a></li>
                    <?php if ($generalSettings->multilingual_system == 1 && countItems($languages) > 1): ?>
                        <li class="dropdown user-menu">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fa fa-globe"></i>&nbsp;
                                <?= esc($activeLang->name); ?>
                                <span class="fa fa-caret-down"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (!empty($languages)):
                                    foreach ($languages as $language): ?>
                                        <li>
                                            <form action="<?= base_url('Admin/setActiveLanguagePost'); ?>" method="post">
                                                <?= csrf_field(); ?>
                                                <button type="submit" value="<?php echo $language->id; ?>" name="lang_id" class="control-panel-lang-btn"><?php echo limitCharacter($language->name, 20, '...'); ?></button>
                                            </form>
                                        </li>
                                    <?php endforeach;
                                endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="<?= getUserAvatar(user()->avatar); ?>" class="user-image" alt="">
                            <span class="hidden-xs"><?= user()->username; ?> <i class="fa fa-caret-down"></i> </span>
                        </a>
                        <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                            <li><a href="<?= base_url('profile/' . user()->slug); ?>"><i class="fa fa-user"></i> <?= trans("profile"); ?></a></li>
                            <li><a href="<?= base_url('settings'); ?>"><i class="fa fa-cog"></i> <?= trans("update_profile"); ?></a></li>
                            <li><a href="<?= base_url('settings/change-password'); ?>"><i class="fa fa-lock"></i> <?= trans("change_password"); ?></a></li>
                            <li class="divider"></li>
                            <li><a href="<?= base_url('logout'); ?>"><i class="fa fa-sign-out"></i> <?= trans("logout"); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?= getUserAvatar(user()->avatar); ?>" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?= esc(user()->username); ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> <?= trans("online"); ?></a>
                </div>
            </div>
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header"><?= trans("main_navigation"); ?></li>
                <li class="nav-home">
                    <a href="<?= adminUrl(); ?>"><i class="fa fa-home"></i><span><?= trans("home"); ?></span></a>
                </li>
                <?php if (hasPermission('themes')): ?>
                    <li class="nav-themes">
                        <a href="<?= adminUrl('themes'); ?>"><i class="fa fa-paint-brush" aria-hidden="true"></i> <span><?= trans("themes"); ?></span></a>
                    </li>
                <?php endif;
                if (hasPermission('navigation')): ?>
                    <li class="nav-navigation">
                        <a href="<?= adminUrl('navigation'); ?>"><i class="fa fa-th"></i><span><?= trans("navigation"); ?></span></a>
                    </li>
                <?php endif;
                if (hasPermission('pages')): ?>
                    <li class="nav-pages">
                        <a href="<?= adminUrl('pages'); ?>"><i class="fa fa-leaf" aria-hidden="true"></i> <span><?= trans("pages"); ?></span></a>
                    </li>
                <?php endif;
                if (hasPermission('add_post')): ?>
                    <li class="treeview<?= isAdminNavActive(['add-post', 'add-video']); ?>">
                        <a href="#">
                            <i class="fa fa-file"></i> <span><?= trans("add_post"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="nav-add-post">
                                <a href="<?= adminUrl('add-post'); ?>"><?= trans("add_post"); ?></a>
                            </li>
                            <li class="nav-add-video">
                                <a href="<?= adminUrl('add-video'); ?>"><?= trans("add_video"); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif;
                if (hasPermission('manage_all_posts') || hasPermission('add_post')): ?>
                    <li class="treeview<?= isAdminNavActive(['posts', 'auto-post-deletion']); ?>">
                        <a href="#">
                            <i class="fa fa-bars"></i> <span><?= trans("posts"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= isAdminPostsNavActive('posts'); ?>">
                                <a href="<?= adminUrl('posts'); ?>"><?= trans("posts"); ?></a>
                            </li>
                            <?php if (hasPermission('manage_all_posts')): ?>
                                <li class="<?= isAdminPostsNavActive('slider-posts'); ?>">
                                    <a href="<?= adminUrl('posts'); ?>?list=slider-posts"><?= trans("slider_posts"); ?></a>
                                </li>
                                <li class="<?= isAdminPostsNavActive('our-picks'); ?>">
                                    <a href="<?= adminUrl('posts'); ?>?list=our-picks"><?= trans("our_picks"); ?></a>
                                </li>
                            <?php endif; ?>
                            <li class="<?= isAdminPostsNavActive('pending'); ?>">
                                <a href="<?= adminUrl('posts'); ?>?list=pending-posts"><?= trans("pending_posts"); ?></a>
                            </li>
                            <li class="<?= isAdminPostsNavActive('drafts'); ?>">
                                <a href="<?= adminUrl('posts'); ?>?list=drafts"><?= trans("drafts"); ?></a>
                            </li>
                            <?php if (hasPermission('manage_all_posts')): ?>
                                <li class="nav-auto-post-deletion">
                                    <a href="<?= adminUrl('auto-post-deletion'); ?>"><?= trans("auto_post_deletion"); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif;
                if (hasPermission('rss_feeds')): ?>
                    <li class="treeview<?= isAdminNavActive(['import-feed', 'update-feed', 'feeds']); ?>">
                        <a href="#">
                            <i class="fa fa-rss"></i> <span><?= trans("rss_feeds"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="nav-import-feed">
                                <a href="<?= adminUrl('import-feed'); ?>"><?= trans("import_rss_feed"); ?></a>
                            </li>
                            <li class="nav-feeds">
                                <a href="<?= adminUrl('feeds'); ?>"><?= trans("rss_feeds"); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif;
                if (hasPermission('categories')): ?>
                    <li class="nav-categories">
                        <a href="<?= adminUrl('categories'); ?>">
                            <i class="fa fa-folder-open"></i><span><?= trans("categories"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('polls')): ?>
                    <li class="nav-polls">
                        <a href="<?= adminUrl('polls'); ?>">
                            <i class="fa fa-list"></i><span><?= trans("polls"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('gallery')): ?>
                    <li class="treeview<?= isAdminNavActive(['gallery', 'gallery-albums', 'gallery-categories', 'update-gallery-image', 'update-gallery-album', 'update-gallery-category']); ?>">
                        <a href="#">
                            <i class="fa fa-image"></i> <span><?= trans("gallery"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="nav-gallery-albums">
                                <a href="<?= adminUrl('gallery-albums'); ?>"><?= trans("albums"); ?></a>
                            </li>
                            <li class="nav-gallery-categories">
                                <a href="<?= adminUrl('gallery-categories'); ?>"><?= trans("categories"); ?></a>
                            </li>
                            <li class="nav-gallery">
                                <a href="<?= adminUrl('gallery'); ?>"><?= trans("images"); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif;
                if (hasPermission('comments')): ?>
                    <li class="nav-comments">
                        <a href="<?= adminUrl('comments'); ?>"><i class="fa fa-comments"></i>
                            <span><?= trans("comments"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('contact_messages')): ?>
                    <li class="nav-contact-messages">
                        <a href="<?= adminUrl('contact-messages'); ?>">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                            <span><?= trans("contact_messages"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('newsletter')): ?>
                    <li class="nav-newsletter">
                        <a href="<?= adminUrl('newsletter'); ?>">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span><?= trans("newsletter"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('ad_spaces')): ?>
                    <li class="nav-ad-spaces">
                        <a href="<?= adminUrl('ad-spaces?type=index_top'); ?>">
                            <i class="fa fa-dollar" aria-hidden="true"></i>
                            <span><?= trans("ad_spaces"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('membership')): ?>
                    <li class="nav-users">
                        <a href="<?= adminUrl('users'); ?>"><i class="fa fa-users"></i><span><?= trans("users"); ?></span></a>
                    </li>
                    <li class="nav-roles-permissions">
                        <a href="<?= adminUrl('roles-permissions'); ?>">
                            <i class="fa fa-key"></i>
                            <span><?= trans("roles_permissions"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('cache_system')): ?>
                    <li class="nav-cache-system">
                        <a href="<?= adminUrl('cache-system'); ?>">
                            <i class="fa fa-database"></i><span><?= trans("cache_system"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('seo_tools')): ?>
                    <li class="nav-seo-tools">
                        <a href="<?= adminUrl('seo-tools'); ?>"><i class="fa fa-wrench"></i>
                            <span><?= trans("seo_tools"); ?></span>
                        </a>
                    </li>
                <?php endif;
                if (hasPermission('settings')): ?>
                    <li class="nav-storage">
                        <a href="<?= adminUrl('storage'); ?>"><i class="fa fa-cloud-upload"></i><span><?= trans("storage"); ?></span></a>
                    </li>
                    <li class="nav-preferences">
                        <a href="<?= adminUrl('preferences'); ?>"><i class="fa fa-check-square-o"></i><span><?= trans("preferences"); ?></span></a>
                    </li>
                    <li class="treeview<?= isAdminNavActive(['settings', 'language-settings', 'email-settings', 'social-login-settings', 'font-settings']); ?>">
                        <a href="#">
                            <i class="fa fa-cogs"></i> <span><?= trans("settings"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="nav-settings">
                                <a href="<?= adminUrl('settings'); ?>"><?= trans("settings"); ?></a>
                            </li>
                            <li class="nav-language-settings">
                                <a href="<?= adminUrl('language-settings'); ?>"><?= trans("language_settings"); ?></a>
                            </li>
                            <li class="nav-email-settings">
                                <a href="<?= adminUrl('email-settings'); ?>"><?= trans("email_settings"); ?></a>
                            </li>
                            <li class="nav-social-login-settings">
                                <a href="<?= adminUrl('social-login-settings'); ?>"><?= trans("social_login_settings"); ?></a>
                            </li>
                            <li class="nav-font-settings">
                                <a href="<?= adminUrl('font-settings'); ?>"><?= trans("font_settings"); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif;
                if (user()->role_id == 1): ?>
                    <li>
                        <div class="database-backup">
                            <form action="<?= base_url('Admin/downloadDatabaseBackup'); ?>" method="post">
                                <?= csrf_field(); ?>
                                <button type="submit" class="btn btn-block"><i class="fa fa-download"></i>&nbsp;&nbsp;<?= trans("download_database_backup"); ?></button>
                            </form>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </section>
    </aside>
    <?php
    $segment2 = $segment = getSegmentValue(2);
    $segment3 = $segment = getSegmentValue(3);
    $uriString = $segment2;
    if (!empty($segment3)) {
        $uriString .= '-' . $segment3;
    } ?>
    <style>
        <?php if(!empty($uriString)):
        echo '.nav-'.$uriString.' > a{color: #fff !important;}';
        else:
        echo '.nav-home > a{color: #fff !important;}';
        endif;?>
    </style>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <?= view('admin/includes/_messages'); ?>
                </div>
            </div>