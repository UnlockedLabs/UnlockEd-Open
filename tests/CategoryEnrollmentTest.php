<?php
declare(strict_types=1);
/**
 * @file  CategoryAdministratorTest.php
 * @brief Unit test file for CategoryAdministrator object
 * 
 * This unit test creates a new category enrollment and then checks certain parameters
 * to ensure the category enrollment is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category_enrollments.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\CategoryEnrollment;

final class CategoryEnrollmentTest extends TestCase
{

    public function testCreate(): CategoryEnrollment
    {
        $guid = new GUID();
        
        // echo "Testing create category enrollment\n";
        $database = new Database();
        $db = $database->getConnection();
        $database->disableFKChecks();
        $cat_enrollment = new CategoryEnrollment($db);
        $cat_enrollment->category_id = $guid->uuid() . '_unit_test';
        $cat_enrollment->student_id = $guid->uuid() . '_unit_test';
        $catEnrollmentCreated = $cat_enrollment->create();
        $this->assertThat(
            $catEnrollmentCreated,
            $this->isTrue()
        );
        $database->enableFKChecks();
        return $cat_enrollment;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(CategoryEnrollment $cat_enrollment): CategoryEnrollment
    {
        // echo "Testing that previous create returned and instance of the CategoryEnrollment class\n";
        $this->assertThat(
            $cat_enrollment,
            $this->isInstanceOf('\unlockedlabs\unlocked\CategoryEnrollment')
        );
        return $cat_enrollment;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(CategoryEnrollment $cat_enrollment): CategoryEnrollment
    {
        // echo "Testing that CategoryEnrollment object has expected attributes\n";
        $this->assertThat(
            $cat_enrollment,
            $this->objectHasAttribute('category_id')
        );
        $this->assertThat(
            $cat_enrollment,
            $this->objectHasAttribute('student_id')
        );
        return $cat_enrollment;
    }

    /**
     * @depends testHasAttribute
     */
    public function testDeleteCategoryEnrollment(CategoryEnrollment $cat_enrollment): void
    {
        // echo "Testing delete CategoryEnrollment\n";
        $this->assertTrue(
            $cat_enrollment->delete()
        );
    }

}
