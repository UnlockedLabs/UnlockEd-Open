<?php

/**
 * Quiz Object
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

/**
 * Quiz Class
 *
 * Provides database I/O (CRUD) for the quizzes table
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Quiz
{

    private $conn;
    private $table_name = "quizzes";

    public $quiz_id;              ///< guid, primary key
    public $quiz_name;            ///< name of the quiz
    public $lesson_id;            ///< lesson id, relates to lessons
    public $quiz_desc;            ///< the html markup for the description of the quiz
    public $admin_id;             ///< admin level id - relates to admin_levels table
    public $created;              ///< datetime record was created

    /**
     * Constructor
     *
     * @param object $db -  database connection to quizzes table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create a quiz
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        // to get time-stamp for 'created' field
        $this->created = date('Y-m-d H:i:s');

        $query = "INSERT INTO
                    quizzes
                SET
                    id=:id,
                    quiz_name=:quiz_name,
                    quiz_desc=:quiz_desc,
                    lesson_id=:lesson_id,
                    admin_id=:admin_id,
                    created=:created";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $this->quiz_name = htmlspecialchars(strip_tags($this->quiz_name));
        $this->lesson_id = htmlspecialchars(strip_tags($this->lesson_id));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":id", $this->quiz_id);
        $stmt->bindParam(":quiz_name", $this->quiz_name);
        $stmt->bindParam(":quiz_desc", $this->quiz_desc);
        $stmt->bindParam(":lesson_id", $this->lesson_id);
        $stmt->bindParam(":admin_id", $this->admin_id);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Read the details of quiz to be edited/displayed
     * assign values to properties quiz_name, quiz_desc, lesson_id, admin_id, created
     *
     * @return void
     */
    public function readOne()
    {
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    id=:id
                LIMIT
                    0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->quiz_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->quiz_name = $row['quiz_name'];
        $this->quiz_desc = $row['quiz_desc'];
        $this->lesson_id = $row['lesson_id'];
        $this->admin_id = $row['admin_id'];
        $this->created = $row['created'];
    }

    /**
     * Read quiz name for particular lesson id
     *
     * @return PDOStatement in ascending id order
     */
    public function readQuizzesByLessonId()
    {
        $query = "SELECT
                    id, quiz_name
                FROM
                    " . $this->table_name . "
                WHERE
                    lesson_id=:lesson_id
                ORDER BY
                    id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->execute();

        return $stmt;
    }


    /**
     * Update the quiz
     *
     * @return bool true if update successful, otherwise false
     */
    public function update()
    {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    quiz_name=:quiz_name,
                    quiz_desc=:quiz_desc,
                    lesson_id=:lesson_id,
                    admin_id=:admin_id
                WHERE
                    id=:id";

        // sanitize
        $this->quiz_name = htmlspecialchars(strip_tags($this->quiz_name));
        $this->quiz_desc = htmlspecialchars(strip_tags($this->quiz_desc));
        $this->lesson_id = htmlspecialchars(strip_tags($this->lesson_id));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));

        $stmt = $this->conn->prepare($query);

        // is it necessary to sanitize first?
        $stmt->bindParam(':quiz_name', $this->quiz_name);
        $stmt->bindParam(':quiz_desc', $this->quiz_desc);
        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->bindParam(':admin_id', $this->admin_id);
        $stmt->bindParam(':id', $this->quiz_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete the quiz
     *
     * @return bool true if delete successful, otherwise false
     */
    public function delete()
    {
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id=:quiz_id";

        // sanitize
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $this->quiz_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }


    /**
     * Count quizzes for particular lesson
     *
     * @return int total quizzes
     */
    public function countByLessonId()
    {
        $query = "SELECT
                    COUNT(*) AS total_rows
                FROM
                    " . $this->table_name . "
                WHERE
                    lesson_id=:lesson_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        return $total_rows;
    }


    /**
     * Validate that quiz exists
     *
     * @return bool true if quiz exists, otherwise false
     */
    public function quizExists()
    {
        // select single record query
        $query = "SELECT
                    quiz_name
                FROM
                    " . $this->table_name . "
                WHERE
                    LOWER(quiz_name)=:quiz_name
                LIMIT
                    0,1";

        $stmt = $this->conn->prepare($query);

        //we are lowering to prevent topics like lesson 1 and Lesson 1
        $quiz_name_lower = strtolower($this->quiz_name);

        $stmt->bindParam(":quiz_name", $quiz_name_lower);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (isset($row['quiz_name'])) {
            return true;
        } else {
            return false;
        }
    }
}
