<?php
/**
 * Layout Principal
 * DevSoft Build SAC
 */
if (!defined('APP_PATH')) {
    die('Acceso directo no permitido');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Desarrollo de software, aplicaciones web y móviles. Soluciones tecnológicas innovadoras para tu negocio.'; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo URL_SERV; ?>/app/public_root/img/favicon.ico">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>/assets/css/main.css">
    
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <?php include TEMPLATE_PATH . '/includes/header.php'; ?>
    
    <main id="main-content">
        <?php
        // Mostrar mensaje flash si existe
        $flash = getFlashMessage();
        if ($flash):
        ?>
            <div class="flash-message flash-<?php echo $flash['type']; ?>">
                <div class="container">
                    <p><?php echo htmlspecialchars($flash['message']); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Cargar contenido de la página
        if (isset($content_file) && file_exists($content_file)) {
            include $content_file;
        } elseif (isset($content)) {
            echo $content;
        }
        ?>
    </main>
    
    <?php include TEMPLATE_PATH . '/includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="<?php echo TEMPLATE_URL; ?>/assets/js/main.js"></script>
    
    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>


