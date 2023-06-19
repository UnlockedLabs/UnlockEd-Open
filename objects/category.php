<?php

/**
 * Category Object
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
 * Category Class
 *
 * Provide database I/O (CRUD) for the categories table.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Category
{
    private $conn;
    private $table_name = "categories";

    // object properties
    public $id;                   ///< guid, primary key
    public $category_name;        ///< text name of the category
    public $access_id;            ///< access level id - relates to access_levels table
    public $created;              ///< timestamp of record creation

    /**
     * Constructor
     *
     * @param database $db categories Instance of the Database Class
     *
     * @return PDO An instance of the PDO Class for the categories table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create category record
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        $query = "INSERT INTO categories
                SET id=?, category_name = ?, access_id = ?";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());

        // sanitize
        $this->category_name = htmlspecialchars(strip_tags($this->category_name));
        $this->access_id = htmlspecialchars(strip_tags($this->access_id));

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->category_name);
        $stmt->bindParam(3, $this->access_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Read all categories
     *
     * @return PDOStatement in ascending category name order
     */
    public function readCategories()
    {

        $query = "SELECT * FROM categories
        /*ORDER BY category_name ASC*/";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read the details of category for particular id and
     * assign values to properties category_name and access_id
     *
     * @return void
     */
    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . "
        WHERE id = ?
        LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->category_name = $row['category_name'];
        $this->access_id = $row['access_id'];
    }

    /**
     * Read all from topics for particular category id
     *
     * @param guid $categoryId category id from this topic's course
     *
     * @return PDOStatement in ascending topic name order
     */
    public function readTopicsByCategoryId($categoryId)
    {

        $query = "SELECT * FROM topics
        WHERE category_id=?
        /*ORDER BY topic_name ASC*/";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $categoryId);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all courses for particular category id
     * 
     * @return PDOStatement in ascending course name order
     */
    public function readCoursesByCatId()
    {
        $query = "SELECT
                    courses.id, courses.course_name, courses.iframe
                FROM
                    " . $this->table_name . "
                JOIN topics ON categories.id=topics.category_id
                JOIN courses ON topics.id=courses.topic_id
                WHERE
                    categories.id=:category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->id);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Read all instructors for particular category id
     * 
     * @return PDOStatement in ascending course name order
     */
    public function readInstructorsByCatId()
    {
        $query = "SELECT
                    ca.course_id, ca.administrator_id course_admin_id, users.username, users.admin_id
                FROM
                    " . $this->table_name . "
                JOIN topics ON categories.id=topics.category_id
                JOIN courses ON topics.id=courses.topic_id
                JOIN course_administrators ca ON courses.id=ca.course_id
                JOIN users ON ca.administrator_id=users.id
                WHERE
                    categories.id=:category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->id);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Read all courses for particular category id
     * 
     * @param guid $categoryId category id
     *
     * @return array of course ids
     */
    public function readCoursesIdArrayByCatId($categoryId)
    {
        $query = "SELECT
                    courses.id
                FROM
                    " . $this->table_name . "
                JOIN topics ON categories.id=topics.category_id
                JOIN courses ON topics.id=courses.topic_id
                WHERE
                    categories.id=:category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        $array = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $array[] = $id;
        }
        return $array;
    }

    /**
     * Read all cohorts for particular category id
     * 
     * @param guid $categoryId category id
     *
     * @return array of course ids
     */
    public function readCohortsIdArrayByCatId($categoryId)
    {
        $query = "SELECT
                    cohorts.id
                FROM
                    " . $this->table_name . "
                JOIN topics ON categories.id=topics.category_id
                JOIN courses ON topics.id=courses.topic_id
                JOIN cohorts ON courses.id=cohorts.course_id
                WHERE
                    categories.id=:category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        $array = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $array[] = $id;
        }
        return $array;
    }

    /**
     * read all cohort-enrolled students in a particular category
     * 
     * @param guid $categoryId category id
     * 
     * @return PDOStatement
     */
    public function readAllCohortStudentsByCatId($categoryId){
        $query = "SELECT
                    ce.student_id
                FROM
                    " . $this->table_name . "
                JOIN topics ON categories.id=topics.category_id
                JOIN courses ON topics.id=courses.topic_id
                JOIN cohorts ON courses.id=cohorts.course_id
                JOIN cohort_enrollments ce ON cohorts.id=ce.cohort_id
                WHERE
                    categories.id=:category_id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        $array = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $array[] = $student_id;
        }
        return $array;
    }

    /**
     * Read quiz ids by category id
     *
     * @return array of quiz ids
     */
    public function readQuizzesByCatId()
    {
        $query = "SELECT
                    quizzes.id
                FROM
                    quizzes
                    JOIN lessons ON quizzes.lesson_id=lessons.id
                    JOIN courses ON lessons.course_id=courses.id
                    JOIN topics ON courses.topic_id=topics.id
                    JOIN categories ON topics.category_id=categories.id
                WHERE
                    categories.id=:category_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':category_id', $this->id);

        $stmt->execute();
        $array = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $array[] = $id;
        }
        return $array;
    }

    /**
     * Count the number of instructors for particular category id
     * 
     * @return PDOStatement in ascending course name order
     */
    public function countInstructorsByCatId()
    {
        $query = "SELECT
                    COUNT(*) AS total_rows
                FROM
                    " . $this->table_name . "
                JOIN topics ON categories.id=topics.category_id
                JOIN courses ON topics.id=courses.topic_id
                JOIN course_administrators ca ON courses.id=ca.course_id
                WHERE
                    categories.id=:category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        return $total_rows;
    }

    /**
     * Update the category
     *
     * @return bool true if update successful, otherwise false
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
        SET category_name = :category_name,
        access_id = :access_id
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':category_name', $this->category_name);
        $stmt->bindParam(':access_id', $this->access_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete the category
     *
     * @return bool true if delete successful, otherwise false
     */
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Check if category name exists
     *
     * @return bool true if category name exists, otherwise false
     */
    public function categoryExists()
    {
        $query = "SELECT category_name FROM " . $this->table_name . "
        WHERE LOWER(category_name) = ?
        LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        //we are lowering to prevent categories like Ohio University and ohio unversity
        $category_name_lower = strtolower($this->category_name);
        $stmt->bindParam(1, $category_name_lower);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (isset($row['category_name'])) {
            return true;
        } else {
            return false;
        }
    }

}
