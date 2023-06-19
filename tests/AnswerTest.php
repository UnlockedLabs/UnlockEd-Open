<?php
declare(strict_types=1);
/**
 * @file  AnswerTest.php
 * @brief Unit test file for Answer object
 * 
 * This unit test creates a new answer and then checks certain parameters
 * to ensure the answer is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/answer.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\Answer;

final class AnswerTest extends TestCase
{

    public function testCreate(): Answer
    {
        $guid = new GUID();
        
        // echo "Testing create answer\n";
        $database = new Database();
        $db = $database->getConnection();
        $database->disableFKChecks();
        $answer = new Answer($db);
        $answer->answer_text = 'Answer string for testing';
        $answer->question_id = $guid->uuid();
        $answer->correct = 'yes';
        $answer->answer_position = 1;
        $answerCreated = $answer->create();
        $database->enableFKChecks();
        $this->assertThat(
            $answerCreated,
            $this->isTrue()
        );
        return $answer;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(Answer $answer): Answer
    {
        // echo "Testing that previous create returned and instance of the Answer class\n";
        $this->assertThat(
            $answer,
            $this->isInstanceOf('\unlockedlabs\unlocked\Answer')
        );
        return $answer;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(Answer $answer): Answer
    {
        // echo "Testing that answer object has expected attributes\n";

        $this->assertThat(
            $answer,
            $this->objectHasAttribute('answer_id')
        );
        $this->assertThat(
            $answer,
            $this->objectHasAttribute('answer_text')
        );
        $this->assertThat(
            $answer,
            $this->objectHasAttribute('question_id')
        );
        $this->assertThat(
            $answer,
            $this->objectHasAttribute('correct')
        );    
        $this->assertThat(
            $answer,
            $this->objectHasAttribute('created')
        );
        return $answer;
    }

    /**
     * @depends testInstanceOf
     */
    public function testUpdateAnswer(Answer $answer): Answer
    {

        $answer->answer_text = "Updated_answer_text_for_testing";

        $this->assertThat(
            $answer->update(),
            $this->isTrue()
        );

        return $answer;
    }

    /**
     * @depends testUpdateAnswer
     */
    public function testDeleteAnswer(Answer $answer): void
    {
        // echo "Testing delete answer\n";
        $this->assertTrue(
            $answer->delete()
        );
    }

}
