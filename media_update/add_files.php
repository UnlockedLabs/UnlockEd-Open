<?php

/**
 * Add Files
 *
 * Handle adding files/media (upload)
 *
 * PHP version 7.2.5
 *
 * @category  Category
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__).'/../session-validation.php';
//ensure admin user (admin is 2 and above)
if (($_SESSION['admin_num'] < 2)) {
    die('<h1>Restricted Action!</h1>');
}

// get and ensure course and lesson id
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing Course ID.');
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: missing Lesson ID.');
?>

<div class="card">
    <div class="card-body bg-green">
        <div class="text-light text-center">
            <img class="mt-2" src="./media_update/images/UELogo_wht.png" alt="" width="75">
            <p style="font-size: 40px;; line-height: 50px; font-weight: 300;" id="create_title">
                Write Your <span style="font-weight: 400;">Lesson.</span>
            </p>
            <p style="font-size: 14px; line-height: 24px;" id="create_instruct">
                Use the editor below to write your lesson for students.<br>Insert links, images, tables, etc.
            </p>
            <div class="d-flex flex-row justify-content-center">
                <p class="py-2 px-3" id="new" style="opacity:0.4;">NEW</p>
                <p class="py-2 px-3" id="write" style="opacity:1.0;">WRITE</p>
                <p class="py-2 px-3" id="upload" style="opacity:0.4;">UPLOAD</p>
                <p class="py-2 px-3" id="organize" style="opacity:0.4;">ORGANIZE</p>
                <p class="py-2 px-3" id="finish" style="opacity:0.4;">FINISH</p>
            </div>
                
            <div class="d-flex flex-row justify-content-center">
                <div class="ui-progressbar bg-green-300" style="height: 5px; width:400px;" role="progressbar">
                    <div class="ui-progressbar-value bg-light" id="progress" style="width: 25%;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="file-uploader animated fadeInUp" id="uploader_container" style="width:100%;">
        <p>Your browser doesn't have Flash installed.</p>
    </div>

    <form action='upload_sort.php' method='post' id='send'>
        <input type="hidden" class="form-control" name='from_editor' value='not in use'>
        <input type="hidden" class="form-control" name='filepath' value='not in use'>
    </form>

    <form action='finish.php' method='post' id='skip'>
        <input type="hidden" class="form-control" name='from_editor' value='not in use'>
    </form>
</div>

<script> 

// fade out the the previous title and instructions
$('#create_instruct').animate(
{
    opacity: '0.0' 
})
$('#create_title').animate(
{
    opacity: '0.0' 
})


// fade in the the current title and instructions after updating below
setTimeout(function()
    {
        $('#create_instruct').animate(
        {
            opacity: '1.0' 
        })
    }, 750);

setTimeout(function()
    {
        $('#create_title').animate(
        {
            opacity: '1.0' 
        })
    }, 750);


// grow progress bar
setTimeout(function()
    {
        $('#progress').animate(
        {
            width: '45%' //25, 45, 70, 
        })
    }, 500);

// fade and highlight steps..update title and instructions
setTimeout(function()
    {
        $('#write').animate(
        {
            opacity: '0.4' 
        })
        $('#upload').animate(
        {
            opacity: '1.0' 
        })
        
        $('#create_title').html('Add Media <span style="font-weight: 400;">Files</span>').fadeIn('slow');
        $('#create_instruct').html('Upload the various content files for your lesson here.<br>Accepted file types are: JPG, PNG, GIF, PDF, MP3, WAV, MP4, OGG, WMV. - 200GB Limit').fadeIn('slow')
    }, 500);


// uploader
$(".file-uploader").pluploadQueue({
    runtimes: 'html5, html4, Flash, Silverlight',
    url: './media_update/upload.php',

    unique_names: true,
    filters: {
        max_file_size: '256Mb',
        mime_types: [{
            title: "File Types",
            extensions: "jpg,pdf,png,gif,mp3,wav,mp4,wmv,ogg,mpeg"
        }]
    },

    multipart_params: {
        'course_id' : '<?php echo $course_id; ?>',
        'lesson_id' : '<?php echo $lesson_id; ?>',
        'media_ids' : 'create-new',
         },


    init: {
        FilesAdded: function(up, files) {
            setTimeout(function()
            {
                myNoty();
                //hide close button so the user cannot close the modal during upload
                $('#close_file_upload_btn_<?php echo $lesson_id; ?>').hide();
            }, 500); 
        },
        FileUploaded: function(up, files, data) {
            fileUploadedResponse(data);
        },
        UploadComplete: function(up, files) {
            $('#uploader_container').addClass('fadeOutDown');
            setTimeout(function()
            {

                /* 
                    * Repopulate the #lesson-media--N div after files have been added.
                    * hide.bs.modal is fired immediately when the hide instance method has been called.
                    * We are calling the hide intance method below.
                */
                $('#add_files_modal_<?php echo $lesson_id; ?>').on('hide.bs.modal', function (e) {

                    var lesson_id = "<?php echo $lesson_id; ?>";

                    var $content = $("#lesson-media-"+lesson_id);

                    $.ajax({
                        type: 'GET',
                        url: 'lesson_media.php?course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson_id; ?>',
                        timeout: 30000,
                        beforeSend: function() {
                            $content.html('<div id="load">Loading</div>');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                        },
                        complete: function() {
                            $('#load').remove();
                        },
                        success: function(data) {
                            $content.html(data);
                            //notify the admin user of the need to refresh the page for the progress bar to work correctly
                            progressLessonBarReload(lesson_id);
                        },
                        error: function(data) {
                            $content.html(data.responseText);
                        },
                        fail : function() {
                            $content.html('<div id="load">Please try again soon.</div>');
                        }
                    });

                });

                //close modal and trigger ajax update, see above
                $('#add_files_modal_<?php echo $lesson_id; ?>').modal('hide');
                
                //notify user of upload completion
                if (!custom_cancelled) {
                    myNotyComplete();
                } else {
                    myNotyCancelled();
                }
            }, 1000);
            
        },
    }
    /* NOTES
    Deleted "chunk_size: '300Kb'", it caused uploaded files to be
    named 'blob' in uploads folder.

    */
});

