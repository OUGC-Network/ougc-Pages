<?php

/***************************************************************************
 *
 *    OUGC Pages plugin (/inc/plugins/ougc_pages.php)
 *    Author: Omar Gonzalez
 *    Copyright: © 2014 Omar Gonzalez
 *
 *    Website: https://ougc.network
 *
 *    Create additional HTML or PHP pages directly from the Administrator Control Panel.
 *
 ***************************************************************************
 ****************************************************************************
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ****************************************************************************/

use function ougc\Pages\Admin\pluginActivate;
use function ougc\Pages\Admin\pluginDeactivate;
use function ougc\Pages\Admin\pluginInfo;
use function ougc\Pages\Admin\pluginIsInstalled;
use function ougc\Pages\Admin\pluginUninstall;
use function ougc\Pages\Core\addHooks;
use function ougc\Pages\Core\cacheUpdate;

use const ougc\Pages\ROOT;

defined('IN_MYBB') or die('Direct initialization of this file is not allowed.');

// Plugin Settings
define('ougc\Pages\Core\SETTINGS', [
    'enableEval' => true,
    'parserOptions' => [
        'allow_html' => true,
        'allow_mycode' => true,
        'allow_smilies' => true,
        'allow_imgcode' => true,
        'allow_videocode' => true,
        #"nofollow_on" => true,
        'filter_badwords' => true,
        'nl2br' => true,
    ]
]);

define('ougc\Pages\DEBUG', false);

define('ougc\Pages\ROOT', constant('MYBB_ROOT') . 'inc/plugins/ougc/Pages');

require_once ROOT . '/core.php';

defined('PLUGINLIBRARY') or define('PLUGINLIBRARY', MYBB_ROOT . 'inc/plugins/pluginlibrary.php');

// Add our hooks
if (defined('IN_ADMINCP')) {
    require_once ROOT . '/admin.php';
    require_once ROOT . '/adminHooks.php';

    addHooks('ougc\Pages\adminHooks');
} else {
    require_once ROOT . '/forumHooks.php';

    addHooks('ougc\Pages\ForumHooks');
}

function ougc_pages_info(): array
{
    return pluginInfo();
}

function ougc_pages_activate()
{
    pluginActivate();
}

function ougc_pages_deactivate()
{
    pluginDeactivate();
}

function ougc_pages_install()
{
    pluginUninstall();
}

function ougc_pages_is_installed(): bool
{
    return pluginIsInstalled();
}

function ougc_pages_uninstall()
{
    pluginUninstall();
}

// Tools -> Cache update helper
function update_ougc_pages()
{
    cacheUpdate();
}

if (!function_exists('ougc_getpreview')) {
    /**
     * Shorts a message to look like a preview.
     * Based off Zinga Burga's "Thread Tooltip Preview" plugin threadtooltip_getpreview() function.
     *
     * @param string Message to short.
     * @param int Maximum characters to show.
     * @param bool Strip MyCode Quotes from message.
     * @param bool Strip MyCode from message.
     * @return string Shortened message
     **/
    function ougc_getpreview($message, $maxlen = 100, $stripquotes = true, $stripmycode = true)
    {
        // Attempt to remove quotes, skip if going to strip MyCode
        if ($stripquotes && !$stripmycode) {
            $message = preg_replace([
                '#\[quote=([\"\']|&quot;|)(.*?)(?:\\1)(.*?)(?:[\"\']|&quot;)?\](.*?)\[/quote\](\r\n?|\n?)#esi',
                '#\[quote\](.*?)\[\/quote\](\r\n?|\n?)#si',
                '#\[quote\]#si',
                '#\[\/quote\]#si'
            ], '', $message);
        }

        // Attempt to remove any MyCode
        if ($stripmycode) {
            global $parser;
            if (!is_object($parser)) {
                require_once MYBB_ROOT . 'inc/class_parser.php';
                $parser = new postParser();
            }

            $message = $parser->parse_message($message, [
                'allow_html' => 0,
                'allow_mycode' => 1,
                'allow_smilies' => 0,
                'allow_imgcode' => 1,
                'filter_badwords' => 1,
                'nl2br' => 0
            ]);

            // before stripping tags, try converting some into spaces
            $message = preg_replace([
                '~\<(?:img|hr).*?/\>~si',
                '~\<li\>(.*?)\</li\>~si'
            ], [' ', "\n* $1"], $message);

            $message = unhtmlentities(strip_tags($message));
        }

        // convert \xA0 to spaces (reverse &nbsp;)
        $message = trim(
            preg_replace(['~ {2,}~', "~\n{2,}~"],
                [' ', "\n"],
                strtr($message, ["\xA0" => ' ', "\r" => '', "\t" => ' ']))
        );

        // newline fix for browsers which don't support them
        $message = preg_replace("~ ?\n ?~", " \n", $message);

        // Shorten the message if too long
        if (my_strlen($message) > $maxlen) {
            $message = my_substr($message, 0, $maxlen - 1) . '...';
        }

        return htmlspecialchars_uni($message);
    }
}