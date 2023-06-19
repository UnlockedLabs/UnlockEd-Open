<?php

/**
 * Update Catgegory
 *
 * Handle Updating Category
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
require_once dirname(__FILE__).'/objects/users.php';

// instantiate database and category object
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$users = new User($db);

// get ID of the category to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
$category->id = $id;

// if the form was submitted
if ($_POST) {
    // set category property values
    $category_name = $_POST['category_name'];
    $access_id = $_POST['access_id'];
    
    if ((empty($category_name))) {
        echo "<div class='alert alert-danger'>Category Name cannot be empty.</div>";
    } elseif (empty($access_id)) {
        echo "<div class='alert alert-danger'>Access Id cannot be empty.</div>";
    } else {
        $category->category_name = $category_name;
        $category->access_id = $access_id;
        
        // execute the query
        if ($category->update()) {
            echo "<div class=\"alert alert-success alert-dismissable\">";
                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                echo "Category was updated. Please reload the page to see the updated Category tags. <a href='./index.php'>Reload page now.</a>";
            echo "</div>";
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                echo "Unable to update category.";
            echo "</div>";
        }
    }
}

$category->readOne();
// assign values to object properties
$category_name = $category->category_name;
//access_id corresponds to access_levels.id
$access_id = $category->access_id;

?>
<!-- HTML form for updating a category -->
<div class="card container">
    <div class="card-body">
        <h3 class="card-title">Edit Category</h3>
        <form id='update-category-form' action='update_category.php?id=<?php echo $id; ?>' method='post'>
            <div class="form-group">
                <label for="categoryName">Category Name</label>
                <input type="text" name='category_name' class="form-control" id="categoryName" value="<?php echo $category_name; ?>" required>
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
                            } else if ($row['id'] > 2) {
                                continue;
                            } else {
                                echo '<option value=' . $row['access_num'] . '>' . $row['access_name'] . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
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

$('#update-category-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.category_name.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Category Name.');
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