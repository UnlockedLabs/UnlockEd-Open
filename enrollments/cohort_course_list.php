<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort.php';
require_once dirname(__FILE__).'/../objects/course.php';
require_once dirname(__FILE__).'/../objects/course_administrators.php';
require_once dirname(__FILE__).'/../objects/category_administrators.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cohort = new Cohort($db);
$course = new Course($db);
$course_admins = new CourseAdministrator($db);
$cat_admins = new CategoryAdministrator($db);

if ($_GET) {
    // get GET data
    $cohort->course_id = isset($_GET['courseId']) ? $_GET['courseId'] : die('ERROR: missing COURSE ID.');
    $course_id = $cohort->course_id;
    $cat_id = $course->readCatIdByCourseId($course_id);
    // get array of admins for category
    $cat_admin_array = [];
    $cat_admins->category_id = $cat_id;
    $stmt1 = $cat_admins->readAllAdministrators();
    while ($row = $stmt1->fetch(\PDO::FETCH_ASSOC)) {
        extract($row);
        $cat_admin_array[] = $cat_admin_id;
    }
    // get array of admins for course
    $course_admin_array = [];
    $course_admins->course_id = $course_id;
    $stmt2 = $course_admins->readAllAdministrators();
    while ($row = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
        extract($row);
        $course_admin_array[] = $course_admin_id;
    }
    // read all cohorts for particular course id
    $stmt = $cohort->readAllByCourse();

    if (!$stmt->rowCount()) {
        echo "<li class='nav-item text-center text-muted usertag'>No Cohorts</li>";
    }

    $user_id = $_SESSION['user_id'];

    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        extract($row); // id, cohort_name, facilitator_id, facilitator_name, course_id, created
        $facilitator_name = ucfirst($facilitator_name);
        // if user is Facilitator, do not show other Facilitator's cohorts
        if ((($_SESSION['admin_num'] == 2) && ($user_id != $facilitator_id))
        || (($_SESSION['admin_num'] == 3) && ($user_id != $facilitator_id) && (!in_array($user_id, $course_admin_array)))
        || (($_SESSION['admin_num'] == 4) && ($user_id != $facilitator_id) && (!in_array($user_id, $cat_admin_array)))) {
            continue;
        }
        $email_link = "";
        if($_SESSION['current_site_settings']['email_enabled'] == 'true'){
            $email_link = "<a href='./lc_email/lc_compose.php?recipient_id={$facilitator_id}' class='dropdown-item'><i class='icon-mail5'></i> Email facilitator</a>";
        }
        echo <<<_COHORT
            <li class="media cohorttag">
                <div class="mr-3">
                    <a href="#" class="btn bg-transparent border-white text-white cohort-ham rounded-round border-2 btn-icon" data-cohort_id="{$id}" data-facilitator_id="{$facilitator_id}">
                        <i class="icon-collaboration"></i>
                    </a>
                </div>
                <div class="media-body align-self-center">
                    {$cohort_name}
                    <div class="text-muted font-size-xs">{$facilitator_name}</div>
                </div>
                <div class="ml-3 align-self-center">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle caret-0 cohort-ham" data-toggle="dropdown"><i class="icon-more2"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" data-cat_id="{$cat_id}" data-cohort_id="{$id}" data-course_id="{$course_id}" data-facilitator_id="{$facilitator_id}">
                            {$email_link}
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item cohort-delete" data-hello="world"><i class="icon-trash"></i> Delete cohort</a>
                        </div>
                    </div>
                </div>
            </li>
_COHORT;
    }

    echo <<<_FUNCTIONS
        <script>
        $('.cohort-delete').on('click', function(e){
            e.preventDefault();
            var cohort_id = $(this).parent().data('cohort_id');
            var category_id = $(this).parent().data('cat_id');
            var cohort_name = $(this).closest('.media').children('.media-body').text();
            var url = 'enrollments/delete_cohort.php'; //e.target.href;
            var course_id = $(this).parent().data('course_id');
            var content = $('#content-area-div');        
    
            swal({
                title: 'Are you sure you want to delete this quiz?',
                html: "<h6 class='mt-2'><em>"+cohort_name+"</em></h6><p>You won't be able to revert this!</p>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
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
                            cohort_id:cohort_id,
                            cohort_name:cohort_name
                        },
                        timeout: 30000,
                        beforeSend: function() {
                            content.html('<div id="load">Loading</div>');
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
                            content.html(data.responseText);
                        }
                    }).done(function(data) {
                        swal({
                            title: "Success",
                            html: "<h6>Cohort deleted!</h6>",
                            confirmButtonColor: '#3085d6',
                            confirmButtonClass: 'btn btn-info',
                            allowOutsideClick: false,
                            confirmButtonText: 'OK',
                            type: "success",
                            onClose: function() {
                                fetchLessons(category_id, course_id);
                            }
                        });
                        content.html(data);
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
                        content.html('<div id="load">Please try again soon.</div>');            
                    });
                }
                else if (result.dismiss === swal.DismissReason.cancel) {
                    swal({
                        title: "Cancelled",
                        text: "The cohort was not deleted.",
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "info",
                        onClose: function() {
                            fetchLessons(category_id, course_id);
                        }
                    });
                }
            });
        });
        </script>

_FUNCTIONS;

}
