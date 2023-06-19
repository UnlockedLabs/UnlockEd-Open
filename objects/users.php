<?php

/**
 * The User object is defined in this file
 *
 * This User class defined in this file represents all user
 * objects throughout the application.
 *
 * PHP version 7.2.5
 *
 * @category  Objects
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

 namespace unlockedlabs\unlocked;

 require_once 'GUID.php';

class User
{

    private $conn;
    private $table_name = "users";

    // object properties
    public $id;               ///<  guid 
    public $created;          ///<  don't think this is being used here 
    public $username;         ///<  the login name  
    public $password;         ///<  the login password 
    public $password_hashed;  ///<  convoluted password 
    public $repeat_password;  ///<  reentered password for verification of new user creation 
    public $email;            ///<  the user's email
    public $oid;              ///<  Unique Identifier accross and Organization/Prison System [Inmate ID#]
    public $access_id;        ///<  access level id - relates to access_levels table
    public $admin_id;         ///<  admin level id - relates to admin_levels table

    /**
     * Constructor
     *
     * @param object $db -  database connection to users table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * insert user and convolute password
     *
     * @return boolean denoting whether insert was successful
     */
    public function create()
    {
         $query = "INSERT INTO users SET
                id = :id,
                username = :username,
                password = :password,
                email = :email,
                oid = :oid,
                access_id = :access_id,
                admin_id = :admin_id";
                

        $stmt = $this->conn->prepare($query);
        $guid = new GUID();
        // sanitize
        $this->id = $guid->uuid();
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->oid = htmlspecialchars(strip_tags($this->oid));
        $this->access_id = htmlspecialchars(strip_tags($this->access_id));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));

        
        /*
            * hash passwords

            * password_hash will generate its own salt, PASSWORD_DEFAULT asks php to select the most secure hashing function

            * use password_verify to compare hashes
            * boolean password_verify ( string $password , string $hash )
            * Note that password_hash() returns the algorithm, cost and salt as part of the returned hash.
            Therefore, all information that's needed to verify the hash is included in it.
            This allows the verify function to verify the hash without needing separate storage for the salt or algorithm information.
        */

        $this->password_hashed = password_hash($this->password, PASSWORD_DEFAULT);

        // bind values
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password_hashed);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':oid', $this->oid);
        //$this->access_id = 1;
        //$this->admin_id = 1;
        $stmt->bindParam(':access_id', $this->access_id);
        $stmt->bindParam(':admin_id', $this->admin_id);

        // execute query
        try {
            //code...
            if ($stmt->execute() && $stmt->rowCount()) {
                return true;
            }
        } catch (\Throwable $th) {
            //throw $th;
            print_r($th);
        }
        return false;
    }
    
    /** 
     * Read all users' data 
     * 
     * @return PDOStatement
    */
    public function readAll()
    {

        /* 
        
        $query = "SELECT DISTINCT
                    users.id, users.username, users.admin_id
                FROM
                    " . $this->table_name;
        */
        
        $query = "SELECT * FROM " . $this->table_name .
        " ORDER BY username ASC";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
    
        return $stmt;
    }


    /** 
     * Read users data by id
     * 
     * @return array of user data
    */
    public function readOne()
    {

        $query = "SELECT * FROM " . $this->table_name . " WHERE id=:id LIMIT 0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    /**
     * update user
     * 
     * @return boolean denoting whether update was successful
     */
    public function update()
    {
        // update the user
        $query = "UPDATE " . $this->table_name . "
                SET username = :username, email = :email, oid = :oid
                WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':oid', $this->oid);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * update user admin level if param > current admin id
     * 
     * @param int $newId
     * 
     * @return boolean denoting whether update was successful
     */
    public function updateAdminId($newId)
    {
        $query = "SELECT
                    admin_id
                FROM
                    " . $this->table_name . "
                WHERE
                    id=:id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->admin_id = ($newId > $row['admin_id']) ? $newId : $row['admin_id'];

        $query = "UPDATE
                    " . $this->table_name . "
                SET admin_id=:admin_id
                WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':admin_id', $this->admin_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * delete user
     * 
     * @return boolean denoting whether delete was successful
     */
    public function delete()
    {
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind record id
        $stmt->bindParam(1, $this->id);

        // execute the query
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Validate that user entered proper user id and password combination
     *
     * @return string
     */
    public function checkUserNamePwd()
    {
        $query = "SELECT
                users.username, users.password, users.id,
                access_levels.access_num, access_levels.access_name,
                admin_levels.admin_name, admin_levels.admin_num
            FROM users
            INNER JOIN access_levels ON users.access_id=access_levels.id
            INNER JOIN admin_levels ON users.admin_id=admin_levels.id
            WHERE users.username=?
            LIMIT 0,1;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->username);

        $stmt->execute();

        // get user details
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        //set user in session if there is a user and hashes match
        if ($row && password_verify($this->password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['access_num'] = $row['access_num'];
            $_SESSION['access_name'] = $row['access_name'];
            $_SESSION['admin_name'] = $row['admin_name'];
            $_SESSION['admin_num'] = $row['admin_num'];
            $_SESSION['homepage_visited'] = 0;

            //see session-validation.php
            $_SESSION['last_activity'] = time();

            //analytics
            $_SESSION['category_id'] = 0;
            $_SESSION['last_row'] = 0;
        } else {
            //echo '<h3 class="text-danger text-center">Login failed.<br />Please try again.</h3>';

            //echo '<div class="alert alert-danger alert-styled-left alert-dismissible">';
            //echo '<button type="button" class="close" data-dismiss="alert"><span>×</span></button>';
            //echo '<span class="font-weight-semibold">Login failed!</span> Please <a href="#" class="alert-link">try submitting again</a>.';
            //echo '</div>';

            echo <<<FAIL
            <script>
                window.addEventListener("load", function() {
                    swal({
                        title: 'Login failed!',
                        text: "Please try again.",
                        type: 'error',
                        backdrop: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonClass: 'btn btn-info',
                        allowOutsideClick: false,
                        confirmButtonText: 'OK',
                    });
                    });
                </script>
FAIL;
        }
    }

    /**
     * Read all access level from access_levels table
     *
     * @return PDOStatement in ascending access_num order
     */
    public function readAccessLevels()
    {

        $query = "SELECT * FROM access_levels
        ORDER BY access_num ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all admin level from admin_levels table
     *
     * @return PDOStatement in ascending admin_num order
     */
    public function readAdminLevels()
    {
        $query = "SELECT * FROM admin_levels
        ORDER BY admin_num ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Make sure username hasn't already been used/created
     *
     * @return bool true if username exists, otherwise false
     */
    public function checkUniqueSignUp()
    {
        $query = "";

        if ($this->oid) {
            // check if oid is already used
            $query = "SELECT oid FROM " . $this->table_name . "
                    WHERE oid = ?
                    LIMIT 0,1";
    
            // prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // bind selected username
            $stmt->bindParam(1, $this->oid);
        } elseif ($this->email) {
            // check if email is already used
            $query = "SELECT email FROM " . $this->table_name . "
                    WHERE email = ?
                    LIMIT 0,1";
    
            // prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // bind selected username
            $stmt->bindParam(1, $this->email);
        } else {
            // check if username is already used
            $query = "SELECT username FROM " . $this->table_name . "
                    WHERE username = ?
                    LIMIT 0,1";
    
            // prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // bind selected username
            $stmt->bindParam(1, $this->username);
        }

        // execute the query
        try {
            //code...
            $stmt->execute();
        } catch (\Throwable $th) {
            //throw $th;
            print_r($th);
        }

        //if not username in db, return true
        if (!$stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * update login time
     *
     * @return boolean denoting whether update was successful
     */
    public function updateLoginTime()
    {
        // update the login time
        $query = "UPDATE " . $this->table_name . "
                SET logged_in = :logged_in WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $new_timestamp = date('Y-m-d H:i:s');
        $this->id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : die('ERROR: missing ID.');

        // bind values
        $stmt->bindParam(':logged_in', $new_timestamp);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * zero out the the login timestamp
     *
     * @return boolean denoting whether update was successful
     */

    public function resetLoginTimestamp()
    {
        // update the login time
        $query = "UPDATE " . $this->table_name . "
                SET logged_in = :logged_in WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $new_timestamp = '1970-01-01 00:00:01';
        $this->id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : die('ERROR: missing ID.');

        // bind values
        $stmt->bindParam(':logged_in', $new_timestamp);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }
}
