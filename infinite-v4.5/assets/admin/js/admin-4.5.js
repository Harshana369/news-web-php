/*CSRF Protection*/
const csrfChannel = new BroadcastChannel('csrf-sync');
window.addEventListener('load', () => {
    const csrfToken = document.querySelector('meta[name="X-CSRF-TOKEN"]').content;
    csrfChannel.postMessage({csrf: csrfToken});
});

csrfChannel.onmessage = (event) => {
    if (event.data?.csrf) {
        $('meta[name="X-CSRF-TOKEN"]').attr('content', event.data.csrf);
        $('input[name="' + InfConfig.csrfTokenName + '"]').val(event.data.csrf).attr('value', event.data.csrf);
    }
};

$.ajaxSetup({
    beforeSend: function (xhr, settings) {
        const csrfHash = $('meta[name="X-CSRF-TOKEN"]').attr('content');
        if (settings.type.toUpperCase() === 'POST') {
            if (typeof settings.data === 'string') {
                settings.data += '&' + InfConfig.csrfTokenName + '=' + csrfHash;
                settings.data += '&sysLangId=' + InfConfig.sysLangId;
            } else if (typeof settings.data === 'object') {
                settings.data = settings.data || {};
                settings.data[InfConfig.csrfTokenName] = csrfHash;
            }
        }
    },
    complete: function (xhr) {
        const newToken = xhr.getResponseHeader('X-CSRF-TOKEN');
        if (newToken) {
            $('meta[name="X-CSRF-TOKEN"]').attr('content', newToken);
            $('input[name="' + InfConfig.csrfTokenName + '"]').val(newToken).attr('value', newToken);
            csrfChannel.postMessage({csrf: newToken});
        }
    }
});

function updateCsrfTokenAfterUpload(data) {
    if (data && data.csrfToken) {
        const newToken = data.csrfToken;
        $('meta[name="X-CSRF-TOKEN"]').attr('content', newToken);
        $('input[name="' + InfConfig.csrfTokenName + '"]').val(newToken).attr('value', newToken);
        csrfChannel.postMessage({csrf: newToken});
    }
}

function swalOptions(message) {
    return {
        text: message,
        icon: 'warning',
        buttons: true,
        buttons: [InfConfig.textCancel, InfConfig.textOk],
        dangerMode: true,
    };
}

function setSerializedData(serializedData) {
    serializedData.push({name: 'sysLangId', value: InfConfig.sysLangId});
    serializedData.push({name: InfConfig.csrfTokenName, value: $('meta[name="X-CSRF-TOKEN"]').attr('content')});
    return serializedData;
}

//datatable
$(document).ready(function () {
    $('#cs_datatable').DataTable({
        "order": [[0, "desc"]],
        "aLengthMenu": [[15, 30, 60, 100], [15, 30, 60, 100, "All"]]
    });
});

//generate text with ai
$(document).on('submit', '#formAIWriter', function (e) {
    e.preventDefault();
    $('.buttons-ai-writer button').prop('disabled', true);
    //reset
    $('#generatedContentAIWriter').html('');
    $('#generatedContentAIWriter').hide();

    var form = $(this);
    var topic = form.find("textarea[name='topic']").val();
    if (!topic || topic.trim() === '') {
        $('.buttons-ai-writer button').prop('disabled', false);
        swal(InfConfig.textTopicEmpty, {buttons: {confirm: InfConfig.textOk}, className: "centered-button", icon: "warning"});
        return false;
    }
    $('#spinnerAIWriter').show();
    var formData = form.serializeArray();
    formData.push({name: 'sysLangId', value: InfConfig.sysLangId});
    formData.push({name: InfConfig.csrfTokenName, value: $('meta[name="X-CSRF-TOKEN"]').attr('content')});
    $.ajax({
        url: InfConfig.baseUrl + '/Ajax/generateTextAI',
        type: 'POST',
        data: formData,
        success: function (response) {
            $('.buttons-ai-writer button').prop('disabled', false);
            $('#spinnerAIWriter').hide();
            if (response.status === 'error') {
                swal(response.message, {buttons: {confirm: InfConfig.textOk}, className: "centered-button", icon: "warning"});
            } else if (response.status === 'success') {
                $('#generatedContentAIWriter').html(response.content);
                $('#generatedContentAIWriter').show();
                $('#btnAIGenerate').hide();
                $('#btnAIRegenerate').show();
                $('#btnAIUseText').show();
                $('#btnAIReset').show();
            } else {
                console.error("Unexpected response format.");
            }
        },
        error: function (error) {
            $('.buttons-ai-writer button').prop('disabled', false);
        }
    });
});

