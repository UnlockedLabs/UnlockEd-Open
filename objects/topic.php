<?php

/**
 * Topic Object
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
  * Topic Class
  *
  * Provides database I/O (CRUD) for the topics table
  *
  * @category Objects
  * @package  UnlockED
  * @author   UnlockedLabs <developers@unlockedlabs.org>
  * @license  https://www.gnu.org/licenses/gpl.html GPLv3
  * @link     http://unlockedlabs.org
  */
class Topic
{
    private $conn;
    private $table_name = "topics";

    public $id;                   ///< guid, primary key
    public $topic_name;           ///< name of the topic
    public $topic_url;            ///< aka iframe
    public $category_id;          ///< not sure what this is used for
    public $description;          ///< not sure what this is used for
    public $created;              ///< not sure what this is used for

    /**
     * Constructor
     *
     * @param object $db -  database connection to topics table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create topic
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        $query = "INSERT INTO topics
                SET 
                    id = ?, 
                    topic_name = ?, 
                    category_id = ?, 
                    iframe = ?";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());

        // sanitize
        $this->topic_name = htmlspecialchars(strip_tags($this->topic_name));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->topic_url = htmlspecialchars(strip_tags($this->topic_url));

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->topic_name);
        $stmt->bindParam(3, $this->category_id);
        $stmt->bindParam(4, $this->topic_url);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Validate that topic exists
     *
     * @return bool true if topic exists, otherwise false
     */
    public function topicExists()
    {
        $query = "SELECT topic_name FROM " . $this->table_name . "
        WHERE LOWER(topic_name) = ? AND category_id = ?
        LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        //we are lowering to prevent topics like Ohio University and ohio unversity
        $topic_name_lower = strtolower($this->topic_name);
        $stmt->bindParam(1, $topic_name_lower);
        $stmt->bindParam(2, $this->category_id);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (isset($row['topic_name'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Read topic and
     * assign values to properties topic_name and topic_url
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
        $this->topic_name = $row['topic_name'];
        $this->topic_url = $row['iframe'];
    }

    /**
     * Update topic's category id
     *
     * @return bool true if update successful, otherwise false
     */
    public function reassignTopicCategoryId()
    {

        $query = "UPDATE " . $this->table_name . "
                SET category_id = :new_category_id
                WHERE category_id = :category_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':new_category_id', $this->new_category_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Update topic
     *
     * @return bool true if update successful, otherwise false
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                SET topic_name = :topic_name,
                iframe = :topic_url
                WHERE id = :id";


        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':topic_name', $this->topic_name);
        $stmt->bindParam(':topic_url', $this->topic_url);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete topic
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
}
