<?php

/**
 * Delete Category
 *
 * Handle Deleting Category
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
require_once dirname(__FILE__).'/objects/category.php';
require_once dirname(__FILE__).'/objects/topic.php';
require_once dirname(__FILE__).'/objects/users.php';

// instantiate database and category object
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$topic = new Topic($db);
$users = new User($db);

// get ID of the category to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
$category->id = $id;

//set the category_id and new_category_id in topic for reassignment
$topic->category_id = $id;

/* 
    a new_category_id of 94876a68-f185-4967-b5fb-f90859ffd5a8
    moves the topics to the Unassigned Topics tab.
*/
$topic->new_category_id = '94876a68-f185-4967-b5fb-f90859ffd5a8';

$category->readOne();
$category_name = $category->category_name;

if ($_POST) {
    // set category id to be deleted
    $category->id = $_POST['category_id'];

    if ($category->delete()) {
        echo "<div class=\"alert alert-success alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Category was deleted. Please reload the page to see the updated Category tags. <a href='./index.php'>Reload page now.</a>";
        echo "</div>";


        /*
            In topics table, change category_id to 1 where topics.category_id=$category->id.
            In populate-tables.php I am setting the 'Unassigned Topics' category to id 1 in categories table.
            When we delete a category and set the associated topics categegory_id to 1,
            we are the topics to the 'Unassigned Topics' category in side navbar'
        */

        if ($topic->reassignTopicCategoryId()) {
            //echo '<h1>all topics under this category were moved to 1 (Unassigned Topics Tab) --if any--</h1>';
        } else {
            //echo '<h1>topics under this category were moved to NOT 1</h1>';
        }
    } else {
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Unable to delete category.";
        echo "</div>";
    }
}

?>

<div class="card container">
    <div class="card-body">
        <h4 class="card-title text-danger">
            <p><i class="icon-warning22"></i> If you delete this category only the category is deleted.</p>
            <p><i class="icon-warning22"></i> Any topics and associated courses are moved to the Unassigned Categories tab.</p>
            <p class="text-info"><i class="icon-info22 "></i> You can reassign or delete a topic under the Unassigned Categories tab once the topic is moved there.</p>
        </h4>
    </div>
</div>

<div class="card container">
    <div class="card-body">
        <h3 class="card-title">Confirm Category Delete</h3>
        <h6 class="card-subtitle mb-2 text-muted">Are you sure you want to delete this category?</h6>
        <form id='delete-category-form' action='delete_category.php?id=<?php echo $id;?>' method='post'>
            <div class="form-group">
                <input type="text" name='category_name' class="form-control" id="categoryName" placeholder="<?php echo $category_name?>" disabled>
                <input type="hidden" name='category_id' value='<?php echo $id?>'>
            </div>
        <button type="submit" class="btn btn-danger">Yes, Delete Category</button>
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

$('#delete-category-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.category_id.value.trim()) {
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
