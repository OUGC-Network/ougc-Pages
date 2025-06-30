<?php

/***************************************************************************
 *
 *    OUGC Pages plugin (/inc/languages/english/admin/ougc_pages.lang.php)
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

// Plugin API
$l['setting_group_ougc_pages'] = 'OUGC Pages';
$l['setting_group_ougc_pages_desc'] = 'Crea páginas adicionales directamente desde tu ACP.';

// Settings
$l['setting_ougc_pages_seo'] = 'Usar Enlaces Amigables SEO';
$l['setting_ougc_pages_seo_desc'] = 'Activa la opción para usar enlaces amigables al SEO.';
$l['setting_ougc_pages_seo_scheme'] = 'Esquema de URL de Página';
$l['setting_ougc_pages_seo_scheme_desc'] = 'Escribe el esquema del enlace de las páginas. Deja vacío para deshabilitar los enlaces SEO para las páginas.';
$l['setting_ougc_pages_seo_scheme_categories'] = 'Esquema de URL de Categorías';
$l['setting_ougc_pages_seo_scheme_categories_desc'] = 'Escribe el esquema del enlace de las categorías. Deja vacío para deshabilitar los enlaces SEO para las categorías.';
$l['setting_ougc_pages_perpage'] = 'Objetos por Página';
$l['setting_ougc_pages_perpage_desc'] = 'Máximo numero de objetos a mostrar por página en el ACP.';
$l['setting_ougc_pages_usercp_priority'] = 'Prioridad en Navegación del UserCP';
$l['setting_ougc_pages_usercp_priority_desc'] = 'La prioridad para los paneles de navegación agregado al panel de usuarios.';

// ACP
$l['ougc_pages_manage'] = 'Administrar Páginas';
$l['ougc_pages_manage_desc'] = 'Esta sección te permite administrar las páginas de esta categoría.';

$l['ougc_pages_tab_category'] = 'Categorías';
$l['ougc_pages_tab_category_desc'] = 'Esta sección te permite administrar tus categorías.';
$l['ougc_pages_tab_category_add'] = 'Nueva Categoría';
$l['ougc_pages_tab_category_add_desc'] = 'Aquí puedes agregar una nueva categoría.';
$l['ougc_pages_tab_category_edit'] = 'Editar Categoría';
$l['ougc_pages_tab_category_edit_desc'] = 'Aquí puedes editar una categoría.';
$l['ougc_pages_tab_page_add'] = 'Nueva Página';
$l['ougc_pages_tab_page_add_desc'] = 'Aquí puedes agregar una nueva página.';
$l['ougc_pages_tab_page_edit'] = 'Editar Página';
$l['ougc_pages_tab_page_edit_desc'] = 'Aquí puedes editar una página.';
$l['ougc_pages_tab_page_import'] = 'Importar Página';
$l['ougc_pages_tab_page_import_desc'] = 'Aquí puedes importar una página.';

$l['ougc_pages_form_preconfigured'] = 'Pagina Preconfigurada';
$l['ougc_pages_form_preconfigured_desc'] = 'Selecciona una pagina del paquete predeterminado.';
$l['ougc_pages_form_import'] = 'Archivo Local';
$l['ougc_pages_form_import_desc'] = 'Selecciona el archivo XML a importar desde tu dispositivo.';
$l['ougc_pages_form_import_url'] = 'URL del Archivo';
$l['ougc_pages_form_import_url_desc'] = 'Inserta la URL del archivo XML a importar.';
$l['ougc_pages_form_import_ignore_version'] = 'Ignorar la Compatibilidad de Version';
$l['ougc_pages_form_import_ignore_version_desc'] = 'Selecciona esta opción para importar una página sin importar la version para la cual fue generada.';

$l['error_preconfigured_file'] = 'Hubo un error con la pagina preconfigurada.';
$l['error_url_file'] = 'Hubo un error al descargar el archivo de URL.';

$l['ougc_pages_form_category_name'] = 'Nombre de la Categoría';
$l['ougc_pages_form_category_name_desc'] = 'Nombre a mostrar para esta categoría.';
$l['ougc_pages_form_category_description'] = 'Descripción de la Categoría';
$l['ougc_pages_form_category_description_desc'] = 'Inserta la descripción para esta categoría.';
$l['ougc_pages_form_category_url'] = 'URL Única';
$l['ougc_pages_form_category_url_desc'] = 'Inserta la URL única que identifica a esta categoría.';
$l['ougc_pages_form_category_allowedGroups'] = 'Visible para Grupos';
$l['ougc_pages_form_category_allowedGroups_desc'] = 'Selecciona los grupos que pueden navegar esta categoría.';
$l['ougc_pages_form_category_breadcrumb'] = 'Mostrar en Navegación';
$l['ougc_pages_form_category_breadcrumb_desc'] = 'Activa para mostrar esta categoría en la navegación de páginas.';
$l['ougc_pages_form_category_displayNavigation'] = 'Mostrar Navegación';
$l['ougc_pages_form_category_displayNavigation_desc'] = 'Activa para mostrar una navegación entre páginas cuando se navega esta categoría.';
$l['ougc_pages_form_category_buildMenu'] = 'Construir Menu';
$l['ougc_pages_form_category_buildMenu_desc'] = 'Activa para construir un menu despegable para esta categoría en el encabezado de la página.';
//$l['ougc_pages_form_category_buildMenu_none'] = 'Ninguno';
//$l['ougc_pages_form_category_buildMenu_header'] = 'Encabezado';
//$l['ougc_pages_form_category_buildMenu_footer'] = 'Pie de página';
$l['ougc_pages_form_category_wrapucp'] = 'Envolver en Menu del UserCP';
$l['ougc_pages_form_category_wrapucp_desc'] = 'Se se activa, se agregara una sección al menu del UserCP para navegar las páginas de esta categoría. Considera los conflictos si los invitados pueden navegar esta categoría.';

$l['ougc_pages_form_page_cid'] = 'Categoría';
$l['ougc_pages_form_page_cid_desc'] = 'Selección la categoría a la cual esta página pertenece.';
$l['ougc_pages_form_page_name'] = 'Nombre de la Página';
$l['ougc_pages_form_page_name_desc'] = 'Nombre a mostrar para esta página.';
$l['ougc_pages_form_page_description'] = 'Descripción de la Página';
$l['ougc_pages_form_page_description_desc'] = 'Inserta la descripción para esta página.';
$l['ougc_pages_form_page_url'] = 'URL Única';
$l['ougc_pages_form_page_url_desc'] = 'Inserta la URL única que identifica a esta página.';
$l['ougc_pages_form_page_allowedGroups'] = 'Visible para Grupos';
$l['ougc_pages_form_page_allowedGroups_desc'] = 'Selecciona los grupos que pueden ver esta página.';
$l['ougc_pages_form_page_menuItem'] = 'Agregar a Menu';
$l['ougc_pages_form_page_menuItem_desc'] = 'Si "Construir Menu" esta activo para esta categoría, se agregara un enlace para esta página en el menu.';
$l['ougc_pages_form_page_wrapper'] = 'Envolver en Plantilla';
$l['ougc_pages_form_page_wrapper_desc'] = 'Si se activa, el contenido de páginas no-PHP serán envueltas dentro de la plantilla <code>ougcpages_wrapper</code>.';
$l['ougc_pages_form_page_wol'] = 'Mostrar en la Lista de Usuarios Online';
$l['ougc_pages_form_page_wol_desc'] = 'Si se desactiva, la actividad dentro de esta página se mostrara como "Ubicación desconocida" enlazando a la página de inicio.';
$l['ougc_pages_form_page_php'] = 'Evaluar Código PHP';
$l['ougc_pages_form_page_php_desc'] = 'Si se activa, esta página será interpretada como código PHP. Desactiva para usar contenido HTML.';
$l['ougc_pages_form_page_parseMyCode'] = 'Evaluar MyCode';
$l['ougc_pages_form_page_parseMyCode_desc'] = 'Si se activa, esta página será interpretada usando MyCode.';
$l['ougc_pages_form_page_classicTemplate'] = 'Usar Plantilla de Estilo';
$l['ougc_pages_form_page_classicTemplate_desc'] = 'Si se desactiva, el "Contenido de Página" será ignorado y una plantilla del estilo será usada en su lugar. El nombre para esta plantilla será en el formato <code>ougcpages_pagePID</code>, por ejemplo: <code>ougcpages_page18</code>';
//$l['ougc_pages_form_page_classicTemplate_desc_plus'] = '<br /><strong>Nombre de plantilla:</strong> <code>{1}</code>';
$l['ougc_pages_form_page_template'] = 'Contenido de Página';
$l['ougc_pages_form_page_template_desc'] = 'Inserta el contenido HTML o PHP para esta página abajo.';
$l['ougc_pages_form_page_init'] = 'Punto de Inicialización de PHP.';
$l['ougc_pages_form_page_init_desc'] = 'Selecciona la sección de ejecución en que se cargará esta página cuando "Evaluar Código PHP" está activo.<br />
<strong>Inicialización:</strong> Ni siquiera todos los complementos han sido cargados en este punto. Aproximadamente 4-6 consultas SQL se han ejecutado en este punto.<br />
<strong>Global (Inicial):</strong> Principalmente solo la sesión y el idioma han sido cargados. Aproximadamente 6-8 consultas SQL se han ejecutado en este punto.<br />
<strong>Global (Intermedio):</strong> El estilo y los templates han sido cargados, pero sin el encabezado, el bloque de bienvenida, ni el pie de página. Aproximadamente 8-10 consultas SQL se han ejecutado en este punto.<br />
<span style="color: blue;"><strong>Global (Final):</strong> Predeterminado; selecciona esta opción si tienes dudas. Ofrece la mayor compatibilidad con todas las características del foro. Aproximadamente 9-13 consultas SQL se han ejecutado en este punto.</span>';
$l['ougc_pages_form_page_init_init'] = 'Inicialización';
$l['ougc_pages_form_page_init_start'] = 'Global (Inicial)';
$l['ougc_pages_form_page_init_intermediate'] = 'Global (Intermedio)';
$l['ougc_pages_form_page_init_end'] = 'Global (Final)';

$l['ougc_pages_category_name'] = 'Nombre';
$l['ougc_pages_category_order'] = 'Orden de Visualización';
$l['ougc_pages_category_status'] = 'Estado';
$l['ougc_pages_category_enabled'] = 'Habilitado';
$l['ougc_pages_category_disabled'] = 'Deshabilitado';
$l['ougc_pages_category_empty'] = 'No hay elementos para mostrar.';
$l['ougc_pages_page_export'] = 'Exportar';

$l['ougc_pages_button_update_order'] = 'Actualizar Orden';
$l['ougc_pages_button_continue'] = 'Guardar y Continuar';
$l['ougc_pages_button_submit'] = 'Guardar';
$l['ougc_pages_button_import'] = 'Importar Archivo';

$l['ougc_pages_category_view'] = 'Ver Categoría';
$l['ougc_pages_page_view'] = 'Ver Página';

// ACP Module: Messages
$l['ougc_pages_error_category_invalid'] = 'La categoría seleccionada es invalida.';
$l['ougc_pages_error_category_invalid_name'] = 'El nombre de la categoría debe ser entre 1 y {1} caracteres.';
$l['ougc_pages_error_category_invalid_description'] = 'La descripción de la categoría debe ser entre 1 y {1} caracteres.';
$l['ougc_pages_error_category_invalid_url'] = 'La URL de la categoría debe ser entre 1 y {1} caracteres.';
$l['ougc_pages_error_category_duplicated_url'] = 'La URL seleccionada esta en uso por otra categoría.';

$l['ougc_pages_error_page_invalid'] = 'La página seleccionada es invalida.';
$l['ougc_pages_error_page_invalid_name'] = 'El nombre de la página debe ser entre 1 y {1} caracteres.';
$l['ougc_pages_error_page_invalid_description'] = 'La descripción de la página debe ser entre 1 y {1} caracteres.';
$l['ougc_pages_error_page_invalid_url'] = 'La URL de la página debe ser entre 1 y {1} caracteres.';
$l['ougc_pages_error_page_duplicated_url'] = 'La URL seleccionada esta en uso por otra página.';
$l['ougc_pages_error_page_invalid_template'] = 'El contenido de la página parece ser invalido para páginas no-PHP.';

$l['ougc_pages_error_import_invalid'] = 'El contenido del archivo parece ser invalido.';
$l['ougc_pages_error_import_invalid_version'] = 'El contenido del archivo parece ser de una version incompatible.';

$l['ougc_pages_success_category_add'] = 'La categoría fue creada exitosamente.';
$l['ougc_pages_success_category_updated'] = 'La categoría fue actualizada exitosamente.';
$l['ougc_pages_success_category_updated_order'] = 'El orden de visualización de las categorías fue actualizado exitosamente.';
$l['ougc_pages_success_category_enabled'] = 'La categoría fue habilitada exitosamente.';
$l['ougc_pages_success_category_disabled'] = 'La categoría fue deshabilitada exitosamente.';
$l['ougc_pages_success_category_deleted'] = 'La categoría fue borrada exitosamente.';

$l['ougc_pages_success_page_add'] = 'La página fue creada exitosamente.';
$l['ougc_pages_success_page_updated'] = 'La página fue actualizada exitosamente.';
$l['ougc_pages_success_page_updated_order'] = 'El orden de visualización de las páginas fue actualizado exitosamente.';
$l['ougc_pages_success_page_enabled'] = 'La página fue habilitada exitosamente.';
$l['ougc_pages_success_page_disabled'] = 'La página fue deshabilitada exitosamente.';
$l['ougc_pages_success_page_deleted'] = 'La página fue borrada exitosamente.';
$l['ougc_pages_success_imported'] = 'La página fue importada exitosamente.';

// Admin Permissions
$l['ougc_pages_config_permissions'] = '¿Puede administrar páginas?';

// PluginLibrary
$l['ougc_pages_pl_required'] = 'Este complemento requiere la version {2} de <a href="{1}">PluginLibrary</a> para funcionar.';