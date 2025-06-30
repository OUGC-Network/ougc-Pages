<?php

/***************************************************************************
 *
 *    OUGC Pages plugin (/inc/languages/english/admin/ougc_pages.lang.php)
 *    Author: Omar Gonzalez
 *    Copyright: © 2014 Omar Gonzalez
 *
 *    Website: https://ougc.network
 *
 *    Create additional pages directly from the ACP.
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

// Plugin API
$l['setting_group_ougc_pages'] = 'OUGC Pages';
$l['setting_group_ougc_pages_desc'] = 'Create additional HTML or PHP pages directly from the Administrator Control Panel.';

// Settings
$l['setting_ougc_pages_seo'] = 'Use SEO friendly URLs';
$l['setting_ougc_pages_seo_desc'] = 'Whether if to enable SEO friendly URLs for pages.';
$l['setting_ougc_pages_seo_scheme'] = 'Page URL Scheme';
$l['setting_ougc_pages_seo_scheme_desc'] = 'Enter the Page URL scheme. Leave empty to disable SEO URLs for Pages.';
$l['setting_ougc_pages_seo_scheme_categories'] = 'Category URL Scheme';
$l['setting_ougc_pages_seo_scheme_categories_desc'] = 'Enter the Category URL scheme. Leave empty to disable SEO URLs for Categories.';
$l['setting_ougc_pages_perpage'] = 'Items Per Page';
$l['setting_ougc_pages_perpage_desc'] = 'Maximum number of items to show per page in the ACP list.';
$l['setting_ougc_pages_usercp_priority'] = 'UserCP Nav Priority';
$l['setting_ougc_pages_usercp_priority_desc'] = 'The priority given to UserCP navigation categories.';

// ACP
$l['ougc_pages_manage'] = 'Manage Pages';
$l['ougc_pages_manage_desc'] = 'This section allows you to manage your pages in this category.';

$l['ougc_pages_tab_category'] = 'Categories';
$l['ougc_pages_tab_category_desc'] = 'This section allows you to manage your page categories.';
$l['ougc_pages_tab_category_add'] = 'New Category';
$l['ougc_pages_tab_category_add_desc'] = 'Here you can add a new page category.';
$l['ougc_pages_tab_category_edit'] = 'Edit Category';
$l['ougc_pages_tab_category_edit_desc'] = 'Here you can update a existing category.';
$l['ougc_pages_tab_page_add'] = 'New Page';
$l['ougc_pages_tab_page_add_desc'] = 'Here you can add a new page.';
$l['ougc_pages_tab_page_edit'] = 'Edit Page';
$l['ougc_pages_tab_page_edit_desc'] = 'Here you can update a existing page.';
$l['ougc_pages_tab_page_import'] = 'Import Page';
$l['ougc_pages_tab_page_import_desc'] = 'Here you can import a new page.';

$l['ougc_pages_form_preconfigured'] = 'Preconfigured Page';
$l['ougc_pages_form_preconfigured_desc'] = 'Select a page from the default package.';
$l['ougc_pages_form_import'] = 'Local File';
$l['ougc_pages_form_import_desc'] = 'Select the XML file to import from your computer.';
$l['ougc_pages_form_import_url'] = 'URL File';
$l['ougc_pages_form_import_url_desc'] = 'Insert the URL of the XML file to import.';
$l['ougc_pages_form_import_ignore_version'] = 'Ignore Version Compatibility';
$l['ougc_pages_form_import_ignore_version_desc'] = 'Should this page be imported regardless of the version of OUGC Pages or Page Manager it was created for?';

$l['error_preconfigured_file'] = 'There was an error with the selected preconfigured file.';
$l['error_url_file'] = 'There was an error trying to download the url file.';

$l['ougc_pages_form_category_name'] = 'Category Name';
$l['ougc_pages_form_category_name_desc'] = 'Display name for this category.';
$l['ougc_pages_form_category_description'] = 'Category Description';
$l['ougc_pages_form_category_description_desc'] = 'Insert the description for this category.';
$l['ougc_pages_form_category_url'] = 'Unique URL';
$l['ougc_pages_form_category_url_desc'] = 'Insert the unique URL identifier for this category.';
$l['ougc_pages_form_category_allowedGroups'] = 'Viewable for Groups';
$l['ougc_pages_form_category_allowedGroups_desc'] = 'Select the groups that are allowed to browse this category.';
$l['ougc_pages_form_category_breadcrumb'] = 'Display in Breadcrumb';
$l['ougc_pages_form_category_breadcrumb_desc'] = 'Enable to display this category in the navigation breadcrumb.';
$l['ougc_pages_form_category_displayNavigation'] = 'Show Navigation';
$l['ougc_pages_form_category_displayNavigation_desc'] = 'Enable to show a previous/next pagination when browsing pages in this category.';
$l['ougc_pages_form_category_buildMenu'] = 'Build Menu';
$l['ougc_pages_form_category_buildMenu_desc'] = 'Enable to build a dropdown menu for this category in the header.';
//$l['ougc_pages_form_category_buildMenu_none'] = 'None';
//$l['ougc_pages_form_category_buildMenu_header'] = 'Header';
//$l['ougc_pages_form_category_buildMenu_footer'] = 'Footer';
$l['ougc_pages_form_category_wrapucp'] = 'Wrap UserCP Menu';
$l['ougc_pages_form_category_wrapucp_desc'] = 'If enabled, a section will be added to the UserCP for browsing this category and the category will be wrapped as if it was a UserCP section. Beware of errors if you allow guest access.';
$l['ougc_pages_form_category_wrapNavigation'] = 'Wrap Navigation Menu';
$l['ougc_pages_form_category_wrapNavigation_desc'] = 'If enabled, a navigation section will be added when browsing this category and the category will be wrapped as if it was a navigation menu.';

$l['ougc_pages_form_page_cid'] = 'Category';
$l['ougc_pages_form_page_cid_desc'] = 'Select the category this page belongs to.';
$l['ougc_pages_form_page_name'] = 'Page Name';
$l['ougc_pages_form_page_name_desc'] = 'Display name for this page.';
$l['ougc_pages_form_page_description'] = 'Page Description';
$l['ougc_pages_form_page_description_desc'] = 'Insert the description for this page.';
$l['ougc_pages_form_page_url'] = 'Unique URL';
$l['ougc_pages_form_page_url_desc'] = 'Insert the unique URL identifier for this page.';
$l['ougc_pages_form_page_allowedGroups'] = 'Viewable for Groups';
$l['ougc_pages_form_page_allowedGroups_desc'] = 'Select the groups that are allowed to see this page.';
$l['ougc_pages_form_page_menuItem'] = 'Add to Menu';
$l['ougc_pages_form_page_menuItem_desc'] = 'If "Build Menu" is enabled for this category, add link to this page in it.';
$l['ougc_pages_form_page_wrapper'] = 'Use Template Wrapper';
$l['ougc_pages_form_page_wrapper_desc'] = 'If enabled, the contents of non-PHP pages will be wrapped within the <code>ougcpages_wrapper</code> template.';
$l['ougc_pages_form_page_wol'] = 'Show In Who Is Online (WOL) List';
$l['ougc_pages_form_page_wol_desc'] = 'If disabled, activity within this page will be displayed as "Unknown location" pointing to the home page.';
$l['ougc_pages_form_page_php'] = 'Eval PHP Code';
$l['ougc_pages_form_page_php_desc'] = 'If enabled, this page wilL be parsed as plain PHP code. Disable to use HTML content instead.';
$l['ougc_pages_form_page_parseMyCode'] = 'Parse MyCode';
$l['ougc_pages_form_page_parseMyCode_desc'] = 'If enabled, this page wilL be parsed using the post parser.';
$l['ougc_pages_form_page_classicTemplate'] = 'Use Theme Template';
$l['ougc_pages_form_page_classicTemplate_desc'] = 'If enabled, the "Page Content" below will be ignored and a theme template will be used instead. The name for the template should follow the format <code>ougcpages_pagePID</code>, for example: <code>ougcpages_page18</code>';
//$l['ougc_pages_form_page_classicTemplate_desc_plus'] = '<br /><strong>Template name:</strong> <code>{1}</code>';
$l['ougc_pages_form_page_template'] = 'Page Content';
$l['ougc_pages_form_page_template_desc'] = 'Insert the page HTML or PHP content below.';
$l['ougc_pages_form_page_init'] = 'PHP Initialization Point';
$l['ougc_pages_form_page_init_desc'] = 'Select the script section where this page should be loaded in when "Eval PHP Code" is enabled.<br />
<strong>Initialization:</strong> Not even all plugins are checked at this point. Very low resource consumption. Around 4-6 queries are ran by this point.<br />
<strong>Global Start:</strong> Mainly only session and language have been loaded. Around 6-8 queries are ran by this point.<br />
<strong>Global Intermediate:</strong> Theme and templates have been loaded without header, welcome block, or footer being available yet. Around 8-10 queries are ran by this point.<br />
<span style="color: blue;"><strong>Global End:</strong> Default; if unsure select this. Has the most compatibility for all forum features. Around 9-13 queries are ran by this point.</span>';
$l['ougc_pages_form_page_init_init'] = 'Initialization';
$l['ougc_pages_form_page_init_start'] = 'Global Start';
$l['ougc_pages_form_page_init_intermediate'] = 'Global Intermediate';
$l['ougc_pages_form_page_init_end'] = 'Global End';

$l['ougc_pages_category_name'] = 'Name';
$l['ougc_pages_category_order'] = 'Display Order';
$l['ougc_pages_category_status'] = 'Status';
$l['ougc_pages_category_enabled'] = 'Enabled';
$l['ougc_pages_category_disabled'] = 'Disabled';
$l['ougc_pages_category_empty'] = 'There are currently no items to display.';
$l['ougc_pages_page_export'] = 'Export';

$l['ougc_pages_button_update_order'] = 'Update Order';
$l['ougc_pages_button_continue'] = 'Save and Continue';
$l['ougc_pages_button_submit'] = 'Save';
$l['ougc_pages_button_import'] = 'Import File';

$l['ougc_pages_category_view'] = 'View Category';
$l['ougc_pages_page_view'] = 'View Page';

// ACP Module: Messages
$l['ougc_pages_error_category_invalid'] = 'The selected category is invalid.';
$l['ougc_pages_error_category_invalid_name'] = 'The category name should be between 1 and {1} characters long.';
$l['ougc_pages_error_category_invalid_description'] = 'The category description should be between 1 and {1} characters long.';
$l['ougc_pages_error_category_invalid_url'] = 'The category url should be between 1 and {1} characters long.';
$l['ougc_pages_error_category_duplicated_url'] = 'The category url is already in use by other category.';

$l['ougc_pages_error_page_invalid'] = 'The selected page is invalid.';
$l['ougc_pages_error_page_invalid_name'] = 'The page name should be between 1 and {1} characters long.';
$l['ougc_pages_error_page_invalid_description'] = 'The page description should be between 1 and {1} characters long.';
$l['ougc_pages_error_page_invalid_url'] = 'The page url should be between 1 and {1} characters long.';
$l['ougc_pages_error_page_duplicated_url'] = 'The page url is already in use by other page.';
$l['ougc_pages_error_page_invalid_template'] = 'The template contents seem to have invalid code for non PHP pages.';

$l['ougc_pages_error_import_invalid'] = 'The file contents seem to be invalid.';
$l['ougc_pages_error_import_invalid_version'] = 'The file contents seem to be from an incompatible version.';

$l['ougc_pages_success_category_add'] = 'The category was created successfully.';
$l['ougc_pages_success_category_updated'] = 'The category was updated successfully.';
$l['ougc_pages_success_category_updated_order'] = 'The display order of the categories was updated successfully.';
$l['ougc_pages_success_category_enabled'] = 'The category was enabled successfully.';
$l['ougc_pages_success_category_disabled'] = 'The category was disabled successfully.';
$l['ougc_pages_success_category_deleted'] = 'The category was deleted successfully.';

$l['ougc_pages_success_page_add'] = 'The page was created successfully.';
$l['ougc_pages_success_page_updated'] = 'The page was updated successfully.';
$l['ougc_pages_success_page_updated_order'] = 'The display order of the pages was updated successfully.';
$l['ougc_pages_success_page_enabled'] = 'The page was enabled successfully.';
$l['ougc_pages_success_page_disabled'] = 'The page was disabled successfully.';
$l['ougc_pages_success_page_deleted'] = 'The page was deleted successfully.';
$l['ougc_pages_success_imported'] = 'The page was imported successfully.';

// Admin Permissions
$l['ougc_pages_config_permissions'] = 'Can manage pages?';

// PluginLibrary
$l['ougc_pages_pl_required'] = 'This plugin requires <a href="{1}">PluginLibrary</a> version {2} or later to be uploaded to your forum.';