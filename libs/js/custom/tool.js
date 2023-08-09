/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

    //register an LTI tool
    $('.tool-register').on('click', function(e) {

        e.preventDefault();

        var $id = $(this).data('category-id');
        var $category_name = $(this).data('cat-name');

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'register_tool.php';

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                id:$id,
                category_name:$category_name
            },
            timeout: 30000,
            beforeSend: function() {
                window.scrollTo(0, 0);
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

    // The delete tool ajax is just a boilerplate that was taken from the topic.js
    // file. We may need to remove a tool in future (?), so its left commented and
    // not fully implemented for now.

    //delete a tool
    // $('.delete-tool').on('click', function(e) {

    //     e.preventDefault();

    //     var $id = $(this).data('topic-id');
    //     var $category_name = $(this).data('cat-name');

    //     scrollToTopCustom();

    //     var $content = $("#content-area-div");
    //     var url = 'delete_topic.php';

    //     $.ajax({
    //         type: 'GET',
    //         url: url,
    //         data: {
    //             topic_id:$id,
    //             category_name:$category_name
    //         },
    //         timeout: 30000,
    //         beforeSend: function() {
    //             $content.html('<div id="load">Loading</div>');
    //         },
    //         complete: function() {
    //             $('#load').remove();
    //         },
    //         error: function(data) {
    //             $content.html(data.responseText);
    //         }
    //     }).done(function(data) {
    //         $content.html(data);
    //     }).fail(function() {
    //         $content.html('<div id="load">Please try again soon.</div>');            
    //     });
    // });
    
}) ();