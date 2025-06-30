<?php

/***************************************************************************
 *
 *    OUGC Pages plugin (/inc/plugins/ougc/Pages/admin.php)
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

namespace ougc\Pages\Admin;

use MyBB;
use DirectoryIterator;
use Form;
use FormContainer;

use function ougc\Pages\Core\cacheUpdate;
use function ougc\Pages\Core\loadLanguage;
use function ougc\Pages\Core\loadPluginLibrary;
use function ougc\Pages\Core\sanitizeIntegers;
use function ougc\Pages\Core\templateGet;
use function ougc\Pages\Core\getSetting;

use const TIME_NOW;
use const MYBB_ADMIN_DIR;
use const ougc\Pages\Core\EXECUTION_HOOK_GLOBAL_END;
use const ougc\Pages\ROOT;

const FIELDS_DATA_CATEGORIES = [
    'cid' => [
        'type' => 'INT',
        'unsigned' => true,
        'auto_increment' => true,
        'primary_key' => true
    ],
    'name' => [
        'type' => 'VARCHAR',
        'formType' => 'textBox',
        'size' => 100,
        'cache' => true,
        'required' => true
    ],
    'description' => [
        'type' => 'VARCHAR',
        'formType' => 'textBox',
        'size' => 255,
        'cache' => true,
        'required' => true
    ],
    'url' => [
        'type' => 'VARCHAR',
        'formType' => 'textBox',
        'size' => 100,
        'unique' => true,
        'cache' => true,
        'required' => true
    ],
    'allowedGroups' => [
        'type' => 'VARCHAR',
        'formType' => 'groupSelect',
        'size' => 255,
        'cache' => true,
        'default' => -1
    ],
    'disporder' => [
        'type' => 'SMALLINT',
        'unsigned' => true,
        'default' => 0
    ],
    'visible' => [
        'type' => 'TINYINT',
        'unsigned' => true,
        'default' => 1
    ],
    'breadcrumb' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 1,
        'cache' => true
    ],
    'displayNavigation' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 1,
        'cache' => true
    ],
    'buildMenu' => [
        'type' => 'TINYINT',
        //'formType' => 'basicSelect',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 1,
        'cache' => true
    ],
    'wrapucp' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 0,
        'cache' => true
    ],
    'wrapNavigation' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 0,
        'cache' => true
    ]
];

const FIELDS_DATA_PAGES = [
    'pid' => [
        'type' => 'INT',
        'unsigned' => true,
        'auto_increment' => true,
        'primary_key' => true
    ],
    'cid' => [
        'type' => 'INT',
        'formType' => 'basicSelect',
        'unsigned' => true,
        'cache' => true
    ],
    'name' => [
        'type' => 'VARCHAR',
        'formType' => 'textBox',
        'size' => 100,
        'cache' => true,
        'required' => true,
        'export' => true,
    ],
    'description' => [
        'type' => 'VARCHAR',
        'formType' => 'textBox',
        'size' => 255,
        'cache' => true,
        'required' => true,
        'export' => true,
    ],
    'url' => [
        'type' => 'VARCHAR',
        'formType' => 'textBox',
        'size' => 100,
        'unique' => true,
        'cache' => true,
        'required' => true,
        'export' => true,
    ],
    'allowedGroups' => [
        'type' => 'VARCHAR',
        'formType' => 'groupSelect',
        'size' => 255,
        'cache' => true,
        'default' => -1,
        'export' => true,
    ],
    'disporder' => [
        'type' => 'SMALLINT',
        'unsigned' => true,
        'default' => 0
    ],
    'visible' => [
        'type' => 'TINYINT',
        'unsigned' => true,
        'default' => 1
    ],
    'menuItem' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 1,
        'cache' => true,
        'export' => true,
    ],
    'wrapper' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 1,
        'cache' => true,
        'export' => true,
    ],
    'wol' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 1,
        'cache' => true,
        'export' => true,
    ],
    'php' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 0,
        'cache' => true,
        'export' => true,
    ],
    'parseMyCode' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 0,
        'cache' => true,
        'export' => true,
    ],
    'classicTemplate' => [
        'type' => 'TINYINT',
        'formType' => 'yesNo',
        'unsigned' => true,
        'default' => 0,
        'cache' => true,
        'export' => true,
    ],
    'init' => [
        'type' => 'TINYINT',
        'formType' => 'basicSelect',
        'unsigned' => true,
        'default' => EXECUTION_HOOK_GLOBAL_END,
        'cache' => true,
        'export' => true,
    ],
    'template' => [
        'type' => 'MEDIUMTEXT',
        'formType' => 'textArea',
        'null' => true,
        'export' => true,
    ],
    'dateline' => [
        'type' => 'INT',
        'unsigned' => true,
        'default' => 0,
        'cache' => true
    ]
];

function pluginInfo(): array
{
    global $lang;

    loadLanguage();

    return [
        'name' => 'OUGC Pages',
        'description' => $lang->setting_group_ougc_pages_desc,
        'website' => 'https://ougc.network',
        'author' => 'Omar G.',
        'authorsite' => 'https://ougc.network',
        'version' => '1.8.34',
        'versioncode' => 1834,
        'compatibility' => '183*',
        'codename' => 'ougc_pages',
        'pl' => [
            'version' => 13,
            'url' => 'http://community.mybb.com/mods.php?action=view&pid=573'
        ]
    ];
}

function pluginActivate()
{
    global $PL, $lang, $cache, $db;

    loadPluginLibrary();

    // Add settings
    $settingsContents = file_get_contents(ROOT . '/settings.json');

    $settingsData = json_decode($settingsContents, true);

    foreach ($settingsData as $settingKey => &$settingData) {
        if (empty($lang->{"setting_ougc_pages_{$settingKey}"})) {
            continue;
        }

        $settingData['title'] = isset($lang->{"setting_ougc_pages_{$settingKey}"}) ? $lang->{"setting_ougc_pages_{$settingKey}"} : '';
        $settingData['description'] = isset($lang->{"setting_ougc_pages_{$settingKey}_desc"}) ? $lang->{"setting_ougc_pages_{$settingKey}_desc"} : '';
    }

    $PL->settings(
        'ougc_pages',
        $lang->setting_group_ougc_pages,
        $lang->setting_group_ougc_pages_desc,
        $settingsData
    );

    // Add templates
    $templatesDirIterator = new DirectoryIterator(ROOT . '/templates');

    $templates = [];

    foreach ($templatesDirIterator as $template) {
        if (!$template->isFile()) {
            continue;
        }

        $pathName = $template->getPathname();

        $pathInfo = pathinfo($pathName);

        if ($pathInfo['extension'] === 'html') {
            $templates[$pathInfo['filename']] = file_get_contents($pathName);
        }
    }

    if ($templates) {
        $PL->templates('ougcpages', 'OUGC Pages', $templates);
    }

    // Insert/update version into cache
    $plugins = (array)$cache->read('ougc_plugins');

    if (!$plugins) {
        $plugins = [];
    }

    if (!isset($plugins['pages'])) {
        $plugins['pages'] = pluginInfo()['versioncode'];
    }

    verifyStylesheet();

    /*~*~* RUN UPDATES START *~*~*/

    if ($plugins['pages'] <= 1819) {
        if ($db->table_exists('ougc_pages')) {
            $db->update_query('ougc_pages', ['visible' => 0], "groups=''");
            $db->update_query('ougc_pages', ['groups' => 0], "groups='-1'");
        }

        if ($db->table_exists('ougc_pages_categories')) {
            $db->update_query('ougc_pages_categories', ['visible' => ''], "groups=''");
            $db->update_query('ougc_pages_categories', ['groups' => ''], "groups='-1'");
        }
    }

    if ($plugins['pages'] <= 1833) {
        if ($db->table_exists('ougc_pages') && $db->field_exists('groups', 'ougc_pages')) {
            $db->rename_column(
                'ougc_pages',
                'groups',
                'allowedGroups',
                dbTables()['ougc_pages']['allowedGroups']
            );

            $db->update_query('ougc_pages', ['allowedGroups' => -1], "allowedGroups=''");
        }

        if ($db->table_exists('ougc_pages_categories') && $db->field_exists('groups', 'ougc_pages_categories')) {
            $db->rename_column(
                'ougc_pages_categories',
                'groups',
                'allowedGroups',
                dbTables()['ougc_pages_categories']['allowedGroups']
            );

            $db->update_query('ougc_pages_categories', ['allowedGroups' => -1], "allowedGroups=''");
        }
    }

    /*~*~* RUN UPDATES END *~*~*/

    dbVerifyTables();

    $plugins['pages'] = pluginInfo()['versioncode'];

    $cache->update('ougc_plugins', $plugins);

    // Update administrator permissions
    change_admin_permission('config', 'ougc_pages');

    cacheUpdate();
}

