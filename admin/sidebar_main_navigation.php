<div class="card card-sidebar-mobile">

    <ul class="nav nav-sidebar" data-nav-type="accordion">
        <li class="nav-item">
            <a href="admin_dashboard.php" class="nav-link" onclick="return ul.admin_fetch_html(this.href, '#content-area-div');">
                <i class="icon-display"></i>
                    <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="course_reports.php" class="nav-link" onclick="return ul.admin_fetch_html(this.href, '#content-area-div');">
                <i class="icon-file-check"></i>
                    <span>Review Course Reports</span>
            </a>
        </li>
        <?php if (($_SESSION['admin_num'] > 3)): ?>
        <li class="nav-item">
            <a href="site_settings.php" class="nav-link" onclick="return ul.admin_fetch_html(this.href, '#content-area-div');">
                <i class="icon-cogs"></i>
                    <span>Site Settings</span>
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
            <a href="user_management.php" class="nav-link" onclick="return ul.admin_fetch_html(this.href, '#content-area-div');">
                <i class="icon-users"></i>
                    <span>User Management</span>
            </a>
        </li>


    </ul>

</div>