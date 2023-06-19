/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

    //get html for creating a category
    $('.create-category-href').on('click', function(e) {

        e.preventDefault();

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'create_category.php';

        $.ajax({
            type: 'GET',
            url: url,
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
            },
            complete: function() {
                $('#load').remove();
            },
            success: function(data) {
                $content.html(data);
            },
            error: function(data) {
                $content.html(data.responseText);
            },
            fail : function() {
                $content.html('<div id="load">Please try again soon.</div>');
            }
        });
    });

    //edit a category
    $('.update-category').on('click', function(e) {

        e.preventDefault();

        var $id = $(this).data('category-id');
        var $category_name = $(this).data('cat-name');

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'update_category.php';

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                id:$id,
                category_name:$category_name
            },
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
            },
            complete: function() {
                $('#load').remove();
            },
            error: function(data) {
                $content.html(data.responseText);
            }
        }).done(function(data) {
            $content.html(data);
        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });
    });

    //delete a category
    $('.delete-category').on('click', function(e) {

        e.preventDefault();

        var $id = $(this).data('category-id');
        var $category_name = $(this).data('cat-name');

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'delete_category.php';

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                id:$id,
                category_name:$category_name
            },
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
            },
            complete: function() {
                $('#load').remove();
            },
            error: function(data) {
                $content.html(data.responseText);
            }
        }).done(function(data) {
            $content.html(data);
        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });
    });
}) ();