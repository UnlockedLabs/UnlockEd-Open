<?php

/**
 * Preference Object
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
 * Preference Class
 *
 * Provide database I/O (CRUD) for the user_preferences table
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class userPreference
{
    private $conn;
    private $table_name = "user_preferences";

    public $id;                   ///< guid, primary key
    public $username;
    public $banner;               ///< banner image selected by user
    public $night_mode;           ///< night mode (on/off) selected by user
    public $user_color;           ///< user's color preference
    public $dashboard_color;      ///< user's dashboard color preferences
    public $sidebarToggle;        ///< user's sidebar toggle preference

    /**
     * Constructor
     *
     * @param database $db user_preferences Instance of the Database Class
     *
     * @return PDO An instance of the PDO Class for the user_preferences table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }


    /**
     * Insert/create user's preference record
     *
     * @return bool true if insert successful, otherwise false
     */
    public function addUserPreferences()
    {
        $query = "INSERT INTO user_preferences
                SET id= :id, username = :username,
                banner = :banner, night_mode = :night_mode,
                user_color = :user_color,
                dashboard_color = :dashboard_color,
                sidebar_toggle = :sidebarToggle";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':banner', $this->banner);
        $stmt->bindParam(':night_mode', $this->night_mode);
        $stmt->bindParam(':user_color', $this->user_color);
        $stmt->bindParam(':dashboard_color', $this->dashboard_color);
        $stmt->bindParam(':sidebarToggle', $this->sidebarToggle);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }



    /**
     * Read user banner number
     *
     * @return int user's banner image preference
     */
    public function getBannerNum()
    {
        $query = "SELECT banner FROM " . $this->table_name . "
            WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $banner = $row['banner'];

        return $banner;
    }

    /**
     * Read user night mode preference
     *
     * @return int user's night mode preference
     */
    public function getNightMode()
    {
        $query = "SELECT night_mode FROM " . $this->table_name . "
            WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $night_mode = $row['night_mode'];
/*
        if ($night_mode == 0)
        {
            $night_mode = "light";
        } else {
            $night_mode = "dark";
        }
*/
        return $night_mode;
    }

    /**
     * Read user color preference
     *
     * @return string user's color preference
     */
    public function getUserColor()
    {
        $query = "SELECT user_color FROM " . $this->table_name . "
            WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $user_color = $row['user_color'];

        return $user_color;
    }


    /**
     * Read user dashboard color preference
     *
     * @return string user's dashboard color preference
     */
    public function getDashboardColor()
    {
        
        $query = "SELECT dashboard_color FROM " . $this->table_name . "
        WHERE id = :id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $dashboard_color = $row['dashboard_color'];

        return $dashboard_color;
    }
    
    /**
     * Read user sidebar toggle preference
     *
     * @return string user's sidebar toggle preference
     */
    public function getUserSidebarToggle()
    {
        
        $query = "SELECT sidebar_toggle FROM " . $this->table_name . "
        WHERE id = :id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $sidebarToggle = $row['sidebar_toggle'];

        if ($sidebarToggle == 2)
        {
            $sidebarToggle = "sidebar-xs";
        } else {
            $sidebarToggle = "";
        }

        return $sidebarToggle;
    }

        /**
     * Read user sidebar toggle preference
     *
     * @return string user's sidebar toggle preference
     */
    public function getUserSidebarToggleBtn()
    {
        
        $query = "SELECT sidebar_toggle FROM " . $this->table_name . "
        WHERE id = :id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $sidebarToggle = $row['sidebar_toggle'];

        if ($sidebarToggle == 2)
        {
            $sidebarToggleRotate = "rotate-180";
        } else {
            $sidebarToggleRotate = "";
        }

        return $sidebarToggleRotate;
    }

    /**
     * Update user's banner image
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateUserBanner()
    {
        
        $query = "UPDATE " . $this->table_name . "
        SET banner = :bannerNum WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':bannerNum', $this->bannerNum);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }


    /**
     * Update user's nightMode setting
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateUserNightMode()
    {
        
        $query = "UPDATE " . $this->table_name . "
        SET night_mode = :nightMode WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nightMode', $this->nightMode);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }


    /**
     * Update user's banner image
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateUserColor()
    {
        
        $query = "UPDATE " . $this->table_name . "
        SET user_color = :userColor WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':userColor', $this->userColor);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }


    /**
     * Update user's banner image
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateUserDashboardColor()
    {
        
        $query = "UPDATE " . $this->table_name . "
        SET dashboard_color = :dashboardColor WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':dashboardColor', $this->dashboardColor);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }


    /**
     * Update user's sidebar toggle position
     *
     * @return bool true if toggle setting successful, otherwise false
     */
    public function updateSidebarToggle()
    {
        $query = "UPDATE " . $this->table_name . "
        SET sidebar_toggle = :sidebarToggle WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':sidebarToggle', $this->sidebarToggle);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }


    /**
     * Reset's user's sidebar toggle position to open (other preference resets may be added later)
     *
     * @return bool true if toggle setting successful, otherwise false
     */
    public function loginResetUserPreferences()
    {
        $query = "UPDATE " . $this->table_name . "
        SET sidebar_toggle = 1 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        //$stmt->bindParam(':sidebarToggle', "1");
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

}