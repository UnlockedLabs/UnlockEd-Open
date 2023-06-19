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

//ensure admin user (admin is 2 and above)
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

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
    // read all cohorts for particular course id
    $stmt = $cohort->readAllByCourse();

    $user_id = $_SESSION['user_id'];
    $cohort_count = 0;

    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        extract($row); // id, cohort_name, facilitator_id, facilitator_name, course_id, created
        $facilitator_name = ucfirst($facilitator_name);
        // if user is Facilitator, do not show other Facilitator's cohorts
        if ((($_SESSION['admin_num'] == 2) && ($user_id != $facilitator_id))
        || (($_SESSION['admin_num'] == 3) && ($user_id != $facilitator_id) && (!in_array($user_id, $course_admin_array)))
        || (($_SESSION['admin_num'] == 4) && ($user_id != $facilitator_id) && (!in_array($user_id, $cat_admin_array)))) {
            continue;
        }
        $cohort_count++;
    }    

    // get number of cohorts in course
    echo $cohort_count; //$cohort->countCohortsByCourse();
}
