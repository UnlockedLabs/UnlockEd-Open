<?php

/**
 * Update Coins
 *
 * Handle updating coins.
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

require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/gamification.php';

// instantiate database
$database = new Database();
$db = $database->getConnection();

// instantiate object
$game = new Game($db);

// set category property values
$user_id = $_SESSION['user_id'];
$coins = $_POST['coins'];
$user_level = $_POST['user_level'];
$user_status = strtoupper($_POST['user_status']);
$username = $_SESSION['username'];

// validate
if ((empty($user_id))) {
    echo "<div class='alert alert-danger'>User ID cannot be empty.</div>";
} elseif (empty($coins)) {
    echo "<div class='alert alert-danger'>Coins cannot be empty.</div>";
} elseif (empty($user_level)) {
    echo "<div class='alert alert-danger'>User Level cannot be empty.</div>";
} elseif (empty($user_status)) {
    echo "<div class='alert alert-danger'>User Status cannot be empty.</div>";
} elseif (empty($username)) {
    echo "<div class='alert alert-danger'>Username cannot be empty.</div>";
} else {
    $game->id = $user_id;
    $game->coins = $coins;
    $game->user_level = $user_level;
    $game->user_status = $user_status;
    $game->username = $username;

    if ($game->updateUserCoins()) {
        echo 'Coins added.';
    } else {
        echo 'Coins not added.';
    }
}
