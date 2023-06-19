<?php

/**
 * Export CSV
 *
 * Export record to CSV file
 *
 * PHP version 7.2.5
 *
 * @category Main_App
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

// include database and object files
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/product.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=all_products_" . date('Y-m-d_H-i-s') . ".csv");
echo $product->export_CSV();
