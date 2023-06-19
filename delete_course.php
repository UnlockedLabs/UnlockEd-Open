<?php

/**
 * Delete Course
 *
 * Handle Deleting Course
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
require_once dirname(__FILE__).'/objects/course.php';
require_once dirname(__FILE__).'/objects/lesson.php';
require_once dirname(__FILE__).'/objects/media.php';
require_once dirname(__FILE__).'/objects/users.php';
require_once dirname(__FILE__).'/objects/media_progress.php';


// instantiate database and course object
$database = new Database();
$db = $database->getConnection();

$course = new Course($db);
$lesson = new Lesson($db);
$media = new Media($db);
$media_progress = new MediaProgress($db);
$users = new User($db);

$category_name = isset($_GET['category_name']) ? $_GET['category_name'] : die('ERROR: missing Category Name.');
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: missing Category ID.');

// get ID of the course to be edited
$id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing Course ID.');

$course->id = $id;
$lesson->course_id = $id;
//$lesson_id = $lesson->readLessonIdByCourseId();
$media->course_id = $id;
$course->readOne();
$course_name = $course->course_name;

//set the media dir path
//$media->media_dir_path = './media/' . $lesson_id;
$media->media_dir_path = './media/images/' . $id;


if ($_POST) {
    // set course id to be deleted
    $course->id = $_POST['object_id'];
    $lesson->course_id = $_POST['object_id'];

    $media_progress->course_id = $course->id;
    $media_progress->deleted = 1;

    $ux_msg = '';
    
    // execute the course delete query
    if ($course->delete()) {
        $ux_msg .= "Course deleted.<br />";
    } else {
        $ux_msg .= "Unable to delete course.<br />";
    }

    // delete the course picture and its directory
    //./media/images/courses/N
    if ($course->ensureCourseImageDirectory()) {
        if ($course->deleteCourseImageDirectory('./' . $course->image_dir . $course->id)) {
            $ux_msg .= "Course picture deleted.<br />";
        } else {
            $ux_msg .= "";
        }
    }

    // execute the sub lesson delete query
    if ($lesson->deleteLessonsByCourseId()) {
        $ux_msg .= "Lessons deleted.<br />";
    } else {
        $ux_msg .= "Unable to delete lessons.<br />";
    }
    
    // execute the media delete query
    if ($media->deleteMediaByCourseId()) {
        $ux_msg .= "Media deleted.<br />";
    } else {
        $ux_msg .= "Unable to delete media.<br />";
    }

    /*
      @todo
      delete_course.php
      set media_progress.delete to 1. Our tracking data will be off if we do not do this
      UX media_progress.deleted set to 1.
     */

    if ($media_progress->updateDeletedColumnCourse()) {
        $ux_msg .= "UX media_progress.deleted set to 1.<br />";
    } else {
        $ux_msg .= "Unable to set media_progress.delete to 1.<br />";
    }
    



    if ($media->ensureLessonMediaDir($media->media_dir_path)) {
        //Delete media files associated with the lesson
        if ($media->deleteMediaFilesByLesson($media->media_dir_path)) {
            $ux_msg .= "Media files were deleted (if any).<br />";
        } else {
            $ux_msg .= "Unable to delete media files.<br />";
        }
    } else {
        $ux_msg .= "Media files were not detected.<br />";
    }

    $ux_msg = '';

    echo "<script>courseDeleted('$category_id', '$course->topic_id', '$course_name', '$ux_msg', '$category_name');</script>";
}

//check for sub 'lessons'
$stmt = $lesson->readLessonNamesByCourseId();
$lesson_html = '';
while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
    extract($row);
    $lesson_html .= "<li>$lesson_name</li>";
}

if ($lesson_html) {
    echo <<<END
<div class="card container">
    <div class="card-body">
        <h3 class="card-title text-danger"><i class="icon-warning icon-2x"></i> Warning!</h3>
        <p>This course contains the following sub lessons/units:</p>
            <ul>
                $lesson_html
            </ul>
        <p>If you delete this course you will also be deleting the above lessons.</p>
        <p>Are you sure you want to do this?</p>
    </div>
</div>
END;
}
?>

<div class="card container">
    <div class="card-body">
        <h3 class="card-title">Confirm Course Delete</h3>
        <h6 class="card-subtitle mb-2 text-muted">Are you sure you want to delete this course?</h6>
        <form id='delete-course-form' action='delete_course.php?category_name=<?php echo $category_name;?>&category_id=<?php echo $category_id;?>&course_id=<?php echo $id;?>' method='post'>
            <div class="form-group">
                <input type="text" name='course_name' class="form-control" id="courseName" placeholder="<?php echo $course_name?>" disabled>
                <input type="hidden" name='object_id' value='<?php echo $id?>'>
                <input type="hidden" name='category_id' value='<?php echo $category_id?>'>
            </div>
        <button type="submit" class="btn btn-danger">Yes, Delete Course</button>
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

$('#delete-course-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.object_id.value.trim()) {
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
            $content.html('<div id="load">Deleting</div>');
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