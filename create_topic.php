<?php

/**
 * Create Topic
 *
 * Process create topic form and insert topic into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Insert/create topic.
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
require_once dirname(__FILE__).'/objects/category.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//$product = new Product($db);
$topic = new Topic($db);
$users = new User($db);

// get ID of the topic to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
$category_name = isset($_GET['category_name']) ? $_GET['category_name'] : die('ERROR: missing Category Name.');
$topic->id = $id;
$topic->category_id = $id;

// if the form was submitted
if ($_POST) {
    // set topic property values
    $topic_name = $_POST['topic_name'];
    $topic_url = $_POST['topic_url'];
    
    if ((empty($topic_name))) {
        echo "<div class='alert alert-danger'>Name cannot be empty.</div>";
    } else {
        $topic->topic_name = $topic_name;
        $topic->topic_url = $topic_url;

        //ensure the topic does not exists
        if ($topic->topicExists()) {
            //$topic->topic_name=$topic_name;
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Topic already exists.";
            echo "</div>";
        } elseif ($topic->create()) {
            // tell the user new topic was created
            echo "<div class=\"alert alert-success alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Topic was created. Please reload the page to see the updated Topic tags. <a href='./index.php'>Reload page now.</a>";
            echo "</div>";
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Unable to create topic.";
            echo "</div>";
        }
    }
}
?>
     

<div class="card container">
    <div class="card-body">
        <h3 class="card-title text-center"><?php echo $category_name; ?></h3>
        <h3 class="card-title">Create New Topic</h3>
        <form id='create-topic-form' action='create_topic.php?id=<?php echo $id; ?>&category_name=<?php echo $category_name; ?>' method='post'>
            <div class="form-group">
                <label for="topicName">Topic Name</label>
                <input type="text" name='topic_name' class="form-control" id="topicName" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="topic_url">External Website's URL</label>
                <input type="text" name='topic_url' class="form-control" id="topic_url" placeholder="Only set this if you are linking to an external site.">
            </div>
            <button type="submit" class="btn btn-primary">Create Topic</button>
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

$('#create-topic-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.topic_name.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Topic Name.');
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

<?php
// include page footer HTML
//include_once "layout_footer.php";
?>