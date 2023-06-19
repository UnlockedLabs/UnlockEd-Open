<?php

/**
 * Upload Sort
 *
 * Provide functionality for sorting/re-ordering media
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

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/media.php';

//get course and lesson id
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing course id.');
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: missing lesson id.');

// instantiate database and lesson object
$database = new Database();
$db = $database->getConnection();

$media = new Media($db);
$media->course_id = $course_id;
$media->lesson_id = $lesson_id;
$stmt = $media->readAllByCourseAndLessonId();

if (!$stmt->rowCount()) {
    die('<p>There are no media entries to sort.</p>');
}
?>
<div class="card">
    <div class="">
        <form action="./media_update/finish.php" method="post" id="finish_form_<?php echo $lesson_id; ?>">
        <div class="d-flex flex-column animated fadeIn">
            <div class="text-center">
            <ul class="ui-sortable" id="sortable-list-placeholder">
<?php

while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
    extract($row);

    $fileType = strtolower(pathinfo($src_path, PATHINFO_EXTENSION));

    /*
    A section of code similar to the following is in the media object.
    If updates are made to that section, be sure to update this section.
    */

    //set icon type
    if (in_array(strtolower($fileType), array("jpg","png","gif"))) {
        $icon = "icon-image2";
        $file_color = "teal-600";
    } elseif (in_array(strtolower($fileType), array("mp3","wav"))) {
        $icon = "icon-headphones";
        $file_color = "slate-600";
    } elseif (in_array(strtolower($fileType), array("mp4","ogg","mpeg","wmv","mpeg"))) {
        $icon = "icon-play";
        $file_color = "warning-300";
    } elseif (in_array(strtolower($fileType), array("pdf"))) {
        $icon = "icon-file-pdf";
        $file_color = "danger-600";
    } elseif (in_array(strtolower($fileType), array("html5"))) {
        $icon = "icon-html5";
        $file_color = "danger-600";
    } else {
        //this is bad if you make it to here
        $icon = 'icon-question3';
        $file_color = "warning-600";
    }

    //check if actual media files exists
    if (is_file('../' . $src_path)) {
        $is_file_check = '<i class="icon-checkmark4 text-success"></i>';
    } else {
        $is_file_check = '<i class="icon-cross2 text-danger"></i>';
    }

    echo <<<_FILE
        <li class="p-1 bg-light border rounded cursor-move mt-2 ui-sortable-handle d-flex flex-row justify-content-left">
            <i class="$icon text-$file_color icon-2x"></i>
            <p class="col-2">Display Name:</p>
            <input class="form-control form-control-sm" type="text" name="disp_name[]" value="$display_name">
            <input type="hidden" name="media_ids[]" value="$id">
            <input type="hidden" name="icon[]" value="$icon">
            <input type="hidden" name="src_path[]" value="$src_path">
            <input type="hidden" name="course_id" value="$course_id">
            <input type="hidden" name="lesson_id" value="$lesson_id">
            <span class="badge badge-light badge-striped badge-striped-left border-left-$file_color">$fileType</span>
            <span class="badge badge-light badge-striped badge-striped-left">File exists? $is_file_check</span>
        </li>

_FILE;
} //end while

echo <<<_SORT_CLOSE
            </ul>
            </div>
            <input class="form-control" name='filepath' value='$parent_dir' type="hidden">
        </div>
        </form>
    </div>
</div>
_SORT_CLOSE;
?>

<script>

/* ------------------------------------------------------------------------------
 *
 *  # jQuery UI interactions
 *
 *  Demo JS code for jqueryui_interactions.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var JqueryUiInteractions = function() {

    //
    // Setup module components
    //

    // Sortable
    var _componentUiSortable = function() {
        if (!$().sortable) {
            console.warn('Warning - jQuery UI components are not loaded.');
            return;
        }

        // Basic functionality
        $('#sortable-list-basic').sortable();
        $('#sortable-list-basic').disableSelection();


        // Placeholder
        $('#sortable-list-placeholder').sortable({
            placeholder: 'sortable-placeholder',
            start: function(e, ui){
                ui.placeholder.height(ui.item.outerHeight());
            }
        });
        $('#sortable-list-placeholder').disableSelection();


        // Connected lists
        $('#sortable-list-first, #sortable-list-second').sortable({
            connectWith: '.selectable-demo-connected'
        }).disableSelection();


        //
        // Include/exclude items
        //

        // Specify sort items
        $('#sortable-list-specify').sortable({
            items: 'li:not(.ui-handle-excluded)'
        });

        // Exclude items
        $('#sortable-list-cancel').sortable({
            cancel: '.ui-handle-excluded'
        });

        // Disable selections
        $('#sortable-list-specify li, #sortable-list-cancel li').disableSelection();
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentUiSortable();
        }
    }
}();


// Initialize module
// ------------------------------

//document.addEventListener('DOMContentLoaded', function() {
    //JqueryUiInteractions.init();
//});
//I altered this for the ajax
JqueryUiInteractions.init();
// --------------------------------------------------------------------------------------------------
</script>

<script>
$('#finish_form_<?php echo $lesson_id ?>').on('submit', function(e){

    $("#modal_full_<?php echo $lesson_id ?>").modal('hide');

    //prevent form submission
    e.preventDefault();

    var fromData = $(this).serialize();
    var $content = $("#lesson-media-<?php echo $lesson_id; ?>");

    $.ajax({
        type: 'POST',
        url: './media_update/finish.php',
        data: fromData, 
        timeout: 30000,
        success: function(data) {
            swal({
                title: 'Success!',
                text: "Your files have been updated.",
                type: 'success',
                backdrop: false,
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                onClose: function() {

                    //redisplay the renamed/resorted media files
                    var $content = $("#lesson-media-<?php echo $lesson_id; ?>");

                    $.ajax({
                        type: 'GET',
                        url: 'lesson_media.php?course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson_id; ?>',
                        timeout: 30000,
                        success: function(data) {
                            $content.html(data);
                        },
                        error: function(data) {
                            $content.html(data.responseText);
                        },
                        fail : function() {
                            $content.html('<div id="load">Please try again soon.</div>');
                        }
                    });
                },
            });
        },
        error: function(data) {
            swal({
                title: "Error",
                html: data.statusText,
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: true,
                confirmButtonText: 'OK',
                type: "error"
            });
        },
        fail : function(data) {
            swal({
                title: "Error",
                html: data.statusText,
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                type: "error"
            });
        }
    });

});
</script>