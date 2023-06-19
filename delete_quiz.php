<?php

/**
 * Delete Quiz
 *
 * Handle deletion of quiz items
 * Don't allow unless user is at least admin level 2.
 * Get quiz id and delete from table.
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
require_once dirname(__FILE__).'/objects/quiz.php';
require_once dirname(__FILE__).'/objects/media_progress.php';

// instantiate database and course object
$database = new Database();
$db = $database->getConnection();

$quiz = new Quiz($db);
$media_progress = new MediaProgress($db);

// get ID of the quiz to be deleted
$id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : die('ERROR: missing QUIZ ID.');
$quiz->quiz_id = $id;
$media_progress->media_id = $id;
$media_progress->deleted = 1;
// $lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: missing LESSON ID.');
// $course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing COURSE ID.');

//this sets certain media properties
// $quiz->readOne();

//delete db row entry
if ($quiz->delete()) {
    echo "quiz deleted";
    // $user_ux = "Donzo!";
    // echo "<script>quizDeleted({$course_id}, {$lesson_id}, {$user_ux});</script>";
} else {
    //echo "<div class=\"alert alert-danger alert-dismissable\">";
        //echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        echo "Unable to delete quiz.<br />";
    //echo "</div>";
}

/*
  @todo
  delete_quiz.php
  set media_progress.delete to 1 (progress stats will mess up if we do not change this to 1)
 */

//set media_progress.delete to 1 (progress stats will mess up if we do not change this to 1)
if ($media_progress->updateDeletedColumn()) {
    //echo "TODO ux improvement here media_progress.delete set to 1.<br />";
} else {
    //echo "ERROR media_progress.delete was not set to 1.<br />";
}
