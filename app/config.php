<?php
/**
 * Configuración del Sistema
 * Solo constantes y variables de configuración básica
 */

// Prevenir acceso directo
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__);
}

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'devsoftbuild');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ============================================
// CONFIGURACIÓN DE URL Y RUTAS
// ============================================
define('URL_SERV', 'http://localhost/devsoftbuild');

// BASE_PATH ya está definida en core.php, solo definir si no existe
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

define('PUBLIC_PATH', BASE_PATH . '/app/public_root');

// ============================================
// CONFIGURACIÓN DE PLANTILLAS
// ============================================
define('TEMPLATE_ACTIVA', 'devsoftbuild'); // Plantilla para DevSoft Build
define('TEMPLATE_PATH', BASE_PATH . '/app/view/frontend/' . TEMPLATE_ACTIVA);
define('TEMPLATE_URL', URL_SERV . '/app/view/frontend/' . TEMPLATE_ACTIVA);

// ============================================
// CONFIGURACIÓN GENERAL
// ============================================
define('APP_NAME', 'DevSoft Build SAC');
define('APP_TIMEZONE', 'America/Lima');
date_default_timezone_set(APP_TIMEZONE);

// ============================================
// CONFIGURACIÓN DE MONEDA
// ============================================
define('CURRENCY_SYMBOL', 'S/.'); // Símbolo de la moneda (S/. para soles, $ para dólares, € para euros)
define('CURRENCY_CODE', 'PEN'); // Código ISO de la moneda (PEN para soles, USD para dólares, EUR para euros)
define('CURRENCY_POSITION', 'before'); // 'before' o 'after' - posición del símbolo respecto al número

// ============================================
// CONFIGURACIÓN DE DESARROLLO
// ============================================
define('DEBUG_MODE', true);
define('ERROR_REPORTING', E_ALL);
define('DISPLAY_ERRORS', true);

// Manejo de Errores
if (DEBUG_MODE) {
    error_reporting(ERROR_REPORTING);
    ini_set('display_errors', DISPLAY_ERRORS ? '1' : '0');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

