<?php

/**
 * Course Object
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
 * Course Class
 *
 * Provide database I/O (CRUD) for the courses table. Handle file/directory management for course image.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Course
{
    private $conn;
    private $table_name = "courses";

    // object properties
    public $id;                   ///< guid, primary key
    public $new_id;               ///< used for naming/making directory that holds media for this course
    public $course_name;          ///< the name of the course
    public $course_desc;          ///< the description of the course
    //public $category_id;          ///< don't think this is used here
    public $topic_id;             ///< guid of the topic, relates to topics
    public $new_topic_id;         ///< not sure what this is used for
    public $iframe;               ///< not sure what this is used for
    public $access_id;            ///< access level id - relates to access_levels table
    // public $admin_id;             ///< admin level id - relates to admin_levels table
    public $course_img;           ///< file name of image used for course
    public $course_img_url;       ///< file path of course image which will be saved to image_dir
    public $old_course_img;       ///< file name of old image being replaced as part of update
    public $image_dir = 'media/images/courses/'; ///< file path to save course image to, append id to get the course image dir path

    /**
     * Constructor
     *
     * @param object $db -  database connection to categories table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create course record and
     * assign new_id to be used for creating this course's media/images directory
     *
     * @return bool true if insert successful, otherwise false
     */
    public function create()
    {
        $query = "INSERT INTO courses SET
                id = ?,
                course_name = ?,
                course_desc = ?,
                topic_id = ?,
                course_img = ?,
                iframe = ?,
                access_id = ?";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());

        // sanitize
        $this->course_name = htmlspecialchars(strip_tags($this->course_name));
        $this->course_desc = htmlspecialchars(strip_tags($this->course_desc));
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
        //$this->course_img=htmlspecialchars(strip_tags($this->course_img));
        $this->iframe = htmlspecialchars(strip_tags($this->iframe));
        $this->access_id = htmlspecialchars(strip_tags($this->access_id));

        $stmt->bindParam(1, $this->id);
        $stmt->bindParam(2, $this->course_name);
        $stmt->bindParam(3, $this->course_desc);
        $stmt->bindParam(4, $this->topic_id);
        $stmt->bindParam(5, $this->course_img);
        $stmt->bindParam(6, $this->iframe);
        $stmt->bindParam(7, $this->access_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            //set the new id (we need to create the directory that will hold the course image)
            $this->new_id = $this->id;
            return true;
        }

        return false;
    }

    /**
     * Read details of course for particular course id to be edited and
     * assign values to properties course_id, access_id, admin_id, course_name, course_desc, topic_id, course_img, iframe
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
        $this->course_id = $row['id'];
        $this->access_id = $row['access_id'];
        $this->course_name = $row['course_name'];
        $this->course_desc = $row['course_desc'];
        $this->topic_id = $row['topic_id'];
        $this->course_img = $row['course_img'];
        $this->iframe = $row['iframe'];
    }

    /**
     * Read course name for particular topic id
     *
     * @return PDOStatement in ascending course name order
     */
    public function readCoursesByTopicId()
    {
        $query = "SELECT * FROM " . $this->table_name . "
        WHERE topic_id = ?
        ORDER BY LOWER(course_name) ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->topicId);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all lessons for particular course id
     *
     * @return PDOStatement
     */
    public function readLessonByCourseId()
    {

        $query = "SELECT * FROM lessons WHERE course_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }

    /**
     * Read all cohorts for particular course id
     * 
     * @param guid $courseId course id
     *
     * @return array of cohort ids
     */
    public function readCohortsIdArrayByCourseId($courseId)
    {
        $query = "SELECT
                    cohorts.id
                FROM
                    " . $this->table_name . "
                JOIN cohorts ON courses.id=cohorts.course_id
                WHERE
                    courses.id=:course_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        $array = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $array[] = $id;
        }
        return $array;
    }

    /**
     * Read category id for particular course id
     * 
     * @param guid $courseId course id
     *
     * @return string category id
     */
    public function readCatIdByCourseId($courseId)
    {
        $query = "SELECT
                    categories.id cat_id
                FROM
                    " . $this->table_name . "
                JOIN topics ON courses.topic_id=topics.id
                JOIN categories ON topics.category_id=categories.id
                WHERE
                    courses.id=:course_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        extract($row);

        return $cat_id;
    }

    /**
     * Read quiz ids by course id
     * (quizzes for the lessons linked to this course)
     *
     * @return array of quiz ids
     */
    public function readQuizzesByCourseId()
    {
        $query = "SELECT
                    quizzes.id
                FROM
                    quizzes
                    JOIN lessons ON quizzes.lesson_id=lessons.id
                    JOIN courses ON lessons.course_id=courses.id
                WHERE
                    courses.id=:course_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':course_id', $this->id);

        $stmt->execute();
        $array = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            extract($row);
            $array[] = $id;
        }
        return $array;
    }

    /**
     * Update the course record
     *
     * @return bool true if update was successful, otherwise false
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET
            course_name = :course_name,
            course_desc = :course_desc,
            topic_id = :topic_id,
            course_img = :course_img,
            iframe = :iframe,
            access_id = :access_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->course_name = htmlspecialchars(strip_tags($this->course_name));
        $this->course_desc = htmlspecialchars(strip_tags($this->course_desc));
        $this->topic_id = htmlspecialchars(strip_tags($this->topic_id));
        //$this->course_img=htmlspecialchars(strip_tags($this->course_img));
        $this->iframe = htmlspecialchars(strip_tags($this->iframe));
        $this->access_id = htmlspecialchars(strip_tags($this->access_id));

        $stmt->bindParam(':course_name', $this->course_name);
        $stmt->bindParam(':course_desc', $this->course_desc);
        $stmt->bindParam(':topic_id', $this->topic_id);
        $stmt->bindParam(':course_img', $this->course_img);
        $stmt->bindParam(':iframe', $this->iframe);
        $stmt->bindParam(':access_id', $this->access_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Update the course's topic id with a new topic id
     *
     * @return bool true if update was successful, otherwise false
     */
    public function reassignCourseTopicId()
    {
        $query = "UPDATE " . $this->table_name . "
        SET topic_id = :new_topic_id
        WHERE topic_id = :topic_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':new_topic_id', $this->new_topic_id);
        $stmt->bindParam(':topic_id', $this->topic_id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Delete the course record
     *
     * @return bool true if delete was successful, otherwise false
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
     * Count the number of course requirements
     * (media, quizzes for the lessons linked to this course)
     *
     * @return PDORow of the total number of course requirements as TotalReq
     */
    public function countRequirements()
    {
        //get quiz count
        $query = "SELECT
                         COUNT(*) AS total_rows
                     FROM
                         quizzes
                     WHERE
                         quizzes.lesson_id IN (
                         SELECT
                             lessons.id
                         FROM
                             lessons
                         WHERE
                             lessons.course_id=:course_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $this->id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $quizCount = $row['total_rows'];
        //get media count
        $query = "SELECT
                        COUNT(*) AS total_rows
                    FROM
                        media
                    WHERE
                        course_id=:course_id AND icon = 'icon-headphones'
                    OR
                        course_id=:course_id AND icon = 'icon-play'";

        $stmt = $this->conn->prepare($query);
        //$stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->bindParam(':course_id', $this->id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $mediaCount = $row['total_rows'];
        return $quizCount + $mediaCount;

        // $query = "SELECT
        //             SUM(TotalReq.ReqCount) AS total_rows
        //         FROM
        //             (
        //             SELECT
        //                 COUNT(*) AS ReqCount
        //             FROM
        //                 quizzes
        //             WHERE
        //                 quizzes.lesson_id IN (
        //                 SELECT
        //                     lessons.id
        //                 FROM
        //                     lessons
        //                 WHERE
        //                     lessons.course_id=:course_id
        //             )
        //             UNION
        //             SELECT
        //                 COUNT(*)
        //             FROM
        //                 media
        //             WHERE
        //                 course_id=:course_id AND icon = 'icon-headphones'
        //             OR
        //                 course_id=:course_id AND icon = 'icon-play'
        //         ) AS TotalReq";

        // $stmt = $this->conn->prepare($query);

        // $stmt->bindParam(':course_id', $this->id);

        // $stmt->execute();
        // $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        // return $row['total_rows'];
    }

    /**
     * Count the number of completed course requirements
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
                    course_id=:course_id AND student_id=:student_id AND deleted = 0 AND completed = 1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':course_id', $this->id);
        $stmt->bindParam(':student_id', $_SESSION['user_id']);

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $completed = $row['total_rows'];

        return $completed;
    }

    /**
     * Create the file path of the course image,
     * the image directory that will hold the image file for this course.
     *
     * @return bool true if file path creation successful, otherwise false
     */
    public function createCourseImageDirectory()
    {
        if (!trim($this->new_id)) {
            return false;
        }

        $target_dir = $this->image_dir . $this->new_id;

        /*In theory this directory should not be in existence at this stage.
        However, if we merge update_course.php and create_course.php into one file
        then we need to ensure we are not trying to create a dir that is already
        in existence. mkdir will throw an error if the dir already exists.*/

        //ensure the dir does not already exist
        if (is_dir($this->image_dir . $this->new_id)) {
            return true;
        }

        //create the image directory that will hold the image file for this course.
        if (!mkdir($this->image_dir . $this->new_id, 0777, true)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Validate that the file path for writing the course image to exists
     *
     * @return bool true is file path exists, otherwise false
     */
    public function ensureCourseImageDirectory()
    {

        $path = './' . $this->image_dir . $this->id;

        /*
        ./media/images/courses/N.

        "/^\.\/media\/images\/courses\/.+$/" finds:
            find . at the start of the line
            followed by /
            followed by media
            followed by /
            followed by images
            followed by /
            followed by courses
            followed by /
            followed by any character except newline at the end of the line
        */

        if (preg_match("/^\.\/media\/images\/courses\/.+$/", $path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Write image to the file system after validating file path and deleting old image
     *
     * @return bool true if write successful, otherwise false
     */
    public function writeCourseImage()
    {

        //ensure we have image content to write
        if (!$this->course_img_url) {
            return false;
        }

        $filename = $this->course_img;
        $fullpath = './' . $this->image_dir . $this->new_id . '/' . $filename;

        //call to ensure we have an image directory to write to
        if (!$this->createCourseImageDirectory()) {
            return false;
        }

        //call delete old image
        $this->deleteOldCourseImage();

        //write new course image
        $img_content = file_get_contents($this->course_img_url);
        //fopen returns a file pointer resource on success, or FALSE on error.
        $fp = fopen($fullpath, 'w');
        if (!$fp) {
            return false;
        }
        //fwrite() returns the number of bytes written, or FALSE on error.
        if ((!fwrite($fp, $img_content))) {
            return false;
        }
        fclose($fp);
        return true;
    }

    /**
     * Delete the old course image from the file system
     *
     * @return bool true if delete successful, otherwise false
     */
    public function deleteOldCourseImage()
    {

        if ($this->old_course_img) {
            $old_path = './' . $this->image_dir . $this->new_id . '/' . $this->old_course_img;
            if (is_file($old_path)) {
                return unlink($old_path);
            }
        }
    }

    /**
     * Remove the file path of the course image
     *
     * @param string $path the file path of the course image
     *
     * @return bool true if removal of file path successfule, otherwise fals
     */
    public function deleteCourseImageDirectory($path)
    {

        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file) {
                $this->deleteCourseImageDirectory(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        } elseif (is_file($path) === true) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Calculate the percent of completed requirements to total required
     *
     * @return int representing percent
     */
    public function calculateCourseAvg()
    {

        $completed = $this->countCompletedRequirements();
        $required = $this->countRequirements();

        if ((!$required)) {
            return 0;
        }

        $percent = round(($completed / $required) * 100, 1);

        return $percent;
    }
}
