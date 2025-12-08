<?php
/**
 * Gestión de Procesos
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Procesos';
$current_page = 'procesos';
require_once BASE_PATH . '/app/model/ProcesoModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

$procesos = ProcesoModel::getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'update') {
        try {
            $pdo = getDB();
            foreach ($_POST['procesos'] as $id_proc => $data) {
                $stmt = $pdo->prepare("UPDATE procesos SET 
                    titulo_proceso = ?, 
                    descripcion_proceso = ?,
                    icono_proceso = ?,
                    orden_proceso = ?,
                    estado_proceso = ?
                    WHERE id_proceso = ?");
                $stmt->execute([
                    $data['titulo'],
                    $data['descripcion'],
                    $data['icono'],
                    $data['orden'],
                    isset($data['estado']) ? 1 : 0,
                    $id_proc
                ]);
            }
            $message = 'Procesos actualizados correctamente';
            $message_type = 'success';
        } catch (PDOException $e) {
            $message = 'Error al actualizar procesos';
            $message_type = 'error';
        }
    }
}

$content_file = BASE_PATH . '/app/view/backend/pages/procesos_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>


