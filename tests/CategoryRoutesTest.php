<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Category;
use unlockedlabs\unlocked\Topic;

require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
require_once dirname(__FILE__).'/../objects/category.php';
require_once dirname(__FILE__).'/../objects/topic.php';
require_once dirname(__FILE__).'/CurlRequest.php';

final class CategoryRoutesTest extends TestCase
{

    /* 

    This test checks:

        * admin login with curl and index.php script 
        * category creation with curl and create_category.php script
        * category update with curl and update_category.php script
        * topic creation with topic object
        * category deletion with curl and delete_category.php
        * whether the category_ids for the topics under the deleted category were changed to the unassigned topics/courses id 
   
    */

    private static $db;
    private static $settings;

    public static function setUpBeforeClass(): void
    {
        $database = new unlockedlabs\unlocked\Database();
        self::$db = $database->getConnection();
        self::$settings = parse_ini_file("settings.ini");
    }

    public function testEnsureLogin(): void
    {

        /* 

            Login and get PHPSESSID.
            PHPSESSID will be saved in cookie.txt.
            See $_cookieFileLocation property of the CurlRequest
            class for the location of cookie.txt.
        
        */

        $login_post = new CurlRequest(self::$settings['base_url'] . 'index.php');
        $post_fields =  [
            'username' => 'haley',
            'password' => 'pwd',
        ];
        $login_post->setPost($post_fields);
        $login_post->createCurl();

        $this->assertSame($login_post->getHttpStatus(), 200);

        // @todo consider using a hidden guid in the html instead of id="content-area-div"
        $this->assertRegExp('/id="content-area-div"/', $login_post->__tostring());
        //$this->assertStringContainsString('id="content-area-div"', $login_post->__tostring());
                
    }

     /**
     * @depends testEnsureLogin
     */
    public function testCreate(): Category
    {

        //create category via post with curl

        $category_name = "unit_test_category_curl_" . uniqid();
        
        $create_category_post = new CurlRequest(self::$settings['base_url'] . 'create_category.php');
        $post_fields =  [
            'category_name' => $category_name,
            'access_id' => 1,
            'admin_id' => 1,
        ]; 

        $create_category_post->setPost($post_fields);
        $create_category_post->createCurl();

        $this->assertSame($create_category_post->getHttpStatus(), 200);
        $this->assertRegExp('/Category was created./', $create_category_post->__tostring());
        //$this->assertStringContainsString('Category was created.', $create_category_post->__tostring());

        // @todo add rowCount() to category create method (do we need to on crate?)
        $category = new Category(self::$db);
        $category->category_name = $category_name;
        $categoryCreated = $category->categoryExists();
        $this->assertThat(
            $categoryCreated,
            $this->isTrue()
        );
        return $category;
    }

    /**
     * @depends testCreate
     */
    public function testUpdateCategory(Category $category): Category
    {

        //query db and get id of the category created in testCreate
        $query = "SELECT id FROM categories
        WHERE category_name = ?
        LIMIT 0,1";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $category->category_name);
        $stmt->execute();
        $category->id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

        $this->assertThat(
            isset($category->id),
            $this->isTrue()
        );

        //send update post
        $url = self::$settings['base_url'] . "update_category.php?id=$category->id";

        $update_category_post = new CurlRequest($url);
        $new_category_name = $category->category_name . '_updated';
        $post_fields =  [
            'category_name' => $new_category_name,
            'access_id' => 2,
            'admin_id' => 2,
        ]; 

        $update_category_post->setPost($post_fields);
        $update_category_post->createCurl();

        $this->assertSame($update_category_post->getHttpStatus(), 200);
        $this->assertRegExp('/Category was updated/', $update_category_post->__tostring());
        //$this->assertStringContainsString('Category was updated', $update_category_post->__tostring());

        $category->category_name = $new_category_name;
        // @todo add rowCount to the return of $category->categoryExists()
        $categoryCreated = $category->categoryExists();
        $this->assertThat(
            $categoryCreated,
            $this->isTrue()
        );
        return $category;

    }

