<?php
declare(strict_types=1);
/**
 * @file  user_Test.php
 * @brief Unit test file for User object
 * 
 * This unit test creates a new user and then checks certain parameters
 * to enusre the user is well formed.
 */
use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__).'/../objects/users.php';
require_once dirname(__FILE__).'/../config/core.php';
require_once dirname(__FILE__).'/../config/database.php';
use unlockedlabs\unlocked\user;

// if ($_SESSION['current_site_settings']['gamification_enabled'] == 'true') {
//     include_once dirname(__FILE__).'/../objects/gamification.php';
// }

final class UserTest extends TestCase
{

    public function testCreate(): user
    {
        echo "Testing create user with username unit_test_user\n";
        $database = new unlockedlabs\unlocked\Database();
        $db = $database->getConnection();
        $user = new User($db);
        $user->username = 'unit_test_user';
        $user->password = '$2y$10\$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La';
        $user->repeat_password = '$2y$10\$GdW0ms3ezuL4.Y5aA1eFKub59XEZNsgtiR0DOh76AghxmZzJ0L5La';
        $user->email = 'unit@test.com';
        $user->access_id = 1;
        $user->admin_id =1;
        $userCreated = $user->create();
        $this->assertThat(
            $userCreated,
            $this->isTrue()
        );
        return $user;
    }

    /**
     * @depends testCreate
     */
    public function testInstanceOf(user $user): user
    {
        echo "Testing that previous create returned and instance of the User class\n";
        $this->assertThat(
            $user,
            $this->isInstanceOf('\unlockedlabs\unlocked\User')
        );
        return $user;
    }

    /**
     * @depends testInstanceOf
     */
    public function testHasAttribute(user $user): user
    {
        echo "Testing that user object has expected attributes\n";

        $this->assertThat(
            $user,
            $this->objectHasAttribute('id')
        );
        $this->assertThat(
            $user,
            $this->objectHasAttribute('created')
        );
        $this->assertThat(
            $user,
            $this->objectHasAttribute('username')
        );
        $this->assertThat(
            $user,
            $this->objectHasAttribute('password')
        );    
        $this->assertThat(
            $user,
            $this->objectHasAttribute('password_hashed')
        );
        $this->assertThat(
            $user,
            $this->objectHasAttribute('repeat_password')
        );
        $this->assertThat(
            $user,
            $this->objectHasAttribute('email')
        );
        $this->assertThat(
            $user,
            $this->objectHasAttribute('oid')
        );
        $this->assertThat(
            $user,
            $this->objectHasAttribute('access_id')
        );
        $this->assertThat(
            $user,
            $this->objectHasAttribute('admin_id')
        );  
        return $user;
    }

    /**
     * @depends testHasAttribute
     */
    public function testUpdateUser(user $user): user
    {

        echo "Testing update user to username update_test_user\n";
        $user->username = "update_test_user";
        $this->assertTrue(
            $user->update()
        );
        return $user;
    }

     /**
     * @depends testUpdateUser
     */
    public function testDeleteUser(user $user): void
    {
        echo "Testing delete user\n";
        $this->assertTrue(
            $user->delete()
        );
    }
}
?>