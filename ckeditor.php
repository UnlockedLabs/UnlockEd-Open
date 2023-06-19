<?php

/**
 * CKEditor
 *
 * Detailed Description
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
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/product.php';
require_once dirname(__FILE__).'/objects/category.php';
require_once dirname(__FILE__).'/objects/users.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);
$user = new User($db);

// header settings
require 'layout_header.php';

$page_url = "index.php?";

?>



<?php require 'layout_footer.php'; ?>

<script src="libs/ckeditor4/ckeditor/ckeditor.js"></script>
<script src="libs/ckeditor4/example/js/sample.js"></script>

<link rel="stylesheet" href="libs/ckeditor4/example/css/samples.css">
<link rel="stylesheet" href="libs/ckeditor4/example/toolbarconfigurator/lib/codemirror/neo.css">
<div id="editor">
    <h1>Hello world!</h1>
    <p>I'm an instance of <a href="http://ckeditor.com">CKEditor</a>.</p>
</div>
<script>
    initSample();
</script>