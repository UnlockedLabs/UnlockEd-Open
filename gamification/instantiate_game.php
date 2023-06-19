<?php

/**
 * Instantiate Game
 *
 * Instantiate Game object, get coin count, coin count with 
 * comma delimeter, user login count, user rank, user status,
 * user level, coin image size, level color
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

    // instantiate gamification object
    $game = new Game($db);
    $game->id = $_SESSION['user_id'];
    $coinsCurrent = $game->getCoinCount();
    $coinsDelimeter = $game->coinsDelimeter();
    $loginCount = $game->userLoginCount();
    $userRank = $game->getUserRank();
    $userStatus = $game->getUserStatus();
    $userLevel = $game->getUserLevel();
    $coinImageSize = $game->getCoinImageSize();
    $levelColor = $game->getLevelColor();
