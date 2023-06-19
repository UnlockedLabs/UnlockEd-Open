#! /bin/bash

rm -R project-documentation/
mkdir -p  /massivestorage/www/htdocs/unlockedlabs.com/demo/project-documentation/phpdox/html/coverage/
mkdir -p  /massivestorage/www/htdocs/unlockedlabs.com/demo/project-documentation/phpdox/standards/
mkdir -p  /massivestorage/www/htdocs/unlockedlabs.com/demo/project-documentation/phpdox/build/logs/

cat <<- 'PHPCSJSON' > project-documentation/phpdox/build/logs/phpcs.php
<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin "*"');

$csv = file_get_contents('phpcs.csv');
$errsarray = array_map("str_getcsv", explode("\n", $csv));
$wellformederrs = array();
foreach($errsarray as $key=>$val){
    if(count($val)==8){
        $wellformederrs[] = $val;
    }
}

$toplevel = array();

$toplevel['data'] = $wellformederrs;

print json_encode($toplevel);
?>
PHPCSJSON

cat <<- 'CSREPORT' > project-documentation/phpdox/standards/phpcs-report.php
<?php
    require_once dirname(__FILE__).'/../../../config/core.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>UnlockED</title>

    <!-- Global stylesheets -->
    <!--<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">-->
    <link href="<?php echo FONTSSDIR; ?>/roboto/roboto.php?weight=400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/assets/css/colors.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo LIBSDIR; ?>/limitless/global_assets/cylesheet" type="text/css">
    <style>
        .dataTables_info{
            float: right;
        }
    </style>
</head>
<body>
    <div>
        <table class="table datatable-ajax">
            <thead>
                <tr>
                    <th>File</th>
                    <th>Line</th>
                    <th>Column</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Source</th>
                    <th>Severity</th>
                    <th>Fixable</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Core JS files -->
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/main/jquery.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/main/bootstrap.bundle.min.js"></script>
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/ui/perfect_scrollbar.min.js"></script>
    <!-- /core JS files -->
   
    <script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
   	<script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
	<script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
	<script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
	<script src="<?php echo LIBSDIR; ?>/limitless/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
    

<script>
$(document).ready(function(){

    if (!$().DataTable) {
        console.warn('Warning - datatables.min.js is not loaded.');
        return;
    }
// Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            }
        });


        // Basic initialization
        $('.datatable-ajax').DataTable({
            ajax: '../build/logs/phpcs.php',
            buttons: {            
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            }
        });
   
});

</script>

</body>
</html>
CSREPORT

phploc --count-tests --log-xml /massivestorage/www/htdocs/unlockedlabs.com/demo/project-documentation/phpdox/build/logs/phploc.xml /massivestorage/www/htdocs/unlockedlabs.com/demo/
phpcs --coverage-xml --report-file=/massivestorage/www/htdocs/unlockedlabs.com/demo/project-documentation/phpdocs/build/logs/coverage/ *.php
phpdox
phpcs --report=csv --report-file=/massivestorage/www/htdocs/unlockedlabs.com/demo/project-documentation/phpdox/build/logs/phpcs.csv -s --standard=PEAR,PSR1,PSR2,PSR12 *.php
phpunit --coverage-html=/massivestorage/www/htdocs/unlockedlabs.com/demo/project-documentation/phpdox/html/coverage/ --whitelist=. .
