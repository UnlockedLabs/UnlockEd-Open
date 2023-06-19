<?php
declare(strict_types=1);
/**
 * @file  CategoryAdministratorTest.php
 * @brief Unit test file for CategoryAdministrator object
 * 
 * This unit test creates a new category administrator and then checks certain parameters
 * to ensure the category administrator is well formed.
 */
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category_administrators.php';
require_once dirname(__FILE__).'/../objects/GUID.php';
use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Database;
use unlockedlabs\unlocked\GUID;
use unlockedlabs\unlocked\CategoryAdministrator;

final class CategoryAdministratorTest extends TestCase
{

    public function testCreate(): CategoryAdministrator
    {
        $guid = new GUID();
        
        // echo "Testing create category administrator\n";
        $database = new Database();
        $db = $database->getConnection();
        $database->disableFKChecks();
        $cat_admin = new CategoryAdministrator($db);
        $cat_admin->category_id = $guid->uuid() . '_unit_test';
        $cat_admin->administrator_id = $guid->uuid() . '_unit_test';
        $catadminCreated = $cat_admin->create();
        $this->assertThat(
            $catadminCreated,
            $this->isTrue()
        );
        $database->enableFKChecks();
        return $cat_admin;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(CategoryAdministrator $cat_admin): CategoryAdministrator
    {
        // echo "Testing that previous create returned and instance of the CategoryAdministrator class\n";
        $this->assertThat(
            $cat_admin,
            $this->isInstanceOf('\unlockedlabs\unlocked\CategoryAdministrator')
        );
        return $cat_admin;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(CategoryAdministrator $cat_admin): CategoryAdministrator
    {
        // echo "Testing that CategoryAdministrator object has expected attributes\n";
        $this->assertThat(
            $cat_admin,
            $this->objectHasAttribute('category_id')
        );
        $this->assertThat(
            $cat_admin,
            $this->objectHasAttribute('administrator_id')
        );
        return $cat_admin;
    }

    /**
     * @depends testCreate
     */
    public function testDeleteCategoryAdministrator(CategoryAdministrator $cat_admin): void
    {
        // echo "Testing delete CategoryAdministrator\n";
        $this->assertTrue(
            $cat_admin->delete()
        );
    }

}
