<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort_enrollments.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$cohort_enrollments = new CohortEnrollment($db);

if ($_GET) {
    // get GET data
    $cohort_enrollments->cohort_id = isset($_GET['cohort_id']) ? $_GET['cohort_id'] : die('ERROR: missing COHORT ID.');
    // get number of enrollments in cohort
    echo $cohort_enrollments->countByCohortId();
}
