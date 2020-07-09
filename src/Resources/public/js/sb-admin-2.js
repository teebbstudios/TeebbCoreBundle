"use strict"; // Start of use strict

$.fn.insertAt = function (index, element) {
    var lastIndex = this.children().length;
    if (index < 0) {
        index = Math.max(0, lastIndex + 1 + index);
    }
    this.append(element);
    if (index < lastIndex) {
        this.children().eq(index).before(this.children().last());
    }
    return this;
};

// Toggle the side navigation
$("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
    $("body").toggleClass("sidebar-toggled");
    var $sidebarAnchor = $(".sidebar");
    $sidebarAnchor.toggleClass("toggled");
    if ($sidebarAnchor.hasClass("toggled")) {
        $('.sidebar .collapse').collapse('hide');
    }

});

// Close any open menu accordions when window is resized below 768px
$(window).resize(function () {
    if ($(window).width() < 768) {
        $('.sidebar .collapse').collapse('hide');
    }

});

// Prevent the content wrapper from scrolling when the fixed side navigation hovered over
$('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
    if ($(window).width() > 768) {
        var e0 = e.originalEvent,
            delta = e0.wheelDelta || -e0.detail;
        this.scrollTop += (delta < 0 ? 1 : -1) * 30;
        e.preventDefault();
    }
});

// Scroll to top button appear
$(document).on('scroll', function () {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
        $('.scroll-to-top').fadeIn();
    } else {
        $('.scroll-to-top').fadeOut();
    }
});

// Smooth scrolling using jQuery easing
$(document).on('click', 'a.scroll-to-top', function (e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
        scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
});


// Card Table中全选所有行
$(document).on('click', '.card table thead input[type=checkbox].check-all', function (e) {
    var $anchor = $(this);
    var $checkboxes = $anchor.closest('table').find('tbody tr td input[type=checkbox]');
    $.each($checkboxes, function (index, value) {
        $(value).prop("checked", !$(value).prop("checked"));
    });
});

// 机器别名的自动生成
$(document).on('input', 'input[type=text].transliterate', function (e) {
    var inputValue = $(this).val();
    var $parentAnchor = $(this).closest("form");
    var alias = slugify(inputValue).replace(/-/ig, '_');

    $parentAnchor.find("span.text-alias").text(alias);

    $parentAnchor.find("input.input-alias").val(alias);

    $parentAnchor.find("a.js-modify-alias").removeClass('d-none');

});

// 编辑机器别名
$(document).on('click', 'a.js-modify-alias', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $parentAnchor = $(this).closest('form');

    $parentAnchor.find('div.text-alias-wrapper').addClass('d-none');
    $parentAnchor.find('div.input-alias-wrapper').removeClass('d-none');
    $parentAnchor.find('input[type=hidden].input-alias').attr('type', 'text');

});


// 选择字段类型 Select change事件
$(document).on('change', 'div.select-field-form select', function (e) {
    var $parentAnchor = $(this).closest('div.form-row');
    var $divWrapper = $parentAnchor.closest('div.select-field-form');

    $divWrapper.find('div.field-info').removeClass('d-none');
});

// 编辑字段设置，选择字段的数量
$(document).on('change', 'select#select_field_limit', function (e) {
    var $parentAnchor = $(this).closest('div.select-field-limit');
    var $inputAnchor = $parentAnchor.find('input[type=number].input-field-limit');
    var $inputWrapper = $parentAnchor.find('div#select_field_limit_input_wrapper');

    if ($(this).val() === '0') {
        $inputAnchor.attr("value", '0');
        $inputAnchor.addClass('d-none');
        $inputWrapper.toggleClass('d-none');
    }
    if ($(this).val() === 'limit') {
        $inputAnchor.attr("value", '1');
        $inputAnchor.removeClass('d-none');
        $inputWrapper.toggleClass('d-none');
    }
});

//允许的扩展名分割符统一替换成小写逗号（,）
$(document).on('input', 'input[type=text].input-allow-extension-name', function (e) {
    var $inputValue = $(this).val();

    var relacedValue = $inputValue.replace(/[，| |\-|_|\－]/, ',');

    $(this).val(relacedValue);
});

//取消a.js-modify-field-weight a.js-modify-term默认行为
$(document).on('click', 'a.js-sortable', function (e) {
    e.preventDefault();
    e.stopPropagation();
});

//Bootstrap Select 搜索框样式修改
$(document).ready(function () {
    $('.bootstrap-select .bs-searchbox input').addClass('form-control-sm');
});

//编辑菜单项页面 左侧添加菜单项 tab 样式修改
$('.js-add-menu-item a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var $newActiveTab = $(e.target); // newly activated tab
    var $previousActiveTab = $(e.relatedTarget); // previous active tab

    $newActiveTab.addClass('bg-gray-100 border-bottom-gray-100');

    $previousActiveTab.removeClass('bg-gray-100 border-bottom-gray-100');
});

