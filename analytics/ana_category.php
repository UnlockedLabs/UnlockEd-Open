<?php
namespace unlockedlabs\unlocked;
/**
 * @todo
 * ana_category.php
 * think about storing an analytics object in $_SESSION itself.
 * let this class handle it's own numbers
 */

// detect session time out


/**
 * @todo 
 * ana_category.php
 * detect when client closes browswer
 
 */

require_once dirname(__FILE__).'/../session-validation.php';

// include database and object files
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/category_accessed.php';

// instantiate database
$database = new Database();
$db = $database->getConnection();

// instantiate database category_accessed object
$category_accessed = new CategoryAccessed($db);

//get category id (user clicked it)  
echo 'categotry id: ' . $_GET['id'] . ' from ana_category.php<br/>';

//$_SESSION['category_id'] gets set to 0 on login see users.php.
if ($_GET['id'] != $_SESSION['category_id'] ) {

    //set new category i
    $_SESSION['category_id'] = $_GET['id'];

    //set properties
    $category_accessed->username = $_SESSION['username'];
    $category_accessed->category_clicked = $_GET['id'];
    $category_accessed->access_id = $_SESSION['access_num'];
    $category_accessed->admin_id = $_SESSION['admin_num'];

    /* 
        Set object property last_row to $_SESSION['last_row'] BEFORE calling timeIn().
        timeIn() updates last_row. timeOut() need to last_row id before timeIn() sets it.
    */

    $category_accessed->last_row = $_SESSION['last_row'];

    //set time_in for  accessed category
    $category_accessed->timeIn();

    //set time_out for previously accessed category row
    echo $category_accessed->last_row;
    $category_accessed->timeOut();

} else {

    echo 'not new cate <br />';

}
?>