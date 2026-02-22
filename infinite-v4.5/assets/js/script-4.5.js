/*CSRF Protection*/
const csrfChannel = new BroadcastChannel('csrf-sync');
window.addEventListener('load', () => {
    const csrfToken = document.querySelector('meta[name="X-CSRF-TOKEN"]').content;
    csrfChannel.postMessage({ csrf: csrfToken });
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
            csrfChannel.postMessage({ csrf: newToken });
        }
    }
});

function updateCsrfTokenAfterUpload(data) {
    if (data && data.csrfToken) {
        const newToken = data.csrfToken;
        $('meta[name="X-CSRF-TOKEN"]').attr('content', newToken);
        $('input[name="' + InfConfig.csrfTokenName + '"]').val(newToken).attr('value', newToken);
        csrfChannel.postMessage({ csrf: newToken });
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

//custom scrollbar
$(function () {
    $('.custom-scrollbar').overlayScrollbars({});
});

$(document).ready(function () {
    $('#mainSlider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4900,
        centerMode: false,
        swipeToSlide: true,
        rtl: InfConfig.rtl,
        lazyLoad: 'ondemand',
        responsive: [
            {
                breakpoint: 1800,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    $('#singleSlider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4900,
        adaptiveHeight: true,
        rtl: InfConfig.rtl,
        lazyLoad: 'ondemand',
        swipeToSlide: true,
    });

    $('#randomSlider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4900,
        adaptiveHeight: true,
        rtl: InfConfig.rtl,
        lazyLoad: 'ondemand',
        swipeToSlide: true,
    });

    $('#postDetailSlider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 4900,
        adaptiveHeight: true,
        rtl: InfConfig.rtl,
        lazyLoad: 'progressive',
        swipeToSlide: true,
    });

    $(".form-newsletter").submit(function (event) {
        event.preventDefault();
        var formId = $(this).attr('id');
        var input = "#" + formId + " .newsletter-input";
        var email = $(input).val().trim();
        if (email == "") {
            $(input).addClass('has-error');
            return false;
        } else {
            $(input).removeClass('has-error');
        }
        var data = {
            'email': email,
            'url': $("#" + formId + " [name = 'url']").val()
        }
        $.ajax({
            type: "POST",
            url: InfConfig.baseUrl + "/Ajax/addToNewsletterPost",
            data: data,
            success: function (response) {
                if (response.result == 1) {
                    if (response.is_success) {
                        swal(response.message, {buttons: {confirm: InfConfig.textOk}, className: "centered-button", icon: "success"});
                    } else {
                        swal(response.message, {buttons: {confirm: InfConfig.textOk}, className: "centered-button", icon: "warning"});
                    }
                    if (response.is_success == 1) {
                        $(input).val('');
                    }
                }
            }
        });
    });
});

//toggle password
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#inputPassword');
    const confirmPassword = document.querySelector('#inputConfirmPassword');
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if (confirmPassword) {
                confirmPassword.setAttribute('type', type);
            }
            this.querySelector('i').classList.toggle('icon-eye-fill');
            this.querySelector('i').classList.toggle('icon-eye-slash-fill');
        });
    }
});

//redirect onclik
$(document).on('click', '.redirect-onclik', function () {
    var url = $(this).attr('data-url');
    window.location.href = url;
});

//mobile memu
$(document).on('click', '.btn-open-mobile-nav', function () {
    document.getElementById("navMobile").style.width = "280px";
    $('#overlay_bg').show();
});
$(document).on('click', '.btn-close-mobile-nav', function () {
    document.getElementById("navMobile").style.width = "0";
    $('#overlay_bg').hide();
});
$(document).on('click', '#overlay_bg', function () {
    document.getElementById("navMobile").style.width = "0";
    $('#overlay_bg').hide();
});

