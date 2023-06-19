<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category.php';
require_once dirname(__FILE__).'/../objects/course.php';
require_once dirname(__FILE__).'/../objects/course_administrators.php';

// ensure user is Site Admin
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$course_administrators = new CourseAdministrator($db);
$category = new Category($db);
$course = new Course($db);

if ($_GET) {
    // get GET data
    $cat_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
    if (isset($_GET['courseId'])) {
        $course_administrators->course_id = $_GET['courseId'];
        $course_id = $course_administrators->course_id;
        $cat_id = $course->readCatIdByCourseId($course_id);
        // get list of instructors in a particular course
        $stmt = $course_administrators->readAllAdministrators();
        if (!$stmt->rowCount()) {
            echo "<li class='nav-item text-center text-muted usertag'>No Instructors assigned</li>";
        }
    } else {
        // get list of instructors in a category
        $category->id = $cat_id;
        $stmt = $category->readInstructorsByCatId();

        if (!$stmt->rowCount()) {
            echo "<li class='nav-item text-center text-muted usertag'>No Instructors assigned</li>";
        }
        
    }

    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        extract($row); // course_admin_id, username, access_id, admin_id

        $username = ucfirst($username);
        $email_link = "";
        if($_SESSION['current_site_settings']['email_enabled'] == 'true'){
            $email_link = "<a href='./lc_email/lc_compose.php?recipient_id={$course_admin_id}' class='dropdown-item'><i class='icon-mail5'></i> Send message</a>";
        }   
        echo <<<_COURSEADMINHEAD
            <li class="media nav-item usertag">
                <a href="#" class="mr-3 position-relative" data-cat_admin_id="{$course_admin_id}" data-admin_id="{$admin_id}">
                    <img src="libs/limitless/global_assets/images/placeholders/person.jpg" width="24" height="24" class="rounded-circle" alt="">
                    <span class="badge badge-info badge-pill badge-float"></span>
                </a>
                <div class="media-body align-self-center" data-cat_admin_id="{$course_admin_id}" data-admin_id="{$admin_id}">
                    {$username}
                </div>
                <div class="ml-3 align-self-center">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle caret-0 stdnt-ham" data-toggle="dropdown"><i class="icon-more2"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" data-course_admin_id="{$course_admin_id}" data-course_id="{$course_id}" data-cat_id="{$cat_id}">
                            {$email_link} 
_COURSEADMINHEAD;
        if ($_SESSION['admin_num'] >= 4) {
            echo <<<_DELETELINK
                            <div class="dropdown-divider"></div>
                            <a href="enrollments/delete_course_admin.php" class="dropdown-item course-admin-delete"><i class="icon-trash"></i> Unassign Admin</a>
_DELETELINK;
        }
        echo <<<_COURSEADMINTAIL
                        </div>
                    </div>
                </div>
            </li>
_COURSEADMINTAIL;
    }

    echo <<<_FUNCTIONS
        <script>
        $('.course-admin-delete').on('click', function(e){
            e.preventDefault();
            var course_id = $(this).parent().data('course_id');
            var cat_id = $(this).parent().data('cat_id');
            var course_admin_id = $(this).parent().data('course_admin_id');
            var course_admin_name = $(this).parents('li.usertag').children('div.media-body').text();
            var url = e.target.href;
            var content = $('#content-area-div'); 
    
            swal({
                title: 'Are you sure you want to unassign this Instructor?',
                html: "<h6 class='mt-2'>"+course_admin_name+"</h6><p>You won't be able to revert this!</p>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, unassign!',
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
                            course_id:course_id,
                            course_admin_id:course_admin_id
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
                                type: "error"
                            });
                            content.html(data.responseText);
                        }
                    }).done(function(data) {
                        swal({
                            title: "Success",
                            html: "<h6>Instructor unassigned!</h6>",
                            confirmButtonColor: '#3085d6',
                            confirmButtonClass: 'btn btn-info',
                            allowOutsideClick: false,
                            confirmButtonText: 'OK',
                            type: "success"
                        });
                        content.html(data);
                        
                        var queryString = 'category_id=' + cat_id; //courseId=' + course_id;
    
                        // get number of instructors for course 
                        $.get(
                            "enrollments/admin_course_count.php",
                            queryString
                        ).done(function(data) {
                            $(".course-admin-num > span > span").text(data);
                        });
    
                        // get instructors assigned to course 
                        $.get(
                            "enrollments/admin_course_list.php",
                            queryString
                        ).done(function(data) {
                            $(".course-admin-list").html(data);
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
                        content.html('<div id="load">Please try again soon.</div>');            
                    });
                }
                else if (result.dismiss === swal.DismissReason.cancel) {
                    swal({
                        title: "Cancelled",
                        text: "The Instructor was not unassigned.",
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "info"
                    });
                }
            });
        });
        </script>
_FUNCTIONS;
}
