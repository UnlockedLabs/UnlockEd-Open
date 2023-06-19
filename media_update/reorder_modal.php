<?php

/**
 * Reorder Modal
 *
 * Create modal for rename or reordering media files
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

echo "<button type='button' class='btn btn-primary mb-2' data-lesson-id='$lesson_id' data-course-id='$course_id' data-toggle='modal' data-target='#modal_full_$lesson_id'>Reorder/Rename Media Files <i class='icon-pencil ml-2'></i></button>";
?>

<div id="modal_full_<?php echo $lesson_id ?>" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rename or Reorder Media Files</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <div class="modal-body" id="reorder_div_<?php echo $lesson_id ?>"></div>
            <div class="modal-footer">
                <button type="button" class="btn bg-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn bg-primary" data-dismiss="modal" onclick="$('#finish_form_<?php echo $lesson_id ?>').submit();">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
//load media_update/upload_sort when modal is called
$("#modal_full_<?php echo $lesson_id ?>").on('show.bs.modal', function(){
    $("#reorder_div_<?php echo $lesson_id ?>").load('media_update/upload_sort.php?course_id=<?php echo $course_id ?>&lesson_id=<?php echo $lesson_id ?>');
});
</script>