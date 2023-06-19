<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category.php';
require_once dirname(__FILE__).'/../objects/course_administrators.php';

//ensure admin user (admin is 2 and above)
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$course_admins = new CourseAdministrator($db);
$category = new Category($db);

if ($_GET) {
    // get GET data
    $cat_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
    if (isset($_GET['courseId'])) {
        $course_admins->course_id = $_GET['courseId'];
        // get number of instructors for course
        echo $course_admins->countByCourseId();
    } else {
        $category->id = $cat_id;
        // get number of instructors for category
        echo $category->countInstructorsByCatId();
    }

}
