<?php

/**
 * Course Tags
 *
 * Detailed Description
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

// include database and object files
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/topic.php';
require_once dirname(__FILE__).'/objects/course.php';
require_once dirname(__FILE__).'/objects/lesson.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$courses = new Course($db);
$topic = new Topic($db);
$lesson = new Lesson($db);

$category_name = $_GET['category_name'];
$courses->topicId = $_GET['topicId'];
$courses->categoryId = $_GET['categoryId'];
$topic->id = $_GET['topicId'];

//tracking idea
$_SESSION['category_id'] = $courses->categoryId;

//this will set $topic->topic_url to a value (either NULL or a URL to external site)
$topic->readOne();

/**
 * TEAM: we need to talk about the event when there are courses for this category
 * and someone sets a url. This would override all the courses for this topic. Not good.
 * I think this could only happen in update_topic.php and update_course.php
 */


//check if there is a url
if ($topic->topic_url != null) {
    echo '<div class="d-flex" id="iframe_loading_spinner"><strong>Loading external content...</strong><div class="spinner-grow ml-auto" role="status" aria-hidden="true"></div></div>';
    echo '<iframe style="height: 100%; width: 100%; border: none" onload="$(\'#iframe_loading_spinner\').hide().remove();" src="' . $topic->topic_url . '"></iframe>';
    die();
}

$stmt = $courses->readCoursesByTopicId();
//<!--limitless-framework/Limitless_2_2/Bootstrap 4/Template/layout_1/LTR/default/full/ecommerce_product_list.html-->
echo '<div class="d-flex align-items-start flex-column flex-md-row">';
echo '<!-- Left content -->';
echo '<div class="w-100 overflow-auto order-2 order-md-1">';

while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
    extract($row);

    //get number of lessons for the course
    $lesson->course_id = $id;
    $lesson_count = $lesson->countByCourseId();

    $courses->id = $id;
    $course_progress = $courses->calculateCourseAvg();

    $course_cohort_array = $courses->readCohortsIdArrayByCourseId($id);
    $cat_id = $courses->readCatIdByCourseId($id);
    if (($access_id <= 2)                                                                                        // open enrollment/category enrollment required
    || ($_SESSION['admin_num'] == 5)                                                                             // Site Admin
    || ($_SESSION['admin_num'] == 4 && in_array($cat_id, $_SESSION['admin']['cat']))                             // School Admin and category admin of category to which course belongs
    || ($access_id > 2 && (array_intersect($_SESSION['enrolled']['cohort'], $course_cohort_array)))              // course enrollment required and user is enrolled in cohort
    || ($_SESSION['admin_num'] >= 2 && array_intersect($_SESSION['admin']['facilitator'], $course_cohort_array)) // Facilitator for cohort in THAT course
    || ($_SESSION['admin_num'] >= 3 && in_array($id, $_SESSION['admin']['course']))) {                           // Course Admin and he/she is Admin of THAT course
        include 'course_tag.php';
    }
}

echo '</div>';
echo '<!-- /left content -->';
echo '</div>';

if (!$stmt->rowCount()) {
    include 'course_no_courses_alert.php';
}
?>

