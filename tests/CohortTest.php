<?php
declare(strict_types=1);
/**
 * @file  CohortTest.php
 * @brief Unit test file for Cohort object
 * 
 * This unit test creates a new cohort and then checks certain parameters
 * to ensure the cohort is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\Cohort;

final class CohortTest extends TestCase
{

    public function testCreate(): Cohort
    {
        $guid = new GUID();
        
        // echo "Testing create cohort\n";
        $database = new Database();
        $db = $database->getConnection();
        $database->disableFKChecks();
        $cohort = new Cohort($db);
        $cohort->id = $guid->uuid();
        $cohort->cohort_name = 'Unit_test_cohort_name';
        $cohort->facilitator_id = $guid->uuid();
        $cohort->course_id = $guid->uuid();
        $cohortCreated = $cohort->create();
        $this->assertThat(
            $cohortCreated,
            $this->isTrue()
        );
        // $database->enableFKChecks();
        return $cohort;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(Cohort $cohort): Cohort
    {
        // echo "Testing that previous create returned and instance of the Cohort class\n";
        $this->assertThat(
            $cohort,
            $this->isInstanceOf('\unlockedlabs\unlocked\Cohort')
        );
        return $cohort;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(Cohort $cohort): Cohort
    {
        // echo "Testing that cohort object has expected attributes\n";
        $this->assertThat(
            $cohort,
            $this->objectHasAttribute('cohort_name')
        );
        $this->assertThat(
            $cohort,
            $this->objectHasAttribute('facilitator_id')
        );
        $this->assertThat(
            $cohort,
            $this->objectHasAttribute('course_id')
        );
        $this->assertThat(
            $cohort,
            $this->objectHasAttribute('created')
        );    
        return $cohort;
    }

    /**
     * @depends testHasAttribute
     */
    public function testUpdateCohort(Cohort $cohort): Cohort
    {

        $guid = new GUID();
        $database = new Database();
        $database->getConnection();
        $cohort->cohort_name = "Updated_cohort_name_for_testing";
        $cohort->facilitator_id = $guid->uuid();
        $cohort->course_id = $guid->uuid();
        $this->assertThat(
            $cohort->update(),
            $this->isTrue()
        );
        $database->enableFKChecks();

        return $cohort;
    }

    /**
     * @depends testCreate
     */
    public function testDeleteCohort(Cohort $cohort): void
    {
        // echo "Testing delete cohort\n";
        $this->assertTrue(
            $cohort->delete()
        );
    }

}
