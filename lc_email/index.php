<?php

namespace unlockedlabs\unlocked;

require_once '../session-validation.php'; 
require_once 'lc_ensure_email.php';

require_once '../config/core.php';
require_once 'lc_layout_header.php';
require_once 'lc_main_navbar.php';
unset($_SESSION['unread_email_count']);
?>

<body class="navbar-top bg-grey-300" id="lc_reminder">
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <div class="content d-flex justify-content-center align-items-center">
                <div class="card-body animated fadeIn" id="welcome_elems">
                    <div class="text-center mb-2">
                        <img src="./images/UELogo_white.png" width="90px">
                    </div>
                    <div class="text-center mb-2">
                        <h1 class="mb-0 text-light font-weight-light">We just wanted to remind you that messages are<br>subject to being monitored and recorded.</h1>
                    </div>
                    <div class="text-center">
                        <a href="#" class="btn btn-lg border-light text-light" id="get_started">Get Started</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
<?php require_once 'lc_layout_footer.php'; ?>
<script>
    $("#get_started").on("click", function() {
    $("#welcome_elems").toggleClass("fadeIn fadeOut");
        setTimeout( function() {
        window.location.href = "lc_inbox.php";
    }, 750)
    });
</script>
</body>
