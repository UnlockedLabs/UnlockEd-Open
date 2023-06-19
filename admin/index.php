<?php
namespace unlockedlabs\unlocked;

require_once dirname(__FILE__).'/./admin-session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/user_preferences.php';


$_SESSION['current_site_settings']['gamification_enabled'] = 'false';

// instantiate database and objects
$database = new Database();
$db = $database->getConnection();

$userPreference = new userPreference($db);
$userPreference->id = $_SESSION['user_id'];
$userColor = $userPreference->getUserColor();

require_once dirname(__FILE__).'/../layout_header.php';
require_once dirname(__FILE__).'/page_content.php';
require_once dirname(__FILE__).'/../layout_footer.php';
?>
<script>
    $('body').removeClass("sidebar-xs");
    $(document).ready(function(){
        $('body').css('background',"url('../images/3.jpg') no-repeat center center fixed") 
            .css('background-size', 'cover');

        $('[href="admin_dashboard.php"').click();

        $.sessionTimeout({
            heading: 'h5',
            title: 'Session expiration',
            message: 'Your session is about to expire. Do you want to stay connected and extend your session?',
            keepAliveUrl: '../analytics/keep_session_alive.php',
            keepAlive: true,
            //keepAliveInterval: 1000, //send ajax post every 1 second
            keepAliveInterval: 600000, //send ajax post every 10 minutes  
            redirUrl: 'index.php?logout=1',
            logoutUrl: 'index.php?logout=1',
            //warnAfter: 5000, //5 seconds
            warnAfter: 1500000, //25 minutes
            //redirAfter: 10000, //10 seconds
            redirAfter: 1560000, //26 minutes
            keepBtnClass: 'btn btn-success',
            keepBtnText: 'Extend session',
            logoutBtnClass: 'btn btn-light',
            logoutBtnText: 'Log me out',
            ignoreUserActivity: false,
        });
    });
</script>
