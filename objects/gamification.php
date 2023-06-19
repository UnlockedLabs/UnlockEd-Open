<?php

/**
 * Game Object
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
 * Game Class
 *
 * Provide database I/O (CRUD) for the user_gamification table
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class Game
{
    private $conn;
    private $table_name = "user_gamification";

    public $id;                   ///< guid, primary key
    public $username;
    public $coins;                ///< number of accumulated coins
    public $coin_balance;         ///< for future use in using coins to purchase prizes
    public $user_level;           ///< user's level
    public $user_status;          ///< user's status (name of level)
    public $newLogins;            ///< number of logins

    /**
     * Constructor
     *
     * @param database $db user_gamification Instance of the Database Class
     *
     * @return PDO An instance of the PDO Class for the user_gamification table
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Insert/create user's game record
     *
     * @return bool true if insert successful, otherwise false
     */
    public function addUserGame()
    {
        $query = "INSERT INTO user_gamification
                SET id= :id, username = :username,
                coins = :coins, coin_balance = :coin_balance,
                user_level = :user_level,
                user_status = :user_status,
                logins = :logins";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':coins', $this->coins);
        $stmt->bindParam(':coin_balance', $this->coin_balance);
        $stmt->bindParam(':user_level', $this->user_level);
        $stmt->bindParam(':user_status', $this->user_status);
        $stmt->bindParam(':logins', $this->logins);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Read number of accumulated coins
     *
     * @return int user's coin count
     */
    public function getCoinCount()
    {
        $query = "SELECT coins FROM " . $this->table_name . "
            WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $coins = $row['coins'];

        return $coins;
    }

    /**
     * Read number of accumulated coins with comma delimeter
     *
     * @return int user's coin count with comma delimeter
     */
    public function coinsDelimeter()
    {
        $coinsDelimeter = $this->getCoinCount();

        return number_format($coinsDelimeter);
    }

    /**
     * Read the number of times user has logged in
     *
     * @return PDOStatement
     */
    public function userLoginCount()
    {
        $query = "SELECT logins FROM " . $this->table_name . "
                WHERE id = :id LIMIT 0,1";

                $stmt = $this->conn->prepare($query);

                        $stmt->bindParam(':id', $this->id);

                $stmt->execute();

                return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Update user's number of logins, increment by one
     *
     * @return bool true if update successful, otherwise false
     */
    public function addLoginCount()
    {
        $currentLogins = $this->userLoginCount();
        $currentLogins = $currentLogins['logins'];
        $newLogins = $currentLogins + 1;

        $query = "UPDATE " . $this->table_name . "
                SET logins = :logins
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':logins', $newLogins);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Update user's coins, status
     *
     * @return bool true if update successful, otherwise false
     */
    public function updateUserCoins()
    {
        $query = "UPDATE " . $this->table_name . "
                SET coins = :coins,
                user_level = :user_level,
                user_status = :user_status,
                username = :username
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':coins', $this->coins);
        $stmt->bindParam(':user_level', $this->user_level);
        $stmt->bindParam(':user_status', $this->user_status);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);

        if ($stmt->execute() && $stmt->rowCount()) {
            return true;
        }

        return false;
    }

    /**
     * Read user's overall rank
     *
     * Count number of rows that have more coins and add 1.
     *
     * @return int user's overall rank
     */
    public function getUserRank()
    {

        // get user's current coin count
        $coinCount = $this->getCoinCount();

        $query = "SELECT COUNT(*) as total_rows
                FROM " . $this->table_name . "
                WHERE coins > :coinCount";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':coinCount', $coinCount);

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $rank = $row['total_rows'] + 1;
        return $rank;
    }


    /**
     * Read user's current status
     *
     * @return string user's current status
     */
    public function getUserStatus()
    {
        $query = "SELECT user_status FROM " . $this->table_name . "
            WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $status = $row['user_status'];
        
        return $status;
    }

    /**
     * Read user's current level
     *
     * @return int user's level
     */
    public function getUserLevel()
    {

        $query = "SELECT user_level FROM " . $this->table_name . "
            WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();       
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row['user_level'];
        
    }

    /**
     * Read user level to determine coin image size
     *
     * @return int coin image size
     */
    public function getCoinImageSize()
    {

        $level = $this->getUserLevel();

        if ($level > 5) {
            return 30;
        } else {
            return 20;
        }
    }

    /**
     * Read user level to determine color
     *
     * @return string user's text-color class
     */
    public function getLevelColor()
    {
        $level = $this->getUserLevel();

        if ($level == 1) 
        {
            return "green-300";
        } else if ($level == 2) {
            return "danger-300";
        } else if ($level == 3) {
            return "indigo-300";
        } else if ($level == 4) {
            return "info-300";
        } else if ($level == 5) {
            return "orange-800";
        } else if ($level == 6) {
            return "grey-300";
        } else if ($level == 7) {
            return "orange-300";
        }
        
    }

}
