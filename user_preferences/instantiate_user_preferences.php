<?php

/**
 * Instantiate User Preference
 *
 * Instantiate User Preference object, get banner image number,
 * night_mode, user's color preference, user's dashboard color preference
 *
 * PHP version 7.2.5
 *
 * @category  Preference
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

    namespace unlockedlabs\unlocked;

    include_once dirname(__FILE__).'/../objects/user_preferences.php';

    // instantiate preference object
    $userPreference = new userPreference($db);
    $userPreference->id = $_SESSION['user_id'];
    $bannerNum = $userPreference->getBannerNum();
    $nightMode = $userPreference->getNightMode();
    $userColor = $userPreference->getUserColor();
    $dashboardColor = $userPreference->getDashboardColor();
    $userSidebar = $userPreference->getUserSidebarToggle();
    $sidebarToggleBtn = $userPreference->getUserSidebarToggleBtn();