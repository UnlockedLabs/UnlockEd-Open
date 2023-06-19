<?php

/**
 * Add Files Modal
 *
 * Create the modal for adding files (upload)
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

echo "<button type='button' class='btn btn-primary mb-2' data-lesson-id='$lesson_id' data-course-id='$course_id' data-toggle='modal' data-target='#add_files_modal_$lesson_id'>Add Files <i class='icon-plus3 ml-2'></i></button>";
?>

<div id="add_files_modal_<?php echo $lesson_id ?>" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-body" id="add_files_div_<?php echo $lesson_id ?>"></div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger" onclick="cancelFileUpload();">Cancel Upload</button>
                <button type="button" class="btn bg-primary" data-dismiss="modal" id="close_file_upload_btn_<?php echo $lesson_id ?>">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
//load media_update/upload_sort when modal is called
$("#add_files_modal_<?php echo $lesson_id ?>").on('show.bs.modal', function(){
    $("#add_files_div_<?php echo $lesson_id ?>").load('media_update/add_files.php?course_id=<?php echo $course_id ?>&lesson_id=<?php echo $lesson_id ?>');
});
</script>