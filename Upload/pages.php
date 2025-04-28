<?php

/***************************************************************************
 *
 *    OUGC Pages plugin (/pages.php)
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

use function ougc\Pages\Core\initRun;
use function ougc\Pages\Core\initShow;
use function ougc\Pages\Core\loadLanguage;
use function ougc\Pages\Core\runHooks;

const IN_MYBB = true;
const THIS_SCRIPT = 'pages.php';

$workingDirectory = dirname(__FILE__);

if (!$workingDirectory) {
    $workingDirectory = '.';
}

$shutdown_queries = $shutdown_functions = [];

require_once $workingDirectory . '/inc/init.php';

if (!function_exists('ougc\\Pages\\Core\\initRun')) {
    error_no_permission();
}

if (isset($templatelist)) {
    $templatelist .= ',';
} else {
    $templatelist = '';
}

$templatelist .= 'ougcpages_category_list_item, ougcpages_category_list, ougcpages_wrapper, usercp_nav_messenger, usercp_nav_messenger_tracking, usercp_nav_messenger_compose, usercp_nav_messenger_folder, usercp_nav_changename, usercp_nav_editsignature, usercp_nav_profile, usercp_nav_attachments, usercp_nav_misc, ougcpages_wrapper_ucp_nav_item, ougcpages_wrapper_ucp_nav, usercp_nav_home, usercp_nav, ougcpages_wrapper_ucp, ougcpages, ougcpages_category_list_empty, ougcpages_navigation_previous, ougcpages_navigation_next, ougcpages_wrapper_edited, ougcpages_wrapper_navigation_section, ougcpages_wrapper_navigation_section_item';

initRun();

require_once $workingDirectory . '/global.php';

loadLanguage();

runHooks('Start');

global $lang;

if (defined('OUGC_PAGES_STATUS_CATEGORY_INVALID')) {
    error($lang->ougc_pages_error_category_invalid);
} elseif (defined('OUGC_PAGES_STATUS_PAGE_INVALID')) {
    error($lang->ougc_pages_error_page_invalid);
} elseif (defined('OUGC_PAGES_STATUS_CATEGORY_NO_PERMISSION') || defined('OUGC_PAGES_STATUS_PAGE_NO_PERMISSION')) {
    error_no_permission();
} elseif (defined('OUGC_PAGES_STATUS_IS_CATEGORY') || defined('OUGC_PAGES_STATUS_IS_PAGE')) {
    initShow();
}

runHooks('End');

error_no_permission();