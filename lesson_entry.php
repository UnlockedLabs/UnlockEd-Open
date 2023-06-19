<?php

/**
 * Lesson Entry
 *
 * Handle Entering Lesson
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

//echo $icon;
//WARNING -- if the default icon gets changed then we will need to update the $icon=='' check
//icon-headphones
//icon-play
//icon-file-pdf

//admin -- delete fil
if ($_SESSION['admin_num'] > 1) {
    $dragula_handle = <<<_DRAG
            <div class="mr-3 mt-1">
                <i class="icon-dots dragula-handle"></i>
            </div>
_DRAG;

    //allow or disallow fast forwarding if audio or video
    // $toggle_required = '';

    if ($icon == 'icon-play' || $icon == 'icon-headphones') {
        $display_name_encoded = urlencode($display_name);
        $fast_forward = <<<_FFHEAD
            <div class="dropdown-item">
                <span class="d-flex justify-content-center" onclick="require_media_file('{$display_name_encoded}', '{$id}');">
_FFHEAD;
            
        if ($required) {
            $fast_forward .= "<input class='form-check-input-styled mr-1' id='require-media-{$id}' type='checkbox' checked>";
        } else {
            $fast_forward .= "<input class='form-check-input-styled mr-1' id='require-media-{$id}' type='checkbox'>";
        }

        $fast_forward .= <<<_FFTAIL
                </span>
                <span class="ml-2">Disable Fast Forwarding</span>
            </div>
_FFTAIL;
    } else {
        $fast_forward = '';
    }

    // CHRISNOTE: This needs to be wired correctly
    $media_hamburger_menu = <<<_MEDIAHAMBURGERMENU
            <div class="ml-3 align-self-center">
                <div class="dropdown mb-1">
                    <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-more2"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        {$fast_forward}
                        <a href="delete_media_file.php?id={$id}" data-media-id="{$id}" data-display-name="{$display_name}" class="dropdown-item delete-media-file-href"><i class="icon-trash"></i> Delete</a>
                    </div>
                </div>
            </div>
_MEDIAHAMBURGERMENU;

    // $delete_file = '<a href="delete_media_file.php?id='.$id.'" data-media-id="'.$id.'" data-display-name="'.$display_name.'" class="text-danger ml-1 delete-media-file-href" style="float:right;"> <i class="icon-trash"></i> Delete File</a>';
} else {
    $dragula_handle = '';
    $media_hamburger_menu = '';
    // $toggle_required = '';
}

//course videos
if (isset($icon) && $icon == 'icon-play') {
    //encode on server and decode in tracking_functions.js (single quotes were causing us problems in the js)
    $src_path_encoded = urlencode($src_path);
    $display_name_encoded = urlencode($display_name);
    $user_id_encoded = urlencode($_SESSION['user_id']);

    //we are setting this because $media->checkLessonCompletion() needs it to calculate
    $media->id = $id;
    
    //$id is media_id at this point
    echo <<<VIDEO
        <li class="media" id="media-entry-{$id}">
            {$dragula_handle}
            <div class="mr-3">
                <a href="#videoPlayer-{$lesson_id}" onclick="playVideoWithTracking('{$course_id}', '{$lesson_id}', '{$id}', '{$src_path_encoded}', '{$user_id_encoded}', '{$display_name_encoded}', '{$required}');">
                    <i class="{$icon} icon-2x"></i>
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-title">{$display_name}</h4>
            </div>
            <span class="mt-1" id="video-completed-$id">{$media->checkLessonCompletion()}</span>
            {$media_hamburger_menu}
        </li>
VIDEO;
}

//course audio
if (isset($icon) && $icon == 'icon-headphones') {
    //encode on server and decode in tracking_functions.js (single quotes were causing us problems in the js)
    $src_path_encoded = urlencode($src_path);
    $display_name_encoded = urlencode($display_name);
    $user_id_encoded = urlencode($_SESSION['user_id']);

    //we are setting this because $media->checkLessonCompletion() needs it to calculate
    $media->id = $id;

    echo <<<AUDIO
        <li class="media" id="media-entry-{$id}">
            {$dragula_handle}
            <div class="mr-3">
                <a href="#audioPlayer-{$lesson_id}" onclick="playAudioWithTracking('{$course_id}', '{$lesson_id}', '{$id}', '{$src_path_encoded}', '{$user_id_encoded}', '{$display_name_encoded}', '{$required}');">
                    <i class="icon-headphones icon-2x"></i>
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-title">{$display_name}</h4>
            </div>
            <span class="mt-1" id="audio-completed-$id">{$media->checkLessonCompletion()}</span>
            {$media_hamburger_menu}
        </li>
AUDIO;
}

//course images
if (isset($icon) && $icon == 'icon-image2') {
    echo <<<TEST
        <li class="media" id="media-entry-{$id}">
            {$dragula_handle}
            <div class="mr-3">
                <a href="{$src_path}" target="_blank" class=''>
                    <i class="icon-image2 icon-2x"></i>
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-title">{$display_name}</h4>
            </div>
            <span class="mt-1"></span>
            {$media_hamburger_menu}
        </li>
TEST;
}

//course readables
if (isset($icon) && $icon == 'icon-file-pdf') {
    echo <<<TEST
        <li class="media" id="media-entry-{$id}">
            {$dragula_handle}
            <div class="mr-3">
                <a href="{$src_path}" target="_blank" class=''>
                    <i class="icon-file-pdf icon-2x"></i>
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-title">{$display_name}</h4>
            </div>
            <span class="mt-1"></span>
            {$media_hamburger_menu}
        </li>
TEST;
}

//html5
if (isset($icon) && $icon == 'icon-html5') {
    echo <<<TEST
        <li class="media" id="media-entry-{$id}">
            {$dragula_handle}
            <div class="mr-3">
                <a href="{$src_path}" target="_blank" class=''>
                    <i class="icon-html5 icon-2x"></i>
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-title">{$display_name}</h4>
            </div>
            <span class="mt-1"></span>
            {$media_hamburger_menu}
        </li>
TEST;
}

//course unknown
if (isset($icon) && $icon == 'icon-question3') {
    echo <<<TEST
        <li class="media" id="media-entry-{$id}">
            {$dragula_handle}
            <div class="mr-3">
                <a href="{$src_path}" class=''>
                    <i class="icon-question3 icon-2x"></i>
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-title">{$display_name}</h4>
            </div>
            <span class="mt-1"></span>
            {$media_hamburger_menu}
        </li>
TEST;
}
