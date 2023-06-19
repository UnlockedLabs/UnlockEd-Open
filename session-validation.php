<?php

/**
 * Session Validation
 *
 * Handle Session Validation
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
    echo '<script>window.location="' . $_SESSION['current_site_settings']['site_url'] . '/index.php"</script>';
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    //echo '<script>window.location="index.php"</script>';
    die();
}


if (!isset($_SESSION['username'])) {
    //echo '<script>window.location="index.php"</script>';
    echo '<script>window.location="' . $_SESSION['current_site_settings']['site_url'] . '/index.php"</script>';
    die();
}

//echo 'session expires in<br>';
//echo date('H:i:s', time() - $_SESSION['last_activity']);

//set the current Unix timestamp in session
$_SESSION['last_activity'] = time();
//echo $_SESSION['last_activity'];
