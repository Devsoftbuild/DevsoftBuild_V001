<?php
/**
 * Página de Blog
 * DevSoft Build SAC
 */

// Configuración de la página
$page_title = 'Blog';
$page_description = 'Artículos sobre desarrollo web, aplicaciones móviles, tecnología y mejores prácticas.';
$current_page = 'blog';

// Por ahora, mostrar mensaje de próximamente
// TODO: Implementar modelo de Artículos cuando esté listo

// Contenido de la página
$content_file = TEMPLATE_PATH . '/pages/blog_content.php';

// Incluir layout
include TEMPLATE_PATH . '/includes/layout.php';
?>