function pluginDeactivate()
{
    loadPluginLibrary();

    // Update administrator permissions
    change_admin_permission('config', 'ougc_pages', 0);
}

function pluginIsInstalled(): bool
{
    global $db;

    static $pluginIsInstalled = null;

    if ($pluginIsInstalled === null) {
        foreach (dbTables() as $table => $fields) {
            $pluginIsInstalled = (bool)$db->table_exists($table);

            break;
        }
    }

    return $pluginIsInstalled;
}

function pluginUninstall()
{
    global $db, $PL, $cache;

    loadPluginLibrary();

    // Drop DB entries
    foreach (dbTables() as $name => $table) {
        $db->drop_table($name);
    }

    verifyStylesheet(true);

    $PL->cache_delete('ougc_pages');
    $PL->settings_delete('ougc_pages');
    $PL->templates_delete('ougcpages');

    // Delete version from cache
    $plugins = (array)$cache->read('ougc_plugins');

    if (isset($plugins['pages'])) {
        unset($plugins['pages']);
    }

    if (!empty($plugins)) {
        $cache->update('ougc_plugins', $plugins);
    } else {
        $PL->cache_delete('ougc_plugins');
    }

    // Remove administrator permissions
    change_admin_permission('config', 'ougc_pages', -1);
}

function dbVerifyTables()
{
    global $db;

    $collation = $db->build_create_table_collation();

    foreach (dbTables() as $table => $fields) {
        if ($db->table_exists($table)) {
            foreach ($fields as $field => $definition) {
                if ($field == 'primary_key' || $field == 'unique_key') {
                    continue;
                }

                if ($db->field_exists($field, $table)) {
                    $db->modify_column($table, "`{$field}`", $definition);
                } else {
                    $db->add_column($table, $field, $definition);
                }
            }
        } else {
            $query = "CREATE TABLE IF NOT EXISTS `{$db->table_prefix}{$table}` (";

            foreach ($fields as $field => $definition) {
                if ($field == 'primary_key') {
                    $query .= "PRIMARY KEY (`{$definition}`)";
                } elseif ($field != 'unique_key') {
                    $query .= "`{$field}` {$definition},";
                }
            }

            $query .= ") ENGINE=MyISAM{$collation};";

            $db->write_query($query);
        }
    }

    dbVerifyIndexes();
}

