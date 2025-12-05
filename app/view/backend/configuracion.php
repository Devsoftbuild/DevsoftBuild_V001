<?php
/**
 * Configuración del Sistema
 * DevSoft Build SAC
 */

$page_title = 'Configuración del Sistema';
$current_page = 'configuracion';
require_once BASE_PATH . '/app/model/ConfigModel.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $clave => $valor) {
        if ($clave !== 'action') {
            $tipo = 'texto';
            if (is_numeric($valor)) $tipo = 'numero';
            elseif ($valor === '1' || $valor === '0') $tipo = 'booleano';
            
            ConfigModel::set($clave, $valor, $tipo);
        }
    }
    $message = 'Configuración guardada correctamente';
    $message_type = 'success';
}

$config_general = ConfigModel::getByGroup('general');
$config_contacto = ConfigModel::getByGroup('contacto');
$config_redes = ConfigModel::getByGroup('redes_sociales');
$config_seo = ConfigModel::getByGroup('seo');

$content_file = BASE_PATH . '/app/view/backend/pages/configuracion_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>
