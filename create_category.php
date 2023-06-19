<?php

/**
 * Create Category
 *
 * Process create category form and insert category into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Insert/create category.
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

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//$product = new Product($db);
$category = new Category($db);
$users = new User($db);

// if the form was submitted
if ($_POST) {
    // set category property values
    $category_name = $_POST['category_name'];
    $access_id = $_POST['access_id'];
    
    if ((empty($category_name))) {
        echo "<div class='alert alert-danger'>Name cannot be empty.</div>";
    } elseif (strlen($category_name) > 64) {
        echo "<div class='alert alert-danger'>Name is too large: 24 character limit including spaces.</div>";
    } elseif (empty($access_id)) {
        echo "<div class='alert alert-danger'>Access Id cannot be empty.</div>";
    } else {
        $category->category_name = $category_name;
        $category->access_id = $access_id;

        //ensure the category does not exists
        if ($category->categoryExists()) {
            //$category->category_name=$category_name;
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Category already exists.";
            echo "</div>";
        } elseif ($category->create()) {
            // tell the user new category was created
            echo "<div class=\"alert alert-success alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Category was created. Please reload the page to see the updated Category tags. <a href='./index.php'>Reload page now.</a>";
            echo "</div>";
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Unable to create category.";
            echo "</div>";
        }
    }
}
?>
     
<div class="card container">
    <div class="card-body">
        <h3 class="card-title">Create New Category</h3>
        <form id='create-category-form' action='create_category.php' method='post'>
            <div class="form-group">
                <label for="categoryName">Category Name</label>
                <input type="text" name='category_name' class="form-control" id="categoryName" placeholder="" maxlength="64" required>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Access Level</label>
                <select class="form-control" name='access_id' id="exampleFormControlSelect1" required>
                    <option value="1">Open Enrollment</option>
                    <option value="2">Category Enrollment Required</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Category</button>
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

$('#create-category-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.category_name.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Category Name.');
        return false;
    }

    if (e.target.category_name.value.length > 64) {
        ul.errorSwalAlert("Info Warning!", 'Name is too large: 64 character limit including spaces.');
        return false;
    }

    if (!e.target.access_id.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Select Access Level.');
        return false;
    }

    var $content = $("#content-area-div");
    var url = 'create_category.php';
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