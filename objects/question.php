<?php

/**
 * Question Object
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
 * Question Class
 *
 * Provides database I/O (CRUD) for the questions table,
 *          the table that hold the test(s) questions.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Question
{
    private $conn;
    private $table_name = "questions";

    public $question_id;          ///< guid, primary key
    public $question_text;        ///< the text of the question
    public $bank_id;              ///< relates to question_bank
    public $admin_id;             ///< admin level id - relates to admin_levels table
    public $created;              ///< datetime record was created

    /**
     * Constructor
     *
     * @param object $db -  database connection to questions table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create a question
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {

        // to get time-stamp for 'created' field
        $this->created = date('Y-m-d H:i:s');

        $query = "INSERT INTO
                    questions
                SET
                    id=:id,
                    question_text=:question_text,
                    bank_id=:bank_id,
                    admin_id=:admin_id,
                    created=:created";

        $stmt = $this->conn->prepare($query);

        // sanitize
        // $this->question_text=htmlspecialchars(strip_tags($this->question_text));
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":id", $this->question_id);
        $stmt->bindParam(":question_text", $this->question_text);
        $stmt->bindParam(":bank_id", $this->bank_id);
        $stmt->bindParam(":admin_id", $this->admin_id);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute() && $stmt->rowCount()) {
            //get and set the id of newly created quiz
            $_SESSION['quiz_question_id'] = $this->question_id;

            return true;
        }

        return false;
    }

    /**
     * Read all questions (id, question_text, bank_id)
     *
     * @return PDOStatement in ascending bank_id order
     */
    public function readAll()
    {
        $query = "SELECT
                    id, question_text, bank_id
                FROM
                    " . $this->table_name . "
                ORDER BY
                    bank_id ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    /**
     * Update the question
     *
     * @return bool true if update successful, otherwise false
     */
    public function update()
    {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    question_text=:question_text,
                    bank_id=:bank_id,
                    admin_id=:admin_id
                WHERE
                    id=:id";

        // sanitize
        $this->question_text = htmlspecialchars(strip_tags($this->question_text));
        $this->bank_id = htmlspecialchars(strip_tags($this->bank_id));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':question_text', $this->question_text);
        $stmt->bindParam(':bank_id', $this->bank_id);
        $stmt->bindParam(':admin_id', $this->admin_id);
        $stmt->bindParam(':id', $this->question_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete a question
     *
     * @return bool true if delete successful, otherwise false
     */
    public function delete()
    {
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id=:question_id";

        // sanitize
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $this->question_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }
}
