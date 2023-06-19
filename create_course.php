<?php

/**
 * Create Course
 *
 * Process create course form and insert course into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Create course image directory and save image.
 * Insert/create course.
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
require_once dirname(__FILE__).'/objects/course.php';
require_once dirname(__FILE__).'/objects/users.php';
require_once dirname(__FILE__).'/objects/category.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$course = new Course($db);
$users = new User($db);

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : die('ERROR: missing TOPIC ID.');
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: missing CATEGORY ID.');
$category_name = isset($_GET['category_name']) ? $_GET['category_name'] : die('ERROR: missing Category Name.');
$topic_name = isset($_GET['topic_name']) ? $_GET['topic_name'] : die('ERROR: missing Topic Name.');
$course->topic_id = $topic_id;

// if the form was submitted
if ($_POST) {
    // set course property values
    $course_name = $_POST['course_name'];
    $course_desc = $_POST['course_desc'];
    $topic_id = $_POST['topic_id'];
    $course_img = $_POST['course_img']; //really just the filename (pic.png)
    $course_img_url = $_POST['course_img_url']; //url data string representing the picture
    $iframe = $_POST['iframe'];
    $access_id = $_POST['access_id'];
    $old_course_img = $_POST['old_course_img'];
    
    if ((empty($course_name))) {
        echo "<div class='alert alert-danger'>Name cannot be empty.</div>";
    } elseif (empty($access_id)) {
        echo "<div class='alert alert-danger'>Access Id cannot be empty.</div>";
    } else {
        $course->course_name = $course_name;
        $course->course_desc = $course_desc;
        $course->topic_id = $topic_id;
        $course->course_img = $course_img;
        $course->course_img_url = $course_img_url;
        $course->old_course_img = $old_course_img;
        $course->iframe = $iframe;
        $course->access_id = $access_id;
    
        if ($course->create()) {
            //create and write course image to directory
            if ($course_img_url) {
                //create dir in ./media/images/course/N where N is the new course id
                if (!$course->createCourseImageDirectory() || !$course->writeCourseImage()) {
                    //course created, but course imgage did not
                    echo "<script>courseCreated('$category_id', '$topic_id', '$course->course_name', '$category_name', 'fail')</script>";
                    die();
                }
            }
            echo "<script>courseCreated('$category_id', '$topic_id', '$course->course_name', '$category_name', '')</script>";

            //no need to render beyond this point
            die();
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Unable to create course.";
            echo "</div>";
        }
    }
} //end of POST

//GET
$course_name = '';
$course_desc = '';
$course_id = '';
$topic_id = ''; //access_id corresponds to access_levels.id
$course_img = '';
$iframe = '';
$access_id = ''; //admin_id corresponds to admin_levels.id

$form_action = 'create_course.php';
$form_title = 'Create Course';

//TEAM: in time we can merge update_course.php with create_course.php and refactor.
//caution update_course.php and create_course.php share this file.
require 'course_html.php';
