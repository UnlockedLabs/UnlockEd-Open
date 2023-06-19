<?php
declare(strict_types=1);
/**
 * @file  QuizQuestionTest.php
 * @brief Unit test file for QuizQuestion object
 * 
 * This unit test creates a new quiz question and then checks certain parameters
 * to ensure the quiz question is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/quiz_question.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\QuizQuestion;

final class QuizQuestionTest extends TestCase
{

    public function testCreate(): QuizQuestion
    {
        $guid = new GUID();
        
        // echo "Testing create quiz question\n";
        $database = new Database();
        $db = $database->getConnection();
        $database->disableFKChecks();
        $quiz_question = new QuizQuestion($db);
        $quiz_question->quiz_id = $guid->uuid();
        $quiz_question->question_id = $guid->uuid();
        $quiz_question->points = 999;
        $quiz_question->question_position = 99;
        $quizQuestionCreated = $quiz_question->create();
        $database->enableFKChecks();
        $this->assertThat(
            $quizQuestionCreated,
            $this->isTrue()
        );
        return $quiz_question;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(QuizQuestion $quizQuestion): QuizQuestion
    {
        // echo "Testing that previous create returned and instance of the Question class\n";
        $this->assertThat(
            $quizQuestion,
            $this->isInstanceOf('\unlockedlabs\unlocked\QuizQuestion')
        );
        return $quizQuestion;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(QuizQuestion $quizQuestion): QuizQuestion
    {
        // echo "Testing that question object has expected attributes\n";

        $this->assertThat(
            $quizQuestion,
            $this->objectHasAttribute('quiz_id')
        );
        $this->assertThat(
            $quizQuestion,
            $this->objectHasAttribute('question_id')
        );
        $this->assertThat(
            $quizQuestion,
            $this->objectHasAttribute('points')
        );
        $this->assertThat(
            $quizQuestion,
            $this->objectHasAttribute('question_position')
        );    
        return $quizQuestion;
    }

    /**
     * @depends testCreate
     */
    public function testDeleteQuizQuestion(QuizQuestion $quizQuestion): void
    {
        // echo "Testing delete question\n";
        $this->assertTrue(
            $quizQuestion->delete()
        );
    }

}
