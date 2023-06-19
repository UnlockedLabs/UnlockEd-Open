<?php

/**
 * Email Access Validation
 *
 * Handle Email Access Validation
 *
 * PHP version 7.2.5
 *
 * @category Email
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

if(!isset($_SESSION['current_site_settings']['email_enabled'])) {
    die();
}

if ($_SESSION['current_site_settings']['email_enabled'] === 'false') {
    header("Location: " . $_SESSION['current_site_settings']['site_url']);
    die();
}
?>