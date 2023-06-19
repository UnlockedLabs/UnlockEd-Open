<?php

/**
 * Course Preview
 *
 * Course Preview piece
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

$prev_course_img = !empty($course_img) ? "media/images/courses/$course_id/$course_img" : 'images/UELogo_8BC34A_cir.png';
$prev_course_name = !empty($course_name) ? $course_name : 'Course Name';
$prev_course_desc = !empty($course_desc) ? $course_desc : 'If you provide a course description it will appear here. Course descriptions are optional.';
?>
<label>Course Preview</label>
<div class="card card-body">
    <div class="media align-items-center align-items-lg-start text-center text-lg-left flex-column flex-lg-row">
        <div class="mr-lg-3 mb-3 mb-lg-0">
            <div class="d-md-inline-block mt-3 mx-auto mr-md-0 mt-md-0 ml-md-3 overflow-hidden rounded" id="img_preview" style="width: 96px; height: 96px;">
                <img src="<?php echo $prev_course_img; ?>" id="course-image-preview" alt="" style="width: 96px; height: 96px;">
            </div>
        </div>

        <div class="media-body">
            <h3 class="media-title font-weight-semibold" id="prev_course_name"><?php echo $prev_course_name; ?></h3>

            <ul class="list-inline list-inline-dotted mb-3 mb-lg-2">
                <li class="list-inline-item"><a href="#" class="text-muted"><?php echo $category_name; ?></a></li>
                <li class="list-inline-item"><a href="#" class="text-muted"><?php echo $topic_name; ?></a></li>
                <li class="list-inline-item"><a href="#" class="text-muted" id="prev_course_name2"><?php echo $prev_course_name; ?></a></li>
            </ul>

            <p class="mb-3" id="prev_course_desc"><?php echo $prev_course_desc; ?></p>


        </div>

        <div class="mt-3 mt-lg-0 ml-lg-3 text-center">
            <div class="text-muted">00 lessons</div>
            <div class="progress rounded-round">
            <div class="progress-bar bg-warning" style="width: 100%">
                <span>00% Complete</span>
            </div>
            </div>
            <a href="#" class="btn bg-teal-400 mt-3 lc-course-lesson disabled"><i class="icon-brain mr-2"></i> View Course</a>
        </div>
    </div>
</div>
<!-- /course preview -->