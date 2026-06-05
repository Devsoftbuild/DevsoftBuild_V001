<?php
/**
 * Controlador Principal / Punto de Entrada
 * DevSoft Build SAC - Sistema Web Corporativo
 */

// Cargar core del sistema
require_once __DIR__ . '/core.php';

// Obtener la ruta solicitada (compatible local /devsoftbuild y producción en raíz)
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$request_path = parse_url($request_uri, PHP_URL_PATH) ?? '/';
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($script_dir === '/' || $script_dir === '\\' || $script_dir === '.') {
    $script_dir = '';
}
if ($script_dir !== '' && strpos($request_path, $script_dir) === 0) {
    $request_path = substr($request_path, strlen($script_dir));
}
$request_path = trim($request_path, '/');

// Si está vacío, es la raíz
if (empty($request_path) || $request_path === '/') {
    $request_path = 'home';
}

// Dividir la ruta en segmentos (slug puede llevar slashes, p. ej. URL mal usada como slug)
$segments = explode('/', $request_path);
$page = $segments[0];
$slug = null;
if (($page === 'proyecto' || $page === 'servicio') && count($segments) > 1) {
    $slug = rawurldecode(implode('/', array_slice($segments, 1)));
} elseif (isset($segments[1])) {
    $slug = rawurldecode($segments[1]);
}

// Rutas disponibles
$routes = [
    'home' => 'home',
    'servicios' => 'servicios',
    'servicio' => 'servicio_detalle', // Detalle de servicio por slug
    'proyectos' => 'proyectos',
    'proyecto' => 'proyecto_detalle', // Detalle de proyecto por slug
    'nosotros' => 'nosotros',
    'blog' => 'blog',
    'contacto' => 'contacto',
    'cotizar' => 'cotizar',
];

// Cargar modelos necesarios
require_once BASE_PATH . '/app/model/ServicioModel.php';
require_once BASE_PATH . '/app/model/ProyectoModel.php';
require_once BASE_PATH . '/app/model/BannerModel.php';
require_once BASE_PATH . '/app/model/EstadisticaModel.php';
require_once BASE_PATH . '/app/model/TestimonioModel.php';
require_once BASE_PATH . '/app/model/TecnologiaModel.php';
require_once BASE_PATH . '/app/model/ProcesoModel.php';
require_once BASE_PATH . '/app/model/ContactoModel.php';
require_once BASE_PATH . '/app/model/CotizacionModel.php';

// Manejar detalle de servicio por slug
if ($page === 'servicio' && $slug) {
    $servicio = ServicioModel::getBySlug($slug, true);
    
    if ($servicio) {
        $page_title = $servicio['meta_titulo_servicio'] ?? $servicio['nombre_servicio'] . ' - ' . APP_NAME;
        $page_description = $servicio['meta_descripcion_servicio'] ?? truncateText($servicio['descripcion_corta_servicio'] ?? '', 160);
        $current_page = 'servicios';
        $content_file = TEMPLATE_PATH . '/pages/servicio_detalle.php';
        include TEMPLATE_PATH . '/includes/layout.php';
        exit;
    } else {
        http_response_code(404);
        $page_title = 'Servicio no encontrado';
        $page_description = 'El servicio que buscas no existe';
        $current_page = '';
        $content = '<div class="section"><div class="container text-center"><h1>404 - Servicio no encontrado</h1><p>El servicio que buscas no existe o no está disponible.</p><a href="' . URL_SERV . '/servicios" class="btn btn-primary">Volver a servicios</a></div></div>';
        include TEMPLATE_PATH . '/includes/layout.php';
        exit;
    }
}

