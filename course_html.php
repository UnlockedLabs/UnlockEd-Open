<?php

/**
 * Course HTML
 *
 * HTML for Create Course and Update Course pages
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
<!--NOTE: this files is shared between create_course.php and update_course.php-->
<div class="card">

    <div class="card-header">
        <h2 class="card-title text-center"><?php echo $form_title . '<i class="icon-chevron-right mr-1 ml-1"></i>' . $category_name; ?></h2>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">

                <label>Course Image</label>
                <!-- Uploader -->
                <div id="pic_upload">
                    <form action="index.php" id="course-img-upload">
                        <input type="file" class="file-input" data-fouc>
                    </form>
                </div>
                <!-- Uploader -->
                <!-- Cropper -->
                <div id="lc-crop-image"></div>
                <!-- Cropper -->
            </div>

            <div class="col-md-8">

                <!-- course preview -->
                <?php require 'course_preview.php'; ?>
                <!-- /course preview -->

                <!-- form -->
                <form id='save-course-form' action='<?php echo "$form_action?category_name=$category_name&topic_name=$topic_name&category_id=$category_id&topic_id=$topic_id&course_id=$course_id"; ?>' method='post'>

                    <div class="form-group">
                        <label for="courseName">Course Name</label>
                        <input type="text" name='course_name' class="form-control" id="courseName" maxlength="64" value="<?php echo $course_name; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="courseDesc">Course Description</label>
                        <textarea name="course_desc" class="form-control" id="courseDesc" placeholder="Describe Your Course *optional" type="text"><?php echo $course_desc; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="courseName">External Website's URL</label>
                        <input type="text" name='iframe' class="form-control" id="iframe" value="<?php echo $iframe; ?>" placeholder="Only set this if you are linking to an external site.">
                    </div>


                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Access Level</label>
                        <select class="form-control" name='access_id' id="exampleFormControlSelect1" required>
                            <?php
                            $access_levels = $users->readAccessLevels();
                            while ($row = $access_levels->fetch(\PDO::FETCH_ASSOC)) {
                                //access_id corresponds to access_levels.id
                                if ($row['access_num'] == "2" || $row['access_num'] == "4" || $row['access_num'] == "5") {
                                    continue;
                                } else if ($row['id'] == $access_id) {
                                    echo '<option value=' . $row['access_num'] . ' selected>' . $row['access_name'] . '</option>';
                                } else {
                                    echo '<option value=' . $row['access_num'] . '>' . $row['access_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <input type="hidden" name="topic_id" value="<?php echo $course->topic_id; ?>">
                    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                    <input type="hidden" name="category_name" value="<?php echo $category_name; ?>">
                    <input type="hidden" name="course_img_url" id="course_img_url" value="">
                    <input type="hidden" name='course_img' id="courseImage" value="<?php echo $course_img; ?>">
                    <input type="hidden" name='old_course_img' value="<?php echo $course_img; ?>">

                    <button type="submit" class="btn btn-primary">Save</button>
                </form> <!-- /form -->
            </div> <!-- /col-md-8 -->
        </div> <!--/ row -->
    </div> <!-- /card body -->
</div> <!-- /card -->

<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

$('#save-course-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.course_name.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Course Name.');
        return false;
    }
    if (!e.target.access_id.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Select Access Level.');
        return false;
    }

    var $content = $("#content-area-div");
    var url = e.target.action;
    var serializedForm = $(this).serialize();
 
    $.ajax({
        type: 'POST',
        url: url,
        data: serializedForm,
        timeout: 30000,
        beforeSend: function() {
            $content.html('<div id="load">Loading</div>');
        },
        complete: function() {
            $('#load').remove();
        },
        error: function(data) {
            $content.html(data.responseText);
        }
    }).done(function(data) {
        $content.html(data);
        $elem.find('.course-name').text('').hide();
        $(".breadcrumb-item .topic-link-num").click();
        $(".course-admin").hide();
        $(".topic-admin").show();
    }).fail(function() {
        $content.html('<div id="load">Please try again soon.</div>');            
    });

});
}) ();
</script>

<script>
// HIDE CROPPER UNTIL IMAGE DRAG/DROP
$('#cropper').hide();

$('#course-img-upload').on('submit', function (e) {

    e.preventDefault();

    var imageName = $('.file-caption-name').val();

    /*
    Remove current extension and replace it with png.
    The uploader saves all pictures as png.
    See lc-image-cropper.php, result.toDataURL("image/png")
    */
    //Set the courseImage form value for posting to N.png
    $('#courseImage').val(imageName.split('.')[0]+'.png');

    var imageDataURL = $(this).find('.file-preview-image').attr('src');
    $('#demo-cropper-image').attr("src", imageDataURL);
    $('#img_preview').addClass('preview preview-lg').html('');
    $('#lc-crop-image').load('lc-image-cropper.php', { imageDataURL: imageDataURL });
    $('#course-img-upload').hide();
    cropImageAlert();

})


// LIVE PREVIEW

// on KEY UP, SET innerHTML (course_name preview) to value of course_name input
$('#courseName').keyup(function ()
{
    $('#prev_course_name').html($('#courseName').val());
    $('#prev_course_name2').html($('#courseName').val());
});

// on KEY UP, SET innerHTML (course_desc preview) to value of course_desc input
$('#courseDesc').keyup(function ()
{
    $('#prev_course_desc').html($('#courseDesc').val());
});

//-------------------------------------------------------------------

//JGROWL ALERT FOR IMAGE

function setImageAlert() {
    $.jGrowl("Your Course Image is now Set!", {
        theme: 'bg-success',
        position: 'center-left',
        life: 3000
    });
}

function cropImageAlert() {
    $.jGrowl("Now You Can Crop Your Image! Set Image When You Like What You See.", {
        theme: 'bg-warning',
        position: 'center-left',
        life: 3000
    });
}

//Initialize the bootstrap uploader.
FileUpload.init();
</script>