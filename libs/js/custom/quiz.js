/**
 * functions for quizzes
 */

function quizCreated(course_id, lesson_id, quiz_name) {

    swal({
        title: `<h4><em>${quiz_name}</em> Created!</h4>`,
        html: '',
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {

            //repopulate the quizzes
            var $content = $(`#lesson-media-${lesson_id}`);
            var url = `lesson_media.php?course_id=${course_id}&lesson_id=${lesson_id}`;
        
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
                error: function(data) {
                    $content.html(data.responseText);
                }
            }).done(function(data) {
                $content.html(data);
                progressLessonBarReload(lesson_id);
                    
                // //notify user how to add files and instructions
                // new Noty({
                //     text: `
                //         <div class="text-center text-white">
                //             <i class="icon-warning2 icon-2x"></i>
                //         </div>
                //         <ul>
                //             <li>You can edit the quiz by clicking the ZZZ button</li>
                //             <li>You can delete the quiz by clicking the ZZZ button</li>
                //         </ul>
                //         `,
                //     type: 'success',
                //     layout: 'topRight',
                //     timeout: 10000,
                //     closeWith: ['button'],
                //     animation: {
                //         open: 'animated bounceInRight',     // or Animate.css class names like: 'animated bounceInUp'
                //         close: 'animated bounceOutRight'    // or Animate.css class names like: 'animated bounceOutDown'
                //     },
                //     progressBar: true, 			       // displays a progress bar if timeout is not false
                // }).show();
                
            }).fail(function() {
                $content.html('<div id="load">Please try again soon.</div>');
            });
        },
    });
}

// CHRISNOTE: need to use this when I implement quiz updating functionality
function quizUpdated(course_id, lesson_id) {
    swal({
        title: '<h4>Quiz Updated!</h4>',
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            fetchQuizzes(course_id, lesson_id);
        },
    });
}
// CHRISNOTE: need to use this when I implement quiz updating functionality
function quizDeleted(course_id, lesson_id) {
    swal({
        title: '<h4>Quiz Deleted!</h4>',
        html: `<p>Quiz was deleted.</p>`,
        type: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonClass: 'btn btn-info',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
        onClose: function() {
            fetchQuizzes(course_id, lesson_id);
        },
    });
}

function fetchQuizzes(course_id, lesson_id) {
    scrollToTopCustom();

    //repopulate the quizzes
    var $content = $(`#lesson-media-${lesson_id}`);
    var url = `lesson_media.php?course_id=${course_id}&lesson_id=${lesson_id}`;

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
        error: function(data) {
            $content.html(data.responseText);
        }
    }).done(function(data) {
        $content.html(data);
    }).fail(function() {
        $content.html('<div id="load">Please try again soon.</div>');
    });

}