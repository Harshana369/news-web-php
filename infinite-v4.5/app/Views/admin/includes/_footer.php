<?= $baseAIWriter->status == 1 && hasPermission('ai_writer') ? view('admin/post/_ai_writer') : ''; ?>
</section>
</div>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <strong style="font-weight: 600;"><?= $settings->copyright; ?>&nbsp;</strong>
    </div>
    <b>Version</b>&nbsp;<?= INFINITE_VERSION; ?>
</footer>
</div>
<script src="<?= base_url('assets/admin/js/plugins-4.5.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap-datetimepicker/moment.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/adminlte.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/pace/pace.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/file-manager-4.5/file-manager.js'); ?>"></script>
<script src="<?= base_url('assets/admin/js/admin-4.5.min.js'); ?>"></script>

<?php if (isset($langSearchColumn)): ?>
    <script>
        var table = $('#cs_datatable_lang').DataTable({
            dom: 'l<"#table_dropdown">frtip',
            "order": [[0, "desc"]],
            "aLengthMenu": [[15, 30, 60, 100], [15, 30, 60, 100, "All"]]
        });
        //insert a label
        $('<label class="table-label"><label/>').text('Language').appendTo('#table_dropdown');
        //insert the select and some options
        $select = $('<select class="form-control input-sm"><select/>').appendTo('#table_dropdown');
        $('<option/>').val('').text('<?= trans("all"); ?>').appendTo($select);
        <?php foreach ($languages as $lang): ?>
        $('<option/>').val('<?= $lang->name; ?>').text('<?= $lang->name; ?>').appendTo($select);
        <?php endforeach; ?>
        $("#table_dropdown select").change(function () {
            table.column(<?= $langSearchColumn; ?>).search($(this).val()).draw();
        });
    </script>
<?php endif; ?>
<script src="<?= base_url('assets/admin/plugins/tinymce-7.6.1/tinymce.min.js'); ?>"></script>
<script>
    tinymce.init({
        selector: '.tinyMCE',
        license_key: 'gpl',
        sandbox_iframes: false,
        height: 500,
        min_height: 500,
        valid_elements: '*[*]',
        relative_urls: false,
        entity_encoding: 'raw',
        remove_script_host: false,
        directionality: directionality,
        language: '<?= $activeLang->text_editor_lang; ?>',
        menubar: 'file edit insert format table help',
        plugins: 'emoticons advlist autolink lists link image charmap preview searchreplace visualblocks code codesample fullscreen insertdatetime media table',
        toolbar: 'fullscreen code preview | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist | forecolor backcolor removeformat | emoticons image media link',
        content_css: ['<?= base_url('assets/admin/plugins/tinymce-7.6.1/editor_content.css'); ?>'],
        mobile: {
            menubar: 'file insert format table help',
        }
    });
</script>
</body>
</html>
