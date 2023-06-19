<?php

/**
 * Cohort Object
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

/**
 * Cohort Class
 *
 * Provide database I/O (CRUD) for the cohorts table.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Cohort
{

    private $conn;
    private $table_name = "cohorts";
    
    // object properties
    public $id;               ///< guid, primary key
    public $cohort_name;      ///< text name of the cohort
    public $facilitator_id;   ///< user id of cohort facilitator
    public $course_id;        ///< id of the course to which cohort belongs
    public $created;          ///< timestamp of record creation
    
    /**
     * Constructor
     *
     * @param database $db cohorts Instance of the Database Class
     * 
     * @return PDE An instance of the PDO Class for the cohorts table
     */
    public function __construct($db){
        $this->conn = $db;
    }
    
    /**
     * Insert/create cohort record
     * 
     * @return bool true if insert successful, otherwise false
     */
    public function create(){

        // insert query
        $query = "INSERT INTO
                    cohorts
                SET
                    id=:id,
                    cohort_name=:cohort_name,
                    facilitator_id=:facilitator_id,
                    course_id=:course_id,
                    created=:created";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // to get time-stamp for 'created' field
        $this->created = date('Y-m-d H:i:s');
        
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->cohort_name=htmlspecialchars(strip_tags($this->cohort_name));
        $this->facilitator_id=htmlspecialchars(strip_tags($this->facilitator_id));
        $this->course_id=htmlspecialchars(strip_tags($this->course_id));
        $this->created=htmlspecialchars(strip_tags($this->created));
        
        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":cohort_name", $this->cohort_name);
        $stmt->bindParam(":facilitator_id", $this->facilitator_id);
        $stmt->bindParam(":course_id", $this->course_id);
        $stmt->bindParam(":created", $this->created);
        
        // execute query
        if($stmt->execute() && $stmt->rowCount()){
            
            $_SESSION['cohort_id'] = $this->id;
            
            return true;
    
        }
        
        return false;
    }
    
    /**
     * Read all cohorts (id, cohort_name, facilitator name, course name) in cohort_name order
     * 
     * @return PDOStatement in ascending course name order, then username order
     */
    public function readAll(){
        $query = "SELECT
                    cohorts.id, cohorts.cohort_name, users.username, courses.course_name
                FROM
                    " . $this->table_name . "
                JOIN
                    users ON cohorts.facilitator_id=users.id
                JOIN
                    courses ON cohorts.course_id=courses.id
                ORDER BY
                    courses.course_name, users.username";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * Read the details of cohort for particular id
     * 
     * @return PDOStatement
     */
    public function readOne(){
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    id=:id
                LIMIT
                    0,1";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * Read all cohorts for particular course id
     * 
     * @return PDOStatement
     */
    public function readAllByCourse(){
        $query = "SELECT
                    cohorts.*, users.username facilitator_name
                FROM
                    " . $this->table_name . "
                JOIN users ON cohorts.facilitator_id=users.id
                WHERE
                    course_id=:course_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * Read all students in cohort
     * 
     * @return PDOStatement
     */
    public function readAllStudentsInCohort(){
        $query = "SELECT
                    cohort_name, ce.student_id, users.username
                FROM
                    " . $this->table_name . "
                JOIN class_enrollments ce ON cohort.id=ce.cohort_id
                JOIN courses ON cohort.course_id=courses.id
                JOIN users ON ce.student_id=users.id
                WHERE
                    id=:id";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * Read facilitator for cohort
     * 
     * @return PDOStatement
     */
    public function readFacilitatorForCohort(){
        $query = "SELECT
                    cohort_name, facilitator_id, users.username, users.admin_id, course_id, categories.id cat_id
                FROM
                    " . $this->table_name . "
                JOIN users ON facilitator_id=users.id
                JOIN courses ON cohorts.course_id=courses.id
                JOIN topics ON courses.topic_id=topics.id
                JOIN categories ON topics.category_id=categories.id
                WHERE
                    cohorts.id=:id";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * Read all students in a course
     * 
     * @return PDOStatement
     */
    public function readAllStudentsInCourse(){
        $query = "SELECT DISTINCT
                    c.id, c.cohort_name, c.course_id, c.facilitator_id, courses.course_name, ce.student_id, users.username, users.admin_id
                FROM
                    " . $this->table_name . " c
                JOIN cohort_enrollments ce ON c.id=ce.cohort_id
                JOIN courses ON c.course_id=courses.id
                JOIN users ON ce.student_id=users.id
                WHERE
                    course_id=:course_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * Read all cohorts and courses for a particular facilitator id
     * 
     * @return PDOStatement
     */
    public function readAllCohortsForFacilitator(){
        $query = "SELECT
                    cohorts.*, courses.course_name
                FROM
                    " . $this->table_name . "
                JOIN courses ON cohorts.course_id=courses.id
                WHERE
                    facilitator_id=:facilitator_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':facilitator_id', $this->facilitator_id);
        $stmt->execute();
    
        return $stmt;
    }

    /**
     * Read all categories for a particular facilitator id
     * 
     * @return PDOStatement
     */
    public function readCatIdArrayByFacilitator(){
        $query = "SELECT
                    categories.id
                FROM
                    " . $this->table_name . "
                JOIN courses ON cohorts.course_id=courses.id
                JOIN topics ON courses.topic_id=topics.id
                JOIN categories ON topics.category_id=categories.id
                WHERE
                    facilitator_id=:facilitator_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':facilitator_id', $this->facilitator_id);
        $stmt->execute();
        $array = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $array[] = $id;
        }
        return $array;
    }

    /**
     * reads category to which a cohort belongs
     * 
     * @return category id
     */
    public function readCohortCategoryId(){
        // query to obtain a cohort's category
        $query = "SELECT
                    categories.id
                FROM
                    " . $this->table_name . "
                JOIN
                    courses ON cohorts.course_id=courses.id
                JOIN 
                    topics ON courses.topic_id=topics.id
                JOIN
                    categories ON topics.category_id=categories.id
                WHERE
                    cohorts.id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $category_id = $row['id'];

        return $category_id;
    }

    /**
     * read all students in a course
     * 
     * @return PDOStatement
     */
    public function readStudentsInCourse(){
        $query = "SELECT
                    ce.student_id, cohorts.course_id
                FROM
                    " . $this->table_name . "
                JOIN
                    cohort_enrollments ce ON cohorts.id=ce.cohort_id
                WHERE
                    course_id=:course_id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();

        return $stmt;
    }
    
    /**
     * Update the cohort
     * 
     * @return boolean whether update was successful, otherwise false
     */	
    public function update(){
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    cohort_name=:cohort_name,
                    facilitator_id=:facilitator_id,
                    course_id=:course_id
                WHERE
                    id=:id";

        // sanitize
        $this->cohort_name=htmlspecialchars(strip_tags($this->cohort_name));
        $this->facilitator_id=htmlspecialchars(strip_tags($this->facilitator_id));
        $this->course_id=htmlspecialchars(strip_tags($this->course_id));
        $this->id=htmlspecialchars(strip_tags($this->id));
        
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(':cohort_name', $this->cohort_name);
        $stmt->bindParam(':facilitator_id', $this->facilitator_id);
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if($stmt->execute() && $stmt->rowCount()){
            return true;
        }

        return false;
    }
    
    /**
     * Delete the cohort
     * 
     * @return boolean whether delete was successful, otherwise false
     */	
    public function delete(){
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id=:id";

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
                    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute() && $stmt->rowCount()){
            return true;
        }

        return false;
    }

    /**
     * Count the number of cohorts in a course
     *
     * @return total count
     */
    public function countCohortsByCourse(){
        $query = "SELECT
                    COUNT(*) AS total_rows
                FROM
                    " . $this->table_name . "
                WHERE
                    course_id=:course_id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        return $total_rows;
    }

    /**
     * Count the number of students in a course
     *
     * @return total count
     */
    public function countStudentsByCourse(){
        $query = "SELECT
                    COUNT(*) AS total_rows
                FROM
                    " . $this->table_name . "
                JOIN
                    cohort_enrollments ce ON cohorts.id=ce.cohort_id
                WHERE
                    course_id=:course_id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        return $total_rows;
    }

}
?>
