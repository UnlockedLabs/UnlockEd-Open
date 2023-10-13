<?php

/**
 * Register LTI Tool
 *
 * Process register LTI tool form and insert tool into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Register LTI tool.
 *
 * PHP version 8.1.0
 *
 * @category Main_App
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */

require_once 'session-validation.php';
//ensure admin user (admin is 2 and above)
if (($_SESSION['admin_num'] < 2)) {
    die('<h1>Restricted Action!</h1>');
}

// include LTI library and utility files
require_once dirname(__FILE__).'/LTI/misc.php';
require_once dirname(__FILE__).'/LTI/ims-blti/blti_util.php';
require_once dirname(__FILE__).'/LTI/Platform/config/database.php';
require_once dirname(__FILE__).'/LTI/objects/tool.php';


$database = new \LTIPlatform\Database();
$db = $database->getConnection();
$tool = new \LTITool\Tool($db);

$category_name = isset($_GET['category_name']) ? $_GET['category_name'] : die('ERROR: missing Category Name.');

// if the form was submitted
if ($_POST) {
    // set tool property values
    $name = $_POST['name'];
    $client_id = $_POST['client_id'];
    $login_url = $_POST['login_url'];
    $launch_url = $_POST['launch_url'];
    $consumer_key = $_POST['consumer_key'];
    $version = $_POST['version'];
    $public_key = $_POST['public_key'];
    
    if ((empty($name))) {
        echo "<div class='alert alert-danger'>Display Name cannot be empty.</div>";
    } elseif ((empty($client_id))) {
        echo "<div class='alert alert-danger'>Provider ID cannot be empty.</div>";
    } elseif ((empty($login_url))) {
        echo "<div class='alert alert-danger'>Login URL cannot be empty.</div>";
    } elseif ((empty($launch_url))) {
        echo "<div class='alert alert-danger'>Launch URL cannot be empty.</div>";
    } elseif ((empty($consumer_key))) {
        echo "<div class='alert alert-danger'>Consumer (Platform) Key cannot be empty.</div>";
    } elseif ((empty($version))) {
        echo "<div class='alert alert-danger'>LTI Version cannot be empty.</div>";
    } elseif ((empty($public_key))) {
        echo "<div class='alert alert-danger'>Public Key cannot be empty.</div>";
    } else {
        $tool->name = $name;
        $tool->client_id = $client_id;
        $tool->login_url = $login_url;
        $tool->launch_url = $launch_url;
        $tool->consumer_key = $consumer_key;
        $tool->version = $version;
        $tool->public_key = $public_key;
    }

    //ensure the tool isn't already registered
    if ($tool->toolExists()) {
        // tell the user tool is already registered
        echo "<div class=\"alert alert-danger alert-dismissable\">";
        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        echo "Tool is already registered.";
        echo "</div>";
    } elseif ($tool->register()) {
        // tell the user tool was successfully registered
        echo "<div class=\"alert alert-success alert-dismissable\">";
        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        echo "Tool was registered. <a href='./index.php'>Reload page now.</a>";
        echo "</div>";
    } else {
        echo "<div class=\"alert alert-danger alert-dismissable\">";
        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        echo "Unable to register tool.";
        echo "</div>";
    }
}
?>

<div class="card container">
    <div class="card-body">
        <h3 class="card-title text-center"><?php echo $category_name; ?></h3>
        <h3 class="card-title">Register an LTI Tool (Tool Settings)</h3>
        <form id='register-tool-form' action='register_tool.php?id=<?php echo $id; ?>&category_name=<?php echo $category_name; ?>' method='post'>
            <div class="form-group">
                <label for="name">Display Name</label>
                <input type="text" name='name' class="form-control" id="name" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="loginUrl">Login URL (OIDC Login URI)</label>
                <input type="text" name='login_url' class="form-control" id="loginUrl" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="launchUrl">Launch URL</label>
                <input type="text" name='launch_url' class="form-control" id="launchUrl" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="clientId">Client ID</label>
                <input type="text" name='client_id' class="form-control" id="clientId" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="consumerKey">Consumer (Platform) Key (iss)</label>
                <!-- UnlockEd is inserted as the value here for simulation purposes -->
                <input type="text" name='consumer_key' class="form-control" id="consumerKey" value="UnlockEd" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="version">LTI Version</label>
                <!-- 1.3 is inserted as the value here for simulation purposes -->
                <input type="text" name='version' class="form-control" id="version" value="1.3" placeholder="" required>
            </div>
            <!-- <div class="form-group">
                <label for="secret">Secret</label>
                <input type="text" name='secret' class="form-control" id="secret" placeholder="" required>
            </div> -->
            <div class="form-group">
                <label for="publicKey">Public Key</label>
                <input type="text" name='public_key' class="form-control" id="publicKey" placeholder="" required>
            </div>
            <button type="submit" class="btn btn-primary">Register Tool</button>
        </form>
    </div>
</div>

<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

$('#register-tool-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.name.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Name.');
        return false;
    }

    if (!e.target.client_id.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Client ID.');
        return false;
    }

    if (!e.target.login_url.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply OIDC Login URL.');
        return false;
    }

    if (!e.target.launch_url.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Launch URL.');
        return false;
    }

    if (!e.target.consumer_key.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Consumer Key.');
        return false;
    }

    if (!e.target.version.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Version.');
        return false;
    }

    if (!e.target.public_key.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Public Key.');
        return false;
    }

    var $content = $("#content-area-div");
    var url = e.target.action;
    var serializedForm = $(this).serialize();
 
    $.ajax({
        type: 'POST',
        url: url,
        data: serializedForm,
        timeout: 30000,
        beforeSend: function() {
            $content.html('<div id="load">Loading</div>');
        },
        complete: function() {
            $('#load').remove();
        },
        success: function(data) {
            $content.html(data);
        },
        error: function(data) {
            $content.html(data.responseText);
        },
        fail : function() {
            $content.html('<div id="load">Please try again soon.</div>');
        }
    });

});
}) ();

</script>

<?php
// include page footer HTML
//include_once "layout_footer.php";
?>