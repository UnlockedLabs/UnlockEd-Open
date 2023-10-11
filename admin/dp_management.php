<?php

/**
 * Downstream Provider Management
 *
 * PHP version 8.1.0
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
require_once dirname(__FILE__) . '/../objects/dp_management.php';

$database = new Database();
$db = $database->getConnection();

// $token = $access_token;

// $url = "http://localhost:8081/test/api.php";//.$name;
$url = "http://localhost/api/v1/1/platform_connections"; // consumer_platform_id hardcoded for now
		

    $client = curl_init();
    curl_setopt($client, CURLOPT_URL, $url);
    curl_setopt($client, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
    // curl_setopt($client, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer ' .$token ) );
    curl_setopt($client, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($client, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // what is the CURLAUTH_BASIC option?
    curl_setopt($client, CURLOPT_ENCODING, "");
    $response = curl_exec($client);
    curl_close($client);

    $result = json_decode($response, true); // this returns an array
?>

<div id="dp-management-div"></div>

<!-- Basic initialization -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Downsteam Provider Management</h3>
    </div>

    <!-- <div class="card-body">
        <a href="users/create_user_admin.php" class="btn bg-teal-400 btn-labeled btn-labeled-left rounded-round get-um-html">
        <b><i class="icon-reading"></i></b> Submit</a>
    </div> -->
    <div class="card-body">
        <?php 
            if (count($result['data']) == 0) {
                echo "<b>No DPs available</b>";
            } else {
                echo "<div class='row'>";
                for ($i = 0; $i < count($result['data']); $i++) {
                    $type = $result['data'][$i]['type'];
                    $id = $result['data'][$i]['id'];
                    $name = $result['data'][$i]['name'];
                    $description = $result['data'][$i]['description'];
                    $icon_url = $result['data'][$i]['icon_url'];
                    $account_id = $result['data'][$i]['account_id'];
                    // $account_name = $result['data'][$i]['account_name'];
                    $access_key = $result['data'][$i]['access_key'];
                    $base_url = $result['data'][$i]['base_url'];
                    $state = $result['data'][$i]['state'];
                    $connection_id = $result['data'][$i]['connection_id'];
                    echo <<<DP_CARD
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-0">$type</h5>
                                    <p>ProviderPlatformID: $id</p>
                                    <p>Type: $type</p>
                                    <p>Name: ($name)</p>
                                    <p>Description: $description</p>
                                    <p>IconURL: $icon_url</p>
                                    <p>AccountId: $account_id</p>
                                    <p>AccessKey: $access_key</p>
                                    <p>BaseURL: $base_url</p>
                                    <div class="form-check form-switch form-check-reverse mb-2">
DP_CARD;
                    if ($state == "enabled") {
                        echo '<input type="checkbox" class="form-check-input toggle-dp" id="connection_state_'.$i.'" data-connectionid="'.$connection_id.'" checked="">';
                        echo '<label class="form-check-label" for="connection_state_'.$i.'">Enabled</label>';
                    } elseif ($state == "disabled") {
                        echo '<input type="checkbox" class="form-check-input toggle-dp" id="connection_state_'.$i.'" data-connectionid="'.$connection_id.'">';
                        echo '<label class="form-check-label" for="connection_state_'.$i.'">Disabled</label>';
                    }
                                    // <p>State: $state</p>
                                    // <p>Connection Id: $connection_id</p>
                                        // <input type="checkbox" class="form-check-input toggle-dp" id="sc_rs_c" data-connectionid="$connection_id">
                                        // <label class="form-check-label" for="sc_rs_c">Checked</label>
                    echo <<<DP_CARD_2
                                    </div>
                                </div>
                            </div>
                        </div>
DP_CARD_2;
                }
            }
            echo "</div>";
        ?>
    </div>

        <!-- </tbody>
    </table> -->
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

    $('.toggle-dp').click(function(e) {

        if ($(this).is(":checked"))
        {
            $("label[for='" + $(this).attr('id') + "']").html("Enabled");
            // $("#connection_state_label").html("Enabled");
            var state = "enabled";
        }
        if ($(this).is(":unchecked"))
        {
            // alert("I am unchecked!");
            $("label[for='" + $(this).attr('id') + "']").html("Disabled");
            // $(this).labels().html("Disabled");
            // $("#connection_state_label").html("Disabled");
            var state = "disabled";
        }

        var $connection_id = $(this).data('connectionid');
        var $url = 'http://localhost/api/v1/platform_connections/' + $connection_id + '/change_connection_state';
        var patch = {
            "state" : state
        }

        $.ajax({
            type: 'PATCH',
            url: $url,
            data: JSON.stringify(patch),
            processData: false,
            contentType: 'application/merge-patch+json',
            timeout: 30000,
            beforeSend: function(xhr) {
            //     xhr.setRequestHeader('Authorization', 'Bearer <bearer token here>');
            },
            complete: function() {
            //     $('#load').remove();
            },
            success: function() {

            },
            error: function(data) {
                // $content.html(data.responseText);
            }
        }).done(function(data) {
            // $content.html(data);
        }).fail(function() {
            // $content.html('<div id="load">Please try again soon.</div>');            
        });
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