//编辑菜单项页面，展开收缩菜单项，点击事件
$('.dd-item .accordion-arrow').on('click', function (e) {
    var $collapse = $(e.target).closest('.dd-item').find('.collapse');
    $collapse.collapse('toggle');
});

//评论列表页评论操作列表显示
$('td.js-comment-td').hover(function (e) {
    var $tdEl = $(e.target);
    $tdEl.find('.comment-option').css('left', '0px');
}, function (e) {
    var $tdEl = $(e.target);
    $tdEl.find('.comment-option').css('left', '-9999em');
});

//编辑评论页面修改提交时间事件
$('button#edit_comment_time_btn').on('click', function (e) {
    var $timeInput = $(e.target).closest('.comment-time').find('input#comment_time');
    $timeInput.removeAttr('disabled');
    $(e.target).hide();
});

//添加编辑内容页面表单 添加表单按钮事件
$('button.form-field-add-item').on('click', function (e) {
    var $formPrototypeWrapper = $(this).parent('.form-group').find('div.prototype-wrapper');
    var $protoTypeHtml = $formPrototypeWrapper.data('prototype');
    var $index = $formPrototypeWrapper.children().length;

    var $formRowHtml = $protoTypeHtml.replace(/__name__/g, $index);

    $formPrototypeWrapper.append($formRowHtml);
    console.log($protoTypeHtml, $index);
});

//如果ajax出错，添加错误消息
function createFormErrorMessage(errorMessage) {
    return '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
        '        <i class="fas fa-info-circle"></i>' +
        '        <span class="small text-muted">' + errorMessage + '</span>' +
        '        <button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '            <span aria-hidden="true">×</span>' +
        '        </button>' +
        '    </div>';
}

//文件 图像字段文件上传
function fieldUploadFile(element) {
    $(element).next('.custom-file-label').html(element.files[0].name);

    var formData = new FormData();
    formData.append("file", element.files[0]);
    formData.append("field_alias", $(element).data('field-alias'));

    $.ajax({
        url: Routing.generate('teebb_file_upload'),
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
    }).done(function (data) {
        $(element).closest('div.custom-file').addClass('d-none');
        $(element).attr('disabled', 'disabled');

        var $parentAnchor = $(element).closest('fieldset.form-group');

        var $fileOtherInfo = $parentAnchor.find('.file-other-info-wrapper');

        $fileOtherInfo.find('div.file-show').removeClass('d-none');

        //如果是文件
        $fileOtherInfo.find('a.file').attr('href', data.url);
        $fileOtherInfo.find('a.file').append(data.file.originFileName);
        //如果是图像
        $fileOtherInfo.find('img.file').attr('src', data.hasOwnProperty('thumbnailUrl') ? data.thumbnailUrl : data.url);
        $fileOtherInfo.find('img.file').attr('alt', data.file.originFileName);

        $fileOtherInfo.find('button.btn-file-delete').attr('data-file-id', data.file.id);

        $parentAnchor.find('input.target-file-id-input').val(data.file.id);
    }).fail(function (jqXHR) {
        $(element).closest('div.file-upload-file-wrapper').prepend(createFormErrorMessage(jqXHR.responseJSON.detail));
    });
}

//文件 图像字段文件删除
function fieldFileDelete(element) {
    var formData = new FormData();
    formData.append("id", $(element).data('file-id'));

    $.ajax({
        url: Routing.generate('teebb_file_delete'),
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
    }).done(function (data) {
        //获取prototype添加新子元素
        var $parentAnchor = $(element).closest('div.prototype-wrapper');
        var $protoTypeHtml = $parentAnchor.data('prototype');

        //获取当前行
        var $itemWrapper = $(element).closest('div.file-upload-wrapper').parent('fieldset');
        //将新子元素插入到当前index
        var $currIndex = $itemWrapper.index();

        var $formRowHtml = $protoTypeHtml.replace(/__name__/g, $currIndex);

        $parentAnchor.insertAt($currIndex, $formRowHtml);

        //删除当前子元素
        $itemWrapper.remove();

    }).fail(function (jqXHR) {
        $(element).closest('fieldset').prepend(createFormErrorMessage(jqXHR.responseJSON.detail));
    });
}

//显示隐藏摘要按钮文字处理
$('a.toggle-show-summary').on('click', function (e) {
    var $boolShow = $(this).attr('aria-expanded');
    console.log($boolShow, $boolShow === "true", $boolShow === "false");
    var $showText = $(this).data('show-text');
    var $hideText = $(this).data('hide-text');
    var $span = $(this).find('span.font-italic');
    if ($boolShow === "true" || $boolShow) {
        $span.text($showText);
    }
    if ($boolShow === "false" || !$boolShow) {
        $span.text($hideText);
    }
});