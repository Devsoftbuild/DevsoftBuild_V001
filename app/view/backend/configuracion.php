<?php
/**
 * Configuración del Sistema
 * DevSoft Build SAC
 */

$page_title = 'Configuración del Sistema';
$current_page = 'configuracion';
require_once BASE_PATH . '/app/model/ConfigModel.php';

/** Clave POST => [grupo_configuracion, tipo_configuracion] (todo texto evita que teléfonos/WhatsApp se guarden como "numero") */
$CONFIG_FORM_FIELDS = [
    'nombre_empresa' => ['general', 'texto'],
    'logo' => ['general', 'texto'],
    'telefono' => ['general', 'texto'],
    'email' => ['general', 'texto'],
    'direccion' => ['general', 'texto'],
    'ciudad' => ['general', 'texto'],
    'pais' => ['general', 'texto'],
    'email_contacto' => ['contacto', 'texto'],
    'telefono_contacto' => ['contacto', 'texto'],
    'whatsapp' => ['contacto', 'texto'],
    'facebook' => ['redes_sociales', 'texto'],
    'instagram' => ['redes_sociales', 'texto'],
    'twitter' => ['redes_sociales', 'texto'],
    'linkedin' => ['redes_sociales', 'texto'],
    'youtube' => ['redes_sociales', 'texto'],
    'github' => ['redes_sociales', 'texto'],
    'meta_titulo_default' => ['seo', 'texto'],
    'meta_descripcion_default' => ['seo', 'texto'],
    'meta_keywords_default' => ['seo', 'texto'],
];

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $clave => $valor) {
        if ($clave === 'action' || !isset($CONFIG_FORM_FIELDS[$clave])) {
            continue;
        }
        if (!is_string($valor)) {
            continue;
        }
        [$grupo, $tipo] = $CONFIG_FORM_FIELDS[$clave];
        ConfigModel::set($clave, $valor, $tipo, $grupo);
    }
    $message = 'Configuración guardada correctamente';
    $message_type = 'success';
}

ConfigModel::repairGruposConocidos();
// Teléfonos y URLs deben leerse como texto en el formulario (datos antiguos guardados como "numero")
ConfigModel::normalizarTiposTextoFormulario();

$config_general = ConfigModel::getByGroup('general');
$config_contacto = ConfigModel::getByGroup('contacto');
$config_redes = ConfigModel::getByGroup('redes_sociales');
$config_seo = ConfigModel::getByGroup('seo');

$content_file = BASE_PATH . '/app/view/backend/pages/configuracion_content.php';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>
