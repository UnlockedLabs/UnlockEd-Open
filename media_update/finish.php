<?php

/**
 * Finish
 *
 * This file is posted to from upload_sort.php and add_files.php.
 * When it comes in from upload_sort.php it will have media_ids that are
 * being used by our course tracking system. We must update in this context.
 *
 * When the post comes in from add_files.php we have to make new records.
 * These two scnarios are reflected in the if statement below.
 *
 * PHP version 7.2.5
 *
 * @category  Media_Update
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__).'/../session-validation.php';
//ensure admin user (admin is 2 and above)
if (($_SESSION['admin_num'] < 2)) {
    die('<h1>Restricted Action!</h1>');
}

$disp_name = isset($_POST['disp_name']) ? $_POST['disp_name'] : die('ERROR: missing Display Name.');
$icon = isset($_POST['icon']) ? $_POST['icon'] : die('ERROR: missing Icon.');
$src_path = isset($_POST['src_path']) ? $_POST['src_path'] : die('ERROR: missing Source Path.');
$filepath = isset($_POST['filepath']) ? $_POST['filepath'] : die('ERROR: missing File Path.');
$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : die('ERROR: missing Course ID.');
$lesson_id = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : die('ERROR: missing Lesson ID.');
$media_ids = isset($_POST['media_ids']) ? $_POST['media_ids'] : die('ERROR: missing Media Ids.');

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/media.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$media = new Media($db);

$media->course_id = $course_id;
$media->lesson_id = $lesson_id;

//set object properties
$media->parent_dir = str_replace('../', '', $filepath);
$media->display_name = $disp_name;

/*
TEAM: are we going to dynamically set access and admin nums on these
or default to 1. How we display categories, topics, courses, lessons etc.
is a topic we have not fully fledged out.
*/

$media->access_id = 1;
$media->admin_id = 1;

//create new media entries
$num = count($disp_name) - 1;
for ($x = 0; $x <= $num; $x++) {
    $media->display_name = $disp_name[$x];
    $media->src_path = str_replace('../', '', $src_path[$x]);
    $media->icon = $icon[$x];
    $media->order_pos = $x;

    if ($media_ids == 'create-new') {
        //create new records
        $media->create();
    } else {
        //update existing records
        $media->id = $media_ids[$x];
        $media->update();
    }
}
