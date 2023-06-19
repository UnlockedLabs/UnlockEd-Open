<?php

/**
 * Site Privilege Management
 *
 * PHP version 7.2.5
 *
 * @category  Main_App
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__) . '/./admin-session-validation.php';

//ensure admin user (admin is 4 and above)
if (($_SESSION['admin_num'] < 4)) {
    die('<h1>Restricted Action!</h1>');
}

// include database and object files
require_once dirname(__FILE__) . '/../config/core.php';
require_once dirname(__FILE__) . '/../config/database.php';
require_once dirname(__FILE__) . '/./objects/privileges_management.php';

$database = new Database();
$db = $database->getConnection();

$privileges = new PrivilegesManagement($db);
$current_privileges = $privileges->read();

?>
<div class="card">
    <div class="card-header">
        <h1>Privileges Management</h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Friendly Name</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = $current_privileges->fetch(\PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<tr>";
                            echo "<td>$id</td>";
                            echo "<td>$name</td>";
                            echo "<td>$friendly_name</td>";
                            echo "<td>$timestamp</td>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div> <!--/ col-12 -->
        </div> <!--/ row -->
    </div> <!-- /card body -->
</div> <!-- /card -->
