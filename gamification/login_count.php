<?php

/**
 * Login Count
 *
 * Handle login count
 *
 * PHP version 7.2.5
 *
 * @category  Gamification
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

    namespace unlockedlabs\unlocked;
    
    require_once dirname(__FILE__).'/../objects/gamification.php';

    // GAMIFICATION JS
    echo '<script src="libs/js/custom/gamification.js"></script>';

    // VARIABLES FOR WHEN CUSTOM SET THRESHOLDS ARE MET. TO BE SET BY ADMINS
    $loginRewardThresholdOne = 10;
    $loginRewardThresholdTwo = 50;
    $loginRewardThresholdThree = 100;

    // MAKES INITIAL CHECK OF STATUS AND COIN ACCUMULATION ON LOGIN
    echo "<script>updateStatus('login', " . $coinsCurrent . ");</script>";

    // CHECKS TOTAL LOGINS AND INCREASES TOTAL LOGINS BY 1
if ($loginCount['logins'] == 1) {
    // WELCOME MODAL WITH FREE COINS
    echo "<script>gamificationModal('new')</script>";
    $game->addLoginCount();
} elseif ($loginCount['logins'] == $loginRewardThresholdOne) {
    // FIRST REWARD MODAL WITH FREE COINS FOR RETURNING
    echo "<script>gamificationModal('rewardOne');</script>";
    $game->addLoginCount();
} elseif ($loginCount['logins'] == $loginRewardThresholdTwo) {
    // SECOND REWARD MODAL WITH FREE COINS FOR RETURNING
    echo "<script>gamificationModal('rewardTwo');</script>";
    $game->addLoginCount();
} elseif ($loginCount['logins'] == $loginRewardThresholdThree) {
    // THIRD/FINAL REWARD MODAL WITH FREE COINS FOR RETURNING
    echo "<script>gamificationModal('rewardThree');</script>";
    $game->addLoginCount();
}

