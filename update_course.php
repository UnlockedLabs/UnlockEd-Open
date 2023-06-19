<?php

/**
 * Update Course
 *
 * Handle Updating Course
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

// instantiate database and course object
$database = new Database();
$db = $database->getConnection();

$course = new Course($db);
$users = new User($db);

$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: Category ID.');
$category_name = isset($_GET['category_name']) ? $_GET['category_name'] : die('ERROR: Category Name.');
$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : die('ERROR: Topic ID.');
$topic_name = isset($_GET['topic_name']) ? $_GET['topic_name'] : die('ERROR: missing Topic Name.');
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: Course ID.');
$course->id = $course_id;
        
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
    // $admin_id = $_POST['admin_id'];
    $old_course_img = $_POST['old_course_img'];
    
    if ((empty($course_name))) {
        echo "<div class='alert alert-danger'>Course Name cannot be empty.</div>";
    } elseif (empty($access_id)) {
        echo "<div class='alert alert-danger'>Access Id cannot be empty.</div>";
    // } elseif (empty($admin_id)) {
    //     echo "<div class='alert alert-danger'>Admin Id cannot be empty.</div>";
    } else {
        $course->course_name = $course_name;
        $course->course_desc = $course_desc;
        $course->topic_id = $topic_id;
        $course->course_img = $course_img;
        $course->course_img_url = $course_img_url;
        $course->iframe = $iframe;
        $course->access_id = $access_id;
        // $course->admin_id = $admin_id;
        $course->old_course_img = $old_course_img;

        if ($course->update()) {
            //create and write course image to directory
            if ($course_img_url) {
                /*
                course image is in ./media/images/course/N where N is the course id.
                This directory will already have been made at this point. However, we
                need to set $this->new_id to the current course id. $this->new_id  is set
                when a new course is created. Since the course is already in existence
                we can just set $this->new_id to $course_id.
                */

                $course->new_id = $course_id;

                if (!$course->writeCourseImage()) {
                    //course updated, but course imgage did not
                    echo "<script>courseUpdated('$category_id', '$topic_id', '$course->course_name', '$category_name', 'fail')</script>";
                    die();
                }
            }

            //the update was successful
            echo "<script>courseUpdated('$category_id', '$topic_id', '$course->course_name', '$category_name', '')</script>";
            die(); //no need to render beyond this point
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
                echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                echo "Unable to create course.";
            echo "</div>";
        }
    }
}



//GET
// assign values to object properties
$course->readOne();
$course_name = $course->course_name;
$course_id = $course->id;
$course_desc = $course->course_desc;
$topic_id = $course->topic_id;
$course_img = $course->course_img;
$iframe = $course->iframe;
$access_id = $course->access_id;
// $admin_id = $course->admin_id;

$form_action = 'update_course.php';
$form_title = 'Edit Course';

//TEAM: in time we can merge update_course.php with create_course.php and refactor.
//caution update_course.php and create_course.php share this file.
require 'course_html.php';
