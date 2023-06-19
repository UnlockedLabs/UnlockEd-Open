<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use unlockedlabs\unlocked\Course;

require_once dirname(__FILE__) . '/../config/core.php';
require_once dirname(__FILE__) . '/../config/database.php';
require_once dirname(__FILE__) . '/../objects/course.php';

final class CourseObjectTest extends TestCase
{
    private static $db;

    public static function setUpBeforeClass(): void
    {
        $database = new unlockedlabs\unlocked\Database();
        self::$db = $database->getConnection();
    }

    public function testCreate(): Course
    {

        // @todo add rowCount to course create method
        $course = new Course(self::$db);

        // set course property values
        $course->course_name = '1111_AAAA_unit_test_course';
        $course->course_desc = 'This is a test in CourseObjectTest.php (unless someone renames it)';
        $course->topic_id = '14876a68-f185-4967-b4fb-f90529ffd5a8';
        $course->course_img = 'courseObjectTest.png'; //really just the filename (pic.png)
        //url data string representing the picture
        $course->course_img_url = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAACfElEQVRIidWWTU8TURSG334gIYSUyNZIXIkIlJTWiAajJq5JTIwxGpCmlJaJxJ2/w1hoS4qY+E/ckBZU4sf/MBEyTKdzHxczhaIQWsrGk9zF3Jn7PnPOfe/Jlf6nsCRah6RBSZELFaeQwqtmoDjThMwGoO4BLmAXr0MtjfvxKk5+vAmZlzTUNYCXCajMgGuAPcjM4GrwYiCWxD4H8CEJn56CC7YBQj1NwIqkxzpvuSwJ8qM4xRFogA14uUmMBPxuQtKShnXWxv/tlsMNNi68uw1befB8iAmJrSNXnZ3FodjbJKYSh9VJGtkpfy6doF4ZYR9wARamOIgKA62AJ5KunJqFJeEu3sdkk7A4hrs0jbcYAJ6loBSH7WWMaYC3h1EfXxXpDLAj8T0YXxTl4HUCS8LOTcDGBOwuY0k4+XEaUWFT7wxw6h6sJKBwA3Yz/nN2FDcqauGe9gFBDEh6KOm5pDlJ85YEmQc45WuwveQDMkmM+qiGoq3toz0nBR/EJF2WNNQUsFcTsPMGS2J/4S5ur6iG/Xe/Xk13fegGLIn656yfQe4mRMUP9QYluwVrqe4gjVIMg//HTm6KhsLUguxMPo5rjdEo3zsfhEo/jaIwRuAKz+v7xwjVUATm7kAx3jmEkqAQAUJ4Xi8gvPIgklYCMcuS+CbBUqrVWe33KHtTOBvCICAE6+FWe45JemRJ/AwObKfOklsSvBduPYyzISiEcdZ6WkUuKTBDTR30qGOlKgvP7oeKsDcF6yeLWMcBbR1AH1AUhhCUhHckng4Ax0QCwIuOAJK0h2A9hK9xKD6sky8CMfmdoX1Ay8LZM8SbEZHfgjq+hTRbSuw8i9uJP5Op/waFsDioAAAAAElFTkSuQmCC';
        $course->iframe = '';
        $course->access_id = 1;
        $course->admin_id = 1;
        $course->old_course_img = ''; //@todo write a test for when this is set 

        //this path is relative (if tests dir moves then the path may be off)
        $course->image_dir = '../media/images/courses/';

        $this->assertThat(
            $course->create(),
            $this->isTrue()
        );
        return $course;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(Course $course): Course
    {

        $this->assertThat(
            $course,
            $this->isInstanceOf('\unlockedlabs\unlocked\Course')
        );
        return $course;

    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(Course $course): Course
    {

        $classProperties = array(
            'id',
            'new_id',
            'course_name',
            'course_desc',
            'topic_id',
            'new_topic_id',
            'iframe',
            'access_id',
            'admin_id',
            'course_img',
            'course_img_url',
            'old_course_img',
            'image_dir');

        foreach ($classProperties as $value) {
            $this->assertThat(
                $course,
                $this->objectHasAttribute($value)
            );
        }

        return $course;
    }

    /**
     * @depends testHasAttribute
     */
    public function testCreateCourseImageDirectory(Course $course): Course
    {

        $file_path = $course->image_dir . $course->id;
        $this->assertTrue($course->createCourseImageDirectory());
        $this->assertDirectoryExists($course->image_dir);
        return $course;

    }

    /**
     * @depends testCreateCourseImageDirectory
     */
    public function testWriteCourseImage(Course $course): Course
    {

        $this->assertTrue($course->writeCourseImage());
        $this->assertFileExists($course->image_dir . $course->id . '/' . $course->course_img);
        return $course;

    }

    /**
     * @depends testWriteCourseImage
     */
    public function testDeleteCourseImageDirectory(Course $course): Course
    {

        /* 
         Be really cautious calling deleteCourseImageDirectory().
         Always call ensureCourseImageDirectory first().
         I once deleted ./ by mistake using the unchecked deleteCourseImageDirectory()
        */

        $this->assertTrue($course->ensureCourseImageDirectory());
        $this->assertTrue($course->deleteCourseImageDirectory($course->image_dir . $course->id));
        $is_false_one = is_file($course->image_dir . $course->id . '/' . $course->course_img);
        $this->assertFalse($is_false_one, 'course image failed to delete');
        $is_false_two = is_dir($course->image_dir . $course->id);
        $this->assertFalse($is_false_two, 'course directory failed to delete');

        return $course;

    }

    /**
     * @depends testHasAttribute
     */
    public function testUpdateCourse(Course $course): Course
    {

        $course->course_name = "1111_AAAA_update_test_topic_name";

        $this->assertThat(
            $course->update(),
            $this->isTrue()
        );

        return $course;
    }

     /**
     * @depends testUpdateCourse
     */
    public function testDeleteCourse(Course $course): void
    {
        $this->assertTrue(
            $course->delete()
        );
    }

    public static function tearDownAfterClass(): void
    {
        self::$db = null;
    }

}
