<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Category;

require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category.php';

final class CategoryObjectTest extends TestCase
{
    private static $db;

    public static function setUpBeforeClass(): void
    {
        $database = new unlockedlabs\unlocked\Database();
        self::$db = $database->getConnection();
    }

    public function testCreate(): Category
    {

        // @todo add rowCount to category create method
        $category = new Category(self::$db);
        $category->category_name = 'unit_test_category';
        $category->access_id = 1;
        $category->admin_id = 1;
        $this->assertThat(
            $category->create(),
            $this->isTrue()
        );
        return $category;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(Category $category): Category
    {
        //echo "Testing that previous create returned and instance of the Category class\n";
        $this->assertThat(
            $category,
            $this->isInstanceOf('\unlockedlabs\unlocked\Category')
        );
        return $category;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(Category $category): Category
    {

        $classProperties = array('id', 'category_name', 'access_id', 'admin_id', 'created');

        foreach ($classProperties as $value) {
            $this->assertThat(
                $category,
                $this->objectHasAttribute($value)
            );
        }

        return $category;
        
    }

    /**
     * @depends testHasAttribute
     */
    public function testUpdateCategory(Category $category): Category
    {

        $category->category_name = "update_test_category_name";

        $this->assertThat(
            $category->update(),
            $this->isTrue()
        );

        return $category;
    }

     /**
     * @depends testUpdateCategory
     */
    public function testDeleteCategory(Category $category): void
    {
        $this->assertTrue(
            $category->delete()
        );
    }

    public static function tearDownAfterClass(): void
    {
        self::$db = null;
    }

}
