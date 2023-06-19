<?php

/**
 * Create Answer
 *
 * Process create answer form and insert answer into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Insert/create answer.
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
require_once dirname(__FILE__).'/objects/answer.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$answer = new Answer($db);

// if the form was submitted
if (($_POST)) {
    $answer->answer_text = isset($_POST['answer_text']) ? $_POST['answer_text'] : die('ERROR: missing ANSWER TEXT.');
    $answer->question_id = isset($_POST['question_id']) ? $_POST['question_id'] : die('ERROR: missing QUESTION GUID.');
    $answer->correct = isset($_POST['correct']) ? $_POST['correct'] : die('ERROR: missing CORRECT.');
    $answer->answer_position = isset($_POST['answer_position']) ? $_POST['answer_position'] : die('ERROR: missing ANSWER POSITION.');

    if ($answer->create()) {
        die();
    } else { // if unable to create the answer, tell the user
        // CHRISNOTE: does this work right?
        // echo "<div class='alert alert-danger alert-dismissable'>";
        //     echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        //     echo "Unable to create question.";
        // echo "</div>";
    }
}
