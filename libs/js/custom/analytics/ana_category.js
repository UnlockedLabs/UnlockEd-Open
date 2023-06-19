/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

    //get html for creating a category
    $('.ana-category').on('click', function(e) {

        e.preventDefault();

        var $content = $("#content-area-div");
		var id = $(this).data('id');
	
        $.ajax({
            type: 'GET',
            url: 'analytics/ana_category.php?id='+id,
            timeout: 30000,
            beforeSend: function() {
                //$content.html('<div id="load">Loading</div>');
            },
            complete: function() {
                //$('#load').remove();
            },
            success: function(data) {
                //$content.html(data);
            },
            error: function(data) {
                //$content.html(data.responseText);
            },
            fail : function() {
                $content.html('<div id="load">Please try again soon.</div>');
            }
        });
    });
}) ();