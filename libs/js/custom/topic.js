/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

    //get html for retrieving the course tags
    $('.topic-link-num').on('click', function(e) {

        e.preventDefault();
        e.stopPropagation();

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = e.target.href;
        $topic_name = $(this).text();
        var $id = $(this).data('topicid');
        var $category_name = $(this).data('category-name');
        var $category_id = $(this).data('category-id');
        var $admin_num = $(this).data('admin-num');
        var $admin = $(this).data('admin');
        
        // hide sub-navigation
        $(".course-admin, .category-admin, .cohort-admin, .student-sidebar, .students, .cohorts").hide();

        if ($id == 'ad1853ad-66e0-4bbb-a5fe-d79632d07b1d') {
            $(".update-topic").parent().hide();
            $(".delete-topic").parent().hide();
        } else {
            $(".update-topic").parent().show();
            $(".delete-topic").parent().show();
        }
        
        if ($admin) {

            var $content_text = `
            <span class='admin-tooltip'><p>With the Topic Administration sidebar,
            privileged users are able to:
            <ul>
                <li>add a course to the particular topic</li>
                <li>edit the particular topic</li>
                <li>delete the particular topic</li>
            </ul></span>
            `;
            var $tooltip_title = "<span class='admin-tooltip tooltip-title'>Topic Administration Sidebar</span>";

            $(".topic-admin").show();
            $(".topic-actions").data('topic-name', $topic_name);
            $(".topic-actions").data('topic-id', $id);
            $(".topic-actions").data('cat-name', $category_name);
            $(".topic-actions").data('cat-id', $category_id);
            $(".topicName").text($topic_name);
            $("#admin-title").addClass('sidebar-right-toggle btn');
            $("#admin-help").attr('data-content', $content_text).attr('data-original-title', $tooltip_title);
            $("#admin_toggler_mobile").css("display", "inline");
            $("body").addClass('sidebar-right-visible');
            $('.sub-menu, .student-container, .cohort-container').slideUp();
            $('.list-icons-item').removeClass('rotate-180');
        } else {
            $("body").removeClass("sidebar-right-visible");
            $("#admin-title").removeClass('sidebar-right-toggle btn');
            $("#admin-help").attr('data-content', '').attr('data-original-title', 'Administrative Sidebar');
        }

        $.ajax({
            type: 'GET',
            url: url,
            timeout: 30000,
            beforeSend: function() {
                $content.html('<div id="load">Loading</div>');
                //update navigation header information
                $elem = $('#lc-navigation-header');
                $elem.hide().slideDown();
                $elem.find('.category-name').text($category_name);
                $elem.find('.topic-name').text(e.target.text).attr('href', url).data('admin', $admin);
                $elem.find('.course-name').text('').hide();
                $elem.find('.lesson-name').text('').hide();
                $elem.find('.media-name').text('').hide();
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

    //create a topic
    $('.create-topic').on('click', function(e) {

        e.preventDefault();

        var $id = $(this).data('category-id');
        var $category_name = $(this).data('cat-name');

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'create_topic.php';

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

    //edit a topic
    $('.update-topic').on('click', function(e) {

        e.preventDefault();

        var $id = $(this).data('topic-id');
        var $category_name = $(this).data('cat-name');

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'update_topic.php';

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                topic_id:$id,
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

    //delete a topic
    $('.delete-topic').on('click', function(e) {

        e.preventDefault();

        var $id = $(this).data('topic-id');
        var $category_name = $(this).data('cat-name');

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var url = 'delete_topic.php';

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                topic_id:$id,
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