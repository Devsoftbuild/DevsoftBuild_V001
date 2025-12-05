<?php
/**
 * Gestión de Estadísticas
 * DevSoft Build SAC
 */

$page_title = 'Gestión de Estadísticas';
$current_page = 'estadisticas';
require_once BASE_PATH . '/app/model/EstadisticaModel.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

$estadisticas = EstadisticaModel::getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'update') {
        try {
            $pdo = getDB();
            foreach ($_POST['estadisticas'] as $id_est => $data) {
                $stmt = $pdo->prepare("UPDATE estadisticas SET 
                    titulo_estadistica = ?,
                    valor_estadistica = ?, 
                    icono_estadistica = ?,
                    orden_estadistica = ?,
                    estado_estadistica = ?
                    WHERE id_estadistica = ?");
                $stmt->execute([
                    $data['titulo'] ?? '',
                    $data['valor'] ?? '',
                    $data['icono'] ?? '',
                    $data['orden'] ?? 0,
                    isset($data['estado']) ? 1 : 0,
                    $id_est
                ]);
            }
            $message = 'Estadísticas actualizadas correctamente';
            $message_type = 'success';
        } catch (PDOException $e) {
            $message = 'Error al actualizar estadísticas';
            $message_type = 'error';
        }
    }
}

$content_file = BASE_PATH . '/app/view/backend/pages/estadisticas_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

