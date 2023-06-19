<?php
declare(strict_types=1);
/**
 * @file  QuestionTest.php
 * @brief Unit test file for Question object
 * 
 * This unit test creates a new question and then checks certain parameters
 * to ensure the user is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/question.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\Question;

final class QuestionTest extends TestCase
{

    public function testCreate(): Question
    {
        $guid = new GUID();
        
        // echo "Testing create question\n";
        $database = new Database();
        $db = $database->getConnection();
        $question = new Question($db);
        $question->question_id = $guid->uuid();
        $question->question_text = 'Is this a valid question test?';
        $question->bank_id = 'c5312268-5404-4eeb-afc2-5b9c2f63d9bd';
        $question->admin_id = 1;
        $question->created = date('Y-m-d H:i:s');
        $questionCreated = $question->create();
        $this->assertThat(
            $questionCreated,
            $this->isTrue()
        );
        return $question;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(Question $question): Question
    {
        // echo "Testing that previous create returned and instance of the Question class\n";
        $this->assertThat(
            $question,
            $this->isInstanceOf('\unlockedlabs\unlocked\Question')
        );
        return $question;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(Question $question): Question
    {
        // echo "Testing that question object has expected attributes\n";

        $this->assertThat(
            $question,
            $this->objectHasAttribute('question_id')
        );
        $this->assertThat(
            $question,
            $this->objectHasAttribute('question_text')
        );
        $this->assertThat(
            $question,
            $this->objectHasAttribute('bank_id')
        );
        $this->assertThat(
            $question,
            $this->objectHasAttribute('admin_id')
        );    
        $this->assertThat(
            $question,
            $this->objectHasAttribute('created')
        );
        return $question;
    }

    /**
     * @depends testCreate
     */
    public function testDeleteQuestion(Question $question): void
    {
        // echo "Testing delete question\n";
        $this->assertTrue(
            $question->delete()
        );
    }

}
