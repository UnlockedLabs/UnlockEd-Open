<?php

/**
 * Quiz Answer Object
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

require_once dirname(__FILE__).'/GUID.php';

/**
 * Answer Class
 *
 * Provide database I/O (CRUD) for the answers table.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Answer
{
    private $conn;
    private $table_name = "answers";

    public $answer_id;            ///< guid, primary key
    public $answer_text;          ///< the text for an answer option
    public $question_id;          ///< relates to questions table (guid)
    public $correct;              ///< yes/no enum denoting if answer is a correct one
    public $answer_position;      ///< position of the answer in the question originally set in quizzicUL
    public $created;              ///< datetime of record creation

    /**
     * Constructor
     *
     * @param database $db answers Instance of the Database Class
     *
     * @return PDO An instance of the PDO Class for the answers table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create quiz answer record
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        $this->created = date('Y-m-d H:i:s');

        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    id=:id,
                    answer_text=:answer_text,
                    question_id=:question_id,
                    correct=:correct,
                    answer_position=:answer_position,
                    created=:created";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());
        $this->answer_id = $this->id;
        // sanitize
        $this->answer_text = htmlspecialchars(strip_tags($this->answer_text));
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));
        $this->correct = htmlspecialchars(strip_tags($this->correct));
        $this->answer_position = htmlspecialchars(strip_tags($this->answer_position));
        $this->created = htmlspecialchars(strip_tags($this->created));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":answer_text", $this->answer_text);
        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":correct", $this->correct);
        $stmt->bindParam(":answer_position", $this->answer_position);
        $stmt->bindParam(":created", $this->created);

        if ($stmt->execute() && $stmt->rowCount()) {
            //NOTE: may be keeping these $_SESSION variables, just in case ...
            $_SESSION['answer_id'] = $this->answer_id;
        }

        return false;
    }

    /**
     * Read all answer text
     *
     * @return PDOStatement in ascending id order
     */
    public function readAll()
    {
        $query = "SELECT
                    id, answer_text
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }


    /**
     * Read all answers for a particular quiz question (question_id)
     *
     * @return PDOStatement in ascending answer_position order
     */
    public function readAllByQuizQuestion()
    {
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    question_id=:question_id
                ORDER BY
                    answer_position ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $this->question_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all answers for a particular quiz question (question_id).
     *
     * @return PDOStatement in random order
     */
    public function readAllByQuizQuestionRandom()
    {
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                WHERE
                    question_id=:question_id
                ORDER BY
                    RAND()";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $this->question_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all id and answer text
     *
     * @return PDOStatement in answer text order
     */
    public function read()
    {
        $query = "SELECT
                    id, answer_text
                FROM
                    " . $this->table_name . "
                ORDER BY
                    answer_text";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read the details of answer for particular id and
     * assign values to properties answer_text, created properties
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

        $stmt->bindParam(':id', $this->answer_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->answer_text = $row['answer_text'];
        $this->created = $row['created'];
    }

    /**
     * Update the answer text
     *
     * @return bool true is update successful, otherwise false
     */
    public function update()
    {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    answer_text=:answer_text,
                WHERE
                    id=:id";

        // sanitize
        $this->answer_text = htmlspecialchars(strip_tags($this->answer_text));
        $this->answer_id = htmlspecialchars(strip_tags($this->answer_id));

        $stmt = $this->conn->prepare($query);

        // is it necessary to sanitize first?
        $stmt->bindParam(':answer_text', $this->answer_text);
        $stmt->bindParam(':id', $this->answer_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Deletes the answer
     *
     * @return bool true if delete successful, otherwise false
     */
    public function delete()
    {
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    id=:id";

        // sanitize
        $this->answer_id = htmlspecialchars(strip_tags($this->answer_id));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->answer_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }
}
