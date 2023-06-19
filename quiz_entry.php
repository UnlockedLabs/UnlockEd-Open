<?php

/**
 * Quiz Entry
 *
 * Handle Quiz Entry
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

//admin -- delete file
if ($_SESSION['admin_num'] > 1) {
    $dragula_handle = <<<_DRAG
            <div class="mr-3 mt-1">
                <i class="icon-dots dragula-handle"></i>
            </div>
_DRAG;

/**
 * @todo
 * quiz_entry.php 
 * The edit functionality needs to be wired correctly
 */
	$quiz_hamburger_menu = <<<_HAMBURGERMENU
			<div class="ml-3 align-self-center">
				<div class="dropdown mb-1">
					<a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-more2"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="#" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
						<a href="delete_quiz.php?quiz_id='{$id}'" class="dropdown-item quiz-delete-link" data-quiz_id="{$id}" data-name="{$quiz_name}"><i class="icon-trash"></i> Delete</a>
					</div>
				</div>
			</div>
_HAMBURGERMENU;
} else {
    $dragula_handle = '';
    $quiz_hamburger_menu = '';
}
//we are setting this because $media->checkQuizCompletion() needs it to calculate
$media->id = $id; // $media->id corresponds to quiz_id for quizzes

// lesson quiz
echo <<<QUIZ
        <li class="media" id="quiz-entry-{$id}">
            {$dragula_handle}
            <div class="mr-3">
                <a href="display_quiz.php?quiz_id='{$id}'" class="quiz-display-link" id="quiz-link-{$id}" data-quiz_id="{$id}" data-lesson_id="{$lesson_id}" data-quiz-name="{$quiz_name}">
                    <i class="icon-brain icon-2x"></i>
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-title">{$quiz_name}</h4>
            </div>
            <span id="quiz-completed-$id" class="mt-1">{$media->checkLessonCompletion()}</span>
            {$quiz_hamburger_menu}
        </li>
QUIZ;
