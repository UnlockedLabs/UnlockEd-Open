/**
 * functions for administration
 */

$(document).ready(function() {
    var $cardCollapsedClass = $('.card-collapsed');

    // Invert collapse arrow behavior
    $cardCollapsedClass.find('[data-action=collapse]').removeClass('rotate-180');

    // show right admin sidebar toggle on category click (site admins only)
    $('.category-link').on('click', function(e) {
        
        e.preventDefault();

        scrollToTopCustom();

        var $category_id = $(this).data('id');
        var $category_name = $(this).find('span').html();
        var $admin = $(this).data('admin');
                            
        // show School Administration sub-navigation
        $(".category-admin, .students").show();
        // hide Course Administration and Cohorts sub-navigation
        $(".topic-admin, .course-admin, .cohorts, .cohort-admin, .student-sidebar").hide();
        $("#category-name > span, #stdntlist, .catName").text($category_name);
        $("#category-enroll, #course-admin-assign, #cat-grades, .category-actions").data('category-id', $category_id);
        $("#cat-grades, .category-actions").data('cat-name', $category_name);

        if ($category_id == '94876a68-f185-4967-b5fb-f90859ffd5a8') {
            $(".create-topic").parent().hide();
            $(".update-category").parent().hide();
            $(".delete-category").parent().hide();
        } else {
            $(".create-topic").parent().show();
            $(".update-category").parent().show();
            $(".delete-category").parent().show();
        }
        
        // show right admin sidebar toggles (main navbar and mobile)
        // for School Admins and Site Admins only
        if ($admin) {

            var $content_text = `
            <span class='admin-tooltip'><p>With the School Administration sidebar,
            privileged users are able to:
            <ul>
                <li>view and enroll students into the particular school</li>
                <li>view and assign School Administrators to the particular school</li>
                <li>unassign School Administrators to the particular school (Site Admins only)</li>
                <li>view and assign/unassign Instructors to courses within the particular school</li>
                <li>view the gradebook for the particular school</li>
            </ul></span>
            `;
            var $tooltip_title = "<span class='admin-tooltip tooltip-title'>School Administration Sidebar</span>";
            
            $("#admin-title").addClass('sidebar-right-toggle btn');
            $("#admin-help").attr('data-content', $content_text).attr('data-original-title', $tooltip_title);
            $("#admin_toggler_mobile").css("display", "inline");
            $("body").addClass('sidebar-right-visible');
            $('.sub-menu, .student-container, .cohort-container').slideUp();
            $('.list-icons-item').removeClass('rotate-180');

            var queryString = 'category_id=' + $category_id;
            // get number of enrollments for category 
            $.get(
                "enrollments/enroll_category_count.php",
                queryString
            ).done(function(data) {
                $("#cat-student-num > span > span").text(data);
            });

            // get students enrolled in category 
            $.get(
                "enrollments/enroll_category_list.php",
                queryString
            ).done(function(data) {
                $(".student-list").html(data);
            });

            // get number of administrators for category 
            $.get(
                "enrollments/admin_cat_count.php",
                queryString
            ).done(function(data) {
                $("#cat-admin-num > span > span").text(data);
            });

            // get School Admins assigned to category 
            $.get(
                "enrollments/admin_cat_list.php",
                queryString
            ).done(function(data) {
                $(".cat-admin-list").html(data);
            });

            // var queryString = 'cat_id=' + cat_id + '&course_id=' + course_id;
            // get number of Instructors for all courses in category 
            $.get(
                "enrollments/admin_course_count.php",
                queryString
            ).done(function(data) {
                $(".course-admin-num > span > span").text(data);
                // $(`.category-link[data-id="${course_id}"]`).click();
            });

            // get Instructors assigned to all courses in category 
            $.get(
                "enrollments/admin_course_list.php",
                queryString
            ).done(function(data) {
                $(".course-admin-list").html(data);
            });
        } else {
            $("#admin-title").removeClass('sidebar-right-toggle btn');
            $("#admin-help").attr('data-content', '').attr('data-original-title', 'Administrative Sidebar');
            $("body").removeClass("sidebar-right-visible");
        }

    });

    $('#category-enroll').on('click', function(e){
        e.preventDefault();
        var $cat_id = $(this).data('category-id');
        var $cat_name = $("#category-name  > span").text();
        var url = e.target.href;
        var $content = $('#content-area-div');        

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                category_id:$cat_id,
                category_name:$cat_name
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
            DualListboxesUL.init();
        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');
            
        });

    });

    $('#cat-admin-assign').on('click', function(e){
        e.preventDefault();

        var url = e.target.href;
        var $content = $('#content-area-div');
        $.get(
            url
        ).done(function(data) {
            $content.html(data);
            $('.form-control-uniform').uniform();
        });

    });

    $('#course-admin-assign').on('click', function(e){
        e.preventDefault();

        var url = e.target.href;
        var $content = $('#content-area-div');
        var $cat_id = $(this).data('category-id');
        var queryString = 'category_id=' + $cat_id;
        $.get(
            url,
            queryString
        ).done(function(data) {
            $content.html(data);
            $('.form-control-uniform').uniform();
        });

    });

    $('#cohort-create, #cohort-create-fac').on('click', function(e) {
        e.preventDefault();
        var $course_id = $(this).data('course-id');
        var $course_name = $(this).data('course-name');
        var url = 'enrollments/create_cohort.php';
        var $content = $('#content-area-div');

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
            $content.html(data);
            DualListboxesUL.init();
        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });

    });

    $('#cohort-enroll').on('click', function(e){
        e.preventDefault();
        // var $cohort_id = $(this).data('cohortId');
        var $course_id = $(this).data('courseId');
        var $cohort_name = $("#cohort-name > span").text();
        var url = e.target.href;
        var $content = $('#content-area-div');        

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
            $content.html(data);
            DualListboxesUL.init();
        }).fail(function() {
            $content.html('<div id="load">Please try again soon.</div>');            
        });

    });

    $('.admin-menu-toggle').on('click', function(e){
        $(this).parent().toggleClass('card-collapsed');
        $(this).find('a.list-icons-item').toggleClass('rotate-180');
        $(this).next().slideToggle(150);
    });

});
