<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort.php';
require_once dirname(__FILE__).'/../objects/course.php';
require_once dirname(__FILE__).'/../objects/submission.php';
require_once dirname(__FILE__).'/../objects/course_administrators.php';
require_once dirname(__FILE__).'/../objects/category_administrators.php';

//ensure admin user (admin is 2 and above)
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cohort = new Cohort($db);
$course = new Course($db);
$course_admins = new CourseAdministrator($db);
$cat_admins = new CategoryAdministrator($db);
$submission = new Submission($db);

if ($_GET) {
    // get GET data
    $course_id = $cohort->course_id = $course->id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing COURSE ID.');
    $course_name = isset($_GET['course_name']) ? $_GET['course_name'] : die('ERROR: missing COURSE NAME.');
    // get category id of course
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

    $user_id = $_SESSION['user_id'];
    $cohorts = array();

    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        extract($row); // id, cohort_name, facilitator_id, facilitator_name, course_id, created
        $facilitator_name = ucfirst($facilitator_name);
        // if user is Facilitator, do not show other Facilitator's cohorts
        if ((($_SESSION['admin_num'] == 2) && ($user_id != $facilitator_id))
        || (($_SESSION['admin_num'] == 3) && ($user_id != $facilitator_id) && (!in_array($user_id, $course_admin_array)))
        || (($_SESSION['admin_num'] == 4) && ($user_id != $facilitator_id) && (!in_array($user_id, $cat_admin_array)))) {
            continue;
        } else {
            $cohorts[] = $id;
        }
    }

    // get list of quiz ids in course
    $quizIds = $course->readQuizzesByCourseId();
    if (count($quizIds) == 0) {
        echo "<div class='text-center text-muted'><h3>No quizzes in course</h3></div>";
    } else {
        $quiz_results = array(); //push $student_results into this array
        $quiz_data = array();
        $data_array = array(); //push $quiz_data into this array
        
        $keptGrade = 'highest';
        $quizId = '';
        $noSubmissions = 0;
        
        foreach ($cohorts as $cohort) {
            $stmt = $submission->readCourseQuizObjectByCohort($cohort, $keptGrade);
            if (!$stmt->rowCount()) {
                $noSubmissions++;
            }
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                extract($row); // course_id, course_name, quiz_id, quiz_name, student_id, student_name, and student_grade for assignment

                if (!in_array($quiz_id, $quizIds)) {
                    continue;
                }
                
                if ($quiz_id != $quizId) { // if its a new quiz...
                    if ($quizId != '') { // if its not the first quiz...
                        $quiz_data["quizResults"] = $quiz_results;
                        $data_array[] = $quiz_data;
                        $quiz_results = array();
                        $quiz_data = array();
                    }
                    $quiz_data["quizId"] = $quiz_id;
                    $quiz_data["quizName"] = $quiz_name;
                    $quizId = $quiz_id;
                }
    
                $student_results = array();
                $student_results["studentId"] = $student_id;
                $student_results["studentName"] = ucfirst($student_name);
                $student_results["studentGrade"] = $student_grade;
                $quiz_results[] = $student_results;
            }
            $quiz_data["quizResults"] = $quiz_results;
            $data_array[] = $quiz_data;
        }

        if ($noSubmissions) {
            echo "<div class='text-center text-muted'><h3>No quiz submissions</h3></div>";
        } else {
            $course_quiz_object = array("courseId"=>"{$course_id}", "courseName"=>"{$course_name}");
            $course_quiz_object["data"] = $data_array;
            $cqo = json_encode($course_quiz_object);
            // echo "<div style='display:none;'>{$cqo}</div>";
            echo $cqo;
        }
    }
}

if ($_POST) {

    $course_id = isset($_POST['course_id']) ? $_POST['course_id'] : die('ERROR: missing COURSE ID.');
    $course_name = isset($_POST['course_name']) ? $_POST['course_name'] : die('ERROR: missing COURSE NAME.');

        echo <<<_QUIZZES
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">Grades for {$course_name}</h5>
                    <!--
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="coursebuttons">
                        <div class="col">
                    -->
                            <!-- course buttons are injected here when you click the admin sidebar "Courses" button -->
                <!--
                        </div>
                    </div>
                    -->
                </div>
                <div class="card-body">
                    <h5>At a Glance</h6>
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="bars_basic"></div>
                    </div>
                </div>
                <div class="card-body">
                    <h5>Gradebook</h5>
                    <table class="table datatable-button-html5-columns">
                        <thead>
                            <tr id="quiz-columns">
                                <th>Name</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

_QUIZZES;

}
