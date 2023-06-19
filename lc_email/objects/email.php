<?php

namespace unlockedlabs\unlocked;

class Email
{

    // database connection and table name
    private $conn;
    private $table_name = "email";

    // object properties

    public $id;
    public $message_id;
    public $recipient_ids;
    public $recipient_names;
    public $recipient_colors;
    public $sender_ids;
    public $sender_names;
    public $sender_colors;
    public $date_time;
    public $read_unread;
    public $subject;
    public $message;
    public $sender_folder;
    public $recipient_folder;
    public $username;
    public $user_id;
    public $draft_update;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readEmails()
    {

        $query = "SELECT * FROM " . $this->table_name . "
        WHERE recipient_folder=:recipient_folder
        AND recipient_ids=:user_id
        ORDER BY timestamp DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':recipient_folder', $this->recipient_folder);
        $stmt->bindParam(':user_id', $this->user_id);

        // execute the query
        $stmt->execute();

        return $stmt;

    }

    public function readSingleEmail()
    {
        
        $query = "SELECT * FROM " . $this->table_name . " WHERE id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':id', $this->id);

        // execute the query
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row;

    }

    public function markEmailAsRead()
    {
        
        $query = "UPDATE " . $this->table_name . " SET read_unread=:read_unread WHERE id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':read_unread', $this->read_unread);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        $stmt->execute();

        return $stmt;

    }

    public function readDrafts()
    {

        $query = "SELECT * FROM " . $this->table_name . "
        WHERE sender_folder=:sender_folder
        AND sender_ids=:sender_ids
        ORDER BY timestamp DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':sender_folder', $this->sender_folder);
        $stmt->bindParam(':sender_ids', $this->sender_ids);

        // execute the query
        $stmt->execute();

        return $stmt;

    }

    public function countUnreadEmails()
    {

        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "
        WHERE recipient_ids=:recipient_ids
        AND recipient_folder=:recipient_folder
        AND read_unread=:read_unread";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':recipient_folder', $this->recipient_folder);
        $stmt->bindParam(':recipient_ids', $this->recipient_ids);
        $stmt->bindParam(':read_unread', $this->read_unread);

        // execute the query
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row['total_rows'];

    }

    public function readSentEmails()
    {

        $query = "SELECT * FROM " . $this->table_name . "
        WHERE sender_folder=:sender_folder
        AND sender_ids LIKE CONCAT('%', :sender_ids, '%')
        ORDER BY timestamp DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':sender_folder', $this->sender_folder);
        $stmt->bindParam(':sender_ids', $this->sender_ids);

        // execute the query
        $stmt->execute();

        return $stmt;

    }

    public function readTrashEmails()
    {

        $query = "SELECT * FROM " . $this->table_name . "
        WHERE recipient_folder=:recipient_folder
        AND recipient_ids LIKE CONCAT('%', :recipient_ids, '%')
        ORDER BY timestamp DESC";

        //"SELECT * FROM email WHERE recipient_folder='trash' AND recipient_ids LIKE '%$user_id%' ORDER BY timestamp DESC"

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':recipient_folder', $this->recipient_folder);
        $stmt->bindParam(':recipient_ids', $this->recipient_ids);

        // execute the query
        $stmt->execute();

        return $stmt;

    }

    public function sendToTrash()
    {

        /* 

        This should only apply to the recipient's folder.
        No need to set the sender_folder.
        
        */

        $query = "UPDATE " . $this->table_name . "
        SET recipient_folder=:recipient_folder
        WHERE id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':recipient_folder', $this->recipient_folder);
        $stmt->bindParam(':id', $this->id);

        // execute the query 
        $result = $stmt->execute();

        return $result;

    }

    public function sendToInbox()
    {

        $query = "UPDATE " . $this->table_name . "
        SET recipient_folder=:recipient_folder
        WHERE id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':recipient_folder', $this->recipient_folder);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        $result = $stmt->execute();

        return $result;

    }

    public function saveAsDraft()
    {

        // @TODO if its an update let's just adjust the fileds that need to be updated

        if ($this->draft_update) {
            $query = "UPDATE " . $this->table_name . "
                    SET message_id=:message_id, recipient_ids=:recipient_ids,
                    recipient_names=:recipient_names, recipient_colors=:recipient_colors,
                    sender_ids=:sender_ids, sender_names=:sender_names,
                    sender_colors=:sender_colors,
                    read_unread=:read_unread, subject=:subject,
                    message=:message, sender_folder=:sender_folder,
                    recipient_folder=:recipient_folder
                    WHERE message_id=:message_id";
        } else {
            $query = "INSERT INTO " . $this->table_name . "
                    SET message_id=:message_id, recipient_ids=:recipient_ids,
                    recipient_names=:recipient_names, recipient_colors=:recipient_colors,
                    sender_ids=:sender_ids, sender_names=:sender_names,
                    sender_colors=:sender_colors,
                    read_unread=:read_unread, subject=:subject,
                    message=:message, sender_folder=:sender_folder,
                    recipient_folder=:recipient_folder";
        }

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->message=htmlspecialchars(strip_tags($this->message));
        $this->subject=htmlspecialchars(strip_tags($this->subject));

        $stmt->bindParam(":message_id", $this->message_id);
        $stmt->bindParam(":recipient_ids", $this->recipient_ids);
        $stmt->bindParam(":recipient_names", $this->recipient_names);
        $stmt->bindParam(":recipient_colors", $this->recipient_colors);
        $stmt->bindParam(":sender_ids", $this->sender_ids);
        $stmt->bindParam(":sender_names", $this->sender_names);
        $stmt->bindParam(":sender_colors", $this->sender_colors);
        $stmt->bindParam(":read_unread", $this->read_unread);
        $stmt->bindParam(":subject", $this->subject);
        $stmt->bindParam(":message", $this->message);
        $stmt->bindParam(":sender_folder", $this->sender_folder);
        $stmt->bindParam(":recipient_folder", $this->recipient_folder);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    public function sendEmail()
    {

        if ($this->draft_update) {
            $query = "UPDATE " . $this->table_name . "
                    SET message_id=:message_id, recipient_ids=:recipient_ids,
                    recipient_names=:recipient_names, recipient_colors=:recipient_colors,
                    sender_ids=:sender_ids, sender_names=:sender_names,
                    sender_colors=:sender_colors,
                    read_unread=:read_unread, subject=:subject,
                    message=:message, sender_folder=:sender_folder,
                    recipient_folder=:recipient_folder
                    WHERE message_id=:message_id";
        } else {
            $query = "INSERT INTO " . $this->table_name . "
                    SET message_id=:message_id, recipient_ids=:recipient_ids,
                    recipient_names=:recipient_names, recipient_colors=:recipient_colors,
                    sender_ids=:sender_ids, sender_names=:sender_names,
                    sender_colors=:sender_colors,
                    read_unread=:read_unread, subject=:subject,
                    message=:message, sender_folder=:sender_folder,
                    recipient_folder=:recipient_folder";
        }

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->message=htmlspecialchars(strip_tags($this->message));
        $this->subject=htmlspecialchars(strip_tags($this->subject));

        $stmt->bindParam(":message_id", $this->message_id);
        $stmt->bindParam(":recipient_ids", $this->recipient_ids);
        $stmt->bindParam(":recipient_names", $this->recipient_names);
        $stmt->bindParam(":recipient_colors", $this->recipient_colors);
        $stmt->bindParam(":sender_ids", $this->sender_ids);
        $stmt->bindParam(":sender_names", $this->sender_names);
        $stmt->bindParam(":sender_colors", $this->sender_colors);
        $stmt->bindParam(":read_unread", $this->read_unread);
        $stmt->bindParam(":subject", $this->subject);
        $stmt->bindParam(":message", $this->message);
        $stmt->bindParam(":sender_folder", $this->sender_folder);
        $stmt->bindParam(":recipient_folder", $this->recipient_folder);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }


    public function markReadUnread()
    {

        $query = "UPDATE " . $this->table_name . " SET read_unread=:read_unread WHERE id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':read_unread', $this->read_unread);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        $result = $stmt->execute();

        return $result;

    }
    
    public function getRecipientsIdUsername()
    {

        /* 
        @TODO should we move this method to the users object?
        */

        $query = "SELECT * FROM users WHERE id != :user_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':user_id', $this->user_id);

        // execute the query
        $stmt->execute();

        return $stmt;

    }

    public function deleteEmailSender()
    {

        $query = "UPDATE " . $this->table_name . "
        SET sender_folder=:sender_folder
        WHERE id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':sender_folder', $this->sender_folder);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        $result = $stmt->execute();

        return $result;

    }

    public function deleteEmailRecipient()
    {

        $query = "UPDATE " . $this->table_name . "
        SET recipient_folder=:recipient_folder
        WHERE id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind params
        $stmt->bindParam(':recipient_folder', $this->recipient_folder);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        $result = $stmt->execute();

        return $result;

    }


}
?>