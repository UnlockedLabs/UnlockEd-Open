<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category_administrators.php';
require_once dirname(__FILE__).'/../objects/users.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cat_admin = new CategoryAdministrator($db);
$users = new User($db);

if ($_POST) {
    $cat_id = $_POST['catId'];
    $cat_name = $_POST['catName'];
    $admin_array = isset($_POST['adminArray']) ? $_POST['adminArray'] : [];
    $admin_id_array = isset($_POST['adminIdArray']) ? $_POST['adminIdArray'] : [];
    $plural = (count($admin_array) > 1) ? 's' : '';

    // assign category admins
    for ($i = 0; $i < count($admin_array); $i++) {
        $cat_admin->category_id = $cat_id;
        $cat_admin->administrator_id = $admin_id_array[$i];

        if ($cat_admin->create()) {
            // set administrators admin_id to 4 (School Administrator)
            $users->id = $admin_id_array[$i];
            $users->updateAdminId(4);
        }
    }

    // tell the school administrator assignments were completed
    echo <<<_ALERT
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            School Administrator assignment{$plural} for {$cat_name} completed.
        </div>
_ALERT;

}
