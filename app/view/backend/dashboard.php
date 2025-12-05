<?php
/**
 * Dashboard Principal
 * DevSoft Build SAC - Panel de Administración
 */

$page_title = 'Dashboard';
$current_page = 'dashboard';

// Cargar modelos necesarios
require_once BASE_PATH . '/app/model/ServicioModel.php';
require_once BASE_PATH . '/app/model/ProyectoModel.php';
require_once BASE_PATH . '/app/model/ContactoModel.php';
require_once BASE_PATH . '/app/model/CotizacionModel.php';
require_once BASE_PATH . '/app/model/BannerModel.php';
require_once BASE_PATH . '/app/model/EstadisticaModel.php';
require_once BASE_PATH . '/app/model/TestimonioModel.php';

try {
    $pdo = getDB();
    
    // Estadísticas generales
    $stats = [];
    
    // Total de servicios
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM servicios WHERE estado_servicio = 1");
    $stats['servicios'] = $stmt->fetch()['total'];
    
    // Total de proyectos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM proyectos WHERE estado_proyecto = 'completado'");
    $stats['proyectos'] = $stmt->fetch()['total'];
    
    // Contactos nuevos (últimos 30 días)
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM contactos WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $stats['contactos_nuevos'] = $stmt->fetch()['total'];
    
    // Cotizaciones pendientes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM cotizaciones WHERE estado_cotizacion = 'pendiente'");
    $stats['cotizaciones_pendientes'] = $stmt->fetch()['total'];
    
    // Total de testimonios
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM testimonios WHERE estado_testimonio = 1");
    $stats['testimonios'] = $stmt->fetch()['total'];
    
    // Contactos recientes
    $contactos_recientes = ContactoModel::getAll(['estado_contacto' => 'nuevo']);
    $contactos_recientes = array_slice($contactos_recientes, 0, 5);
    
    // Cotizaciones recientes
    $cotizaciones_recientes = CotizacionModel::getAll(['estado_cotizacion' => 'pendiente']);
    $cotizaciones_recientes = array_slice($cotizaciones_recientes, 0, 5);
    
} catch (PDOException $e) {
    $stats = [
        'servicios' => 0,
        'proyectos' => 0,
        'contactos_nuevos' => 0,
        'cotizaciones_pendientes' => 0,
        'testimonios' => 0
    ];
    $contactos_recientes = [];
    $cotizaciones_recientes = [];
    if (DEBUG_MODE) {
        error_log("Error en dashboard: " . $e->getMessage());
    }
}

$content_file = BASE_PATH . '/app/view/backend/pages/dashboard_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>
