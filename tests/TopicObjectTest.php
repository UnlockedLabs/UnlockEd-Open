<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Topic;

require_once dirname(__FILE__) . '/../config/core.php';
require_once dirname(__FILE__) . '/../config/database.php';
require_once dirname(__FILE__) . '/../objects/topic.php';

final class TopicObjectTest extends TestCase
{
    private static $db;
    private static $settings;

    public static function setUpBeforeClass(): void
    {
        $database = new unlockedlabs\unlocked\Database();
        self::$db = $database->getConnection();
        self::$settings = parse_ini_file("settings.ini");
    }

    public function testCreate(): Topic
    {

        // @todo add rowCount to topic create method
        $topic = new Topic(self::$db);
        $topic->category_id = '94876a68-f185-4967-b5fb-f90859ffd5a8';
        $topic->topic_name = 'unit_test_topic';
        $topic->topic_url = '';
        $topic->access_id = 1;
        $topic->admin_id = 1;
        $this->assertThat(
            $topic->create(),
            $this->isTrue()
        );
        return $topic;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(Topic $topic): Topic
    {
        //echo "Testing that previous create returned and instance of the Topic class\n";
        $this->assertThat(
            $topic,
            $this->isInstanceOf('\unlockedlabs\unlocked\Topic')
        );
        return $topic;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(Topic $topic): Topic
    {

        $classProperties = array('topic_url', 'category_id', 'description', 'id', 'topic_name', 'access_id', 'admin_id', 'created');

        foreach ($classProperties as $value) {
            $this->assertThat(
                $topic,
                $this->objectHasAttribute($value)
            );
        }

        return $topic;
    }

    /**
     * @depends testHasAttribute
     */
    public function testUpdateTopic(Topic $topic): Topic
    {

        $topic->topic_name = "update_test_topic_name";

        $this->assertThat(
            $topic->update(),
            $this->isTrue()
        );

        return $topic;
    }

     /**
     * @depends testUpdateTopic
     */
    public function testDeleteTopic(Topic $topic): void
    {
        $this->assertTrue(
            $topic->delete()
        );
    }

    public static function tearDownAfterClass(): void
    {
        self::$db = null;
    }

}
