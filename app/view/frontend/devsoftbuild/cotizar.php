<?php
/**
 * Página de Cotización
 * DevSoft Build SAC
 */

// Configuración de la página
$page_title = 'Cotizar Proyecto';
$page_description = 'Solicita una cotización para tu proyecto. Te contactaremos pronto con una propuesta personalizada.';
$current_page = 'cotizar';

// Cargar servicios para el select
$servicios = ServicioModel::getAll(['estado_servicio' => 1]);
$servicio_seleccionado = isset($_GET['servicio']) ? (int)$_GET['servicio'] : null;

// Contenido de la página
$content_file = TEMPLATE_PATH . '/pages/cotizar_content.php';

// Incluir layout
include TEMPLATE_PATH . '/includes/layout.php';
?>


