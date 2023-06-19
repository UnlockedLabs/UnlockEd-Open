<?php

/**
 * tasks
 *
 * Handle the tasks
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


// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/user_tasks.php';

$userTask = new userTasks($db);
$stmt = $userTask->readTasks();

while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
    extract($row);
    $taskChecked = "";
    $text="";
    if ($checked == 1) {
        $taskChecked = 'checked=""';
        $text = "text-muted";
    }
    echo <<<TASK
        <div class="custom-control custom-checkbox custom-control-inline mb-1" id="$task_id">
            <input class="custom-control-input" id="task$task_id" type="checkbox" $taskChecked onclick="complete($task_id);">
            <label class="custom-control-label $text" for="task$task_id" id="desc$task_id">$task</label>
            <span class="font-size-xs text-danger cursor-pointer"  onclick="removeTask($task_id);"><i class="icon icon-cross3"></i></span>
        </div>
TASK;
} 

?>