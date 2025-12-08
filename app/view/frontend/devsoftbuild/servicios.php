<?php
/**
 * Página de Servicios
 * DevSoft Build SAC
 */

// Configuración de la página
$page_title = 'Servicios';
$page_description = 'Ofrecemos servicios de desarrollo web, aplicaciones móviles, software a medida y consultoría tecnológica.';
$current_page = 'servicios';

// Cargar datos
$servicios = ServicioModel::getAll(['estado_servicio' => 1]);

// Contenido de la página
$content_file = TEMPLATE_PATH . '/pages/servicios_content.php';

// Incluir layout
include TEMPLATE_PATH . '/includes/layout.php';
?>


