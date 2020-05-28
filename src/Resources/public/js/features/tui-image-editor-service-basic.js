/**
 * basic.js
 * @author NHN Ent. FE Development Team <dl_javascript@nhnent.com>
 * @fileoverview
 */
'use strict';

var supportingFileAPI = !!(window.File && window.FileList && window.FileReader);
var rImageType = /data:(image\/.+);base64,/;
var shapeOptions = {};
var shapeType;
var activeObjectId;

// Buttons
var $btns = $('.menu-item');
var $btnsActivatable = $btns.filter('.activatable');
var $inputImage = $('#input-image-file');
var $btnDownload = $('#btn-download');

var $btnCancel = $('#btn-cancel');

var $btnUndo = $('#btn-undo');
var $btnRedo = $('#btn-redo');
var $btnReset = $('#btn-reset');

var $btnClearObjects = $('#btn-clear-objects');
var $btnRemoveActiveObject = $('#btn-remove-active-object');
var $btnCrop = $('#btn-crop');
var $btnFlip = $('#btn-flip');
var $btnRotation = $('#btn-rotation');
var $btnDrawLine = $('#btn-draw-line');
var $btnDrawShape = $('#btn-draw-shape');
var $btnApplyCrop = $('#btn-apply-crop');
var $btnCancelCrop = $('#btn-cancel-crop');
var $btnFlipX = $('#btn-flip-x');
var $btnFlipY = $('#btn-flip-y');
var $btnResetFlip = $('#btn-reset-flip');
var $btnRotateClockwise = $('#btn-rotate-clockwise');
var $btnRotateCounterClockWise = $('#btn-rotate-counter-clockwise');
var $btnText = $('#btn-text');
var $btnTextStyle = $('.btn-text-style');
var $btnAddIcon = $('#btn-add-icon');
var $btnRegisterIcon = $('#btn-register-icon');
var $btnMaskFilter = $('#btn-mask-filter');
var $btnImageFilter = $('#btn-image-filter');
var $btnLoadMaskImage = $('#input-mask-image-file');
var $btnApplyMask = $('#btn-apply-mask');
var $btnClose = $('.menu-close');

// Input etc.
var $inputRotationRange = $('#input-rotation-range');
var $inputBrushWidthRange = $('#input-brush-width-range');
var $inputFontSizeRange = $('#input-font-size-range');
var $inputStrokeWidthRange = $('#input-stroke-width-range');
var $inputCheckTransparent = $('#input-check-transparent');
var $inputCheckGrayscale = $('#input-check-grayscale');
var $inputCheckInvert = $('#input-check-invert');
var $inputCheckSepia = $('#input-check-sepia');
var $inputCheckSepia2 = $('#input-check-sepia2');
var $inputCheckBlur = $('#input-check-blur');
var $inputCheckSharpen = $('#input-check-sharpen');
var $inputCheckEmboss = $('#input-check-emboss');
var $inputCheckRemoveWhite = $('#input-check-remove-white');
var $inputRangeRemoveWhiteThreshold = $('#input-range-remove-white-threshold');
var $inputRangeRemoveWhiteDistance = $('#input-range-remove-white-distance');
var $inputCheckBrightness = $('#input-check-brightness');
var $inputRangeBrightnessValue = $('#input-range-brightness-value');
var $inputCheckNoise = $('#input-check-noise');
var $inputRangeNoiseValue = $('#input-range-noise-value');
var $inputCheckGradientTransparency = $('#input-check-gradient-transparancy');
var $inputRangeGradientTransparencyValue = $('#input-range-gradient-transparency-value');
var $inputCheckPixelate = $('#input-check-pixelate');
var $inputRangePixelateValue = $('#input-range-pixelate-value');
var $inputCheckTint = $('#input-check-tint');
var $inputRangeTintOpacityValue = $('#input-range-tint-opacity-value');
var $inputCheckMultiply = $('#input-check-multiply');
var $inputCheckBlend = $('#input-check-blend');
var $inputCheckColorFilter = $('#input-check-color-filter');
var $inputRangeColorFilterValue = $('#input-range-color-filter-value');

