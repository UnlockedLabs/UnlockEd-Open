<?php
namespace unlockedlabs\unlocked;
class CategoryAccessed{

    // database connection and table name
    private $conn;
    private $table_name = "category_accessed";

    // object properties
    public $id;
    public $username;
    public $category_clicked;
    public $time_in;
    public $time_out;
    public $forced_logout;
    public $access_id;
    public $admin_id;
    public $timestamp;
    public $last_row;
    
    public function __construct($db){
        $this->conn = $db;
    }


    // create timeIn analytic
    public function timeIn(){

        // to get time-stamp for the time_in/out fields
        $this->getTimestamp();

        //write query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    username=:username,
                    category_clicked=:category_clicked,
                    time_in=:time_in,
                    access_id=:access_id,
                    admin_id=:admin_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":category_clicked", $this->category_clicked);
        $stmt->bindParam(":time_in", $this->timestamp);
        $stmt->bindParam(":access_id", $this->access_id);
        $stmt->bindParam(":admin_id", $this->admin_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            $_SESSION['last_row'] = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }


        // create timeIn analytic
        public function timeOut(){

            // to get time-stamp for the time_in/out fields
            $this->getTimestamp();
    
            //write query
            $query = "UPDATE " . $this->table_name . "
                    SET
                    time_out=:time_out
                    WHERE id=:last_row;";
    
            $stmt = $this->conn->prepare($query);
    
            $stmt->bindParam(":time_out", $this->timestamp);
            $stmt->bindParam(":last_row", $this->last_row);
    
            if ($stmt->execute() && $stmt->rowCount()) {
                return true;
            }
    
            return false;
        }

    public function getTimestamp(){
        date_default_timezone_set('America/Chicago');
        $this->timestamp = date('Y-m-d H:i:s');
    }
}
?>
