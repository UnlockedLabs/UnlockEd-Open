<?php
declare(strict_types=1);
/**
 * @file  CohortEnrollmentTest.php
 * @brief Unit test file for CohortEnrollment object
 * 
 * This unit test creates a new cohort enrollment and then checks certain parameters
 * to ensure the cohort enrollment is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/cohort_enrollments.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\CohortEnrollment;

final class CohortEnrollmentTest extends TestCase
{

    public function testCreate(): CohortEnrollment
    {
        $guid = new GUID();
        
        // echo "Testing create cohort enrollment\n";
        $database = new Database();
        $db = $database->getConnection();
        $database->disableFKChecks();
        $cohort_enrollment = new CohortEnrollment($db);
        $cohort_enrollment->cohort_id = $guid->uuid() . '_unit_test';
        $cohort_enrollment->student_id = $guid->uuid() . '_unit_test';
        $cohortEnrollmentCreated = $cohort_enrollment->create();
        $this->assertThat(
            $cohortEnrollmentCreated,
            $this->isTrue()
        );
        $database->enableFKChecks();
        return $cohort_enrollment;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(CohortEnrollment $cohort_enrollment): CohortEnrollment
    {
        // echo "Testing that previous create returned and instance of the CohortEnrollment class\n";
        $this->assertThat(
            $cohort_enrollment,
            $this->isInstanceOf('\unlockedlabs\unlocked\CohortEnrollment')
        );
        return $cohort_enrollment;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(CohortEnrollment $cohort_enrollment): CohortEnrollment
    {
        // echo "Testing that CohortEnrollment object has expected attributes\n";
        $this->assertThat(
            $cohort_enrollment,
            $this->objectHasAttribute('cohort_id')
        );
        $this->assertThat(
            $cohort_enrollment,
            $this->objectHasAttribute('student_id')
        );
        return $cohort_enrollment;
    }

    /**
     * @depends testHasAttribute
     */
    public function testDeleteCohortEnrollment(CohortEnrollment $cohort_enrollment): void
    {
        // echo "Testing delete CohortEnrollment\n";
        $this->assertTrue(
            $cohort_enrollment->delete()
        );
    }

}