// Manejar detalle de proyecto por slug
if ($page === 'proyecto' && $slug) {
    $proyecto = ProyectoModel::getBySlug($slug);
    
    if ($proyecto) {
        $tecnologias = ProyectoModel::getTecnologias($proyecto['id_proyecto']);
        $imagenes = ProyectoModel::getImagenes($proyecto['id_proyecto']);
        
        $page_title = $proyecto['meta_titulo_proyecto'] ?? $proyecto['nombre_proyecto'] . ' - ' . APP_NAME;
        $page_description = $proyecto['meta_descripcion_proyecto'] ?? truncateText($proyecto['descripcion_corta_proyecto'] ?? '', 160);
        $current_page = 'proyectos';
        $content_file = TEMPLATE_PATH . '/pages/proyecto_detalle.php';
        include TEMPLATE_PATH . '/includes/layout.php';
        exit;
    } else {
        http_response_code(404);
        $page_title = 'Proyecto no encontrado';
        $page_description = 'El proyecto que buscas no existe';
        $current_page = '';
        $content = '<div class="section"><div class="container text-center"><h1>404 - Proyecto no encontrado</h1><p>El proyecto que buscas no existe o no está disponible.</p><a href="' . URL_SERV . '/proyectos" class="btn btn-primary">Volver a proyectos</a></div></div>';
        include TEMPLATE_PATH . '/includes/layout.php';
        exit;
    }
}

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($page === 'contacto') {
        $data = [
            'nombre_contacto' => sanitize($_POST['nombre'] ?? ''),
            'email_contacto' => sanitize($_POST['email'] ?? ''),
            'telefono_contacto' => sanitize($_POST['telefono'] ?? ''),
            'empresa_contacto' => sanitize($_POST['empresa'] ?? ''),
            'tipo_proyecto_contacto' => sanitize($_POST['tipo_proyecto'] ?? ''),
            'mensaje_contacto' => sanitize($_POST['mensaje'] ?? '')
        ];
        
        if (ContactoModel::create($data)) {
            setFlashMessage('success', '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo pronto.');
        } else {
            setFlashMessage('error', 'Error al enviar el mensaje. Por favor, intenta nuevamente.');
        }
        redirect(URL_SERV . '/contacto');
    }
    
    if ($page === 'cotizar') {
        $data = [
            'nombre_cotizacion' => sanitize($_POST['nombre'] ?? ''),
            'email_cotizacion' => sanitize($_POST['email'] ?? ''),
            'telefono_cotizacion' => sanitize($_POST['telefono'] ?? ''),
            'empresa_cotizacion' => sanitize($_POST['empresa'] ?? ''),
            'tipo_proyecto_cotizacion' => sanitize($_POST['tipo_proyecto'] ?? ''),
            'descripcion_proyecto_cotizacion' => sanitize($_POST['descripcion'] ?? ''),
            'presupuesto_estimado_cotizacion' => sanitize($_POST['presupuesto'] ?? ''),
            'plazo_estimado_cotizacion' => sanitize($_POST['plazo'] ?? ''),
            'servicio_id_cotizacion' => !empty($_POST['servicio_id']) ? (int)$_POST['servicio_id'] : null
        ];
        
        if (CotizacionModel::create($data)) {
            setFlashMessage('success', '¡Solicitud de cotización enviada! Te contactaremos pronto.');
        } else {
            setFlashMessage('error', 'Error al enviar la solicitud. Por favor, intenta nuevamente.');
        }
        redirect(URL_SERV . '/cotizar');
    }
}

// Verificar si la ruta existe
if (isset($routes[$page])) {
    $view_file = $routes[$page];
    
    // Cargar la vista
    $view_path = TEMPLATE_PATH . '/' . $view_file . '.php';
    
    if (file_exists($view_path)) {
        require_once $view_path;
    } else {
        // Vista no encontrada
        http_response_code(404);
        $page_title = 'Página no encontrada';
        $page_description = 'La página que buscas no existe';
        $current_page = '';
        $content = '<div class="section"><div class="container text-center"><h1>404 - Página no encontrada</h1><p>La página que buscas no existe.</p><a href="' . URL_SERV . '" class="btn btn-primary">Volver al inicio</a></div></div>';
        include TEMPLATE_PATH . '/includes/layout.php';
    }
} else {
    // Ruta no encontrada - 404
    http_response_code(404);
    $page_title = 'Página no encontrada';
    $page_description = 'La página que buscas no existe';
    $current_page = '';
    $content = '<div class="section"><div class="container text-center"><h1>404 - Página no encontrada</h1><p>La página que buscas no existe.</p><a href="' . URL_SERV . '" class="btn btn-primary">Volver al inicio</a></div></div>';
    include TEMPLATE_PATH . '/includes/layout.php';
}


