<?php
/**
 * Página de Contacto
 * DevSoft Build SAC
 */

// Configuración de la página
$page_title = 'Contacto';
$page_description = 'Contáctanos para tu proyecto de desarrollo. Estamos listos para ayudarte a transformar tus ideas en realidad.';
$current_page = 'contacto';

// Obtener datos de contacto
$email_contacto = ConfigModel::get('email_contacto', 'contacto@devsoftbuild.com');
$telefono_contacto = ConfigModel::get('telefono_contacto', '');
$direccion_contacto = ConfigModel::get('direccion_contacto', '');
$horario_atencion = ConfigModel::get('horario_atencion', 'Lunes a Viernes: 9:00 AM - 6:00 PM');

// Contenido de la página
$content_file = TEMPLATE_PATH . '/pages/contacto_content.php';

// Incluir layout
include TEMPLATE_PATH . '/includes/layout.php';
?>