function dbVerifyIndexes()
{
    global $db;

    foreach (dbTables() as $table => $fields) {
        if (!$db->table_exists($table)) {
            continue;
        }

        if (isset($fields['unique_key'])) {
            foreach ($fields['unique_key'] as $k => $v) {
                if ($db->index_exists($table, $k)) {
                    continue;
                }

                $db->write_query("ALTER TABLE {$db->table_prefix}{$table} ADD UNIQUE KEY {$k} ({$v})");
            }
        }
    }
}

function dbTables(): array
{
    $tablesData = [];

    foreach (
        [
            'ougc_pages_categories' => FIELDS_DATA_CATEGORIES,
            'ougc_pages' => FIELDS_DATA_PAGES,
        ] as $tableName => $fieldsData
    ) {
        foreach ($fieldsData as $fieldName => $fieldData) {
            $fieldDefinition = '';

            if (!isset($fieldData['type'])) {
                continue;
            }

            $fieldDefinition .= $fieldData['type'];

            if (isset($fieldData['size'])) {
                $fieldDefinition .= "({$fieldData['size']})";
            }

            if (isset($fieldData['unsigned'])) {
                $fieldDefinition .= ' UNSIGNED';
            }

            if (!isset($fieldData['null'])) {
                $fieldDefinition .= ' NOT';
            }

            $fieldDefinition .= ' NULL';

            if (isset($fieldData['auto_increment'])) {
                $fieldDefinition .= ' AUTO_INCREMENT';
            }

            if (isset($fieldData['default'])) {
                $fieldDefinition .= " DEFAULT '{$fieldData['default']}'";
            }

            $tablesData[$tableName][$fieldName] = $fieldDefinition;
        }

        foreach ($fieldsData as $fieldName => $fieldData) {
            if (isset($fieldData['primary_key'])) {
                $tablesData[$tableName]['primary_key'] = $fieldName;
            }
        }
    }

    return $tablesData;
}

