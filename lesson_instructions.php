<?php

/**
 * Lesson Instructions
 *
 * Handle the Lesson Instructions
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
require_once dirname(__FILE__).'/objects/lesson.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$lesson = new Lesson($db);

$id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: Lesson ID.');

$lesson->id = $id;

$lesson->readOne();

echo $lesson->editor_html;
