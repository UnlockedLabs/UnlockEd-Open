<?php
namespace unlockedlabs\unlocked;

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
        <div class="content-wrapper">

            <?php require_once dirname(__FILE__).'/../page_header.php' ?>

            <!-- Content area -->
            <div class="content" id="content-area-div"> 
            
            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->