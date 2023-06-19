<?php

/**
 * Sidebar Main Navigation
 *
 * Handle Sidebar Main Navigation
 *
 * PHP version 7.2.5
 *
 * @category Main_App
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;
echo '<div class="card card-sidebar-mobile">';
    echo <<<TOGGLE

    <ul class="navbar-nav togglers">
        <li class="nav-item">
            <button href="#" onclick="$('.sidenavneedscollapse').fadeToggle();" id="collapseSidebarBtn" class="btn btn-link nav-link sidebar-control sidebar-main-toggle d-none d-md-block text-light $sidebarToggleBtn" data-fouc>
                <i class="ua-icon ua-icon-chevron-left" id="collapseIcon"></i>
            </button>
        </li>
    </ul>
TOGGLE;
    echo '<ul class="nav nav-sidebar" data-nav-type="accordion">';

        echo '<li class="nav-item">';
            echo '<a href="index.php" class="nav-link">';
                echo '<i class="icon-home4"></i>';
                echo '<span>Home</span>';
            echo '</a>';
        echo '</li>';
        
if ($_SESSION['admin_num'] == 5) {
    echo '<li class="nav-item">';
    echo '<a href="create_category.php" title="Add A New Category" class="create-category-href nav-link"><i class="icon-new"></i><span>Create A New Category</span></a>';
    echo '</li>';
}
        
//get categories
$stmt1 = $category->readCategories();

while ($catRow = $stmt1->fetch(\PDO::FETCH_ASSOC)) {
    extract($catRow);

    $course_array = $category->readCoursesIdArrayByCatId($id);
    $cohort_array = $category->readCohortsIdArrayByCatId($id);

    if ($_SESSION['admin_num'] == 1 && $id == '94876a68-f185-4967-b5fb-f90859ffd5a8') {
        continue;
    }

    if ($access_id == 1                                                                                   // open enrollments
    || $_SESSION['admin_num'] == 5                                                                        // Site Admin
    || ($access_id > 1 && (in_array($id, $_SESSION['enrolled']['cat'])))                                  // category enrollment required and user is enrolled in category
    || ($_SESSION['admin_num'] >= 2 && array_intersect($_SESSION['admin']['facilitator'], $cohort_array)) // Facilitator for cohort in THAT category
    || ($_SESSION['admin_num'] >= 3 && array_intersect($_SESSION['admin']['course'], $course_array))      // Course admin for course in THAT category
    || ($_SESSION['admin_num'] >= 4 && in_array($id, $_SESSION['admin']['cat']))) {                       // School Admin and he/she is Admin of THAT category
        if ($_SESSION['admin_num'] == 5
        || ($_SESSION['admin_num'] == 4 && in_array($id, $_SESSION['admin']['cat']))) {
            echo '<li class="nav-item nav-item-submenu ana-category category-link" data-id="'.$id.'" data-admin="true">';
        } else {
            echo '<li class="nav-item nav-item-submenu ana-category category-link" data-id="'.$id.'" data-admin="false">';
        }
        
        echo '<a href="#" class="nav-link"><i class="ua-icon ua-icon-record my-auto"></i>';
        echo '<span>'.$category_name.'</span>';
        echo '</a>';


        
        echo '<ul class="nav nav-group-sub" data-submenu-title="'.$category_name.'">';


        //set category_name for the next while loop
        $category_id = $id;
        //get topics by category id
        $stmt2 = $category->readTopicsByCategoryId($category_id);

        $count = 0;
        while ($topicRow = $stmt2->fetch(\PDO::FETCH_ASSOC)) {
            extract($topicRow);
            echo '<li class="nav-item">';
            if ($_SESSION['admin_num'] == 5
            || ($_SESSION['admin_num'] == 4 && in_array($category_id, $_SESSION['admin']['cat']))) {
                echo '<a href="course_tags.php?category_name='.$category_name.'&categoryId='.$category_id.'&topicId='.$id.'" class="nav-link topic-link-num" data-topicId="'.$id.'" data-category-name="'.$category_name.'" data-category-id="'.$category_id.'" data-admin="true">';
            } else {
                echo '<a href="course_tags.php?category_name='.$category_name.'&categoryId='.$category_id.'&topicId='.$id.'" class="nav-link topic-link-num" data-topicId="'.$id.'" data-category-name="'.$category_name.'" data-category-id="'.$category_id.'" data-admin="false">';
            }
            echo $topic_name;
            echo '</a>';

            
            echo '</li>';
            $count++;
        }
    
        if (!$count) {
            echo '<li class="nav-item"><a href="#" class="nav-link" data-topicId="0">Sorry, No Topics</a></li>';
        }

        echo '</ul>';
        echo '</li>';
    }
}
    echo '</ul>';
echo '</div>';
