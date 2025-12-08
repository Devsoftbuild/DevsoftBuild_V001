<?php
/**
 * Página de Proyectos
 * DevSoft Build SAC
 */

// Configuración de la página
$page_title = 'Proyectos';
$page_description = 'Conoce nuestros proyectos de desarrollo web y aplicaciones móviles. Casos de éxito y soluciones implementadas.';
$current_page = 'proyectos';

// Cargar datos
$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$filtros = ['estado_proyecto' => 'completado'];
if (!empty($filtro_tipo)) {
    $filtros['tipo_proyecto'] = $filtro_tipo;
}
$proyectos = ProyectoModel::getAll($filtros);
$tipos_proyectos = ['web', 'mobile', 'software', 'ecommerce'];

// Contenido de la página
$content_file = TEMPLATE_PATH . '/pages/proyectos_content.php';

// Incluir layout
include TEMPLATE_PATH . '/includes/layout.php';
?>


