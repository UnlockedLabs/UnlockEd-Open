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
$email->id = $_GET['id'];
$email->user_id = $_SESSION['user_id']; //are we using this?


require_once 'lc_layout_header.php';



$user_id = $_SESSION['user_id'];


// marks email as read (inbox_message_id is isset when it comes in from the INBOX tab)
if (isset($_GET['inbox_message_id'])) {
    $messageId = $_GET['inbox_message_id'];
    $email->message_id = $_GET['inbox_message_id'];
    $email->read_unread = 1;
    $email->markEmailAsRead();
}
    

// message_id isset when it comes in from the other folders except INBOX
if (isset($_GET['message_id'])) {
        $messageId = $_GET['message_id'];
        $email->message_id = $messageId;
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


            <?php

            //GETS ALL THE MESSAGE INFO
            $msgRow = $email->readSingleEmail();

            //$message = $msgRow['message'];
            
            // REFORMATS THE RECIPIENT LIST IF MULTIPLE RECIPIENTS
            if (strpbrk($msgRow['recipient_names'], ',')) {
                //TODO find out what this does
                $msgRow['recipient_names'] = explode(',', $msgRow['recipient_names']);
                $msgRow['recipient_names'] = implode(', ', $msgRow['recipient_names']);
            }
            
            // SENDER'S FIRST INITIAL FOR BADGE
            $firstInitial = strtoupper($msgRow['sender_names'][0]);

            ?>


                <!-- Single mail -->
                <div class="card">

                    <!-- Action toolbar -->
                    <div class="bg-light rounded-top">
                        <div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2 rounded-top">
                            <div class="text-center d-lg-none w-100">
                                <button type="button" class="navbar-toggler w-100 h-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-read">
                                    <i class="icon-circle-down2"></i>
                                </button>
                            </div>

                            <div class="navbar-collapse text-center text-lg-left flex-wrap collapse" id="inbox-toolbar-toggle-read">
                                <div class="mt-3 mt-lg-0 mr-lg-3">
                                    <!-- REPLY/FORWARD/TRASH BUTTON GROUP -->
                                    <form method="get" action="lc_compose.php">
                                        <div class="btn-group">
                                            <input type="hidden" name="message_id" value="<?php echo $messageId ?>">
                                            <input type="hidden" name="timestamp" value="<?php echo $msgRow['timestamp']; ?>">
                                            <input type="hidden" name="subject" value="<?php echo $msgRow['subject']; ?>">
                                            <input type="hidden" name="message" value="<?php echo $msgRow['message']; ?>">
                                            <input type="hidden" name="fromUser" value="<?php echo $msgRow['sender_names']; ?>">
                                            <input type="hidden" name="sender_ids" value="<?php echo $msgRow['sender_ids']; ?>">

                                            <?php
                                            if ($msgRow['sender_ids'] != $_SESSION['user_id'] && $msgRow['recipient_folder'] != 'trash') {
                                                echo '<a href="lc_inbox.php" class="btn btn-light" onclick="ajaxTrash(\'' . $msgRow['id'] . '\')">';
                                                echo '<i class="icon-bin"></i>';
                                                echo '<span class="d-none d-lg-inline-block ml-2">Send to trash</span>';
                                                echo '</a>';
                                            }
                                            ?>

                                        </div>
                                    </form>
                                    <!-- / BUTTON GROUP -->
                                </div>

                                <div class="navbar-text ml-lg-auto"><?php echo $msgRow['timestamp']; ?></div>

                                
                            </div>
                        </div>
                    </div>
                    <!-- /action toolbar -->


                    <!-- Mail details -->
                    <div class="card-body">
                        <div class="media flex-column flex-md-row">
                            <a href="#" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
                                <span class="btn bg-<?php echo $msgRow['sender_colors']; ?> btn-icon btn-lg rounded-round">
                                    <span class="letter-icon"><?php echo $firstInitial; ?></span>
                                </span>
                            </a>

                            <div class="media-body">
                                <h6 class="mb-0"> Subject: <?php echo $msgRow['subject']; ?></h6>
                                <div class="letter-icon-title font-weight-semibold">From:  <?php echo $msgRow['sender_names']; ?></div>
                                <div class="letter-icon-title font-weight-semibold">To:  <?php echo $msgRow['recipient_names']; ?></div>
                            </div>
                        </div>
                    </div>
                    <!-- /mail details -->


                    <!-- Mail container -->
                    <div class="card-body">
                        <div class="overflow-auto mw-100">
                            <?php echo $msgRow['message']; ?>
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
