<?php

/**
 * Media Progress Object
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
 * MediaProgress Class
 *
 * Provides database I/O (CRUD) for the media_progress table.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class MediaProgress
{
    private $conn;
    private $table_name = "media_progress";

    public $id;                   ///< guid, primary key
    public $category_id;          ///< don't think this is being used here
    public $topic_id;             ///< don't think this is being used here
    public $course_id;            ///< course id this media belongs to
    public $lesson_id;            ///< lesson id this media belongs to
    public $parent_dir;           ///< don't think this is being used here
    public $src_path;             ///< don't think this is being used here
    public $order_pos;            ///< don't think this is being used here
    public $icon;                 ///< don't think this is being used here
    public $display_name;         ///< don't think this is being used here
    public $access_id;            ///< access level id - relates to access_levels table
    public $admin_id;             ///< admin level id - relates to admin_levels table
    public $media_dir_path;       ///< don't think this is being used here
    public $file_type;            ///< the type of media file, video, etc.
    public $current_pos;          ///< viewed up to marker, in seconds
    public $duration;             ///< length of media, in seconds
    public $student_id;           ///< the id of the student logged in
    public $media_id;             ///< the id of the media, relates to media
    public $file_location;        ///< file path of media
    public $file_name;            ///< file name of media
    public $completed;            ///< whether or not media has been completed
    public $reflection;           ///< not sure what this is for
    public $deleted;              ///< whether or not media has been deleted from lesson
    public $required;             ///< whether or not media is required viewing/reading

    /**
     * Constructor
     *
     * @param object $db -  database connection to media_progress table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create media progress record
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {

        $query = "INSERT INTO $this->table_name SET
			id = ?,
			course_id = ?,
			lesson_id = ?,
			student_id = ?,
			media_id = ?,
			file_location = ?,
			file_type = ?,
			file_name = ?,
			duration = ?,
			current_pos = ?,
			completed = ?,
			reflection = ?,
			deleted = ?,
			required = ?";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());

        // sanitize
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->lesson_id = htmlspecialchars(strip_tags($this->lesson_id));
        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $this->media_id = htmlspecialchars(strip_tags($this->media_id));
        $this->file_location = htmlspecialchars(strip_tags($this->file_location));
        $this->file_type = htmlspecialchars(strip_tags($this->file_type));
        $this->file_name = htmlspecialchars(strip_tags($this->file_name));
        $this->duration = htmlspecialchars(strip_tags($this->duration));
        $this->current_pos = htmlspecialchars(strip_tags($this->current_pos));
        $this->completed = htmlspecialchars(strip_tags($this->completed));
        $this->reflection = htmlspecialchars(strip_tags($this->reflection));
        $this->deleted = htmlspecialchars(strip_tags($this->deleted));
        $this->required = htmlspecialchars(strip_tags($this->required));

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->course_id);
        $stmt->bindParam(3, $this->lesson_id);
        $stmt->bindParam(4, $this->student_id);
        $stmt->bindParam(5, $this->media_id);
        $stmt->bindParam(6, $this->file_location);
        $stmt->bindParam(7, $this->file_type);
        $stmt->bindParam(8, $this->file_name);
        $stmt->bindParam(9, $this->duration);
        $stmt->bindParam(10, $this->current_pos);
        $stmt->bindParam(11, $this->completed);
        $stmt->bindParam(12, $this->reflection);
        $stmt->bindParam(13, $this->deleted);
        $stmt->bindParam(14, $this->required);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Check to see if this user has yet reviewed this media.
     *
     * Read id for particular student id and media id to validate existence
     *
     * @return bool true if record exists, otherwise false
     */
    public function rowExists()
    {

        $query = "SELECT id FROM $this->table_name
		WHERE student_id = ? AND media_id = ? LIMIT 1";

        // prepare and execute query statement

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->student_id);
        $stmt->bindParam(2, $this->media_id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Read media progress records for particular student id and media id.
     *
     * @return PDOStatement
     */
    public function readRowByStudentAndMediaId()
    {

        $query = "SELECT * FROM $this->table_name
		WHERE student_id = ? AND media_id = ? LIMIT 1";

        // prepare and execute query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->student_id);
        $stmt->bindParam(2, $this->media_id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Update media progress record
     *
     * @return bool true if update successful, otherwise false
     */
    public function update()
    {

        $query = "UPDATE $this->table_name SET
        course_id = ?,
        lesson_id = ?,
        student_id = ?,
        media_id = ?,
        file_location = ?,
        file_type = ?,
        file_name = ?,
        duration = ?,
        current_pos = ?,
        completed = ?,
        reflection = ?,
        deleted = ?,
        required = ?
        WHERE media_id = ? AND student_id = ?";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->lesson_id = htmlspecialchars(strip_tags($this->lesson_id));
        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $this->media_id = htmlspecialchars(strip_tags($this->media_id));
        $this->file_location = htmlspecialchars(strip_tags($this->file_location));
        $this->file_type = htmlspecialchars(strip_tags($this->file_type));
        $this->file_name = htmlspecialchars(strip_tags($this->file_name));
        $this->duration = htmlspecialchars(strip_tags($this->duration));
        $this->current_pos = htmlspecialchars(strip_tags($this->current_pos));
        $this->completed = htmlspecialchars(strip_tags($this->completed));
        $this->reflection = htmlspecialchars(strip_tags($this->reflection));
        $this->deleted = htmlspecialchars(strip_tags($this->deleted));
        $this->required = htmlspecialchars(strip_tags($this->required));

        $stmt->bindParam(1, $this->course_id);
        $stmt->bindParam(2, $this->lesson_id);
        $stmt->bindParam(3, $this->student_id);
        $stmt->bindParam(4, $this->media_id);
        $stmt->bindParam(5, $this->file_location);
        $stmt->bindParam(6, $this->file_type);
        $stmt->bindParam(7, $this->file_name);
        $stmt->bindParam(8, $this->duration);
        $stmt->bindParam(9, $this->current_pos);
        $stmt->bindParam(10, $this->completed);
        $stmt->bindParam(11, $this->reflection);
        $stmt->bindParam(12, $this->deleted);
        $stmt->bindParam(13, $this->required);
        $stmt->bindParam(14, $this->media_id);
        $stmt->bindParam(15, $this->student_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Update required status of this media
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateRequired()
    {
        // update the media
        $query = "UPDATE " . $this->table_name . "
            SET required = :required
            WHERE media_id = :media_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':required', $this->required);
        $stmt->bindParam(':media_id', $this->media_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Update/set deleted flag to deleted (1) for the deleted media
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateDeletedColumn()
    {
        $query = "UPDATE " . $this->table_name . "
				SET deleted = :deleted
				WHERE media_id = :media_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':deleted', $this->deleted);
        $stmt->bindParam(':media_id', $this->media_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Update/set deleted flag to deleted (1) for the deleted lesson
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateDeletedColumnLesson()
    {
        $query = "UPDATE " . $this->table_name . "
				SET deleted = :deleted
				WHERE lesson_id = :lesson_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':deleted', $this->deleted);
        $stmt->bindParam(':lesson_id', $this->lesson_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Update/set deleted flag to deleted (1) for the deleted course
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateDeletedColumnCourse()
    {
        $query = "UPDATE " . $this->table_name . "
				SET deleted = :deleted
				WHERE course_id = :course_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':deleted', $this->deleted);
        $stmt->bindParam(':course_id', $this->course_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }
}
