<?php

namespace unlockedlabs\unlocked;

?>

<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark fixed-top">
<div class="mt-2 mr-3">
        <a href="../index.php" class="d-inline-block">
            <img src="../images/UELogo.png" alt="UnlockED Navbar Logo" width="130px">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav ml-md-auto">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span  class="btn rounded-round btn-icon mr-2" style="background-color: <?php echo $userColor;?>">
                    <span class="letter-icon text-light"><?php echo ucfirst(substr($_SESSION['username'], 0,1)); ?></span>
                </span>
                <span class="ml-1"><?php echo ucwords($_SESSION['username']); ?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="../index.php" class="dropdown-item"><i class="icon-arrow-left5"></i> Return</a>
                    <a href="../index.php?logout=1" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->