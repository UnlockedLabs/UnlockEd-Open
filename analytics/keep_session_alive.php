<?php

/**
 * Keep Session Alive
 *
 * Update Session Activity Time
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

/*
This file is posted to with a ajax request issued from session_idle_timeout.js.
Our current server session timeout is at 30 minutes.
This can be adjusted in session-validation.php included below.

In session_idle_timeout.js we are asking the user to extend the
session after 25 minutes of inactivity via a popup.
If they do not press the extended session button they are
automatically logged out 60 seconds after the popup appears.

*/

require_once dirname(__FILE__) . '/../session-validation.php';

// include database and object files
require_once dirname(__FILE__) . '/../config/core.php';
require_once dirname(__FILE__) . '/../config/database.php';
require_once dirname(__FILE__) . '/../objects/users.php';

// instantiate database and user object
$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$user->updateLoginTime();
