<?php

/***************************************************************************
 *
 *    OUGC Pages plugin (/inc/plugins/ougc/Pages/admin/module.php)
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

use function ougc\Pages\Admin\categoryFormBuildFields;
use function ougc\Pages\Admin\categoryFormCheckFields;
use function ougc\Pages\Admin\categoryFormParseFields;
use function ougc\Pages\Admin\categoryFormSetFields;
use function ougc\Pages\Admin\pageFormBuildFields;
use function ougc\Pages\Admin\pageFormCheckFields;
use function ougc\Pages\Admin\pageFormParseFields;
use function ougc\Pages\Admin\pageFormSetFields;
use function ougc\Pages\Admin\pluginInfo;
use function ougc\Pages\Core\cacheUpdate;
use function ougc\Pages\Core\categoryBuildLink;
use function ougc\Pages\Core\categoryBuildSelect;
use function ougc\Pages\Core\categoryDelete;
use function ougc\Pages\Core\categoryGet;
use function ougc\Pages\Core\categoryInsert;
use function ougc\Pages\Core\categoryQuery;
use function ougc\Pages\Core\categoryUpdate;
use function ougc\Pages\Core\getQueryLimit;
use function ougc\Pages\Core\getQueryStart;
use function ougc\Pages\Core\importGetUrl;
use function ougc\Pages\Core\loadLanguage;
use function ougc\Pages\Core\logAction;
use function ougc\Pages\Core\multipageBuild;
use function ougc\Pages\Core\pageBuildLink;
use function ougc\Pages\Core\pageDelete;
use function ougc\Pages\Core\pageGet;
use function ougc\Pages\Core\pageInsert;
use function ougc\Pages\Core\pageQuery;
use function ougc\Pages\Core\pageUpdate;
use function ougc\Pages\Core\parseUrl;
use function ougc\Pages\Core\redirect;
use function ougc\Pages\Core\templateGet;
use function ougc\Pages\Core\urlBuild;
use function ougc\Pages\Core\urlSet;

use const ougc\Pages\Admin\FIELDS_DATA_PAGES;
use const ougc\Pages\Core\EXECUTION_HOOK_GLOBAL_END;
use const ougc\Pages\Core\EXECUTION_HOOK_GLOBAL_INTERMEDIATE;
use const ougc\Pages\Core\EXECUTION_HOOK_GLOBAL_START;
use const ougc\Pages\Core\EXECUTION_HOOK_INIT;

defined('IN_MYBB') or die('Direct initialization of this file is not allowed.');

// Set url to use
urlSet('index.php?module=config-ougc_pages');

loadLanguage();

global $mybb, $lang, $templates, $db;
global $page, $PL;

$moduleTabs = [
    'categoryView' => [
        'title' => $lang->ougc_pages_tab_category,
        'link' => urlBuild(['action' => 'categories']),
        'description' => $lang->ougc_pages_tab_category_desc
    ]
];

if ($mybb->get_input('manage') != 'pages') {
    $moduleTabs['categoryNew'] = [
        'title' => $lang->ougc_pages_tab_category_add,
        'link' => urlBuild(['action' => 'add']),
        'description' => $lang->ougc_pages_tab_category_add_desc
    ];

    if ($mybb->get_input('action') == 'edit') {
        $moduleTabs['categoryEdit'] = [
            'title' => $lang->ougc_pages_tab_category_edit,
            'link' => urlBuild(['action' => 'edit', 'cid' => $mybb->get_input('cid', MyBB::INPUT_INT)]
            ),
            'description' => $lang->ougc_pages_tab_category_edit_desc,
        ];
    }
}

$page->add_breadcrumb_item($lang->ougc_pages_manage, urlBuild());

if ($mybb->get_input('manage') == 'pages') {
    if (!($categoryData = categoryGet($mybb->get_input('cid', MyBB::INPUT_INT)))) {
        redirect($lang->ougc_pages_error_category_invalid, true);
    }

    // Set url to use
    urlSet(
        urlBuild(['manage' => 'pages', 'cid' => $categoryData['cid']])
    );

    $page->add_breadcrumb_item(strip_tags($categoryData['name']), urlBuild());

    $moduleTabs['pageView'] = [
        'title' => $lang->ougc_pages_manage,
        'link' => urlBuild(),
        'description' => $lang->ougc_pages_manage_desc
    ];

    $moduleTabs['pageAdd'] = [
        'title' => $lang->ougc_pages_tab_page_add,
        'link' => urlBuild(['action' => 'add']),
        'description' => $lang->ougc_pages_tab_page_add_desc
    ];

    if ($mybb->get_input('action') == 'edit') {
        $moduleTabs['pageEdit'] = [
            'title' => $lang->ougc_pages_tab_page_edit,
            'link' => urlBuild(['action' => 'edit', 'pid' => $mybb->get_input('pid', MyBB::INPUT_INT)]
            ),
            'description' => $lang->ougc_pages_tab_page_edit_desc,
        ];
    }

    $moduleTabs['pageImport'] = [
        'title' => $lang->ougc_pages_tab_page_import,
        'link' => urlBuild(['action' => 'import']),
        'description' => $lang->ougc_pages_tab_page_import_desc
    ];

    if ($mybb->get_input('action') == 'add' || $mybb->get_input('action') == 'edit') {
        if (!($newPage = $mybb->get_input('action') === 'add')) {
            if (!($pageData = pageGet($mybb->get_input('pid', MyBB::INPUT_INT)))) {
                redirect($lang->ougc_pages_error_page_invalid, true);
            }

            $page->add_breadcrumb_item(strip_tags($pageData['name']));

            pageFormSetFields($pageData);
        } else {
            $page->add_breadcrumb_item($lang->ougc_pages_tab_page_add);

            pageFormSetFields();
        }

        if (!empty($admin_options['codepress'])) {
            $page->extra_header .= eval(templateGet('adminCodeMirror', false));
        }

        $page->output_header($lang->ougc_pages_manage);

        $navTabsString = 'pageAdd';

        if (!$newPage) {
            $navTabsString = 'pageEdit';
        }

        $page->output_nav_tabs($moduleTabs, $navTabsString);

        if ($mybb->request_method == 'post') {
            $errors = [];

            $mybb->input['url'] = parseUrl($mybb->get_input('url'));

            $whereConditions = ["url='{$db->escape_string($mybb->get_input('url'))}'"];

            if (!$newPage) {
                $whereConditions[] = "pid!='{$pageData['pid']}'";
            }

            if (pageQuery(['pid'], $whereConditions, ['limit' => 1])) {
                $errors[] = $lang->ougc_pages_error_page_duplicated_url;
            }

            // if this is a non-php page then check its contents
            if (!$mybb->get_input('php', MyBB::INPUT_INT) && check_template($mybb->get_input('template'))) {
                $errors[] = $lang->ougc_pages_error_page_invalid_template;
            }

            pageFormCheckFields($errors);

            if (empty($errors)) {
                $formData = [];

                if ($newPage) {
                    $higherOrder = pageQuery(
                        ['MAX(disporder) as maxOrder'],
                        ["cid='{$categoryData['cid']}'"]
                    );

                    $formData['disporder'] = 1;

                    if (!empty($higherOrder[0]['maxOrder'])) {
                        $formData['disporder'] += $higherOrder[0]['maxOrder'];
                    }
                }

                pageFormParseFields($formData);

                if ($newPage) {
                    $pageID = pageInsert($formData);

                    $redirectText = 'ougc_pages_success_page_add';
                } else {
                    $pageID = pageUpdate($formData, $pageData['pid']);

                    $redirectText = 'ougc_pages_success_page_updated';
                }

                cacheUpdate();

                logAction($pageID);

                if ($mybb->get_input('continue')) {
                    urlSet(
                        urlBuild(['action' => 'edit', 'pid' => $pageID])
                    );
                }

                redirect($lang->{$redirectText});
            } else {
                $page->output_inline_error($errors);
            }
        }

        $navTabsString = $formContainerTitle = 'pageAdd';

        $formUrlFields = ['action' => 'add'];

        if (!$newPage) {
            $navTabsString = $formContainerTitle = 'pageEdit';

            $formUrlFields = ['action' => 'edit', 'pid' => $pageData['pid']];
        }

        $formObject = new Form(
            urlBuild($formUrlFields),
            'post'
        );

        $formContainer = new FormContainer(
            $moduleTabs[$formContainerTitle]['description']
        );

        $basicSelectItems = [
            'cid' => categoryBuildSelect(),
            'init' => [
                EXECUTION_HOOK_INIT => $lang->ougc_pages_form_page_init_init,
                EXECUTION_HOOK_GLOBAL_START => $lang->ougc_pages_form_page_init_start,
                EXECUTION_HOOK_GLOBAL_INTERMEDIATE => $lang->ougc_pages_form_page_init_intermediate,
                EXECUTION_HOOK_GLOBAL_END => $lang->ougc_pages_form_page_init_end,
            ]
        ];

        pageFormBuildFields($formContainer, $formObject, $basicSelectItems);

        $formContainer->end();

        $formObject->output_submit_wrapper([
            $formObject->generate_submit_button($lang->ougc_pages_button_continue, ['name' => 'continue']),
            $formObject->generate_submit_button($lang->ougc_pages_button_submit),
            $formObject->generate_submit_button($lang->reset)
        ]);

        $formObject->end();

        if (!empty($admin_options['codepress'])) {
            echo eval(templateGet('adminCodeMirrorFooter', false));
        }

        $page->output_footer();
    } elseif ($mybb->get_input('action') == 'delete') {
        if (!($pageData = pageGet($mybb->get_input('pid', MyBB::INPUT_INT)))) {
            redirect($lang->ougc_pages_error_page_invalid, true);
        }

        if ($mybb->request_method == 'post') {
            if (!verify_post_check($mybb->get_input('my_post_key'), true)) {
                redirect($lang->invalid_post_verify_key2, true);
            }

            if ($mybb->get_input('no')) {
                redirect();
            }

            pageDelete($pageData['pid']);

            logAction($pageData['pid']);

            cacheUpdate();

            redirect($lang->ougc_pages_success_page_deleted);
        }

        $page->output_confirm_action(
            urlBuild(['action' => 'delete', 'pid' => $pageData['pid']])
        );
    } elseif ($mybb->get_input('action') == 'update') {
        if (!($pageData = pageGet($mybb->get_input('pid', MyBB::INPUT_INT)))) {
            redirect($lang->ougc_pages_error_page_invalid, true);
        }

        $statusUpdate = 1;

        $updateText = 'ougc_pages_success_page_enabled';

        if ($pageData['visible']) {
            $statusUpdate = 0;

            $updateText = 'ougc_pages_success_page_disabled';
        }

        pageUpdate(
            ['visible' => $statusUpdate],
            $pageData['pid']
        );

        logAction($pageData['pid']);

        cacheUpdate();

        redirect($lang->{$updateText});
    } elseif ($mybb->get_input('action') == 'export') {
        if (!($page = pageGet($mybb->get_input('pid', MyBB::INPUT_INT)))) {
            redirect($lang->ougc_pages_error_invalidpage, true);
        }

        $info = ougc_pages_info();

        $file = $PL->xml_export([
            'name' => $page['name'],
            'description' => $page['description'],
            'url' => $page['url'],
            'allowedGroups' => $page['allowedGroups'],
            'php' => $page['php'],
            'wol' => $page['wol'],
            'visible' => $page['visible'],
            'wrapper' => $page['wrapper'],
            'init' => $page['init'],
            'template' => $page['template'],
            'versioncode' => $info['versioncode']
        ], 'OUGC_Pages_' . $page['name'] . '_' . $info['versioncode'] . '.xml');
    } elseif ($mybb->get_input('action') == 'import') {
        $page->add_breadcrumb_item(htmlspecialchars_uni($categoryData['name']));

        $page->output_header($lang->ougc_pages_manage);

        $page->output_nav_tabs($moduleTabs, 'pageImport');

        if ($mybb->request_method == 'post') {
            $errors = [];

            if ($mybb->get_input('file_url')) {
                if (!($contents = fetch_remote_file($mybb->get_input('file_url')))) {
                    $errors[] = $lang->error_local_file;
                }
            } elseif ($_FILES['local_file'] && $_FILES['local_file']['error'] != 4) {
                // Find out if there was an error with the uploaded file
                if ($_FILES['local_file']['error'] != 0) {
                    $errors[] = $lang->error_uploadfailed . $lang->error_uploadfailed_detail;

                    switch ($_FILES['local_file']['error']) {
                        case 1: // UPLOAD_ERR_INI_SIZE
                            $errors[] = $lang->error_uploadfailed_php1;
                            break;
                        case 2: // UPLOAD_ERR_FORM_SIZE
                            $errors[] = $lang->error_uploadfailed_php2;
                            break;
                        case 3: // UPLOAD_ERR_PARTIAL
                            $errors[] = $lang->error_uploadfailed_php3;
                            break;
                        case 6: // UPLOAD_ERR_NO_TMP_DIR
                            $errors[] = $lang->error_uploadfailed_php6;
                            break;
                        case 7: // UPLOAD_ERR_CANT_WRITE
                            $errors[] = $lang->error_uploadfailed_php7;
                            break;
                        default:
                            $errors[] = $lang->sprintf(
                                $lang->error_uploadfailed_phpx,
                                $_FILES['local_file']['error']
                            );
                            break;
                    }
                }

                if (empty($errors)) {
                    // Was the temporary file found?
                    if (!is_uploaded_file($_FILES['local_file']['tmp_name'])) {
                        $errors[] = $lang->error_uploadfailed_lost;
                    }

                    // Get the contents
                    if (!($contents = trim(file_get_contents($_FILES['local_file']['tmp_name'])))) {
                        $errors[] = $lang->error_uploadfailed_nocontents;
                    }

                    // Delete the temporary file if possible
                    unlink($_FILES['local_file']['tmp_name']);
                }
            } else {
                // UPLOAD_ERR_NO_FILE
                $errors[] = $lang->error_uploadfailed_php4;
            }

            if (empty($errors)) {
                $xmlImport = [];

                $isValidVersion = true;

                if ($xmlImportPL = @$PL->xml_import($contents)) {
                    if (!$mybb->get_input('ignore_version', MyBB::INPUT_INT)) {
                        $isValidVersion = (float)$xmlImportPL['versioncode'] == pluginInfo()['versioncode'];
                    }

                    if ($isValidVersion) {
                        $xmlImport = $xmlImportPL;

                        unset($xmlImportPL);
                    }
                } else {
                    // try to get this as a "Page Manager" page
                    require_once MYBB_ROOT . 'inc/class_xml.php';

                    $xmlParser = new XMLParser($contents);

                    $treeContents = $xmlParser->get_tree();

                    if (!empty($treeContents['pagemanager'])) {
                        $pageManagerFile = $treeContents['pagemanager'];

                        unset($treeContents);

                        if (!$mybb->get_input('ignore_version', MyBB::INPUT_INT)) {
                            $isValidVersion = (float)$pageManagerFile['attributes']['version'] == '1.5.2';
                        }

                        if ($isValidVersion) {
                            $pageManagerContents = $pageManagerFile['page'];
                        }

                        unset($pageManagerFile);

                        if (!empty($pageManagerContents)) {
                            if (!($template = base64_decode(
                                $pageManagerContents['template']['value']
                            ))) {
                                $template = $pageManagerContents['template']['value'];
                            }

                            $xmlImport = [
                                'name' => (string)$pageManagerContents['name']['value'],
                                'description' => '',
                                'url' => (string)$pageManagerContents['url']['value'],
                                'allowedGroups' => -1,
                                'php' => empty($pageManagerContents['framework']['value']) ? 0 : 1,
                                'wol' => empty($pageManagerContents['online']['value']) ? 0 : 1,
                                'visible' => empty($pageManagerContents['enabled']['value']) ? 0 : 1,
                                'wrapper' => 0, // It runs without a wrapper, similar here
                                'init' => 4, // It runs at misc_stats, so we run at global_end
                                'template' => (string)trim($template)
                            ];
                        }
                    }
                }

                if (empty($xmlImport)) {
                    if ($isValidVersion) {
                        $errors[] = $lang->ougc_pages_error_import_invalid;
                    } else {
                        $errors[] = $lang->ougc_pages_error_import_invalid_version;
                    }
                }

                if (empty($errors)) {
                    $xmlImport['cid'] = $categoryData['cid'];

                    $higherOrder = pageQuery(
                        ['MAX(disporder) as maxOrder'],
                        ["cid='{$categoryData['cid']}'"]
                    );

                    $xmlImport['disporder'] = 1;

                    if (!empty($higherOrder[0]['maxOrder'])) {
                        $xmlImport['disporder'] += $higherOrder[0]['maxOrder'];
                    }

                    $xmlImport['url'] = importGetUrl(
                        $xmlImport['name'],
                        $xmlImport['url']
                    );

                    $pageData = [];

                    foreach (FIELDS_DATA_PAGES as $fieldKey => $fieldData) {
                        if (!isset($xmlImport[$fieldKey])) {
                            continue;
                        }

                        $pageData[$fieldKey] = $xmlImport[$fieldKey];
                    }

                    unset($fieldKey, $fieldData, $xmlImport);

                    $pageID = pageInsert($pageData);

                    cacheUpdate();

                    logAction($pageID);

                    redirect($lang->ougc_pages_success_imported);
                }
            }

            if (!empty($errors)) {
                $page->output_inline_error($errors);
            }
        }

        $form = new Form(urlBuild(['action' => 'import']), 'post', '', true);

        $formContainer = new FormContainer($moduleTabs['pageImport']['description']);

        $formContainer->output_row(
            $lang->ougc_pages_form_import,
            $lang->ougc_pages_form_import_desc,
            $form->generate_file_upload_box('local_file', $mybb->get_input('local_file'))
        );

        $formContainer->output_row(
            $lang->ougc_pages_form_import_url,
            $lang->ougc_pages_form_import_url_desc,
            $form->generate_text_box('file_url', $mybb->get_input('file_url'))
        );

        $formContainer->output_row(
            $lang->ougc_pages_form_import_ignore_version,
            $lang->ougc_pages_form_import_ignore_version_desc,
            $form->generate_yes_no_radio(
                'ignore_version',
                $mybb->get_input('ignore_version', MyBB::INPUT_INT)
            )
        );

        $formContainer->end();

        $form->output_submit_wrapper([
            $form->generate_submit_button($lang->ougc_pages_button_import),
            $form->generate_reset_button($lang->reset)
        ]);

        $form->end();

        $page->output_footer();
    } else {
        $page->add_breadcrumb_item(htmlspecialchars_uni($categoryData['name']));

        $page->output_header($lang->ougc_pages_manage);

        $page->output_nav_tabs($moduleTabs, 'pageView');

        $tableObject = new Table();

        $tableObject->construct_header($lang->ougc_pages_category_name);
        $tableObject->construct_header(
            $lang->ougc_pages_category_order,
            ['width' => '15%', 'class' => 'align_center']
        );
        $tableObject->construct_header(
            $lang->ougc_pages_category_status,
            ['width' => '15%', 'class' => 'align_center']
        );
        $tableObject->construct_header($lang->options, ['width' => '15%', 'class' => 'align_center']);

        if (!($totalPages = pageQuery(['COUNT(pid) AS pages'],
            ["cid='{$categoryData['cid']}'"])[0]['pages'])) {
            $tableObject->construct_cell(
                $lang->ougc_pages_category_empty,
                ['colspan' => 4, 'class' => 'align_center']
            );

            $tableObject->construct_row();

            $tableObject->output($moduleTabs['pageView']['title']);
        } else {
            $multiPage = multipageBuild($totalPages, urlBuild());

            if ($mybb->request_method == 'post') {
                if (!verify_post_check($mybb->get_input('my_post_key'), true)) {
                    redirect($lang->invalid_post_verify_key2, true);
                }

                foreach ($mybb->get_input('disporder', MyBB::INPUT_ARRAY) as $pageID => $newOrder) {
                    pageUpdate(
                        ['disporder' => $newOrder],
                        $pageID
                    );
                }

                cacheUpdate();

                redirect($lang->ougc_pages_success_page_updated_order);
            }

            echo $multiPage;

            $pagesList = pageQuery(['*'], ["cid='{$categoryData['cid']}'"], [
                'limit_start' => getQueryStart(),
                'limit' => getQueryLimit(),
                'order_by' => 'disporder'
            ]);

            $formObject = new Form(urlBuild(), 'post');

            foreach ($pagesList as $pageData) {
                $manageUrl = urlBuild(
                    ['manage' => 'pages', 'action' => 'edit', 'pid' => $pageData['pid']]
                );

                $pageName = htmlspecialchars_uni($pageData['name']);

                $pageLink = '---';

                if ($pageData['visible'] && $pageData['allowedGroups'] !== '') {
                    $pageLink = pageBuildLink(
                        $lang->ougc_pages_page_view,
                        $pageData['pid']
                    );
                }

                $tableObject->construct_cell(
                    eval(templateGet('adminPageName', false))
                );

                $tableObject->construct_cell(
                    $formObject->generate_text_box(
                        "disporder[{$pageData['pid']}]",
                        $pageData['disporder'],
                        ['style' => 'text-align: center; width: 30px;']
                    ),
                    ['class' => 'align_center']
                );

                $updateStatusUrl = urlBuild([
                    'action' => 'update',
                    'pid' => $pageData['pid'],
                    'my_post_key' => $mybb->post_code
                ]);

                $updateStatusBullet = 'off';

                $updateStatusText = $lang->ougc_pages_category_disabled;

                if ($pageData['visible']) {
                    $updateStatusBullet = 'on';

                    $updateStatusText = $lang->ougc_pages_category_enabled;
                }

                $tableObject->construct_cell(
                    eval(templateGet('adminCategoryStatus', false)),
                    ['class' => 'align_center']
                );

                $popup = new PopupMenu('page_' . $pageData['pid'], $lang->options);

                $popup->add_item(
                    $lang->edit,
                    urlBuild(['action' => 'edit', 'pid' => $pageData['pid']])
                );

                $popup->add_item(
                    $lang->delete,
                    urlBuild(['action' => 'delete', 'pid' => $pageData['pid']])
                );

                $popup->add_item(
                    $lang->ougc_pages_page_export,
                    urlBuild([
                        'action' => 'export',
                        'pid' => $pageData['pid'],
                        'my_post_key' => $mybb->post_code
                    ])
                );

                $tableObject->construct_cell($popup->fetch(), ['class' => 'align_center']);

                $tableObject->construct_row();
            }

            $tableObject->output($moduleTabs['categoryView']['title']);

            $formObject->output_submit_wrapper([
                $formObject->generate_submit_button($lang->ougc_pages_button_update_order),
                $formObject->generate_reset_button($lang->reset)
            ]);

            $formObject->end();
        }

        $page->output_footer();
    }
} else {
    if ($mybb->get_input('action') == 'add' || $mybb->get_input('action') == 'edit') {
        if (!($newCategory = $mybb->get_input('action') === 'add')) {
            if (!($categoryData = categoryGet($mybb->get_input('cid', MyBB::INPUT_INT)))) {
                redirect($lang->ougc_pages_error_category_invalid, true);
            }

            categoryFormSetFields($categoryData);

            $page->add_breadcrumb_item(strip_tags($categoryData['name']));
        } else {
            categoryFormSetFields();
        }

        $page->output_header($lang->ougc_pages_manage);

        $navTabsString = $formContainerTitle = 'categoryNew';

        $formUrlFields = ['action' => 'add'];

        if (!$newCategory) {
            $navTabsString = $formContainerTitle = 'categoryEdit';

            $formUrlFields = ['action' => 'edit', 'cid' => $categoryData['cid']];
        }

        $page->output_nav_tabs($moduleTabs, $navTabsString);

        if ($mybb->request_method == 'post') {
            $errors = [];

            $mybb->input['url'] = parseUrl($mybb->get_input('url'));

            $whereConditions = ["url='{$db->escape_string($mybb->get_input('url'))}'"];

            if (!$newCategory) {
                $whereConditions[] = "cid!='{$categoryData['cid']}'";
            }

            if (categoryQuery(['cid'], $whereConditions, ['limit' => 1])) {
                $errors[] = $lang->ougc_pages_error_category_duplicated_url;
            }

            categoryFormCheckFields($errors);

            if (empty($errors)) {
                $formData = [];

                if ($newCategory) {
                    $higherOrder = categoryQuery(
                        ['MAX(disporder) as maxOrder']
                    );

                    $formData['disporder'] = 1;

                    if (!empty($higherOrder[0]['maxOrder'])) {
                        $formData['disporder'] += $higherOrder[0]['maxOrder'];
                    }
                }

                categoryFormParseFields($formData);

                if ($newCategory) {
                    $categoryID = categoryInsert($formData);
                } else {
                    $categoryID = categoryUpdate($formData, $categoryData['cid']);
                }

                cacheUpdate();

                logAction($categoryID);

                if ($newCategory) {
                    redirect($lang->ougc_pages_success_category_add);
                }

                redirect($lang->ougc_pages_success_category_updated);
            } else {
                $page->output_inline_error($errors);
            }
        }

        $formObject = new Form(
            urlBuild($formUrlFields),
            'post'
        );

        $formContainer = new FormContainer(
            $moduleTabs[$formContainerTitle]['description']
        );

        /*$basicSelectItems = [
          'buildMenu' => [
               0 => $lang->ougc_pages_form_category_buildMenu_none,
               1 => $lang->ougc_pages_form_category_buildMenu_header,
               2 => $lang->ougc_pages_form_category_buildMenu_footer
           ]
       ];*/

        categoryFormBuildFields($formContainer, $formObject/*, $basicSelectItems*/);

        $formContainer->end();

        $formObject->output_submit_wrapper([
            $formObject->generate_submit_button($lang->ougc_pages_button_submit),
            $formObject->generate_reset_button($lang->reset)
        ]);

        $formObject->end();

        $page->output_footer();
    } elseif ($mybb->get_input('action') == 'delete') {
        if (!($categoryData = categoryGet($mybb->get_input('cid', MyBB::INPUT_INT)))) {
            redirect($lang->ougc_pages_error_category_invalid, true);
        }

        if ($mybb->request_method == 'post') {
            if (!verify_post_check($mybb->get_input('my_post_key'), true)) {
                redirect($lang->invalid_post_verify_key2, true);
            }

            if ($mybb->get_input('no')) {
                redirect();
            }

            categoryDelete($categoryData['cid']);

            logAction($categoryData['cid']);

            cacheUpdate();

            redirect($lang->ougc_pages_success_category_deleted);
        }

        $page->output_confirm_action(
            urlBuild(['action' => 'delete', 'cid' => $categoryData['cid']])
        );
    } elseif ($mybb->get_input('action') == 'update') {
        if (!($categoryData = categoryGet($mybb->get_input('cid', MyBB::INPUT_INT)))) {
            redirect($lang->ougc_pages_error_category_invalid, true);
        }

        if (!verify_post_check($mybb->get_input('my_post_key'), true)) {
            redirect($lang->invalid_post_verify_key2, true);
        }

        $statusUpdate = 1;

        $updateText = 'ougc_pages_success_category_enabled';

        if ($categoryData['visible']) {
            $statusUpdate = 0;

            $updateText = 'ougc_pages_success_category_disabled';
        }

        categoryUpdate(
            ['visible' => $statusUpdate],
            $categoryData['cid']
        );

        logAction($categoryData['cid']);

        cacheUpdate();

        redirect($lang->{$updateText});
    } else {
        $page->add_breadcrumb_item($moduleTabs['categoryView']['title'], urlBuild());
        $page->output_header($lang->ougc_pages_manage);
        $page->output_nav_tabs($moduleTabs, 'categoryView');

        $tableObject = new Table();

        $tableObject->construct_header($lang->ougc_pages_category_name);
        $tableObject->construct_header(
            $lang->ougc_pages_category_order,
            ['width' => '15%', 'class' => 'align_center']
        );
        $tableObject->construct_header(
            $lang->ougc_pages_category_status,
            ['width' => '15%', 'class' => 'align_center']
        );
        $tableObject->construct_header($lang->options, ['width' => '15%', 'class' => 'align_center']);

        if (!($totalCategories = categoryQuery(['COUNT(cid) AS categories']
        )[0]['categories'])) {
            $tableObject->construct_cell(
                $lang->ougc_pages_category_empty,
                ['colspan' => 4, 'class' => 'align_center']
            );

            $tableObject->construct_row();

            $tableObject->output($moduleTabs['categoryView']['title']);
        } else {
            $multiPage = multipageBuild($totalCategories, urlBuild());

            if ($mybb->request_method == 'post') {
                if (!verify_post_check($mybb->get_input('my_post_key'), true)) {
                    redirect($lang->invalid_post_verify_key2, true);
                }

                foreach ($mybb->get_input('disporder', MyBB::INPUT_ARRAY) as $categoryID => $newOrder) {
                    categoryUpdate(
                        ['disporder' => $newOrder],
                        $categoryID
                    );
                }

                cacheUpdate();

                redirect($lang->ougc_pages_success_category_updated_order);
            }

            echo $multiPage;

            $categoriesList = categoryQuery(['*'], ['1=1'], [
                'limit_start' => getQueryStart(),
                'limit' => getQueryLimit(),
                'order_by' => 'disporder'
            ]);

            $formObject = new Form(urlBuild(), 'post');

            foreach ($categoriesList as $categoryData) {
                $manageUrl = urlBuild(['manage' => 'pages', 'cid' => $categoryData['cid']]);

                $categoryName = htmlspecialchars_uni($categoryData['name']);

                $categoryLink = '---';

                if ($categoryData['visible'] && $categoryData['allowedGroups'] !== '') {
                    $categoryLink = categoryBuildLink(
                        $lang->ougc_pages_category_view,
                        $categoryData['cid']
                    );
                }

                $tableObject->construct_cell(
                    eval(templateGet('adminCategoryName', false))
                );

                $tableObject->construct_cell(
                    $formObject->generate_text_box(
                        "disporder[{$categoryData['cid']}]",
                        $categoryData['disporder'],
                        ['style' => 'text-align: center; width: 30px;']
                    ),
                    ['class' => 'align_center']
                );

                $updateStatusUrl = urlBuild([
                    'action' => 'update',
                    'cid' => $categoryData['cid'],
                    'my_post_key' => $mybb->post_code
                ]);

                $updateStatusBullet = 'off';

                $updateStatusText = $lang->ougc_pages_category_disabled;

                if ($categoryData['visible']) {
                    $updateStatusBullet = 'on';

                    $updateStatusText = $lang->ougc_pages_category_enabled;
                }

                $tableObject->construct_cell(
                    eval(templateGet('adminCategoryStatus', false)),
                    ['class' => 'align_center']
                );

                $popup = new PopupMenu('category_' . $categoryData['cid'], $lang->options);

                $popup->add_item(
                    $lang->edit,
                    urlBuild(['action' => 'edit', 'cid' => $categoryData['cid']])
                );

                $popup->add_item(
                    $lang->delete,
                    urlBuild(['action' => 'delete', 'cid' => $categoryData['cid']])
                );

                $tableObject->construct_cell($popup->fetch(), ['class' => 'align_center']);

                $tableObject->construct_row();
            }

            $tableObject->output($moduleTabs['categoryView']['title']);

            $formObject->output_submit_wrapper([
                $formObject->generate_submit_button($lang->ougc_pages_button_update_order),
                $formObject->generate_reset_button($lang->reset)
            ]);

            $formObject->end();
        }

        $page->output_footer();
    }
}