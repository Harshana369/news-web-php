<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"></div>
            <div class="box-body">
                <form action="<?= base_url('Admin/setModePost'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <ul class="nav nav-tabs layout-nav-tabs">
                        <li class="<?= $generalSettings->dark_mode == 0 ? 'active' : ''; ?>">
                            <button type="submit" name="theme_mode" value="light"><?= trans("light_mode"); ?></button>
                        </li>
                        <li class="<?= $generalSettings->dark_mode == 1 ? 'active' : ''; ?>">
                            <button type="submit" name="theme_mode" value="dark"><?= trans("dark_mode"); ?></button>
                        </li>
                    </ul>
                </form>
                <div class="tab-content tab-content-layout-items">
                    <div id="light_mode" class="tab-pane fade in active">
                        <input type="hidden" name="layout" id="light_layout" value="<?= $generalSettings->layout; ?>">
                        <div class="row row-layout-items">
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 m-b-15 layout-item <?= $generalSettings->layout == 'layout_1' ? 'active' : ''; ?>" data-val="layout_1" onclick="setTheme('layout_1');">
                                <img src="<?= base_url('assets/admin/img/ly_1.jpg'); ?>" alt="" class="img-responsive">
                                <button type="button" class="btn btn-block"><?= $generalSettings->layout == 'layout_1' ? trans("activated") : trans("activate"); ?></button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 m-b-15 layout-item <?= $generalSettings->layout == 'layout_2' ? 'active' : ''; ?>" data-val="layout_2" onclick="setTheme('layout_2');">
                                <img src="<?= base_url('assets/admin/img/ly_2.jpg'); ?>" alt="" class="img-responsive">
                                <button type="button" class="btn btn-block"><?= $generalSettings->layout == 'layout_2' ? trans("activated") : trans("activate"); ?></button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 m-b-15 layout-item <?= $generalSettings->layout == 'layout_3' ? 'active' : ''; ?>" data-val="layout_3" onclick="setTheme('layout_3');">
                                <img src="<?= base_url('assets/admin/img/ly_3.jpg'); ?>" alt="" class="img-responsive">
                                <button type="button" class="btn btn-block"><?= $generalSettings->layout == 'layout_3' ? trans("activated") : trans("activate"); ?></button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 m-b-15 layout-item <?= $generalSettings->layout == 'layout_4' ? 'active' : ''; ?>" data-val="layout_4" onclick="setTheme('layout_4');">
                                <img src="<?= base_url('assets/admin/img/ly_4.jpg'); ?>" alt="" class="img-responsive">
                                <button type="button" class="btn btn-block"><?= $generalSettings->layout == 'layout_4' ? trans("activated") : trans("activate"); ?></button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 m-b-15 layout-item <?= $generalSettings->layout == 'layout_5' ? 'active' : ''; ?>" data-val="layout_5" onclick="setTheme('layout_5');">
                                <img src="<?= base_url('assets/admin/img/ly_5.jpg'); ?>" alt="" class="img-responsive">
                                <button type="button" class="btn btn-block"><?= $generalSettings->layout == 'layout_5' ? trans("activated") : trans("activate"); ?></button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 m-b-15 layout-item <?= $generalSettings->layout == 'layout_6' ? 'active' : ''; ?>" data-val="layout_6" onclick="setTheme('layout_6');">
                                <img src="<?= base_url('assets/admin/img/ly_6.jpg'); ?>" alt="" class="img-responsive">
                                <button type="button" class="btn btn-block"><?= $generalSettings->layout == 'layout_6' ? trans("activated") : trans("activate"); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer"></div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 60px;">
    <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= trans('theme_settings'); ?></h3>
            </div>
            <form action="<?= base_url('Admin/setThemeSettingsPost'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="box-body">
                    <div class="form-group">
                        <label><?= trans('site_color'); ?></label>
                        <div>
                            <input type="text" class="form-control" id="inputSiteColor" name="site_color" maxlength="200" placeholder="<?= trans('color_code'); ?>" value="<?= esc($generalSettings->site_color); ?>" data-coloris required>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?= trans('save_changes'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/admin/plugins/coloris-0.24/coloris.min.css'); ?>"/>
<script src="<?= base_url('assets/admin/plugins/coloris-0.24/coloris.min.js'); ?>"></script>

<script>
    Coloris({
        theme: 'polaroid',
        swatches: ['#6366f1', '#264653', '#2a9d8f', '#e9c46a', '#f4a261', '#e76f51', '#d62828', '#023e8a', '#0077b6', '#0096c7']
    });
</script>

<script>
    function setTheme(layout) {
        $.ajax({
            type: "POST",
            url: InfConfig.baseUrl + "/Admin/setThemePost",
            data: {'layout': layout},
            success: function (response) {
                location.reload();
            }
        });
    }
</script>

<style>
    .layout-item {
        min-width: 200px;
    }
</style>


