<?php

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__) . '/./admin-session-validation.php';

//ensure admin user (admin is 4 and above)
if (($_SESSION['admin_num'] < 4)) {
    die('<div class="card"><h1>You do not have authority to modify site settings</h1></div>');
}
// include database and object files
require_once dirname(__FILE__) . '/../config/core.php';
require_once dirname(__FILE__) . '/../config/database.php';
require_once dirname(__FILE__) . '/../objects/site_settings.php';
require_once 'gamification/gamification_modal.php';
require_once 'email/email_modal.php';
require_once 'timezone/timezone_modal.php';

$database = new Database();
$db = $database->getConnection();

$settings = new SiteSettings($db);
$current_settings = $settings->read();
?>
<div class="card">
    <div class="card-header">
        <h1>Site Settings</h1>
        <p>These settings control sitewide functionality,
            allowing the site admin to enable/disable various functionality
            as well as allowing the control of various other features
            that effect the functionality of UnlockED.</p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Setting</th>
                            <th>Value</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    while ($row = $current_settings->fetch(\PDO::FETCH_ASSOC)) {
                        extract($row);

                        $description = '';

                        //set description
                        if ($setting == 'email_enabled') {
                            $description = "This setting will either enable or disable
                                messaging functionality throughout the entire site.";
                        } elseif ($setting == 'gamification_enabled') {
                            $description = "This setting will either enable or disable
                                gamification functionality throughout the entire site.";
                        } elseif ($setting == 'timezone_setting') {
                            $description = "The current timezone of your site.
                                This setting affects the time logged in the database.";
                        } elseif ($setting == 'site_url') {
                            $description = "The name of your site’s url.
                                This should match the domain where your site is hosted.";
                        }

                        $tooltip = "<span class='tooltip-initialize' data-popup='tooltip' 
                            itle='' data-original-title='$description'><i class='icon-help mr-1'></i></span>";
                        
                        echo "<tr>";
                        echo "<td>$setting $tooltip</td>";
                        echo "<td id='$setting-value'>$value</td>";
                        if (!$read_only) {
                            echo "<td><a href='#' data-toggle='modal' data-target='#$setting'>edit</a></td>";
                        } else {
                            echo "<td></td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                    <script>$(".tooltip-initialize").tooltip();</script>
                </table>
            </div>
            </div>
        </div> <!--/ row -->
    </div> <!-- /card body -->
</div> <!-- /card -->
