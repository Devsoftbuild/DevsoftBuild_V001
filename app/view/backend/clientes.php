<?php
$page_title = 'Gestión de Clientes';
$current_page = 'clientes';
require_once BASE_PATH . '/app/model/ClienteModel.php';
$pdo = getDB();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        if (ClienteModel::create($_POST)) {
            $message = 'Cliente creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear cliente';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        if (ClienteModel::update($_POST['id'], $_POST)) {
            $message = 'Cliente actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar cliente';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        if (ClienteModel::delete($_POST['id'])) {
            $message = 'Cliente eliminado correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$filters = ['buscar' => $_GET['buscar'] ?? '', 'activo' => 1];
$clientes = ClienteModel::getAll($filters);
$cliente = ($id && ($action === 'edit' || $action === 'view')) ? ClienteModel::getById($id) : null;

$content_file = BASE_PATH . '/app/view/backend/pages/clientes_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