    /**
     * @depends testUpdateCategory
     */
    public function testCategoryTopicRelation(Category $category): Category
    {

        /* 

        This test ensures two test topics with the current
        category id are created.

        When we delete our test category below we need to ensure that
        these two topics have their category_ids changed to
        the value set in self::$settings['unassigned_category_id']
        which is the category id for 'Unassigned Topics and Courses'
        category

        See delete_category.php and populate-tables.php
        where we are hardcoding the 'Unassigned Topics and Courses'
        category id
        
        */

        //create two topics with current category id
        $topic = new Topic(self::$db);
        $topic->topic_name = 'testTopicForCatIdOneOfTwo_' . $category->id;
        $topic->category_id = $category->id;
        $topic->topic_url = '';
        $topic->access_id = 1;
        $topic->admin_id = 1;

        //test topic One
        $this->assertThat(
            $topic->create(),
            $this->isTrue()
        );

        //test topic Two
        $topic->topic_name = 'testTopicForCatIdTwoOfTwo_' . $category->id;
        $this->assertThat(
            $topic->create(),
            $this->isTrue()
        );

        return $category;

    }

     /**
     * @depends testUpdateCategory
     */
    public function testDeleteCategory(Category $category): Category
    {

        /* 
            @todo should we move this functionality to tearDownAfterClass()?
            This would ensure that the test topics we created in
            testCategoryTopicRelation() delete if errors occur during testing.
        
        */

        $url = self::$settings['base_url'] . "delete_category.php?id=$category->id";

        $delete_category_post = new CurlRequest($url);
        $post_fields =  [
            'category_id' => $category->id,
        ]; 

        $delete_category_post->setPost($post_fields);
        $delete_category_post->createCurl();

        $this->assertSame($delete_category_post->getHttpStatus(), 200);
        $this->assertRegExp('/Category was deleted./', $delete_category_post->__tostring());
        //$this->assertStringContainsString('Category was deleted.', $delete_category_post->__tostring());

        // @todo add rowCount() to category create method
        $categoryCreated = $category->categoryExists();
        $this->assertThat(
            $categoryCreated,
            $this->isFalse()
        );

        return $category;
    }

    /**
     * @depends testDeleteCategory
     */
    public function testTopicIds(Category $category): array
    {

        /* 
            ensure topic ids previously under the deleted category
            now have the 'Unassigned Topics and Courses' category id
        
        */

        $topic_id_array = array();

        //query db and get category_ids of the test topics 
        $query = "SELECT id, category_id FROM topics
        WHERE topic_name = ?
        OR topic_name = ?";

        $stmt = self::$db->prepare($query);
        $name_one = 'testTopicForCatIdOneOfTwo_' . $category->id;
        $name_two = 'testTopicForCatIdTwoOfTwo_' . $category->id;
        $stmt->bindParam(1, $name_one);
        $stmt->bindParam(2, $name_two);
        $stmt->execute();

        //we only created 2 so there should only be 2
        $this->assertEquals(2, $stmt->rowCount());

        //ensure the test topics have the correct category_id
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->assertEquals(self::$settings['unassigned_category_id'], $row['category_id']);
            $topic_id_array[] = $row['id'];
        }

        return $topic_id_array;

    }

    /**
     * @depends testTopicIds
     */
    public function testDeleteTestTopics(array $topic_id_array): void
    {

        $topic = new Topic(self::$db);

        //delete the topics that we created for this test
        foreach ($topic_id_array as $id) {

            $topic->id = $id;
            // @todo add rowCount to delete return method
            $this->assertThat(
                $topic->delete(),
                $this->isTrue()
            );

        }

    }

    public static function tearDownAfterClass(): void
    {

        //logout which resets PHPSESSID in cookie.txt
        $logout = new CurlRequest(self::$settings['base_url'] . 'index.php?logout=1');
        $logout->createCurl();
        self::assertSame($logout->getHttpStatus(), 200);
        self::assertRegExp('/<input type="text" class="form-control" name="username" placeholder="Username">/', $logout->__tostring());

        //ensure database connection closes
        self::$db = null;

    }

}
?>