<?php

/**
 * Index
 *
 * Handle the Index Page
 *
 * PHP version 7.2.5
 *
 * @category Main_App
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

// include database and object files
require_once dirname(__FILE__) . '/config/core.php';
require_once dirname(__FILE__) . '/config/database.php';
require_once dirname(__FILE__) . '/objects/category.php';
require_once dirname(__FILE__) . '/objects/users.php';
require_once dirname(__FILE__) . '/objects/user_preferences.php';
require_once dirname(__FILE__) . '/objects/cohort.php';
require_once dirname(__FILE__) . '/objects/category_enrollments.php';
require_once dirname(__FILE__) . '/objects/cohort_enrollments.php';
require_once dirname(__FILE__) . '/objects/category_administrators.php';
require_once dirname(__FILE__) . '/objects/course_administrators.php';
// require_once dirname(__FILE__) . '/objects/site_settings.php';
require_once dirname(__FILE__) . '/lc_email/objects/email.php';




session_start();

// instantiate database and objects
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$user = new User($db);
$cat_enrollment = new CategoryEnrollment($db);
$cohort_enrollment = new CohortEnrollment($db);
$cat_admin = new CategoryAdministrator($db);
$course_admin = new CourseAdministrator($db);
$cohort = new Cohort($db);
$userPreference = new UserPreference($db);
// $settings = new SiteSettings($db);
// $current_site_settings_collection = $settings->read()->fetchAll(\PDO::FETCH_ASSOC);
// $current_site_settings = array();

// foreach ($current_site_settings_collection as $key => $value) {
//     $current_site_settings[$value['setting']] = $value['value'];
// }
$_SESSION['current_site_settings'] = $current_site_settings;

if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
    include_once dirname(__FILE__) . '/objects/gamification.php';
    $game = new Game($db);
}


require_once 'layout_header.php';

//log user out and destroy session
if (isset($_GET['logout'])) {
    if (isset($_SESSION['username'])) {
        // be sure to call resetLoginTimestamp before calling destroySession
        $user->resetLoginTimestamp();
        $userPreference->loginResetUserPreferences(); // resets certain user preferences on login
    }
    destroySession();
}

//log user in
if (isset($_POST['username'])) {
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    $login = $user->checkUserNamePwd();

    if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
        // increases login count by 1 for gamification
        if (isset($_SESSION['user_id'])) {
            $game->id = $_SESSION['user_id'];
            $game->addLoginCount();
        }
    }
    
    if (isset($_SESSION['user_id'])) {
        $cat_enrollment->student_id = $_SESSION['user_id'];
        $cohort_enrollment->student_id = $_SESSION['user_id'];
        // initialize session arrays
        $_SESSION['enrolled'] = [];
        $_SESSION['enrolled']['cat'] = [];
        $_SESSION['enrolled']['cohort'] = [];
        $_SESSION['admin'] = [];
        $_SESSION['admin']['cat'] = [];
        $_SESSION['admin']['course'] = [];
        $_SESSION['admin']['facilitator'] = [];

        $stmt = $cat_enrollment->readAllCategoriesForStudent();
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $_SESSION['enrolled']['cat'][] = $category_id;
        }
        
        $cat_admin->administrator_id = $_SESSION['user_id'];
        $stmt2 = $cat_admin->readAllCategoriesForAdmin();

        while ($row = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $_SESSION['admin']['cat'][] = $category_id;
        }

        $course_admin->administrator_id = $_SESSION['user_id'];
        $stmt3 = $course_admin->readAllCoursesForAdmin();

        while ($row = $stmt3->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $_SESSION['admin']['course'][] = $course_id;
        }

        $cohort->facilitator_id = $_SESSION['user_id'];
        $stmt4 = $cohort->readAllCohortsForFacilitator();

        while ($row = $stmt4->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $_SESSION['admin']['facilitator'][] = $id;
        }

        $stmt5 = $cohort_enrollment->readAllCohortsForStudent();

        while ($row = $stmt5->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $_SESSION['enrolled']['cohort'][] = $cohort_id;
        }
    }
}

if (isset($_SESSION['username'])) {
    include_once 'page_content.php';
} elseif (isset($_GET['create-account'])) {
    include_once 'sign_up.php';
} else {
    include_once 'login.php';
}

require_once 'layout_footer.php';
