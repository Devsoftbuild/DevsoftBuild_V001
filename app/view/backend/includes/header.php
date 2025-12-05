<?php
/**
 * Header del Panel de Administración
 * DevSoft Build SAC
 */
if (!defined('BASE_PATH')) {
    die('Acceso directo no permitido');
}

$current_user = AuthModel::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Panel de Administración - <?php echo APP_NAME; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo URL_SERV; ?>/app/public_root/img/favicon.ico">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URL_SERV; ?>/dw-panel/assets/css/admin.css">
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <a href="<?php echo URL_SERV; ?>/dw-panel/" class="sidebar-logo">
                <i class="fas fa-code"></i>
                <span><?php echo APP_NAME; ?></span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/" class="nav-item <?php echo (isset($current_page) && $current_page == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-section">
                    <span class="nav-section-title">Contenido</span>
                </li>
                
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/servicios" class="nav-item <?php echo (isset($current_page) && $current_page == 'servicios') ? 'active' : ''; ?>">
                        <i class="fas fa-laptop-code"></i>
                        <span>Servicios</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/proyectos" class="nav-item <?php echo (isset($current_page) && $current_page == 'proyectos') ? 'active' : ''; ?>">
                        <i class="fas fa-project-diagram"></i>
                        <span>Proyectos</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/tecnologias" class="nav-item <?php echo (isset($current_page) && $current_page == 'tecnologias') ? 'active' : ''; ?>">
                        <i class="fas fa-tools"></i>
                        <span>Tecnologías</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/testimonios" class="nav-item <?php echo (isset($current_page) && $current_page == 'testimonios') ? 'active' : ''; ?>">
                        <i class="fas fa-quote-left"></i>
                        <span>Testimonios</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/banners" class="nav-item <?php echo (isset($current_page) && $current_page == 'banners') ? 'active' : ''; ?>">
                        <i class="fas fa-image"></i>
                        <span>Banners</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/blog" class="nav-item <?php echo (isset($current_page) && $current_page == 'blog') ? 'active' : ''; ?>">
                        <i class="fas fa-blog"></i>
                        <span>Blog</span>
                    </a>
                </li>
                
                <li class="nav-section">
                    <span class="nav-section-title">Gestión</span>
                </li>
                
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/contactos" class="nav-item <?php echo (isset($current_page) && $current_page == 'contactos') ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i>
                        <span>Contactos</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/cotizaciones" class="nav-item <?php echo (isset($current_page) && $current_page == 'cotizaciones') ? 'active' : ''; ?>">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Cotizaciones</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/estadisticas" class="nav-item <?php echo (isset($current_page) && $current_page == 'estadisticas') ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Estadísticas</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/procesos" class="nav-item <?php echo (isset($current_page) && $current_page == 'procesos') ? 'active' : ''; ?>">
                        <i class="fas fa-sitemap"></i>
                        <span>Procesos</span>
                    </a>
                </li>
                
                <li class="nav-section">
                    <span class="nav-section-title">Sistema</span>
                </li>
                
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/usuarios" class="nav-item <?php echo (isset($current_page) && $current_page == 'usuarios') ? 'active' : ''; ?>">
                        <i class="fas fa-users-cog"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo URL_SERV; ?>/dw-panel/configuracion" class="nav-item <?php echo (isset($current_page) && $current_page == 'configuracion') ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i>
                        <span>Configuración</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <!-- Main Content Wrapper -->
    <div class="admin-main-wrapper">
        <!-- Top Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
            </div>
            
            <div class="header-right">
                <a href="<?php echo URL_SERV; ?>" class="header-link" target="_blank" title="Ver sitio web">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Ver Sitio</span>
                </a>
                
                <div class="user-menu" id="userMenu">
                    <button class="user-info" id="userMenuToggle" aria-label="Menú de usuario">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($current_user['nombre'] ?? 'Admin'); ?></span>
                        <i class="fas fa-chevron-down user-chevron"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <i class="fas fa-user-circle"></i>
                            <div>
                                <strong><?php echo htmlspecialchars($current_user['nombre'] ?? 'Administrador'); ?></strong>
                                <small><?php echo htmlspecialchars($current_user['email'] ?? 'admin@devsoftbuild.com'); ?></small>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo URL_SERV; ?>/dw-panel/logout.php" class="dropdown-item dropdown-item-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content -->
        <main class="admin-content">