// Sub menus
var $displayingSubMenu = $();
var $cropSubMenu = $('#crop-sub-menu');
var $flipSubMenu = $('#flip-sub-menu');
var $rotationSubMenu = $('#rotation-sub-menu');
var $freeDrawingSubMenu = $('#free-drawing-sub-menu');
var $drawLineSubMenu = $('#draw-line-sub-menu');
var $drawShapeSubMenu = $('#draw-shape-sub-menu');
var $textSubMenu = $('#text-sub-menu');
var $iconSubMenu = $('#icon-sub-menu');
var $filterSubMenu = $('#filter-sub-menu');
var $imageFilterSubMenu = $('#image-filter-sub-menu');

// Select line type
var $selectLine = $('[name="select-line-type"]');

// Select shape type
var $selectShapeType = $('[name="select-shape-type"]');

// Select color of shape type
var $selectColorType = $('[name="select-color-type"]');

//Select blend type
var $selectBlendType = $('[name="select-blend-type"]');

// Image editor
var imageEditor = new tui.ImageEditor('.tui-image-editor', {
    cssMaxWidth: 700,
    cssMaxHeight: 500,
    selectionStyle: {
        cornerSize: 20,
        rotatingPointOffset: 70
    }
});

// Color picker for free drawing
var brushColorpicker = tui.colorPicker.create({
    container: $('#tui-brush-color-picker')[0],
    color: '#000000'
});

// Color picker for text palette
var textColorpicker = tui.colorPicker.create({
    container: $('#tui-text-color-picker')[0],
    color: '#000000'
});

// Color picker for shape
var shapeColorpicker = tui.colorPicker.create({
    container: $('#tui-shape-color-picker')[0],
    color: '#000000'
});

// Color picker for icon
var iconColorpicker = tui.colorPicker.create({
    container: $('#tui-icon-color-picker')[0],
    color: '#000000'
});

var tintColorpicker = tui.colorPicker.create({
    container: $('#tui-tint-color-picker')[0],
    color: '#000000'
});

var multiplyColorpicker = tui.colorPicker.create({
    container: $('#tui-multiply-color-picker')[0],
    color: '#000000'
});

var blendColorpicker = tui.colorPicker.create({
    container: $('#tui-blend-color-picker')[0],
    color: '#00FF00'
});

// Common global functions
// HEX to RGBA
function hexToRGBa(hex, alpha) {
    var r = parseInt(hex.slice(1, 3), 16);
    var g = parseInt(hex.slice(3, 5), 16);
    var b = parseInt(hex.slice(5, 7), 16);
    var a = alpha || 1;

    return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';
}

function base64ToBlob(data) {
    var mimeString = '';
    var raw, uInt8Array, i, rawLength;

    raw = data.replace(rImageType, function(header, imageType) {
        mimeString = imageType;

        return '';
    });

    raw = atob(raw);
    rawLength = raw.length;
    uInt8Array = new Uint8Array(rawLength); // eslint-disable-line

    for (i = 0; i < rawLength; i += 1) {
        uInt8Array[i] = raw.charCodeAt(i);
    }

    return new Blob([uInt8Array], {type: mimeString});
}

function resizeEditor() {
    var $editor = $('.tui-image-editor');
    var $container = $('.tui-image-editor-canvas-container');
    var height = parseFloat($container.css('max-height'));

    $editor.height(height);
}

function getBrushSettings() {
    var brushWidth = $inputBrushWidthRange.val();
    var brushColor = brushColorpicker.getColor();

    return {
        width: brushWidth,
        color: hexToRGBa(brushColor, 0.5)
    };
}

function activateShapeMode() {
    if (imageEditor.getDrawingMode() !== 'SHAPE') {
        imageEditor.stopDrawingMode();
        imageEditor.startDrawingMode('SHAPE');
    }
}

function activateIconMode() {
    imageEditor.stopDrawingMode();
}

function activateTextMode() {
    if (imageEditor.getDrawingMode() !== 'TEXT') {
        imageEditor.stopDrawingMode();
        imageEditor.startDrawingMode('TEXT');
    }
}

function setTextToolbar(obj) {
    var fontSize = obj.fontSize;
    var fontColor = obj.fill;

    $inputFontSizeRange.val(fontSize);
    textColorpicker.setColor(fontColor);
}

function setIconToolbar(obj) {
    var iconColor = obj.fill;

    iconColorpicker.setColor(iconColor);
}

