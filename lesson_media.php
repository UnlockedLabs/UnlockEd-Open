<?php

/**
 * Lesson Media
 *
 * Handle the Lesson Media
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

$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing course id.');
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: missing lesson id.');

// include database and object files
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/course.php';
require_once dirname(__FILE__).'/objects/media.php';
require_once dirname(__FILE__).'/objects/quiz.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$courses = new Course($db);
$cat_id = $courses->readCatIdByCourseId($course_id);
$media = new Media($db);
$media->course_id = $course_id;
$media->lesson_id = $lesson_id;
$quiz = new Quiz($db);
$quiz->lesson_id = $lesson_id;
$media->student_id = $_SESSION['user_id'];
    
echo <<<VIDEO
      <!--video-->
      <!-- Only MP4, WebM, and Ogg video are supported by the HTML5 standard. -->
      <!--<h4 class="font-weight-semibold text-center">Videos</h4>-->
      <div class="text-center">
        <video id="videoPlayer-{$lesson_id}" style="display:none;" width="640" height="480" poster="./images/TLC_Logo_4.png" controls>
            <!--<source src="" type="video/mp4">-->
            Your browser does not support the video tag.
        </video>
    </div>
VIDEO;

echo <<<AUDIO
<!--audio-->
<!-- Only MP3, WAV, and Ogg audio are supported by the HTML5 standard. -->
<!--<h4 class="font-weight-semibold text-center">Audio Files</h4>-->
<div class="text-center">
    <audio id="audioPlayer-{$lesson_id}" style="display:none; width:640px;" controls>
        <!--<source src="" type="audio/mpeg">-->
        Your browser does not support the audio element.
    </audio>
</div>
AUDIO;

if ($_SESSION['admin_num'] == 5                                                           // Site Admin
|| ($_SESSION['admin_num'] == 4 && in_array($cat_id, $_SESSION['admin']['cat']))          // Category Admin and category admin of category to which course belongs
|| ($_SESSION['admin_num'] == 3 && in_array($course_id, $_SESSION['admin']['course']))) { // Course Admin and course admin for THIS course
    include 'media_update/reorder_modal.php';
    include 'media_update/add_files_modal.php';
    include 'create_quiz.php'; // CHRISNOTE: can I (or should I) put this code in add_quiz_module.php?
}

$stmt2 = $media->readAllByCourseAndLessonId();
$lessonQuizzes = $quiz->readQuizzesByLessonId();

if ($stmt2->rowCount() || $quiz->countByLessonId()) {
    if ($stmt2->rowCount()) {
        if ($quiz->countByLessonId() == 0) {
            $media_number = "Media: <span id='mediaCount'>{$stmt2->rowCount()}</span>";
        } else {
            $media_number = "Media: <span id='mediaCount'>{$stmt2->rowCount()}</span>, ";
        }
    } else {
        $media_number = '';
    }

    if ($quiz->countByLessonId() != 0) {
        $quiz_number = "Quizzes: <span id='quizCount'>{$quiz->countByLessonId()}</span>";
    } else {
        $quiz_number = '';
    }

    

    echo <<<_LEARNINGMODCARD
        <div class="card">
            <div class="card-header text-center">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4"><h3 class="card-title">Learning Modules</h4></div>
                    <div class="col-md-4 d-flex align-items-center">
                        <div class="ml-auto">
                            <span class="text-info">{$media_number}{$quiz_number}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class='media-list media-list-container media-list-bordered' id='media-list-target'>
_LEARNINGMODCARD;

    // echo '<div class="card">';
    // echo '<div class="card-header text-center">';
    // echo '<h3 class="card-title">Learning Modules</h4>';
    // echo '</div>';
    // echo '<div class="card-body">';
    // echo "<ul class='media-list media-list-container media-list-bordered' id='media-list-target'>";

    while ($row = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
        extract($row);
        include 'lesson_entry.php';
    }
    
    while ($quizrow = $lessonQuizzes->fetch(\PDO::FETCH_ASSOC)) {
        extract($quizrow);
        include 'quiz_entry.php';
    }

    echo '</ul></div></div>';
} else {
        echo '<h1 class="text-center">No Learning Modules</h1>';
}

/**
 * This creates the listing of quizzes that is visible from the
 * lesson page.
 */

// $lessonQuizzes = $quiz->readQuizzesByLessonId();

// if ( $quiz->countByLessonId() ) {
// // if ( $lessonQuizzes->rowCount() ) {

//     echo '<div class="card">';
//     echo '<div class="card-header text-center">';
//     echo '<h3 class="card-title">Quizzes</h3>';
//     echo '</div>';
//     echo '<div class="card-body">';
//     echo "<ul class='media-list media-list-container media-list-bordered' id='quiz-list-target'>";
//     // echo "<ul class='media-list media-list-container' id='media-list-target-left'>";
    
//     while ($row = $lessonQuizzes->fetch(\PDO::FETCH_ASSOC)) {
//         extract($row);
//         require 'quiz_entry.php';
//     }
    
//     echo '</ul></div></div>';
// } else {
//     echo '<h1 class="text-center">No Quiz Files</h1>';
// }

