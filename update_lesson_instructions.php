<?php

/**
 * Update Lesson Instructions
 *
 * Handle Updating Lesson Instructions
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
require_once dirname(__FILE__).'/objects/lesson.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$lesson = new Lesson($db);

$id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: Lesson ID.');

$editor_html = isset($_POST['editor_html']) ? $_POST['editor_html'] : die('ERROR: Editor Html.');

//set object properties
$lesson->id = $id;
$lesson->editor_html = $editor_html;

//update html
if ($lesson->updateEditorHtml()) {
    echo <<<_ALERT
    <script>
    swal({
        title: '<h4>Instructions Updated!</h4>',
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            //pass
        },
    });
    </script>
_ALERT;
} else {
    echo <<<_ALERT
    <script>
    swal({
        title: '<h4>Instructions Could Not Be Updated!</h4>',
        type: 'error',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            //pass
        },
    });
    </script>
_ALERT;
}

/*
* This may be an unnecessary query as $editor_html holds the post html.
* Will the post and db strings ever differ?
* If not, omit this query.
*/
$lesson->readOne();

//return new html
echo $lesson->editor_html;
