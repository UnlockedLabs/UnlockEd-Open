<?php

/**
 * Sidebar User Material
 *
 * Handle Sidebar User Material
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

<div class="sidebar-user-material">
    <div class="sidebar-user-material-body">
        <!--<h3><a id="navbar-mobile-toggle" class="float-right mr-3 sidebar-control sidebar-main-toggle" href="#"><i id="sidebarToggleArrow" class="icon-arrow-left8"></i></a></h3>-->

        
        <div class="card-body text-center"> 
            <div>
                <h2 class="mb-2 text-white text-shadow-dark sidenavneedscollapse">UnlockED!</h2>
                <img src="./images/UELogo_128.png" class="img-fluid shadow-1 mb-3 sidenavneedscollapse" alt="" width="80" height="80">
            </div>
            <h6 class="mb-0 text-white text-shadow-dark sidenavneedscollapse">Welcome, <?php echo ucwords($_SESSION['username']); ?></h6>
        </div>
        
        <!--
        <div class="sidebar-user-material-footer mr-2 ml-2">
            <a href="#user-nav" style="color:#049768;" class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle legitRipple" data-toggle="collapse"><span>Navigation</span></a>
        </div>
        -->
    </div>
</div>