<?php

/**
 * Create Question
 *
 * Process create question form and insert question into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Insert/create question.
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

//ensure admin user (admin is 2 and above)
if (($_SESSION['admin_num'] < 2)) {
    die('<h1>Restricted Action!</h1>');
}

// include database and object files
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
// require_once(dirname(__FILE__).'/objects/users.php');
// require_once(dirname(__FILE__).'/objects/quiz.php');
require_once dirname(__FILE__).'/objects/question.php';
require_once dirname(__FILE__).'/objects/quiz_question.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// $quiz = new Quiz($db);
$question = new Question($db);
$quizquestion = new QuizQuestion($db);
// $users = new User($db);

// $question_text = isset($_POST['question_text']) ? $_POST['question_text'] : die('ERROR: missing QUESTION TEXT.');
// $quiz->course_id = $course_id;
// $question->question_text = $question_text;

// if the form was submitted
if ($_POST) {
    if (($_POST['quiz_id'])) {
        // if you enter this conditional, $_SESSION['quiz_id'] already set
        // $question->id = isset($_POST['quiz_id']) ? $_POST['bank_id'] : die('ERROR: missing ID.');
        
        $question->question_text = isset($_POST['question_text']) ? $_POST['question_text'] : die('ERROR: missing QUESTION TEXT.');
        $question->bank_id = isset($_POST['bank_id']) ? $_POST['bank_id'] : die('ERROR: missing BANK ID.');
        $question->question_id = isset($_POST['question_id']) ? $_POST['question_id'] : die('ERROR: missing QUESTION ID.');
        $question->admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : die('ERROR: missing ADMIN ID.');
        $question->create();

        // after creating question, assign it to a quiz through quiz_question
        if ($_SESSION['quiz_question_id']) {
            $quizquestion->question_id = $_SESSION['quiz_question_id'];
            $quizquestion->quiz_id = $_POST['quiz_id'];
            $quizquestion->points = $_POST['points'];
            $quizquestion->question_position = $_POST['question_position'];
            $quizquestion->create();
            // die();
        }
    } else { // if unable to create the question, tell the user
        echo "<div class='alert alert-danger alert-dismissable'>";
        echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        echo "Unable to create question.";
        echo "</div>";
    }
}
