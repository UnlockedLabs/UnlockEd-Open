<!-- Right sidebar -->
<div class="sidebar sidebar-dark sidebar-right sidebar-expand-md sidebar-fixed">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
        <span class="font-weight-semibold">Admin sidebar</span>
        <a href="#" class="sidebar-mobile-right-toggle">
            <i class="icon-arrow-right8"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">


        <!-- School Admin Sub navigation -->
        <div class="card card-collapsed category-admin">
            <div class="card-header admin-menu-toggle bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold">School Administration</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item">
                            <i class="icon-arrow-down12"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 sub-menu">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <li class="nav-item-header" id="category-name"><span>School</span> Information</li>
                    <li class="nav-item nav-item-submenu">
                        <a class="nav-link" id="cat-student-num">
                            <i class="icon-reading"></i>
                            Students
                            <span class="badge bg-primary badge-pill ml-auto"><span></span> enrolled</span>
                        </a>
                        <ul class="nav nav-group-sub student-list" data-submenu-title="Folded nav title">
                            <!-- This is where the individual Student tags go -->
                        </ul>
                    </li>
                    <li class="nav-item nav-item-submenu">
                        <a class="nav-link" id="cat-admin-num">
                            <i class="icon-woman"></i>
                            School Admins
                            <span class="badge bg-primary badge-pill ml-auto"><span></span> assigned</span>
                        </a>
                        <ul class="nav nav-group-sub cat-admin-list" data-submenu-title="Folded nav title">
                            <!-- This is where the individual School Admin tags go -->
                        </ul>
                    </li>
                    <li class="nav-item nav-item-submenu">
                        <a class="nav-link course-admin-num">
                            <i class="icon-man"></i>
                            Instructors
                            <span class="badge bg-primary badge-pill ml-auto"><span></span> assigned</span>
                        </a>
                        <ul class="nav nav-group-sub course-admin-list" data-submenu-title="Folded nav title">
                            <!-- This is where the individual Instructor tags go -->
                        </ul>
                    </li>
                    <li class="nav-item-header">Actions</li>
                    <li class="nav-item">
                        <a href="enrollments/enroll_category.php" class="nav-link" id="category-enroll">
                            <i class="icon-user-plus"></i>
                            Enroll students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="enrollments/cat_admins_form.php" class="nav-link" id="cat-admin-assign">
                            <i class="icon-user-plus"></i>
                            Assign School Administrators
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="enrollments/course_admins_form.php" class="nav-link" id="course-admin-assign">
                            <i class="icon-user-plus"></i>
                            Assign Instructors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="create_topic.php" title="Add A Topic" id="topic-create" class="create-topic category-actions nav-link">
                            <i class="icon-add"></i>
                            Add a Topic
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="update_category.php" title="Edit This Category" id="category-edit" class="update-category category-actions nav-link">
                            <i class="icon-pencil3 my-auto"></i>
                            <span>Edit&nbsp;<span class="catName">this Category</span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="delete_category.php" title="Delete This Category" id="category-delete" class="delete-category category-actions nav-link">
                            <i class="icon-trash my-auto"></i>
                            <span>Delete&nbsp;<span class="catName">this Category</span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="grades/course_grades_buttons.php" class="nav-link gradebook" id="cat-grades">
                            <i class="icon-book2"></i> View school gradebook
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /School Admin sub navigation -->


        <!-- Topic Admin Sub navigation -->
        <div class="card card-collapsed topic-admin">
            <div class="card-header admin-menu-toggle bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold">Topic Administration</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item">
                            <i class="icon-arrow-down12"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 sub-menu">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <li class="nav-item-header">Actions</li>
                    <li class="nav-item">
                        <a href="create_course.php" class="create-course topic-actions nav-link" title="Add A Course">
                            <i class="icon-add my-auto"></i>
                            <span>Add Course to&nbsp;<span class="topicName">This Topic</span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="update_topic.php" class="update-topic topic-actions nav-link" id="topic-update" title="Edit This Topic">
                            <i class="icon-pencil3 my-auto"></i>
                            <span>Edit&nbsp;<span class="topicName">This Topic</span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="delete_topic.php" class="delete-topic topic-actions nav-link" title="Delete This Topic">
                            <i class="icon-trash my-auto"></i>
                            <span>Delete&nbsp;<span class="topicName">This Topic</span></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Topic Admin sub navigation -->


        <!-- Course Admin Sub navigation -->
        <div class="card card-collapsed course-admin">
            <div class="card-header admin-menu-toggle bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold">Course Administration</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item">
                            <i class="icon-arrow-down12"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 sub-menu">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <li class="nav-item-header" id="course-name"><span>Course</span> Information</li>
                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link" id="course-students">
                            <i class="icon-reading"></i>
                            Students
                            <span class="badge bg-primary badge-pill ml-auto"><span></span> enrolled</span>
                        </a>
                        <ul class="nav nav-group-sub student-list" data-submenu-title="Folded nav title">
                            <!-- This is where the individual Student tags go -->
                        </ul>
                    </li>
                    <li class="nav-item nav-item-submenu">
                        <a class="nav-link course-admin-num">
                            <i class="icon-man"></i>
                            Instructors
                            <span class="badge bg-primary badge-pill ml-auto"><span></span> assigned</span>
                        </a>
                        <ul class="nav nav-group-sub course-admin-list" data-submenu-title="Folded nav title">
                            <!-- This is where the individual Instructor tags go -->
                        </ul>
                    </li>
                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link" id="course-cohorts">
                            <i class="icon-users4"></i>
                            Cohorts
                            <span class="badge bg-primary badge-pill ml-auto"><span></span></span>
                        </a>
                        <ul class="nav nav-group-sub cohort-list" data-submenu-title="Folded nav title">
                            <!-- This is where the Cohort tags go -->
                        </ul>
                    </li>
                    <li class="nav-item-header">Actions</li>
                    <li class="nav-item">
                        <a href="create_lesson.php" title="Add A New Lesson" class="create-lesson course-actions nav-link">
                            <i class="icon-add my-auto"></i>
                            <span>Add Lesson to&nbsp;<span class="courseName">This Course</span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="enrollments/create_cohort.php" class="nav-link" id="cohort-create">
                            <i class="icon-users4"></i> Create cohort
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="update_course.php" title="Edit This Course" id="course-edit" class="update-course course-actions nav-link">
                            <i class="icon-pencil3 my-auto"></i>
                            <span>Edit&nbsp;<span class="courseName">this Course</span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="delete_course.php" title="Delete This Course" id="course-delete" class="delete-course course-actions nav-link">
                            <i class="icon-trash my-auto"></i>
                            <span>Delete&nbsp;<span class="courseName">this Course</span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="grades/view_course_grades.php" class="nav-link gradebook" id="course-grades">
                            <i class="icon-book2"></i> View course gradebook
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Course Admin sub navigation -->


        <!-- Cohort Admin Sub navigation -->
        <div class="card card-collapsed cohort-admin">
            <div class="card-header admin-menu-toggle bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold">Cohort Administration</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item">
                            <i class="icon-arrow-down12"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 sub-menu">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <li class="nav-item-header" id="cohort-name">Cohort Information</li>
                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link" id="course-students">
                            <i class="icon-reading"></i>
                            Students
                            <span class="badge bg-primary badge-pill ml-auto"><span></span> enrolled</span>
                        </a>
                        <ul class="nav nav-group-sub student-list" data-submenu-title="Folded nav title">
                            <!-- This is where the individual Student tags go -->
                        </ul>
                    </li>
                    <li class="nav-item nav-item-submenu">
                        <a class="nav-link course-admin-num">
                            <i class="icon-man"></i>
                            Instructors
                            <span class="badge bg-primary badge-pill ml-auto"><span></span> assigned</span>
                        </a>
                        <ul class="nav nav-group-sub course-admin-list" data-submenu-title="Folded nav title">
                            <!-- This is where the individual Instructor tags go -->
                        </ul>
                    </li>
                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link" id="course-cohorts">
                            <i class="icon-users4"></i>
                            Cohorts
                            <span class="badge bg-primary badge-pill ml-auto"><span></span></span>
                        </a>
                        <ul class="nav nav-group-sub cohort-list" data-submenu-title="Folded nav title">
                            <!-- This is where the Cohort tags go -->
                        </ul>
                    </li>
                    <li class="nav-item-header">Actions</li>
                    <li class="nav-item">
                        <a href="enrollments/create_cohort.php" class="nav-link" id="cohort-create-fac">
                            <i class="icon-users4"></i> Create cohort
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="grades/view_cohort_grades.php" class="nav-link gradebook" id="cohort-grades">
                            <i class="icon-book2"></i> View cohort gradebook
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Cohort Admin sub navigation -->


        <!-- Student Sub navigation -->
        <div class="card card-collapsed student-sidebar">
            <div class="card-header admin-menu-toggle bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold">My Cohort</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item">
                            <i class="icon-arrow-down12"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 sub-menu">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <li class="nav-item-header" id="course-name"><span>My Cohort</span> Information</li>
                    <li class="nav-item nav-item-submenu">
                        <a href="#" class="nav-link" id="cohort-students">
                            <i class="icon-reading"></i>
                            Students
                        </a>
                        <ul class="nav nav-group-sub cohort-student-list" data-submenu-title="Folded nav title">
                            <!-- This is where the individual Student tags go -->
                        </ul>
                    </li>
                    <li class="nav-item nav-item-submenu">
                        <a class="nav-link course-admin-numXXXX">
                            <i class="icon-man"></i>
                            Facilitator
                        </a>
                        <ul class="nav nav-group-sub facilitator" data-submenu-title="Folded nav title">
                            <!-- This is where the Facilitator tag goes -->
                        </ul>
                    </li>
                    <li class="nav-item-header">Actions</li>
                    <li class="nav-item">
                        <a href="grades/view_my_grades.php" class="nav-link gradebook" id="my-grades">
                            <i class="icon-book2"></i> View my gradebook
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /Student sub navigation -->


        <!-- Students -->
        <!-- <div class="card card-collapsed students">
            <div class="card-header admin-menu-toggle bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold"><span id="stdntlist"></span> Students</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item">
                            <i class="icon-arrow-down12"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body student-container">
                <ul class="media-list student-list">
                </ul>
            </div>
        </div> -->
        <!-- /Students -->


        <!-- Cohorts -->
        <!-- <div class="card card-collapsed cohorts">
            <div class="card-header admin-menu-toggle bg-transparent header-elements-inline">
                <span class="text-uppercase font-size-sm font-weight-semibold"><span id="my-cohorts"></span>Cohorts</span>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item">
                            <i class="icon-arrow-down12"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body cohort-container">
                <ul class="media-list cohort-list">
                </ul>
            </div>
        </div> -->
        <!-- /cohorts -->


    </div>
    <!-- /sidebar content -->

</div>
<!-- /right sidebar -->