<?php

/**
 * Delete Lesson
 *
 * Handle Deleting Lesson
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

require_once 'session-validation.php';
//ensure admin user (admin is 2 and above)
if (($_SESSION['admin_num'] < 2)) {
    die('<h1>Restricted Action!</h1>');
}

// include database and object files
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/lesson.php';
require_once dirname(__FILE__).'/objects/media.php';
require_once dirname(__FILE__).'/objects/media_progress.php';

// instantiate database and lesson object
$database = new Database();
$db = $database->getConnection();

$lesson = new Lesson($db);
$media = new Media($db);
$media_progress = new MediaProgress($db);

// get IDs
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: missing Category ID.');
$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : die('ERROR: missing Topic ID.');
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing Course ID.');
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: missing Lesson ID.');

//read single lesson, get its name
$lesson->id = $lesson_id;
$lesson->readOne();
$lesson_name = $lesson->lesson_name;

$media_progress->lesson_id = $lesson_id;
$media_progress->deleted = 1;

if ($_POST) {
    /*
    * delete lesson by id in lessons table
    * delete media rows associated with the lesson by course_id and lesson_id
    * delete media files in ./media/lessons.id (media + lessons.id is the path)
    */

    //pay attention to the relative path here
    //be sure to add the lesson_id to lessons.media_dir
    $media->media_dir_path = './' . $lesson->media_dir . $lesson->id;
    
    // set lesson object ids
    $lesson->category_id = $_POST['category_id'];
    $lesson->topic_id = $_POST['topic_id'];
    $lesson->course_id = $_POST['course_id'];
    $lesson->id = $_POST['lesson_id'];
    
    // set media object ids
    $media->category_id = $_POST['category_id'];
    $media->topic_id = $_POST['topic_id'];
    $media->course_id = $_POST['course_id'];
    $media->lesson_id = $_POST['lesson_id'];

    $user_ux = '';

    //delete lesson row form lessons
    if ($lesson->delete()) {
        $user_ux .= "Lesson was deleted.<br />";
    } else {
        $user_ux .= "Unable to delete lesson.<br />";
    }

    //Delete media rows form media table
    if ($media->deleteByLessonId()) {
        $user_ux .= "Media db rows were deleted.<br />";
    } else {
        $user_ux .= "Unable to delete media rows.<br />";
    }

    if ($media->ensureLessonMediaDir($media->media_dir_path)) {
        //Delete media files associated with the lesson
        if ($media->deleteMediaFilesByLesson($media->media_dir_path)) {
            $user_ux .= "Media files were deleted (if any).<br />";
        } else {
            $user_ux .= "Unable to delete media files.<br />";
        }
    }
 
    /*
      @todo
      delete_lesson.php
      set media_progress.delete to 1. Our tracking data will be off if we do not do this
     */

    if ($media_progress->updateDeletedColumnLesson()) {
        $user_ux .= "UX media_progress.deleted set to 1.<br />";
    } else {
        $user_ux .= "Unable to set media_progress.delete to 1.<br />";
    }

    $user_ux = '';

    echo "<script>lessonDeleted('$category_id', '$course_id', '$user_ux');</script>";
}


?>

<div class="card container">
    <div class="card-body">
        <h4 class="card-title text-danger">
            <p><i class="icon-warning22"></i> If you delete this lesson all media files associated with it will also be deleted.</p>
            <p class="text-info"><i class="icon-info22 "></i> These media files are for this lesson only and will not affect other lessons if they are deleted.</p>
        </h4>
    </div>
</div>


<div class="card container">
    <div class="card-body">
        <h3 class="card-title">Confirm Lesson Delete</h3>
        <h6 class="card-subtitle mb-2 text-muted">Are you sure you want to delete this lesson?</h6>
        <form id='delete-lesson-form' action='delete_lesson.php?category_id=<?php echo $category_id;?>&topic_id=<?php echo $topic_id;?>&course_id=<?php echo $course_id;?>&lesson_id=<?php echo $lesson_id;?>' method='post'>
            <div class="form-group">
                <input type="text" name='lesson_name' class="form-control" id="lessonName" placeholder="<?php echo $lesson_name?>" disabled>
                <input type="hidden" name='category_id' value='<?php echo $category_id?>'>
                <input type="hidden" name='topic_id' value='<?php echo $topic_id?>'>
                <input type="hidden" name='course_id' value='<?php echo $course_id?>'>
                <input type="hidden" name='lesson_id' value='<?php echo $lesson_id?>'>
            </div>
            <button type="submit" class="btn btn-danger">Yes, Delete Lesson</button>
        </form>
    </div>
</div>

<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

    //post the form for deleting the lesson
    $('#delete-lesson-form').on('submit', function(e) {

        //prevent form submission
        e.preventDefault();

        if (!e.target.lesson_id.value.trim()) {
            alert('Cannot delete!');
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
            success: function(data) {
                $elem.find('.lesson-name').text('').hide();
                $content.html(data);
            },
            error: function(data) {
                $content.html(data.responseText);
            },
            fail : function() {
                $content.html('<div id="load">Please try again soon.</div>');
            }
        });
    });

    //reload the course after lesson is deleted
    $('.return-to-course').on('click', function(e) {

    //prevent href
    e.preventDefault();

    var $content = $("#content-area-div");
    var url = e.target.href;

    $.ajax({
        type: 'POST',
        url: url,
        timeout: 30000,
        beforeSend: function() {
            $content.html('<div id="load">Loading</div>');
        },
        complete: function() {
            $('#load').remove();
        },
        success: function(data) {
            $content.html(data);
        },
        error: function(data) {
            $content.html(data.responseText);
        },
        fail : function() {
            $content.html('<div id="load">Please try again soon.</div>');
        }
    });

    });
}) ();
</script>