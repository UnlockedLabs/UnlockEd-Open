<?php

/**
 * LTI Tool Object
 *
 * PHP version 8.1.0
 *
 * @category  LTI Objects
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2023 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace LTITool;

// require_once $_SERVER['DOCUMENT_ROOT'] .'/LTI/GUID.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/LTI/misc.php';

 /**
  * Tool Class
  *
  * Provides database I/O (CRUD) for the lti2_tool table
  *
  * @category Objects
  * @package  UnlockED
  * @author   UnlockedLabs <developers@unlockedlabs.org>
  * @license  https://www.gnu.org/licenses/gpl.html GPLv3
  * @link     http://unlockedlabs.org
  */
class Tool
{
    private $conn;
    private $table_name = "lti2_tool";

    public $client_id;            ///< was going to use this for tool_pk, but the data type doesn't support GUID
    public $name;                 ///< name of the tool
    public $secret;               ///< shared secret with consumer
    public $provider_url;         ///< the LTI provider url, which is currently stored as initiate_login_url in LTI 1.1 db
    public $consumer_key;         ///< can be the name of the consumer, which is currently hardcoded as "UnlockEd" (perhaps turn this into GUID)
    public $version;              ///< version of LTI (value hardcoded into form currently)
    public $enabled;              ///< currently hardcoded as true
    public $created;              ///< auto-generated datetime
    public $updated;              ///< auto-generated datetime

    /**
     * Constructor
     *
     * @param object $db -  database connection to lti2_tool table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Register LTI tool (Provider)
     *
     * @return bool true if insert successful, otherwise false
     */
    public function register()
    {
        $query = "INSERT INTO " . $this->table_name . 
                " SET 
                    -- tool_pk = :tool_pk,
                    name = :name,
                    consumer_key = :consumer_key,
                    secret = :secret, 
                    initiate_login_url = :provider_url,
                    lti_version = :version,
                    enabled = :enabled,
                    created = :created,
                    updated = :updated";
                    // -- lti_version = ?";

        $stmt = $this->conn->prepare($query);

        // 8/2/2023: Until we implement 1.3 standard, this is not used for now
        $guid = new \GUID();
        $this->client_id = trim($guid->uuid());
        $this->enabled = true;

        // to get time-stamp for 'created' field
        $this->created = date('Y-m-d H:i:s');

        // to get time-stamp for 'updated' field
        $this->updated = date('Y-m-d H:i:s');

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->secret = htmlspecialchars(strip_tags($this->secret));
        $this->provider_url = htmlspecialchars(strip_tags($this->provider_url));
        $this->consumer_key = htmlspecialchars(strip_tags($this->consumer_key));
        $this->version = htmlspecialchars(strip_tags($this->version));

        // $stmt->bindParam(':tool_pk', $this->client_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':enabled', $this->enabled);
        $stmt->bindParam(':secret', $this->secret);
        $stmt->bindParam(':provider_url', $this->provider_url);
        $stmt->bindParam(':consumer_key', $this->consumer_key);
        $stmt->bindParam(':version', $this->version);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":updated", $this->updated);

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
    // public function toolExists()
    // {
    //     $query = "SELECT initiate_login_url FROM " . $this->table_name . "
    //     WHERE LOWER(topic_name) = ? AND category_id = ?
    //     LIMIT 0,1";

    //     $stmt = $this->conn->prepare($query);

    //     //we are lowering to prevent topics like Ohio University and ohio unversity
    //     $topic_name_lower = strtolower($this->topic_name);
    //     $stmt->bindParam(1, $topic_name_lower);
    //     $stmt->bindParam(2, $this->category_id);

    //     $stmt->execute();

    //     $row = $stmt->fetch(\PDO::FETCH_ASSOC);

    //     if (isset($row['topic_name'])) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    /**
     * Read topic and
     * assign values to properties topic_name and topic_url
     *
     * @return void
     */
    // public function readOne()
    // {
    //     $query = "SELECT * FROM " . $this->table_name . "
    //             WHERE id = ?
    //             LIMIT 0,1";

    //     $stmt = $this->conn->prepare($query);

    //     $stmt->bindParam(1, $this->id);

    //     $stmt->execute();

    //     $row = $stmt->fetch(\PDO::FETCH_ASSOC);

    //     // assign values to object properties
    //     $this->topic_name = $row['topic_name'];
    //     $this->topic_url = $row['iframe'];
    // }

    /**
     * Update topic's category id
     *
     * @return bool true if update successful, otherwise false
     */
    // public function reassignTopicCategoryId()
    // {

    //     $query = "UPDATE " . $this->table_name . "
    //             SET category_id = :new_category_id
    //             WHERE category_id = :category_id";

    //     $stmt = $this->conn->prepare($query);

    //     $stmt->bindParam(':new_category_id', $this->new_category_id);
    //     $stmt->bindParam(':category_id', $this->category_id);

    //     if ($stmt->execute() && $stmt->rowCount()) {
    //         return true;
    //     }

    //     return false;
    // }

    /**
     * Update topic
     *
     * @return bool true if update successful, otherwise false
     */
    // public function update()
    // {
    //     $query = "UPDATE " . $this->table_name . "
    //             SET topic_name = :topic_name,
    //             iframe = :topic_url
    //             WHERE id = :id";


    //     $stmt = $this->conn->prepare($query);

    //     $stmt->bindParam(':topic_name', $this->topic_name);
    //     $stmt->bindParam(':topic_url', $this->topic_url);
    //     $stmt->bindParam(':id', $this->id);

    //     if ($stmt->execute() && $stmt->rowCount()) {
    //         return true;
    //     }

    //     return false;
    // }

    /**
     * Delete topic
     *
     * @return bool true if delete successful, otherwise false
     */
    // public function delete()
    // {
    //     $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    //     $stmt = $this->conn->prepare($query);

    //     // sanitize
    //     $this->id = htmlspecialchars(strip_tags($this->id));

    //     $stmt->bindParam(1, $this->id);

    //     if ($stmt->execute() && $stmt->rowCount()) {
    //         return true;
    //     }

    //     return false;
    // }
}
