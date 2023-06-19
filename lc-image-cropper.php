<?php

/**
 * LC Image Cropper
 *
 * Handle cropping image
 *
 * PHP version 7.2.5
 *
 * @category Main_App
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

$pic = isset($_POST['imageDataURL']) ? $_POST['imageDataURL'] : die('ERROR: missing picture.');
?>

<div id="cropper" class="text-center">
    <div class="image-cropper-container mb-1">
        <img src="<?php echo $pic; ?>" alt="" class="cropper cropper-hidden" id="demo-cropper-image">
    </div>

    <div class="form-group demo-cropper-toolbar">
        <div class="btn-group btn-group-justified">

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="setDragMode" data-option="move" title="Move">
                    <span class="icon-move"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="setDragMode" data-option="crop" title="Crop">
                    <span class="icon-crop2"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                    <span class="icon-arrow-left13"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                    <span class="icon-arrow-right14"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                    <span class="icon-arrow-up13"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                    <span class="icon-arrow-down132"></span>
                </button>
            </div>

        </div>
    </div>
    

    <div class="form-group demo-cropper-toolbar">
        <div class="btn-group btn-group-justified">

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="zoom" data-option="0.1" title="Zoom In">
                    <span class="icon-zoomin3"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="zoom" data-option="-0.1" title="Zoom Out">
                    <span class="icon-zoomout3"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="rotate" data-option="-45" title="Rotate Left">
                    <span class="icon-rotate-ccw3"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="rotate" data-option="45" title="Rotate Right">
                    <span class="icon-rotate-cw3"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="scaleX" data-option="-1" title="Flip Horizontal">
                    <span class="icon-flip-vertical4"></span>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn bg-blue btn-icon" data-method="scaleY" data-option="-1" title="Flip Vertical">
                    <span class="icon-flip-vertical3"></span>
                </button>
            </div>

        </div>

        <div>
            <button class="btn btn-success mt-2" id="set_img" data-method="getCroppedCanvas">Set Image</button>
            <button class="btn btn-warning mt-2" id="change_img">Change Image</button>
        </div>
        
    </div>
</div>




<script>




// --------------------------------------------------------------------------------

// IMAGE CROPPER - COPIED FROM LIMITLESS JS file....MAY NOT NEED EVERYTHING IN HERE


var ImageCropper = function() {


    //
    // Setup module components
    //

    // Image cropper
    var _componentImageCropper = function() {
        if (!$().cropper) {
            console.warn('Warning - cropper.min.js is not loaded.');
            return;
        }
        
        // Default initialization
        $('.crop-basic').cropper();
        

        // Define variables
        var $cropper = $('.cropper'),
            $image = $('#demo-cropper-image'),
            $download = $('#download'),
            $dataX = $('#dataX'),
            $dataY = $('#dataY'),
            $dataHeight = $('#dataHeight'),
            $dataWidth = $('#dataWidth'),
            $dataScaleX = $('#dataScaleX'),
            $dataScaleY = $('#dataScaleY'),
            options = {
                aspectRatio: 1,
                strict: true,
                preview: '.preview',
                crop: function (e) {
                    $dataX.val(Math.round(e.detail.x));
                    $dataY.val(Math.round(e.detail.y));
                    $dataHeight.val(Math.round(e.detail.height));
                    $dataWidth.val(Math.round(e.detail.width));
                    $dataScaleX.val(e.detail.scaleX);
                    $dataScaleY.val(e.detail.scaleY);
                }
            };

        // Initialize cropper with options
        $cropper.cropper(options);


        //
        // Toolbar
        //

        $('.demo-cropper-toolbar').on('click', '[data-method]', function () {
            var $this = $(this),
                data = $this.data(),
                $target,
                result;

            if ($image.data('cropper') && data.method) {
                data = $.extend({}, data);

                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);

                    if (typeof data.option === 'undefined') {
                        data.option = JSON.parse($target.val());
                    }
                }

                result = $image.cropper(data.method, data.option, data.secondOption);

                switch (data.method) {
                    case 'scaleX':
                    case 'scaleY':
                        $(this).data('option', -data.option);
                    break;

                    case 'getCroppedCanvas':
                        if (result) {

                            //NOTE: we are saving the course as a png to take advantage of its transparent background
                            var img = result.toDataURL("image/png"); // CONVERTS IMG TO DATA STRING
                            $('#course_img_url').val(img); // SETS img AS VALUE OF HIDDEN INPUT
                            setImageAlert(); // FUNCTION TO LET USER KNOW IMAGE IS SET

                            // Init modal
                            //$('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

                            // Download image
                            //$download.attr('href', result.toDataURL('image/jpeg'));
                        }
                    break;
                }
            }
        });


        //
        // Aspect ratio
        //

        $('.demo-cropper-ratio').on('change', 'input[type=radio]', function () {
            options[$(this).attr('name')] = $(this).val();
            $image.cropper('destroy').cropper(options);
        });


        //
        // Switching modes
        //

        /*

        // Crop and clear
        var cropClear = document.querySelector('.clear-crop-switch');
        var cropClearInit = new Switchery(cropClear);
        cropClear.onchange = function() {
            if (cropClear.checked) {

                // Crop mode
                $cropper.cropper('crop');

                // Enable other options
                enableDisableInit.enable();
                destroyCreateInit.enable();
            }
            else {

                // Clear move
                $cropper.cropper('clear');

                // Disable other options
                enableDisableInit.disable();
                destroyCreateInit.disable();
            }
        };

        // Enable and disable
        var enableDisable = document.querySelector('.enable-disable-switch');
        var enableDisableInit = new Switchery(enableDisable);
        enableDisable.onchange = function() {
            if (enableDisable.checked) {

                // Enable cropper
                $cropper.cropper('enable');

                // Enable other options
                cropClearInit.enable();
                destroyCreateInit.enable();
            }
            else {

                // Disable cropper
                $cropper.cropper('disable');

                // Disable other options
                cropClearInit.disable();
                destroyCreateInit.disable();
            }
        };

        // Destroy and create
        var destroyCreate = document.querySelector('.destroy-create-switch');
        var destroyCreateInit = new Switchery(destroyCreate);
        destroyCreate.onchange = function() {
            if (destroyCreate.checked) {

                // Initialize again
                $cropper.cropper({
                    aspectRatio: 1,
                    preview: '.preview',
                    data: {
                        x: 208,
                        y: 22
                    }
                });

                // Enable other options
                cropClearInit.enable();
                enableDisableInit.enable();
            }
            else {

                // Destroy cropper
                $cropper.cropper('destroy');
                
                // Disable other options
                cropClearInit.disable();
                enableDisableInit.disable();
            }
        };

        */


        //
        // Methods
        //

        // Get data
        $('#getData').on('click', function() {
            $('#showData1').val(JSON.stringify($cropper.cropper('getData')));
        });

        // Set data
        $('#setData').on('click', function() {
            $cropper.cropper('setData', {
                x: 291,
                y: 86,
                width: 158,
                height: 158
            });

            $('#showData1').val('Image data has been changed');
        });


        // Get container data
        $('#getContainerData').on('click', function() {
            $('#showData2').val(JSON.stringify($cropper.cropper('getContainerData')));
        });

        // Get image data
        $('#getImageData').on('click', function() {
            $('#showData2').val(JSON.stringify($cropper.cropper('getImageData')));
        });


        // Get canvas data
        $('#getCanvasData').on('click', function() {
            $('#showData3').val(JSON.stringify($cropper.cropper('getCanvasData')));
        });

        // Set canvas data
        $('#setCanvasData').on('click', function() {
            $cropper.cropper('setCanvasData', {
                left: -50,
                top: 0,
                width: 750,
                height: 750
            });

            $('#showData3').val('Canvas data has been changed');
        });


        // Get crop box data
        $('#getCropBoxData').on('click', function() {
            $('#showData4').val(JSON.stringify($cropper.cropper('getCropBoxData')));
        });

        // Set crop box data
        $('#setCropBoxData').on('click', function() {
            $cropper.cropper('setCropBoxData', {
                left: 395,
                top: 68,
                width: 183,
                height: 183
            });

            $('#showData4').val('Crop box data has been changed');
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentImageCropper();
            
        }
    }
}();

$('#change_img').on('click', function() {
    $('#courseImage').val(''); //RESET COURSE IMAGE VALUE TO NONE
    $('#course_img_url').val(''); //RESET URL IMAGE VALUE TO NONE
    $('#lc-crop-image').html(""); // REMOVES CROPPER DIV
    $('.fileinput-remove-button').click(); // RESETS UPLOADER
    $('#img_preview img').attr('src', ''); //reset preview image
    $('#course-img-upload').show();
})

ImageCropper.init(); // INITIALIZE CROPPER

</script>