function setShapeToolbar(obj) {
    var strokeColor, fillColor, isTransparent;
    var colorType = $selectColorType.val();

    if (colorType === 'stroke') {
        strokeColor = obj.stroke;
        isTransparent = (strokeColor === 'transparent');

        if (!isTransparent) {
            shapeColorpicker.setColor(strokeColor);
        }
    } else if (colorType === 'fill') {
        fillColor = obj.fill;
        isTransparent = (fillColor === 'transparent');

        if (!isTransparent) {
            shapeColorpicker.setColor(fillColor);
        }
    }

    $inputCheckTransparent.prop('checked', isTransparent);
    $inputStrokeWidthRange.val(obj.strokeWidth);
}

function showSubMenu(type) {
    var $submenu;

    switch (type) {
        case 'shape':
            $submenu = $drawShapeSubMenu;
            break;
        case 'icon':
            $submenu = $iconSubMenu;
            break;
        case 'text':
            $submenu = $textSubMenu;
            break;
        default:
            $submenu = 0;
    }

    $displayingSubMenu.hide();
    $displayingSubMenu = $submenu.show();
}

function applyOrRemoveFilter(applying, type, options) {
    if (applying) {
        imageEditor.applyFilter(type, options).then(function(result){
            console.log(result);
        });
    } else {
        imageEditor.removeFilter(type);
    }
}

function reset(){
    imageEditor.undo().then(function () {
        if(!imageEditor.isEmptyUndoStack()){
            reset();
        }
    },function () {
    });
    $btnReset.addClass("disabled");
}

// Attach image editor custom events
imageEditor.on({
    objectAdded: function(objectProps) {
        console.info(objectProps);
    },
    undoStackChanged: function(length) {
        if (length) {
            $btnUndo.removeClass('disabled');
            $btnReset.removeClass('disabled');
        } else {
            $btnUndo.addClass('disabled');
            $btnReset.addClass('disabled');
        }
        resizeEditor();
    },
    redoStackChanged: function(length) {
        if (length) {
            $btnRedo.removeClass('disabled');
        } else {
            $btnRedo.addClass('disabled');
        }
        resizeEditor();
    },
    objectScaled: function(obj) {
        if (obj.type === 'text') {
            $inputFontSizeRange.val(obj.fontSize);
        }
    },
    addText: function(pos) {
        imageEditor.addText('Double Click', {
            position: pos.originPosition
        }).then(function(objectProps) {
            console.log(objectProps);
        });
    },
    objectActivated: function(obj) {
        activeObjectId = obj.id;
        if (obj.type === 'rect' || obj.type === 'circle' || obj.type === 'triangle') {
            showSubMenu('shape');
            setShapeToolbar(obj);
            activateShapeMode();
        } else if (obj.type === 'icon') {
            showSubMenu('icon');
            setIconToolbar(obj);
            activateIconMode();
        } else if (obj.type === 'text') {
            showSubMenu('text');
            setTextToolbar(obj);
            activateTextMode();
        }
    },
    mousedown: function(event, originPointer) {
        if ($imageFilterSubMenu.is(':visible') && imageEditor.hasFilter('colorFilter')) {
            imageEditor.applyFilter('colorFilter', {
                x: parseInt(originPointer.x, 10),
                y: parseInt(originPointer.y, 10)
            });
        }
    }
});

// Attach button click event listeners
$btns.on('click', function() {
    $btnsActivatable.removeClass('active');
});

$btnsActivatable.on('click', function() {
    $(this).addClass('active');
});

$btnUndo.on('click', function() {
    $displayingSubMenu.hide();

    if (!$(this).hasClass('disabled')) {
        imageEditor.undo().then(function () {

        },function () {

        });
    }
});

$btnRedo.on('click', function() {
    $displayingSubMenu.hide();

    if (!$(this).hasClass('disabled')) {
        imageEditor.redo().then(function () {
            
        },function () {
            
        });
    }
});

$btnReset.on('click', function() {
    $displayingSubMenu.hide();

    if (!$(this).hasClass('disabled')) {
        reset();
    }
});

$btnCancel.on('click', function () {
    reset();
});

$btnClearObjects.on('click', function() {
    $displayingSubMenu.hide();
    imageEditor.clearObjects();
});

$btnRemoveActiveObject.on('click', function() {
    // $displayingSubMenu.hide();
    imageEditor.removeObject(activeObjectId);
});

$btnCrop.on('click', function() {
    imageEditor.startDrawingMode('CROPPER');
    $displayingSubMenu.hide();
    $displayingSubMenu = $cropSubMenu.show();
});

