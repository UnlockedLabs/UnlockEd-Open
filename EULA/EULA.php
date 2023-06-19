<?php

/**
 * EULA
 *
 * Handle the EULA
 *
 * PHP version 7.2.5
 *
 * @category  EULA
 * @package   UnlockED
 * @author    UnlockedLabs <developers@unlockedlabs.org>
 * @copyright 2021 UnlockedLabs.org <http://unlockedlabs.org/>
 * @license   https://www.gnu.org/licenses/gpl.html GPLv3
 * @link      http://unlockedlabs.org
 */

require_once 'Michelf/MarkdownInterface.inc.php';
require_once 'Michelf/Markdown.inc.php';
$filepath = realpath('.') . '/EULA/License.md';
// Read file and pass content through the Markdown parser
$text = file_get_contents($filepath);

$html = \Michelf\Markdown::defaultTransform($text);

echo $html;
