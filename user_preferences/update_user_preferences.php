<?php

/**
 * Update Preferences
 *
 * Handle updating user's preferences.
 *
 * PHP version 7.2.5
 *
 * @category  Preferences
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

 
namespace unlockedlabs\unlocked;

require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/user_preferences.php';

// instantiate database
$database = new Database();
$db = $database->getConnection();

// instantiate object
$userPreference = new userPreference($db);

// set user_id
$user_id = $_SESSION['user_id'];


if (isset($_POST['bannerNum']))
{
    $bannerNum = $_POST['bannerNum'];

    if (empty($bannerNum)) {
        echo "<div class='alert alert-danger'>Banner Number cannot be empty.</div>";
    } else {
        $userPreference->id = $user_id;
        $userPreference->bannerNum = $bannerNum;

        if ($userPreference->updateUserBanner()) {
            echo 'Banner changed.';
        } else {
            echo 'Banner not changed.';
        }
    }
}

if (isset($_POST['nightMode']))
{
    $nightMode = $_POST['nightMode'];

    if (empty($nightMode)) {
        echo "<div class='alert alert-danger'>nightMode cannot be empty.</div>";
    } else {
        $userPreference->id = $user_id;
        $userPreference->nightMode = $nightMode;

        if ($userPreference->updateUserNightMode()) {
            echo 'nightMode changed.';
        } else {
            echo 'nightMode not changed.';
        }
    }
}

if (isset($_POST['userColor']))
{
    $userColor = $_POST['userColor'];

    if (empty($userColor)) {
        echo "<div class='alert alert-danger'>userColor cannot be empty.</div>";
    } else {
        $userPreference->id = $user_id;
        $userPreference->userColor = $userColor;

        if ($userPreference->updateUserColor()) {
            echo 'userColor changed.';
        } else {
            echo 'userColor not changed.';
        }
    }
}

if (isset($_POST['dashboardColor']))
{
    $dashboardColor = $_POST['dashboardColor'];

    if (empty($dashboardColor)) {
        echo "<div class='alert alert-danger'>dashboardColor cannot be empty.</div>";
    } else {
        $userPreference->id = $user_id;
        $userPreference->dashboardColor = $dashboardColor;

        if ($userPreference->updateUserDashboardColor()) {
            echo 'dashboardColor changed.';
        } else {
            echo 'dashboardColor not changed.';
        }
    }
}

if (isset($_POST['sidebarToggle']))
{
    $sidebarToggle = $_POST['sidebarToggle'];

    if (empty($sidebarToggle)) {
        echo "<div class='alert alert-danger'>sidebarToggle cannot be empty.</div>";
    } else {
        $userPreference->id = $user_id;
        $userPreference->sidebarToggle = $sidebarToggle;

        if ($userPreference->updateSidebarToggle()) {
            echo 'sidebarToggle changed.';
        } else {
            echo 'sidebarToggle not changed.';
        }
    }
}