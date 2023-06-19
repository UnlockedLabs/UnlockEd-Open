<?php

/**
 * Require Media File
 *
 * Handles setting required for media.
 *
 * PHP version 7.2.5
 *
 * @category  Track_Progress
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once '../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/media.php';
require_once dirname(__FILE__).'/../objects/media_progress.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$media = new Media($db);
$media_progress = new MediaProgress($db);

if (isset($_GET)) {
    /*
    TEAM: required is not the best db column name for this functionality.
    no-seek / no-fast-forward would be better.
    As we progress we may have to indicate what files actually make up the
    course, like, what files are required and what files are not
    required. When we come to that bridge the current required column in
    media and media_progress will need to become no_seek, is_seek or something
    similar.
    */

    //get media id
    $media_id = isset($_GET['media_id']) ? $_GET['media_id'] : die('ERROR: missing Media Id.');
    $required = isset($_GET['required']) ? $_GET['required'] : die('ERROR: missing Required Value.');

    //set class objects
    $media->id = $media_id;
    $media->required = $required;

    $media_progress->media_id = $media_id;
    $media_progress->required = $required;
    
    //update db records
    $media->updateRequired();
    $media_progress->updateRequired();
}
