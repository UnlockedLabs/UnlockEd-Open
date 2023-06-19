<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/course_administrators.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$course_admin = new CourseAdministrator($db);

if ($_POST) {
    $course_admin->course_id = isset($_POST['course_id']) ? $_POST['course_id'] : die('ERROR: missing COURSE ID.');
    $course_admin->administrator_id = isset($_POST['course_admin_id']) ? $_POST['course_admin_id'] : die('ERROR: missing ADMINISTRATOR ID.');

    // delete Course Admin
    if ($course_admin->delete()) {
        // tell the user Course Admin was deleted
        echo <<<_ALERT
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Instructor deleted.
            </div>
_ALERT;
    }

}
