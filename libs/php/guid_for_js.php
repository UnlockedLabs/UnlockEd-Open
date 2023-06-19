<?php
namespace unlockedlabs\unlocked;
/**
 * This is for generating guids in Javascript using the GUID object in php
 */
require_once dirname(__FILE__).'/../../session-validation.php';
require_once dirname(__FILE__).'/../../objects/GUID.php';

//ensure admin user (admin is 2 and above)
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');


// instantiate GUID object
$guidcls = new GUID();

echo trim($guidcls->uuid());

?>