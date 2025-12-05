<?php
/**
 * Layout Principal del Panel de Administración
 */

if (!defined('BASE_PATH')) {
    die('Acceso directo no permitido');
}

// Variables por defecto
$page_title = isset($page_title) ? $page_title : 'Dashboard';
$current_page = isset($current_page) ? $current_page : 'dashboard';
$extra_css = isset($extra_css) ? $extra_css : [];
$extra_js = isset($extra_js) ? $extra_js : [];

// Incluir header
include BASE_PATH . '/app/view/backend/includes/header.php';
?>

<?php
// El contenido de la página se incluirá aquí
if (isset($content_file)) {
    include $content_file;
} elseif (isset($content)) {
    echo $content;
}
?>

<?php
// Incluir footer
include BASE_PATH . '/app/view/backend/includes/footer.php';
?>