function verifyStylesheet($removeStylesheet = false)
{
    global $db;

    $dbQuery = $db->simple_select(
        'themestylesheets',
        'sid, attachedto',
        "name='usercp.css' AND tid= '1'"
    );

    $updateResult = false;

    while ($stylesheet = $db->fetch_array($dbQuery)) {
        $sheetID = (int)$stylesheet['sid'];

        if (!$removeStylesheet && my_strpos($stylesheet['attachedto'], '|pages.php') === false) {
            $db->update_query('themestylesheets', [
                'attachedto' => $stylesheet['attachedto'] . '|pages.php',
                'lastmodified' => TIME_NOW
            ], "sid = '{$sheetID}'");
            $updateResult = true;
        }

        if ($removeStylesheet && my_strpos($stylesheet['attachedto'], '|pages.php') !== false) {
            $db->update_query('themestylesheets', [
                'attachedto' => str_replace('|pages.php', '', $stylesheet['attachedto']),
                'lastmodified' => TIME_NOW
            ], "sid = '{$sheetID}'");
            $updateResult = true;
        }
    }

    if ($updateResult) {
        $dbQuery = $db->simple_select('themes', 'tid');

        require_once MYBB_ADMIN_DIR . 'inc/functions_themes.php';

        while ($tid = $db->fetch_field($dbQuery, 'tid')) {
            update_theme_stylesheet_list($tid);
        }
    }
}

function categoryFormCheckFields(
    array &$errors,
    string $errorIdentifier = 'category',
    array $fieldsData = FIELDS_DATA_CATEGORIES
) {
    pageFormCheckFields($errors, $errorIdentifier, $fieldsData);
}

function pageFormCheckFields(
    array &$errors,
    string $errorIdentifier = 'page',
    array $fieldsData = FIELDS_DATA_PAGES
) {
    global $mybb, $lang;

    foreach ($fieldsData as $fieldKey => $fieldData) {
        if (!isset($fieldData['formType'])) {
            continue;
        }

        if ($fieldData['formType'] == 'textBox') {
            $inputLength = my_strlen($mybb->get_input($fieldKey));

            if ($inputLength < 1 || $inputLength > $fieldData['size']) {
                $errors[] = $lang->sprintf(
                    $lang->{"ougc_pages_error_{$errorIdentifier}_invalid_{$fieldKey}"},
                    my_number_format($fieldData['size'])
                );
            }
        } elseif ($fieldData['formType'] == 'groupSelect') {
            if ($mybb->get_input("{$fieldKey}Select") === 'all') {
                $mybb->input[$fieldKey] = -1;
            } elseif ($mybb->get_input("{$fieldKey}Select") === 'custom') {
                $mybb->input[$fieldKey] = implode(
                    ',',
                    sanitizeIntegers(
                        $mybb->get_input($fieldKey, MyBB::INPUT_ARRAY)
                    )
                );
            }
        }

        unset($fieldKey, $fieldData);
    }
}

function categoryFormBuildFields(
    FormContainer &$formContainer,
    Form &$formObject,
    array $basicSelectItems = [],
    string $errorIdentifier = 'category',
    array $fieldsData = FIELDS_DATA_CATEGORIES
) {
    pageFormBuildFields($formContainer, $formObject, $basicSelectItems, $errorIdentifier, $fieldsData);
}

