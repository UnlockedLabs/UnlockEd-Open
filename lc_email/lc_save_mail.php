<?php

namespace unlockedlabs\unlocked;

require_once '../session-validation.php'; 
require_once 'lc_ensure_email.php';

// include database and object files
require_once '../config/core.php';
require_once '../config/database.php';
require_once "../objects/GUID.php";
require_once 'objects/email.php';

// instantiate database and email object
$database = new Database();
$db = $database->getConnection();

$email = new Email($db);

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
$email->sender_folder = 'drafts';
$email->recipient_folder = 'pending';

if ($messageId != '0000') {
    $email->draft_update = 1;
} else {
    $guid = new GUID();
    $email->message_id = trim($guid->uuid());
    $email->draft_update = 0;
}
$email->saveAsDraft();
