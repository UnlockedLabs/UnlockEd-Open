<?php

/**
 * Perform db read operations on admin privileges table
 *
 * PHP version 7.2.5
 *
 * @category  Object
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__) . '/../../objects/GUID.php';

class PrivilegesManagement
{

    // database connection and table name
    private $conn;
    private $table_name = "admin_privileges";

    // object properties
    public $id;                   ///< guid, primary key
    public $name;                 ///< the privilege name
    public $friendly_name;        ///< the privilege display name
    public $created;              ///< timestamp of record creation

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
     * read site settings
     *
     * @return PDOStatement
     */
    public function read()
    {

        $query = "SELECT * FROM  $this->table_name
                    ORDER BY friendly_name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
