<?php

/**
 * Create Lesson
 *
 * Process create lesson form and insert lesson into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Insert/create course.
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
require_once dirname(__FILE__).'/objects/users.php';
require_once dirname(__FILE__).'/objects/lesson.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$course = new Course($db);
$lesson = new Lesson($db);
$users = new User($db);

// get topic id
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: missing CATEGORY ID.');
$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : die('ERROR: missing TOPIC ID.');
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing COURSE ID.');
$lesson->topic_id = $topic_id;
$lesson->course_id = $course_id;

// if the form was submitted
if ($_POST) {
    // set lesson property values
    $lesson_name = $_POST['lesson_name'];
    $topic_id = $_POST['topic_id'];
    $access_id = $_POST['access_id'];
    $admin_id = $_POST['admin_id'];
    
    if ((empty($lesson_name))) {
        echo "<div class='alert alert-danger'>Name cannot be empty.</div>";
    } elseif (empty($access_id)) {
        echo "<div class='alert alert-danger'>Access Id cannot be empty.</div>";
    } elseif (empty($admin_id)) {
        echo "<div class='alert alert-danger'>Admin Id cannot be empty.</div>";
    } else {
        $lesson->lesson_name = $lesson_name;
        $lesson->topic_id = $topic_id;
        $lesson->access_id = $access_id;
        $lesson->admin_id = $admin_id;

        /*
        Note: to get the lesson media files directory, concatenate lessons.media_dir with lessons.id
        */

        $lesson->media_dir = 'media/';
    
        //ensure the lesson does not exists
        //as of 09-07-21 this method returns false, not performing the check.
        if ($lesson->lessonExists()) {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Lesson already exists.";
            echo "</div>";
        } elseif ($lesson->create()) {
            echo "<script>lessonCreated('$category_id', '$course_id', '$lesson->new_lesson_id', '$lesson_name');</script>";
            die();
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Unable to create lesson.";
            echo "</div>";
        }
    }
}
?>

<div class="card">
    <div class="card-body">
        <h3 class="card-title">Create New Lesson</h3>
        <form id='create-lesson-form' action='create_lesson.php?category_id=<?php echo $category_id; ?>&topic_id=<?php echo $lesson->topic_id; ?>&course_id=<?php echo $lesson->course_id; ?>' method='post'>
            <div class="form-group">
                <label for="lessonName">Lesson Name</label>
                <input type="text" name='lesson_name' class="form-control" id="lessonName" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Access Level</label>
                <select class="form-control" name='access_id' id="exampleFormControlSelect1" required>
                    <?php
                    $access_levels = $users->readAccessLevels();
                    while ($row = $access_levels->fetch(\PDO::FETCH_ASSOC)) {
                        echo '<option value=' . $row['access_num'] . '>' . $row['access_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Admin Level</label>
                <select class="form-control" name='admin_id' id="exampleFormControlSelect1" required>
                    <?php
                    $admin_levels = $users->readAdminLevels();
                    while ($row = $admin_levels->fetch(\PDO::FETCH_ASSOC)) {
                        echo '<option value=' . $row['admin_num'] . '>' . $row['admin_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="topic_id" value="<?php echo $lesson->topic_id; ?>">
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
            <button type="submit" class="btn btn-primary">Create Lesson</button>
        </form>
    </div> <!--/.card-body-->
</div> <!--/.card-->


<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

$('#create-lesson-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.lesson_name.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Lesson Name.');
        return false;
    }
    if (!e.target.access_id.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Select Access Level.');
        return false;
    }

    if (!e.target.admin_id.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Select Admin Level.');
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