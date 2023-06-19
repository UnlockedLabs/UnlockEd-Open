<?php

namespace unlockedlabs\unlocked;

require_once '../session-validation.php'; 
require_once 'lc_ensure_email.php';

// include database and object files
require_once '../config/core.php';
require_once '../config/database.php';
require_once 'objects/email.php';
require_once '../objects/GUID.php';

// instantiate database and email object
$database = new Database();
$db = $database->getConnection();

$email = new Email($db);

$user_id = $_SESSION['user_id'];

// SENDER INFO
$senderId = $_GET['userId'];
$senderName = $_GET['userName'];

// MESSAGE CONTENT
$messageId = $_GET['messageId'];
$subject = $_GET['subject'];
$message = $_GET['message'];
$dateTime = date("Y-m-d H:i:s");

//RECIPIENT INFO
$recipients = $_GET['recipientNameString'];
$recipient_ids = $_GET['recipientIdsString'];
$recipient_colors = 'primary';

$email->message_id = $messageId;
$email->recipient_ids = $recipient_ids;
$email->recipient_names = $recipients;
$email->recipient_colors = $recipient_colors;
$email->user_id = $senderId;
$email->sender_ids = $senderId;
$email->sender_names = $senderName;
$email->sender_colors = 'success';
$email->read_unread = 0;
$email->subject = $subject;
$email->message = $message;
//$email->sender_folder = 'sent';
//$email->recipient_folder = 'inbox';

//an email with a single recipient will share the row with the sender

//this is the sender's draft, send it to their sent box
if ($messageId != '0000') {
    $email->draft_update = 1;
    $email->sender_folder = 'sent';
    $email->recipient_folder = 'inbox';
    $email->sendEmail();
} else {
    $guid = new GUID();
    $email->message_id = trim($guid->uuid());
    $email->draft_update = 0;
    $email->sender_folder = 'sent';

    if (strpos($email->recipient_ids, ',')) {
        /*
            Multiple recipients in this context.
            Their records will get created below.
            Set recipient_folder to n/a to indicate
            this is the sender's original. Recipient
            folder will be set to inbox below.
         */

        $email->recipient_folder = 'n/a';
    } else {
        $email->recipient_folder = 'inbox';
    }

    $email->sendEmail();
}

/*
    For each email recipient we add one row.
    Multiple recipients will have commas in the recipient_id variable.

    @TODO there is a more efficient way of doing this, but the
    number of recipients at this current time should be relatively
    small so the hit should not be too big.
    See http://localhost:8081/stackoverflow/779986 for more info.

*/

//multiple recipents will be processed here, create new records
if (strpos($email->recipient_ids, ',')) {
    $email->draft_update = 0;

    //@TODO add exception handling.
    $recipient_array = explode(",", $email->recipient_ids);
    
    foreach ($recipient_array as $recipient_id) {
        $email->recipient_ids = $recipient_id;
        $email->sender_folder = 'n/a';
        $email->recipient_folder = 'inbox';
        $email->sendEmail();
    }
}
