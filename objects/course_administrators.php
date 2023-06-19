<?php
namespace unlockedlabs\unlocked;

/**
 * @brief   CourseAdministrator Class
 * @details Provides database I/O (CRUD) for the course_administrators table
 */
class CourseAdministrator
{

    // database connection and table name
    private $conn;
    private $table_name = "course_administrators";
    
    // object properties
    public $course_id;        ///< the course guid */
    public $administrator_id; ///< the administrator's user guid */
    
    /**
     * Constructor
     *
     * @param    object $db  -  database connection to course_administrators table
    */
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    /**
     * insert/create a course administrator
     * 
     * @return boolean denoting whether insert was successful
     */
    public function create()
    {

        // insert query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    course_id=:course_id,
                    administrator_id=:administrator_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->course_id=htmlspecialchars(strip_tags($this->course_id));
        $this->administrator_id=htmlspecialchars(strip_tags($this->administrator_id));
        
        // bind values
        $stmt->bindParam(":course_id", $this->course_id);
        $stmt->bindParam(":administrator_id", $this->administrator_id);
        
        // execute query
        if($stmt->execute() && $stmt->rowCount()){
            
            return true;
        }
        
        return false;
    }
    
    /** 
     * read all administrators for a particular course id 
     * 
     * @return PDOStatement
    */
    public function readAllAdministrators()
    {
        $query = "SELECT DISTINCT
                    co.administrator_id course_admin_id, users.username, users.access_id, users.admin_id
                FROM
                    " . $this->table_name . " co
                JOIN users ON users.id=co.administrator_id
                WHERE
                    course_id=:course_id
                ORDER BY
                    users.username";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * read all courses in which a user is an administrator
     * 
     * @return PDOStatement
     */
    public function readAllCoursesForAdmin()
    {
        $query = "SELECT DISTINCT
                    co.course_id, courses.course_name
                FROM
                    " . $this->table_name . " co
                JOIN courses ON co.course_id=courses.id
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
     * deletes an administrator from course administrators
     * 
     * @return boolean denoting whether delete was successful
     */
    public function delete()
    {
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    course_id=:course_id AND administrator_id=:administrator_id";

        // sanitize
        $this->course_id=htmlspecialchars(strip_tags($this->course_id));
        $this->administrator_id=htmlspecialchars(strip_tags($this->administrator_id));
                    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':administrator_id', $this->administrator_id);

        if($stmt->execute() && $stmt->rowCount()){
            return true;
        }

        return false;
    }

    /**
     * counts all administrators of course by course id
     * 
     * @return total count
     */
    public function countByCourseId()
    {
        // query to count all data
        $query = "SELECT DISTINCT
                    COUNT(*) AS total_rows
                FROM
                    " . $this->table_name . "
                WHERE
                    course_id=:course_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        return $total_rows;
    }

    /**
     * check records for particular administrator id and course id to validate
     * existence of course administrator
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
                    administrator_id=:administrator_id AND course_id=:course_id
                LIMIT
                    1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':administrator_id', $this->administrator_id);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }

    }

}
?>
