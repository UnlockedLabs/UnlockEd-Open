<?php

/**
 * User Management
 *
 * PHP version 7.2.5
 *
 * @category  Main_App
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__) . '/./admin-session-validation.php';

// include database and object files
require_once dirname(__FILE__) . '/../config/core.php';
require_once dirname(__FILE__) . '/../config/database.php';
require_once dirname(__FILE__) . '/../objects/users.php';

$database = new Database();
$db = $database->getConnection();

$users = new User($db);
$all_users = $users->readAll();
?>

<div id="user-management-div"></div>

<!-- Basic initialization -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Management</h3>
    </div>

    <div class="card-body">
        <a href="users/create_user_admin.php" class="btn bg-teal-400 btn-labeled btn-labeled-left rounded-round get-um-html">
        <b><i class="icon-reading"></i></b> Create New User</a>
    </div>

    <table class="table datatable-button-html5-basic">
        <thead>
            <tr>
                <th>id</th>
                <th>username</th>
                <th>email</th>
                <th>oid</th>
                <th>admin_id</th>
                <th>logged_in</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            while ($row = $all_users->fetch(\PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>$id</td>";
                echo "<td>$username</td>";
                echo "<td>$email</td>";
                echo "<td>$oid</td>";
                echo "<td>$admin_id</td>";
                if ($logged_in == '1970-01-01 00:00:01') {
                    echo "<td><span class='badge badge-danger'>No</span></td>";
                } else {
                    echo "<td><span class='badge badge-success'>$logged_in</span></td>";
                }
                echo <<<HTML
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="users/update_user_admin.php?id=$id" class="dropdown-item get-um-html"><i class="icon-clipboard"></i> Edit</a>
                                <a href="users/delete_user_admin.php?id=$id" class="dropdown-item get-um-html"><i class="icon-cross3"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
HTML;

            }
        ?>

        </tbody>
    </table>
</div>
<!-- /basic initialization -->

<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

// get html for user management functionality
$('.get-um-html').on('click', function(e) {

    e.preventDefault();
    scrollToTopCustom();
    var url = e.currentTarget.href;
    ul.ajax_content_area(url, "#user-management-div");

});
}) ();
</script>


<script>

    /* ------------------------------------------------------------------------------
 *
 *  # Buttons extension for Datatables. HTML5 examples
 *
 *  Demo JS code for datatable_extension_buttons_html5.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var DatatableButtonsHtml5 = function() {


//
// Setup module components
//

// Basic Datatable examples
var _componentDatatableButtonsHtml5 = function() {
    if (!$().DataTable) {
        console.warn('Warning - datatables.min.js is not loaded.');
        return;
    }

    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
    });


    // Basic initialization
    $('.datatable-button-html5-basic').DataTable({
        buttons: {            
            dom: {
                button: {
                    className: 'btn btn-light'
                }
            },
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]				

        }
    });

};

// Select2 for length menu styling
var _componentSelect2 = function() {
    if (!$().select2) {
        console.warn('Warning - select2.min.js is not loaded.');
        return;
    }

    // Initialize
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        dropdownAutoWidth: true,
        width: 'auto'
    });
};


//
// Return objects assigned to module
//

return {
    init: function() {
        _componentDatatableButtonsHtml5();
        _componentSelect2();
    }
}
}();


// Initialize module
// ------------------------------

//document.addEventListener('DOMContentLoaded', function() {
//DatatableButtonsHtml5.init();
//});

DatatableButtonsHtml5.init();
</script>