function pageFormBuildFields(
    FormContainer &$formContainer,
    Form &$formObject,
    array $basicSelectItems = [],
    string $errorIdentifier = 'page',
    array $fieldsData = FIELDS_DATA_PAGES
) {
    global $mybb, $lang;

    foreach ($fieldsData as $fieldKey => $fieldData) {
        if (!isset($fieldData['formType']) || $fieldKey === 'php' && getSetting('enableEval') !== true) {
            continue;
        }

        $requiredMark = '';

        if (!empty($fieldData['required'])) {
            $requiredMark = ' <em>*</em>';
        }

        $formContainer->output_row(
            $lang->{"ougc_pages_form_{$errorIdentifier}_{$fieldKey}"} . $requiredMark,
            $lang->{"ougc_pages_form_{$errorIdentifier}_{$fieldKey}_desc"},
            call_user_func_array(
                function (string $fieldKey, string $formType, array $basicSelectItems = []) use (&$formObject): string {
                    global $mybb, $lang, $templates;

                    if ($formType == 'yesNo') {
                        return $formObject->generate_yes_no_radio(
                            $fieldKey,
                            $mybb->get_input($fieldKey, MyBB::INPUT_INT),
                            true
                        );
                    } elseif ($formType == 'groupSelect') {
                        $selectedItems = [];

                        $multiSelectChecked = ['all' => '', 'custom' => '', 'none' => ''];

                        if ($mybb->get_input($fieldKey, MyBB::INPUT_INT) === -1) {
                            $multiSelectChecked['all'] = 'checked="checked"';
                        } elseif (!empty($mybb->get_input($fieldKey))) {
                            $multiSelectChecked['custom'] = 'checked="checked"';

                            $selectedItems = sanitizeIntegers(
                                explode(
                                    ',',
                                    $mybb->get_input($fieldKey)
                                )
                            );
                        } else {
                            $multiSelectChecked['none'] = 'checked="checked"';
                        }

                        print_selection_javascript();

                        $groupSelectField = $formObject->generate_group_select(
                            "{$fieldKey}[]",
                            $selectedItems,
                            ['id' => $fieldKey, 'multiple' => true, 'size' => 5]
                        );

                        return eval(templateGet('adminGroupSelect', false));
                    } elseif ($formType == 'basicSelect') {
                        if ($fieldKey === 'init' && !$mybb->get_input($fieldKey, MyBB::INPUT_INT)) {
                            $mybb->input[$fieldKey] = EXECUTION_HOOK_GLOBAL_END;
                        }

                        return $formObject->generate_select_box(
                            $fieldKey,
                            $basicSelectItems[$fieldKey],
                            $mybb->get_input($fieldKey, MyBB::INPUT_INT),
                            ['id' => $fieldKey]
                        );
                    } elseif ($formType == 'textArea') {
                        return $formObject->generate_text_area(
                            $fieldKey,
                            $mybb->get_input($fieldKey),
                            ['id' => 'template', 'class' => '', 'style' => 'width: 100%; height: 500px;']
                        );
                    } else {
                        return $formObject->generate_text_box(
                            $fieldKey,
                            $mybb->get_input($fieldKey),
                            ['id' => $fieldKey]
                        );
                    }
                },
                [$fieldKey, $fieldData['formType'], $basicSelectItems]
            ),
            '',
            [],
            ['id' => "row_{$fieldKey}"]
        );

        unset($fieldKey, $fieldData);
    }
}

function categoryFormParseFields(array &$formData, array $fieldsData = FIELDS_DATA_CATEGORIES)
{
    pageFormParseFields($formData, $fieldsData);
}

function pageFormParseFields(array &$formData, array $fieldsData = FIELDS_DATA_PAGES)
{
    global $mybb;

    foreach ($fieldsData as $fieldKey => $fieldData) {
        if (!isset($fieldData['formType'])) {
            continue;
        }

        if ($fieldData['formType'] == 'yesNo') {
            $formData[$fieldKey] = $mybb->get_input($fieldKey, MyBB::INPUT_INT);
        } else {
            $formData[$fieldKey] = $mybb->get_input($fieldKey);
        }

        unset($fieldKey, $fieldData);
    }
}

function categoryFormSetFields(array &$objectData = [], array $fieldsData = FIELDS_DATA_CATEGORIES)
{
    pageFormSetFields($objectData, $fieldsData);
}

function pageFormSetFields(array &$objectData = [], array $fieldsData = FIELDS_DATA_PAGES)
{
    global $mybb;

    foreach ($fieldsData as $fieldKey => $fieldData) {
        if (!isset($fieldData['formType'])) {
            continue;
        }

        if (!isset($mybb->input[$fieldKey])) {
            if (isset($objectData[$fieldKey])) {
                $mybb->input[$fieldKey] = $objectData[$fieldKey];
            } else {
                $mybb->input[$fieldKey] = '';
            }
        }

        unset($fieldKey, $fieldData);
    }
}