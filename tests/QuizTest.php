<?php
declare(strict_types=1);
/**
 * @file  QuizTest.php
 * @brief Unit test file for Quiz object
 * 
 * This unit test creates a new quiz and then checks certain parameters
 * to ensure the quiz is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/quiz.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\Quiz;

final class QuizTest extends TestCase
{

    public function testCreate(): Quiz
    {
        $guid = new GUID();
        
        // echo "Testing create quiz\n";
        $database = new Database();
        $db = $database->getConnection();
        $database->disableFKChecks();
        $quiz = new Quiz($db);
        $quiz->quiz_id = $guid->uuid();
        $quiz->quiz_name = 'Unit_test_quiz_name';
        $quiz->lesson_id = $guid->uuid();
        $quiz->quiz_desc = 'Unit_test_quiz_desc';
        $quiz->admin_id = 1;
        $quizCreated = $quiz->create();
        $this->assertThat(
            $quizCreated,
            $this->isTrue()
        );
        // $database->enableFKChecks();
        return $quiz;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(Quiz $quiz): Quiz
    {
        // echo "Testing that previous create returned and instance of the Cohort class\n";
        $this->assertThat(
            $quiz,
            $this->isInstanceOf('\unlockedlabs\unlocked\Quiz')
        );
        return $quiz;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(Quiz $quiz): Quiz
    {
        // echo "Testing that cohort object has expected attributes\n";
        $this->assertThat(
            $quiz,
            $this->objectHasAttribute('quiz_id')
        );
        $this->assertThat(
            $quiz,
            $this->objectHasAttribute('quiz_name')
        );
        $this->assertThat(
            $quiz,
            $this->objectHasAttribute('lesson_id')
        );
        $this->assertThat(
            $quiz,
            $this->objectHasAttribute('quiz_desc')
        );
        $this->assertThat(
            $quiz,
            $this->objectHasAttribute('admin_id')
        );
        $this->assertThat(
            $quiz,
            $this->objectHasAttribute('created')
        );    
        return $quiz;
    }

    /**
     * @depends testHasAttribute
     */
    public function testUpdateQuiz(Quiz $quiz): Quiz
    {

        $guid = new GUID();
        $database = new Database();
        $database->getConnection();
        $quiz->quiz_name = 'Updated_unit_test_quiz_name';
        $quiz->quiz_desc = 'Updated_unit_test_quiz_desc';
        $quiz->lesson_id = $guid->uuid();
        $quiz->admin_id = 2;
        $quiz->quiz_id = $guid->uuid();
        $this->assertThat(
            $quiz->update(),
            $this->isTrue()
        );
        $database->enableFKChecks();

        return $quiz;
    }

    /**
     * @depends testCreate
     */
    public function testDeleteQuiz(Quiz $quiz): void
    {
        // echo "Testing delete cohort\n";
        $this->assertTrue(
            $quiz->delete()
        );
    }

}
