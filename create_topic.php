<?php

/**
 * Create Topic
 *
 * Process create topic form and insert topic into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Insert/create topic.
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

require_once 'session-validation.php';
//ensure admin user (admin is 2 and above)
if (($_SESSION['admin_num'] < 2)) {
    die('<h1>Restricted Action!</h1>');
}

// include database and object files
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/topic.php';
require_once dirname(__FILE__).'/objects/users.php';
require_once dirname(__FILE__).'/objects/category.php';

// include LTI library and utility files
require_once dirname(__FILE__).'/LTI/misc.php';
require_once dirname(__FILE__).'/LTI/ims-blti/blti_util.php';

// dummy data for LTI Launch
$lmsdata = array(
    "resource_link_id" => "120988f929-274612",
    "resource_link_title" => "Weekly Blog",
    "resource_link_description" => "A weekly blog.",
    "user_id" => "292832126",
    "roles" => "Instructor",  // or Learner
    "lis_person_name_full" => 'Jane Q. Public',
    "lis_person_name_family" => 'Public',
    "lis_person_name_given" => 'Given',
    "lis_person_contact_email_primary" => "user@school.edu",
    "lis_person_sourcedid" => "school.edu:user",
    "context_id" => "456434513",
    "context_title" => "Design of Personal Environments",
    "context_label" => "SI182",
    "tool_consumer_info_product_family_code" => "ims",
    "tool_consumer_info_version" => "1.1",
    "tool_consumer_instance_guid" => "lmsng.school.edu",
    "tool_consumer_instance_description" => "University of School (LMSng)",
);

// for the POST back to replace dummy values with POST values
foreach ($lmsdata as $k => $val ) {
    if (isset($_POST[$k])) {
      if ( $_POST[$k] && strlen($_POST[$k]) > 0 ) {
          $lmsdata[$k] = $_POST[$k];
      }
    }
}

$cur_url = curPageURL();

// A consumer key can be any unique string; it could, for example be the domain
// name of the Tool Consumer system, or simply a GUID
$ltikey = @$_REQUEST["ltikey"];
// $ltikey ? $ltikey : $ltikey = "12345";
$ltikey = ($ltikey ? $ltikey : "12345");

// A shared secret is used to secure the messages sent between the systems, it should,
// therefore, be strong - a random string of at least 15 characters, some systems use
// a GUID for the secret
$secret = @$_REQUEST["secret"];
$secret = ($secret ? $secret : "secret");

$endpoint = @$_REQUEST["endpoint"];

$b64 = base64_encode($ltikey.":::".$secret);

$endpoint = ($endpoint ? $endpoint : str_replace("create_topic.php","LTI/tool.php",$cur_url));

$outcomes = @$_REQUEST["outcomes"];
$outcomes = ($outcomes ? $outcomes : str_replace("create_topic.php","tool_consumer_outcome.php",$cur_url) . "?b64=" . htmlentities($b64));

$tool_consumer_instance_guid = $lmsdata['tool_consumer_instance_guid'];
$tool_consumer_instance_description = $lmsdata['tool_consumer_instance_description'];

$xmldesc = @$_REQUEST["xmldesc"];
$xmldesc = ($xmldesc ? str_replace("\\\"","\"",$_REQUEST["xmldesc"]) : $default_desc);

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//$product = new Product($db);
$topic = new Topic($db);
$users = new User($db);

// get ID of the topic to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
$category_name = isset($_GET['category_name']) ? $_GET['category_name'] : die('ERROR: missing Category Name.');
$topic->id = $id;
$topic->category_id = $id;

// if the form was submitted
if ($_POST) {
    // set topic property values
    $topic_name = $_POST['topic_name'];
    $topic_url = $_POST['topic_url'];
    
    if ((empty($topic_name))) {
        echo "<div class='alert alert-danger'>Name cannot be empty.</div>";
    } else {
        $topic->topic_name = $topic_name;
        $topic->topic_url = $topic_url;

        //ensure the topic does not exists
        if ($topic->topicExists()) {
            //$topic->topic_name=$topic_name;
            echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Topic already exists.";
            echo "</div>";
        } elseif ($topic->create()) {
            // tell the user new topic was created
            echo "<div class=\"alert alert-success alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            echo "Topic was created. Please reload the page to see the updated Topic tags. <a href='./index.php'>Reload page now.</a>";
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
        <h3 class="card-title">Create New Topic</h3>
        <form id='create-topic-form' action='create_topic.php?id=<?php echo $id; ?>&category_name=<?php echo $category_name; ?>' method='post'>
            <div class="form-group">
                <label for="topicName">Topic Name</label>
                <input type="text" name='topic_name' class="form-control" id="topicName" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="topic_url">External Website's URL</label>
                <input type="text" name='topic_url' class="form-control" id="topic_url" placeholder="Only set this if you are linking to an external site.">
            </div>
            <button type="submit" class="btn btn-primary">Create Topic</button>
        </form>
    </div>
</div>

<div class="card container">
    <div class="card-body">
        <h3 class="card-title text-center"><?php echo $category_name; ?></h3>
        <h3 class="card-title">Create New LTI 1.1 Consumer Launch</h3>
        <h4>This is a very simple reference implementation of the LMS side (i.e. consumer) for IMS LTI 1.1.</h4>
        <a id="displayText" href="">Toggle Resource and Launch Data</a>
        <!-- <a id="displayText" href="javascript:lmsdataToggle();">Toggle Resource and Launch Data</a> -->
    <div id="lmsDataForm" style="display:block">
        <form method="post">
            <input type="submit" value="Recompute Launch Data">
            <input type="reset" name="reset" value="Reset">
            <fieldset>
                <legend>Basic LTI Resource</legend>
                <div class="form-group">
                    <label for="launchUrl">Launch URL</label>
                    <input type="text" name='endpoint' size="60" id="launchUrl" value=<?php echo $endpoint ?>><br>
                    <label for="ltiKey">Key</label>
                    <input type="text" name='ltikey' size="60" id="ltiKey" value=<?php echo $ltikey ?>><br>
                    <label for="secret">Secret</label>
                    <input type="text" name='secret' size="60" id="secret" value=<?php echo $secret ?>>
                </div>
            </fieldset>
<?php
echo("<fieldset><legend>Launch Data</legend>\n");
foreach ($lmsdata as $k => $val ) {
    echo($k.": <input type=\"text\" name=\"".$k."\" value=\"");
    echo(htmlspecialchars($val));
    echo("\"><br/>\n");
}
echo("</fieldset>\n");
echo("</form>\n");
echo("</div>\n");
echo("<hr>");

$parms = $lmsdata;
// Cleanup parms before we sign
foreach( $parms as $k => $val ) {
    if (strlen(trim($parms[$k]) ) < 1 ) {
        unset($parms[$k]);
    }
}

// Add oauth_callback to be compliant with the 1.0A spec
$parms["oauth_callback"] = "about:blank";
if ( $outcomes ) {
    $parms["lis_outcome_service_url"] = $outcomes;
    $parms["lis_result_sourcedid"] = "feb-123-456-2929::28883";
}

$parms = signParameters($parms, $endpoint, "POST", $ltikey, $secret, "Press to Launch", $tool_consumer_instance_guid, $tool_consumer_instance_description);

$content = postLaunchHTML($parms, $endpoint, true, "width=\"100%\" height=\"900\" scrolling=\"auto\" frameborder=\"1\" transparency");
print($content);
?>
        </div>
    </div>
</div>


<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

$('#create-topic-form').on('submit', function(e) {

    //prevent form submission
    e.preventDefault();

    if (!e.target.topic_name.value.trim()) {
        ul.errorSwalAlert("Info Warning!", 'Must Supply Topic Name.');
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

(function(){

$('#displayText').on('click', function(e) {

    e.preventDefault();
    console.log("I was clicked!");

    var $content = $("#lmsDataForm");
    if ($content.css("display") == "block") {
        $content.css("display", "none");
    }
    else {
        $content.css("display", "block");
    }
});

}) ();
</script>

<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

// (function(){

//     $('#displayText').on('click', function(e) {

//         e.preventDefault();
//         console.log("I was clicked!");

//         var $content = $("#lmsDataForm");
//         if ($content.css("display") == "block") {
//             $content.css("display", "none");
//         }
//         else {
//             $content.css("display", "block");
//         }
//     });

// }) ();
</script>

<!-- just a simple toggle for the LMS Resource and Launch Data -->
<!-- <script language="javascript"> 
  //<![CDATA[ 
function lmsdataToggle() {
    var ele = document.getElementById("lmsDataForm");
    if(ele.style.display == "block") {
        ele.style.display = "none";
    }
    else {
        ele.style.display = "block";
    }
} 
  //]]> 
</script> -->

<?php
// include page footer HTML
//include_once "layout_footer.php";
?>