//add ai content to editor
$(document).on('click', '#btnAIUseText', function () {
    const content = $('#generatedContentAIWriter').html().trim();

    if (content && tinymce.activeEditor) {
        tinymce.activeEditor.execCommand('mceInsertContent', false, content);
        $('#modalAiWriter').modal('hide');
        resetFormAIWriter();
    } else {
        console.log('TinyMCE editor not found or content is empty.');
    }
});

//reset ai writer form
function resetFormAIWriter() {
    $('#formAIWriter')[0].reset();
    $('#generatedContentAIWriter').html('');
    $('#generatedContentAIWriter').hide();
    $('#btnAIGenerate').show();
    $('#btnAIRegenerate').hide();
    $('#btnAIUseText').hide();
    $('#btnAIReset').hide();
}

function getSubCategories(val) {
    var data = {
        "parent_id": val
    };
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Category/getSubCategories",
        data: data,
        success: function (response) {
            $('#subcategories').children('option:not(:first)').remove();
            if (response.status) {
                $("#subcategories").append(response.content);
            }
        }
    });
}

function getParentCategoriesByLang(val) {
    var data = {
        "lang_id": val
    };
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Category/getParentCategoriesByLang",
        data: data,
        success: function (response) {
            $('#categories').children('option:not(:first)').remove();
            $('#subcategories').children('option:not(:first)').remove();
            if (response.status) {
                $("#categories").append(response.content);
            }
        }
    });
}

function getAlbumsByLang(val) {
    var data = {
        "lang_id": val
    };
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Gallery/galleryAlbumsByLang",
        data: data,
        success: function (response) {
            $('#albums').children('option:not(:first)').remove();
            if (response.status) {
                $("#albums").append(response.content);
            }
        }
    });
}

function getCategoriesByAlbums(val) {
    var data = {
        "category_id": val
    };
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Gallery/galleryCategoriesByAlbum",
        data: data,
        success: function (response) {
            $('#categories').children('option:not(:first)').remove();
            if (response.status) {
                $("#categories").append(response.content);
            }
        }
    });
}

function setAsAlbumCover(val) {
    var data = {
        "image_id": val
    };
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Gallery/setAsAlbumCover",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
}

function getMenuLinksByLang(val) {
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Admin/getMenuLinksByLang",
        data: {"lang_id": val},
        success: function (response) {
            $('#parent_links').children('option:not(:first)').remove();
            if (response.status) {
                $("#parent_links").append(response.content);
            }
        }
    });
}

$(document).on('change', '.input-slider-order', function () {
    var input = $(this);
    var data = {
        "id": input.data('id'),
        "slider_order": input.val()
    };
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Post/homeSliderPostsOrderPost",
        data: data,
        success: function (response) {
            input.addClass('flash-background-color');
            setTimeout(function () {
                input.removeClass('flash-background-color');
            }, 500);
        }
    });
});

//datetimepicker
$(function () {
    $('#datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
});

//upload product image update page
$(document).on('change', '#Multifileupload', function () {
    var MultifileUpload = document.getElementById("Multifileupload");
    if (typeof (FileReader) != "undefined") {
        var MultidvPreview = document.getElementById("MultidvPreview");
        MultidvPreview.innerHTML = "";
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
        for (var i = 0; i < MultifileUpload.files.length; i++) {
            var file = MultifileUpload.files[i];
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.createElement("IMG");
                img.height = "100";
                img.width = "100";
                img.src = e.target.result;
                img.id = "Multifileupload_image";
                MultidvPreview.appendChild(img);
                $("#Multifileupload_button").show();
            }
            reader.readAsDataURL(file);
        }
    } else {
        alert("This browser does not support HTML5 FileReader.");
    }
});

/*
*
* Video Upload Functions
*
* */

