<?php
/**
 * Gestión de Cotizaciones
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Cotizaciones';
$current_page = 'cotizaciones';
require_once BASE_PATH . '/app/model/CotizacionModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Cargar cotización si estamos viendo detalles
$cotizacion = ($id && $action === 'view') ? CotizacionModel::getById($id) : null;

// Procesar cambio de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_estado') {
    if (CotizacionModel::updateEstado($_POST['id'], $_POST['estado_cotizacion'])) {
        $message = 'Estado actualizado correctamente';
        $message_type = 'success';
    } else {
        $message = 'Error al actualizar estado';
        $message_type = 'error';
    }
}

$filters = [];
if (!empty($_GET['estado'])) {
    $filters['estado_cotizacion'] = $_GET['estado'];
}
$cotizaciones = CotizacionModel::getAll($filters);

$content_file = BASE_PATH . '/app/view/backend/pages/cotizaciones_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

