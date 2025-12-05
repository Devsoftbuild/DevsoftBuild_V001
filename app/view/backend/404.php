<?php
/**
 * Página 404 del Panel
 */
$page_title = 'Página no encontrada';
$current_page = '';
$content = '
<div style="text-align: center; padding: 60px 20px;">
    <i class="fas fa-exclamation-triangle" style="font-size: 5rem; color: #F39C12; margin-bottom: 20px;"></i>
    <h1 style="font-size: 2.5rem; margin-bottom: 15px; color: #2C3E50;">404</h1>
    <p style="font-size: 1.2rem; color: #7F8C8D; margin-bottom: 30px;">Página no encontrada</p>
    <a href="' . URL_SERV . '/dw-panel/" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #9B59B6, #7D3C98); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
        <i class="fas fa-home"></i> Volver al Dashboard
    </a>
</div>
';
include BASE_PATH . '/app/view/backend/includes/layout.php';
?>

