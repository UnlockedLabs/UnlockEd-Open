<?php

/**
 * Update Media Progress
 *
 * Handles updating media progress.
 *
 * PHP version 7.2.5
 *
 * @category  Track_Progress
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once '../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/media_progress.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$media_progress = new MediaProgress($db);

if (isset($_GET)) {
    /*
      @todo
      update_media_progress.php
      we should probably query media and get the required value. See js updateMediaProgress() for more information as to why.
     */

    //get post data
    $course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing Course Id.');
    $lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: missing Lesson Id.');
    $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : die('ERROR: missing Student Id.');
    $current_pos = isset($_GET['current_pos']) ? $_GET['current_pos'] : die('ERROR: missing Current Position.');
    $media_id = isset($_GET['media_id']) ? $_GET['media_id'] : die('ERROR: missing Media Id.');
    $duration = isset($_GET['duration']) ? $_GET['duration'] : die('ERROR: missing Duration.');
    $file_location = isset($_GET['file_location']) ? $_GET['file_location'] : die('ERROR: missing File Location.');
    $file_type = isset($_GET['file_type']) ? $_GET['file_type'] : die('ERROR: missing File Type.');
    $file_name = isset($_GET['file_name']) ? $_GET['file_name'] : die('ERROR: missing File Name.');
    $completed = isset($_GET['completed']) ? $_GET['completed'] : die('ERROR: missing Completed.');
    $reflection = isset($_GET['reflection']) ? $_GET['reflection'] : die('ERROR: missing Reflection.');
    $deleted = isset($_GET['deleted']) ? $_GET['deleted'] : die('ERROR: missing Deleted.');
    $required = isset($_GET['required']) ? $_GET['required'] : die('ERROR: missing Required.');

    //TODO if $completed is 1, query db.media.required and set media_progress.required to this value

    //set class object
    $media_progress->course_id = $course_id;
    $media_progress->lesson_id = $lesson_id;
    $media_progress->student_id = $student_id;
    $media_progress->current_pos = $current_pos;
    $media_progress->media_id = $media_id;
    $media_progress->duration = $duration;
    $media_progress->file_location = $file_location;
    $media_progress->file_type = $file_type;
    $media_progress->file_name = $file_name;
    $media_progress->completed = $completed;
    $media_progress->reflection = $reflection;
    $media_progress->deleted = $deleted;
    $media_progress->required = $required;

    //create or update record
    if ($media_progress->rowExists()) {
        $media_progress->update();
    } else {
        $media_progress->create();
    }
}
