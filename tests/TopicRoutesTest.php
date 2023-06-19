<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Topic;
use unlockedlabs\unlocked\Course;

require_once dirname(__FILE__) . '/../config/core.php';
require_once dirname(__FILE__) . '/../config/database.php';
require_once dirname(__FILE__) . '/../objects/topic.php';
require_once dirname(__FILE__) . '/../objects/course.php';
require_once dirname(__FILE__) . '/CurlRequest.php';

final class TopicRoutesTest extends TestCase
{

    /* 

    This test checks:

        * admin login with curl and index.php script 
        * topic creation with curl and create_topic.php script
        * topic update with curl and update_topic.php script
        * topic creation with topic object
        * topic deletion with curl and delete_topic.php
        * whether the topic_ids for the courses under the deleted topic were changed to the unassigned topics/courses id 
   
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
    public function testCreate(): Topic
    {

        //create topic via post with curl

        $topic_name = "unit_test_topic_curl_" . uniqid();
        
        $create_topic_post = new CurlRequest(self::$settings['base_url'] . 'create_topic.php?id=94876a68-f185-4967-b5fb-f90859ffd5a8&category_name=' . $topic_name);
        $post_fields =  [
            'topic_name' => $topic_name,
            'topic_url' => '',
            'access_id' => 1,
            'admin_id' => 1,
        ]; 

        $create_topic_post->setPost($post_fields);
        $create_topic_post->createCurl();

        $this->assertSame($create_topic_post->getHttpStatus(), 200);
        $this->assertRegExp('/Topic was created./', $create_topic_post->__tostring());
        //$this->assertStringContainsString('Topic was created.', $create_topic_post->__tostring());

        // @todo add rowCount() to topic create method (do we need to on crate?)
        $topic = new Topic(self::$db);
        $topic->topic_name = $topic_name;
        $topic->category_id = '94876a68-f185-4967-b5fb-f90859ffd5a8';
        $topicCreated = $topic->topicExists();
        $this->assertThat(
            $topicCreated,
            $this->isTrue()
        );
        return $topic;
    }

    /**
     * @depends testCreate
     */
    public function testUpdateTopic(Topic $topic): Topic
    {

        //query db and get id of the topic created in testCreate
        $query = "SELECT id FROM topics
        WHERE topic_name = ?
        LIMIT 0,1";

        $stmt = self::$db->prepare($query);
        $stmt->bindParam(1, $topic->topic_name);
        $stmt->execute();
        $topic->id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

        $this->assertThat(
            isset($topic->id),
            $this->isTrue()
        );

        //send update post
        $url = self::$settings['base_url'] . "update_topic.php?topic_id=$topic->id&category_name=Unassigned%25Topics%25and%25Courses";

        $update_topic_post = new CurlRequest($url);
        $new_topic_name = $topic->topic_name . '_updated';
        $post_fields =  [
            'topic_name' => $new_topic_name,
            'topic_url' => '',
            'access_id' => 2,
            'admin_id' => 2,
        ]; 

        $update_topic_post->setPost($post_fields);
        $update_topic_post->createCurl();

        $this->assertSame($update_topic_post->getHttpStatus(), 200);
        $this->assertRegExp('/Topic was updated/', $update_topic_post->__tostring());
        //$this->assertStringContainsString('Topic was updated', $update_topic_post->__tostring());

        $topic->topic_name = $new_topic_name;
        // @todo add rowCount to the return of $topic->topicExists()
        $topicCreated = $topic->topicExists();
        $this->assertThat(
            $topicCreated,
            $this->isTrue()
        );
        return $topic;

    }

    /**
     * @depends testUpdateTopic
     */
    public function testTopicCourseRelation(Topic $topic): Topic
    {

        /* 

        This test ensures two test courses with the current
        topic id are created.

        When we delete our test topic below we need to ensure that
        these two courses have their topic_ids changed to
        the value set in self::$settings['unassigned_topic_id']
        which is the category id for 'Unassigned Topics and Courses'
        topic

        See delete_topic.php and populate-tables.php
        where we are hardcoding the 'Unassigned Topics and Courses'
        topic id
        
        */

        //create two courses with current topic id
        $course = new Course(self::$db);
        $course->course_desc = 'PHPUnit test course create';
        $course->topic_id = $topic->id;
        $course->course_img = '';
        $course->iframe = '';
        $course->access_id = 1;
        $course->admin_id = 1;

        $course->course_name = 'testCourseForTopicIdOneOfTwo_' . $topic->id;
        //test course One
        $this->assertThat(
            $course->create(),
            $this->isTrue()
        );

        //test course Two
        $course->course_name = 'testCourseForTopicIdTwoOfTwo_' . $topic->id;
        $this->assertThat(
            $course->create(),
            $this->isTrue()
        );

        return $topic;

    }

     /**
     * @depends testUpdateTopic
     */
    public function testDeleteTopic(Topic $topic): Topic
    {

        /* 
            @todo should we move this functionality to tearDownAfterClass()?
            This would ensure that the test courses we created in
            testTopicCourseRelation() delete if errors occur during testing.
        
        */

        $url = self::$settings['base_url'] . "delete_topic.php?topic_id=$topic->id";

        $delete_topic_post = new CurlRequest($url);
        $post_fields =  [
            'topic_id' => $topic->id,
        ]; 

        $delete_topic_post->setPost($post_fields);
        $delete_topic_post->createCurl();

        $this->assertSame($delete_topic_post->getHttpStatus(), 200);

        $this->assertRegExp('/Topic was deleted./', $delete_topic_post->__tostring());
        //$this->assertStringContainsString('Topic was deleted.', $delete_topic_post->__tostring());

        // @todo add rowCount() to topic create method
        $topicCreated = $topic->topicExists();
        $this->assertThat(
            $topicCreated,
            $this->isFalse()
        );

        return $topic;
    }

    /**
     * @depends testDeleteTopic
     */
    public function testTopicIds(Topic $topic): array
    {

        /* 
            ensure topic ids previously under the deleted topic
            now have the 'Unassigned Topics and Courses' topic id
        
        */

        $course_id_array = array();

        //query db and get topic_ids of the test topics 
        $query = "SELECT id, topic_id FROM courses
        WHERE course_name = ?
        OR course_name = ?";

        $stmt = self::$db->prepare($query);
        $name_one = 'testCourseForTopicIdOneOfTwo_' . $topic->id;
        $name_two = 'testCourseForTopicIdTwoOfTwo_' . $topic->id;
        $stmt->bindParam(1, $name_one);
        $stmt->bindParam(2, $name_two);
        $stmt->execute();

        //we only created 2 so there should only be 2
        $this->assertEquals(2, $stmt->rowCount());

        //ensure the test topics have the correct topic_id
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->assertEquals(self::$settings['unassigned_category_id'], $row['topic_id']);
            $course_id_array[] = $row['id'];
        }

        return $course_id_array;

    }

    /**
     * @depends testTopicIds
     */
    public function testDeleteTestCourses(array $course_id_array): void
    {

        $course = new Course(self::$db);

        //delete the topics that we created for this test
        foreach ($course_id_array as $id) {

            $course->id = $id;
            // @todo add rowCount to delete return method
            $this->assertThat(
                $course->delete(),
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