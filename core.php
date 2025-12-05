<?php
/**
 * Core del Sistema
 * Inicializa la aplicación y carga las dependencias necesarias
 */

// Definir ruta base
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

// Cargar configuración
require_once BASE_PATH . '/app/config.php';

// Cargar modelos base
require_once BASE_PATH . '/app/model/Database.php';
require_once BASE_PATH . '/app/model/ConfigModel.php';

// Verificar que las constantes de plantilla estén definidas
if (!defined('TEMPLATE_PATH')) {
    define('TEMPLATE_PATH', BASE_PATH . '/app/view/frontend/' . TEMPLATE_ACTIVA);
}

if (!defined('TEMPLATE_URL')) {
    define('TEMPLATE_URL', URL_SERV . '/app/view/frontend/' . TEMPLATE_ACTIVA);
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Función helper para cargar una vista
 * @param string $view Nombre de la vista (sin extensión)
 * @param array $data Datos para pasar a la vista
 */
function loadView($view, $data = []) {
    // Extraer variables del array
    extract($data);
    
    // Ruta del archivo de vista
    $viewFile = TEMPLATE_PATH . '/' . $view . '.php';
    
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        die("Error: Vista no encontrada: {$view}");
    }
}

/**
 * Función helper para redirigir
 * @param string $url URL a la que redirigir
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Función helper para sanitizar entrada
 * @param string $data Datos a sanitizar
 * @return string
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Función helper para mostrar mensajes flash
 * @param string $type Tipo de mensaje (success, error, warning, info)
 * @param string $message Mensaje a mostrar
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Función helper para obtener y limpiar mensaje flash
 * @return array|null
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Función helper para formatear precios con la moneda configurada
 * @param float $amount Cantidad a formatear
 * @param int $decimals Número de decimales (default: 2)
 * @return string Precio formateado con símbolo de moneda
 */
function formatPrice($amount, $decimals = 2) {
    $formatted = number_format($amount, $decimals, '.', ',');
    $symbol = defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : 'S/.';
    $position = defined('CURRENCY_POSITION') ? CURRENCY_POSITION : 'before';
    
    if ($position === 'after') {
        return $formatted . ' ' . $symbol;
    } else {
        return $symbol . ' ' . $formatted;
    }
}

/**
 * Función helper para truncar texto (resumen)
 * @param string $text Texto a truncar
 * @param int $length Longitud máxima (default: 150)
 * @param string $suffix Sufijo si se trunca (default: '...')
 * @return string Texto truncado
 */
function truncateText($text, $length = 150, $suffix = '...') {
    if (empty($text)) return '';
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . $suffix;
}

