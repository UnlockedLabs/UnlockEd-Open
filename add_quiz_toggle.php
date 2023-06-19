<?php

/**
 * Add Quiz Toggle
 *
 * Detailed Description
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

// CHRISNOTE: not using this file (yet?)
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/users.php';

$database = new Database();
$db = $database->getConnection();

// $course = new Course($db);
// $lesson = new Lesson($db);
// $quiz = new Quiz($db);
$users = new User($db);

echo "<button type='button' class='btn btn-primary mb-2' data-lesson-id='" . $lesson_id . "' data-course-id='" . $course_id . "' data-toggle='collapse' data-target='#add_quiz' aria-expanded='true'>Add Quiz <i class='icon-plus3 ml-2'></i></button>";
?>

<div class="card collapse" id="add_quiz">
    <div class="card-body">
        <h3 class="card-title">Create New Quiz</h3>
        <form id='create-quiz-form' action='create_quiz.php?course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson_id; ?>' method='post'>
            <div class="form-group">
                <label for="quizName">Quiz Name:</label>
                <input type="text" name='quiz_name' class="form-control" id="quizName" placeholder="Unnamed Quiz" required>
            </div>
            <div class="form-group">
                <label for="quizDesc">Quiz Description:</label>
                <textarea name="quiz_desc" id="quizDesc"></textarea>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Access Level</label>
                <select class="form-control" name='access_id' id="exampleFormControlSelect1" required>
                    <?php
                        $access_levels = $users->readAccessLevels();
                    while ($row = $access_levels->fetch(\PDO::FETCH_ASSOC)) {
                        echo '<option value=' . $row['access_num'] . '>' . $row['access_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Admin Level</label>
                <select class="form-control" name='admin_id' id="exampleFormControlSelect1" required>
                    <?php
                        $admin_levels = $users->readAdminLevels();
                    while ($row = $admin_levels->fetch(\PDO::FETCH_ASSOC)) {
                        echo '<option value=' . $row['admin_num'] . '>' . $row['admin_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
            <button type="submit" class="btn btn-primary">Create Quiz</button>
        </form>
    </div>
</div>
<script>
initSample('quizDesc');
</script>