$btnFlip.on('click', function() {
    imageEditor.stopDrawingMode();
    $displayingSubMenu.hide();
    $displayingSubMenu = $flipSubMenu.show();
});

$btnRotation.on('click', function() {
    imageEditor.stopDrawingMode();
    $displayingSubMenu.hide();
    $displayingSubMenu = $rotationSubMenu.show();
});

$btnClose.on('click', function() {
    imageEditor.stopDrawingMode();
    $displayingSubMenu.hide();
});

$btnApplyCrop.on('click', function() {
    imageEditor.crop(imageEditor.getCropzoneRect()).then(function(){
        imageEditor.stopDrawingMode();
        resizeEditor();
    });
});

$btnCancelCrop.on('click', function() {
    imageEditor.stopDrawingMode();
    $displayingSubMenu.hide();
});

$btnFlipX.on('click', function() {
    imageEditor.flipX().then(function(status) {
        console.log('flipX: ', status.flipX);
        console.log('flipY: ', status.flipY);
        console.log('angle: ', status.angle);
    });
});

$btnFlipY.on('click', function() {
    imageEditor.flipY().then(function(status) {
        console.log('flipX: ', status.flipX);
        console.log('flipY: ', status.flipY);
        console.log('angle: ', status.angle);
    });
});

$btnResetFlip.on('click', function() {
    imageEditor.resetFlip().then(function(status) {
        console.log('flipX: ', status.flipX);
        console.log('flipY: ', status.flipY);
        console.log('angle: ', status.angle);
    });
});

$btnRotateClockwise.on('click', function() {
    imageEditor.rotate(30);
});

$btnRotateCounterClockWise.on('click', function() {
    imageEditor.rotate(-30);
});

$inputRotationRange.on('mousedown', function() {
    var changeAngle = function() {
        imageEditor.setAngle(parseInt($inputRotationRange.val(), 10)).catch(function() {});
    };
    $(document).on('mousemove', changeAngle);
    $(document).on('mouseup', function stopChangingAngle() {
        $(document).off('mousemove', changeAngle);
        $(document).off('mouseup', stopChangingAngle);
    });
});

$inputRotationRange.on('change', function() {
    imageEditor.setAngle(parseInt($inputRotationRange.val(), 10)).catch(function() {});
});

$inputBrushWidthRange.on('change', function() {
    imageEditor.setBrush({width: parseInt(this.value, 10)});
});

$inputImage.on('change', function(event) {
    var file;

    if (!supportingFileAPI) {
        alert('This browser does not support file-api');
    }

    file = event.target.files[0];
    imageEditor.loadImageFromFile(file).then(function(result) {
        console.log(result);
        imageEditor.clearUndoStack();
    });
});

$btnDownload.on('click', function() {
    var imageName = imageEditor.getImageName();
    var dataURL = imageEditor.toDataURL();
    console.log(imageName,dataURL);
    var blob, type, w;

    if (supportingFileAPI) {
        blob = base64ToBlob(dataURL);
        type = blob.type.split('/')[1];
        if (imageName.split('.').pop() !== type) {
            imageName += '.' + type;
        }

        // Library: FileSaver - saveAs
        saveAs(blob, imageName); // eslint-disable-line
    } else {
        alert('This browser needs a file-server');
        w = window.open();
        w.document.body.innerHTML = '<img src=' + dataURL + '>';
    }
});

// control draw line mode
$btnDrawLine.on('click', function() {
    imageEditor.stopDrawingMode();
    $displayingSubMenu.hide();
    $displayingSubMenu = $drawLineSubMenu.show();
    $selectLine.eq(0).change();
});

$selectLine.on('change', function() {
    var mode = $(this).val();
    var settings = getBrushSettings();

    imageEditor.stopDrawingMode();
    if (mode === 'freeDrawing') {
        imageEditor.startDrawingMode('FREE_DRAWING', settings);
    } else {
        imageEditor.startDrawingMode('LINE_DRAWING', settings);
    }
});

brushColorpicker.on('selectColor', function(event) {
    imageEditor.setBrush({
        color: hexToRGBa(event.color, 0.5)
    });
});

