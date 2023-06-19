<?php

namespace unlockedlabs\unlocked;

require_once '../session-validation.php'; 
require_once 'lc_ensure_email.php';

// include database and object files
require_once '../config/core.php';
require_once '../config/database.php';
require_once 'objects/email.php';

// instantiate database and email object
$database = new Database();
$db = $database->getConnection();

$email = new Email($db);
$email->sender_ids = $_SESSION['user_id'];
$email->user_id = $_SESSION['user_id'];

require_once 'lc_layout_header.php';

$user_id = $_SESSION['user_id'];
$user = $_SESSION['username'];

$subject = "Add Subject";
$message = "My Message";
$messageId = "0000";
$recipient_id = "";
$from = "";

// COMING FROM DRAFTS
if (isset($_GET['edit'])) {
    $recipient_id = $_GET['recipient_id'];
    $messageId = $_GET['message_id'];
    $subject = $_GET['subject'];
    $message = $_GET['message'];
    $dateTime = date("Y-m-d H:i:s");

    $email->recipient_id = $recipient_id;

    $recipientColor = 'teal-400';
    $contactName = $_SESSION['username'];
}
if (isset($_GET['recipient_id'])) {
    $recipient_id = $_GET['recipient_id'];
}

?>
    <!-- Inner container -->
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Actions -->
                <div class="card">

                    <div class="card-body">
                        <a href="lc_compose.php" class="btn bg-indigo-400 btn-block">Compose message</a>
                    </div>
                </div>
                <!-- /actions -->


                <!-- Sub navigation -->
                <div class="card">

                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2" data-nav-type="accordion">
                            <li class="nav-item-header">Folders</li>
                            <li class="nav-item">
                                <a href="lc_inbox.php" class="nav-link">
                                    <i class="icon-drawer-in"></i>
                                    Inbox
                                    <div class="ml-auto num_new"></div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="lc_drafts.php" class="nav-link"><i class="icon-drawer3"></i> Drafts</a>
                            </li>
                            <li class="nav-item">
                                <a href="lc_sent.php" class="nav-link"><i class="icon-drawer-out"></i> Sent messages</a>
                            </li>
                            <li class="nav-item">
                                <a href="lc_trash.php" class="nav-link"><i class="icon-bin"></i> Trash</a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                <!-- /sub navigation -->


            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->


        <!-- Right content -->
        <div class="flex-fill overflow-auto">

                <!-- Single mail -->
                <div class="card">

                    <!-- Action toolbar -->
                    
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" id="user_name" name="user_name" value="<?php echo $user; ?>">
                        <div class="bg-light rounded-top">
                            <div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2 rounded-top">
                                <div class="text-center d-lg-none w-100">
                                    <button type="button" class="navbar-toggler w-100 h-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-write">
                                        <i class="icon-circle-down2"></i>
                                    </button>
                                </div>

                                <div class="navbar-collapse text-center text-lg-left flex-wrap collapse" id="inbox-toolbar-toggle-write">

                                    <div class="mt-3 mt-lg-0 mr-lg-3">
                                        <a href="lc_inbox.php" id="send_message" class="btn bg-blue" onclick="return sendEmail('<?php echo $messageId ?>');"><i class="icon-paperplane mr-2"></i> Send</a>
                                    </div>


                                    <div class="mt-3 mt-lg-0 mr-lg-3">
                                        <div class="btn-group">
                                            <a href="lc_drafts.php" class="btn btn-light" onclick="return saveEmail('<?php echo $messageId; ?>');">
                                                <i class="icon-checkmark3"></i>
                                                <span class="d-none d-lg-inline-block ml-2">Save</span>
                                            </a>
                                            <a type="button" href="lc_inbox.php" class="btn btn-light">
                                                <i class="icon-cross2"></i>
                                                <span class="d-none d-lg-inline-block ml-2">Cancel</span>
                                            </a>

                                        </div>
                                    </div>

                                    <div class="navbar-text ml-lg-auto"><?php date_default_timezone_set("America/Chicago");
                                    echo date("h:i a"); ?></div>

                                </div>
                            </div>
                        </div>
                        <!-- /action toolbar -->


                        <!-- Mail details -->
                        
                        <div class="table-responsive overflow-hidden">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="align-top py-0" style="width: 1%">
                                            <div class="py-2 mr-sm-3">To:</div>
                                        </td>
                                        <td class="align-top py-0">

                                            <div class="flex-sm-wrap">     
                                                <div class="py-0 mr-sm-3">                                                                                                                      
                                                    <select data-placeholder="Add a recipient" id="recipient" name="recipient_id" multiple="multiple" class="form-control select flex-fill py-2 border-0 rounded-0 required" data-fouc>
                                                    <?php
                                                        
                                                        $contactsResults = $email->getRecipientsIdUsername();
                                                        $numContacts = $contactsResults->rowCount();
                                                        
                                                    for ($r = 0; $r < $numContacts; $r++) {
                                                        $contactsRow = $contactsResults->fetch(\PDO::FETCH_ASSOC);
                                                        $contactName = $contactsRow['username'];
                                                        $contactId = $contactsRow['id'];

                                                        if (strpos($recipient_id, $contactId) !== false) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = "";
                                                        }
                                                                                                                                    
                                                        echo "<option value='$contactId' $selected>$contactName</option>";
                                                    }
                                                    ?>
                                                    </select>
                                                    </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-top py-0">
                                            <div class="py-2 mr-sm-3">Subject:</div>
                                        </td>
                                        <td class="align-top py-0">
                                            <input type="text" id="subject" value="<?php echo $subject; ?>" name="subject" class="form-control py-2 px-0 border-0 rounded-0">
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- /mail details -->
                        <!-- Mail container -->
                        <div class="card-body p-0">
                            <div class="overflow-auto mw-100">
                                <div class="mb-3">
                                    <textarea name="message" id="editor-full" rows="4" cols="4">
                                        <?php echo $message; ?>
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /mail container -->
                </div>
                <!-- /single mail -->
        </div>
        <!-- /right content -->
    </div>
    <!-- /inner container -->
<?php require_once 'lc_layout_footer.php'; ?>
<script src="./styles/instantiate_ckeditor.js"></script>
