<?php

/**
 * Admin Session Validation
 *
 * Handle Admin Session Validation
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

session_start();

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // last request was more than 30 minutes ago (1800)
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    echo '<script>window.location="../index.php"</script>';
    die();
}


if (!isset($_SESSION['username']) || $_SESSION['admin_num'] < 2) {
    echo '<script>window.location="../index.php"</script>';
    die();
}

//set the current Unix timestamp in session
$_SESSION['last_activity'] = time();