// control draw shape mode
$btnDrawShape.on('click', function() {
    showSubMenu('shape');

    // step 1. get options to draw shape from toolbar
    shapeType = $('[name="select-shape-type"]:checked').val();

    shapeOptions.stroke = '#000000';
    shapeOptions.fill = '#ffffff';

    shapeOptions.strokeWidth = Number($inputStrokeWidthRange.val());

    // step 2. set options to draw shape
    imageEditor.setDrawingShape(shapeType, shapeOptions);

    // step 3. start drawing shape mode
    activateShapeMode();
});

$selectShapeType.on('change', function() {
    shapeType = $(this).val();

    imageEditor.setDrawingShape(shapeType);
});

$inputCheckTransparent.on('change', function() {
    var colorType = $selectColorType.val();
    var isTransparent = $(this).prop('checked');
    var color;

    if (!isTransparent) {
        color = shapeColorpicker.getColor();
    } else {
        color = 'transparent';
    }

    if (colorType === 'stroke') {
        imageEditor.changeShape(activeObjectId, {
            stroke: color
        });
    } else if (colorType === 'fill') {
        imageEditor.changeShape(activeObjectId, {
            fill: color
        });
    }

    imageEditor.setDrawingShape(shapeType, shapeOptions);
});

shapeColorpicker.on('selectColor', function(event) {
    var colorType = $selectColorType.val();
    var isTransparent = $inputCheckTransparent.prop('checked');
    var color = event.color;

    if (isTransparent) {
        return;
    }

    if (colorType === 'stroke') {
        imageEditor.changeShape(activeObjectId, {
            stroke: color
        });
    } else if (colorType === 'fill') {
        imageEditor.changeShape(activeObjectId, {
            fill: color
        });
    }

    imageEditor.setDrawingShape(shapeType, shapeOptions);
});

$inputStrokeWidthRange.on('change', function() {
    var strokeWidth = Number($(this).val());

    imageEditor.changeShape(activeObjectId, {
        strokeWidth: strokeWidth
    });

    imageEditor.setDrawingShape(shapeType, shapeOptions);
});

// control text mode
$btnText.on('click', function() {
    showSubMenu('text');
    activateTextMode();
});

$inputFontSizeRange.on('change', function() {
    imageEditor.changeTextStyle(activeObjectId, {
        fontSize: parseInt(this.value, 10)
    });
});

$btnTextStyle.on('click', function(e) { // eslint-disable-line
    var styleType = $(this).attr('data-style-type');
    var styleObj;

    e.stopPropagation();

    switch (styleType) {
        case 'b':
            styleObj = {fontWeight: 'bold'};
            break;
        case 'i':
            styleObj = {fontStyle: 'italic'};
            break;
        case 'u':
            styleObj = {textDecoration: 'underline'};
            break;
        case 'l':
            styleObj = {textAlign: 'left'};
            break;
        case 'c':
            styleObj = {textAlign: 'center'};
            break;
        case 'r':
            styleObj = {textAlign: 'right'};
            break;
        default:
            styleObj = {};
    }

    imageEditor.changeTextStyle(activeObjectId, styleObj);
});

textColorpicker.on('selectColor', function(event) {
    imageEditor.changeTextStyle(activeObjectId, {
        'fill': event.color
    });
});

// control icon
$btnAddIcon.on('click', function() {
    showSubMenu('icon');
    activateIconMode();
});

function onClickIconSubMenu(event) {
    var element = event.target || event.srcElement;
    var iconType = $(element).attr('data-icon-type');

    if (iconType === undefined) {
        iconType = $(element).closest('.icon-text').attr('data-icon-type');
    }
    
    imageEditor.once('mousedown', function(e, originPointer) {
        imageEditor.addIcon(iconType, {
            left: originPointer.x,
            top: originPointer.y
        }).then(function(objectProps) {
            // console.log(objectProps);
        });
    });
}

