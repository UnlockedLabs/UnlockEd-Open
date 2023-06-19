<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category.php';
require_once dirname(__FILE__).'/../objects/category_enrollments.php';
require_once dirname(__FILE__).'/../objects/users.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$category_enrollments = new CategoryEnrollment($db);
$users = new User($db);

if ($_GET) {

    //get GET data
    $category_enrollments->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: missing CATEGORY ID.');
    $category_name = isset($_GET['category_name']) ? $_GET['category_name'] : die('ERROR: missing CATEGORY NAME.');
    
    echo <<<_ENROLLMENTSHEAD
    <!-- Multiple selection -->
    <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title">Enroll Students in {$category_name}</h5>
        </div>
        <form class="enrollments" id='enroll-students-form' action='enrollments/enroll_category.php' method='post' data-category-id='{$category_enrollments->category_id}' data-fouc>
            <div class="card-body">
                <p class="mb-3">To enroll students into {$category_name} move names from the left column to the right.<br/>
                To unenroll students into {$category_name} move names from the right column to the left.<br/>
                <span class="font-size-xs">NOTE: Names in gray are enrolled in cohorts and cannot be unenrolled from school.</span></p>
                <p class="mb-3">When you are satisfied with your changes, then click ENROLL/UNENROLL STUDENTS.</p>
                <select name="category_enrollments" multiple="multiple" class="form-control listbox-no-selection" data-fouc>
_ENROLLMENTSHEAD;

    $cat_id = $category_enrollments->category_id;
    $cohort_enroll_array = $category->readAllCohortStudentsByCatId($cat_id);
    $stmt = $category_enrollments->readAllStudents();
    $enrollments_array = array();
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        extract($row);
        $username = ucfirst($username);
        $enrollments_array[] = $student_id;
        if (in_array($student_id, $cohort_enroll_array)) {
            echo "<option value='{$student_id}' selected='selected' disabled='disabled'>{$username}</option>";
        } else {
            echo "<option value='{$student_id}' selected='selected'>{$username}</option>";
        }
    }

    $stmt2 = $users->readAll();
    while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
        extract($row2);
        $username = ucfirst($username);
        if (in_array($id, $enrollments_array)) {
            continue;
        }
        echo "<option value='{$id}'>{$username}</option>";
    }

echo <<<_ENROLLMENTSTAIL
                </select>
                <button type="submit" class="d-flex btn btn-primary ml-auto">Enroll/Unenroll Students</button>
                <input type="hidden" name="categoryName" value="{$category_name}">
            </div>
        </form>
    </div>
    <!-- /multiple selection -->
_ENROLLMENTSTAIL;

}
            
if ($_POST) {
    $enrollees = isset($_POST['enrolledIds']) ? $_POST['enrolledIds'] : [];
    $unenrollees = isset($_POST['unenrolledIds']) ? $_POST['unenrolledIds'] : [];
    $cat_id = $_POST['categoryId'];
    $cat_name = $_POST['categoryName'];

    // delete record(s) of unenrolled student(s)
    foreach ($unenrollees as $unenrollId) {
        $category_enrollments->category_id = $cat_id;
        $category_enrollments->student_id = $unenrollId;
        $category_enrollments->delete();
    }

    // add record(s) of enrolled student(s)
    foreach ($enrollees as $enrollId) {
        $category_enrollments->category_id = $cat_id;
        $category_enrollments->student_id = $enrollId;
        if ($category_enrollments->rowExists()) {
            continue;
        }
        $category_enrollments->create();
    }

    // tell the user enrollments were completed
    echo <<<_ALERT
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Enrollments for {$cat_name} were completed.
        </div>
_ALERT;

}

?>

<script>
$('#enroll-students-form').on('submit', function(e) {
        // prevent form submission
        e.preventDefault();

        var $content = $("#content-area-div");
        var url = e.target.action;
        var $category_id = $(this).data('category-id');
        var category_name = e.target.categoryName.value;
        var serializedForm = $(this).serialize();
        var unenrolledArray = e.target.category_enrollments_helper1;
        var enrolledArray = e.target.category_enrollments_helper2;
        var unenrolledIdArray = [];
        var enrolledIdArray = [];
        var html = "";
        var count = 0;

        for (let i=0; i<unenrolledArray.length; i++) {
            if (unenrolledArray[i].getAttribute('selected')) {
                count++;
            }
        }
        if (count > 0) {
            html += "<h6 class='ml-5' style='text-align:left;'>Unenroll the following students:</h6><ul class='ml-5' style='text-align:left'>"
            for (let i=0; i<unenrolledArray.length; i++) {
                if (unenrolledArray[i].getAttribute('selected')) {
                    unenrolledIdArray.push(unenrolledArray[i].value);
                    html += "<li>" + unenrolledArray[i].text + "</li>";
                }
            }
            html += "</ul>";
        }

        count = 0;
        for (let i=0; i<enrolledArray.length; i++) {
            if (!enrolledArray[i].getAttribute('selected')) {
                count++;
            }
        }        
        if (count > 0) {
            html += "<h6 class='ml-5' style='text-align:left;'>Enroll the following students:</h6><ul class='ml-5' style='text-align:left'>"
            for (let i=0; i<enrolledArray.length; i++) {
                if (!enrolledArray[i].getAttribute('selected')) {
                    enrolledIdArray.push(enrolledArray[i].value);
                    html += "<li>" + enrolledArray[i].text + "</li>";
                }
            }
            html += "</ul>";
        }

        swal({
            title: 'Are you sure you want to perform the following actions?',
            html: html,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        unenrolledIds:unenrolledIdArray,
                        enrolledIds:enrolledIdArray,
                        categoryId:$category_id,
                        categoryName:category_name
                    },
                    timeout: 30000,
                    beforeSend: function() {
                        $content.html('<div id="load">Loading</div>');
                    },
                    complete: function() {
                        $('#load').remove();
                    },
                    error: function(data) {
                        swal({
                            title: "Error",
                            html: data.statusText,
                            confirmButtonColor: '#3085d6',
                            confirmButtonClass: 'btn btn-info',
                            allowOutsideClick: false,
                            confirmButtonText: 'OK',
                            type: "error"
                        });
                        $content.html(data.responseText);
                    }
                }).done(function(data) {
                    swal({
                        title: "Success",
                        html: "<h6>Enrollments updated!</h6>",
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "success"
                    });
                    $content.html(data);
                    var queryString = 'category_id=' + $category_id;
                    // get number of enrollments for category 
                    $.get(
                        "enrollments/enroll_category_count.php",
                        queryString
                    ).done(function(data) {
                        $("#cat-student-num > span > span").text(data);
                    });

                    // get students enrolled in category 
                    $.get(
                        "enrollments/enroll_category_list.php",
                        queryString
                    ).done(function(data) {
                        $(".student-list").html(data);
                    });
                }).fail(function() {
                    swal({
                        title: "Error",
                        html: data.statusText,
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "error"
                    });
                    $content.html('<div id="load">Please try again soon.</div>');
                });

            } else if (result.dismiss === swal.DismissReason.cancel) {
                swal({
                    title: "Cancelled",
                    text: "Enrollments have not been changed.",
                    confirmButtonColor: '#3085d6',
                    confirmButtonClass: 'btn btn-info',
                    allowOutsideClick: false,
                    confirmButtonText: 'OK',
                    type: "info"
                });
                $('.listbox-no-selection').trigger('bootstrapDualListbox.refresh', true);
            }
        });
    });

</script>
