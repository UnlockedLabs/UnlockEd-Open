<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category_enrollments.php';

//ensure admin user (admin is 2 and above)
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$category_enrollments = new CategoryEnrollment($db);

if ($_GET) {
    // get GET data
    $category_enrollments->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: missing CATEGORY ID.');
    // get number of enrollments in category
    echo $category_enrollments->countByCategoryId();
}
