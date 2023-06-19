<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category_administrators.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cat_admin = new CategoryAdministrator($db);

if ($_POST) {
    $cat_admin->category_id = isset($_POST['cat_id']) ? $_POST['cat_id'] : die('ERROR: missing CATEGORY ID.');
    $cat_admin->administrator_id = isset($_POST['cat_admin_id']) ? $_POST['cat_admin_id'] : die('ERROR: missing ADMINISTRATOR ID.');

    // delete School Admin
    if ($cat_admin->delete()) {
        // tell the user School Admin was deleted
        echo <<<_ALERT
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                School Administrator deleted.
            </div>
_ALERT;
    }

}
