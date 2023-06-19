/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){
   
    //get html for creating a course
    $('.create-course').on('click', function(e) {

        e.preventDefault();

        scrollToTopCustom();

        var $content = $("#content-area-div");
        var $cat_id = $(this).data('cat-id');
        var $category_name = $(this).data('cat-name');
        var $topic_id = $(this).data('topic-id');
        var $topic_name = $(this).data('topic-name');
        var url = 'create_course.php';

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                category_id:$cat_id,
                category_name:$category_name,
                topic_id:$topic_id,
                topic_name:$topic_name
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

function fetchCourses(category_id, topic_id, category_name) {

    scrollToTopCustom();

    var $content = $("#content-area-div");
   
    var url = `course_tags.php?category_name=${category_name}&categoryId=${category_id}&topicId=${topic_id}`;

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
}

function courseCreated(category_id, topic_id, course_name, category_name, image_failed) {
    var image_failed_msg = '';
    if(image_failed) {
        image_failed_msg = 'The course was created, but the course image was not.';
    }
    swal({
        title: `<h4>${course_name} Created</h4>`,
        text: image_failed_msg,
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            fetchCourses(category_id, topic_id, category_name);
        },
    });
}

function courseUpdated(category_id, topic_id, course_name, category_name, image_failed) {
    var image_failed_msg = '';
    if(image_failed) {
        image_failed_msg = 'The course was updated, but the course image was not.';
    }
    swal({
        title: `<h4>${course_name} Updated</h4>`,
        text: image_failed_msg,
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            fetchCourses(category_id, topic_id, category_name);
        },
    });
}


function courseDeleted(category_id, topic_id, course_name, user_ux, category_name) {
    swal({
        title: `<h4>${course_name} Deleted!</h4>`,
        html: user_ux,
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            fetchCourses(category_id, topic_id, category_name);
        },
    });
}