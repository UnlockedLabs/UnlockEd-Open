<?php
namespace unlockedlabs\unlocked;

require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort.php';
require_once dirname(__FILE__).'/../objects/cohort_enrollments.php';
require_once dirname(__FILE__).'/../objects/users.php';
require_once dirname(__FILE__).'/../objects/course.php';
require_once dirname(__FILE__).'/../objects/GUID.php';



// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cohort = new Cohort($db);
$cohort_enrollments = new CohortEnrollment($db);
$users = new User($db);
$course = new Course($db);
$guidcls = new GUID();

if ($_GET) {

    // get GET data
    $course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing COURSE ID.');
    $course_name = isset($_GET['course_name']) ? $_GET['course_name'] : die('ERROR: missing COURSE NAME.');
    $cat_id = $course->readCatIdByCourseId($course_id);
    // get user info for facilitator list, unenrolled list, and enrolled list
    $stmt = $cohort_enrollments->readAllStudents();
    $enrollments_array = array();
    $enrolled_students = '';
    $unenrolled_students = '';
    $facilitators = '';

    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        extract($row);
        $enrollments_array[] = $student_id;
        $enrolled_students .= "<option value='{$student_id}' selected='selected'>{$username}</option>";
    }

    $stmt2 = $users->readAll();
    while ($row2 = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
        extract($row2);
        $username = ucfirst($username);
        if ($admin_id == 1) {
            if (!in_array($id, $enrollments_array)) {
                $unenrolled_students .= "<option value='{$id}'>{$username}</option>";
            }
            continue;
        }
        if (!in_array($id, $enrollments_array)) {
            if ($id == $_SESSION['user_id']) {
                $unenrolled_students .= "<option value='{$id}' disabled='disabled'>{$username}</option>";
            } else {
                $unenrolled_students .= "<option value='{$id}'>{$username}</option>";
            }
        }
        if ($id == $_SESSION['user_id']) {
            $facilitators = "<option value='{$id}'>{$username}</option>" . $facilitators;
        } else {
            $facilitators .= "<option value='{$id}'>{$username}</option>";
        }
    }

    $cohort_id = trim($guidcls->uuid());

    echo <<<_COHORT1
    <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title">Create Cohort for {$course_name}</h5>
        </div>
        <form class="enrollments" id='create-cohort-form' action='enrollments/create_cohort.php' method='post' data-fouc>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-auto">Cohort Name:</label>
                            <div class="col-lg-8">
                                <input name="cohort_name" id="cohort-name" class="form-control" placeholder="Enter cohort name..." type="text" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
_COHORT1;
                        
    if (($_SESSION['admin_num'] == 4 && !in_array($cat_id, $_SESSION['admin']['cat']) && !in_array($course_id, $_SESSION['admin']['course']))
    || ($_SESSION['admin_num'] == 3 && !in_array($course_id, $_SESSION['admin']['course']))
    || ($_SESSION['admin_num'] == 2)) {
        $facilitator_name = ucfirst($_SESSION['username']);
        echo <<<_FACILITATOR
                            <label class="col-form-label col-lg-auto">Facilitator:</label>
                            
                            <div class="col-lg-8">
                                <select name="facilitator_id" id="facilitator" class="form-control form-control-uniform" data-fouc>
                                    <option value='{$_SESSION['user_id']}'>{$facilitator_name}</option>
                                </select>
                            </div>
_FACILITATOR;
    } else {
        echo <<<_ADMIN
                            <label class="col-form-label col-lg-auto">Facilitator:</label>
                            <div class="col-lg-8">
                                <select name="facilitator_id" id="facilitator" class="form-control form-control-uniform" data-fouc>
                                    {$facilitators}
                                </select>
                            </div>
_ADMIN;
    }
    echo <<<_COHORT2
                        </div>
                    </div>
                </div>
                <p class="mb-3">To enroll students into <span class="cohortName">_____</span>, move names from the left column to the right.<br/>
                To unenroll students into <span class="cohortName">_____</span>, move names from the right column to the left.</p>
                <p class="mb-3">When you are satisfied with your changes, then click ENROLL/UNENROLL STUDENTS.</p>
                <select name="cohort_enrollments" multiple="multiple" class="form-control listbox-no-selection" data-fouc>
_COHORT2;

    echo $enrolled_students;
    echo $unenrolled_students;

echo <<<_ENROLLMENTSTAIL
                </select>
                <button type="submit" class="d-flex btn btn-primary ml-auto">Enroll/Unenroll Students</button>
                <input type="hidden" name="cat_id" value="{$cat_id}">
                <input type="hidden" name="course_name" value="{$course_name}">
                <input type="hidden" name="course_id" value="{$course_id}">
                <input type="hidden" name="cohort_id" value="{$cohort_id}">
            </div>
        </form>
    </div>
_ENROLLMENTSTAIL;

}
            
