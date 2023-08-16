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

    public $name;                 ///< name of the tool
    public $client_id;            ///< was going to use this for tool_pk, but the data type doesn't support GUID
    public $login_url;            ///< the OIDC login url, which is currently stored as initiate_login_url
    public $launch_url;           ///< the fully qualified domain name where the tool is hosted
    public $consumer_key;         ///< can be the name of the consumer, which is currently hardcoded as "UnlockEd" (perhaps turn this into GUID)
    public $version;              ///< version of LTI (value hardcoded into form currently)
    public $public_key;               ///< shared secret with consumer
    // public $secret;               ///< shared secret with consumer
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
     * Register LTI Tool
     *
     * @return bool true if insert successful, otherwise false
     */
    public function register()
    {
        $query = "INSERT INTO " . $this->table_name . 
                " SET 
                    -- tool_pk = :tool_pk,
                    name = :name,
                    initiate_login_url = :login_url,
                    redirection_uris = :launch_url,
                    consumer_key = :consumer_key,
                    lti_version = :version,
                    public_key = :public_key,
                    -- secret = :secret, 
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
        $this->login_url = htmlspecialchars(strip_tags($this->login_url));
        $this->launch_url = htmlspecialchars(strip_tags($this->launch_url));
        $this->consumer_key = htmlspecialchars(strip_tags($this->consumer_key));
        $this->version = htmlspecialchars(strip_tags($this->version));
        $this->public_key = htmlspecialchars(strip_tags($this->public_key));
        // $this->secret = htmlspecialchars(strip_tags($this->secret));

        // $stmt->bindParam(':tool_pk', $this->client_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':login_url', $this->login_url);
        $stmt->bindParam(':launch_url', $this->launch_url);
        $stmt->bindParam(':consumer_key', $this->consumer_key);
        $stmt->bindParam(':version', $this->version);
        $stmt->bindParam(':public_key', $this->public_key);
        $stmt->bindParam(':enabled', $this->enabled);
        $stmt->bindParam(':created', $this->created);
        $stmt->bindParam(':updated', $this->updated);
        // $stmt->bindParam(':secret', $this->secret);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Validate that tool is registered
     *
     * @return bool true if tool is already in database, otherwise false
     */
    public function toolExists()
    {
        $query = "SELECT initiate_login_url FROM " . $this->table_name . "
        WHERE initiate_login_url = :provider_url
        LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':provider_url', $this->provider_url);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (isset($row['initiate_login_url'])) {
            return true;
        } else {
            return false;
        }
    }
}
