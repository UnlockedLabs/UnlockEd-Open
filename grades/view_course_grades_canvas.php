<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
// require_once dirname(__FILE__).'/../objects/cohort.php';
require_once dirname(__FILE__).'/../objects/course.php';
require_once dirname(__FILE__).'/../objects/submission.php';

//ensure admin user (admin is 2 and above)
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// $cohort = new Cohort($db);
$course = new Course($db);
$submission = new Submission($db);

if ($_GET) {
    // get GET data
    $course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing COURSE ID.');
    $course_name = isset($_GET['course_name']) ? $_GET['course_name'] : die('ERROR: missing COURSE NAME.');
    $url = isset($_GET['url']) ? $_GET['url'] : die('ERROR: missing URL.');
    $access_token = isset($_GET['access_token']) ? $_GET['access_token'] : die('ERROR: missing ACCESS TOKEN.');
    // get list of quiz ids in course

    $url = "http://192.168.1.1:3000/api/v1/courses/{$course_id}/quizzes";
    $token = $access_token;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' .$token ) );
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    $curlData = curl_exec($curl);
    curl_close($curl);
    $array = json_decode($curlData, true);
    $quiz_ids = array();

    for ($i = 0; $i < count($array); $i++) {
        $quiz_ids[] = $array[$i]['id'];
    }

    if (count($quiz_ids) == 0) {
        echo "<div class='text-center text-muted'><h3>No quizzes in course</h3></div>";
    } else {

        $quiz_results = array(); //push $student_results into this array
        $quiz_data = array();
        $data_array = array(); //push $quiz_data into this array
        
        $quizId = '';
        $submission_count = 0;
        
        foreach ($quiz_ids as $quiz_id){
            $url = "http://192.168.1.1:3000/api/v1/courses/{$course_id}/quizzes/{$quiz_id}/submissions?include[]=user&include[]=quiz";
            $token = $access_token;
    
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' .$token ));
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_ENCODING, "");
            $curlData = curl_exec($curl);
            curl_close($curl);
            
            $submissions = json_decode($curlData, true);
            $submission_count += count($submissions['quiz_submissions']);
            if (count($submissions['quiz_submissions']) == 0) {
                continue;
            }
            $quiz_data['quizId'] = $quiz_id;
            $quiz_data['quizName'] = $submissions['quizzes'][0]['title'];
            $quiz_data['pointsPossible'] = $submissions['quizzes'][0]['points_possible'];

            for ($i = 0; $i < count($submissions['quiz_submissions']); $i++) {
                // extract($submission);
                $student_results = array();
                $student_results['studentId'] = $submissions['users'][$i]['id'];
                $student_results['studentName'] = ucfirst($submissions['users'][$i]['name']);
                $student_results['studentGrade'] = $submissions['quiz_submissions'][$i]['kept_score'];
                $quiz_results[] = $student_results;
            }
            $quiz_data['quizResults'] = $quiz_results;
            $data_array[] = $quiz_data;
            $quiz_results = array();
            $quiz_data = array();
        }

        if ($submission_count == 0) {
            echo "<div class='text-center text-muted'><h3>No quiz submissions</h3></div>";
        } else {
            $course_quiz_object = array("courseId"=>"{$course_id}", "courseName"=>"{$course_name}");
            $course_quiz_object["data"] = $data_array;
            $cqo = json_encode($course_quiz_object);
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
                    <h3 class="card-title text-center">Grades for {$course_name}</h3>
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
