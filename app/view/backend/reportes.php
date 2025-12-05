<?php
$page_title = 'Reportes y Estadísticas';
$current_page = 'reportes';
require_once BASE_PATH . '/app/model/Database.php';
$pdo = getDB();

$tipo_reporte = $_GET['tipo'] ?? 'ingresos';
$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-01');
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-t');

// Ingresos
$sql_ingresos = "SELECT DATE(fecha) as dia, SUM(total) as total 
                 FROM facturas 
                 WHERE fecha BETWEEN ? AND ? AND estado = 'pagada' 
                 GROUP BY DATE(fecha) 
                 ORDER BY dia";
$stmt = $pdo->prepare($sql_ingresos);
$stmt->execute([$fecha_desde, $fecha_hasta]);
$ingresos_diarios = $stmt->fetchAll();

$total_ingresos = array_sum(array_column($ingresos_diarios, 'total'));

// Servicios más vendidos
$servicios_vendidos = $pdo->query("SELECT s.nombre, COUNT(c.id) as cantidad, SUM(c.total) as total 
                                   FROM citas c 
                                   INNER JOIN servicios s ON c.servicio_id = s.id 
                                   WHERE c.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' 
                                   GROUP BY s.id 
                                   ORDER BY cantidad DESC 
                                   LIMIT 10")->fetchAll();

// Clientes frecuentes
$clientes_frecuentes = $pdo->query("SELECT cl.nombre, cl.apellido, COUNT(c.id) as citas, SUM(c.total) as total 
                                    FROM citas c 
                                    INNER JOIN clientes cl ON c.cliente_id = cl.id 
                                    WHERE c.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' 
                                    GROUP BY cl.id 
                                    ORDER BY citas DESC 
                                    LIMIT 10")->fetchAll();

$content_file = BASE_PATH . '/app/view/backend/pages/reportes_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

