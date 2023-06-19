<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/course_administrators.php';
require_once dirname(__FILE__).'/../objects/users.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$course_admin = new CourseAdministrator($db);
$users = new User($db);

if ($_POST) {
    $course_id = $_POST['courseId'];
    $course_name = $_POST['courseName'];
    $admin_array = isset($_POST['adminArray']) ? $_POST['adminArray'] : [];
    $admin_id_array = isset($_POST['adminIdArray']) ? $_POST['adminIdArray'] : [];
    $plural = (count($admin_array) > 1) ? 's' : '';

    // assign instructors
    for ($i = 0; $i < count($admin_array); $i++) {
        $course_admin->course_id = $course_id;
        $course_admin->administrator_id = $admin_id_array[$i];

        if ($course_admin->create()) {
            // set administrators admin_id to 3 (Instructor)
            $users->id = $admin_id_array[$i];
            $users->updateAdminId(3);
        }
    }

    // tell the instructor assignments were completed
    echo <<<_ALERT
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Instructor assignment{$plural} for {$course_name} completed.
        </div>
_ALERT;

}
