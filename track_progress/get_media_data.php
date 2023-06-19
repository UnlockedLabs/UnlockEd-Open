<?php

/**
 * Get Media Data
 *
 * Gets media data.
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

$_SESSION['student_id'] = $_SESSION['user_id'];

$media_id = isset($_GET['media_id']) ? $_GET['media_id'] : die('ERROR: missing Media Id.');
$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : die('ERROR: missing Student Id.');

$media_progress->media_id = $media_id;
$media_progress->student_id = $student_id;
$stmt = $media_progress->readRowByStudentAndMediaId();

//return media row as json
if (!$stmt) {
    echo json_encode(array('isData' => 'false'));
}
else {
    $stmt['isData'] = 'true';
    echo json_encode($stmt);
}