//################################

//cancel file upload
var custom_cancelled = false;
function cancelFileUpload() {
    //http://primeauxillaryserver.doc.mo.gov:8081/stackoverflow/4720928
    $(".file-uploader").pluploadQueue().splice();
    $(".file-uploader").pluploadQueue().refresh();
    custom_cancelled = true;
    //show the close button so the user can close the modal
    $('#close_file_upload_btn_<?php echo $lesson_id; ?>').show();
    
}

function fileUploadedResponse(data) {
    var parsedResult = $.parseJSON(data.response);
    //display success for 2 secs
    var secTimeOut = 2000;
    //require user to close noty on error
    if (parsedResult.noty_color == 'error') {
        secTimeOut = false;
    }

    new Noty({
        theme: 'limitless',
        text: `<p>${parsedResult.filename}${parsedResult.msg}</p>`,
        type: parsedResult.noty_color,
        layout: 'topRight',
        timeout: secTimeOut,
        closeWith: ['button'],
        animation: {
            open: 'animated bounceInUp',     // or Animate.css class names like: 'animated bounceInLeft'
            close: 'animated bounceOutDown'    // or Animate.css class names like: 'animated bounceOutLeft'
        },
        progressBar: false,                    // displays a progress bar if timeout is not false
    }).show();

}

function myNotyCancelled() {

new Noty({
    theme: 'limitless',
    text: `
    <div class="text-center text-white">
        <i class="icon-checkmark3 icon-2x"></i>
    </div>
    <blockquote class="blockquote mb-0">
        <p>
            <b>UPLOAD CANCELLED:</b>
            You may need to delete any files that where uploaded before you cancelled the upload.
        </p>
    </blockquote>`,
    type: 'info',
    layout: 'topRight',
    timeout: 20000,
    closeWith: ['button'],
    animation: {
        open: 'animated bounceInUp',     // or Animate.css class names like: 'animated bounceInLeft'
        close: 'animated bounceOutDown'    // or Animate.css class names like: 'animated bounceOutLeft'
    },
    progressBar: false,                    // displays a progress bar if timeout is not false
}).show();

}

//################################

// "don't click" notification
function myNoty() {

if ($(".noty_body").length > 0) {
    return false;
}

new Noty({
    theme: 'limitless',
    text: '<div class="text-center text-white"><i class="icon-warning2 icon-2x"></i></div><blockquote class="blockquote mb-0"><p><b>REMINDER: </b>Once you begin uploading, please remain on this page to ensure that every file is uploaded completely.</p></blockquote></div>',
    type: 'warning',
    layout: 'topRight',
    timeout: 5000,
    closeWith: ['button'],
    animation: {
        open: 'animated bounceInUp',     // or Animate.css class names like: 'animated bounceInLeft'
        close: 'animated bounceOutDown'    // or Animate.css class names like: 'animated bounceOutLeft'
    },
    progressBar: true,                    // displays a progress bar if timeout is not false
}).show();

}

function myNotyComplete() {

new Noty({
    theme: 'limitless',
    text: `
    <div class="text-center text-white">
        <i class="icon-checkmark3 icon-2x"></i>
    </div>
    <blockquote class="blockquote mb-0">
        <p>
            <b>UPLOAD COMPLETE: </b>
            You can now rename and order your files by clicking the Reorder/Rename Media Files button above or clicking
            <strong>
                <a href="#" class="text-light" data-lesson-id="<?php echo $lesson_id; ?>" data-course-id="<?php echo $course_id; ?>" data-toggle="modal" data-target="#modal_full_<?php echo $lesson_id; ?>">here</a>.
            </strong>
        </p>
    </blockquote>`,
    type: 'success',
    layout: 'topRight',
    timeout: 20000,
    closeWith: ['button'],
    animation: {
        open: 'animated bounceInUp',     // or Animate.css class names like: 'animated bounceInLeft'
        close: 'animated bounceOutDown'    // or Animate.css class names like: 'animated bounceOutLeft'
    },
    progressBar: false,                    // displays a progress bar if timeout is not false
}).show();

}

//$('.plupload_buttons').append('<a href="#" class="plupload_button bg-warning" id="skip_btn">Skip Upload</a>');

$('#skip_btn').on('click', function() {
        // grow progress bar
        $('#progress').animate(
        {
            width: '100%'
        })
        $('#upload').animate(
        {
            opacity: '0.4' 
        })
        $('#finish').animate(
        {
            opacity: '1.0' 
        })
        setTimeout(function()
        {
            //$('#skip').submit();
            alert('where to now?');
        }, 1000);
})

//prevent the user from dragging files into areas other than the uploader
$("html").on("dragover", function (event) {
    event.preventDefault();
    event.stopPropagation();
});

$("html").on("dragleave", function (event) {
    event.preventDefault();
    event.stopPropagation();
});

$("html").on("drop", function (event) {
    event.preventDefault();
    event.stopPropagation();
});
</script>