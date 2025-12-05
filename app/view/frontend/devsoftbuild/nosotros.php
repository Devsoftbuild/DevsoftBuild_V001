<?php
/**
 * Página Nosotros
 * DevSoft Build SAC
 */

// Configuración de la página
$page_title = 'Nosotros';
$page_description = 'Conoce más sobre DevSoft Build SAC, nuestra misión, visión y equipo de desarrollo.';
$current_page = 'nosotros';

// Cargar datos
$estadisticas = EstadisticaModel::getAll(['estado_estadistica' => 1]);
$tecnologias = TecnologiaModel::getAll(['estado_tecnologia' => 1]);

// Contenido de la página
$content_file = TEMPLATE_PATH . '/pages/nosotros_content.php';

// Incluir layout
include TEMPLATE_PATH . '/includes/layout.php';
?>

