<?php

/**
 * Quiz Question Object
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
 * QuizQuestion Class
 *
 * Provides database I/O (CRUD) for the quiz_questions table
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class QuizQuestion
{
    private $conn;
    private $table_name = "quiz_questions";

    public $quiz_id;              ///< the quiz guid
    public $question_id;          ///< the quesiton guid
    public $points;               ///< number of points the question is worth
    public $question_position;    ///< position of question in quiz

    /**
     * Constructor
     *
     * @param object $db -  database connection to quiz_questions table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create a quiz question
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    quiz_id=:quiz_id,
                    question_id=:question_id,
                    points=:points,
                    question_position=:question_position";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));
        $this->points = htmlspecialchars(strip_tags($this->points));
        $this->question_position = htmlspecialchars(strip_tags($this->question_position));

        // $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":quiz_id", $this->quiz_id);
        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":points", $this->points);
        $stmt->bindParam(":question_position", $this->question_position);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Read all quiz questions for a particular quiz id
     *
     * @return PDOStatement in ascending question position order
     */
    public function readAll()
    {
        $query = "SELECT
                    qs.*, qq.points, qq.question_position
                FROM
                    (
                    SELECT
                        *
                    FROM
                        questions
                    WHERE
                        id IN(
                        SELECT
                            quiz_questions.question_id
                        FROM
                            quiz_questions
                        WHERE
                            quiz_id=:quiz_id
                    )
                ) AS qs
                JOIN quiz_questions qq ON
                    qs.id = qq.question_id
                ORDER BY
                    qq.question_position ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $this->quiz_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all quiz questions for a particular quiz id
     *
     * @return PDOStatement in random order
     */
    public function readAllRandom()
    {
        $query = "SELECT
                    qs.*, qq.points, qq.question_position
                FROM
                    (
                    SELECT
                        *
                    FROM
                        questions
                    WHERE
                        id IN(
                        SELECT
                            quiz_questions.question_id
                        FROM
                            quiz_questions
                        WHERE
                            quiz_id=:quiz_id
                    )
                ) AS qs
                JOIN quiz_questions qq ON
                    qs.id = qq.question_id
                ORDER BY
                    RAND()";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $this->quiz_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all quiz questions and their question bank(s) for all questions in quiz
     *
     * @return PDOStatement
     */
    public function readAllWithQuestionBanks()
    {
        $query = "SELECT
                    qq.question_text, qb.bank_name
                FROM
                    (
                    SELECT
                        question_text, bank_id
                    FROM
                        questions
                    WHERE
                        id IN(
                        SELECT
                            quiz_questions.question_id
                        FROM
                            quiz_questions
                        WHERE
                            quiz_id=:quiz_id
                    )
                ) AS qq
                JOIN question_bank qb ON qq.bank_id=question_bank.id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $this->quiz_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read one quiz question for a quiz and
     * assign values to properties quiz_id, question_id
     *
     * @return void
     */
    public function readOne()
    {
        $query = "SELECT
                    *
                FROM
                    questions
                WHERE
                    id=:question_id
                LIMIT
                    0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $this->question_id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->quiz_id = $row['quiz_id'];
        $this->question_id = $row['question_id'];
    }

    /**
     * Delete the quiz question
     *
     * @return bool true if delete successful, otherwise false
     */
    public function delete()
    {
        $query = "DELETE FROM
                    " . $this->table_name . "
                WHERE
                    quiz_id=:quiz_id AND question_id=:question_id";

        // sanitize
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $this->quiz_id);
        $stmt->bindParam(':question_id', $this->question_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }
}
