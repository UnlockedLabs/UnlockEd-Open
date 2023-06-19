<?php

/**
 * Main Navbar
 *
 * HTML Main Navbar
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

<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark fixed-top">
    <div class="mt-2 mr-3">
        <a href="index.php" class="d-inline-block">
            <img src="images/UELogo.png" alt="UnlockED Navbar Logo" width="130px">
        </a>
    </div>

    <div class="d-md-none togglers-mobile">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-menu2"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-menu"></i>
        </button>

        <?php
            if ($_SESSION['admin_num'] > 1) {
                echo <<<_ADMINMENUTOGGLE
                <button class="navbar-toggler sidebar-mobile-right-toggle" id="admin_toggler_mobile" type="button" style="display:none;">
                    <i class="icon-more"></i>
                </button>
_ADMINMENUTOGGLE;
        }
        ?>

    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <!-- MOVED TO SIDEBAR (id: toggleSidebarBtn) -->
        <!--ul class="navbar-nav togglers">
            <li class="nav-item">
                <a href="#" onclick="$('.sidenavneedscollapse').fadeToggle();" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block" data-fouc>
                    <i class="icon-transmission"></i>
                </a>
            </li>
        </ul-->
        

        <?php
            $title_text = <<<_TITLE
<span class='admin-tooltip tooltip-title'>Administrative Sidebar</span>
_TITLE;
            $content_text = <<<_DATACONTENT
<span class='admin-tooltip'><p>This administrative level badge also acts
as a context-sensitive administrative sidebar toggle, which will
reveal the appropriate sidebar relative to the current view or previously
clicked link.</p>
<p>Administrative sidebars are only viewable for those with
access privileges to the particular school, category, course, or
cohort. Where applicable, hovering over the badge will reveal a
tooltip with a relevant description for that sidebar.</p></span>
_DATACONTENT;
        ?>
        <ul class="navbar-nav ml-md-3 mr-md-auto">
            <li class="nav-item">
                <span class="badge" id="admin-title" data-popup="popover"></span>
                <span class="badge" id="admin-help" data-popup="popover" title="<?php echo $title_text; ?>" data-trigger="hover" data-html="true" data-content="<?php echo $content_text; ?>"><!--<i class="icon-help text-blue-400"></i>--></span>
            </li>
        </ul>
        <ul class="navbar-nav">
        <?php if ($_SESSION['admin_num']>4): ?>  
            <li class="nav-item">
                <a href="admin/index.php" class="navbar-nav-link">
                <i class="icon-cog5" data-popup="tooltip" data-placement="bottom" title="Admin"></i>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($current_site_settings['email_enabled']=='true'): ?>
            <li class="nav-item">
                <a href="lc_email/index.php" class="navbar-nav-link dropdown-toggle caret-0">
                    <i class="icon-bubbles4" data-popup="tooltip" data-placement="bottom" title="Messages"></i>
                    <span class="d-md-none ml-2">Messages</span>
                    <span class="badge badge-pill bg-warning-400 ml-auto ml-md-0 unread-messages-badge">0</span>
                </a>
            </li>
        <?php endif; ?>

          
<?php

if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
    echo <<<COINSNAV
            <li class="nav-item">
                <!-- COIN STATUS BUTTON -->
                <button href="#" id="show_coin_info" class="btn btn-link text-light ml-auto media">
                    <p class="coin_count">{$coinsDelimeter}</p>
                    <img src="./images/gamification/coins/gold_{$userLevel}.png" id="nav_coin_img" class="ml-2 mr-2" width="{$coinImageSize}" />
                    <b class="text-{$levelColor}" id="status">{$userStatus}</b>
                </button>
                <!-- / COIN STATUS BUTTON -->
            </li>
COINSNAV;
}
?>
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <span  class="btn rounded-round btn-icon mr-2" style="background-color: <?php echo $userColor;?>">
                        <span class="letter-icon text-light"><?php echo ucfirst(substr($_SESSION['username'], 0,1)); ?></span>
                    </span>
                    <span class="ml-1"><?php echo ucwords($_SESSION['username']); ?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <?php if ($current_site_settings['email_enabled']=='true'): ?>
                        <a href="lc_email/index.php" class="dropdown-item"><i class="icon-mail-read"></i> My Messages </a>
                    <?php endif; ?>
                    <div class="dropdown-divider"></div>

                    <a href="index.php?logout=1" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                    
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->

<?php

if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
    include_once dirname(__FILE__).'/gamification/gamification_html.php'; 
}
?>