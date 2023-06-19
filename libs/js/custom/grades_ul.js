/**
 * functions for administration
 */

$(document).ready(function() {

    // get the quiz grades for all the students in a school
    $('#cat-grades').on('click', function(e){
        e.preventDefault();

        var $cat_id = $(this).data('category-id');
        var $cat_name = $(this).data('cat-name');
        var url = e.target.href;
        var $content = $('#content-area-div');

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                cat_id:$cat_id,
                cat_name:$cat_name
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

    // get the quiz grades for all the students in a course
    $('#course-grades').on('click', function(e){
        e.preventDefault();

        var $course_id = $(this).data('course-id');
        var $course_name = $(this).data('course-name');
        var url = e.target.href;
        var $content = $('#content-area-div');
        var course_quiz_object;

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                course_id:$course_id,
                course_name:$course_name
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
            if (data.includes('text-muted')) {
                $content.html(data);
            } else {
                course_quiz_object = JSON.parse(data);
    
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        course_id:$course_id,
                        course_name:$course_name
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
                    ULQuizBars.init(course_quiz_object);
                    DatatableButtonsHtml5.init(course_quiz_object);
                }).fail(function() {
                    $content.html('<div id="load">Please try again soon.</div>');            
                });
            }

        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });

    });

    // get the quiz grades for all the students in a course
    $('#cohort-grades').on('click', function(e){
        e.preventDefault();

        var $course_id = $(this).data('course-id');
        var $course_name = $(this).data('course-name');
        var url = e.target.href;
        var $content = $('#content-area-div');
        var course_quiz_object;

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                course_id:$course_id,
                course_name:$course_name
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
            if (data.includes('text-muted')) {
                $content.html(data);
            } else {
                course_quiz_object = JSON.parse(data);
    
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        course_id:$course_id,
                        course_name:$course_name
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
                    ULQuizBars.init(course_quiz_object);
                    DatatableButtonsHtml5.init(course_quiz_object);
                }).fail(function() {
                    $content.html('<div id="load">Please try again soon.</div>');            
                });
            }

        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });

    });

    // get all the quiz grades for a student in a course
    $('#my-grades').on('click', function(e){
        e.preventDefault();

        var $course_id = $(this).data('course-id');
        var $course_name = $(this).data('course-name');
        var url = e.target.href;
        var $content = $('#content-area-div');
        var course_quiz_object;

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                course_id:$course_id,
                course_name:$course_name
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
            if (data.includes('text-muted')) {
                $content.html(data);
            } else {
                course_quiz_object = JSON.parse(data);
    
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        course_id:$course_id,
                        course_name:$course_name
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
                    ULQuizBars.init(course_quiz_object);
                    DatatableStudent.init(course_quiz_object);
                }).fail(function() {
                    $content.html('<div id="load">Please try again soon.</div>');            
                });
            }

        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });

    });

});