<?php

/**
 * Updates site setting gamification_enabled
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

$gamification_value = isset($_POST['gamificationEnabled']) ?
    $_POST['gamificationEnabled'] : die('ERROR: missing value.');
    
$setting->setting = 'gamification_enabled';
$setting->value = $gamification_value;
echo $setting->update();
