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
    $provider_url = $_POST['provider_url'];
    $consumer_key = $_POST['consumer_key'];
    $version = $_POST['version'];
    $secret = $_POST['secret'];
    
    if ((empty($name))) {
        echo "<div class='alert alert-danger'>Name cannot be empty.</div>";
    } elseif ((empty($client_id))) {
        echo "<div class='alert alert-danger'>Provider ID cannot be empty.</div>";
    } elseif ((empty($provider_url))) {
        echo "<div class='alert alert-danger'>Provider URL cannot be empty.</div>";
    } elseif ((empty($consumer_key))) {
        echo "<div class='alert alert-danger'>Consumer (Platform) Key cannot be empty.</div>";
    } elseif ((empty($version))) {
        echo "<div class='alert alert-danger'>LTI Version cannot be empty.</div>";
    } elseif ((empty($secret))) {
        echo "<div class='alert alert-danger'>Secret cannot be empty.</div>";
    } else {
        $tool->name = $name;
        $tool->client_id = $client_id;
        $tool->provider_url = $provider_url;
        $tool->consumer_key = $consumer_key;
        $tool->version = $version;
        $tool->secret = $secret;

        //ensure the tool isn't already registered
        // if ($tool->toolExists()) {
        //     //$tool->topic_name=$topic_name;
        //     echo "<div class=\"alert alert-danger alert-dismissable\">";
        //     echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
        //     echo "Tool is already registered.";
        //     echo "</div>";
        if ($tool->register()) {
            // tell the user tool was registered
            echo "<div class=\"alert alert-success alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Tool was registered. <a href='./index.php'>Reload page now.</a>";
            echo "</div>";
        } else {
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Unable to create topic.";
            echo "</div>";
        }
    }
}
?>

<div class="card container">
    <div class="card-body">
        <h3 class="card-title text-center"><?php echo $category_name; ?></h3>
        <h3 class="card-title">Register an LTI Tool</h3>
        <form id='register-tool-form' action='register_tool.php?id=<?php echo $id; ?>&category_name=<?php echo $category_name; ?>' method='post'>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name='name' class="form-control" id="name" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="clientId">Provider ID</label>
                <input type="text" name='client_id' class="form-control" id="clientId" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="providerUrl">Provider (Tool) URL</label>
                <input type="text" name='provider_url' class="form-control" id="providerUrl" placeholder="" required>
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
            <div class="form-group">
                <label for="secret">Secret</label>
                <input type="text" name='secret' class="form-control" id="secret" placeholder="" required>
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

    if (!e.target.client_id.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Provider ID.');
        return false;
    }

    if (!e.target.client_id.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Name.');
        return false;
    }

    if (!e.target.secret.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Secret.');
        return false;
    }

    if (!e.target.provider_url.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Provider URL.');
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