imageEditor.registerIcons({
    star: 'M175.785,58.457c0-2.01-1.52-3.259-4.564-3.748l-40.897-5.947l-18.331-37.07c-1.031-2.227-2.363-3.34-3.992-3.34  s-2.96,1.113-3.992,3.34l-18.332,37.07l-40.899,5.947c-3.041,0.489-4.562,1.738-4.562,3.748c0,1.141,0.679,2.445,2.037,3.911  l29.656,28.841l-7.006,40.736c-0.109,0.761-0.163,1.305-0.163,1.63c0,1.141,0.285,2.104,0.855,2.893  c0.57,0.788,1.425,1.181,2.566,1.181c0.978,0,2.064-0.324,3.259-0.977L108,117.443l36.583,19.229  c1.142,0.652,2.228,0.977,3.258,0.977c1.089,0,1.916-0.392,2.486-1.181c0.569-0.788,0.854-1.752,0.854-2.893  c0-0.706-0.027-1.249-0.082-1.63l-7.007-40.736l29.574-28.841C175.08,60.956,175.785,59.652,175.785,58.457z',
    heart: 'M170.652,20.532c-6.898-6.736-16.43-10.103-28.598-10.103c-3.367,0-6.803,0.584-10.306,1.751  c-3.504,1.168-6.763,2.744-9.776,4.726c-3.015,1.982-5.607,3.843-7.781,5.581c-2.172,1.738-4.236,3.585-6.191,5.54  c-1.956-1.955-4.02-3.802-6.192-5.54c-2.173-1.738-4.766-3.598-7.781-5.581c-3.015-1.983-6.274-3.558-9.777-4.726  c-3.503-1.167-6.939-1.751-10.306-1.751c-12.167,0-21.699,3.368-28.597,10.103S35,36.609,35,48.558  c0,3.639,0.639,7.388,1.915,11.243c1.276,3.856,2.73,7.143,4.359,9.858c1.629,2.715,3.476,5.364,5.54,7.944s3.572,4.358,4.522,5.336  c0.95,0.977,1.697,1.683,2.24,2.117l50.839,49.047c0.978,0.979,2.173,1.468,3.585,1.468s2.607-0.489,3.585-1.466l50.758-48.885  C174.781,72.783,181,60.562,181,48.558C181,36.609,177.55,27.267,170.652,20.532z',
    cut: 'M0.405,78.262c5.988-3.438,10.378-8.914,12.047-15.029c1.137-4.161,0.925-8.384-0.574-12.064l16.805-9.61    C64.744,59.626,75.139,65.036,77.49,66.379c7.653,4.379,16.153-2.808,16.511-3.116l2.424-2.087L45.406,32.001L96.43,2.83    l-2.424-2.089C93.649,0.43,85.15-6.76,77.49-2.381c-4.773,2.735-34.888,17.854-48.805,24.827l-16.807-9.61    c1.5-3.683,1.713-7.915,0.576-12.061c-1.662-6.116-6.057-11.59-12.047-15.036c-11.287-6.458-24.968-3.851-30.498,5.812    c-2.399,4.179-2.959,9.204-1.606,14.197c3.495,11.063,11.979,16.524,11.979,16.524l8.112,1.542l0.755,0.142    c0.18,0.031,0.373,0.059,0.445,0.065c12.776,1.902,20.438,5.134,24.952,7.985c-4.506,2.852-12.154,6.078-24.841,7.97    c-0.18,0.014-0.35,0.039-0.733,0.098l-0.774,0.092l-0.085,0.082c-2.682,0.568-5.288,1.568-7.779,2.983    c-0.347,0.204-0.695,0.415-1.038,0.63l-1.118,0.741c-4.875,3.438-8.423,8.307-9.878,13.662c-0.485,1.767-0.729,3.559-0.729,5.314    c0,3.196,0.787,6.184,2.338,8.881C-24.563,82.115-10.882,84.719,0.405,78.262z M-14.869,12.415    c-3.743-2.149-6.559-5.586-7.546-9.199c-0.257-0.942-0.386-1.877-0.386-2.78c0-1.495,0.36-2.875,1.064-4.109    c2.891-5.044,10.677-6.043,17.358-2.224c3.799,2.18,6.546,5.536,7.542,9.199c0.679,2.464,0.451,4.909-0.664,6.882    C-0.408,15.239-8.191,16.239-14.869,12.415z M-21.741,67.664c-0.7-1.225-1.064-2.604-1.064-4.102c0-0.896,0.131-1.831,0.386-2.779    c0.981-3.606,3.807-7.05,7.546-9.194c6.678-3.818,14.468-2.82,17.358,2.227c1.132,1.981,1.345,4.396,0.672,6.896    c-0.994,3.659-3.742,7.012-7.535,9.188C-11.062,73.722-18.855,72.715-21.741,67.664z',
    bubble: 'M200.401,10.445H40.73c-16.534,0-29.59,10.725-29.59,25.148v95.762c0,14.423,13.056,27.09,29.59,27.09h10.41V201  l69.425-42.555h79.835c16.534,0,29.739-12.667,29.739-27.09V35.593C230.14,21.17,216.934,10.445,200.401,10.445z',
    check: 'M66.524,139.417L7.653,80.546c-3.536-3.537-3.536-9.271,0-12.809l12.808-12.809c3.537-3.537,9.272-3.537,12.809,0  l39.657,39.659l84.944-84.943c3.537-3.537,9.271-3.537,12.808,0l12.808,12.809c3.537,3.537,3.537,9.271,0,12.809L79.332,139.417  C75.795,142.954,70.061,142.954,66.524,139.417L66.524,139.417z',
});

