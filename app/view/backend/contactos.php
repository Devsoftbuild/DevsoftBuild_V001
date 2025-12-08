<?php
/**
 * Gestión de Contactos
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Contactos';
$current_page = 'contactos';
require_once BASE_PATH . '/app/model/ContactoModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

// Cargar contacto si estamos viendo detalles
$contacto = ($id && $action === 'view') ? ContactoModel::getById($id) : null;

// Procesar cambio de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_estado') {
    if (ContactoModel::updateEstado($_POST['id'], $_POST['estado_contacto'])) {
        $message = 'Estado actualizado correctamente';
        $message_type = 'success';
    } else {
        $message = 'Error al actualizar estado';
        $message_type = 'error';
    }
}

$filters = [];
if (!empty($_GET['estado'])) {
    $filters['estado_contacto'] = $_GET['estado'];
}
$contactos = ContactoModel::getAll($filters);

$content_file = BASE_PATH . '/app/view/backend/pages/contactos_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>


