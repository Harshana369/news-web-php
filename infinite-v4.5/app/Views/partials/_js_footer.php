<?php if (!empty($isGalleryPage)): ?>
<script src="<?php echo base_url('assets/vendor/masonry-filter/imagesloaded.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/masonry-filter/masonry-3.1.4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/masonry-filter/masonry.filter.js'); ?>"></script>
<link href="<?= base_url('assets/vendor/glightbox/css/glightbox.min.css'); ?>" rel="stylesheet">
<script src="<?= base_url('assets/vendor/glightbox/js/glightbox.min.js'); ?>"></script>
<script>
    var startFromLeft = true;
    if (InfConfig.rtl == true) {
        startFromLeft = false;
    }
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        zoomable: false
    });
    $(document).ready(function () {
        $(document).on("click touchstart", ".filters .btn", function () {
            $(".filters .btn").removeClass("active"), $(this).addClass("active")
        }), $(function () {
            var i = $("#masonry");
            i.imagesLoaded(function () {
                i.masonry({gutterWidth: 0, isAnimated: !0, itemSelector: ".gallery-item", isOriginLeft: startFromLeft});
                $(".page-gallery").css("opacity", 1);
            }), $(".filters .btn").click(function (t) {
                t.preventDefault();
                var e = $(this).attr("data-filter");
                i.masonryFilter({
                    filter: function () {
                        return !e || $(this).attr("data-filter") == e
                    }
                })
            })
        })
    });
</script>
<?php endif; ?>