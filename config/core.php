<?php

namespace unlockedlabs\unlocked;

require_once 'database.php';
require_once dirname(__FILE__) . '/../objects/site_settings.php';

$database = new Database();
$db = $database->getConnection();
$settings = new SiteSettings($db);
$current_site_settings_collection = $settings->read()->fetchAll(\PDO::FETCH_ASSOC);
$current_site_settings = array();

foreach ($current_site_settings_collection as $key => $value) {
    $current_site_settings[$value['setting']] = $value['value'];
}


// show error reporting
ini_set('display_errors', 1); 
error_reporting(E_ALL);

date_default_timezone_set($current_site_settings['timezone_setting']);
define("LIBSDIR",$current_site_settings['site_url'] . "/libs");
define("FONTSSDIR",$current_site_settings['site_url'] . "/fonts");

function destroySession()
{
    $_SESSION = array();

    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');
    }

    session_destroy();
}
?>