<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

    //load course content matching the course id
    $('.lc-course-lesson').on('click', function(e) {

        e.preventDefault();

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = e.target.href;
        var atIndex = url.indexOf("?");
        var params = url.slice(atIndex+1);
        var $cat_id = $(this).data('cat-id');
        var $cat_name = $(this).data('cat-name');
        var $topic_id = $(this).data('topic-id');
        var $topic_name = $(this).data('topic-name');
        var $course_name = $(this).data('course-name');
        var $course_id = $(this).data('course-id');
        var $is_admin = $(this).data('admin');
        var $is_facilitator = $(this).data('facilitator');
        var $is_enrolled = $(this).data('enrolled');
        var $cohorts_enrolled_in = $(this).data('cohorts');
        var $admin_num = <?php echo $_SESSION['admin_num']; ?>;
        
        // hide Topic Administration sub-navigation
        $(".topic-admin").hide();
        
        // show Course Administration sub-navigation
        $(".course-admin, .students, .cohorts").show();

        $(".course-actions").data("category-name", $cat_name);
        $(".course-actions").data("category-id", $cat_id);
        $(".course-actions").data("topic-name", $topic_name);
        $(".course-actions").data("topic-id", $topic_id);
        $(".course-actions").data("course-id", $course_id);
        $(".courseName").text($course_name);

        if ($is_facilitator) {
            var $content_text = `
                <span class='admin-tooltip'><p>With the Facilitator sidebar,
                Facilitators for cohorts in a particular course are able to:
                <ul>
                    <li>view students enrolled in the particular cohort</li>
                    <li>view Instructors assigned to the particular course</li>
                    <li>view cohorts for the particular course</li>
                    <li>create and delete their own cohorts</li> 
                    <li>view the cohort gradebook</li>
                </ul></span>
                `;
            var $tooltip_title = "<span class='admin-tooltip tooltip-title'>Facilitator Sidebar</span>";
            $("#course-name > span, #stdntlist").text("My Cohort");
            $("#my-cohorts").text("My ");
            $(".course-admin").hide();
            $(".cohort-admin").show();
            $("body").addClass('sidebar-right-visible');
            $('.sub-menu, .student-container, .cohort-container').slideUp();
            $('.list-icons-item').removeClass('rotate-180');
        } else if ($is_enrolled) {
            $('#admin-title').html('Student').addClass('bg-green');
            $('#admin-help').html('<i class="icon-help text-blue-400"></i>');
            var $content_text = `
                <span class='admin-tooltip'><p>With the Student sidebar,
                Students in a particular course are able to:
                <ul>
                    <li>view all students in Student's cohort</li>
                    <li>view the Facilitator of Student's cohort</li>
                    <li>view the Student's own gradebook for the particular course</li>
                </ul></span>
                `;
            var $tooltip_title = "<span class='admin-tooltip tooltip-title'>Student Sidebar</span>";
            $(".course-admin, .cohort-admin, .students, .cohorts").hide();
            $(".student-sidebar").show();
            $("body").addClass('sidebar-right-visible');
            $('.sub-menu, .student-container, .cohort-container').slideUp();
            $('.list-icons-item').removeClass('rotate-180');
        } else if ($is_admin) {
            var $content_text = `
                <span class='admin-tooltip'><p>With the Course Administration sidebar,
                privileged users are able to:
                <ul>
                    <li>view students enrolled in the particular course
                        <br/>
                        <em><strong>Note:</strong> enrolled students are enrolled in cohorts WITHIN the course,
                        not the course itself</em></li>
                    <li>view Instructors assigned to the particular course</li>
                    <li>view cohorts for the particular course</li>
                    <li>create cohorts for the particular course (privileged administrators only)</li>
                    <li>view the gradebook for the particular course</li>
                </ul></span>
                `;
            var $tooltip_title = "<span class='admin-tooltip tooltip-title'>Course Administration Sidebar</span>";
            $("#course-name > span, #stdntlist").text($course_name);
            $("#my-cohorts").text($course_name+" ");
            $(".cohort-admin").hide();
            $("body").addClass('sidebar-right-visible');
            $('.sub-menu, .student-container, .cohort-container').slideUp();
            $('.list-icons-item').removeClass('rotate-180');
        }
        // $("#course-enroll").data('courseId', $course_id);
        // $("#cohort-enroll").data('courseId', $course_id);
        // assign data attributes to admin menu links
        $("#cohort-create, #cohort-create-fac, #course-grades, #cohort-grades, #my-grades").data('course-id', $course_id);
        $("#cohort-create, #cohort-create-fac, #course-grades, #cohort-grades, #my-grades").data('course-name', $course_name);
        
        // hide Category Administration sub-navigation
        $(".category-admin").hide();

        // show right admin sidebar toggles (and mobile)
        // for Cohort Enrollees, Instructors, School Admins, and Site Admins only
        if ($admin_num == 5 || $is_admin || $is_facilitator || $is_enrolled) {
            $("#admin_toggler_mobile").css("display", "inline");
            $("#admin-title").addClass('sidebar-right-toggle btn');
            $("#admin-help").attr('data-content', $content_text).attr('data-original-title', $tooltip_title);
        }

        // get number of enrollments for course
        $.get(
            "enrollments/enroll_course_count.php",
            params
        ).done(function(data) {
            $("#course-students > span > span").text(data);
        });

        $.get(
            "enrollments/cohort_course_count.php",
            params
        ).done(function(data) {
            $("#course-cohorts > span > span").text(data);
        });

        $.get(
            "enrollments/enroll_course_list.php",
            params
        ).done(function(data) {
            $(".student-list").html(data);
        });

        // get number of instructors for course 
        $.get(
            "enrollments/admin_course_count.php",
            params
        ).done(function(data) {
            $(".course-admin-num > span > span").text(data);
            // $(`.category-link[data-id="${course_id}"]`).click();
        });

        // get instructors assigned to course 
        $.get(
            "enrollments/admin_course_list.php",
            params
        ).done(function(data) {
            $(".course-admin-list").html(data);
        });

        // get cohort(s) user (Student) is enrolled in
        var queryString = 'cohort_enrollments=' + $cohorts_enrolled_in;
        $.get(
            "enrollments/enroll_cohort_list.php",
            queryString
        ).done(function(data) {
            $(".cohort-student-list").html(data);
        });

        // get Facilitator(s) of cohort(s) user (Student) is enrolled in
        $.get(
            "enrollments/cohort_facilitator_list.php",
            queryString
        ).done(function(data) {
            $(".facilitator").html(data);
        });

        $.ajax({
            type: 'GET',
            url: url,
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
                //update navigation header information
                $elem = $('#lc-navigation-header');
                $elem.find('.course-name').text($course_name).attr('href', url).slideDown();
                $elem.find('.lesson-name').text('').hide();
                $elem.find('.media-name').text('').hide();
                var atIndex = url.indexOf("&");
                var queryStr = 'courseId=' + url.slice(atIndex+10);

                $.get(
                    "enrollments/cohort_course_list.php",
                    queryStr
                ).done(function(data) {
                    $(".cohort-list").html(data);
                });
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

    //edit a course
    $('.update-course').on('click', function(e) {

        e.preventDefault();

        var $category_name = $(this).data('category-name');
        var $category_id = $(this).data('category-id');
        var $topic_name = $(this).data('topic-name');
        var $topic_id = $(this).data('topic-id');
        var $course_id = $(this).data('course-id');

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'update_course.php';

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                category_name:$category_name,
                category_id:$category_id,
                topic_name:$topic_name,
                topic_id:$topic_id,
                course_id:$course_id
            },
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
            },
            complete: function() {
                $('#load').remove();
            },
            error: function(data) {
                $content.html(data.responseText);
            }
        }).done(function(data) {
            $content.html(data);
        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });
    });

    //delete a course
    $('.delete-course').on('click', function(e) {

        e.preventDefault();

        var $category_name = $(this).data('category-name');
        var $category_id = $(this).data('category-id');
        var $course_id = $(this).data('course-id');

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'delete_course.php';

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                category_name:$category_name,
                category_id:$category_id,
                course_id:$course_id
            },
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
            },
            complete: function() {
                $('#load').remove();
            },
            error: function(data) {
                $content.html(data.responseText);
            }
        }).done(function(data) {
            $content.html(data);
        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });
    });

}) ();
</script>