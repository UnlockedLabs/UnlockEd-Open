<?php
declare(strict_types=1);
/**
 * @file  GUID_Test.php
 * @brief Unit test file for GUID object
 * 
 * This unit test to ensure the uuid function of the GUID
 * class returns a properly formed UUID.
 */




use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__).'/../objects/GUID.php';



final class RegExpTest extends TestCase
{
    public function testFailure(): void
    {
        $guid = new unlockedlabs\unlocked\GUID();
        $this->assertRegExp('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $guid->uuid());
    }
}

?>