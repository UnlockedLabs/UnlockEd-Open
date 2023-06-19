<?php

/**
 * Analytics Object
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
 * Analytic Class
 * 
 * Handles analytics, when it is completed.
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Analytic
{

    // database connection and table name
    private $conn;
    private $table_name = "analytics";

    // object properties
    public $id;
    public $category_id;
    public $course_id;
    public $topic_id;
    public $media_id;
    public $category_name;
    public $timestamp;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // will upload image file to server
    function uploadPhoto()
    {

        $result_message = "";

        // now, if image is not empty, try to upload the image
        if (!empty($_FILES["image"]["tmp_name"])) {
            // sha1_file() function is used to make a unique file name
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->image;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error message is empty
            $file_upload_error_messages = "";

            // make sure that file is a real image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                // submitted file is an image
            } else {
                $file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
            }

            // make sure certain file types are allowed
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($file_type, $allowed_file_types)) {
                $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
            }

            // make sure file does not exist
            if (file_exists($target_file)) {
                $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
            }

            // make sure submitted file is not too large, can't be larger than 1 MB
            if ($_FILES['image']['size'] > (1024000)) {
                $file_upload_error_messages .= "<div>Image must be less than 1 MB in size.</div>";
            }

            // make sure the 'uploads' folder exists
            // if not, create it
            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }

            // if $file_upload_error_messages is still empty
            if (empty($file_upload_error_messages)) {
                // it means there are no errors, so try to upload the file
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // it means photo was uploaded
                } else {
                    $result_message .= "<div class='alert alert-danger'>";
                        $result_message .= "<div>Unable to upload photo.</div>";
                        $result_message .= "<div>Update the record to upload photo.</div>";
                    $result_message .= "</div>";
                }
            } else {
                // it means there are some errors, so show them to user
                $result_message .= "<div class='alert alert-danger'>";
                    $result_message .= "{$file_upload_error_messages}";
                    $result_message .= "<div>Update the record to upload photo.</div>";
                $result_message .= "</div>";
            }
        }

        return $result_message;
    }

    // count records in date ranges
    public function countSearchByDateRange($date_from, $date_to)
    {

        // query to count records in date ranges
        $query = "SELECT COUNT(*) as total_rows
                    FROM analytics
                    WHERE
                        created BETWEEN :date_from AND :date_to
                        OR created LIKE :date_from_for_query
                        OR created LIKE :date_to_for_query";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":date_from", $date_from);
        $stmt->bindParam(":date_to", $date_to);

        $date_from_for_query = "%{$date_from}%";
        $date_to_for_query = "%{$date_to}%";
        $stmt->bindParam(":date_from_for_query", $date_from_for_query);
        $stmt->bindParam(":date_to_for_query", $date_to_for_query);

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    public function searchByDateRange($date_from, $date_to, $from_record_num, $records_per_page)
    {

        //select all data
        $query = "SELECT p.id, p.name, p.description, p.price, c.name as category_name, p.created
                    FROM " . $this->table_name . " p
                        LEFT JOIN categories c
                            ON p.category_id=c.id
                    WHERE
                        p.created BETWEEN :date_from AND :date_to
                        OR p.created LIKE :date_from_for_query
                        OR p.created LIKE :date_to_for_query
                    ORDER BY created DESC
                    LIMIT :from_record_num, :records_per_page";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":date_from", $date_from);
        $stmt->bindParam(":date_to", $date_to);

        $date_from_for_query = "%{$date_from}%";
        $date_to_for_query = "%{$date_to}%";
        $stmt->bindParam(":date_from_for_query", $date_from_for_query);
        $stmt->bindParam(":date_to_for_query", $date_to_for_query);

        $stmt->bindParam(":from_record_num", $from_record_num, \PDO::PARAM_INT);
        $stmt->bindParam(":records_per_page", $records_per_page, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    // used to export records to csv
    public function export_CSV()
    {

        //select all data
        $query = "SELECT id, name, description, price, created, modified FROM analytics";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        //this is how to get number of rows returned
        $num = $stmt->rowCount();

        $out = "ID,Name,Description,Price,Created,Modified\n";

        if ($num > 0) {
            //retrieve our table contents
            //fetch() is faster than fetchAll()
            //http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                //extract row
                //this will make $row['name'] to
                //just $name only
                extract($row);
                $out .= "{$id},\"{$name}\",\"{$description}\",{$price},{$created},{$modified}\n";
            }
        }

        return $out;
    }

    // read analytics by search term
    public function search($search_term, $from_record_num, $records_per_page)
    {
        $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                WHERE
                    p.name LIKE ? OR p.description LIKE ?
                ORDER BY
                    p.name ASC
                LIMIT
                    ?, ?";

        $stmt = $this->conn->prepare($query);

        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $from_record_num, \PDO::PARAM_INT);
        $stmt->bindParam(4, $records_per_page, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    public function countAll_BySearch($search_term)
    {
        $query = "SELECT
                    COUNT(*) as total_rows
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                WHERE
                    p.name LIKE ?";

        $stmt = $this->conn->prepare($query);

        $search_term = "%{$search_term}%";
        $stmt->bindParam(1, $search_term);

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    // create analytic
    public function create()
    {

        // to get time-stamp for 'created' field
        $this->getTimestamp();

        //write query
        $query = "INSERT INTO " . $this->table_name . "
                SET id=:id, name=:name, price=:price, description=:description,
                    image=:image, category_id=:category_id, created=:created";

        $stmt = $this->conn->prepare($query);

        $guid = new GUID();
        $this->id = trim($guid->uuid());

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->timestamp = htmlspecialchars(strip_tags($this->timestamp));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->timestamp);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    // read analytics with field sorting
    public function readAll_WithSorting($from_record_num, $records_per_page, $field, $order)
    {

        $query = "SELECT p.id, p.name, p.description, p.price, c.name as category_name, p.created
                    FROM analytics p
                        LEFT JOIN categories c
                            ON p.category_id=c.id
                    ORDER BY {$field} {$order}
                    LIMIT :from_record_num, :records_per_page";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":from_record_num", $from_record_num, \PDO::PARAM_INT);
        $stmt->bindParam(":records_per_page", $records_per_page, \PDO::PARAM_INT);
        $stmt->execute();

        // return values from database
        return $stmt;
    }

    // read analytics
    public function readAll($from_record_num, $records_per_page)
    {
        $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                ORDER BY
                    p.created DESC
                LIMIT
                    ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $from_record_num, \PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    // read analytics
    public function readAll_ByCategory($from_record_num, $records_per_page)
    {
        $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                WHERE
                    p.category_id=?
                ORDER BY
                    p.name ASC
                LIMIT
                    ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->category_id);
        $stmt->bindParam(2, $from_record_num, \PDO::PARAM_INT);
        $stmt->bindParam(3, $records_per_page, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    // read analytics
    public function countAll_ByCategory()
    {
        $query = "SELECT
                    COUNT(*) as total_rows
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                WHERE
                    p.category_id=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->category_id);

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    // used for paging analytic list with field sorting
    public function countAll_WithSorting($field, $order)
    {
        // for now countAll() is used
    }

    // used for paging analytics
    public function countAll()
    {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

    // used when filling up the update analytic form
    public function readOne()
    {

        $query = "SELECT p.name, p.price, p.description, p.image, p.category_id, c.name as category_name
                FROM
                    " . $this->table_name . " p
                        LEFT JOIN categories c
                            ON p.category_id=c.id
                WHERE p.id = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->image = $row['image'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    // update the analytic
    public function update()
    {

        $query = "UPDATE " . $this->table_name . "
                SET name=:name,
                    price=:price,
                    description=:description,
                    image=:image,
                    category_id=:category_id
                WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    // delete the analytic
    public function delete()
    {

        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    // delete selected analytics
    public function deleteSelected($ids)
    {

        $in_ids = str_repeat('?,', count($ids) - 1) . '?';

        // query to delete multiple records
        $query = "DELETE FROM " . $this->table_name . " WHERE id IN ({$in_ids})";

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute($ids)) {
            return true;
        } else {
            return false;
        }
    }

    // used for the 'created' field when creating a analytic
    public function getTimestamp()
    {
        date_default_timezone_set('America/Chicago');
        $this->timestamp = date('Y-m-d H:i:s');
    }
}
