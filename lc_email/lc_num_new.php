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
$email->recipient_folder = 'inbox';
$email->recipient_ids = $_SESSION['user_id'];
$email->read_unread = 0;
echo $email->countUnreadEmails();
