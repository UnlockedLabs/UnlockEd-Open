<?php

/**
 * Layout Footer
 *
 * HTML Footer Template
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
   <!-- Core JS files -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/main/jquery.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/main/bootstrap.bundle.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/visualization/d3/d3.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/ui/moment/moment.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/pickers/daterangepicker.js"></script>

    <!-- sweet alerts -->
    <!--
        IE11 support
        Sweet Alert library doesn't support IE11 by default.
        In order to enable IE11 support, you need to include 2 additional files:
        promise.min.js and fetch.min.js polyfills located in /js/polyfills/ folder. 
    -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/polyfills/promise.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/polyfills/fetch.min.js"></script>
    <!-- /sweet alerts -->

    <script src="<?php echo LIBSDIR; ?>/limitless/assets/js/app.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/ui/perfect_scrollbar.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/demo_pages/sidebar_secondary.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/notifications/noty.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/notifications/pnotify.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/notifications/jgrowl.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
   
    <!-- admin -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/uploaders/plupload/plupload.full.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/uploaders/plupload/plupload.queue.min.js"></script>
    
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/media/cropper.min.js"></script>

    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/uploaders/fileinput/fileinput.min.js"></script>

    <script src="<?php echo LIBSDIR; ?>/js/custom/ul_uploader_bootstrap.js"></script>
    <!-- /admin -->

    <!-- admin for media and quiz dragability -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/ui/dragula.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/selects/select2.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
    <!-- <script src="<?php echo LIBSDIR; ?>/js/custom/extension_dnd_ul.js"></script> -->
    <!-- /admin for media and quiz dragability -->

    <!-- admin for form wizard -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/wizards/steps.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/inputs/inputmask.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/validation/validate.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/extensions/cookie.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/form_drag_ul.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/createquiz_wiz_ul.js"></script>
    <!-- <script src="<?php echo LIBSDIR; ?>/js/custom/form_wizard_ul.js"></script> -->
    <!-- <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/styling/switch.min.js"></script> -->
    <!-- /admin for form wizard -->
    <!-- /theme JS files -->

    <!--ckeditor-->
    <script src="<?php echo LIBSDIR; ?>/ckeditor4/ckeditor/ckeditor.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/ckeditor_sample.js"></script>
    <!--/ckeditor-->

    <!-- echarts_bars_tornadoes -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/visualization/echarts/echarts.min.js"></script>
    <!-- /echarts_bars_tornadoes -->

    <!-- d3 bubble chart -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/visualization/d3/d3.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
    <!--/d3 bubble chart -->

    <!-- form dual listboxes -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/forms/inputs/duallistbox/duallistbox.min.js"></script>
    <!--/form dual listboxes -->

    <!-- datatables -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
    <!-- <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/demo_pages/datatables_extension_buttons_html5.js"></script> -->
    <!--/datatables -->
    
    <!-- Custom JS files -->
    <script src="<?php echo LIBSDIR; ?>/js/custom/analytics/ana_category.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/analytics/ana_set_time_out.js"></script>

    <script src="<?php echo LIBSDIR; ?>/js/custom/category.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/topic.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/course.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/lesson.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/quiz.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/scroll_to_top.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/lc_helper_functions.js"></script>
    <!--<script src="<?php echo LIBSDIR; ?>/js/custom/horizontalscrollhandler.js"></script>-->
    <script src="<?php echo LIBSDIR; ?>/js/custom/submissions_columns_ul.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/bubbles_ul.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/ul_layout_fixed_sidebar_custom.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/administration_ul.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/form_dual_listboxes_ul.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/grades_ul.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/quiz_bars_ul.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/unread_email_badges.js"></script>
    
    
    <script src="<?php echo LIBSDIR; ?>/js/custom/datatables_gradebook_ul.js"></script>
    <script src="<?php echo LIBSDIR; ?>/js/custom/datatables_student_gradebook_ul.js"></script>

    <script src="<?php echo LIBSDIR; ?>/js/custom/login_signup.js"></script>

<?php

if (isset($_SESSION['username'])) {
    
    //only load scripts if user is logged in
    echo '<script src="' . LIBSDIR . '/js/custom/user_preferences.js"></script>';
    echo '<script src="' . LIBSDIR . '/js/custom/quote_generator.js"></script>';


    echo '<!-- active sesstion tracking -->';
    echo "<script src='" . LIBSDIR . "/limitless/global_assets/js/plugins/extensions/session_timeout.min.js'></script>";
    echo "<script src='" . LIBSDIR . "/js/custom/session_idle_timeout.js'></script>";
    echo '<!-- /active sesstion tracking -->';

    //login_count.php counts users logins for gamification, place before tracking_functions.js
    if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
        include_once dirname(__FILE__).'/gamification/login_count.php';
    }

    $_SESSION['homepage_visited'] += 1;
    
    //show welome greeting on the first login only
    if ($_SESSION['homepage_visited'] === 1) {

        $first_name = ucfirst($_SESSION['username']);

        echo <<<GREET
        <script>
            window.addEventListener("load", function() {
                swal({
                    title: 'Hello $first_name!',
                    html: '<p>Welcome to UnlockED!</p>',
                    type: 'success',
                    backdrop: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonClass: 'btn btn-info',
                    allowOutsideClick: false,
                    confirmButtonText: 'OK',
                });
            });
        </script>
GREET;

    }

}
?>
    <!-- must be below gamifaction to collect coins on item completion -->
    <script src="<?php echo LIBSDIR; ?>/js/custom/tracking_functions.js"></script>
    <!-- /Custom JS files -->

</body>
</html>