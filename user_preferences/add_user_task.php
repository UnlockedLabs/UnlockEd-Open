<?php

/**
 * User tasks
 *
 * Handle adding user's tasks on dashboard
 *
 * PHP version 7.2.5
 *
 * @category  Tasks
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/user_tasks.php';

// instantiate database
$database = new Database();
$db = $database->getConnection();

// instantiate object
$userTask = new userTasks($db);

$userTask->id = $_SESSION['user_id'];
$userTask->task_id = $_POST['newTaskNum'];
$userTask->task = $_POST['newTask'];
$userTask->checked = 0;
$userTask->addUserTask();

?>