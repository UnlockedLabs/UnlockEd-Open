<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort.php';
require_once dirname(__FILE__).'/../objects/course.php';
require_once dirname(__FILE__).'/../objects/course_administrators.php';
require_once dirname(__FILE__).'/../objects/category_administrators.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cohort = new Cohort($db);
$course = new Course($db);
$course_admins = new CourseAdministrator($db);
$cat_admins = new CategoryAdministrator($db);

if ($_GET) {
    // get GET data
    $cohort->course_id = isset($_GET['courseId']) ? $_GET['courseId'] : die('ERROR: missing COURSE ID.');
    $course_id = $cohort->course_id;
    // get category for course
    $cat_id = $course->readCatIdByCourseId($course_id);
    // get array of admins for category
    $cat_admin_array = [];
    $cat_admins->category_id = $cat_id;
    $stmt1 = $cat_admins->readAllAdministrators();
    while ($row = $stmt1->fetch(\PDO::FETCH_ASSOC)) {
        extract($row);
        $cat_admin_array[] = $cat_admin_id;
    }
    // get array of admins for course
    $course_admin_array = [];
    $course_admins->course_id = $course_id;
    $stmt2 = $course_admins->readAllAdministrators();
    while ($row = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
        extract($row);
        $course_admin_array[] = $course_admin_id;
    }
    // get list of enrolled students in course
    $stmt3 = $cohort->readAllStudentsInCourse();

    if (!$stmt3->rowCount()) {
        echo "<li class='nav-item text-center text-muted usertag'>No students enrolled</li>";
    }

    $name = '';
    $user_id = $_SESSION['user_id'];

    while ($row = $stmt3->fetch(\PDO::FETCH_ASSOC)) {
        extract($row); // cohort_id, cohort_name, course_id, facilitator_id, course_name, student_id, username

        // if user is Facilitator, do not show other Facilitator's cohort students
        if ((($_SESSION['admin_num'] == 2) && ($user_id != $facilitator_id))
        || (($_SESSION['admin_num'] == 3) && ($user_id != $facilitator_id) && (!in_array($user_id, $course_admin_array)))
        || (($_SESSION['admin_num'] == 4) && ($user_id != $facilitator_id) && (!in_array($user_id, $cat_admin_array)))) {
            continue;
        }

        if ($name != $cohort_name) {
            if ($name == '') {
                echo "<li class='nav-item text-center text-muted pt-1'>{$cohort_name}</li>";
            } else {
                echo "<li class='nav-item text-center text-muted'>{$cohort_name}</li>";
            }
        }
        $name = $cohort_name;
        $username = ucfirst($username);
        $email_link = "";
        if($_SESSION['current_site_settings']['email_enabled'] == 'true'){
            $email_link = "<a href='./lc_email/lc_compose.php?recipient_id={$student_id}' class='dropdown-item'><i class='icon-mail5'></i> Send message</a>";
        }
        echo <<<_STUDENT
            <li class="media usertag">
                <a href="#" class="mr-3 position-relative">
                    <img src="libs/limitless/global_assets/images/placeholders/person.jpg" width="24" height="24" class="rounded-circle" alt="">
                    <span class="badge badge-info badge-pill badge-float"></span>
                </a>
                <div class="media-body align-self-center">
                    {$username}
                </div>
                <div class="ml-3 align-self-center">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle caret-0 stdnt-ham" data-toggle="dropdown"><i class="icon-more2"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" data-student_id="{$student_id}" data-admin_id="{$admin_id}">
                            {$email_link}
                        </div>
                    </div>
                </div>
            </li>
_STUDENT;
    }
}
