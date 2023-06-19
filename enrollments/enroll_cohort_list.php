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
    $cohort_ids = isset($_GET['cohort_enrollments']) ? $_GET['cohort_enrollments'] : die('ERROR: missing COHORT IDS.');
    $cohorts = explode(",", $cohort_ids);

    $name = '';
    foreach ($cohorts as $cohort) {
        $cohort_enrollments->cohort_id = $cohort;
        // get list of enrolled students in cohort
        $stmt = $cohort_enrollments->readAllStudents();
    
        // if (!$stmt->rowCount()) {
        //     echo "<li class='nav-item text-center text-muted usertag'>No students enrolled</li>";
        // }
    
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row); // student_id, username, users.access_id, users.admin_id, cohort_name

            if ($name != $cohort_name) {
                if ($name == '') {
                    echo "<li class='nav-item text-center text-muted pt-1'>{$cohort_name}</li>";
                } else {
                    echo "<li class='nav-item text-center text-muted'>{$cohort_name}</li>";
                }
            }
            $name = $cohort_name;
            $username = ucfirst($username);
            $email_link = "";
            if($_SESSION['current_site_settings']['email_enabled'] == 'true'){
                $email_link = "<a href='./lc_email/lc_compose.php?recipient_id={$student_id}' class='dropdown-item'><i class='icon-mail5'></i> Send message</a>";
            }
            echo <<<_STUDENT
                <li class="media usertag">
                    <a href="#" class="mr-3 position-relative">
                        <img src="libs/limitless/global_assets/images/placeholders/person.jpg" width="24" height="24" class="rounded-circle" alt="">
                        <span class="badge badge-info badge-pill badge-float"></span>
                    </a>
                    <div class="media-body align-self-center">
                        {$username}
                    </div>
                    <div class="ml-3 align-self-center" style="display:none;">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle caret-0 stdnt-ham" data-toggle="dropdown"><i class="icon-more2"></i></a>
                            <div class="dropdown-menu dropdown-menu-right" data-student_id="{$student_id}" data-admin_id="{$admin_id}">
                                {$email_link}    
                            </div>
                        </div>
                    </div>
                </li>
_STUDENT;
        }
    }
}
