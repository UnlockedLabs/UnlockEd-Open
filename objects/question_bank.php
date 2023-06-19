<?php

/**
 * Question Bank Object
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
 * QuestionBank Class
 *
 * Provides database I/O (CRUD) for the question_bank table.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class QuestionBank
{
    private $conn;
    private $table_name = "question_bank";

    public $bank_id;          ///< guid, primary key
    public $bank_name;        ///< the question bank name
    public $created;          ///< datetime the record was created

    /**
     * Constructor
     *
     * @param object $db -  database connection to question_bank table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create the bank name
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        // to get time-stamp for 'created' field
        $this->created = date('Y-m-d H:i:s');

        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    id=:id,
                    bank_name=:bank_name,
                    created=:created";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());

        // sanitize
        $this->bank_name = htmlspecialchars(strip_tags($this->bank_name));
        $this->created = htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":bank_name", $this->bank_name);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute() && $stmt->rowCount()) {
            //get and set the id of newly created quiz
            $this->bank_id = $this->conn->lastInsertId();

            //NOTE: may be keeping these $_SESSION variables, just in case ...
            $_SESSION['bank_id'] = $this->bank_id;
            $_SESSION['bank_name'] = $this->bank_name;
        }

        return false;
    }

    /**
     * Read all bank names
     *
     * @return PDOStatement in ascending id order
     */
    public function readAll()
    {
        $query = "SELECT
                    id, bank_name
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    /**
     * Read one question bank, the details of quiz to be edited, and
     * assign values to properties bank_id, bank_name, created
     *
     * @return PDOStatement
     */
    public function readOne()
    {
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    id=:bank_id
                LIMIT
                    0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':bank_id', $this->bank_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->bank_id = $row['bank_id'];
        $this->bank_name = $row['bank_name'];
        $this->created = $row['created'];
    }

    /**
     * Update bank name
     *
     * @return bool true if update successful, otherwise false
     */
    // update the question-bank
    public function update()
    {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    bank_name=:bank_name
                WHERE
                    id=:id";

        // sanitize
        $this->bank_name = htmlspecialchars(strip_tags($this->bank_name));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));

        $stmt = $this->conn->prepare($query);

        // is it necessary to sanitize first?
        $stmt->bindParam(':bank_name', $this->bank_name);
        $stmt->bindParam(':id', $this->bank_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete a question bank
     *
     * @return bool true if delete successful, otherwise false
     */
    public function delete()
    {
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id=:bank_id";

        // sanitize
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':bank_id', $this->bank_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }
}
