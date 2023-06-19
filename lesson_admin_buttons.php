<?php

/**
 * Lesson Admin Buttons
 *
 * Handle Add, Edit, Delete Admin buttons for Lessons
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

//echo '<div class="text-center font-size-xs mt-3">';
    //echo '<a href="create_lesson.php?category_id='.$category_id.'&lesson_id='.$id.'&course_id='.$course_id.'&topic_id='.$topic_id.'" title="Add A Lesson" class="create-lesson-href text-primary-400 font-size-xs">Add A Lesson</a>';
    echo '<a href="update_lesson_name.php?category_id='.$category_id.'&lesson_id='.$id.'&course_id='.$course_id.'&topic_id='.$topic_id.'" title="Edit This Lesson" class="update-lesson-href text-primary-400 font-size-xs"> Edit '.$lesson_name.'\'s title</a>';
    echo '<a href="delete_lesson.php?category_id='.$category_id.'&lesson_id='.$id.'&course_id='.$course_id.'&topic_id='.$topic_id.'" title="Delete This Lesson" class="delete-lesson-href text-primary-400 font-size-xs"> | Delete '.$lesson_name.'</a>';
//echo '</div>';
?>