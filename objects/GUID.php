<?php

/**
 * GUID Object
 *
 * PHP version 7.2.5
 *
 * @category  Objects
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

namespace unlockedlabs\unlocked;

/**
 * GUID Class
 *
 * Provide UUIDs for PK fields in the database. Create guid (Version 4 UUID, random).
 *
 * Version 4 (random)
 * A version 4 UUID is randomly generated.
 * As in other UUIDs, four bits are used to indicate version 4,
 * and 2 or 3 bits to indicate the variant (10 or 110 for variants 1 and 2, respectively).
 * Thus, for variant 1 (that is, most UUIDs) a random version 4 UUID
 * will have 6 predetermined variant and version bits,
 * leaving 122 bits for the randomly-generated part,
 * for a total of 2122, or 5.3x1036 (5.3 undecillion) possible version 4 variant 1 UUIDs.
 * There are half as many possible version 4 variant 2 UUIDs (legacy GUIDs)
 * because there is one less random bit available, 3 bits being consumed for the variant.
 *     Microsoft GUIDs are sometimes represented with surrounding braces:
 *     {123e4567-e89b-12d3-a456-426655440000}
 *
 * @category Objects
 * @package  UnlockED
 * @author   UnlockedLabs <developers@unlockedlabs.org>
 * @license  https://www.gnu.org/licenses/gpl.html GPLv3
 * @link     http://unlockedlabs.org
 */
class GUID
{
    /**
     * Create guid (MS Windows?)
     *
     * @return guid
     */
    private function guidv4()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            // $uuid = chr(123)// "{"
                $uuid = substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12);
                    // .chr(125);// "}"
            return $uuid; // should we trim curly braces? --> trim($uuid, '{}');
        }
    }

    /**
     * Create guid (???)
     *
     * @return guid
     */
    private function uuidv4()
    {
        return implode(
            '-',
            [
            bin2hex(random_bytes(4)),
            bin2hex(random_bytes(2)),
            bin2hex(chr((ord(random_bytes(1)) & 0x0F) | 0x40)) . bin2hex(random_bytes(1)),
            bin2hex(chr((ord(random_bytes(1)) & 0x3F) | 0x80)) . bin2hex(random_bytes(1)),
            bin2hex(random_bytes(6))
            ]
        );
    }

    /**
     * Create guid (???)
     *
     * @return guid
     */
    private function phpuniquid()
    {
        return uniqid();
    }

    /**
     * Create guid (???)
     *
     * @return guid
     */
    private function uuidgen()
    {
        return `uuidgen -r`; // -r = version 4
    }

    /**
     * Create an RFC compliant UUID v.4.
     *
     * Try for different OS???
     *
     * @return UUID
     */
    public function uuid()
    {

        try {
            return trim(GUID::uuidv4());
        } catch (Exception $th) {
            //throw $th;
        }

        try {
            //code...
            return trim(GUID::uuidgen());
        } catch (Exception $th) {
            //throw $th;
        }
        try {
            return trim(GUID::guidv4());
        } catch (Exception $th) {
            //throw $th;
        }
        try {
            return trim(GUID::phpuniquid());
        } catch (Exception $th) {
            //throw $th;
        }
        return null;
    }
}
