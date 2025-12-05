<?php
$page_title = 'Calendario de Citas';
$current_page = 'calendario';
require_once BASE_PATH . '/app/model/CitaModel.php';
$pdo = getDB();

$fecha_seleccionada = $_GET['fecha'] ?? date('Y-m-d');
$citas_dia = CitaModel::getByDate($fecha_seleccionada);
$empleados = $pdo->query("SELECT id, nombre, apellido FROM empleados WHERE activo = 1")->fetchAll();

$content_file = BASE_PATH . '/app/view/backend/pages/calendario_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

