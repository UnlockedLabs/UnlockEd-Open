<?php
namespace unlockedlabs\unlocked;
require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category.php';
require_once dirname(__FILE__).'/../objects/users.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$users = new User($db);

echo <<<_CATADMINSHEAD
    <div class="card">
        <div class="card-header text-center">
            <h3 class="card-title">Assign School Administrators</h5>
        </div>
        <div class="card-body">
            <p class="mb-3">To assign an administrator to a school,
            select a school from the left field list and at least
            one person from right field list. To assign more than one administrator, SHIFT click or CTRL
            click additional individuals.</p>
            <p class="mb-3">When you are satisfied with your
                changes, then click ASSIGN ADMINISTRATOR(S).</p>
            <p class="mb-3 font-size-xs">NOTE: If the individual(s) you
            select have an administrative level less than School Administrator,
            the selected individual(s) will be promoted to School Administrator
            status.</p>
        </div>
        <form class="assignments" id="assign-cat-admins" action="enrollments/assign_cat_admins.php" method="post" data-fouc>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-auto">School Name:</label>
                            <div class="col-lg-8">
                                <select name="category_id" id="cat" class="form-control form-control-uniform" data-fouc>
_CATADMINSHEAD;

$stmt = $category->readCategories();
while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
    extract($row);
    echo "<option value='{$id}'>{$category_name}</option>";
}

echo <<<_CATADMINSBODY
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-auto">Administrator(s):</label>
                            <div class="col-lg-8">
                                <select name="admin_id" id="admin" multiple="multiple" class="form-control" required>
_CATADMINSBODY;

$stmt2 = $users->readAll();
while ($row = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
    extract($row);
    if ($admin_id == 1) {
        continue;
    }
    $username = ucfirst($username);
    echo "<option value='{$id}'>{$username}</option>";
}

echo <<<_CATADMINSTAIL
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="d-flex btn btn-primary ml-auto">Assign Administrator(s)</button>
            </div>
        </form>
    </div>
_CATADMINSTAIL;

?>

<script>
$('#assign-cat-admins').on('submit', function(e) {
    e.preventDefault();
    var url = e.target.action;
    var $content = $('#content-area-div');
    var cat_id = e.target.category_id.value;
    var cat_name = e.target.category_id.selectedOptions[0].childNodes[0].textContent;
    var admin_array = [];
    var admin_id_array = [];
    
    for (var i=0; i < e.target.admin_id.length; i++) {
        if (e.target.admin_id[i].selected == true) {
            admin_array.push(e.target.admin_id[i].text);
            admin_id_array.push(e.target.admin_id[i].value);
        }
    }
    var html = '';
    var count = 0;

    if (admin_array.length > 1) {
        html += "<h6 class='ml-5 text-left'>Assign the following administrators to " + cat_name + ":</h6><ul class='ml-5 text-left'>";
    } else {
        html += "<h6 class='ml-5 text-left'>Assign the following administrator to " + cat_name + ":</h6><ul class='ml-5 text-left'>";
    }

    for (var i of admin_array) {
        html += "<li>" + i + "</li>";
    }
    html += "</ul>";

    swal({
        title: 'Are you sure you want to perform the following actions?',
        html: html,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes!',
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
                    catId:cat_id,
                    catName:cat_name,
                    adminArray:admin_array,
                    adminIdArray:admin_id_array
                },
                timeout: 30000,
                beforeSend: function() {
                    $content.html('<div id="load">Loading</div>');
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
                        type: "error",
                        onClose: function() {
                        }
                    });
                    $content.html(data.responseText);
                }
            }).done(function(data) {
                var plural = (admin_array.length > 1) ? "s" : "";
                swal({
                    title: "Success",
                    html: "<h6>School Administrator"+plural+" assigned!</h6>",
                    confirmButtonColor: '#3085d6',
                    confirmButtonClass: 'btn btn-info',
                    allowOutsideClick: false,
                    confirmButtonText: 'OK',
                    type: "success",
                    onClose: function() {
                    }
                });
                $content.html(data);
                var queryString = 'category_id=' + cat_id;
                // get number of administrators for category 
                $.get(
                    "enrollments/admin_cat_count.php",
                    queryString
                ).done(function(data) {
                    $("#cat-admin-assign > span > span").text(data);
                    $(`.category-link[data-id="${cat_id}"]`).click();
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
                    type: "error",
                    onClose: function() {
                    }
                });
                $content.html('<div id="load">Please try again soon.</div>');
            });
        } else if (result.dismiss === swal.DismissReason.cancel) {
            var plural = (admin_array.length > 1) ? "s" : "";
            swal({
                title: "Cancelled",
                text: "No School Administrator"+plural+" assigned.",
                confirmButtonColor: '#3085d6',
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                confirmButtonText: 'OK',
                type: "info",
                onClose: function() {
                }
            });
        }
    });

});
</script>