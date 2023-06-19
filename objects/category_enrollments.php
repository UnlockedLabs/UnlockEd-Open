<?php
namespace unlockedlabs\unlocked;

/**
 * @brief   CategoryEnrollment Class
 * @details Provides database I/O (CRUD) for the category_enrollments table
 */
class CategoryEnrollment
{

    // database connection and table name
    private $conn;
    private $table_name = "category_enrollments";
    
    // object properties
    public $category_id;      /**< the category guid */
    public $student_id;       /**< the student's user guid */
    
    /**
     * Constructor
     *
     * @param    object $db  -  database connection to category_enrollments table
    */
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    /**
     * insert/create a category-enrollment
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
                    student_id=:student_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->student_id=htmlspecialchars(strip_tags($this->student_id));
        
        // bind values
        // $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":student_id", $this->student_id);
        
        // execute query
        if($stmt->execute() && $stmt->rowCount()){
            
            return true;
        }
        
        return false;
    }
    
    /** 
     * read all students for a particular category id 
     * 
     * @return PDOStatement
    */
    public function readAllStudents()
    {
        $query = "SELECT DISTINCT
                    ce.student_id, users.username, users.access_id, users.admin_id
                FROM
                    " . $this->table_name . " ce
                JOIN users ON users.id=ce.student_id
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
     * read all categories in which a student is enrolled
     * 
     * @return PDOStatement
     */
    public function readAllCategoriesForStudent()
    {
        $query = "SELECT DISTINCT
                    ce.category_id, categories.category_name
                FROM
                    " . $this->table_name . " ce
                JOIN categories ON ce.category_id=categories.id
                WHERE
                    student_id=:student_id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * reads one student for a category 
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
                    id=:student_id
                LIMIT
                    0,1";

                    
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->execute();
    
        return $stmt;
    }
    
    /** 
     * deletes a student from category-enrollments
     * 
     * @return boolean denoting whether delete was successful
     */
    public function delete()
    {
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    category_id=:category_id AND student_id=:student_id";

        // sanitize
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->student_id=htmlspecialchars(strip_tags($this->student_id));
                    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':student_id', $this->student_id);

        if($stmt->execute() && $stmt->rowCount()){
            return true;
        }

        return false;
    }

    /**
     * counts students enrolled in category by category id
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
     * check records for particular student id and category id to validate
     * existence of category enrollment
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
                    student_id=:student_id AND category_id=:category_id
                LIMIT
                    1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $this->student_id);
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
