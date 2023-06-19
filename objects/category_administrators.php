<?php
namespace unlockedlabs\unlocked;

/**
 * @brief   CategoryAdministrator Class
 * @details Provides database I/O (CRUD) for the category_administrators table
 */
class CategoryAdministrator
{

    // database connection and table name
    private $conn;
    private $table_name = "category_administrators";
    
    // object properties
    public $category_id;      ///< the category guid */
    public $administrator_id;  ///< the administrator's user guid */
    
    /**
     * Constructor
     *
     * @param    object $db  -  database connection to category_administrators table
    */
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    /**
     * insert/create a category administrator
     * 
     * @return boolean denoting whether insert was successful
     */
    public function create()
    {

        // insert query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    category_id=:category_id,
                    administrator_id=:administrator_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->administrator_id=htmlspecialchars(strip_tags($this->administrator_id));
        
        // bind values
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":administrator_id", $this->administrator_id);
        
        // execute query
        if($stmt->execute() && $stmt->rowCount()){
            
            return true;
        }
        
        return false;
    }
    
    /** 
     * read all administrators for a particular category id 
     * 
     * @return PDOStatement
    */
    public function readAllAdministrators()
    {
        $query = "SELECT DISTINCT
                    ca.administrator_id cat_admin_id, users.username, users.access_id, users.admin_id
                FROM
                    " . $this->table_name . " ca
                JOIN users ON users.id=ca.administrator_id
                WHERE
                    category_id=:category_id
                ORDER BY
                    users.username";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * read all categories for which a user is an administrator
     * 
     * @return PDOStatement
     */
    public function readAllCategoriesForAdmin()
    {
        $query = "SELECT DISTINCT
                    ca.category_id, categories.category_name
                FROM
                    " . $this->table_name . " ca
                JOIN categories ON ca.category_id=categories.id
                WHERE
                    administrator_id=:administrator_id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':administrator_id', $this->administrator_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * reads one administrator for a category 
     * 
     * @return PDOStatement
     */
    public function readOne()
    {
        $query = "SELECT
                    *
                FROM
                    users
                WHERE
                    id=:administrator_id
                LIMIT
                    0,1";

                    
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':administrator_id', $this->administrator_id);
        $stmt->execute();
    
        return $stmt;
    }
    
    /** 
     * deletes an administrator from category_administrators
     * 
     * @return boolean denoting whether delete was successful
     */
    public function delete()
    {
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    category_id=:category_id AND administrator_id=:administrator_id";

        // sanitize
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->administrator_id=htmlspecialchars(strip_tags($this->administrator_id));
                    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':administrator_id', $this->administrator_id);

        if($stmt->execute() && $stmt->rowCount()){
            return true;
        }

        return false;
    }

    /**
     * counts all administrators of category by category id
     * 
     * @return total count
     */
    public function countByCategoryId()
    {
        // query to count all data
        $query = "SELECT DISTINCT
                    COUNT(*) AS total_rows
                FROM
                    " . $this->table_name . "
                WHERE
                    category_id=:category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        return $total_rows;
    }

    /**
     * check records for particular administrator id and category id to validate
     * existence of category administrator
     * 
     * @return boolean denoting whether record exists
     */
    public function rowExists()
    {
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    administrator_id=:administrator_id AND category_id=:category_id
                LIMIT
                    1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':administrator_id', $this->administrator_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }

    }

}
?>
