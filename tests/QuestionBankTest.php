<?php
declare(strict_types=1);
/**
 * @file  QuestionBankTest.php
 * @brief Unit test file for QuestionBank object
 * 
 * This unit test creates a new question bank and then checks certain parameters
 * to ensure the question bank is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/question_bank.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\QuestionBank;

final class QuestionBankTest extends TestCase
{

    public function testCreate(): QuestionBank
    {
        $guid = new GUID();
        
        // echo "Testing create question bank\n";
        $database = new Database();
        $db = $database->getConnection();
        $question_bank = new QuestionBank($db);
        $question_bank->bank_id = $guid->uuid();
        $question_bank->bank_name = 'Unit_test_question_bank_name';
        $questionBankCreated = $question_bank->create();
        $this->assertThat(
            $questionBankCreated,
            $this->isTrue()
        );
        return $question_bank;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(QuestionBank $question_bank): QuestionBank
    {
        // echo "Testing that previous create returned and instance of the QuestionBank class\n";
        $this->assertThat(
            $question_bank,
            $this->isInstanceOf('\unlockedlabs\unlocked\QuestionBank')
        );
        return $question_bank;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(QuestionBank $question_bank): QuestionBank
    {
        // echo "Testing that question bank object has expected attributes\n";
        $this->assertThat(
            $question_bank,
            $this->objectHasAttribute('bank_id')
        );
        $this->assertThat(
            $question_bank,
            $this->objectHasAttribute('bank_name')
        );
        $this->assertThat(
            $question_bank,
            $this->objectHasAttribute('created')
        );    
        return $question_bank;
    }

    /**
     * @depends testHasAttribute
     */
    public function testUpdateQuestionBank(QuestionBank $question_bank): QuestionBank
    {

        $question_bank->bank_name = 'Updated_unit_test_bank_name';
        $this->assertThat(
            $question_bank->update(),
            $this->isTrue()
        );

        return $question_bank;
    }

    /**
     * @depends testCreate
     */
    public function testDeleteQuestionBank(QuestionBank $question_bank): void
    {
        // echo "Testing delete question bank\n";
        $this->assertTrue(
            $question_bank->delete()
        );
    }

}
