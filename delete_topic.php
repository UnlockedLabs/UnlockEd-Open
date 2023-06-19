<?php

/**
 * Delete Topic
 *
 * Handle Deleting Topic
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
require_once dirname(__FILE__).'/objects/topic.php';
require_once dirname(__FILE__).'/objects/users.php';
require_once dirname(__FILE__).'/objects/course.php';

// instantiate database and topic object
$database = new Database();
$db = $database->getConnection();

$course = new Course($db);
$topic = new Topic($db);
$users = new User($db);

// get ID of the topic to be edited
$id = isset($_GET['topic_id']) ? $_GET['topic_id'] : die('ERROR: missing Topic ID.');
$topic->id = $id;

//set the topic_id and new_topic_id in course for reassignment
$course->topic_id = $id;

/* 
    a new_category_id of ad1853ad-66e0-4bbb-a5fe-d79632d07b1d
    moves the course(s) to the Unassigned Courses tab.
*/
$course->new_topic_id =  'f5bd24fb-81a7-4a95-9cdf-5b09183bcada';

$topic->readOne();
$topic_name = $topic->topic_name;

if ($_POST) {
    // set topic id to be deleted
    $topic->id = $_POST['topic_id'];

    if ($topic->delete()) {
        echo "<div class=\"alert alert-success alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Topic was deleted. Please reload the page to see the updated Topic tags. <a href='./index.php'>Reload page now.</a>";
        echo "</div>";

        /*  In courses table, change topic_id to 1 where courses.topic_id=$topic->id.
            In populate-tables.php I am setting the 'Unassigned Topics and Courses' category to id 1 in categories table.
            When we delete a topic and set the associated course topic_id to 1,
            we are moving the courses to the 'Unassigned Courses' topic which is a sub of
            the 'Unassigned Topics and Courses'category.
        */

        if ($course->reassignCourseTopicId()) {
            //echo '<h1>all couses under this topic were moved to 1 (Unassigned Courses Tab) --if any--</h1>';
        } else {
            //echo '<h1>Courses under this topic were moved to NOT 1</h1>';
        }
    } else {
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Unable to delete topic.";
        echo "</div>";
    }
}

?>
<div class="card container">
    <div class="card-body">
        <h4 class="card-title text-danger">
            <p><i class="icon-warning22"></i> If you delete this topic only the topic is deleted.</p>
            <p><i class="icon-warning22"></i> Any courses and associated lessons are moved to the Unassigned Courses tab.</p>
            <p class="text-info"><i class="icon-info22 "></i> You can reassign or delete a course under the Unassigned Courses tab once the course is moved there.</p>
        </h4>
    </div>
</div>


<div class="card container">
    <div class="card-body">
        <h3 class="card-title">Confirm Topic Delete</h3>
        <h6 class="card-subtitle mb-2 text-muted">Are you sure you want to delete this topic?</h6>
        <form id='delete-topic-form' action='delete_topic.php?topic_id=<?php echo $id;?>' method='post'>
            <div class="form-group">
                <input type="text" name='topic_name' class="form-control" id="topicName" placeholder="<?php echo $topic_name?>" disabled>
                <input type="hidden" name='topic_id' value='<?php echo $id?>'>
            </div>
        <button type="submit" class="btn btn-danger">Yes, Delete Topic</button>
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

$('#delete-topic-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.topic_id.value.trim()) {
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
            $content.html(data);
            $("body").removeClass("sidebar-right-visible");
        },
        error: function(data) {
            $content.html(data.responseText);
        },
        fail : function() {
            $content.html('<div id="load">Please try again soon.</div>');
            $("body").removeClass("sidebar-right-visible");
        }
    });

});
}) ();
</script>