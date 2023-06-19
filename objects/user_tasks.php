<?php

/**
 * Tasks Object
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
 * Tasks Class
 *
 * Provide database I/O (CRUD) for the user_task_list table
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */


class userTasks
{
    private $conn;
    private $table_name = "user_task_list";

    public $id;                   ///< guid, primary key
    public $task;                 ///< user's task
    public $task_id;              ///< task id generated in js
    public $checked;              ///< determines if user's task is checked off or not

    /**
     * Constructor
     *
     * @param database $db user_task_list Instance of the Database Class
     *
     * @return PDO An instance of the PDO Class for the user_task_list table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }



    /**
     * Read all user's tasks
     *
     * @return PDOStatement in ascending datetime order
     */
    public function readTasks()
    {
        $query = "SELECT * FROM " . $this->table_name . "
        WHERE id= :id ORDER BY timestamp ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        
        return $stmt;
    }



    /**
     * Insert/create user's task
     *
     * @return bool true if insert successful, otherwise false
     */
    public function addUserTask()
    {
        $query = "INSERT INTO user_task_list
                SET id= :id,
                task_id= :task_id,
                task = :task,
                checked = :checked";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->task_id = htmlspecialchars(strip_tags($this->task_id));
        $this->task = htmlspecialchars(strip_tags($this->task));
        $this->checked = htmlspecialchars(strip_tags($this->checked));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':task_id', $this->task_id);
        $stmt->bindParam(':task', $this->task);
        $stmt->bindParam(':checked', $this->checked);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Remove user's task
     *
     * @return bool true if removal successful, otherwise false
     */
    public function removeUserTask()
    {
        $query = "DELETE FROM user_task_list
                WHERE id= :id AND
                task_id= :task_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':task_id', $this->task_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    

    /**
     * Check if user's task as complete
     *
     * @return int 0 for unchecked, 1 for checked
     */
    public function getTaskCompletion()
    {
        $query = "SELECT checked FROM " . $this->table_name . "
                WHERE id = :id AND task_id = :task_id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':task_id', $this->task_id);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $checked = $row['checked'];

        return $checked;
    }


    /**
     * Mark user's task as complete
     *
     * @return bool true if toggle completed successful, otherwise false
     */
    public function completeUserTask()
    {
        $query = "UPDATE " . $this->table_name . "
                SET checked = :checked
                WHERE id = :id AND task_id = :task_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':task_id', $this->task_id);
        $stmt->bindParam(':checked', $this->checked);
    
        $stmt->execute();
    }

}

?>