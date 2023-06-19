<?php

/**
 * Updates site setting email_enabled
 *
 * PHP version 7.2.5
 *
 * @category  Main_App
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__) . '/../../session-validation.php';

//ensure admin user (admin is 4 and above)
if (($_SESSION['admin_num'] < 4)) {
    die('<h1>Restricted Action!</h1>');
}

// include database and object files
require_once dirname(__FILE__) . '/../../config/core.php';
require_once dirname(__FILE__) . '/../../config/database.php';
require_once dirname(__FILE__) . '/../../objects/site_settings.php';

// instantiate database and SiteSettings object
$database = new Database();
$db = $database->getConnection();
$setting = new SiteSettings($db);

$email_value = isset($_POST['emailEnabled']) ?
    $_POST['emailEnabled'] : die('ERROR: missing value.');
    
$setting->setting = 'email_enabled';
$setting->value = $email_value;
echo $setting->update();
