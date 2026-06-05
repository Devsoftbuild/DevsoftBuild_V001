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
 * Formatea un importe con símbolo de moneda (uso interno de formatPrice).
 */
function formatMoneyPart($num, $decimals, $symbol, $position) {
    $formatted = number_format((float) $num, $decimals, '.', ',');
    if ($position === 'after') {
        return $formatted . ' ' . $symbol;
    }
    return $symbol . ' ' . $formatted;
}

/**
 * Función helper para formatear precios con la moneda configurada.
 * Acepta números o rangos / slugs del formulario de cotización (p. ej. "10000-25000", "menos-5000").
 *
 * @param float|string|null $amount Cantidad o rango textual
 * @param int $decimals Número de decimales para importes numéricos simples (default: 2)
 * @return string Precio formateado con símbolo de moneda
 */
function formatPrice($amount, $decimals = 2) {
    $symbol = defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : 'S/.';
    $position = defined('CURRENCY_POSITION') ? CURRENCY_POSITION : 'before';

    if ($amount === null || $amount === '') {
        return '';
    }

    $str = is_string($amount) ? trim($amount) : null;

    if ($str !== null && $str !== '') {
        $slugLabels = [
            'menos-5000' => 'Menos de ' . $symbol . ' 5,000',
            '5000-10000' => $symbol . ' 5,000 - ' . $symbol . ' 10,000',
            '10000-25000' => $symbol . ' 10,000 - ' . $symbol . ' 25,000',
            '25000-50000' => $symbol . ' 25,000 - ' . $symbol . ' 50,000',
            'mas-50000' => 'Más de ' . $symbol . ' 50,000',
        ];
        if (isset($slugLabels[$str])) {
            return $slugLabels[$str];
        }

        if (preg_match('/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)$/', $str, $m)) {
            $low = (float) $m[1];
            $high = (float) $m[2];
            $rangeDecimals = ((floor($low) == $low) && (floor($high) == $high)) ? 0 : $decimals;
            return formatMoneyPart($low, $rangeDecimals, $symbol, $position)
                . ' - '
                . formatMoneyPart($high, $rangeDecimals, $symbol, $position);
        }

        if (!is_numeric($str)) {
            return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        }

        $amount = $str;
    }

    if (!is_numeric($amount)) {
        return htmlspecialchars((string) $amount, ENT_QUOTES, 'UTF-8');
    }

    $formatted = number_format((float) $amount, $decimals, '.', ',');
    if ($position === 'after') {
        return $formatted . ' ' . $symbol;
    }
    return $symbol . ' ' . $formatted;
}

/**
 * Precio de servicio: solo número + soles (S/.).
 * No usa formatPrice() para evitar confundir rangos "10000-25000" ni símbolos mal configurados.
 */
function formatServicePrice($amount, $decimals = 2) {
    if ($amount === null || $amount === '') {
        return '';
    }
    if (is_string($amount)) {
        $amount = str_replace(',', '', trim($amount));
        // Si alguien guardó un rango por error (ej. 262145-1500), usar solo el precio final
        if (preg_match('/^\d+(?:\.\d+)?\s*-\s*(\d+(?:\.\d+)?)$/', $amount, $m)) {
            $amount = $m[1];
        }
    }
    if (!is_numeric($amount)) {
        return '';
    }
    $num = (float) $amount;
    if ($num <= 0) {
        return '';
    }
    return 'S/. ' . number_format($num, $decimals, '.', ',');
}

/**
 * URL pública de un servicio por slug.
 */
function servicio_url(array $servicio) {
    $slug = trim((string) ($servicio['slug_servicio'] ?? ''), '/ ');
    if ($slug === '') {
        return URL_SERV . '/servicios';
    }
    return URL_SERV . '/servicio/' . rawurlencode($slug);
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

/**
 * El slug fue guardado por error como URL absoluta (p. ej. https://sitio.com/)
 */
function proyecto_slug_es_url_legacy(array $proyecto): bool {
    $slug = trim($proyecto['slug_proyecto'] ?? '');
    return $slug !== '' && preg_match('#^https?://#i', $slug);
}

/**
 * Enlace para "Ver detalles": ficha en el sitio, o URL en vivo si el slug es legacy.
 */
function proyecto_url_ficha(array $proyecto): string {
    $base = rtrim(URL_SERV, '/');
    if (proyecto_slug_es_url_legacy($proyecto)) {
        $url = trim($proyecto['url_proyecto'] ?? '');
        return $url !== '' ? $url : trim($proyecto['slug_proyecto']);
    }
    $slug = trim($proyecto['slug_proyecto'] ?? '');
    if ($slug === '') {
        return $base . '/proyectos';
    }
    return $base . '/proyecto/' . rawurlencode($slug);
}

/**
 * Atributos HTML para enlaces que apuntan fuera del sitio (slug guardado como URL).
 */
function proyecto_ficha_target_attrs(array $proyecto): string {
    return proyecto_slug_es_url_legacy($proyecto) ? 'target="_blank" rel="noopener"' : '';
}

