<?php
/**
 * File Doc Comment Short Description
 *
 * PHP version 7.2.5
 *
 * @category  Dashboard
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__) . '/../../objects/GUID.php';

class Dashboard
{

    // database connection and table name
    private $conn;

    /**
     * Constructor
     *
     * @param object $db -  database connection to database
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * read number of users assigned to each admin level
     *
     * @return PDOStatement
     */
    public function usersByAdminLevel()
    {

        $query = "SELECT admin_id as number, admin_name as name,
        COUNT(admin_id) AS count
        FROM users JOIN admin_levels ON users.admin_id = admin_levels.id
        GROUP BY admin_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }



    /**
     * count number of categories
     *
     * @return PDOStatement
     */
    public function countNumOfCategories()
    {

        $query = "SELECT COUNT(id) AS total_count FROM categories";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * count number of topics
     *
     * @return PDOStatement
     */
    public function countNumOfTopics()
    {

        $query = "SELECT COUNT(id) AS total_count FROM topics";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * count number of courses
     *
     * @return PDOStatement
     */
    public function countNumOfCourses()
    {

        $query = "SELECT COUNT(id) AS total_count FROM courses";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    
    /**
     * count number of lessons
     *
     * @return PDOStatement
     */
    public function countNumOfLessons()
    {

        $query = "SELECT COUNT(id) AS total_count FROM lessons";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * get the sum total in seconds for videos watched
     *
     * @return PDOStatement
     */
    public function totalSecsVideo()
    {
 
        $query = "SELECT SUM(current_pos) AS total_secs
        FROM media_progress
        WHERE file_type=:file_type";
 
        $stmt = $this->conn->prepare($query);

        $file_type = 'video';
        $stmt->bindParam(":file_type", $file_type);
        $stmt->execute();
 
        return $stmt;
    }

    /**
     * get the sum total in seconds for audio files listened to
     *
     * @return PDOStatement
     */
    public function totalSecsAudio()
    {
 
        $query = "SELECT SUM(current_pos) AS total_secs
        FROM media_progress
        WHERE file_type=:file_type";
 
        $stmt = $this->conn->prepare($query);

        $file_type = 'audio';
        $stmt->bindParam(":file_type", $file_type);
        $stmt->execute();
 
        return $stmt;
    }

    /**
     * get the total number of users logged in to the site
     *
     * @return PDOStatement
     */
    public function totalUsersLoggedIn()
    {

        $query = "SELECT COUNT(id) AS total_count
        FROM users
        WHERE logged_in > SUBTIME(CURRENT_TIMESTAMP, '00:30:00.000000')";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
 
        return $stmt;
    }
}
