<?php

/**
 * Course Tag
 *
 * Detailed Description
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

if ($course_img) {
    //$course_img = 'images/UELogo_8BC34A_cir.png';
    $course_img = "<img src='media/images/courses/$id/$course_img' alt='' width='96'>";
} else {
    //$course_img = '<i class="icon-book icon-2x text-success-400 border-success-400 border-3 rounded-round p-3 mb-3 mt-1"></i>';
    $course_img = "<img src='images/UELogo_8BC34A_cir.png' alt='' width='96'>";
    //$course_img = "<img src='images/UELogo_128.png' alt='' width='96'>";
}

?>
<!-- List -->
<div class="card card-body">
    <div class="media align-items-center align-items-lg-start text-center text-lg-left flex-column flex-lg-row">
        <div class="mr-lg-3 mb-3 mb-lg-0">
            <?php echo $course_img; ?>
        </div>

        <div class="media-body">
            <h3 class="media-title font-weight-semibold"><?php echo $course_name; ?></a></h3>

            <ul class="list-inline list-inline-dotted mb-3 mb-lg-2">
                <li class="list-inline-item text-muted"><?php echo $category_name; ?></li>
                <li class="list-inline-item text-muted"><?php echo $topic->topic_name; ?></li>
                <li class="list-inline-item text-muted"><?php echo $course_name; ?></li>
            </ul>

            <p class="mb-3">
                <?php echo $course_desc; ?>
            </p>

        </div>

        <div class="mt-3 mt-lg-0 ml-lg-3 text-center">

            <div class="text-muted"><?php echo $lesson_count; ?> lessons</div>

            

<!--             <div>
                <i class="icon-star-full2 font-size-base text-warning-300"></i>
                <i class="icon-star-full2 font-size-base text-warning-300"></i>
                <i class="icon-star-full2 font-size-base text-warning-300"></i>
                <i class="icon-star-full2 font-size-base text-warning-300"></i>
                <i class="icon-star-full2 font-size-base text-warning-300"></i>
            </div> -->

            <div class="progress rounded-round">
                <div class="progress-bar bg-warning" style="width: 100%">
                    <span><?php echo $course_progress; ?>% Complete</span>
                </div>
            </div>
            <?php
                if (($_SESSION['admin_num'] == 5)                                                  // Site Admin
                || ($_SESSION['admin_num'] == 4 && in_array($cat_id, $_SESSION['admin']['cat']))   // Category Admin and category admin of category to which course belongs
                || ($_SESSION['admin_num'] == 3 && in_array($id, $_SESSION['admin']['course']))) { // Course Admin and course admin of THIS course
                    echo "<a href='lesson.php?category_id={$courses->categoryId}&courseId={$id}' class='btn bg-teal-400 mt-3 lc-course-lesson' data-cat-name='{$category_name}' data-cat-id='{$courses->categoryId}' data-topic-name='{$topic->topic_name}' data-topic-id='{$topic->id}' data-course-id='{$id}' data-course-name='{$course_name}' data-admin='true'>View Course</a>";
                } elseif ($_SESSION['admin_num'] >= 2 && array_intersect($_SESSION['admin']['facilitator'], $course_cohort_array)) { // Facilitator of cohort in THIS course
                    echo "<a href='lesson.php?category_id={$courses->categoryId}&courseId={$id}' class='btn bg-teal-400 mt-3 lc-course-lesson' data-cat-name='{$category_name}' data-cat-id='{$courses->categoryId}' data-topic-name='{$topic->topic_name}' data-topic-id='{$topic->id}' data-course-id='{$id}' data-course-name='{$course_name}' data-facilitator='true'>View Course</a>";
                } elseif ($array = array_intersect($_SESSION['enrolled']['cohort'], $course_cohort_array)) { // Student enrolled in cohort(s) in THIS course
                    $stu_cohorts = implode(",", $array);
                    echo "<a href='lesson.php?category_id={$courses->categoryId}&courseId={$id}' class='btn bg-teal-400 mt-3 lc-course-lesson' data-cat-name='{$category_name}' data-cat-id='{$courses->categoryId}' data-topic-name='{$topic->topic_name}' data-topic-id='{$topic->id}' data-course-id='{$id}' data-course-name='{$course_name}' data-enrolled='true' data-cohorts='{$stu_cohorts}'>View Course</a>";
                } else {
                    echo "<a href='lesson.php?category_id={$courses->categoryId}&courseId={$id}' class='btn bg-teal-400 mt-3 lc-course-lesson' data-cat-name='{$category_name}' data-cat-id='{$courses->categoryId}' data-topic-name='{$topic->topic_name}' data-topic-id='{$topic->id}' data-course-id='{$id}' data-course-name='{$course_name}' data-admin='false'>View Course</a>";
                }
            ?>
        </div>
    </div>
</div>
<!-- /list -->


