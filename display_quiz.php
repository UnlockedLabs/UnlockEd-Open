<?php

/**
 * Display Quiz
 *
 * Handle Displaying Quiz
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
require_once dirname(__FILE__).'/objects/lesson.php';
require_once dirname(__FILE__).'/objects/quiz.php';
// require_once(dirname(__FILE__).'/objects/question.php');
require_once dirname(__FILE__).'/objects/quiz_question.php';
require_once dirname(__FILE__).'/objects/answer.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// $courses = new Course($db); // CHRISNOTE: not using this
$lessons = new Lesson($db);
$quiz = new Quiz($db);
$quizquestion = new QuizQuestion($db);
$quizanswer = new Answer($db);


$quiz_id = $_GET['quiz_id'];
//$course_id = $_GET['id'];
// $course_id = $_GET['course_id'];
// $lesson_id = $_GET['lesson_id'];
//$_SESSION['course_time_in'] = date(); //analytics
//var_dump($_SESSION);

// $courses->id = $course_id;
// $lessons->course_id = $course_id;
$quiz->quiz_id = $quiz_id;
$quizquestion->quiz_id = $quiz_id;

$quiz->readOne(); // properties: quiz_name, quiz_desc, lesson_id, admin_id, created

echo <<<QUIZINSTRUCTIONS
    <div class="card-header bg-white header-elements-inline">
        <h3 class="card-title">{$quiz->quiz_name}</h3>
    </div>
    <div class="card-body">
        <form class="wizard-form steps-validation" id='take-quiz-form' action='submit_quiz.php' method='post' data-fouc>
            <h6>Instructions</h6>
            <fieldset>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                {$quiz->quiz_desc}
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
QUIZINSTRUCTIONS;

$i = 0; // counter for question number
$total_points = 0; // count of total points for quiz
$quizquestions = $quizquestion->readAll(); // properties: question_id, question_text, bank_id, admin_id, created, points (from quiz_questions), question_position (from quiz_questions)
while ($row = $quizquestions->fetch(\PDO::FETCH_ASSOC)) {
    extract($row); // question_text, bank_id, question_id, admin_id, question_position, created
    $i++;
    echo <<<QUESTIONHEAD
            <h6>Question {$i}</h6>
            <fieldset>
                <div class="row align-items-center">
                    <div class="col-md-6"> <!-- maybe change class to "row" -->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row justify-content-center font-size-lg">
                                    <div>
                                        {$question_text}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row ml-0">
QUESTIONHEAD;
    $total_points += $points;
    $quizanswer->question_id = $id;
    $quizanswers = $quizanswer->readAllByQuizQuestionRandom();
    while ($answer_row = $quizanswers->fetch(\PDO::FETCH_ASSOC)) {
        extract($answer_row);
        echo <<<QUIZANSWERS
                            <div class="col-md-12">
                                <div class="row mt-1 mb-1">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-input-styled required" name="question_{$i}_response" data-correct="{$correct}" value="{$correct},{$answer_text}" data-fouc>
                                            {$answer_text}
                                        </label>
                                    </div>
                                </div>
                            </div>
QUIZANSWERS;
    }

    echo <<<QUESTIONTAIL
                        </div>
                    </div>
                </div>
            </fieldset>
        <input type='hidden' name='question_id_{$i}' value='{$question_id}'>
        <input type='hidden' name='question_text_{$i}' value='{$question_text}'>
        <input type='hidden' name='question_points_{$i}' value='{$points}'>
QUESTIONTAIL;
}
echo <<<QUIZTAIL
        <input type='hidden' name='lesson_id' value='{$quiz->lesson_id}'>
        <input type='hidden' name='quiz_id' value='{$quiz_id}'>
        <input type='hidden' name='question_count' value='{$i}'>
        <input type='hidden' name='total_points' value='{$total_points}'>
        </form>
    </div> <!--/.card-body-->
    <script>
        FormWizardDraggable.init();
    </script>    
QUIZTAIL;

?>

<script>
// (function(){

    $('#take-quiz-form').on('submit', function(e) {

        e.preventDefault();

        var serializedForm = $('#take-quiz-form').serialize();
        var url = e.target.action;
        var $content = $("#content-area-div");

        $.ajax({
            type: 'POST',
            url: url,
            data: serializedForm,
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

</script>
