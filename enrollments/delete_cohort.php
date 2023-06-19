<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cohort = new Cohort($db);

if ($_POST) {
    $cohort_id = isset($_POST['cohort_id']) ? $_POST['cohort_id'] : die('ERROR: missing COHORT ID.');
    $cohort_name = isset($_POST['cohort_name']) ? $_POST['cohort_name'] : die('ERROR: missing COHORT NAME.');
    $cohort->id = $cohort_id;

    // delete cohort
    if ($cohort->delete()) {
        // tell the user cohort was deleted
        echo <<<_ALERT
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Cohort: {$cohort_name} deleted.
            </div>
_ALERT;
    }

}
