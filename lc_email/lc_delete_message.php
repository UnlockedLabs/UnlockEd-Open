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

// @TODO add validation
$id = $_GET['id'];
$sender_ids = $_GET['sender_ids'];
$email->id = $id;

if ($sender_ids == $_SESSION['user_id']) {
    $email->sender_folder = 'deleted';
    $email->deleteEmailSender();
} else {
    $email->recipient_folder = 'deleted';
    $email->deleteEmailRecipient();
}
