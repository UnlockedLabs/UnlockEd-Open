<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category.php';
require_once dirname(__FILE__).'/../objects/submission.php';

//ensure admin user (admin is 2 and above)
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$submission = new Submission($db);

if ($_GET) {
    // get GET data
    $cat_id = $category->id = isset($_GET['cat_id']) ? $_GET['cat_id'] : die('ERROR: missing CATEGORY ID.');
    $cat_name = isset($_GET['cat_name']) ? $_GET['cat_name'] : die('ERROR: missing CATEGORY NAME.');
    // get list of quiz ids in category
    $quizIds = $category->readQuizzesByCatId();
    if (count($quizIds) == 0) {
        echo "<div class='text-center text-muted'><h3>No quizzes in category</h3></div>";
    } else {
        $quiz_results = array(); //push $student_results into this array
        $quiz_data = array();
        $data_array = array(); //push $quiz_data into this array
        
        $keptGrade = 'highest';
        $stmt = $submission->readCourseQuizObjectByCatId($cat_id, $keptGrade);
        $quizId = '';
        
        if (!$stmt->rowCount()) {
            echo "<div class='text-center text-muted'><h3>No quiz submissions</h3></div>";
        } else {
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                extract($row); // course_id, course_name, quiz_id, quiz_name, student_id, student_name, and student_grade for assignment
                
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
    
            $course_quiz_object = array("courseId"=>"{$course_id}", "courseName"=>"{$course_name}");
            $course_quiz_object["data"] = $data_array;
            $cqo = json_encode($course_quiz_object);
            // echo "<div style='display:none;'>{$cqo}</div>";
            echo $cqo;
        }
    }
}

if ($_POST) {

    $cat_id = isset($_POST['cat_id']) ? $_POST['cat_id'] : die('ERROR: missing CATEGORY ID.');
    $cat_name = isset($_POST['cat_name']) ? $_POST['cat_name'] : die('ERROR: missing CATEGORY NAME.');

        echo <<<_QUIZZES
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">Grades for {$cat_name}</h5>
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
