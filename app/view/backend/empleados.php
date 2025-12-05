<?php
$page_title = 'Gestión de Empleados';
$current_page = 'empleados';
require_once BASE_PATH . '/app/model/EmpleadoModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        if (EmpleadoModel::create($_POST)) {
            $message = 'Empleado creado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al crear empleado';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'update') {
        if (EmpleadoModel::update($_POST['id'], $_POST)) {
            $message = 'Empleado actualizado correctamente';
            $message_type = 'success';
            $action = 'list';
        } else {
            $message = 'Error al actualizar empleado';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'delete') {
        if (EmpleadoModel::delete($_POST['id'])) {
            $message = 'Empleado eliminado correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$filters = ['buscar' => $_GET['buscar'] ?? '', 'activo' => 1];
$empleados = EmpleadoModel::getAll($filters);
$empleado = ($id && ($action === 'edit' || $action === 'view')) ? EmpleadoModel::getById($id) : null;

$content_file = BASE_PATH . '/app/view/backend/pages/empleados_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

