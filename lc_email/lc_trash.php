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
$email->recipient_folder = 'trash';
$email->username = $_SESSION['username'];
$email->user_id = $_SESSION['user_id'];
$email->recipient_ids = $_SESSION['user_id'];

$result = $email->readTrashEmails();
$numMessages = $result->rowCount();

require_once 'lc_layout_header.php';
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
                                <a href="lc_trash.php" class="nav-link active"><i class="icon-bin"></i> Trash</a>
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

            <!-- Single line -->
            <div class="card">

                <!-- Action toolbar -->
                <div class="bg-light">
                    <div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2">
                        <div class="text-center d-lg-none w-100">
                            <button type="button" class="navbar-toggler w-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-single">
                                <i class="icon-circle-down2"></i>
                            </button>
                        </div>

                        <div class="navbar-collapse text-center flex-wrap collapse" id="inbox-toolbar-toggle-single">
                            <div class="mt-3 mt-lg-0">
                                <div class="btn-group">
                                    <button class="btn btn-light font-size-sm" onclick="selectAll();">Select all</button>
                                    <button class="btn btn-light font-size-sm" onclick="selectRead();">Select read</button>
                                    <button class="btn btn-light font-size-sm" onclick="selectUnread();">Select unread</button>
                                    <button class="btn btn-light font-size-sm" onclick="clearAll();">Unselect all</button>
                                </div>
                                
                                <div class="btn-group ml-3 mr-lg-3">
                                    <button type="button" class="btn btn-light font-size-sm" onclick="deleteMessage();"><i class="icon-cross3"></i> <span class="d-none d-lg-inline-block ml-2">Delete forever</span></button>
                                    <button type="button" class="btn btn-light font-size-sm" onclick="sendToInbox();"><i class="icon-envelop4"></i> <span class="d-none d-lg-inline-block ml-2">Send to inbox</span></button>
                                </div>
                            </div>
                            <div class="navbar-text ml-lg-auto"><span class="font-weight-semibold">1- <?php echo $numMessages; ?></span></div>
                        </div>
                    </div>
                </div>
                <!-- /action toolbar -->


                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-inbox">
                        <tbody data-link="row" class="rowlink">
                        

                            <?php

                            for ($m = 0; $m < $numMessages; $m++) {
                                $row = $result->fetch(\PDO::FETCH_ASSOC);
                                $id = $row['id'];
                                $messageId = $row['message_id'];
                                $userToId = $row['recipient_ids'];
                                ;
                                $userToName = $row['recipient_names'];
                                $userFromId = $row['sender_ids'];
                                $userFromName = $row['sender_names'];
                                $userFromColor = $row['sender_colors'];
                                $dateTime = $row['timestamp'];
                                $read = $row['read_unread'];
                                $subject = $row['subject'];
                                $message = $row['message'];
                                
                                // Set Checkbox ID for selection
                                $checkbox = "checkbox" . $id;

                                // Set Class Name for read/unread message
                                if ($read == "0") {
                                    $read = "unread";
                                } elseif ($read == "1") {
                                    $read = "read";
                                }

                                // Set Time Display
                                $dateTime = strtotime($dateTime);
                                $time = date("h:i a", $dateTime);
                                $date = date("Y-m-d", $dateTime);
                                if ($date < date("Y-m-d")) {
                                    $sentAt = date("M d", $dateTime);
                                } elseif ($date == date("Y-m-d")) {
                                    $sentAt = $time;
                                }


                                echo <<<_MESSAGE

                                <tr class="$read" id="$id" value="$userFromId">
                                    <td class="table-inbox-checkbox rowlink-skip">
                                        <input type="checkbox" class="form-input-styled" data-fouc>
                                    </td>
                                    <td class="table-inbox-checkbox rowlink-skip">
                                        <i id="$checkbox" class="icon-checkbox-unchecked text-muted" onclick="selectMessage('$id')"></i>
                                    </td>
                                    <td class="table-inbox-image">
                                        <span class="btn bg-$userFromColor rounded-circle btn-icon btn-sm">
                                            <span class="letter-icon"></span>
                                        </span>
                                    </td>
                                    <td class="table-inbox-name font-size-sm">
                                        <a href="#">
                                            <div class="letter-icon-title text-default">$userFromName $id</div>
                                        </a>
                                    </td>
                                    <form method="get" action="lc_read.php">
                                    <td class="table-inbox-message font-size-sm">
                                        <button type="submit" class="btn btn-link text-default media">
                                            <input type="hidden" name="id" value="$id">
                                            <input type="hidden" name="message_id" value="$messageId">
                                            <span class="table-inbox-subject">$subject</span>
                                        </button>
                                    </td>
                                    </form>
                                    
                                    <td class="table-inbox-time font-size-sm">
                                        $sentAt
                                    </td>

                                </tr>


_MESSAGE;
                            }



                            ?>

                        </tbody>
                    </table>
                </div>
                <!-- /table -->

            </div>
            <!-- /single line -->

        </div>
        <!-- /right content -->

    </div>
    <!-- /inner container -->
<?php require_once 'lc_layout_footer.php'; ?>
