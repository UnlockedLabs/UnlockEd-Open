<?php

/**
 * Lesson Editor Update
 *
 * Handle UPdating the Lesson Editing
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

?>

<div id="modal_full" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">

        <!--

            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

        -->

            <div class="modal-body">


                <div class="card-body bg-green">
                    <div class="text-light text-center">
                        <!--<img class="mt-2" src="images/UELogo.png" alt="" width="75">-->
                        <p style="font-size: 40px;; line-height: 50px; font-weight: 300;" id="create_title">
                            Write Your <span style="font-weight: 400;">Lesson.</span>
                        </p>
                        <p style="font-size: 14px; line-height: 24px;" id="create_instruct">
                            Use the editor below to write your lesson for students.<br>Insert links, images, tables, etc.
                        </p>
                        <div class="d-flex flex-row justify-content-center">
                            <p class="py-2 px-3" id="new" style="opacity:1.0;">NEW</p>
                            <p class="py-2 px-3" id="write" style="opacity:0.4;">WRITE</p>
                            <p class="py-2 px-3" id="upload" style="opacity:0.4;">UPLOAD</p>
                            <p class="py-2 px-3" id="organize" style="opacity:0.4;">ORGANIZE</p>
                            <p class="py-2 px-3" id="finish" style="opacity:0.4;">FINISH</p>
                        </div>
                        <div class="d-flex flex-row justify-content-center">
                            <div class="ui-progressbar bg-green-300" style="height: 5px; width:400px;" role="progressbar">
                                <div class="ui-progressbar-value bg-light" id="progress" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <form action="update_editor.php" method='post' class="text-center">                              
                    <textarea name="ck_input" id="editor" rows="4" cols="4" style="visibility: hidden; display: none;" value=""></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn bg-primary update-lesson-instructions" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
//intialize ckeditor
CKEDITOR.config.readOnly = false;
CKEDITOR.config.height = 'auto';
initSample();
//console.table(CKEDITOR.config);


/*  
    This (shown.bs.modal) event is fired when the modal has been made visible to the user (will wait for CSS transitions to complete).
    If caused by a click, the clicked element is available as the relatedTarget property of the event.
*/
$('#modal_full').on('show.bs.modal', function (e) {

    // grow progress bar
    setTimeout(function()
    {
        $('#progress').animate(
        {
            width: '25%' //25, 45, 70, 
        })
    }, 500);

    // fade and highlight steps..update title and instructions
    setTimeout(function()
    {
        $('#new').animate(
        {
            opacity: '1.0' 
        })
        $('#write').animate(
        {
            opacity: '0.4' 
        })
    }, 500);

});

//This event is fired immediately when the hide instance method has been called.
$('#modal_full').on('hide.bs.modal', function (e) {

    // grow progress bar
    setTimeout(function()
    {
        $('#progress').animate(
        {
            width: '0%' //25, 45, 70, 
        })
    }, 500);

    // fade and highlight steps..update title and instructions
    setTimeout(function()
    {
        $('#new').animate(
        {
            opacity: '0.4' 
        })
        $('#write').animate(
        {
            opacity: '1.0' 
        })
    }, 500);

});
</script>

<script>
//current_lesson_id gets set when the .lesson-instructions button is clicked
var current_lesson_id = 0;

$('.lesson-instructions').on('click', function(e) {
   
    var lessonId = $(this).data('lesson-id');
    current_lesson_id = lessonId;

    var url = 'lesson_instructions.php?lesson_id='+lessonId;

    $.ajax({
        type: 'GET',
        url: url,
        timeout: 30000,
        beforeSend: function() {
            //pass
        },
        complete: function() {
            //pass
        },
        success: function(data) {
            
            CKEDITOR.instances["editor"].setData(data);
        },
        error: function(data) {
            CKEDITOR.instances["editor"].setData(data.responseText);
        },
        fail : function() {
            CKEDITOR.instances["editor"].setData('<div id="load">Please try again soon.</div>');
        }
    });
});


$('.update-lesson-instructions').on('click', function(e) {

    //current_lesson_id gets set when the .lesson-instructions button is clicked
    var lessonId = current_lesson_id;

    var url = 'update_lesson_instructions.php?lesson_id='+lessonId;

    /**
     * This function takes the emoji src attribute and strips the http protocol, making
     * the address relative to the root directory (so the link to emoji won't break if
     * URL changes)
     */
    var strippedSrc = ul.stripUrl("editor");
    
    //send ajax request if lesson content has not already been loaded
    $.ajax({
        type: 'POST',
        url: url,
        data: {editor_html:strippedSrc},
        timeout: 30000,
        beforeSend: function() {
            //pass
        },
        complete: function() {
            //pass
        },
        success: function(data) {
            //update non-editor instructions
            $('#lesson-card-id-'+lessonId).find('.card-body').html(data);
            $('#lesson-card-id-'+lessonId).show();
        },
        error: function(data) {
            CKEDITOR.instances["editor"].setData(data.responseText);
        },
        fail : function() {
            CKEDITOR.instances["editor"].setData('<div id="load">Please try again soon.</div>');
        }
    });
});
</script>