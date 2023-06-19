<?php

/**
 * Page Content
 *
 * Handle the Page Content
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

if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
    include_once dirname(__FILE__).'/objects/gamification.php';
    include_once dirname(__FILE__).'/gamification/instantiate_game.php';
}

include_once dirname(__FILE__).'/objects/user_preferences.php';
include_once dirname(__FILE__).'/user_preferences/instantiate_user_preferences.php';

    require_once 'main_navbar.php';
?>
    <!-- /main navbar and Gamification -->

    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md sidebar-fixed">

            <!-- Sidebar mobile toggler -->
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-main-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                Navigation
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <!-- /sidebar mobile toggler -->


            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!--?php require 'sidebar_user_material.php'; ?-->

                <!-- Main navigation -->
                <?php require 'sidebar_main_navigation.php' ?>
                <!-- /main navigation -->

            </div>
            <!-- /sidebar content -->
            
        </div>
        <!-- /main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper bg-<?php echo $nightMode ?>">

            <?php require_once 'page_header.php' ?>

            <!-- Content area -->
            <div class="content" id="content-area-div"> 
            <?php

            if ($_SESSION['admin_num'] > 1) {
                include_once 'welcome_admin.php';
                include_once './user_preferences/dashboard_html.php';
            } else {
                include_once './user_preferences/dashboard_html.php';
            }
            
            ?>
            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

		<?php
				require 'sidebar_admin_right.php';
		?>

	</div>
	<!-- /page content -->
