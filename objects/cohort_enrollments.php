<?php
namespace unlockedlabs\unlocked;

/**
 * @brief   CohortEnrollment Class
 * @details Provides database I/O (CRUD) for the cohort_enrollments table
 */
class CohortEnrollment{

    // database connection and table name
    private $conn;
    private $table_name = "cohort_enrollments";
    
    // object properties
    public $cohort_id;        /**< the cohort guid */
    public $student_id;       /**< the student's user guid */
    
    /**
     * Constructor
     *
     * @param    object $db  -  database connection to cohort_enrollments table
    */
    public function __construct($db){
        $this->conn = $db;
    }
    
    /**
     * insert/create a cohort-enrollment
     * 
     * @return boolean denoting whether insert was successful
     */
    public function create(){

        // insert query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    cohort_id=:cohort_id,
                    student_id=:student_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->cohort_id=htmlspecialchars(strip_tags($this->cohort_id));
        $this->student_id=htmlspecialchars(strip_tags($this->student_id));
        
        // bind values
        // $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":cohort_id", $this->cohort_id);
        $stmt->bindParam(":student_id", $this->student_id);
        
        // execute query
        if($stmt->execute() && $stmt->rowCount()){
            return true;
        } else {
            return false;
        }
        
    }
    
    /** 
     * read all students for a particular cohort id 
     * 
     * @return PDOStatement
    */
    public function readAllStudents(){
        $query = "SELECT DISTINCT
                    ce.*, users.username, users.access_id, users.admin_id, cohorts.cohort_name
                FROM
                    " . $this->table_name . " ce
                JOIN
                    users ON users.id=ce.student_id
                JOIN
                    cohorts ON cohorts.id=ce.cohort_id
                WHERE
                    cohort_id=:cohort_id
                ORDER BY
                    users.username";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':cohort_id', $this->cohort_id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * read all courses and cohorts for a student
     * 
     * @return PDOStatement
     */
    public function readAllCoursesAndCohorts(){
        $query = "SELECT
                    ce.cohort_id, courses.id course_id, courses.course_name
                FROM
                    " . $this->table_name . " ce
                JOIN
                    cohorts ON ce.cohort_id=cohorts.id
                JOIN
                    courses ON cohorts.course_id=courses.id
                WHERE
                    student_id=:student_id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * reads one student for a cohort 
     * 
     * @return PDOStatement
     */
    public function readOne(){
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
     * read all cohorts in which a student is enrolled
     * 
     * @return PDOStatement
     */
    public function readAllCohortsForStudent()
    {
        $query = "SELECT DISTINCT
                    co.cohort_id, cohorts.cohort_name
                FROM
                    " . $this->table_name . " co
                JOIN cohorts ON co.cohort_id=cohorts.id
                WHERE
                    student_id=:student_id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->execute();

        return $stmt;
    }

    /** 
     * deletes a student from the cohort
     * 
     * @return boolean denoting whether delete was successful
     */
    public function delete(){
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    cohort_id=:cohort_id AND student_id=:student_id";

        // sanitize
        $this->cohort_id=htmlspecialchars(strip_tags($this->cohort_id));
        $this->student_id=htmlspecialchars(strip_tags($this->student_id));
                    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cohort_id', $this->cohort_id);
        $stmt->bindParam(':student_id', $this->student_id);

        if($stmt->execute() && $stmt->rowCount()){
            return true;
        }

        return false;
    }

    /**
     * check records for particular student id and cohort id to validate
     * existence of cohort enrollment
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
                    student_id=:student_id AND cohort_id=:cohort_id
                LIMIT
                    1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->bindParam(':cohort_id', $this->cohort_id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * counts students enrolled in cohort by cohort id
     * 
     * @return total count
     */
    public function countByCohortId(){
        // query to count all data
        $query = "SELECT DISTINCT
                    COUNT(*) AS total_rows
                FROM
                    " . $this->table_name . "
                WHERE
                    cohort_id=:cohort_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cohort_id', $this->cohort_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        return $total_rows;
    }

}
?>
