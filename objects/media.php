<?php

/**
 * Media Object
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
 * Media Class
 *
 * Provides database I/O (CRUD) for the media table.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Media
{
    private $conn;
    private $table_name = "media";

    private $max_file_size = 256000000;  ///< 256M in bytes, be sure to set max_file_size for $(".file-uploader").pluploadQueue in add_files.php
    private $allowed_file_types = array("jpg","pdf","png","gif","mp3","wav","mp4","wmv","ogg","mpeg");  ///< be sure to set extensions: for $(".file-uploader").pluploadQueue in add_files.php

    public $id;                   ///< guid, primary key
    public $category_id;          ///< don't think this is being used here
    public $topic_id;             ///< don't think this is being used here
    public $course_id;            ///< course id this media belongs to
    public $lesson_id;            ///< lesson id this media belongs to
    public $parent_dir;           ///< this media's parent directory
    public $src_path;             ///< this media's full file path and file name
    public $order_pos;            ///< the order position of this media
    public $icon;                 ///< the file name of the icon used to represent this media
    public $display_name;         ///< the name displayed for this media
    public $access_id;            ///< access level id - relates to access_levels table
    public $admin_id;             ///< admin level id - relates to admin_levels table
    public $media_dir_path;       ///< don't think this is being used here
    public $file_type;            ///< the file extension for this media
    public $required;             ///< the number of media required to  be viewed
    public $student_id;           ///< the student's id


    /**
     * Constructor
     *
     * @param object $db -  database connection to media table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Move uploaded media file to the server and
     * if successful, insert record into media table
     *
     * @return void Output notification to user.
     */
    public function moveUploadedFile()
    {

        $result_message = "Upload failed";
        $result_color = "warning";
        $filename = "Upload failed";
        $file_upload_error_messages = "Upload failed";

        // if file is not empty, try to upload the file
        if (!empty($_FILES["file"]["tmp_name"])) {
            $filename = $_FILES["file"]["name"];
            $target_directory = "../media/" . $this->lesson_id;
            $target_file = $target_directory . '/' . $filename;
            $this->$file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            //ensure media directory exists (there is a check below that also ensures this dir exists)
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }

            // error message is empty
            $file_upload_error_messages = "";

            // make sure certain file types are allowed -- case insensitive
            if (!in_array(strtolower($this->$file_type), $this->allowed_file_types)) {
                $file_upload_error_messages .= "<p>Only jpg, pdf, png, gif, mp3, wav, mp4, wmv, ogg files are allowed.</p>";
            }

            // make sure file does not exist
            if (is_file($target_file)) {
                /*
                * The media directory is created when a new lesson is created.
                * Current directory is: ../media/N where N is db.lessons.id
                */

                $file_upload_error_messages .= "<p>File already exists. Try to change the file name or delete the uploaded one.</p>";
            }

            // make sure submitted file is not too large, can't be larger than 256 MB
            if ($_FILES['file']['size'] > $this->max_file_size) {
                $file_upload_error_messages .= "<p>File must be less than 256 MB in size.</p>";
            }

            // make sure the 'uploads' folder exists
            if (!is_dir($target_directory)) {
                $file_upload_error_messages .= "<p>UPLOAD ERROR: lesson media directory does not exist. Contact your administrator if you continue to experience this error.</p>";
            }

            // if $file_upload_error_messages is still empty,it means there are no errors, so try to upload the file
            if (empty($file_upload_error_messages)) {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    $result_message .= "<p>File successfully uploaded.</p>";
                    $result_color = 'success';
                } else {
                    $file_upload_error_messages .= "<p>File could not be uploaded.</p>";
                    $result_message .= "<p>File could not be uploaded.</p>";
                    $result_color = 'error';
                }
            } else {
                // it means there are some errors, so show them to user
                $result_message .= $file_upload_error_messages;
                $result_color = 'error';
            }
        }

        //if not errors, insert file data into the db
        if (!$file_upload_error_messages) {
            $this->parent_dir = "media/" . $this->lesson_id;
            $this->src_path = "media/" . $this->lesson_id . "/" . $_FILES['file']['name'];
            //default to 9999, the admin user will most likely reorder them later
            $this->order_pos = 9999;

            /*
            * the icon name is used in lesson_entry.php to determine the html tags to use.
            * Care should be taken when adjusting the arrays holding the extensions.
            * A section of code similar to the following is also in upload_sort.php.
            * If changes are made be sure those changes are made in upload_sort.php as well.
            * On refactor we can probably make one method in media that returns
            * the icon name and color when an extension is passed in.
            */

            //set icon type
            if (in_array(strtolower($this->$file_type), array("jpg","png","gif"))) {
                $this->icon = "icon-image2";
            } elseif (in_array(strtolower($this->$file_type), array("mp3","wav"))) {
                $this->icon = "icon-headphones";
            } elseif (in_array(strtolower($this->$file_type), array("mp4","ogg","mpeg","wmv","mpeg"))) {
                $this->icon = "icon-play";
            } elseif (in_array(strtolower($this->$file_type), array("pdf"))) {
                $this->icon = "icon-file-pdf";
            } elseif (in_array(strtolower($this->$file_type), array("html5"))) {
                $this->icon = "icon-html5";
            } else {
                //this is bad if you make it to here
                $this->icon = 'icon-question3';
            }

            $this->display_name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);

            /*
            TEAM: are we going to dynamically set access and admin nums on these
            or default to 1. How we display categories, topics, courses, lessons etc.
            is a topic we have not fully fledged out.
            */

            $this->access_id = 1;
            $this->admin_id = 1;

            $this->create();
        }

        //notify user
        $result_array = array('filename' => $filename,'noty_color' => $result_color,'msg' => $result_message);
        echo json_encode($result_array, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Insert/create media record
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        $query = "INSERT INTO media SET
                id = ?,
                course_id = ?,
                lesson_id = ?,
                parent_dir = ?,
                src_path = ?,
                order_pos = ?,
                icon = ?,
                display_name = ?,
                access_id = ?,
                admin_id = ?";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());

        // sanitize
        $this->course_id = htmlspecialchars(strip_tags($this->course_id));
        $this->lesson_id = htmlspecialchars(strip_tags($this->lesson_id));
        $this->parent_dir = htmlspecialchars(strip_tags($this->parent_dir));
        //we do not want to escape the source path.
        //$this->src_path=htmlspecialchars(strip_tags($this->src_path));
        $this->order_pos = htmlspecialchars(strip_tags($this->order_pos));
        $this->icon = htmlspecialchars(strip_tags($this->icon));
        $this->display_name = htmlspecialchars(strip_tags($this->display_name));
        $this->access_id = htmlspecialchars(strip_tags($this->access_id));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->course_id);
        $stmt->bindParam(3, $this->lesson_id);
        $stmt->bindParam(4, $this->parent_dir);
        $stmt->bindParam(5, $this->src_path);
        $stmt->bindParam(6, $this->order_pos);
        $stmt->bindParam(7, $this->icon);
        $stmt->bindParam(8, $this->display_name);
        $stmt->bindParam(9, $this->access_id);
        $stmt->bindParam(10, $this->admin_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Read all media for particular course id
     *
     * @return PDOStatement in ascending order_pos order
     */
    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table_name . "
        WHERE course_id=?
        ORDER BY order_pos ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->course_id);

        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all media for particular course id and lesson id
     *
     * @return PDOStatement in ascending order_pos, display_name order
     */
    public function readAllByCourseAndLessonId()
    {

        $query = "SELECT * FROM " . $this->table_name . "
        WHERE course_id=? AND lesson_id=?
        ORDER BY order_pos, display_name ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->course_id);
        $stmt->bindParam(2, $this->lesson_id);

        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all display names
     *
     * @return PDOStatement in ascending display_name order
     */
    public function read()
    {
        $query = "SELECT
                    id, display_name
                FROM
                    " . $this->table_name . "
                ORDER BY
                    display_name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read source path for this media id and
     * assign values to properties src_path
     *
     * @return void
     */
    public function readOne()
    {
        $query = "SELECT src_path
		FROM " . $this->table_name . "
				WHERE id = ?
				LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        // assign values to object properties
        $this->src_path = $row['src_path'];
    }

    /**
     * Read completed status from media_progress for this student and media
     *
     * @return string  html markup signifying finished or not
     */
    public function checkLessonCompletion()
    {
        $query = "SELECT completed FROM media_progress
                WHERE media_id = ? AND student_id = ?
                LIMIT 0, 1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->student_id);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($stmt->rowCount() && $row['completed']) {
            //this exact html is used in tracking_functions as well
            return '<i class="icon-checkmark4 ml-2 text-success"> Finished!</i>';
        } else {
            return '';
        }
    }


    /**
     *  Count the number of course requirements (media, quizzes for the lessons linked to this course)
     *
     * @return int of the total number of course requirements
     */
    public function countRequirements()
    {
        //get quiz count
        $query = "SELECT
						COUNT(*) AS total_rows
					FROM
						quizzes
					WHERE
						lesson_id=:lesson_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $quizCount = $row['total_rows'];

        //get media count
        $query = "SELECT
                        COUNT(*) AS total_rows
                    FROM
                        media
                    WHERE
                        lesson_id=:lesson_id AND icon = 'icon-headphones'
                    OR
                        lesson_id=:lesson_id AND icon = 'icon-play'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $mediaCount = $row['total_rows'];

        return $quizCount + $mediaCount;

    }

    /**
     * Count the number of completed lesson requirements
     *
     * @return int as the total number completed requirements
     */
    public function countCompletedRequirements()
    {

        $query = "SELECT
					COUNT(completed) AS total_rows
				FROM
					media_progress
				WHERE
					lesson_id=:lesson_id AND student_id=:student_id AND deleted=0 AND completed=1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->bindParam(':student_id', $this->student_id);

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $completed = $row['total_rows'];

        return $completed;
    }

    /**
     * Update order position and display name
     *
     * @return bool true if update successful, otherwise false
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
            SET order_pos = :order_pos,
            display_name = :display_name
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':order_pos', $this->order_pos);
        $stmt->bindParam(':display_name', $this->display_name);
        $stmt->bindParam(':id', $this->id);

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
        $query = "UPDATE " . $this->table_name . "
            SET required = :required
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':required', $this->required);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete this media record
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
     * Delete media for particular lesson id
     *
     * @return bool true if delete successful, otherwise false
     */
    public function deleteByLessonId()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE lesson_id = ?";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->lesson_id));

        $stmt->bindParam(1, $this->lesson_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete media record for particular course id
     *
     * @return bool true if delete successful, otherwise false
     */
    public function deleteMediaByCourseId()
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
     * Calculate percent of completed requirements and show progress bar
     *
     * @return string  html markup signifying progress bar
     */
    public function showMediaProgressbar()
    {

        $completed = $this->countCompletedRequirements();
        $required = $this->countRequirements();

        if ($completed && $required) {
            $percent = round(($completed / $required) * 100, 1);
            $bar_color = 'bg-success';
            $span_msg = $percent . '% Complete';
            $width = $percent;
        } elseif (!$required) {
            $percent = 100;
            $bar_color = 'bg-primary';
            $span_msg = 'Open lesson | No requirements.';
            $width = $percent;
        } else {
            $percent = 0;
            $bar_color = 'bg-warning';
            $span_msg = $percent . '% Complete';
            $width = 100;
        }

        $progress_bar = "<div class='progress rounded-round inline'>";
        $progress_bar .= "<div id='lesson-progress-$this->lesson_id' class='progress-bar $bar_color' data-required='$required' data-completed='$completed' data-reload='0' style='width:$width%'>";
        $progress_bar .= "<span>$span_msg</span></div></div>";

        return $progress_bar;
    }

    /**
     * Validate that this file exists on the file system
     *
     * @param string $path file path of media file
     *
     * @return bool true if file exists, otherwise false
     */
    public function ensureLessonMediaFile($path)
    {

        /*

        It is absolutely imperative that we ensure that we are deleting a lesson file in ./media/N.

        "/^\.\/media\/.+\/.+$/" finds:
        . at the start of the line
        followed by /
        followed by media
        followed by /
        folwowed by one or more characters
        followed by /
        folwowed by one or more characters at the end of the line
        */

        if (preg_match("/^\.\/media\/.+\/.+$/", $path)) {
            //echo "A match was found.";
            return true;
        } else {
            //echo "A match was not found.";
            return false;
        }
    }

    /**
     * Validate that this file path exists
     *
     * @param string $path file path of media file
     *
     * @return bool true if file path exists, otherwise false
     */
    public function ensureLessonMediaDir($path)
    {

        /*

        It is absolutely imperative that we ensure that we are deleting a lesson directory in ./media.
        If we pass ./ by itself to deleteMediaFilesByLesson() we will end up deleting the root directory of this program.

        "/^\.\/media\/.+$/" finds:
        . at the start of the line
        followed by /
        followed by media
        followed by /
        followed by any character one or more times at the end of the line
        */

        if (preg_match("/^\.\/media\/.+$/", $path)) {
            //echo "A match was found.";
            return true;
        } else {
            //echo "A match was not found.";
            return false;
        }
    }

    /**
     * Delete media files (for this lesson) from file system
     *
     * @param string $path file path of media file
     *
     * @return bool true if delete successful, otherwise false
     */
    public function deleteMediaFilesByLesson($path)
    {

        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file) {
                $this->deleteMediaFilesByLesson(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        } elseif (is_file($path) === true) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Delete media file from the file system
     *
     * @return bool true if delete successful, otherwise false
     */
    public function deleteMediaFileBySrcPath()
    {

        $path = './' . $this->src_path;

        //ensure the media file exists and matches the correct pattern
        if (is_file($path) && $this->ensureLessonMediaFile($path)) {
            return unlink($path);
        }
        return false;
    }
}
