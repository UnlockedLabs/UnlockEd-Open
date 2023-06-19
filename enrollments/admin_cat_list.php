<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category_administrators.php';

// ensure user is Site Admin
if ($_SESSION['admin_num'] < 2) die('<h1>Restricted Action!</h1>');

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$category_administrators = new CategoryAdministrator($db);

if ($_GET) {
    // get GET data
    $category_administrators->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: missing CATEGORY ID.');
    $cat_id = $category_administrators->category_id;
    // get list of School Admins for a school
    $stmt = $category_administrators->readAllAdministrators();

    if (!$stmt->rowCount()) {
        echo "<li class='nav-item text-center text-muted usertag'>No Administrators assigned</li>";
    }

    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        extract($row); // cat_admin_id, username, access_id, admin_id

        $username = ucfirst($username);

        echo <<<_CATADMINHEAD
            <li class="media nav-item usertag">
                <a href="#" class="mr-3 position-relative" data-cat_admin_id="{$cat_admin_id}" data-admin_id="{$admin_id}">
                    <img src="libs/limitless/global_assets/images/placeholders/person.jpg" width="24" height="24" class="rounded-circle" alt="">
                    <span class="badge badge-info badge-pill badge-float"></span>
                </a>
                <div class="media-body align-self-center" data-cat_admin_id="{$cat_admin_id}" data-admin_id="{$admin_id}">
                    {$username}
                </div>
_CATADMINHEAD;
    if ($_SESSION['admin_num'] == 5) {        
        $email_link = "";
        if($_SESSION['current_site_settings']['email_enabled'] == 'true'){
            $email_link = "<a href='./lc_email/lc_compose.php?recipient_id={$cat_admin_id}' class='dropdown-item'><i class='icon-mail5'></i> Send message</a>";
        }    
        echo <<<_DELETELINK
                <div class="ml-3 align-self-center">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle caret-0 stdnt-ham" data-toggle="dropdown"><i class="icon-more2"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" data-cat_admin_id="{$cat_admin_id}" data-cat_id="{$cat_id}">
                            {$email_link}                            
                            <div class="dropdown-divider"></div>
                            <a href="enrollments/delete_cat_admin.php" class="dropdown-item cat-admin-delete"><i class="icon-trash"></i> Unassign Admin</a>
                        </div>
                    </div>
                </div>
_DELETELINK;
    }
        echo <<<_CATADMINTAIL
            </li>
_CATADMINTAIL;
    }

    echo <<<_FUNCTIONS
        <script>
        $('.cat-admin-delete').on('click', function(e){
            e.preventDefault();
            var cat_id = $(this).parent().data('cat_id');
            var cat_admin_id = $(this).parent().data('cat_admin_id');
            var cat_admin_name = $(this).parents('li.usertag').children('div.media-body').text();
            var url = e.target.href;
            var content = $('#content-area-div'); 
    
            swal({
                title: 'Are you sure you want to unassign this School Administrator?',
                html: "<h6 class='mt-2'>"+cat_admin_name+"</h6><p>You won't be able to revert this!</p>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, unassign!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
            
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            cat_id:cat_id,
                            cat_admin_id:cat_admin_id
                        },
                        timeout: 30000,
                        beforeSend: function() {
                            content.html('<div id="load">Loading</div>');
                        },
                        complete: function() {
                            $('#load').remove();
                        },
                        error: function(data) {
                            swal({
                                title: "Error",
                                html: data.statusText,
                                confirmButtonColor: '#3085d6',
                                confirmButtonClass: 'btn btn-info',
                                allowOutsideClick: false,
                                confirmButtonText: 'OK',
                                type: "error"
                            });
                            content.html(data.responseText);
                        }
                    }).done(function(data) {
                        swal({
                            title: "Success",
                            html: "<h6>School Administrator unassigned!</h6>",
                            confirmButtonColor: '#3085d6',
                            confirmButtonClass: 'btn btn-info',
                            allowOutsideClick: false,
                            confirmButtonText: 'OK',
                            type: "success"
                        });
                        content.html(data);
                        
                        var queryString = 'category_id=' + cat_id;
    
                        // get number of administrators for category 
                        $.get(
                            "enrollments/admin_cat_count.php",
                            queryString
                        ).done(function(data) {
                            $("#cat-admin-num > span > span").text(data);
                        });
    
                        // get school admins assigned to category 
                        $.get(
                            "enrollments/admin_cat_list.php",
                            queryString
                        ).done(function(data) {
                            $(".cat-admin-list").html(data);
                        });
                    }).fail(function() {
                        swal({
                            title: "Error",
                            html: data.statusText,
                            confirmButtonColor: '#3085d6',
                            confirmButtonClass: 'btn btn-info',
                            allowOutsideClick: false,
                            confirmButtonText: 'OK',
                            type: "error"
                        });
                        content.html('<div id="load">Please try again soon.</div>');            
                    });
                }
                else if (result.dismiss === swal.DismissReason.cancel) {
                    swal({
                        title: "Cancelled",
                        text: "The School Administrator was not unassigned.",
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                        type: "info"
                    });
                }
            });
        });
        </script>
_FUNCTIONS;
}
