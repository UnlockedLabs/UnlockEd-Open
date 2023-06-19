<?php
    require_once dirname(__FILE__).'/../../config/core.php';
?>

<html>
<head>
    <script src="<?php echo LIBSDIR; ?>/js/jquery.js"></script>
</head>
<body>

<div id="testing"></div>

<script>
$('#testing').html("<h1>It works</h1>");
</script>
</body>
</html>


