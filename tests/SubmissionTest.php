<?php
declare(strict_types=1);
/**
 * @file  SubmissionTest.php
 * @brief Unit test file for Submission object
 * 
 * This unit test creates a new submission and then checks certain parameters
 * to ensure the submission is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/submission.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\Submission;

final class SubmissionTest extends TestCase
{

    // @todo setup arrays for questions, submitted answers

    public function testCreate(): Submission
    {
        $guid = new GUID();
        
        // echo "Testing create submission\n";
        $database = new Database();
        $db = $database->getConnection();
        $database->disableFKChecks();
        $submission = new Submission($db);
        $submission->student_id = $guid->uuid();
        $submission->assignment_id = $guid->uuid();
        $submission->type = 'quiz';
        $submission->attempt = 1;
        $submission->score = 99;
        $submission->grade = '99';
        $submission->questions = ''; // setup an array of question guids
        $submission->submitted_answers = 'a,b,c'; // setup an array of answers that matches the count of the questions
        $submission->comments = 'Unit_test_submission_comment';
        $submissionCreated = $submission->create();
        $database->enableFKChecks();
        $this->assertThat(
            $submissionCreated,
            $this->isTrue()
        );
        // $database->enableFKChecks();
        return $submission;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(Submission $submission): Submission
    {
        // echo "Testing that previous create returned and instance of the Cohort class\n";
        $this->assertThat(
            $submission,
            $this->isInstanceOf('\unlockedlabs\unlocked\Submission')
        );
        return $submission;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(Submission $submission): Submission
    {
        // echo "Testing that cohort object has expected attributes\n";
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('id')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('student_id')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('assignment_id')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('type')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('attempt')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('score')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('grade')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('questions')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('submitted_answers')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('comments')
        );
        $this->assertThat(
            $submission,
            $this->objectHasAttribute('submitted')
        );    
        return $submission;
    }

    /**
     * @depends testHasAttribute
     */
    public function testUpdateSubmission(Submission $submission): Submission
    {

        $submission->attempt = 2;
        $submission->score = 11;
        $submission->grade = '11';
        $submission->submitted_answers = 'd,e,f';
        $submission->comments = 'Updated_unit_test_submission_comment';
        $submission->submitted = date('Y-m-d H:i:s');
        $this->assertThat(
            $submission->update(),
            $this->isTrue()
        );        

        return $submission;
    }

    /**
     * @depends testCreate
     */
    public function testDeleteSubmission(Submission $submission): void
    {
        // echo "Testing delete cohort\n";
        $this->assertTrue(
            $submission->delete()
        );
    }

}