//switch theme mode
document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".dark-mode-toggle");
    const body = document.body;
    let mode = "light";

    toggles.forEach(function (toggleSwitch) {
        toggleSwitch.addEventListener("change", function () {
            if (this.checked) {
                body.classList.add("dark-mode");
                mode = "dark";
            } else {
                body.classList.remove("dark-mode");
                mode = "light";
            }

            $.ajax({
                type: 'POST',
                url: InfConfig.baseUrl + '/Ajax/switchThemeMode',
                data: { 'theme_mode': mode }
            });
        });
    });
});

//load more posts
var pageNumLoadMoreSearchPosts = 1;

function loadMoreSearchPosts(langId) {
    pageNumLoadMoreSearchPosts++;
    var data = {
        'lang_id': langId,
        'page': pageNumLoadMoreSearchPosts,
        'q': getUrlParameter('q')
    };
    $(".btn-load-more").prop("disabled", true);
    $('#load_posts_spinner').show();
    $.ajax({
        type: 'POST',
        url: InfConfig.baseUrl + '/Ajax/loadMoreSearchPosts',
        data: data,
        success: function (response) {
            if (response.result == 1) {
                setTimeout(function () {
                    $("#searchPostsLoadMoreContent").append(response.htmlContent);
                    $(".btn-load-more").prop("disabled", false);
                    $('#load_posts_spinner').hide();
                    if (!response.hasMore) {
                        $(".btn-load-more").hide();
                    }
                }, 200);
            } else {
                setTimeout(function () {
                    $(".btn-load-more").hide();
                    $('#load_posts_spinner').hide();
                }, 200);
            }
        }
    });
}

function getUrlParameter(param) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    return urlParams.get(param);
}

//scroll to top
$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $('.scrollup').fadeIn();
    } else {
        $('.scrollup').fadeOut();
    }
});
$('.scrollup').click(function () {
    $("html, body").animate({scrollTop: 0}, 700);
    return false;
});

//search
$(document).on('click', '#btnOpenSearch', function () {
    $('body').toggleClass('search-open');
});
$(document).on('click', '#btnOpenSearchMobile', function () {
    $('body').toggleClass('search-open');
});
$(document).on('click', '.modal-search .s-close', function () {
    $('body').removeClass('search-open');
});
$(document).on('click', '#btnOpenMobileNav', function () {
    $('body').removeClass('search-open');
});

//add att to iframe
$(document).ready(function () {
    $('iframe').attr("allowfullscreen", "");
});

//add reaction
let reactionAjaxRequest = false;
function addReaction(postId, reaction) {
    if (reactionAjaxRequest) {
        return false;
    }
    reactionAjaxRequest = true;
    var data = {
        'post_id': postId,
        'reaction': reaction
    };
    $.ajax({
        type: 'POST',
        url: InfConfig.baseUrl + '/Ajax/addReactionPost',
        data: data,
        success: function (response) {
            if (response.result == 1) {
                document.getElementById("reactions_result").innerHTML = response.htmlContent
            }
            reactionAjaxRequest = false;
        }
    });
}

//view poll results
function viewPollResults(a) {
    $("#poll_" + a + " .question").hide();
    $("#poll_" + a + " .result").show()
}

//view poll options
function viewPollOptions(a) {
    $("#poll_" + a + " .result").hide();
    $("#poll_" + a + " .question").show()
}

