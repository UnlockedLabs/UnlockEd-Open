<?php

/**
 * Lesson Object
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
 * Lesson Class
 *
 * Provide database I/O (CRUD) for the lessons table.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Lesson
{
    private $conn;
    private $table_name = "lessons";

    public $id;                   ///< guid, primary key
    public $lesson_name;          ///< the name of the lesson
    public $category_id;          ///< don't think this is used here
    public $course_id;            ///< guid of the course, relates to courses
    public $topic_id;             ///< don't think this is used here
    public $editor_html;          ///< html text for lesson instructions
    public $access_id;            ///< access level id - relates to access_levels table
    public $admin_id;             ///< admin level id - relates to admin_levels table
    public $media_dir;            ///< the file path for the media directory
    public $new_lesson_id;        ///< not sure what this is used for

    /**
     * Constructor
     *
     * @param object $db -  database connection to lessons table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create the lesson record and
     * assign new_lesson_id to be used for creating this lesson's media/images directory
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        // create the lesson
        // insert query
        $query = "INSERT INTO lessons SET
                id = ?,
                lesson_name = ?,
                course_id = ?,
                editor_html = ?,
                media_dir = ?,
                access_id = ?,
                admin_id = ?";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());

        // sanitize
        $this->lesson_name = htmlspecialchars(strip_tags($this->lesson_name));
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->access_id = htmlspecialchars(strip_tags($this->access_id));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->lesson_name);
        $stmt->bindParam(3, $this->course_id);
        $stmt->bindParam(4, $this->editor_html);
        $stmt->bindParam(5, $this->media_dir);
        $stmt->bindParam(6, $this->access_id);
        $stmt->bindParam(7, $this->admin_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            //get and set the id of newly created lesson
            $this->new_lesson_id = $this->id;

            //the media dir will be the lessons.id value
            $this->media_dir = 'media/' . $this->new_lesson_id;

            //returns true of the dir is created, false if not
            return $this->createLessonMediaDirectory();
        }

        return false;
    }

    /**
     * Read lesson for particular lesson id and
     * assign values to properties lesson_name, course_id, access_id, media_dir, admin_id, editor_html
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
        $this->lesson_name = $row['lesson_name'];
        $this->course_id = $row['course_id'];
        $this->access_id = $row['access_id'];
        $this->media_dir = $row['media_dir'];
        $this->admin_id = $row['admin_id'];
        $this->editor_html = $row['editor_html'];
    }

    /**
     * Read lesson names for particular course id
     *
     * @return PDOStatement
     */
    public function readLessonNamesByCourseId()
    {

        $query = "SELECT lesson_name FROM lessons WHERE course_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->course_id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Update the lesson record
     *
     * @return bool true if update successful, otherwise false
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
        SET
                lesson_name = :lesson_name,
                access_id = :access_id,
                admin_id = :admin_id
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':lesson_name', $this->lesson_name);
        $stmt->bindParam(':access_id', $this->access_id);
        $stmt->bindParam(':admin_id', $this->admin_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete lesson
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
     * Delete lesson for particular course id
     *
     * @return bool true if delete successful, otherwise false
     */
    public function deleteLessonsByCourseId()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE course_id = ?";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->course_id));

        $stmt->bindParam(1, $this->course_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Create file path of media directory for this lesson
     *
     * @return bool true if creation of file path successful, otherwise false
     */
    public function createLessonMediaDirectory()
    {

        /*
            TEAM: be sure to delete the numbered directories:

                ./media/N
                ./media/images/courses/N

            after you repopulate your db. If you don't then
            the previously uploaded media files
            in these directories become orphans.

        */

        if (is_dir($this->media_dir)) {
            return true;
        }

        //create the media directory that will hold the media files for this lesson.
        if (!mkdir($this->media_dir, 0777, true)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Update the editor html for this lesson
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateEditorHtml()
    {
        // update editor html
        $query = "UPDATE lessons SET
                editor_html = :editor_html
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':editor_html', $this->editor_html);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Count the number of lessons for a particular course
     *
     * @return int total count
     */
    public function countByCourseId()
    {
        $query = "SELECT COUNT(*) as total_rows FROM  $this->table_name WHERE course_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->course_id);

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];

        return $total_rows;
    }

    /**
     * Check if lesson exists
     *
     * For the time being we are not going to perform this check??
     *
     * @return false
     */
    public function lessonExists()
    {

        //for the time being we are not going to perform this check
        return false;

        // check if lesson exists
        // select single record query
        $query = "SELECT lesson_name FROM " . $this->table_name . "
                WHERE LOWER(lesson_name) = ? AND course_id = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        //we are lowering to prevent topics like lesson 1 and Lessson 1
        $lesson_name_lower = strtolower($this->lesson_name);
        $stmt->bindParam(1, $lesson_name_lower);
        $stmt->bindParam(2, $this->course_id);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);


        if (isset($row['lesson_name'])) {
            return true;
        } else {
            return false;
        }
    }
}
