<?php
/**
 * Header Template
 * DevSoft Build SAC
 */
if (!defined('APP_PATH')) {
    die('Acceso directo no permitido');
}

// Asegurar que ConfigModel esté cargado
if (!class_exists('ConfigModel')) {
    require_once BASE_PATH . '/app/model/ConfigModel.php';
}

// Obtener logo de la configuración
$logo_url = ConfigModel::get('logo', '');
$nombre_empresa = ConfigModel::get('nombre_empresa', APP_NAME);
?>
<header class="header" id="header">
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="navbar-content">
                <!-- Logo -->
                <div class="navbar-brand">
                    <a href="<?php echo URL_SERV; ?>" class="logo">
                        <?php if (!empty($logo_url)): ?>
                            <img src="<?php echo htmlspecialchars($logo_url); ?>" alt="<?php echo htmlspecialchars($nombre_empresa); ?>" class="logo-img">
                        <?php else: ?>
                            <span class="logo-text"><?php echo htmlspecialchars($nombre_empresa); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Menu Desktop -->
                <div class="navbar-menu" id="navbarMenu">
                    <ul class="navbar-nav">
                        <li><a href="<?php echo URL_SERV; ?>" class="nav-link <?php echo (isset($current_page) && $current_page == 'home') ? 'active' : ''; ?>">Inicio</a></li>
                        <li><a href="<?php echo URL_SERV; ?>/servicios" class="nav-link <?php echo (isset($current_page) && $current_page == 'servicios') ? 'active' : ''; ?>">Servicios</a></li>
                        <li><a href="<?php echo URL_SERV; ?>/proyectos" class="nav-link <?php echo (isset($current_page) && $current_page == 'proyectos') ? 'active' : ''; ?>">Proyectos</a></li>
                        <li><a href="<?php echo URL_SERV; ?>/nosotros" class="nav-link <?php echo (isset($current_page) && $current_page == 'nosotros') ? 'active' : ''; ?>">Nosotros</a></li>
                        <li><a href="<?php echo URL_SERV; ?>/blog" class="nav-link <?php echo (isset($current_page) && $current_page == 'blog') ? 'active' : ''; ?>">Blog</a></li>
                        <li><a href="<?php echo URL_SERV; ?>/contacto" class="nav-link <?php echo (isset($current_page) && $current_page == 'contacto') ? 'active' : ''; ?>">Contacto</a></li>
                    </ul>
                </div>
                
                <!-- CTA Button -->
                <div class="navbar-actions">
                    <a href="<?php echo URL_SERV; ?>/cotizar" class="btn btn-primary btn-cta">
                        <i class="fas fa-paper-plane"></i>
                        <span>Cotizar Proyecto</span>
                    </a>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>

