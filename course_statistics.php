<?php
namespace unlockedlabs\unlocked;
include_once 'session-validation.php';

// include database and object files
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/course.php';
include_once 'objects/lesson.php';
include_once 'objects/quiz.php';
include_once 'objects/quiz_question.php';
include_once 'objects/submission.php';

// instantiate database and objects
$database = new Database();
$db = $database->getConnection();
$lesson = new Lesson($db);
$quiz = new Quiz($db);
$submission = new Submission($db);

if ($_POST) {

    $question_count = isset($_POST['question_count']) ? $_POST['question_count']: die('ERROR: missing QUESTION COUNT');
    $total_points = isset($_POST['total_points']) ? $_POST['total_points'] : die('ERROR: missing TOTAL POINTS');
    $quiz->quiz_id = isset($_POST['quiz_id']) ? $_POST['quiz_id'] : die('ERROR: missing QUIZ ID');
    $lesson->id = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : die('ERROR: missing LESSON ID');
    $question_id = [];
    $answer_text = [];
    $incorrect_question_text = [];
    $attempt_number = [];
    $attempt_score = [];
    $correct_answer_count = 0;
    $correct_point_total = 0;

    for ($i=1; $i<=$question_count; $i++) {
        /* This is another way of getting isCorrect w/o using eval: */
        $response_arg = "question_{$i}_response";
        $response = isset($_POST["{$response_arg}"]) ? $_POST["{$response_arg}"] : die("ERROR: missing QUESTION {$i} RESPONSE");
        list($isCorrect, $ansText) = explode(",", $response);
        $answer_text[] = $ansText;
        $question_arg = "question_id_{$i}";
        $question_id[] = isset($_POST["{$question_arg}"]) ? $_POST["{$question_arg}"] : die("ERROR: missing QUESTION ID {$i}");
        
        // eval('$isCorrect = $_POST["question_{$i}_response"];');
        if ($isCorrect == 'yes') {
            $correct_answer_count++;
            eval('$correct_point_total += $_POST["question_points_{$i}"];');
        } else {
            eval('$incorrect_question_text[] = $_POST["question_text_{$i}"];');
        }
    }

    $percent_correct = round(($correct_point_total / $total_points) * 100, 2);

    // assign submission properties
    $submission->student_id = $_SESSION['user_id'];
    $submission->assignment_id = isset($_POST['quiz_id']) ? $_POST['quiz_id'] : die('ERROR: missing QUIZ ID');
    $submission->type = 'quiz';
    $submission->attempt = $submission->attemptNumber() + 1;
    $submission->score = $correct_point_total;
    $submission->grade = $percent_correct . "%";    // This can change according to the type of grading the assignment receives:
                                                    // 'pass_fail', 'percent', 'letter_grade', 'gpa_scale', 'points'
    $submission->questions = implode(",", $question_id);
    $submission->submitted_answers = implode(",", $answer_text);

    // create submission
    $submission->create();

    $quiz->readOne();

    // get all of a student's submissions for this quiz
    $stmt = $submission->readStudentAssignmentSubmissions();
    while ($row_submission = $stmt->fetch(\PDO::FETCH_ASSOC)){
        extract($row_submission);
        $attempt_phrase = "Attempt {$attempt}";
        $attempt_number[] = $attempt_phrase;
        $attempt_score[] = $score;
        $att_numbers = implode(",", $attempt_number);
        $att_scores = implode(",", $attempt_score);
    }

    $username = ucfirst($_SESSION['username']);

    // display quiz results
    echo <<<QUIZRESULTSHEAD
    <div class="card">
        <div class="card-header bg-white header-elements-inline text-md-center">
            <h3 class="card-title">Results for {$quiz->quiz_name}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <h5>Unlock Ed says:</h5>
                    </div>
                    <div class="row">
                        <p>Very good, {$username}! You got <strong>{$correct_answer_count}/{$question_count}</strong> questions correct for a grade of <strong>{$percent_correct}%</strong>!</p>
                    </div>
                    <div class="row">
                        <p>Here are the questions you got wrong:</p>
                    </div>
                    <div class="row">
                        <ul>
QUIZRESULTSHEAD;

    foreach ($incorrect_question_text as $text) {
        echo "<li>{$text}</li>";
    }

    echo <<<QUIZRESULTSTAIL
                        </ul>
                    </div>
                    <div class="row">
                        <p>Based on the above incorrect questions, I suggest you review the following lessons:</p>
                    </div>
                    <div class="row">
                        <ul>
                            <li>Counting [question tagging not fully implemented yet]</li>
                            <li>Basic Subtraction [question tagging not fully implemented yet]</li>
                            <li>Counting II [question tagging not fully implemented yet]</li>
                            <li>Subtraction II [question tagging not fully implemented yet]</li>
                        </ul>
                    </div>
                    <div class="row">
                        <p>Keep pushing! You can do it!</p>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    <div class="row">
                        <img src="http://unlockedlabs.org/unlocked/images/GloreStill.png" class="img-fluid" alt="">
                    </div>                
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-md-center">
                        <h4>Submissions for {$quiz->quiz_name}</h4>
                    </div>
                    <input type='hidden' name='submissions_info' value='{$quiz->quiz_name}' data-attempt_numbers='{$att_numbers}' data-attempt_scores='{$att_scores}'>
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="columns_basic"></div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-md-center">
                        <h4>Concept Cloud Based on Incorrect Questions</h4>
                    </div>
                    <div class="chart-container has-scroll">
                        <div class="chart svg-center" id="d3-bubbles"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
QUIZRESULTSTAIL;

} else {
    echo "ERROR: quiz not submitted properly.";
}

