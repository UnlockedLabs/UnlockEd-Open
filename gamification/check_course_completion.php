<?php

/**
 * Check Course Completion
 *
 * Handle getting course completion percentage.
 *
 * PHP version 7.2.5
 *
 * @category  Gamification
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
require_once dirname(__FILE__).'/../objects/course.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// instantiate object
$course = new Course($db);

// set category property values
$course_id = $_GET['courseId'];

if (!empty($course_id)) {
    $course->id = $course_id;
    echo $course->calculateCourseAvg();
} else {
    echo 0;
}
