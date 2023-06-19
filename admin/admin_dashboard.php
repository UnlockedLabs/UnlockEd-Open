<?php

/**
 * Admin Dashboard
 *
 * PHP version 7.2.5
 *
 * @category  Main_App
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

require_once dirname(__FILE__) . '/./admin-session-validation.php';

// include database and object files
require_once dirname(__FILE__) . '/../config/core.php';
require_once dirname(__FILE__) . '/../config/database.php';
require_once dirname(__FILE__) . '/./objects/dashboard.php';
require_once dirname(__FILE__) . '/../objects/submission.php';

$database = new Database();
$db = $database->getConnection();

$dashboard = new Dashboard($db);
$submission = new Submission($db);
$smbcnts = $submission->countAll();

?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>Dashboard</h1>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a href="#users-tab" class="nav-link active" data-toggle="tab">Users Overview</a></li>
                    <li class="nav-item"><a href="#content-tab" class="nav-link" data-toggle="tab">Content Overview</a></li>
                    <li class="nav-item"><a href="#content-users-tab" class="nav-link" data-toggle="tab">Content Usage Overview</a></li>
                    
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="users-tab">
                        <ul>
                            <li>Number of users in the site</li>

                            <?php
                                $logged_in = $dashboard->totalUsersLoggedIn();
                                echo '<ul>';
                            while ($row = $logged_in->fetch(\PDO::FETCH_ASSOC)) {
                                echo "<li>users logged in {$row['total_count']}</li>";
                            }
                                echo '</ul>';
                            ?>

                            <li>Number of users assigned to each admin level</li>

                            <?php
                                $user_levels = $dashboard->usersByAdminLevel();
                                echo '<ul>';
                            while ($row = $user_levels->fetch(\PDO::FETCH_ASSOC)) {
                                echo "<li>Admin Level: {$row['name']} has {$row['count']} users assigned to it.</li>";
                            }
                                echo '</ul>';
                            ?>
                        </ul>    
                    </div>
                                
                    <div class="tab-pane fade" id="content-tab">
                        <ul>
                            <li>Number of Categories</li>

                                <?php
                                    $num_categories = $dashboard->countNumOfCategories();
                                    echo '<ul>';
                                while ($row = $num_categories->fetch(\PDO::FETCH_ASSOC)) {
                                    echo "<li>total number of categories: {$row['total_count']}</li>";
                                }
                                    echo '</ul>';
                                ?>

                            <li>Number of Topics</li>

                            <?php
                                $num_topics = $dashboard->countNumOfTopics();
                                echo '<ul>';
                            while ($row = $num_topics->fetch(\PDO::FETCH_ASSOC)) {
                                echo "<li>total number of topics: {$row['total_count']}</li>";
                            }
                                echo '</ul>';
                            ?>

                            <li>Number of Courses</li>

                            <?php
                                $num_courses = $dashboard->countNumOfCourses();
                                echo '<ul>';
                            while ($row = $num_courses->fetch(\PDO::FETCH_ASSOC)) {
                                echo "<li>total number of courses: {$row['total_count']}</li>";
                            }
                                echo '</ul>';
                            ?>

                            <li>Number of Lessons</li>

                            <?php
                                $num_courses = $dashboard->countNumOfLessons();
                                echo '<ul>';
                            while ($row = $num_courses->fetch(\PDO::FETCH_ASSOC)) {
                                echo "<li>total number of lessons: {$row['total_count']}</li>";
                            }
                                echo '</ul>';
                            ?>


                           
                        </ul>
                    </div>
                    
                    <div class="tab-page fade" id="content-users-tab">
                    <ul>
                        <li>Hours of Media Interaction</li>
                            <ul>
                            <?php
                                $num_courses = $dashboard->totalSecsVideo();
                                while ($row = $num_courses->fetch(\PDO::FETCH_ASSOC)) {
                                    echo "<li>Hours of video watched: " . round(($row['total_secs'] / 3600), 1) . "</li>";
                                }
                                $num_courses = $dashboard->totalSecsAudio();

                                while ($row = $num_courses->fetch(\PDO::FETCH_ASSOC)) {
                                    echo "<li>Hours of audio listened to: " . round(($row['total_secs'] / 3600), 1) . "</li>";
                                }
                            ?>
                            </ul>                        
                            <li>Number of Quizzes Taken: <?php echo $smbcnts; ?></li>
                        </ul>
                    </div>
 
                </div>
            </div><!-- /card body -->
        </div><!-- /card -->
    </div> <!-- /col-12 -->
</div> <!-- /row -->
