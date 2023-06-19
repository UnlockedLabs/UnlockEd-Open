<?php

/**
 * Update Lesson Name
 *
 * Handle Updating the Lesson Name
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
require_once dirname(__FILE__).'/objects/users.php';

// instantiate database and lesson object
$database = new Database();
$db = $database->getConnection();

$lesson = new Lesson($db);
$users = new User($db);

// get ID of the lesson to be edited
$id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: Lesson Id.');
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: Category Id.');
$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : die('ERROR: Topic Id.');
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: Course Id.');

//set the id property in the lesson class (refers to the db col id)
$lesson->id = $id;

// if the form was submitted
if ($_POST) {
    // set lesson property values
    $lesson_name = $_POST['lesson_name'];
    $access_id = $_POST['access_id'];
    $admin_id = $_POST['admin_id'];
    
    if ((empty($lesson_name))) {
        echo "<div class='alert alert-danger'>Lesson Name cannot be empty.</div>";
    } elseif (empty($access_id)) {
        echo "<div class='alert alert-danger'>Access Id cannot be empty.</div>";
    } elseif (empty($admin_id)) {
        echo "<div class='alert alert-danger'>Admin Id cannot be empty.</div>";
    } else {
        $lesson->lesson_name = $lesson_name;
        $lesson->access_id = $access_id;
        $lesson->admin_id = $admin_id;
        
        // execute the query
        if ($lesson->update()) {
            echo "<script>lessonUpdated('$category_id', '$course_id');</script>";
            die();
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                echo "Unable to update lesson.";
            echo "</div>";
        }
    }
}

$lesson->readOne();

// assign values to object properties
$lesson_id = $lesson->id;
$lesson_name = $lesson->lesson_name;
$course_id = $lesson->course_id;

/*
TEAM: are we going to edit the html (editor_html) here or just set the name, access and admin ids?
At present, we are using a modal with a ckeditor inside of a lesson tab to format
and save the lesson instructions. Ajax calls are being used to do this.

If we edit here we will need to insert the ckeditor into the form.

There are pros and cons to both placements.
*/

$editor_html = $lesson->editor_html;
//access_id corresponds to access_levels.id
$access_id = $lesson->access_id;
//admin_id corresponds to admin_levels.id
$admin_id = $lesson->access_id;
?>

<!-- HTML form for updating a lesson -->
<div class="card container">
    <div class="card-body">
        <h3 class="card-title">Edit Lesson <br/></h3>
        <form id='update-lesson-form' action='update_lesson_name.php?category_id=<?php echo $category_id; ?>&course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson_id; ?>&topic_id=<?php echo $topic_id; ?>' method='post'>
            <div class="form-group">
                <label for="lessonName">Lesson Name</label>
                <input type="text" name='lesson_name' class="form-control" id="lessonName" maxlength="64" value="<?php echo $lesson_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Access Level</label>
                <select class="form-control" name='access_id' id="exampleFormControlSelect1" required>
                    <?php
                    $access_levels = $users->readAccessLevels();
                    while ($row = $access_levels->fetch(\PDO::FETCH_ASSOC)) {
                        //access_id corresponds to access_levels.id
                        if ($row['id'] == $access_id) {
                            echo '<option value=' . $row['access_num'] . ' selected>' . $row['access_name'] . '</option>';
                        } else {
                            echo '<option value=' . $row['access_num'] . '>' . $row['access_name'] . '</option>';
                        }
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
                    //admin_id corresponds to admin_levels.id
                    if ($row['id'] == $admin_id) {
                        echo '<option value=' . $row['admin_num'] . ' selected>' . $row['admin_name'] . '</option>';
                    } else {
                        echo '<option value=' . $row['admin_num'] . '>' . $row['admin_name'] . '</option>';
                    }
                }
                ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Lesson</button>
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

$('#update-lesson-form').on('submit', function(e) {

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