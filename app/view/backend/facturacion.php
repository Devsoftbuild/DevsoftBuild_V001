<?php
$page_title = 'Facturación';
$current_page = 'facturacion';
require_once BASE_PATH . '/app/model/Database.php';
$pdo = getDB();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        try {
            $pdo->beginTransaction();
            $numero = 'FAC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $stmt = $pdo->prepare("INSERT INTO facturas (numero_factura, cliente_id, cita_id, fecha, subtotal, descuento, impuesto, total, estado) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $numero, $_POST['cliente_id'], $_POST['cita_id'] ?? null,
                $_POST['fecha'], $_POST['subtotal'], $_POST['descuento'] ?? 0,
                $_POST['impuesto'] ?? 0, $_POST['total'], $_POST['estado'] ?? 'pendiente'
            ]);
            $factura_id = $pdo->lastInsertId();
            $pdo->commit();
            $message = 'Factura creada correctamente';
            $message_type = 'success';
            $action = 'list';
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = 'Error al crear factura';
            $message_type = 'error';
        }
    } elseif ($_POST['action'] === 'change_status') {
        $stmt = $pdo->prepare("UPDATE facturas SET estado = ? WHERE id = ?");
        if ($stmt->execute([$_POST['estado'], $_POST['id']])) {
            $message = 'Estado actualizado';
            $message_type = 'success';
        }
        $action = 'list';
    }
}

$filters = ['buscar' => $_GET['buscar'] ?? '', 'estado' => $_GET['estado'] ?? ''];
$sql = "SELECT f.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido 
        FROM facturas f 
        INNER JOIN clientes c ON f.cliente_id = c.id 
        WHERE 1=1";
$params = [];
if ($filters['buscar']) {
    $sql .= " AND f.numero_factura LIKE ?";
    $params[] = "%{$filters['buscar']}%";
}
if ($filters['estado']) {
    $sql .= " AND f.estado = ?";
    $params[] = $filters['estado'];
}
$sql .= " ORDER BY f.fecha DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$facturas = $stmt->fetchAll();

$clientes = $pdo->query("SELECT id, nombre, apellido FROM clientes WHERE activo = 1")->fetchAll();
$citas = $pdo->query("SELECT id, fecha, hora FROM citas ORDER BY fecha DESC LIMIT 50")->fetchAll();

$content_file = BASE_PATH . '/app/view/backend/pages/facturacion_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