//poll
$(document).ready(function () {
    $(".poll-form").submit(function (event) {
        event.preventDefault();
        var formId = $(this).attr("data-form-id");
        var data = {
            'poll_id': $("#formPoll_" + formId + " [name = 'poll_id']").val(),
            'option_id': $("#formPoll_" + formId + " [name = 'option']:checked").val()
        }
        $(':input[type="submit"]').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: InfConfig.baseUrl + "/Ajax/addPollVotePost",
            data: data,
            success: function (response) {
                if (response.result == 1) {
                    if (response.response == "required") {
                        $("#poll-required-message-" + formId).show();
                        $("#poll-error-message-" + formId).hide();
                    } else if (response.response == "voted") {
                        $("#poll-error-message-" + formId).show();
                        $("#poll-required-message-" + formId).hide();
                    } else {
                        document.getElementById("poll-results-" + formId).innerHTML = response.response;
                        $("#poll_" + formId + " .result").show();
                        $("#poll_" + formId + " .question").hide()
                    }
                    $(':input[type="submit"]').prop('disabled', false);
                }
            }
        });
    });

    //add registered comment
    $("#form_add_comment_registered").submit(function (event) {
        event.preventDefault();
        var formValues = $(this).serializeArray();
        var data = {
            'limit': $('#post_comment_limit').val()
        };
        var submit = true;
        $(formValues).each(function (i, field) {
            if ($.trim(field.value).length < 1) {
                $("#form_add_comment_registered [name='" + field.name + "']").addClass("is-invalid");
                submit = false;
            } else {
                $("#form_add_comment_registered [name='" + field.name + "']").removeClass("is-invalid");
                data[field.name] = field.value;
            }
        });
        if (submit == true) {
            $('#comments form .btn').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: InfConfig.baseUrl + "/Ajax/addCommentPost",
                data: data,
                success: function (response) {
                    $('#comments form .btn').prop('disabled', false);
                    if (response.type == 'message') {
                        document.getElementById("message-comment-result").innerHTML = response.message;
                    } else {
                        document.getElementById("comment-result").innerHTML = response.message;
                    }
                    $("#form_add_comment_registered")[0].reset();
                }
            });
        }
    });

    //add comment
    $("#form_add_comment").submit(function (event) {
        event.preventDefault();
        var formValues = $(this).serializeArray();
        var data = {
            'limit': $('#post_comment_limit').val()
        };
        var submit = true;
        $(formValues).each(function (i, field) {
            if ($.trim(field.value).length < 1) {
                $("#form_add_comment [name='" + field.name + "']").addClass("is-invalid");
                submit = false;
            } else {
                $("#form_add_comment [name='" + field.name + "']").removeClass("is-invalid");
                data[field.name] = field.value;
            }
        });
        if (InfConfig.isRecaptchaEnabled == true) {
            if (typeof data['g-recaptcha-response'] === 'undefined') {
                $('.g-recaptcha').addClass("is-recaptcha-invalid");
                submit = false;
            } else {
                $('.g-recaptcha').removeClass("is-recaptcha-invalid");
            }
        }
        if (submit == true) {
            $('.g-recaptcha').removeClass("is-recaptcha-invalid");
            $('#comments form .btn').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: InfConfig.baseUrl + "/Ajax/addCommentPost",
                data: data,
                success: function (response) {
                    $('#comments form .btn').prop('disabled', false);
                    if (response.type == 'message') {
                        document.getElementById("message-comment-result").innerHTML = response.message;
                    } else {
                        document.getElementById("comment-result").innerHTML = response.message;
                    }
                    if (InfConfig.isRecaptchaEnabled == true) {
                        grecaptcha.reset();
                    }
                    $("#form_add_comment")[0].reset();
                }
            });
        }
    });
});

