<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort_enrollments.php';
require_once dirname(__FILE__).'/../objects/cohort.php';
require_once dirname(__FILE__).'/../objects/category_enrollments.php';
require_once dirname(__FILE__).'/../objects/users.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cohort = new Cohort($db);
$cohort_enrollments = new CohortEnrollment($db);
$category_enrollments = new CategoryEnrollment($db);
$users = new User($db);

if ($_POST) {
    $enrollees = isset($_POST['enrolledIds']) ? $_POST['enrolledIds'] : [];
    $unenrollees = isset($_POST['unenrolledIds']) ? $_POST['unenrolledIds'] : [];
    $cohort_id = isset($_POST['cohortId']) ? $_POST['cohortId'] : die('ERROR: missing COHORT ID.');
    $cohort_name = isset($_POST['cohortName']) ? $_POST['cohortName'] : '';
    $cohort->id = $cohort_id;
    $category_id = $cohort->readCohortCategoryId();
    // delete record(s) of unenrolled student(s)
    foreach ($unenrollees as $unenrollId) {
        $cohort_enrollments->cohort_id = $cohort_id;
        $cohort_enrollments->student_id = $unenrollId;
        $cohort_enrollments->delete();
    }

    // add record(s) of enrolled student(s)
    foreach ($enrollees as $enrollId) {
        $cohort_enrollments->cohort_id = $cohort_id;
        $cohort_enrollments->student_id = $enrollId;
        if ($cohort_enrollments->rowExists()) {
            continue;
        }
        if ($cohort_enrollments->create()) {
            // if cohort_enrollment created, enroll student in
            // corresponding category also
            $category_enrollments->category_id = $category_id;
            $category_enrollments->student_id = $enrollId;
            if ($category_enrollments->rowExists()) {
                continue;
            }
            $category_enrollments->create();
        }
        
    }

}
