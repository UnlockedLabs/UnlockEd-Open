<?php

/**
 * Create Quiz
 *
 * Process create quiz form and insert quiz into table.
 * Don't show page unless user is at least admin level 2.
 * Validate required fields. Process properly completed form.
 * Insert/create quiz.
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

require_once 'session-validation.php';

//ensure admin user (admin is 2 and above)
if (($_SESSION['admin_num'] < 2)) {
    die('<h1>Restricted Action!</h1>');
}

// include database and object files
require_once dirname(__FILE__).'/config/core.php';
require_once dirname(__FILE__).'/config/database.php';
require_once dirname(__FILE__).'/objects/users.php';
require_once dirname(__FILE__).'/objects/quiz.php';
require_once dirname(__FILE__).'/objects/question.php';
require_once dirname(__FILE__).'/objects/quiz_question.php';
require_once dirname(__FILE__).'/objects/GUID.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$quiz = new Quiz($db);
$users = new User($db);
$guidcls = new GUID();
// get course and lesson ids
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : die('ERROR: missing COURSE ID.');
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : die('ERROR: missing LESSON ID.');
$quiz->lesson_id = $lesson_id;

// if the form was submitted
if ($_POST) {
    if (empty($_POST['quiz_name'])) {
        echo "<div class='alert alert-danger'>Name cannot be empty.</div>";
    } else {
        // set quiz property values
        $quiz->quiz_id = $_POST['quiz_id'];
        $quiz->quiz_name = $_POST['quiz_name'];
        $quiz->quiz_desc = $_POST['quiz_desc'];
        $quiz->admin_id = $_POST['admin_id'];

        // ensure the quiz does not exist
        if ($quiz->quizExists()) {
            echo "<div class='alert alert-danger alert-dismissable'>";
            echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
            echo "Quiz already exists.";
            echo "</div>";
        } elseif (($quiz->create())) {
            die();
        } else { // if unable to create the quiz, tell the user
            echo "<div class='alert alert-danger alert-dismissable'>";
            echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
            echo "Unable to create quiz.";
            echo "</div>";
        }
    }
}
?>

<?php
/*
  @todo
  create_quiz.php
  implement a different way to toggle menu than the 'data-toggle' attribute (might produce bugs when more than one editor open)
 */
?>
<button type='button' class='btn btn-primary mb-2 quiz-btn' data-course_id='<?php echo $course_id; ?>' data-lesson_id='<?php echo $lesson_id; ?>' data-toggle='collapse' data-target='#add_quiz-<?php echo $lesson_id; ?>' aria-expanded='true'>Add Quiz <i class='icon-plus3 ml-2'></i></button>

