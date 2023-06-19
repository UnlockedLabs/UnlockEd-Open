<?php

/**
 * Lesson
 *
 * Handle the Lesson
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
require_once dirname(__FILE__).'/objects/course.php';
require_once dirname(__FILE__).'/objects/media.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$courses = new Course($db);
$media = new Media($db);

$course_id = $_GET['courseId'];
$category_id = $_GET['category_id'];

$courses->id = $course_id;
$media->course_id = $course_id;
$media->student_id = $_SESSION['user_id'];

//check for iframe
$courses->readOne();

$topic_id = $courses->topic_id;

//check if there is a url
if ($courses->iframe != null) {
    echo '<div class="d-flex" id="iframe_loading_spinner"><strong>Loading external content...</strong><div class="spinner-grow ml-auto" role="status" aria-hidden="true"></div></div>';
    echo '<iframe style="height: 100%; width: 100%; border: none" onload="$(\'#iframe_loading_spinner\').hide().remove();" src="' . $courses->iframe . '"></iframe>';
    die();
}

$cat_id = $courses->readCatIdByCourseId($course_id);

if ($_SESSION['admin_num'] == 5                                                           // Site Admin
|| ($_SESSION['admin_num'] == 4 && in_array($cat_id, $_SESSION['admin']['cat']))          // Category Admin and category admin of category to which course belongs
|| ($_SESSION['admin_num'] == 3 && in_array($course_id, $_SESSION['admin']['course']))) { // Course Admin and course admin for THIS course
    echo <<<LESSON
        <div id="new-lesson-form"></div>
LESSON;
}


//this method queries the lesson table
$stmt = $courses->readLessonByCourseId();
//$stmt->rowCount(); might be a better way to detect no-lessons
$count = 0;

echo "<div class='accordion-default' id='contentPanel'>";

while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
    $count++;
    extract($row);

    $media->lesson_id = $id;

    echo "<div class='accordion' id='contentPanel-$id'>";
    echo "<div class='card'>";
    echo "<div class='card-header' id='headingOne-$id'>";
    echo "<h2 class='mb-0 card-title'>";
    echo $media->showMediaProgressbar();
    echo "<button class='btn btn-link lc-lesson-media' type='button' data-toggle='collapse' data-course-id='$courses->id' data-lesson-id='$id' data-cat-id='$cat_id' data-target='#collapseOne-$id' aria-expanded='true' aria-controls='collapseOne-$id'>";
    echo "<i class='icon-book3 ml-2'></i> $lesson_name";
    echo "</button>";
    if ($_SESSION['admin_num'] == 5                                                           // Site Admin
    || ($_SESSION['admin_num'] == 4 && in_array($cat_id, $_SESSION['admin']['cat']))          // Category Admin and category admin of category to which course belongs
    || ($_SESSION['admin_num'] == 3 && in_array($course_id, $_SESSION['admin']['course']))) { // Course Admin and course admin for THIS course
        include 'lesson_admin_buttons.php';
    }
    echo "</h2>";
    echo "</div>";
    echo "<div id='collapseOne-{$id}' class='collapse' aria-labelledby='headingOne-{$id}'' data-parent='#contentPanel'>";
    echo "<div class='card-body'>";

    if ($_SESSION['admin_num'] == 5                                                           // Site Admin
    || ($_SESSION['admin_num'] == 4 && in_array($cat_id, $_SESSION['admin']['cat']))          // Category Admin and category admin of category to which course belongs
    || ($_SESSION['admin_num'] == 3 && in_array($course_id, $_SESSION['admin']['course']))) { // Course Admin and course admin for THIS course
        //the modal is in lesson_editor_update.php for the moment
        echo "<button type='button' class='btn btn-primary lesson-instructions mb-2' data-lesson-id='$id' data-toggle='modal' data-target='#modal_full'>Add or edit instructions <i class='icon-pencil ml-2'></i></button>";
    }


    //course description
    if (trim($editor_html)) {
        echo "<div class='card' id='lesson-card-id-{$id}'>";
        echo "<div class='card-body'>";
        echo $editor_html;
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='card' id='lesson-card-id-{$id}' style='display:none;'>";
        echo "<div class='card-body'>";
        echo "</div>";
        echo "</div>";
    }

    echo <<<QUIZ
    <div id='lesson-quiz-{$id}'>
        <!-- quiz on the lesson page will be displayed here for admin purposes -->
    </div>
QUIZ;

    /*
    * Media from from the media table will go here.
    * Sending an ajax on click (see script tags below), passing in the course and lesson id.
    * See below for the ajax js
    */
    echo "<div id='lesson-media-{$id}'></div>";
    echo '</div> <!--.card-body-->';
    echo '</div> <!--.collapse-->';
    echo '</div> <!--.card-->';
} //end outter while