if ($percent_correct >= 70) {

    $lesson->readOne();
    $student_id = $_SESSION['user_id'];
    echo "<script>updateQuizProgress('{$lesson->course_id}', '{$lesson->id}', '{$student_id}', '{$quiz->quiz_id}', '{$quiz->quiz_name}');</script>";
}
?>

<script>
// assign arguments for submissions columns instantiation
var $numbers = $("input[name='submissions_info']").data('attempt_numbers');
var $scores = $("input[name='submissions_info']").data('attempt_scores');
var $name = $("input[name='submissions_info']").val();

// create JSON object
// NOTE: Assuming strict mode, declaring using var will allow you to
// redeclare same variable in same scope. let will not.
/* 
var myJson = 
    {
        "name": "chris",
        "children": [
            {
                "name": "animate",
                "children": [
                    {"name": "Addition", "size": 64},
                    {"name": "Subtraction", "size": 94},
                    {"name": "Multiplication", "size": 22},
                    {
                        "name": "interpolate",
                        "children": [
                            {"name": "Polynomials", "size": 33},
                            {"name": "Factoring", "size": 42}
                        ]
                    },
                    {"name": "Geometry", "size": 11},
                    {"name": "Linear Algebra", "size": 56},
                    {"name": "Something Mathematical", "size": 16}
                ]
            },
            {
                "name": "physics",
                "children": [
                    {"name": "Even Numbers", "size": 68},
                    {"name": "Division", "size": 81}
                ]
            },
            {
                "name": "query",
                "children": [
                    {"name": "Multiplication", "size": 23},
                    {"name": "Quadratic Equations", "size": 43}
                ]
            },
            {
                "name": "scale",
                "children": [
                    {"name": "Fill in the Number", "size": 10},
                    {"name": "Scaling", "size": 83}
                ]
            }
        ]
    };
*/

var myJson =
{
    "name": "tags",
    "children":
    [
    
        {
            "name":  "Addition",
            "children":
                [
                    {"name": "Addition", "size": 2}
                ]
        },
        {
            "name":  "Subtraction",
            "children":
                [
                    {"name": "Subtraction", "size": 3}
                ]
        },
        {
            "name":  "Multiplication",
            "children":
                [
                    {"name": "Multiplication", "size": 1}
                ]
        },
        {
            "name":  "Division",
            "children":
                [
                    {"name": "Division", "size": 4}
                ]
        }
    ]
};

/* 
// create js object
var myObj = 
    {
    name: "flare",
    children: [
    {
    name: "animate",
    children: [
        {name: "Addition", size: 64},
        {name: "Rounding", size: 94},
        {name: "Counting", size: 22},
        {
        name: "interpolate",
        children: [
        {name: "Polynomials", size: 33},
        {name: "Factoring", size: 42}
        ]
        },
        {name: "Geometry", size: 11},
        {name: "Linear Algebra", size: 56},
        {name: "Something Mathematical", size: 16}
    ]
    },
    {
    name: "physics",
    children: [
        {name: "Even Numbers", size: 68},
        {name: "Division", size: 81}
    ]
    },
    {
    name: "query",
    children: [
        {name: "Multiplication", size: 23},
        {name: "Quadratic Equations", size: 43}
    ]
    },
    {
    name: "scale",
    children: [
        {name: "Fill in the Number", size: 10},
        {name: "Scaling", size: 83}
    ]
    }
    ]
};
 */

// initialize bar chart module
ULQuizSubmissionsColumns.init($name, $numbers, $scores);
// D3Bubbles.init(myJson);
submissionBubbles(myJson) // this function can use either JSON or JS objects

d3.selectAll(".d3-bubbles-node").on('click', function(bubble) {
    console.log(bubble);
    // alert(bubble.bubbleName + " was clicked");

    // scrollToTopCustom();

    var $content = $("#content-area-div");

    $.ajax({
        type: 'GET',
        url: 'course_tags.php?category_name=Advanced Education&categoryId=1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774&topicId=05c2ef38-9caa-477e-8c8f-dd2b8e4630cb',
        timeout: 30000,
        beforeSend: function() {
            $content.html('<div id="load">Loading</div>');
        },
        complete: function() {
            $('#load').remove();
        },
        error: function(data) {
            $content.html(data.responseText);
        },
        fail : function() {
            $content.html('<div id="load">Please try again soon.</div>');
        }
    }).then(function(data) {
        $content.html(data);
        $.ajax({
            type: 'GET',
            url: 'lesson.php?category_id=1b511dc2-1b94-4d6b-a5ac-4c3dccb0d774&courseId=7c436aeb-9c1a-4325-a9b7-858b1a766bec',
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
            },
            complete: function() {
                $('#load').remove();
            },
            error: function(data) {
                $content.html(data.responseText);
            },
            fail : function() {
                $content.html('<div id="load">Please try again soon.</div>');
            }
        }).then(function(data) {
            $content.html(data);
        }).then(function() {
            $(".lc-lesson-media[data-lesson-id='3affc0e3-0d5e-449b-b42a-d1107550b5d3']").click();
            $(".d3-tip").remove();
        });
    });
    
    // .done(function() {

    //     scrollToTopCustom();

    // });
 /*    
    .then(function() {

    });
 */

});
</script>
