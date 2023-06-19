<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category.php';
require_once dirname(__FILE__).'/../objects/course.php';
require_once dirname(__FILE__).'/../objects/submission.php';

//ensure admin user (admin is 2 and above)
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// $cohort = new Cohort($db);
$course = new Course($db);
$category = new Category($db);

if ($_GET) {
    // get GET data
    $cat_id = $category->id = isset($_GET['cat_id']) ? $_GET['cat_id'] : die('ERROR: missing CATEGORY ID.');
    $category_name = $category->category_name = isset($_GET['cat_name']) ? $_GET['cat_name'] : die('ERROR: missing CATEGORY NAME.');

    // get list of Canvas courses in category
    $url = "http://192.168.1.1:3000/api/v1/courses";
    $token = '36aojg4yflfZZqQFwF0VVLdivw1pMiJ1ywLGOzXSPT0SoeeEZHxj80ODhRGA0B2M';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' .$token ) );
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    $curlData = curl_exec($curl);
    curl_close($curl);
    
    $canvas_array = json_decode($curlData, true);

    // get list of courses in category
    $stmt = $category->readCoursesByCatId();

    $course_buttons_html = <<<_HEAD
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-center">Courses for {$category_name}</h3>
_HEAD;

    if (!$stmt->rowCount() && !$canvas_array) {
        $course_buttons_html .= <<<_BODY
                <div class='text-center text-muted'>
                    <h3>No courses</h3>
                </div>
            </div>
            <div class='card-body'>
                <div class='row'>
_BODY;
    } else {
        $course_buttons_html .= <<<_BODY
                <div class='text-center'>
                    <p>To view grades for a particular course, click on the appropriate course button</p>
                </div>
            </div>
            <div class='card-body'>
                <div class='row d-flex justify-content-center'>
_BODY;
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row); // id, course_name, iframe
            if ($iframe) {
                $course_buttons_html .= "<div class='d-flex col-sm-3 p-1'><button type='button' id='{$id}' class='btn btn-primary w-100 course-grade-button' data-course-id='{$id}' data-course-name='{$course_name}'>(Canvas) {$course_name}</button></div>";
            } else {
                $course_buttons_html .= "<div class='d-flex col-sm-3 p-1'><button type='button' id='{$id}' class='btn btn-primary w-100 course-grade-button' data-course-id='{$id}' data-course-name='{$course_name}'>{$course_name}</button></div>";
            }
        }

        if ($canvas_array) {
            for ($i = 0; $i < count($canvas_array); $i++) {
                $course_buttons_html .= "<div class='d-flex col-sm-3 p-1'><button type='button' id='{$canvas_array[$i]['id']}' class='btn btn-primary w-100 canvas-course-grade-button' data-course-id='{$canvas_array[$i]['id']}' data-course-name='{$canvas_array[$i]['name']}'>{$canvas_array[$i]['name']}</button></div>";
            }
        }
    }

    $course_buttons_html .= <<<_TAIL
                </div>
            </div>
            <div id='cat-course-grades'></div>
        </div>
_TAIL;

    echo $course_buttons_html;
}
?>

<script>
// get the quiz grades for all the students in a course
$('.course-grade-button').on('click', function(e){
    e.preventDefault();

    var $course_id = $(this).data('course-id');
    var $course_name = $(this).data('course-name');
    var url = 'grades/view_course_grades.php';
    var $content = $('#cat-course-grades');
    var course_quiz_object;

    $.ajax({
        type: 'GET',
        url: url,
        data: {
            course_id:$course_id,
            course_name:$course_name
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
        if (data.includes('text-muted')) {
            $content.html(data);
        } else {
            course_quiz_object = JSON.parse(data);

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    course_id:$course_id,
                    course_name:$course_name
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
                ULQuizBars.init(course_quiz_object);
                DatatableButtonsHtml5.init(course_quiz_object);
            }).fail(function() {
                $content.html('<div id="load">Please try again soon.</div>');            
            });
        }

    }).fail(function() {
        $content.html('<div id="load">Please try again soon.</div>');            
    });

});

// get the quiz grades for all the students in a Canvas course
$('.canvas-course-grade-button').on('click', function(e) {
    e.preventDefault();
    
    var course_id = this.id;
    var course_name = this.textContent;
    var quiz_ids = new Array();
    var url = 'grades/view_course_grades_canvas.php';
    var $content = $('#cat-course-grades');
    var course_quiz_object;

    $.ajax({
        type: 'GET',
        url: 'grades/view_course_grades_canvas.php',
        data: {
            course_id: course_id,
            course_name: course_name,
            url: 'http://192.168.1.1:3000/api/v1/courses/' + course_id + '/quizzes',
            access_token: '36aojg4yflfZZqQFwF0VVLdivw1pMiJ1ywLGOzXSPT0SoeeEZHxj80ODhRGA0B2M'
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
        if (data.includes('text-muted')) {
            $content.html(data);
        } else {
            console.log(data);
            course_quiz_object = JSON.parse(data);

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    course_id:course_id,
                    course_name:course_name
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
                ULQuizBars.init(course_quiz_object);
                DatatableButtonsHtml5.init(course_quiz_object);
            }).fail(function() {
                $content.html('<div id="load">Please try again soon.</div>');            
            });

        }        

    }).fail(function() {
                $content.html('<div id="load">Please try again soon.</div>');            
    });

});
</script>
