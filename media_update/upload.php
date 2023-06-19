<?php

/**
 * Upload
 *
 * Provide media upload to file server functionality
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

/*
This file is posted to multiple times by
$(".file-uploader").pluploadQueue(...) in media_update/add_files.php.

Our current server upload sizes are below:

/etc/php7/apache2/php.ini
post_max_size = 256M
upload_max_filesize = 256M
max_file_uploads = 200
default_socket_timeout = 600

See the media object properties for where we are setting
the max upload size as a file handling check for media.moveUploadedFile()
*/

require_once dirname(__FILE__).'/../session-validation.php';

//ensure admin user (admin is 2 and above)
if (($_SESSION['admin_num'] < 2)) {
    die('<h1>Restricted Action!</h1>');
}

// get and ensure course and lesson id
$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : die('ERROR: missing Course ID.');
$lesson_id = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : die('ERROR: missing Lesson ID.');

// include database and object files
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/media.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$media = new Media($db);
//set object properties
$media->course_id = $course_id;
$media->lesson_id = $lesson_id;

//move the uploaded file to its lesson directory and insert file data into db
$media->moveUploadedFile();