echo '</div> <!--.accordian-default-->';

//$stmt->rowCount(); might be a better way to detect no-lessons
if (!$count) {
    include 'course_no_lessons_alert.php';
}

if ($_SESSION['admin_num'] == 5                                                           // Site Admin
|| ($_SESSION['admin_num'] == 4 && in_array($cat_id, $_SESSION['admin']['cat']))          // Category Admin and category admin of category to which course belongs
|| ($_SESSION['admin_num'] == 3 && in_array($course_id, $_SESSION['admin']['course']))) { // Course Admin and course admin for THIS course
    include 'lesson_editor_update.php';
}

?>

<script>

/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

//holds the lesson id number of the lesson clicked
var lessonClicked = [];

//get lesson media by course id
$('.lc-lesson-media').on('click', function(e) {

    var courseId = $(this).data('course-id');
    var lessonId = $(this).data('lesson-id');
    var catId = $(this).data('cat-id');
    var $content = $("#lesson-media-"+lessonId);
    var url = 'lesson_media.php?course_id='+courseId+'&lesson_id='+lessonId;
    var lesson_name = $(this).text();
    
    //update navigation header information
    $elem = $('#lc-navigation-header');
    $elem.find('.lesson-name').text(lesson_name).attr('href', url).slideDown();
    $elem.find('.media-name').text('').hide();

    //send ajax request if lesson content has not already been loaded
    if ( !lessonClicked.includes(lessonId) ) {
        $.ajax({
            type: 'GET',
            url: url,
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
                // //update navigation header information
                // $elem = $('#lc-navigation-header');
                // $elem.find('.lesson-name').text(lesson_name).attr('href', url).slideDown();
                // $elem.find('.media-name').text('').hide();

            },
            complete: function() {
                $('#load').remove();
            },
            success: function(data) {
                $content.html(data);
                //add lessonId to lessonClicked to keep the user from repeating a successful ajax request.
                lessonClicked.push(lessonId);
            },
            error: function(data) {
                $content.html(data.responseText);
            },
            fail : function() {
                $content.html('<div id="load">Please try again soon.</div>');
            }
        });
    }
});

//get html for creating a lesson
$('.create-lesson-href').on('click', function(e) {

    e.preventDefault();

    scrollToTopCustom();

    //var $content = $("#content-area-div");
    var $content = $("#new-lesson-form");
    var url = e.target.href;

    $.ajax({
        type: 'GET',
        url: url,
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

//create a lesson
$('.create-lesson').on('click', function(e) {

    e.preventDefault();

    $category_id = $(this).data('category-id');
    $topic_id = $(this).data('topic-id');
    $course_id = $(this).data('course-id');

    scrollToTopCustom();

    //var $content = $("#content-area-div");
    var $content = $("#new-lesson-form");
    var url = 'create_lesson.php';

    $.ajax({
        type: 'GET',
        url: url,
        data: {
            category_id:$category_id,
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

//get html for editing a lesson
$('.update-lesson-href').on('click', function(e) {

    e.preventDefault();

    scrollToTopCustom();

    var $content = $("#content-area-div");
    var url = e.target.href;

    $.ajax({
        type: 'GET',
        url: url,
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

//get html for editing a lesson
$('.delete-lesson-href').on('click', function(e) {

    e.preventDefault();

    scrollToTopCustom();

    var $content = $("#content-area-div");
    var url = e.target.href;

    $.ajax({
        type: 'GET',
        url: url,
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