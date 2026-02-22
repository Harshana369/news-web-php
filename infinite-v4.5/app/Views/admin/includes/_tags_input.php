<link rel="stylesheet" href="<?= base_url("assets/admin/plugins/tagify/tagify.css") ?>">
<script src="<?= base_url("assets/admin/plugins/tagify/tagify.js") ?>"></script>

<div class="row">
    <div class="col-sm-12 m-b-5">
        <label class="control-label"><?= trans('tags'); ?></label>
        <?php if (hasPermission('tags')): ?>
            <a href="<?= adminUrl("tags"); ?>" class="btn btn-xs btn-default btn-manage-tags" target="_blank"><i class="fa fa-edit"></i>&nbsp;<?= trans("manage_tags"); ?></a>
        <?php endif; ?>
    </div>
    <div class="col-sm-12">
        <input name="tags" id="tag-input" class="form-control tags-input" value="<?= !empty($tags) ? $tags : ''; ?>" placeholder="<?= esc(trans('type_tag')); ?>"/>
    </div>
</div>

<style>
    .btn-manage-tags {
        float: right;
        padding: 2px 10px;
        line-height: 20px;
    }

    .tagify--focus {
        --tags-border-color: #3c8dbc !important
    }

    .tagify__dropdown {
        max-width: 300px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .tagify__dropdown__item {
        background-color: #fff !important;
        padding: 10px 15px;
        font-size: 14px;
        color: #333 !important;
        cursor: pointer;
        transition: background-color 0.2s, color 0.2s;
    }

    .tagify__dropdown__item:hover {
        background-color: #000;
        color: #222;
    }

    .tagify__dropdown__wrapper {
        border-color: #3c8dbc;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputElement = document.querySelector('#tag-input');
        const tagify = new Tagify(inputElement, {
            enforceWhitelist: false,
            whitelist: [],
            maxTags: <?= POST_TAGS_LIMIT; ?>,
            dropdown: {
                enabled: 1,
                position: 'text',
                closeOnSelect: false
            }
        });
        tagify.on('input', function (e) {
            const searchTerm = e.detail.value;
            if (searchTerm.length < 2) return;
            var data = {
                searchTerm: searchTerm
            };
            $.ajax({
                type: 'POST',
                url: InfConfig.baseUrl + '/Ajax/getTagSuggestions',
                data: data,
                success: function (response) {
                    if (response.result == 1) {
                        tagify.settings.whitelist = response.tags;
                        tagify.dropdown.show(e.detail.value);
                    }
                }
            });
        });
    });
</script>