?>

<script>
// to show quiz in preview area beneath lesson instructions
$('.quiz-display-link').on('click', function(e) {

    //prevent form submission
    e.preventDefault();

    var $quiz_id = ($(this).data('quiz_id'));
    var $lesson_id = ($(this).data('lesson_id'));
    var $quiz_name = $(this).data('quiz-name');

    // var $content = $(`#lesson-quiz-${$lesson_id}`); // CHRISNOTE: maybe change this to #content-area-div
    var $content = $('#content-area-div');
    var url = `display_quiz.php?quiz_id=${$quiz_id}`;

    $.ajax({
        type: 'GET',
        url: url,
        // data: serializedForm,
        timeout: 30000,
        beforeSend: function() {
            // $content.html('<div id="load">Loading</div>');
            $elem = $('#lc-navigation-header');
            $elem.find('.media-name').html('<i class="icon-brain"></i> '+$quiz_name).slideDown();

        },
        complete: function() {
            // $('#load').remove();
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

// to delete quiz
$('.quiz-delete-link').on('click', function(e) {

    e.preventDefault();

    var $quiz_id = $(this).data('quiz_id');
    var $quiz_name = $(this).data('name');
    var course_id = '<?php echo "$course_id"; ?>';
    var lesson_id = '<?php echo "$lesson_id"; ?>';
    var url = `delete_quiz.php?quiz_id=${$quiz_id}`;

    swal({
        title: 'Are you sure you want to delete this quiz?',
        html: "<h6 class='mt-2'><em>"+$quiz_name+"</em></h6><p>You won't be able to revert this!</p>",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {

            //send ajax delete GET to delete_quiz.php?quiz_id=$quiz_id
            $.ajax({
                type: 'GET',
                url: url,
                timeout: 30000,
                success: function(data) {
                    quizCount = Number($("#quizCount").html())-1;
                    if (quizCount < 1) {
                        $("#quizCount").html("0");    
                    } else {
                        $("#quizCount").html(quizCount);
                    }
                    swal({
                        title: "Success",
                        html: data,
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "success"
                    });
                    $('#quiz-entry-'+$quiz_id).remove();
                    //notify the user of the need to reload the page for the progress bar to work correctly
                    progressLessonBarReload(lesson_id);
                    quizDeleted(course_id, lesson_id)
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
                },
                fail: function(data) {
                    swal({
                        title: "Error",
                        html: data.statusText,
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "error"
                    });
                }
            });

        }
        else if (result.dismiss === swal.DismissReason.cancel) {
            swal({
                title: "Cancelled",
                text: "Your file is safe.",
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                type: "info"
            });
        }
    });

});

$('.delete-media-file-href').on('click', function(e) {

    e.preventDefault();

    var displayName = $(this).data('display-name');
    var mediaId = $(this).data('media-id');

    //ensure url
    if (!mediaId) {

        swal({
            title: "Error!",
            text: "Request failed! Please refresh and try again.",
            confirmButtonColor: '#3085d6',
            confirmButtonClass: 'btn btn-info',
            allowOutsideClick: false,
            confirmButtonText: 'OK',
            type: "error"
        });

        return false;
    }

    var url = 'delete_media_file.php?id='+mediaId;
    var course_id = '<?php echo "$course_id"; ?>';
    var lesson_id = '<?php echo "$lesson_id"; ?>';

    swal({
        title: 'Are you sure you want to delete this file?',
        html: "<h6 class='mt-2'><em>"+displayName+"</em></h6><p>You won't be able to revert this!</p>",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {

            //send ajax delete get to delete_media_file.php?id=media_id
            $.ajax({
                type: 'GET',
                url: url,
                timeout: 30000,
                success: function(data) {
                    $('#media-entry-'+mediaId).remove();
                    //notify the user of the need to reload the page for the progress bar to work correctly
                    progressLessonBarReload(lesson_id);
                    mediaDeleted(course_id, lesson_id)
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
                },
                fail: function(data) {
                    swal({
                        title: "Error",
                        html: data.statusText,
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "error"
                    });
                }
            });

        }
        else if (result.dismiss === swal.DismissReason.cancel) {
            swal({
                title: "Cancelled",
                text: "Your file is safe.",
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                type: "info"
            });
        }
    });
});

function mediaDeleted(course_id, lesson_id) {
    swal({
        title: '<h4>Media Deleted!</h4>',
        html: `<p>Media was deleted.</p>`,
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            fetchMedia(course_id, lesson_id);
        },
    });
}

function fetchMedia(course_id, lesson_id) {
    scrollToTopCustom();

    //repopulate the quizzes
    var $content = $(`#lesson-media-${lesson_id}`);
    var url = `lesson_media.php?course_id=${course_id}&lesson_id=${lesson_id}`;

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
        error: function(data) {
            $content.html(data.responseText);
        }
    }).done(function(data) {
        $content.html(data);
    }).fail(function() {
        $content.html('<div id="load">Please try again soon.</div>');
    });

}

var quizform_id = "#create-quiz-form-<?php echo $lesson_id; ?>";
CreateQuizFormWizard.init(quizform_id);
</script>