//add some comment
$(document).on('click', '.btn-subcomment', function () {
    var commentId = $(this).attr("data-comment-id");
    var formId = "#form_add_subcomment_" + commentId;
    var $form = $(formId);
    var formData = new FormData($form[0]);
    var submit = true;
    formData.append(InfConfig.csrfTokenName, $('meta[name="X-CSRF-TOKEN"]').attr('content'));
    formData.append('limit', $('#post_comment_limit').val());
    $form.find(':input[name]').each(function () {
        var field = $(this);
        var fieldName = field.attr('name');
        var fieldValue = $.trim(field.val());

        if (fieldValue.length < 1) {
            field.addClass("is-invalid");
            submit = false;
        } else {
            field.removeClass("is-invalid");
        }
    });
    if (InfConfig.isRecaptchaEnabled === true) {
        if (typeof formData.get('g-recaptcha-response') === 'undefined' || formData.get('g-recaptcha-response') === "") {
            $form.find('.g-recaptcha').addClass("is-recaptcha-invalid");
            submit = false;
        } else {
            $form.find('.g-recaptcha').removeClass("is-recaptcha-invalid");
        }
    }
    if (!submit) {
        return;
    }
    $('#comments form .btn').prop('disabled', true);
    $.ajax({
        url: InfConfig.baseUrl + "/Ajax/addCommentPost",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#comments form .btn').prop('disabled', false);
            if (InfConfig.isRecaptchaEnabled === true) {
                grecaptcha.reset();
            }
            if (response.type === 'message') {
                document.getElementById("message-subcomment-result-" + commentId).innerHTML = response.message;
            } else {
                document.getElementById("comment-result").innerHTML = response.message;
            }
            $("#form_add_subcomment_" + commentId).empty();
        }
    });
});

//load more comment
function loadMoreComment(postId) {
    var limit = parseInt($("#post_comment_limit").val());
    var data = {
        "post_id": postId,
        "limit": limit
    };
    $("#load_comment_spinner").show();
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Ajax/loadMoreCommentPost",
        data: data,
        success: function (response) {
            setTimeout(function () {
                $("#load_comment_spinner").hide();
                if (response.result == 1) {
                    document.getElementById("comment-result").innerHTML = response.content;
                }
            }, 1000)
        }
    });
}

//delete comment
function deleteComment(commentId, postId, message) {
    swal(swalOptions(message)).then(function (isConfirm) {
        if (isConfirm) {
            var limit = parseInt($("#post_comment_limit").val());
            var data = {
                "id": commentId,
                "post_id": postId,
                "limit": limit
            };
            $.ajax({
                type: "POST",
                url: InfConfig.baseUrl + "/Ajax/deleteCommentPost",
                data: data,
                success: function (response) {
                    if (response.result == 1) {
                        document.getElementById("comment-result").innerHTML = response.content;
                    }
                }
            });
        }
    });
}

//show comment box
var activeSubCommentFormId = null;
function showCommentBox(commentId) {
    if (activeSubCommentFormId === commentId) {
        $('.row-sub-comment-form').remove();
        activeSubCommentFormId = null;
        return;
    }

    $('.row-sub-comment-form').remove();
    activeSubCommentFormId = commentId;

    var limit = parseInt($("#post_comment_limit").val());
    var data = {
        "comment_id": commentId,
        "limit": limit
    };
    $.ajax({
        type: "POST",
        url: InfConfig.baseUrl + "/Ajax/loadSubcommentBox",
        data: data,
        success: function (response) {
            if (response.result == 1) {
                $('#sub_comment_form_' + commentId).append(response.content);
            }
        }
    });
}

//hide cookies warning
function closeCookiesWarning() {
    $('.cookies-warning').hide();
    $.ajax({
        type: 'POST',
        url: InfConfig.baseUrl + "/Ajax/closeCookiesWarningPost",
        data: {}
    });
}

$(document).ready(function () {
    $('form.validate-check-inputs').submit(function (e) {
        var isValid = true;
        $(this).find('.required-check-input').each(function () {
            var label = $('label[for="' + this.id + '"]');
            var link = label.find('a');

            if (!this.checked) {
                isValid = false;
                label.addClass('text-danger');
                link.addClass('text-danger');
            } else {
                label.removeClass('text-danger');
                link.removeClass('text-danger');
            }
        });
        if (!isValid) {
            e.preventDefault();
        }
    });
});

//loader checkmark
$(document).ready(function () {
    $('.circle-loader').toggleClass('load-complete');
    $('.checkmark').toggle();
});

$(function () {
    $('.post-text table').wrap('<div style="overflow-x:auto;"></div>');
});

if ($(".fb-comments").length > 0) {
    $(".fb-comments").attr("data-href", window.location.href);
}

$("#form_validate").validate();