$btnRegisterIcon.on('click', function() {
    $iconSubMenu.find('.menu-item').eq(3).after(
        '<li id="customArrow" class="menu-item icon-text" data-icon-type="customArrow">↑</li>'
    );

    imageEditor.registerIcons({
        customArrow: 'M 60 0 L 120 60 H 90 L 75 45 V 180 H 45 V 45 L 30 60 H 0 Z',
    });

    $btnRegisterIcon.off('click');

    $iconSubMenu.on('click', '#customArrow', onClickIconSubMenu);
});

$iconSubMenu.on('click', '.icon-text', onClickIconSubMenu);

iconColorpicker.on('selectColor', function(event) {
    imageEditor.changeIconColor(activeObjectId, event.color);
});

// control mask filter
$btnMaskFilter.on('click', function() {
    imageEditor.stopDrawingMode();
    $displayingSubMenu.hide();

    $displayingSubMenu = $filterSubMenu.show();
});

$btnImageFilter.on('click', function() {
    var filters = {
        'grayscale': $inputCheckGrayscale,
        'invert': $inputCheckInvert,
        'sepia': $inputCheckSepia,
        'sepia2': $inputCheckSepia2,
        'blur': $inputCheckBlur,
        'shapren': $inputCheckSharpen,
        'emboss': $inputCheckEmboss,
        'removeWhite': $inputCheckRemoveWhite,
        'brightness': $inputCheckBrightness,
        'noise': $inputCheckNoise,
        'gradientTransparency': $inputCheckGradientTransparency,
        'pixelate': $inputCheckPixelate,
        'tint': $inputCheckTint,
        'multiply': $inputCheckMultiply,
        'blend': $inputCheckBlend,
        'colorFilter': $inputCheckColorFilter
    };

    tui.util.forEach(filters, function($value, key) {
        $value.prop('checked', imageEditor.hasFilter(key));
    });
    $displayingSubMenu.hide();

    $displayingSubMenu = $imageFilterSubMenu.show();
});

$btnLoadMaskImage.on('change', function() {
    var file;
    var imgUrl;

    if (!supportingFileAPI) {
        alert('This browser does not support file-api');
    }

    file = event.target.files[0];

    if (file) {
        imgUrl = URL.createObjectURL(file);

        imageEditor.loadImageFromURL(imageEditor.toDataURL(), 'FilterImage').then(function() {
            imageEditor.addImageObject(imgUrl).then(function(objectProps) {
                URL.revokeObjectURL(file);
                console.log(objectProps);
            });
        });
    }
});

$btnApplyMask.on('click', function() {
    imageEditor.applyFilter('mask', {
        maskObjId: activeObjectId
    }).then(function(result) {
        console.log(result);
    });
});

$inputCheckGrayscale.on('change', function() {
    applyOrRemoveFilter(this.checked, 'Grayscale', null);
});

$inputCheckInvert.on('change', function() {
    applyOrRemoveFilter(this.checked, 'Invert', null);
});

$inputCheckSepia.on('change', function() {
    applyOrRemoveFilter(this.checked, 'Sepia', null);
});

$inputCheckSepia2.on('change', function() {
    applyOrRemoveFilter(this.checked, 'Sepia2', null);
});

$inputCheckBlur.on('change', function() {
    applyOrRemoveFilter(this.checked, 'Blur', null);
});

$inputCheckSharpen.on('change', function() {
    applyOrRemoveFilter(this.checked, 'Sharpen', null);
});

$inputCheckEmboss.on('change', function() {
    applyOrRemoveFilter(this.checked, 'Emboss', null);
});