<div class="card collapse" id="add_quiz-<?php echo $lesson_id; ?>">
    <div class="card-header bg-white header-elements-inline">
        <h3 class="card-title">QuizzicUL Test Creator</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form class="wizard-form steps-basic quiz-creator" id='create-quiz-form-<?php echo $lesson_id; ?>' action='create_quiz.php?course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson_id; ?>' method='post' data-fouc>
                    <h6>Quiz Description</h6>
                    <fieldset>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="row">
                                    <label class="col-md-2 col-form-label text-lg-right" for="quizName">Quiz name:</label>
                                    <div class="col-md-8">
                                        <input type="text" name='quiz_name' class="form-control" id="quizName" placeholder="Unnamed Quiz" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-md-12 col-form-label" for="quizDesc-<?php echo $lesson_id; ?>">Quiz Description:</label>
                                <textarea name="quiz_desc" id="quizDesc-<?php echo $lesson_id; ?>"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                        <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
                    </fieldset>

                    <h6>Privileges</h6>
                    <fieldset>
                        <div class="form-group row">
                        <?php
                        /*
                          @todo
                          create_quiz.php
                          implement these to items
                         */
                        ?>
                            <div class="col-md-6">
                                <div class="row">
                                    <label class="col-md-4 col-form-label text-lg-right" for="exampleFormControlSelect1">Access Level</label>
                                    <div class="col-md-7">
                                        <select class="form-control" name='access_id' id="exampleFormControlSelect1" required>
                                            <?php
                                            $access_levels = $users->readAccessLevels();
                                            while ($row = $access_levels->fetch(\PDO::FETCH_ASSOC)) {
                                                echo '<option value=' . $row['access_num'] . '>' . $row['access_name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label class="col-md-3 col-form-label text-lg-right" for="exampleFormControlSelect1">Admin Level</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name='admin_id' id="exampleFormControlSelect1" required>
                                            <?php
                                            $admin_levels = $users->readAdminLevels();
                                            while ($row = $admin_levels->fetch(\PDO::FETCH_ASSOC)) {
                                                echo '<option value=' . $row['admin_num'] . '>' . $row['admin_name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h6>Questions</h6>
                    <fieldset class="question-step">
                        <div class="question-container">
                    <!--
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-lg-right" for="questionType">Question Type: </label>
                            <div class="col-md-9">
                                <select class="form-control form-control-uniform" name="question_type"  id="questionType" data-fouc="">
                                    <option value="opt1">Multiple Choice</option>
                                    <option value="opt2">True/False</option>
                                    <option value="opt3">Fill in the Blank</option>
                                    <option value="opt4">Free Response</option>
                                    <option value="opt5">Essay</option>
                                    <option value="opt6">Option 6</option>
                                    <option value="opt7">Option 7</option>
                                    <option value="opt8">Option 8</option>
                                </select>
                            </div>
                        </div>
                    -->
                        </div>
                        <div class="row mb-3 pb-3 border-bottom add-quiz-elements-container">
                            <button class="btn btn-sm btn-primary question-button mx-1" style="width:100px;">Add Question</button>
                            <button class="btn btn-sm btn-primary answer-button mx-1" style="width:100px;">Add Answer</button>
                            <!-- <input type="number" name="tester" class="" id="questionPoints-chris" value=1> -->
                        </div>
                    </fieldset>
                </form>
            </div> <!--/.col-md-10-->
            <!-- The following div is for the future QuizzicUL sidebar, which includes question bank, tags, etc. -->
            <!-- <div class="d-flex flex-column justify-content-end align-items-center col-md-2 py-2">
                <div class="mb-2" id="add-question-div" style="display:none;">
                    <button class="btn btn-sm btn-primary question-button" style="width:100px;">Add Question</button>
                </div>
                <div class="mb-2" id="add-answer-div" style="display:none;">
                    <button class="btn btn-sm btn-primary answer-button" style="width:100px;" disabled>Add Answer</button>
                </div>
            </div> -->
        </div> <!--/.row-->
    </div> <!--/.card-body-->
</div> <!--/.card-->

<script>

    // File input
    $('.form-control-uniform').uniform();

    var html;

    var question_counter = 1;

    // preliminary ckeditor instantiation when adding quiz
    $('.quiz-btn').on('click', function(e) {
        // get id for the textarea that will contain ckeditor
        var editor = `quizDesc-${$(this).data('lesson_id')}`
        
        // instantiate ckeditor for quiz description
        initSample(editor);

        $.get( // get GUID for first question 
            "libs/php/guid_for_js.php"
        ).done(function(data) {

            var answer_counter = 1;
            
            var question_id = data;
            
            html = `
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <label class="col-md-4 col-form-label text-lg-right" for="questionPoints">Points:</label>
                                    <!-- NOTE: this value is stored in the quiz_questions table -->
                                    <input type="number" name="points-${question_id}" class="form-control col-md-5" id="questionPoints-${question_id}" value="1" min="0">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                <!--deleted concepts tags here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-md-12 col-form-label" for="questionText">Question text:</label>                        
                        <textarea name="question_text-${question_id}" id="questionText-${question_id}" data-question_id="${question_id}" data-question_position="${question_counter++}" required></textarea>
                    </div>
                </div>
                <div class="form-group row pb-1 answer-container">
                    <div class="col-md-6 mb-2">
                        <div class="row">
                            <label class="col-md-4 col-form-label text-lg-right" for="quizAnswer"><span class="bg-green text-white px-1 py-1 rounded">Correct Answer:</span></label>
                            <div class="col-md-8">
                                <input type="text" name="quiz_answer-${answer_counter}" class="form-control quiz-answers" data-answer_position="${answer_counter++}" data-question_id="${question_id}" data-correct="yes" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="row">
                            <label class="col-md-4 col-form-label text-lg-right" for="quizAnswer">Answer:</label>
                            <div class="col-md-8">
                                <input type="text" name="quiz_answer-${answer_counter}" class="form-control quiz-answers" data-answer_position="${answer_counter++}" data-question_id="${question_id}" data-correct="no">
                            </div>
                        </div>
                    </div>                    
                </div>
            `;
            

            // append question to question step fieldset
            $('.question-container').append(html);

            // initialize ckeditor for first question
            initSample(`questionText-${question_id}`);

            // initialize tags for first question
            $('.select-multiple-tags').select2({
                tags: true
            });
        });
    });

    // create quiz
    $('.quiz-creator').on('submit', function(e) {
        e.preventDefault();

        // get data from quiz description ckeditor
        var quizID = "<?php echo trim($guidcls->uuid()); ?>";
        var quizEd = e.target.quiz_desc.id;
        
        var qzDescStripped = ul.stripUrl(quizEd);

        var qzName =  e.target.quiz_name.value.trim();
        var accessId = e.target.access_id.value.trim();
        var adminId = e.target.admin_id.value.trim();
        var courseId = e.target.course_id.value.trim();
        var lessonId = e.target.lesson_id.value.trim();
        var url = e.target.action;

        // get data from question text ckeditors
        var $questionEdArray = $("[id^='questionText-']");

        // get array of question points
        var $pointsArray = $("[id^='questionPoints-']");

        // get array of answers
        var $answerArray = $(".quiz-answers");

        if ((!e.target.quiz_name.value.trim())) {
// maybe make ternary above?
            alert('Must Supply Quiz Name.');
            return false;
        }
        // CHRISNOTE: access_id not used in quiz (yet?)
        if ((!e.target.access_id.value.trim())) {
// maybe make ternary above?
            alert('Must Select Access Level.');
            return false;
        }

        if ((!e.target.admin_id.value.trim())) {
// maybe make ternary above?
            alert('Must Select Admin Level.');
            return false;
        }

        var $content = $("#lesson-media-<?php echo $lesson_id; ?>");
        
        $.ajax({ // for quiz
            type: 'POST',
            url: url,
            data: {
                quiz_id:quizID,
                quiz_name:qzName,
                quiz_desc:qzDescStripped,
                lesson_id:lessonId,
                admin_id:adminId
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
        }).then(function(){
            $questionEdArray.each(function(index) {
                // get the value of the element from ckeditor, which is the question_text
                var $questionTextId = $(this).attr('id');
                var $questionText = ul.stripUrl($questionTextId);
                if ($questionText.trim() == "") {
                    return true;
                }
                
                // get the question GUID
                var $questionId = $(this).data('question_id');

                // get the points for the quiz question
                var $pointsVal = $($pointsArray[index]).val();

                // get the quiz question position
                var $questionPosition = $(this).data('question_position');

                // do an ajax call for each question which calls the php 
                // file to create the question, passing the question_text data
                $.ajax({ // for questions
                    type: 'POST',
                    url: 'create_question.php',
                    data: {
                        quiz_id:quizID,
                        question_text:$questionText,
                        question_id:$questionId,
                        admin_id:adminId,
                        points:$pointsVal,
                        question_position:$questionPosition,
                        bank_id:'c5312268-5404-4eeb-afc2-5b9c2f63d9bd'
                    },
                    timeout: 30000,
                    error: function(data) {
                        $content.html(data.responseText);
                    }                
                });
            });
        }).then(function() {
            $($answerArray).each(function() {
                // get answer text for answer
                var $answerText = $(this).val();

                if ($answerText.trim() == "") {
                    return true;
                }

                // get question GUID (for reference to question)
                var $question_id = $(this).data('question_id');

                // get answer position
                var $answerPosition = $(this).data('answer_position');

                // get whether or not answer is correct one
                var $correct = $(this).data('correct');

                $.ajax({ // for answers
                    type: 'POST',
                    url: 'create_answer.php',
                    data: {
                        answer_text:$answerText,
                        question_id:$question_id,
                        answer_position:$answerPosition,
                        correct:$correct
                    },
                    timeout: 30000,
                    error: function(data) {
                        $content.html(data.responseText);
                    }
                });
            });    
        }).done(function() {
            quizCreated(courseId, lessonId, qzName);
        }).fail(function() {
            $content.html('<div id="load">Unable to create quiz. Please try again soon.</div>');
        });
    });
</script>
