<?php
$page_title = 'Gestión de Promociones';
$current_page = 'promociones';
require_once BASE_PATH . '/app/model/Database.php';
$pdo = getDB();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $stmt = $pdo->prepare("INSERT INTO promociones (nombre, descripcion, tipo, valor, codigo, servicio_id, categoria_servicio_id, fecha_inicio, fecha_fin, uso_maximo, activo) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([
            $_POST['nombre'], $_POST['descripcion'] ?? null, $_POST['tipo'],
            $_POST['valor'], $_POST['codigo'] ?? null, $_POST['servicio_id'] ?? null,
            $_POST['categoria_servicio_id'] ?? null, $_POST['fecha_inicio'], $_POST['fecha_fin'],
            $_POST['uso_maximo'] ?? null, $_POST['activo'] ?? 1
        ])) {
            $message = 'Promoción creada correctamente';
            $message_type = 'success';
            $action = 'list';
        }
    } elseif ($_POST['action'] === 'update') {
        $stmt = $pdo->prepare("UPDATE promociones SET nombre=?, descripcion=?, tipo=?, valor=?, codigo=?, servicio_id=?, categoria_servicio_id=?, fecha_inicio=?, fecha_fin=?, uso_maximo=?, activo=? WHERE id=?");
        if ($stmt->execute([
            $_POST['nombre'], $_POST['descripcion'] ?? null, $_POST['tipo'],
            $_POST['valor'], $_POST['codigo'] ?? null, $_POST['servicio_id'] ?? null,
            $_POST['categoria_servicio_id'] ?? null, $_POST['fecha_inicio'], $_POST['fecha_fin'],
            $_POST['uso_maximo'] ?? null, $_POST['activo'] ?? 1, $_POST['id']
        ])) {
            $message = 'Promoción actualizada correctamente';
            $message_type = 'success';
            $action = 'list';
        }
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("UPDATE promociones SET activo = 0 WHERE id = ?");
        if ($stmt->execute([$_POST['id']])) {
            $message = 'Promoción eliminada correctamente';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$promociones = $pdo->query("SELECT p.*, s.nombre as servicio_nombre, cs.nombre as categoria_nombre 
                            FROM promociones p 
                            LEFT JOIN servicios s ON p.servicio_id = s.id 
                            LEFT JOIN categorias_servicios cs ON p.categoria_servicio_id = cs.id 
                            ORDER BY p.fecha_inicio DESC")->fetchAll();
$servicios = $pdo->query("SELECT id, nombre FROM servicios WHERE activo = 1")->fetchAll();
$categorias = $pdo->query("SELECT id, nombre FROM categorias_servicios WHERE activo = 1")->fetchAll();
$promocion = null;
if ($id && $action === 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM promociones WHERE id = ?");
    $stmt->execute([$id]);
    $promocion = $stmt->fetch();
}

$content_file = BASE_PATH . '/app/view/backend/pages/promociones_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

