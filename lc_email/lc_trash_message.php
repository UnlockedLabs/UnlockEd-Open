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
// @TODO add vaidation
$email->id = $_GET['id'];

// @TODO find a better way to do this
$email->recipient_folder = 'trash';

//return 1 or 0
echo $email->sendToTrash();
