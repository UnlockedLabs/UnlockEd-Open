<?php

/**
 * Welcome Admin
 *
 * Handle Welcoming the Administrator
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

?>

<script>
window.addEventListener("load", function() {
    var admin_title = '';
    switch (<?php echo $_SESSION['admin_num'] ?>) {
        case 1:
            break;
        case 2:
            $('#admin-title').html('Facilitator').addClass('bg-indigo');
            $('#admin-help').html('<i class="icon-help text-blue-400"></i>');
            admin_title = 'a Facilitator';
            break;
        case 3:
            $('#admin-title').html('Instructor').addClass('bg-orange');
            $('#admin-help').html('<i class="icon-help text-blue-400"></i>');
            admin_title = 'an Instructor';
            break;
        case 4:
            $('#admin-title').html('School Administrator').addClass('bg-violet');
            $('#admin-help').html('<i class="icon-help text-blue-400"></i>');
            admin_title = 'a School Administrator';
            break;
        case 5:
            $('#admin-title').html('Site Administrator').addClass('bg-pink');
            $('#admin-help').html('<i class="icon-help text-blue-400"></i>');
            admin_title = 'a Site Administrator';
            break;
    }
});
</script>
<?php //require 'traffic_sources.php'; ?>