$("#video_embed_code").on("change keyup paste", function () {
    var embed_code = $("#video_embed_code").val();
    $("#video_preview").attr('src', embed_code);

    if ($("#video_embed_code").val() == '') {
        $("#selected_image_file").attr('src', '');
    }
});

$("#video_thumbnail_url").on("change keyup paste", function () {
    var url = $("#video_thumbnail_url").val();
    $("#selected_image_file").attr('src', url);
    $('input[name="post_image_id"]').val('');
});

//reset file input
function reset_file_input(id) {
    $(id).val('');
    $(id + "_label").html('');
    $(id + "_button").hide();
}

//reset preview image
function reset_preview_image(id) {
    $(id).val('');
    $(id + "_image").remove();
    $(id + "_button").hide();
}

//check all checkboxes
$("#checkAll").click(function () {
    $('input:checkbox').not(this).prop('checked', this.checked);
});

//show hide delete button
$('.checkbox-table').click(function () {
    if ($(".checkbox-table").is(':checked')) {
        $(".btn-table-delete").show();
    } else {
        $(".btn-table-delete").hide();
    }
});

//delete selected posts
function deleteSelectedPosts(message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var postIds = [];
            $("input[name='checkbox-table']:checked").each(function () {
                postIds.push(this.value);
            });
            $.ajax({
                type: "POST",
                url: InfConfig.baseUrl + "/Post/deleteSelectedPosts",
                data: {'post_ids': postIds},
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//approve selected comments
function approveSelectedComments() {
    var commentIds = [];
    $("input[name='checkbox-table']:checked").each(function () {
        commentIds.push(this.value);
    });
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Admin/approveSelectedComments",
        data: {'comment_ids': commentIds},
        success: function (response) {
            location.reload();
        }
    });
};

//delete selected comments
function deleteSelectedComments(message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var commentIds = [];
            $("input[name='checkbox-table']:checked").each(function () {
                commentIds.push(this.value);
            });
            $.ajax({
                type: "POST",
                url: InfConfig.baseUrl + "/Admin/deleteSelectedComments",
                data: {'comment_ids': commentIds},
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//delete selected contact messages
function deleteSelectedContactMessages(message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var messageIds = [];
            $("input[name='checkbox-table']:checked").each(function () {
                messageIds.push(this.value);
            });
            $.ajax({
                type: "POST",
                url: InfConfig.baseUrl + "/Admin/deleteSelectedContactMessagesPost",
                data: {'message_ids': messageIds},
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//delete post main image
$(document).on('click', '#btn_delete_post_main_image', function () {
    var content = '<a class="btn-select-image" data-toggle="modal" data-target="#image_file_manager" data-image-type="main">' +
        '<div class="btn-select-image-inner">' +
        '<i class="fa fa-image"></i>' +
        '<button class="btn">' + txt_select_image + '</button>' +
        '</div>' +
        '</a>';
    document.getElementById("post_select_image_container").innerHTML = content;
    $("#post_image_id").val('');
    $("#input_image_url").val('');
});

//delete post main image database
$(document).on('click', '#btn_delete_post_main_image', function () {
    var content = '<a class="btn-select-image" data-toggle="modal" data-target="#image_file_manager" data-image-type="main">' +
        '<div class="btn-select-image-inner">' +
        '<i class="fa fa-image"></i>' +
        '<button class="btn">' + txt_select_image + '</button>' +
        '</div>' +
        '</a>';
    document.getElementById("post_select_image_container").innerHTML = content;
    $("#post_image_id").val('');
    $("#input_image_url").val('');
});

$("#input_image_url").on("change keyup paste", function () {
    var url = $("#input_image_url").val();
    var image = '<div class="post-select-image-container">' +
        '<img src="' + url + '" alt="">' +
        '<a id="btn_delete_post_main_image" class="btn btn-danger btn-sm btn-delete-selected-file-image">' +
        '<i class="fa fa-times"></i> ' +
        '</a>' +
        '</div>';
    document.getElementById("post_select_image_container").innerHTML = image;
    $('#selected_image_file').css('margin-top', '15px');
});

$(document).on('click', '#btn_delete_post_main_image_database', function () {
    var data = {
        "post_id": $(this).attr('data-post-id')
    };
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Post/deletePostMainImage",
        data: data,
        success: function (response) {
            var content = '<a class="btn-select-image" data-toggle="modal" data-target="#image_file_manager" data-image-type="main">' +
                '<div class="btn-select-image-inner">' +
                '<i class="fa fa-image"></i>' +
                '<button class="btn">' + txt_select_image + '</button>' +
                '</div>' +
                '</a>';
            document.getElementById("post_select_image_container").innerHTML = content;
            $("#post_image_id").val('');
            $("#input_image_url").val('');
        }
    });
});

$('.increase-count').each(function () {
    $(this).prop('Counter', 0).animate({
        Counter: $(this).text()
    }, {
        duration: 1000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});

//delete item
function deleteItem(url, id, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                url: InfConfig.baseUrl + '/' + url,
                data: {'id': id},
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};

//delete additional image
$(document).on('click', '.btn-delete-additional-image', function () {
    var item_id = $(this).attr("data-value");
    $('.additional-item-' + item_id).remove();

});

//delete additional image from database
$(document).on('click', '.btn-delete-additional-image-database', function () {
    var fileId = $(this).attr("data-value");
    $('.additional-item-' + fileId).remove();
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Post/deletePostAdditionalImage",
        data: {"file_id": fileId}
    });
});

//delete selected file
$(document).on('click', '.btn-delete-selected-file', function () {
    var item_id = $(this).attr("data-value");
    $('#file_' + item_id).remove();
});

//delete selected file from database
$(document).on('click', '.btn-delete-selected-file-database', function () {
    var fileId = $(this).attr("data-value");
    $('#post_selected_file_' + fileId).remove();
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Post/deletePostFile",
        data: {"file_id": fileId}
    });
});

//delete video image
function delete_video_image(post_id) {
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Post/deletePostMainImage",
        data: {"post_id": post_id},
        success: function (response) {
            $('.btn-delete-main-img').hide();
            $("#selected_image_file").attr('src', '');
            $("#video_thumbnail_url").val('');
            $(".btn-delete-post-video").hide();
            $('input[name="post_image_id"]').val('');
        }
    });
}

/*
*
* Video Upload Functions
*
* */

$("#video_embed_code").on("change keyup paste", function () {
    var embed_code = $("#video_embed_code").val();
    $("#video_preview").attr('src', embed_code);

    if ($("#video_embed_code").val() == '') {
        $("#selected_image_file").attr('src', '');
    }
});

function getVideoFromURL() {
    var url = $("#video_url").val();
    if (url) {
        $.ajax({
            type: "POST",
            url: InfConfig.baseUrl + "/Post/getVideoFromURL",
            data: {"url": url},
            success: function (response) {
                if (response.video_embed_code) {
                    $("#video_embed_code").html(response.video_embed_code);
                    $("#video_embed_preview").attr('src', response.video_embed_code);
                    $("#video_embed_preview").show();
                }
                if (response.video_thumbnail) {
                    $("#video_thumbnail_url").val(response.video_thumbnail);
                    $("#selected_image_file").attr('src', response.video_thumbnail);
                }
            }
        });
    }
}

$("#video_thumbnail_url").on("change keyup paste", function () {
    var url = $("#video_thumbnail_url").val();
    $("#selected_image_file").attr('src', url);
    $('input[name="post_image_id"]').val('');
});

//sanitize url
function sanitizeUrl(url) {
    url = url.replace(/&amp;/g, '&');
    const validUrlPattern = /^[a-zA-Z0-9-._~:/?#[\]@!$&'()*+,;%=]+$/;
    if (!validUrlPattern.test(url)) {
        return '';
    }
    if (url.toLowerCase().includes("javascript:")) {
        return '';
    }
    let urlObj = new URL(url);
    let params = new URLSearchParams(urlObj.search);
    params.forEach((value, key) => {
        if (params.getAll(key).length > 1) {
            params.delete(key);
            params.append(key, value);
        }
    });
    urlObj.search = params.toString();
    return urlObj.href;
}

//add back url to the forms
$(document).ready(function () {
    $('form[method="post"]').each(function () {
        if ($(this).find('input[name="back_url"]').length === 0) {
            let backUrl = window.location.href;
            backUrl = sanitizeUrl(backUrl);
            if (backUrl) {
                $(this).append('<input type="hidden" name="back_url" value="' + backUrl + '">');
            }
        }
    });
});