if ($_POST) {
    $cohort->id = isset($_POST['cohortId']) ? $_POST['cohortId'] : die('ERROR: missing COHORT ID.');
    $cohort->cohort_name = isset($_POST['cohortName']) ? $_POST['cohortName'] : die('ERROR: missing COHORT NAME.');
    $cohort->facilitator_id = isset($_POST['facilitatorId']) ? $_POST['facilitatorId'] : die('ERROR: missing FACILITATOR ID');
    $cohort->course_id = isset($_POST['courseId']) ? $_POST['courseId'] : die('ERROR: missing COURSE ID');

    if ($cohort->create()) {
        $_SESSION['admin']['facilitator'][] = $cohort->id;
        return true;
    } else {
        return false;
    }
        
}

?>

<script>
$('.form-control-uniform').uniform();

$('#cohort-name').on('change', function() {
    $('.cohortName').html(this.value);
});

$('#facilitator').on('change', function() {
    var option_value = `option[value="${this.value}"]`;
    $('select[name="cohort_enrollments"]').find(option_value).attr('disabled', 'disabled').siblings().removeAttr('disabled');
    $('.listbox-no-selection').trigger('bootstrapDualListbox.refresh', true);
});

$('#create-cohort-form').on('submit', function(e) {
    // prevent form submission
    e.preventDefault();

    var $content = $("#content-area-div");
    var url = e.target.action;
    var cohort_name = e.target.cohort_name.value;
    var cohort_id = e.target.cohort_id.value;
    var facilitator_id = e.target.facilitator_id.value;
    var facilitator_name = e.target.facilitator_id.selectedOptions[0].firstChild.nodeValue;
    // var course_name = e.target.course_name.value;
    var course_id = e.target.course_id.value;
    var category_id = e.target.cat_id.value;
    var serializedForm = $(this).serialize();
    var unenrolledArray = e.target.cohort_enrollments_helper1;
    var enrolledArray = e.target.cohort_enrollments_helper2;
    var unenrolledIdArray = [];
    var enrolledIdArray = [];
    var html = "";
    var count = 0;

    html += "<h6 class='ml-5 text-left'>Create the following cohort:</h6>";
    html += "<p class='ml-5 text-left'>Cohort Name: " + cohort_name + "<br/>Cohort Facilitator: " + facilitator_name + "</p>";

    for (let i=0; i<unenrolledArray.length; i++) {
        if (unenrolledArray[i].getAttribute('selected')) {
            count++;
        }
    }
    if (count > 0) {
        html += "<h6 class='ml-5 text-left'>Unenroll the following students:</h6><ul class='ml-5 text-left'>"
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
        html += "<h6 class='ml-5 text-left'>Enroll the following students:</h6><ul class='ml-5 text-left'>"
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
                    cohortId:cohort_id,
                    cohortName:cohort_name,
                    facilitatorId:facilitator_id,
                    courseId:course_id
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
                        type: "error",
                        onClose: function() {
                            fetchLessons(category_id, course_id);
                        }
                    });
                    $content.html(data.responseText);
                }
            }).done(function(data) {
                $.ajax({
                    type: 'POST',
                    url: 'enrollments/enroll_cohort.php',
                    data: {
                        enrolledIds:enrolledIdArray,
                        unenrolledIds:unenrolledIdArray,
                        cohortId:cohort_id,
                        cohortName:cohort_name
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
                            type: "error",
                            onClose: function() {
                                fetchLessons(category_id, course_id);
                            }
                        });
                        $content.html(data.responseText);
                    }
                }).done(function(data) {
                    swal({
                        title: "Success",
                        html: "<h6>Cohort created!</h6>",
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "success",
                        onClose: function() {
                            fetchLessons(category_id, course_id);
                        }
                    });
                    $content.html(data);
                    // var queryString = 'cohort_id=' + cohort_id;
                    var queryString = 'courseId=' + course_id;

                    // get number of enrollments for course 
                    $.get(
                        "enrollments/enroll_course_count.php",
                        queryString
                    ).done(function(data) {
                        $("#course-students > span > span").text(data);
                    });

                    // get number of cohorts
                    $.get(
                        "enrollments/cohort_course_count.php",
                        queryString
                    ).done(function(data) {
                        $("#course-cohorts > span > span").text(data);
                    });

                    // get students enrolled in course 
                    $.get(
                        "enrollments/enroll_course_list.php",
                        queryString
                    ).done(function(data) {
                        $(".student-list").html(data);
                    });

                    $.get(
                        "enrollments/cohort_course_list.php",
                        queryString
                    ).done(function(data) {
                        $(".cohort-list").html(data);
                    });

                }).fail(function() {
                    swal({
                        title: "Error",
                        html: data.statusText,
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "error",
                        onClose: function() {
                            fetchLessons(category_id, course_id);
                        }
                    });
                    $content.html('<div id="load">Please try again soon.</div>');
                });
            });

        } else if (result.dismiss === swal.DismissReason.cancel) {
            swal({
                title: "Cancelled",
                text: "Enrollments have not been changed.",
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                type: "info",
                onClose: function() {
                    fetchLessons(category_id, course_id);
                }
            });
            $('.listbox-no-selection').trigger('bootstrapDualListbox.refresh', true);
        }
    });
});

</script>
