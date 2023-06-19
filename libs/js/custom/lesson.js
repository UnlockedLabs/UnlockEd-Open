function lessonCreated(category_id, course_id, lesson_id, lesson_name) {

    swal({
        title: `<h4><em>${lesson_name}</em> Created!</h4>`,
        html: '',
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {

            //repopulate the lessons
            var $content = $("#content-area-div");
            var url = `lesson.php?category_id=${category_id}&courseId=${course_id}`;
        
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

                    //scroll lesson into view
                    $('html, body').animate({
                        scrollTop: $("#headingOne-" + lesson_id).offset().top
                    }, 2000);

                    //open the lesson which fires the ajax call for the media content 
                    $('#headingOne-' + lesson_id).find('button').click();

                    //notify user how to add files and instructions
                    new Noty({
                        theme: 'limitless',
                        text: `
                            <div class="text-center text-white">
                                <i class="icon-warning2 icon-2x"></i>
                            </div>
                            <ul>
                                <li>You can add instructions by clicking the Add or Edit Instructions button.</li>
                                <li>You can upload media files by clicking the Add Files button.</li>
                                <li>You can add a quiz by clicking the Add Quiz button.</li>
                            </ul>
                            `,
                        type: 'success',
                        layout: 'topRight',
                        timeout: 10000,
                        closeWith: ['button'],
                        animation: {
                            open: 'animated bounceInUp',     // or Animate.css class names like: 'animated bounceInLeft'
                            close: 'animated bounceOutDown'    // or Animate.css class names like: 'animated bounceOutLeft'
                        },
                        progressBar: true, 			       // displays a progress bar if timeout is not false
                    }).show();

                },
                error: function(data) {
                    $content.html(data.responseText);
                },
                fail : function() {
                    $content.html('<div id="load">Please try again soon.</div>');
                }
            });
        },
    });
}

function lessonUpdated(category_id, course_id) {
    swal({
        title: '<h4>Lesson Updated!</h4>',
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            fetchLessons(category_id, course_id);
        },
    });
}

function lessonDeleted(category_id, course_id, user_ux) {
    swal({
        title: '<h4>Lesson Deleted!</h4>',
        html: `<p>${user_ux}</p>`,
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            fetchLessons(category_id, course_id);
        },
    });
}

function fetchLessons(category_id, course_id) {

    scrollToTopCustom();

    var $content = $("#content-area-div");
    var url = `lesson.php?category_id=${category_id}&courseId=${course_id}`;

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