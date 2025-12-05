<?php
/**
 * Cerrar Sesión
 * Panel de Administración
 */

// Cargar core del sistema
require_once __DIR__ . '/../core.php';

// Cargar modelo de autenticación
require_once BASE_PATH . '/app/model/AuthModel.php';

// Cerrar sesión
AuthModel::logout();

// Redirigir al login
header('Location: ' . URL_SERV . '/dw-panel/login.php');
exit;

