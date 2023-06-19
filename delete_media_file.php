<?php

/**
 * Delete Media File
 *
 * Handle deletion of media items
 * Don't allow unless user is at least admin level 2.
 * Use/get media id to obtain other media information.
 * Delete media file from file system.
 * Delete media record from table.
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
require_once dirname(__FILE__).'/objects/media.php';
require_once dirname(__FILE__).'/objects/media_progress.php';

// instantiate database and course object
$database = new Database();
$db = $database->getConnection();

$media = new Media($db);
$media_progress = new MediaProgress($db);

// get media ID
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing Media ID.');
$media->id = $id;
$media_progress->media_id = $id;
$media_progress->deleted = 1;

//this sets certain media properties
$media->readOne();

//delete media file by src_path
if ($media->deleteMediaFileBySrcPath()) {
    echo "Media deleted.<br />";
} else {
    //echo "Unable to delete media file.<br />";
}

//delete db row entry
if ($media->delete()) {
    //echo "Media row deleted.<br />";
} else {
    //echo "Unable to delete media row file.<br />";
}

/*
 @todo
 delete_media_file.php
 set media_progress.delete to 1 (progress stats will mess up if we do not change this to 1)
*/


if ($media_progress->updateDeletedColumn()) {
    //echo "TODO ux improvement here media_progress.delete set to 1.<br />";
} else {
    //echo "ERROR media_progress.delete was not set to 1.<br />";
}