$inputCheckRemoveWhite.on('change', function() {
    applyOrRemoveFilter(this.checked, 'removeWhite', {
        threshold: parseInt($inputRangeRemoveWhiteThreshold.val(), 10),
        distance: parseInt($inputRangeRemoveWhiteDistance.val(), 10)
    });
});

$inputRangeRemoveWhiteThreshold.on('change', function() {
    applyOrRemoveFilter($inputCheckRemoveWhite.is(':checked'), 'removeWhite', {
        threshold: parseInt(this.value, 10)
    });
});

$inputRangeRemoveWhiteDistance.on('change', function() {
    applyOrRemoveFilter($inputCheckRemoveWhite.is(':checked'), 'removeWhite', {
        distance: parseInt(this.value, 10)
    });
});

$inputCheckBrightness.on('change', function() {
    applyOrRemoveFilter(this.checked, 'brightness', {
        brightness: parseInt($inputRangeBrightnessValue.val(), 10)
    });
});

$inputRangeBrightnessValue.on('change', function() {
    applyOrRemoveFilter($inputCheckBrightness.is(':checked'), 'brightness', {
        brightness: parseInt(this.value, 10)
    });
});

$inputCheckNoise.on('change', function() {
    applyOrRemoveFilter(this.checked, 'noise', {
        noise: parseInt($inputRangeNoiseValue.val(), 10)
    });
});

$inputRangeNoiseValue.on('change', function() {
    applyOrRemoveFilter($inputCheckNoise.is(':checked'), 'noise', {
        noise: parseInt(this.value, 10)
    });
});

$inputCheckGradientTransparency.on('change', function() {
    applyOrRemoveFilter(this.checked, 'gradientTransparency', {
        threshold: parseInt($inputRangeGradientTransparencyValue.val(), 10)
    });
});

$inputRangeGradientTransparencyValue.on('change', function() {
    applyOrRemoveFilter($inputCheckGradientTransparency.is(':checked'), 'gradientTransparency', {
        threshold: parseInt(this.value, 10)
    });
});

$inputCheckPixelate.on('change', function() {
    applyOrRemoveFilter(this.checked, 'pixelate', {
        blocksize: parseInt($inputRangePixelateValue.val(), 10)
    });
});

$inputRangePixelateValue.on('change', function() {
    applyOrRemoveFilter($inputCheckPixelate.is(':checked'), 'pixelate', {
        blocksize: parseInt(this.value, 10)
    });
});

$inputCheckTint.on('change', function() {
    applyOrRemoveFilter(this.checked, 'tint', {
        color: tintColorpicker.getColor(),
        opacity: parseFloat($inputRangeTintOpacityValue.val())
    });
});

tintColorpicker.on('selectColor', function(e) {
    applyOrRemoveFilter($inputCheckTint.is(':checked'), 'tint', {
        color: e.color
    });
});

$inputRangeTintOpacityValue.on('change', function() {
    applyOrRemoveFilter($inputCheckTint.is(':checked'), 'tint', {
        opacity: parseFloat($inputRangeTintOpacityValue.val())
    });
});

$inputCheckMultiply.on('change', function() {
    applyOrRemoveFilter(this.checked, 'multiply', {
        color: multiplyColorpicker.getColor()
    });
});

multiplyColorpicker.on('selectColor', function(e) {
    applyOrRemoveFilter($inputCheckMultiply.is(':checked'), 'multiply', {
        color: e.color
    });
});

$inputCheckBlend.on('change', function() {
    applyOrRemoveFilter(this.checked, 'blend', {
        color: blendColorpicker.getColor(),
        mode: $selectBlendType.val()
    });
});

blendColorpicker.on('selectColor', function(e) {
    applyOrRemoveFilter($inputCheckBlend.is(':checked'), 'blend', {
        color: e.color
    });
});

$selectBlendType.on('change', function() {
    applyOrRemoveFilter($inputCheckBlend.is(':checked'), 'blend', {
        mode: this.value
    });
});

$inputCheckColorFilter.on('change', function() {
    applyOrRemoveFilter(this.checked, 'colorFilter', {
        color: '#FFFFFF',
        threshold: $inputRangeColorFilterValue.val()
    });
});

$inputRangeColorFilterValue.on('change', function() {
    applyOrRemoveFilter($inputCheckColorFilter.is(':checked'), 'colorFilter', {
        threshold: this.value
    });
});

// IE9 Unselectable
$('.menu').on('selectstart', function() {
    return false;
});
