<?php
/**
 * Panel de Administración
 * DevSoft Build SAC
 */

// Cargar core del sistema
require_once __DIR__ . '/../core.php';

// Cargar modelo de autenticación
require_once BASE_PATH . '/app/model/AuthModel.php';

// Verificar autenticación (excepto para login)
$current_page = basename($_SERVER['PHP_SELF'], '.php');
if ($current_page !== 'login' && $current_page !== 'logout') {
    AuthModel::requireAuth();
}

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

// Remover el directorio base de la URI
$base_path = str_replace('\\', '/', dirname($script_name));
$request_path = str_replace($base_path, '', $request_uri);
$request_path = str_replace('/dw-panel', '', $request_path);

// Limpiar la ruta
$request_path = trim($request_path, '/');
$request_path = parse_url($request_path, PHP_URL_PATH);

// Si está vacío, es el dashboard
if (empty($request_path) || $request_path === '/') {
    $request_path = 'dashboard';
}

// Dividir la ruta en segmentos
$segments = explode('/', $request_path);
$page = $segments[0];

// Rutas disponibles del panel para DevSoft Build
$routes = [
    'dashboard' => 'dashboard',
    'servicios' => 'servicios',
    'proyectos' => 'proyectos',
    'tecnologias' => 'tecnologias',
    'testimonios' => 'testimonios',
    'contactos' => 'contactos',
    'cotizaciones' => 'cotizaciones',
    'banners' => 'banners',
    'estadisticas' => 'estadisticas',
    'procesos' => 'procesos',
    'blog' => 'blog',
    'usuarios' => 'usuarios',
    'configuracion' => 'configuracion',
    'configuracion_logo' => 'configuracion_logo',
];

// Verificar si la ruta existe
if (isset($routes[$page])) {
    $view_file = $routes[$page];
    
    // Ruta del archivo de vista
    $view_path = BASE_PATH . '/app/view/backend/' . $view_file . '.php';
    
    if (file_exists($view_path)) {
        require_once $view_path;
    } else {
        // Vista no encontrada
        http_response_code(404);
        include BASE_PATH . '/app/view/backend/404.php';
    }
} else {
    // Ruta no encontrada - 404
    http_response_code(404);
    include BASE_PATH . '/app/view/backend/404.php';
}
