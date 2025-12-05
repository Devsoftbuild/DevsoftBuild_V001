<?php
/**
 * Gestión de Citas
 * Panel de Administración
 */

$page_title = 'Gestión de Citas';
$current_page = 'citas';

// Cargar modelos
require_once BASE_PATH . '/app/model/CitaModel.php';
require_once BASE_PATH . '/app/model/Database.php';

// Obtener servicios y clientes para los select
$pdo = getDB();
$servicios = $pdo->query("SELECT id, nombre, precio, duracion_minutos FROM servicios WHERE activo = 1 ORDER BY nombre")->fetchAll();
$clientes = $pdo->query("SELECT id, nombre, apellido, telefono FROM clientes WHERE activo = 1 ORDER BY nombre, apellido")->fetchAll();
$empleados = $pdo->query("SELECT id, nombre, apellido FROM empleados WHERE activo = 1 ORDER BY nombre, apellido")->fetchAll();

// Procesar acciones
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$message = '';
$message_type = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $data = [
                    'cliente_id' => $_POST['cliente_id'],
                    'servicio_id' => $_POST['servicio_id'],
                    'empleado_id' => !empty($_POST['empleado_id']) ? $_POST['empleado_id'] : null,
                    'fecha' => $_POST['fecha'],
                    'hora' => $_POST['hora'],
                    'duracion_minutos' => $_POST['duracion_minutos'],
                    'precio' => $_POST['precio'],
                    'descuento' => $_POST['descuento'] ?? 0,
                    'estado' => $_POST['estado'],
                    'notas' => $_POST['notas'] ?? null
                ];
                
                if (CitaModel::create($data)) {
                    $message = 'Cita creada correctamente';
                    $message_type = 'success';
                    $action = 'list';
                } else {
                    $message = 'Error al crear la cita';
                    $message_type = 'error';
                }
                break;
                
            case 'update':
                $data = [
                    'cliente_id' => $_POST['cliente_id'],
                    'servicio_id' => $_POST['servicio_id'],
                    'empleado_id' => !empty($_POST['empleado_id']) ? $_POST['empleado_id'] : null,
                    'fecha' => $_POST['fecha'],
                    'hora' => $_POST['hora'],
                    'duracion_minutos' => $_POST['duracion_minutos'],
                    'precio' => $_POST['precio'],
                    'descuento' => $_POST['descuento'] ?? 0,
                    'estado' => $_POST['estado'],
                    'notas' => $_POST['notas'] ?? null
                ];
                
                if (CitaModel::update($_POST['id'], $data)) {
                    $message = 'Cita actualizada correctamente';
                    $message_type = 'success';
                    $action = 'list';
                } else {
                    $message = 'Error al actualizar la cita';
                    $message_type = 'error';
                }
                break;
                
            case 'delete':
                if (CitaModel::delete($_POST['id'])) {
                    $message = 'Cita eliminada correctamente';
                    $message_type = 'success';
                } else {
                    $message = 'Error al eliminar la cita';
                    $message_type = 'error';
                }
                $action = 'list';
                break;
                
            case 'change_status':
                if (CitaModel::changeStatus($_POST['id'], $_POST['estado'])) {
                    $message = 'Estado actualizado correctamente';
                    $message_type = 'success';
                } else {
                    $message = 'Error al actualizar el estado';
                    $message_type = 'error';
                }
                $action = 'list';
                break;
        }
    }
}

// Obtener filtros
$filters = [
    'estado' => $_GET['estado'] ?? '',
    'fecha_desde' => $_GET['fecha_desde'] ?? '',
    'fecha_hasta' => $_GET['fecha_hasta'] ?? '',
    'buscar' => $_GET['buscar'] ?? ''
];

// Obtener citas
$citas = CitaModel::getAll($filters);
$total_citas = CitaModel::count($filters);

// Si es editar o ver, obtener la cita
$cita = null;
if ($id && ($action === 'edit' || $action === 'view')) {
    $cita = CitaModel::getById($id);
    if (!$cita) {
        $message = 'Cita no encontrada';
        $message_type = 'error';
        $action = 'list';
    }
}

$content_file = BASE_PATH . '/app/view/backend/pages/citas_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

